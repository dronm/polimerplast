<?php
class ExtProg{

	private static function parseHeaders($headers){
	    $head = array();
	    foreach( $headers as $k=>$v )
	    {
		$t = explode( ':', $v, 2 );
		if( isset( $t[1] ) )
		    $head[ trim($t[0]) ] = trim( $t[1] );
		else{
		    $head[] = $v;
		    if( preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out ) )
		        $head['reponse_code'] = intval($out[1]);
		        $head['reponse_descr'] = $v;
		}
	    }
	    return $head;
	}
	
	private static function send_query($cmd,$params,&$xml,$fileResp=FALSE,$fileExt='pdf'){
		$CON_TIMEOUT = 300;		
		/*
		$par_str = '';
		foreach($params as $name=>$val){
			$par_str.= '&'.$name.'='.urlencode($val);
		}
		throw new Exception('http://'.HOST_1C.'/API1c.php?cmd='.$cmd.$par_str);
		set_time_limit($CON_TIMEOUT);
		$res = @fopen('http://'.HOST_1C.'/API1c.php?cmd='.$cmd.$par_str,'r');
		if (!$res) {
			throw new Exception('Ошибка соединения с сервером 1с');
		}
		stream_set_timeout($res,$CON_TIMEOUT);		
		$contents = '';
		while (!feof($res)) {
		  $contents .= fread($res, 8192);
		}
		fclose($res);
		*/
		
		$params['cmd'] = $cmd;
		$options = array(
			'http' => array(
				'method'  => 'POST',
				'header'  => array(
					'Content-type: application/x-www-form-urlencoded; charset="utf-8"'
					),
				'content' => http_build_query($params)
			)
		);
		$context = stream_context_create($options);
		$contents = file_get_contents('http://'.HOST_1C.':'.PORT_1C.'/API1c.php', FALSE, $context);
		
		$header_res = self::parseHeaders($http_response_header);
		if ($header_res['reponse_code']&&$header_res['reponse_code']!=200){
			throw new Exception($header_res['reponse_descr']);
		}
		
		//ответ всегда в ANSI		
		//throw new Exception($contents);
		
		if ($fileResp){
			//throw new Exception($contents);
			$f = OUTPUT_PATH.uniqid().'.'.$fileExt;
			file_put_contents($f,$contents);
			return $f;
		}
		else{
			$contents=@iconv('Windows-1251','UTF-8',$contents);
			try{
				$xml = new SimpleXMLElement($contents);					
			}
			catch(Exception $e){
				throw new Exception('Ошибка в XML ответе из 1с, содержание: '.$contents.', ошибка: '.$e->getMessage());
			}
			
			if ($xml['status']=='false'){
				$e = (string) $xml->error;
				throw new Exception($e);
			}					
		}
	}

	public static function getClientRefOnINN($inn){
		$xml=null;
		ExtProg::send_query('get_client_on_inn',array('inn'=>$inn),$xml);
		return (string) $xml->ref[0];
	}
	public static function getFirmRefOnName($name){
		$xml=null;
		ExtProg::send_query('get_firm_on_name',array('name'=>$name),$xml);
		return (string) $xml->ref[0];
	}	
	public static function getPersonRefOnName($name){
		$xml=null;
		ExtProg::send_query('get_person_on_name',array('name'=>$name),$xml);
		return (string) $xml->ref[0];
	}	
	public static function getUserRefOnName($name){
		$xml=null;
		ExtProg::send_query('get_user_on_name',array('name'=>$name),$xml);
		return (string) $xml->ref[0];
	}	
	public static function getProductGroupRefOnName($name){
		$xml=null;
		ExtProg::send_query('get_product_group_on_name',array('name'=>$name),$xml);
		return (string) $xml->ref[0];
	}	
	
	public static function getClientActivityRefOnName($name){
		$xml=null;
		ExtProg::send_query('get_client_activity_on_name',array('name'=>$name),$xml);
		return (string) $xml->ref[0];
	}	
	
	public static function getWarehouseRefOnName($name){
		$xml=null;
		ExtProg::send_query('get_warehouse_on_name',array('name'=>$name),$xml);
		return (string) $xml->ref[0];
	}	
	public static function getMeasureRefOnName($name,&$res){
		$xml=null;
		ExtProg::send_query('get_measure_on_name',array('name'=>$name),$xml);
		$res['ext_ref'] = (string) $xml->ref[0];
		$res['name_full'] = (string) $xml->name_full[0];
	}	
	
	public static function addClient($struc){
		$xml=null;
		ExtProg::send_query('add_client',$struc,$xml);
		return (string) $xml->ref[0];
	}	
	public static function completeClient($pattern,&$struc){
		$xml=null;
		ExtProg::send_query('complete_client',array('templ'=>$pattern),$xml);
		foreach($xml->ref as $ref){
			$struc[(string) $ref[0]]=$ref['name'];
		}
	}
	public static function completeUser($pattern,&$struc){
		$xml=null;
		ExtProg::send_query('complete_user',array('templ'=>$pattern),$xml);
		foreach($xml->ref as $ref){
			$struc[(string) $ref[0]]=$ref['name'];
		}
	}
	
	public static function getClientAttrsOnName($name,&$struc){
		$xml=null;
		ExtProg::send_query('get_client_attrs_on_name',array('name'=>$name),$xml);
		$i=0;
		
		$struc['found'] = 0;
		if ($xml->attrs){
			foreach ($xml->attrs->children() as $attr=>$data) {
				//throw new Exception($attr);		
				if ($i==0){
					$struc['found'] = 1;
				}
				$struc[$attr]=$data;
				$i++;
			}
		}
	}
	public static function getDebtList(&$res){
		$xml=null;
		ExtProg::send_query('get_debt_list',array(),$xml);
		foreach ($xml->rec as $rec){
			array_push($res,array(
				'firmRef'=>(string) $rec->firmRef,
				'clientRef'=>(string)$rec->clientRef,
				'debt'=>(float) $rec->debt
			));
		}
	}
	
	public static function firm_data($firmRef){
		$xml=null;
		ExtProg::send_query('firm_data',array('firm_ref'=>$firmRef),$xml);
		return (string) $xml->org_data;
	}	
	
	public static function sale($head,$items,&$res){
		$xml=null;
		ExtProg::send_query('sale',
			array('head'=>serialize($head),
			'items'=>serialize($items)
			),
			$xml);
		$res['naklRef'] = $xml->naklRef;
		$res['naklNum'] = $xml->naklNum;
		$res['invRef'] = $xml->invRef;
		$res['invNum'] = $xml->invNum;
	}
	public static function order($head,$items,&$res){
		$xml=null;
		ExtProg::send_query('order',
			array('head'=>serialize($head),
			'items'=>serialize($items)
			),
			$xml);
		$res['orderRef'] = $xml->orderRef;
		$res['orderNum'] = $xml->orderNum;
	}
	public static function print_order($ref,$userExtRef='',$stamp=0){
		$xml=null;
		return ExtProg::send_query('print_order',
			array('doc_ref'=>$ref,
				'stamp'=>$stamp,
				'user_ref'=>$userExtRef),
			$xml,TRUE);
	}	
	public static function print_torg12($ref,$userExtRef='',$stamp=0){
		$xml=null;
		return ExtProg::send_query('print_torg12',
			array('doc_ref'=>$ref,
				'stamp'=>$stamp,
				'user_ref'=>$userExtRef),
			$xml,TRUE);
	}	
	public static function print_invoice($ref,$userExtRef=''){
		$xml=null;
		return ExtProg::send_query('print_invoice',
			array('doc_ref'=>$ref,
				'user_ref'=>$userExtRef
			),$xml,TRUE);
	}	
	public static function print_upd($ref,$userExtRef='',$stamp=0){
		$xml=null;
		return ExtProg::send_query('print_upd',
			array('doc_ref'=>$ref,
				'user_ref'=>$userExtRef,
				'stamp'=>$stamp),
			$xml,TRUE);
	}		
	public static function print_shipment($ref,$head,$userExtRef='',$stamp=0){
		$xml=null;
		return ExtProg::send_query('print_shipment',
			array('doc_ref'=>$ref,
				'head'=>serialize($head),
				'user_ref'=>$userExtRef,
				'stamp'=>$stamp),
			$xml,TRUE,'zip');
	}			
	public static function print_ttn($ref,$head,$userExtRef='',$stamp=0){
		$xml=null;
		return ExtProg::send_query('print_ttn',
			array('doc_ref'=>$ref,
				'head'=>serialize($head),
				'user_ref'=>$userExtRef,
				'stamp'=>$stamp),
			$xml,TRUE);
	}			
	public static function print_balance($from,$to,$clientRef,$firmRef,$userExtRef='',$fileType='pdf'){
		$xml=null;
		return ExtProg::send_query('print_balance',
			array(
				'to'=>$to,
				'from'=>$from,
				'firm_ref'=>$firmRef,
				'client_ref'=>$clientRef,
				'user_ref'=>$userExtRef,
				'file_type'=>$fileType
			),$xml,TRUE,$fileType);
	}	
	public static function paid_to_acc($firm_totals){
		$xml=null;
		ExtProg::send_query('paid_to_acc',
			array('head'=>serialize($firm_totals)),
			$xml);
	}			
	
}
?>
