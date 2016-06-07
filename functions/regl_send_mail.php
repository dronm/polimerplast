<?php
require_once(dirname(__FILE__)."/../Config.php");
require_once(FRAME_WORK_PATH."db/db_pgsql.php");
require_once("PPEmailSender.php");

PPEmailSender::sendAllMail(TRUE);
?>
