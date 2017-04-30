<?php
/**
* @author Reza J. https://www.facebook.com/ppabcd <rezajuliandri20@gmail.com>
* @license Kyla-AI (c) 2017
*/
/**
 * Process Class
 */


class Process
{
   protected $ai;
   protected $cmd;
   function __construct()
   {
      $this->ai = new AI;
      $this->cmd = new Command;
   }
   function chat($chat){
      $ai = $this->ai;
      $cmd = $this->cmd;
      //Get trim data chat
      $chat = trim($chat);
      //Command Admin
      $cmd_admin = $this->cmd_admin();
      //Explode Chat data
      $chat_ex = $this->data_explode($chat);
      //Get Public data Command
      $class = new ReflectionClass('Command');
      $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
      foreach ($methods as $key) {
         $method[] = $key->name;
      }
      //var_dump($method);
      if(in_array($chat_ex[0],$method)){
         if(in_array($chat_ex[0],$cmd_admin)){
            $ai->add_chat($chat,1);
         }
         //Mengambil string yang nantinya digunakan untuk command

         $meth = explode($chat_ex[0],$chat);
         $cmd_process = $cmd->{$chat_ex[0]}($meth[1]);
         if(is_string($cmd_process)){
            $ai->add_chat("Command sudah diproses.",2);
            return $cmd_process;
         }
         if($cmd_process){
            $ai->add_chat("Command sudah diproses.",2);
            $translate = $cmd->translate(" Command+sudah+diproses.",1);
            return "0 | 2 | ".$translate;
         }
         else {
            $ai->add_chat("Command gagal di proses.",2);
            $translate = $cmd->translate(" Command+gagal+di+proses.",1);
            return "0 | 2 | ".$translate;
         }
      }
      else {
         //Jika bukan command
         if($ai->filter_kata($chat)){
            //Jika lulus filter
            $ai_chat = $ai->get_messages($chat);
            $ai->add_chat($chat,1);
            if($ai_chat){
               $ai_chat =$ai->decode_kalimat($ai_chat);
               $ai_chat = str_replace(array_keys($this->special_word()),array_values($this->special_word()),$ai_chat);
               $ai->add_chat($ai_chat,2);
               $chat_tr = str_replace(" ","+",$ai_chat);
               $translate = $cmd->translate(" ".$chat_tr,1);
               return $ai->encode_kalimat($chat)." | 2 | ".$translate;
            }
            else{
               $translate = $cmd->translate(" Aku+tidak+mengerti,+mohon+ajari+aku+jawabannya+dibawah",1);
               $ai->add_chat("Aku tidak mengerti, mohon ajari aku jawabannya dibawah",2);
               return $ai->encode_kalimat($chat)." | 1 | ".$translate;
            }
         }
         else {
            $translate = $cmd->translate(" Mohon+maaf+kata+tersebut+tidak+dapat+ditampilkan.",1);
            $ai->add_chat("Mohon maaf kata tersebut tidak dapat ditampilkan.",2);
            return "0 | 2 | ".$translate;
         }
      }
   }
   function response($respon,$kalimat){
      $ai = $this->ai;
      $cmd = $this->cmd;
      $respon = trim($_POST['respon']);
      $kalimat = trim($_POST['kalimat']);
      if($ai->filter_kata($respon)):
         if($ai->add_response(trim($respon),$kalimat)){
            $ai->add_chat("Terima kasih sudah mengajari",2);
            $translate = $cmd->translate(" Terima+kasih+sudah+mengajari.",1);
         }
         else {
            $ai->add_chat("Ada kesalahan pada server",2);
            $translate = $cmd->translate(" Ada+kesalahan+pada+server.",1);

         }
      else :
         $ai->add_chat("Aku tidak mau diajari kata itu",2);
         $translate = $cmd->translate(" Aku+tidak+mau+diajari+kata+itu.",1);
      endif;
      return $translate;
   }
   function special_word(){
      $jam = date("h:i")." ".date("A");
      $tanggal = date("d-m-Y");
      return [
         "[name]"=>"User",
         "[jam]"=>$jam,
         "[tanggal]"=>$tanggal
      ];
   }
   function cmd_admin(){
      return ["testing","system_status","status","clear_all","add_filter"];
   }
   function data_explode($data){
      return explode(" ",strtolower($data));
   }
}
