<?php
ob_start("ob_gzhandler");
/*
require_once("./class/AI.php");
require_once("./class/Command.php");
$ai = new AI;
$cmd = new Command;
if(isset($_POST['chat'])){
   $chat = trim($_POST['chat']);
   $chat_arr = explode(" ",strtolower($chat));
   if(in_array("cmd",$chat_arr)){
      $chat_arr2 = explode("cmd",strtolower($chat));
      if($chat_arr2[1] == null){
         $ai->add_chat("Command yang anda masukkan tidak benar.",2);
         echo "0 | 2";
         die();
      }
      $class = new ReflectionClass('Command');
      $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
      foreach ($methods as $key) {
         $method[] = $key->name;
      }
      $command = explode(" ",$chat_arr2[1]);
      if(!in_array($command[1],$method)){
         $ai->add_chat("Command yang anda masukkan tidak benar.",2);
         echo "0 | 2";
         die();
      }
      //Melakukan command sesuai class.
      if(!in_array($command[1],$cmd_admin)){
         $ai->add_chat($chat,1);
      }
      $meth = explode($command[1],$chat_arr2[1]);
      $cmd_process = $cmd->{$command[1]}($meth[1]);
      $cmd_admin = ["testing","system_status","status","clear_all","add_filter"];
      if(is_string($cmd_process)){
         $ai->add_chat("Command sudah diproses.",2);
         echo $cmd_process;
         die();
      }
      if($cmd_process){
         $ai->add_chat("Command sudah diproses.",2);
         echo "0 | 2";
      }
      else {
         $ai->add_chat("Command gagal di proses.",2);
         echo "0 | 2";
      }
   }
   else {
      if($chat == "delete"){
         $ai->truncate("chat");
         $ai->add_chat("Chat Berhasil dihapus",2);
      }
      else {
         if($ai->filter_kata($chat)){
            //Mengambil dalam bentuk kalimat
            $ai_chat = $ai->get_messages($chat);
            $ai->add_chat($chat,1);
            if($ai_chat){
               $ai_chat = $ai->decode_kalimat($ai_chat);
               $ai_chat = str_replace("[name]","Reza",$ai_chat);
               $jam = date("h:i")." ".date("A");
               $tanggal = date("d-m-Y");
               $ai_chat = str_replace("[jam]",$jam,$ai_chat);
               $ai_chat = str_ireplace("[tanggal]",$tanggal,$ai_chat);
               $ai->add_chat($ai_chat,2);
               echo $ai->encode_kalimat($chat)." | 2";
            }
            else {
               $ai->add_chat("Aku tidak mengerti, mohon ajari aku jawabannya dibawah",2);
               echo $ai->encode_kalimat($chat)." | 1";
            }
         }
         else {
            $ai->add_chat("Mohon maaf kata tersebut tidak dapat ditampilkan.",2);
            echo "0 | 2";
         }
      }
   }
}
//Jika mode mengajari
if(isset($_POST['respon'])){
   $respon = trim($_POST['respon']);
   $kalimat = trim($_POST['kalimat']);
   if($ai->filter_kata($respon)):
      if($ai->add_response(trim($respon),$kalimat)){
         $ai->add_chat("Terima kasih sudah mengajari",2);
      }
      else {
         $ai->add_chat("Ada kesalahan pada server",2);
      }
      echo "Answare";
   else :
      $ai->add_chat("Aku tidak mau diajari kata itu",2);
   endif;
}
/*
<form class="" action="" method="post">
   <input type="text" name="chat" value="">
</form>

*/
//$cmd = new Command;
require_once("./class/Process.php");
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
