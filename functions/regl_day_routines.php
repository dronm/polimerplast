<?php
require_once('db_con.php');
require_once('PPEmailSender.php');

$ar = $dbLink->query_first(
"SELECT
	MAX(t.id) AS max_id
FROM doc_orders_states t");

//Закрытие несгласованных заявок
$dbLink->query("SELECT doc_orders_to_archive()");

$id = $dbLink->query(
"SELECT
	t.doc_orders_id AS doc_id
FROM doc_orders_states t
WHERE t.state='canceled_by_sales_manager'
	AND t.id>".$ar['max_id']);

//отправим собщения
while ($ar = $dbLink->fetch_array($id)){
	PPEmailSender::addEMail(
		$dbLink,
		sprintf("email_text_order_cancel(%d)",$ar['doc_id']),
		NULL,
		'order_cancel'
		);
}
?>