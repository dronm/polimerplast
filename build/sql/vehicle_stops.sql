-- Function: vehicle_stops(in_date_time_from timestamp without time zone, in_date_time_to timestamp without time zone, stop_dur_interval interval, in_vehicle_id int)

-- DROP FUNCTION vehicle_stops(in_date_time_from timestamp without time zone, in_date_time_to timestamp without time zone, stop_dur_interval interval, in_vehicle_id int);

CREATE OR REPLACE FUNCTION vehicle_stops(in_date_time_from timestamp without time zone, in_date_time_to timestamp without time zone, stop_dur_interval interval, in_vehicle_id int)
RETURNS TABLE(
	vh_id int,
	vh_descr text,
	lon character varying,
	lat character varying,	
	date_time timestamp without time zone,
	date_time_descr text,
	duration interval
) AS
$BODY$
DECLARE tr_stops_row RECORD;
	v_stop_started boolean;
BEGIN
	vh_id = NULL;
	FOR tr_stops_row IN	
		SELECT 
			vh.id AS vh_id, 
			vh.plate::text AS vh_descr,
			tr.period+age(now(),now() at time zone 'UTC') AS date_time,
			tr.longitude,
			tr.latitude,
			tr.speed
		FROM car_tracking AS tr
		LEFT JOIN vehicles vh ON vh.tracker_id = tr.car_id
		WHERE tr.period+age(now(),now() at time zone 'UTC') BETWEEN in_date_time_from AND in_date_time_to
		AND ( (in_vehicle_id<>0 AND vh.id=in_vehicle_id) OR (in_vehicle_id=0) )
		AND tr.gps_valid=1
		ORDER BY tr.car_id,tr.period
	
	LOOP
		IF vh_id IS NULL OR vh_id<>tr_stops_row.vh_id THEN
			--new vehicle
			v_stop_started = FALSE;
		END IF;
		
		IF NOT v_stop_started AND tr_stops_row.speed=0 THEN	
			--new stop - check duration
			v_stop_started = true;
			vh_id		= tr_stops_row.vh_id;
			vh_descr	= tr_stops_row.vh_descr;
			date_time	= tr_stops_row.date_time;
			date_time_descr	= date8_time5_descr(tr_stops_row.date_time)::text;
			lon		= tr_stops_row.longitude;
			lat		= tr_stops_row.latitude;
		ELSIF v_stop_started AND tr_stops_row.speed>0 THEN	
			duration = tr_stops_row.date_time - date_time;
			--RAISE 'duration=%',duration;
			IF duration>=stop_dur_interval THEN
				--end of stop
				v_stop_started = false;
			
				RETURN NEXT;
			END IF;
		END IF;
		
	END LOOP;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;
ALTER FUNCTION vehicle_stops(in_date_time_from timestamp without time zone, in_date_time_to timestamp without time zone, stop_dur_interval interval, in_vehicle_id int)
  OWNER TO polimerplast;

