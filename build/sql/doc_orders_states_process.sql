-- Function: doc_orders_states_process()
-- DROP FUNCTION doc_orders_states_process();

CREATE OR REPLACE FUNCTION doc_orders_states_process()
  RETURNS trigger AS
$BODY$
BEGIN
	IF (TG_WHEN='BEFORE' AND TG_OP='DELETE') THEN
		DELETE FROM doc_orders_head_history
		WHERE doc_orders_states_id=OLD.id;
		
		DELETE FROM doc_orders_products_history
		WHERE doc_orders_states_id=OLD.id;
		
		DELETE FROM client_pay_schedules
		WHERE doc_id=OLD.doc_orders_id;
		
		RETURN OLD;
	END IF;
END;
$BODY$
LANGUAGE plpgsql VOLATILE COST 100;
ALTER FUNCTION doc_orders_states_process() OWNER TO polimerplast;



-- Trigger: doc_orders_states_trigger on doc_orders_states
-- DROP TRIGGER doc_orders_states_trigger ON doc_orders_states;

CREATE TRIGGER doc_orders_states_trigger
  BEFORE DELETE
  ON doc_orders_states
  FOR EACH ROW
  EXECUTE PROCEDURE doc_orders_states_process();