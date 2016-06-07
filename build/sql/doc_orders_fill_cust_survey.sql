-- Function: doc_orders_fill_cust_survey(integer)

-- DROP FUNCTION doc_orders_fill_cust_survey(integer);

CREATE OR REPLACE FUNCTION doc_orders_fill_cust_survey(
	IN in_login_id integer)
  RETURNS void AS
$BODY$
	--Удалим все строки
	DELETE FROM doc_orders_t_tmp_cust_surveys
	WHERE login_id=$1;
	
	INSERT INTO doc_orders_t_tmp_cust_surveys
	(login_id,line_number,customer_survey_question_id)
	(SELECT
		$1,
		row_number() OVER (ORDER BY question),
		id
	FROM customer_survey_questions
	WHERE in_use=true
	ORDER BY question);
	
$BODY$
  LANGUAGE sql VOLATILE
  COST 100;
ALTER FUNCTION doc_orders_fill_cust_survey(integer) OWNER TO polimerplast;
