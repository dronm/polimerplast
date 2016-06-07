-- Trigger: warehouses_trigger on product_warehouses

-- DROP TRIGGER warehouses_trigger ON warehouses;

CREATE TRIGGER warehouses_trigger
  AFTER UPDATE OR DELETE
  ON warehouses FOR EACH ROW
  EXECUTE PROCEDURE warehouses_process();
