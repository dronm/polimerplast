-- Function: warehouse_list_for_order(in_login_id int,in_product_id int)

-- DROP FUNCTION warehouse_list_for_order(in_login_id int,in_product_id int);

CREATE OR REPLACE FUNCTION warehouse_list_for_order(in_login_id int,in_product_id int)
  RETURNS Table(
	id int,
	name text
  ) AS
$BODY$
	(SELECT
		w.id,
		w.name::text
	FROM warehouses w
	WHERE $2=0)
	
	UNION
	
	(SELECT
		DISTINCT(sub.warehouse_id),
		w.name::text
	FROM	
	(
	(SELECT pw.warehouse_id
	FROM product_warehouses AS pw
	WHERE pw.product_id=$2
		/*
		AND pw.warehouse_id=ANY(
			SELECT DISTINCT(pw.warehouse_id)
			FROM product_warehouses AS pw
			WHERE pw.product_id = ANY(
				SELECT t.product_id
				FROM doc_orders_t_tmp_products AS t
				WHERE t.login_id=$1
				)		
			)
		*/
	)
	/*
	UNION

	(SELECT DISTINCT(pw.warehouse_id)
	FROM product_warehouses AS pw
	WHERE pw.product_id = ANY(
		SELECT t.product_id
		FROM doc_orders_t_tmp_products AS t
		WHERE t.login_id=$1
		)
	)
	*/
	) AS sub
	LEFT JOIN warehouses AS w ON w.id=sub.warehouse_id
	WHERE $2>0
	ORDER BY w.name::text)
	;
$BODY$
  LANGUAGE sql
  COST 100;
ALTER FUNCTION warehouse_list_for_order(in_login_id int,in_product_id int)
OWNER TO polimerplast;
