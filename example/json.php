<?php
/**
* @author Reza J. https://www.facebook.com/ppabcd <rezajuliandri20@gmail.com>
* @license Kyla-AI (c) 2017
*/
header('Content-Type: application/json');
require_once("../class/autoload.php");
$ai = new AI;
if(isset($_GET['chat'])){
   echo $ai->list_chat();
}
if(isset($_GET['filter'])){
   echo $ai->list_filter();
}
if(isset($_GET['kalimat'])){
   echo $ai->list_kalimat();
}
