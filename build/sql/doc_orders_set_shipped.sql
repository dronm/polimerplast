-- Function: doc_orders_set_shipped(integer, varchar(32))

-- DROP FUNCTION doc_orders_set_shipped(integer, varchar(32));

CREATE OR REPLACE FUNCTION doc_orders_set_shipped(
		in_doc_id integer,
		in_view_id varchar(32))
  RETURNS void AS
$BODY$
DECLARE
    r RECORD;
	v_new_doc_id integer;
	v_new_prod_k numeric;
	v_quant numeric;
	v_base_quant numeric;
	v_total numeric;
	v_total_pack numeric;
	v_volume numeric;
	v_weight numeric;	
	v_new_doc_user_id integer;
	--
	v_pay_day  date;	
	v_firm_id integer;
	v_client_id integer;
	
BEGIN
	--всегда оплачено
	--UPDATE doc_orders SET paid=TRUE WHERE id=in_doc_id;

	--clear fact table
	DELETE FROM doc_orders_t_products WHERE doc_id=in_doc_id;

	v_new_doc_id = 0;
	FOR r IN
	SELECT t.*,
		p.base_measure_unit_vol_m,
		p.base_measure_unit_weight_t
	FROM doc_orders_t_tmp_products AS t
	LEFT JOIN products p ON t.product_id=p.id
	WHERE view_id = in_view_id
	ORDER BY t.line_number
	LOOP
		/*
		Если в документе было больше,чем подтвердили
		создадим из данного документа еще один
		если меньше - нового докум.не создаем
		*/
		IF (r.quant_confirmed_base_measure_unit<
		r.quant_base_measure_unit) THEN
			--Новый документ
			v_new_prod_k = 1 - r.quant_confirmed_base_measure_unit/r.quant_base_measure_unit;
			IF v_new_doc_id=0 THEN
				--Создадим шапку нового документа
				INSERT INTO doc_orders
				(date_time,
				client_id,
				delivery_plan_date,
				firm_id,
				user_id,
				sales_manager_comment,
				warehouse_id,
				deliv_type,
				deliv_to_third_party,
				deliv_send_sms,
				deliv_add_cost_to_product,
				deliv_period_id,
				deliv_responsable,
				deliv_destination_id,
				client_user_id,
				deliv_total_edit,
				deliv_cost_opt_id,
				deliv_responsable_tel,
				deliv_total
				)
				(
				SELECT
					h.date_time,
					h.client_id,
					h.delivery_plan_date,
					h.firm_id,
					h.user_id,
					
					coalesce(h.sales_manager_comment,'')||
						CASE
							WHEN empty(h.sales_manager_comment) THEN
								''
							ELSE ' '
						END
					||'Остатки по заявке №'||h.number,
					
					h.warehouse_id,
					h.deliv_type,
					h.deliv_to_third_party,
					h.deliv_send_sms,
					h.deliv_add_cost_to_product,
					h.deliv_period_id,
					h.deliv_responsable,
					h.deliv_destination_id,
					h.client_user_id,
					h.deliv_total_edit,
					h.deliv_cost_opt_id,
					h.deliv_responsable_tel,
					--ROUND(h.deliv_total*v_new_prod_k,2)
					0
				FROM doc_orders AS h
				WHERE h.id=in_doc_id
				)
				RETURNING id,user_id
					INTO v_new_doc_id,
						v_new_doc_user_id;
				
				--Отметим связку
				INSERT INTO doc_orders_links
				VALUES (in_doc_id,v_new_doc_id);
				
				--Статус документа
				INSERT INTO doc_orders_states
				(doc_orders_id,date_time,state,user_id)
				VALUES (v_new_doc_id,
					now()::timestamp,
					'waiting_for_us'::order_states,
					v_new_doc_user_id);
			END IF;
			
			/*Неподтвержденное количество
			в новую строку нового документа
			*/
			
			--базовое количество
			v_base_quant = 	r.quant_base_measure_unit-
				r.quant_confirmed_base_measure_unit;

			--количество в единице документа
			SELECT doc_order_calc_quant(
						r.product_id,
						r.measure_unit_id,
						r.mes_length,
						r.mes_width,
						r.mes_height,
						v_base_quant
			) INTO v_quant;	
			v_total = round(r.price*v_base_quant,2);
			--RAISE 'r.total=%,v_new_prod_k=%',r.total,v_new_prod_k;
			v_volume = round(v_base_quant*r.base_measure_unit_vol_m,3);
			v_weight = round(v_base_quant*r.base_measure_unit_weight_t,3);
			
			INSERT INTO doc_orders_t_products
				(doc_id,
				line_number,
				product_id,
				quant,
				price,
				total,
				mes_length,
				mes_width,
				mes_height,
				measure_unit_id,
				pack_exists,
				pack_in_price,
				quant_base_measure_unit,
				quant_confirmed_base_measure_unit,
				volume,
				weight,
				price_edit,
				total_pack)
			VALUES (
				v_new_doc_id,
				r.line_number,
				r.product_id,
				
				/*количе-во в единице
				основного документа
				*/
				v_quant,				
				--цена из основного документа
				r.price,				
				v_total,
				
				r.mes_length,
				r.mes_width,
				r.mes_height,
				r.measure_unit_id,
				r.pack_exists,
				r.pack_in_price,
				
				/*в новый док остаток*/
				v_base_quant,
				v_base_quant,
					
				v_volume,
				v_weight,
				
				r.price_edit,
				round(r.total_pack*v_new_prod_k,2)
			);
			
		END IF;
		

		--количество в единице документа
		IF (r.quant_confirmed_base_measure_unit=
			r.quant_base_measure_unit) THEN
			--все как в основном документе
			v_quant		= r.quant;
			v_volume 	= r.volume;
			v_weight 	= r.weight;
			v_total		= r.total;
			v_total_pack= r.total_pack;
		ELSE
			--расчетным путем
			SELECT doc_order_calc_quant(
					r.product_id,
					r.measure_unit_id,
					r.mes_length,
					r.mes_width,
					r.mes_height,
					r.quant_confirmed_base_measure_unit
			) INTO v_quant;
			v_volume = round(
				r.quant_confirmed_base_measure_unit*
				r.base_measure_unit_vol_m,3);
			v_weight = round(r.quant_confirmed_base_measure_unit*
				r.base_measure_unit_weight_t,3);
			v_total = round(r.total/r.quant_base_measure_unit*r.quant_confirmed_base_measure_unit,2);
			v_total_pack = round(r.total_pack/r.quant_base_measure_unit*r.quant_confirmed_base_measure_unit,2);
		END IF;
		
		/* То что подтверждено в реальную
		таблицу */
		INSERT INTO doc_orders_t_products
			(doc_id,
			line_number,
			product_id,
			quant,
			price,
			total,
			mes_length,
			mes_width,
			mes_height,
			measure_unit_id,
			pack_exists,
			pack_in_price,
			quant_base_measure_unit,
			quant_confirmed_base_measure_unit,
			volume,
			weight,
			price_edit,
			total_pack)
		VALUES (
			in_doc_id,
			r.line_number,
			r.product_id,			
			v_quant,			
			r.price,			
			v_total,			
			r.mes_length,
			r.mes_width,
			r.mes_height,
			r.measure_unit_id,
			r.pack_exists,
			r.pack_in_price,
			
			r.quant_confirmed_base_measure_unit,
			r.quant_confirmed_base_measure_unit,
			
			v_volume,
			v_weight,
			
			r.price_edit,
			v_total_pack
		);
		
	END LOOP;

	--ИТОГИ по новому документу
	IF (v_new_doc_id>0) THEN
		PERFORM doc_orders_update_totals(v_new_doc_id);
	END IF;
	
	--ИТОГИ по документу
	PERFORM doc_orders_update_totals(in_doc_id);
	
	--ГРАФИК ПЛАТЕЖЕЙ
	v_pay_day = NULL;
	SELECT
		CASE
			WHEN cl.pay_type<>'cash'::payment_types THEN
				pay_date(
					now()::date,
					cl.pay_type,
					cl.pay_delay_days,
					cl.pay_fix_to_dow,
					cl.pay_dow_days
				)
			ELSE NULL
		END AS pay_day,
		d.client_id,
		d.firm_id,
		
		COALESCE(d.total,0)+
		CASE
		WHEN d.deliv_type='by_supplier'::delivery_types
		AND coalesce(d.deliv_add_cost_to_product,FALSE)=FALSE THEN
			COALESCE(d.deliv_total,0)
		ELSE 0
		END+
		COALESCE(d.total_pack,0)		
	INTO
		v_pay_day,
		v_client_id,
		v_firm_id,
		v_total	
	FROM doc_orders AS d
	LEFT JOIN clients AS cl ON cl.id=d.client_id
	WHERE d.id=in_doc_id;
	
	IF (v_pay_day IS NOT NULL) THEN
		INSERT INTO client_pay_schedules
		(doc_id,pay_date,firm_id,client_id,total)
		VALUES
		(in_doc_id,v_pay_day,v_firm_id,v_client_id,v_total);
	END IF;
	
	--ВЗАИМОРАСЧЕТЫ
	UPDATE client_debts
	SET debt_total = debt_total + v_total,
	    update_date = now()
	WHERE
		firm_id = v_firm_id
		AND client_id = v_client_id
	;
	
	RETURN;
END;		
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION doc_orders_set_shipped(integer, varchar(32))
  OWNER TO polimerplast;
