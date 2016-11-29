-- Function: doc_orders_before_open(varchar(32), integer, integer)

-- DROP FUNCTION doc_orders_before_open(varchar(32), integer, integer);

CREATE OR REPLACE FUNCTION doc_orders_before_open(in_view_id varchar(32), in_login_id integer, in_doc_id integer)
  RETURNS void AS
$BODY$
BEGIN
	--DELETE FROM doc_orders_t_tmp_products WHERE login_id=in_login_id;
	DELETE FROM doc_orders_t_tmp_products WHERE view_id=in_view_id;
	
	IF (in_doc_id IS NOT NULL AND in_doc_id>0) THEN
		INSERT INTO doc_orders_t_tmp_products
		(login_id,view_id,line_number,product_id,quant,quant_confirmed,quant_base_measure_unit,quant_confirmed_base_measure_unit,volume,weight,price,price_edit,total,total_pack,mes_length,mes_width,mes_height,measure_unit_id,pack_exists,pack_in_price)
		(SELECT in_login_id, in_view_id
		,line_number,product_id,quant,quant_confirmed,quant_base_measure_unit,quant_confirmed_base_measure_unit,volume,weight,price,price_edit,total,total_pack,mes_length,mes_width,mes_height,measure_unit_id,pack_exists,pack_in_price
		FROM doc_orders_t_products
		WHERE doc_id=in_doc_id);
	END IF;

	--DELETE FROM doc_orders_t_tmp_cust_surveys WHERE login_id=in_login_id;
	DELETE FROM doc_orders_t_tmp_cust_surveys WHERE view_id=in_view_id;
	
	IF (in_doc_id IS NOT NULL AND in_doc_id>0) THEN
	
		INSERT INTO doc_orders_t_tmp_cust_surveys
		(login_id,view_id,line_number,customer_survey_question_id,points,answer_comment)
		(SELECT in_login_id, in_view_id
		,line_number,customer_survey_question_id,points,answer_comment
		FROM doc_orders_t_cust_surveys
		WHERE doc_id=in_doc_id);
	END IF;
	
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION doc_orders_before_open(varchar(32), integer, integer)
  OWNER TO polimerplast;
