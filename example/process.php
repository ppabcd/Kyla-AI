<?php
/**
* @author Reza J. https://www.facebook.com/ppabcd <rezajuliandri20@gmail.com>
* @license Kyla-AI (c) 2017
*/
ob_start("ob_gzhandler");
require_once("../class/autoload.php");
$proses = new Process;
if(isset($_POST['chat'])){
   if(!empty($_POST['chat'])){
      echo $proses->chat($_POST['chat']);
   }
}
if(isset($_POST['respon'])){
   if(!empty($_POST['respon']) && !empty($_POST['kalimat'])){
      echo $proses->response($_POST['respon'],$_POST['kalimat']);
   }
}
?>
