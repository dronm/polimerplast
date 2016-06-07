-- Function: doc_orders_t_tmp_products_process()

-- DROP FUNCTION doc_orders_t_tmp_products_process();

CREATE OR REPLACE FUNCTION doc_orders_t_tmp_products_process()
  RETURNS trigger AS
$BODY$
BEGIN
	IF (TG_WHEN='BEFORE' AND TG_OP='INSERT') THEN
		IF NOT doc_orders_t_tmp_products_can_add(NEW.login_id,NEW.product_id) THEN
			RAISE 'Данный вид продукции невозможно отгрузить с другими видами продукции документа!';
		END IF;
		SELECT coalesce(MAX(t.line_number),0)+1 INTO NEW.line_number FROM doc_orders_t_tmp_products AS t WHERE t.login_id = NEW.login_id;
		RETURN NEW;
	ELSIF (TG_WHEN='AFTER' AND TG_OP='INSERT') THEN
		RETURN NEW;
	ELSIF (TG_WHEN='BEFORE' AND TG_OP='UPDATE') THEN
		RETURN NEW;					
	ELSIF (TG_WHEN='AFTER' AND TG_OP='UPDATE') THEN
		RETURN NEW;									
	ELSIF (TG_WHEN='BEFORE' AND TG_OP='DELETE') THEN
		RETURN OLD;
	ELSIF (TG_WHEN='AFTER' AND TG_OP='DELETE') THEN
		UPDATE doc_orders_t_tmp_products
		SET line_number = line_number - 1
		WHERE login_id=OLD.login_id AND line_number>OLD.line_number;
		RETURN OLD;
	END IF;
END;
$BODY$
LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION doc_orders_t_tmp_products_process()
  OWNER TO polimerplast;
