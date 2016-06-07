-- View: products_filter_list

--DROP VIEW products_filter_list;

CREATE OR REPLACE VIEW products_filter_list AS 
	SELECT 
		p.name AS id,
		p.name
	FROM products AS p
	ORDER BY p.name;
ALTER TABLE products_filter_list OWNER TO polimerplast;

