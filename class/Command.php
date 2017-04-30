<?php
/**
* @author Reza J. https://www.facebook.com/ppabcd <rezajuliandri20@gmail.com>
* @license Kyla AI (c) 2017
*/
require_once("./class/AI.php");
require_once("./class/SaferScript.php");
require_once("./class/Wikipedia.php");
require_once("./class/Weather.php");
/**
 * Command Class
 */
class Command
{
   private $ai;
   private $ver;
   function __construct()
   {
      $this->ai = new AI;
      $this->ver = 1.2;
   }
   public function testing($data){
      $ai = $this->ai;
      $ai->add_chat("Test test 123 System Status : [OK]",2);
      $ai->add_chat("Data ".$data." send to database.",2);
      return true;
   }
   public function system_status($data=null){
      $ai = new AI;
      if($data){
         if($data == "ai"){
            if($ai->status())
            $ai->add_chat("AI Status : [OK]",2);
            return $ai->status();
         }
         else if($data == "command"){
            if($this->status())
            $ai->add_chat("Command Status : [OK]",2);
            return $this->status();
         }
         else {
            return false;
         }
      }
      else {
         if($ai->status())
         $ai->add_chat("AI Status : [OK]",2);
         else return false;
         if($this->status())
         $ai->add_chat("Command Status : [OK]",2);
         else return false;
         return true;
      }
   }
   public function status(){
      return true;
   }
   public function clear_all(){
      $ai = $this->ai;
      $table_list = [
         "kata","kalimat","chat","response"
      ];
      for ($i=0; $i < count($table_list); $i++) {
         $ai->truncate($table_list[$i]);
         $ai->add_chat("Table {$table_list[$i]} berhasil dihapus.",2);
      }
      return true;
   }
   public function add_filter($data){
      if($data == null){
         return false;
      }
      $ai = $this->ai;
      $data = $ai->filter(trim($data));
      if($data == "--help"){
         $ai->add_chat("Gunakan command ini : cmd add_filter [filter(gunakan tanda koma untuk lebih dari 1)]",2);
         return true;
      }
      $data_filter = explode(" ",$data);
      $a = 0;
      for ($i=0; $i <count($data_filter) ; $i++) {
         $data_list = [
            "id_filter"=>"",
            "filter"=>$data_filter[$i],
         ];
         $ai->post_data("filter",$data_list);
         $a++;
      }
      $ai->add_chat("Filter sudah ditambahkan sebanyak {$a} kata",2);
      return true;
   }
   public function hitung($data=null){
      $ai = $this->ai;
      if($data == null){
         return false;
      }
      $data = trim($data);
      if($data == "--help"){
         $ai->add_chat("Gunakan command ini : cmd hitung [operator]",2);
         return true;
      }

      $ls = new SaferScript('$q = '.$data.';');
      $ls->allowHarmlessCalls('hitung');
      $error = $ls->parse();
      $return = $ls->execute();
      $ai->add_chat("Hasil dari {$data} adalah {$return}",2);
      $translate = $this->translate(" ".str_replace(" ","+","Hasil dari {$data} adalah {$return}"),1);
      return "0 | 2 | ".$translate;
   }
   public function learning($data){
      $ai = $this->ai;
      if($data == null){
         return false;
      }
      if(trim($data) == "--help"){
         $ai->add_chat("Gunakan command ini : cmd learning [kalimat]",2);
         return true;
      }
      $data = $ai->filter(strtolower(trim($data)));
      $ai->input_messages($data);
      $result = $ai->encode_kalimat($data);
      $ai->add_chat("Inputkan jawaban untuk {$data}",2);
      return $result." | 1";
   }
   public function translate($data,$out=false){
      $ai = $this->ai;

      if($data == null){
         return false;
      }
      if(trim($data) == "--help"){
         $ai->add_chat("Gunakan command ini : cmd translate [from] [to] [kalimat(lebih dari 1 kata dipisah dengan tanda +)]",2);
         return true;
      }
      //cmd translate en id kata
      $data = explode(" ",$data);
      $data = array_filter($data);
      $num = count($data);
      $url = "m?hl=us&sl=auto&tl=en&ie=UTF-8&prev=_m&q={$data[1]}";
      $row = str_replace("+"," ",$data[1]);
      $chat = "Translate {$row} ke bahasa inggris adalah ";
      if($num == 3){
         $url = "m?hl=us&sl={$data[1]}&tl={$data[2]}n&ie=UTF-8&prev=_m&q={$data[3]}";
         $row = str_replace("+"," ",$data[3]);
         $chat = "Translate {$row} dari {$data[2]} ke $data[1] adalah ";
      }
      $c = curl_init('http://translate.google.com/'.$url);
      curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);
      curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
      //curl_setopt(... other options you want...)

      $html = curl_exec($c);
      if (curl_error($c))
          die(curl_error($c));

      // Get the status code
      $status = curl_getinfo($c, CURLINFO_HTTP_CODE);

      curl_close($c);
      $a = explode('<div dir="ltr"',$html);
      $a = explode('">',$a[1]);
      $a = explode('</',$a[1]);
      if(!$out){
         $ai->add_chat($chat.html_entity_decode($a[0],ENT_QUOTES,'UTF-8'),2);
         return true;
      }
      else {
         return html_entity_decode($a[0],ENT_QUOTES,'UTF-8');
      }
   }
   public function ask($data){
      $ai = $this->ai;
      $ask = $data;
      if($data == null){
         return false;
      }
      if(trim($data) == "--help"){
         $ai->add_chat("Gunakan command ini : cmd ask [pertanyaan]",2);
         return true;
      }
      $c = curl_init("https://brainly.co.id/api/28/api_tasks/suggester?limit=100&query=".urlencode($data));
      curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);
      curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
      //curl_setopt(... other options you want...)

      $html = curl_exec($c);
      $a = json_decode($html,true);
      $data = $a['data']['tasks']['items'];
      $num = count($data);
      $jawaban = null;
      $n = 0;
      $s = 0;
      for ($i=0; $i < $num; $i++) {
         $pertanyaan = $data[$i]['task']['content'];
         similar_text($ask,$pertanyaan,$s);
         if($s>=50){
            if($s>$n){
               $n = $s;
               $id = $i;
               $jawaban = $data[$id]['responses'][0]['content'];
            }
         }
      }
      if($jawaban == null){
         $ai->add_chat("Saya tidak menemukan jawaban dari apa yang anda cari.",2);
         return true;
      }
      $ai->add_chat("Jawaban dari pertanyaan {{$ask}} adalah {$jawaban}",2);
      return true;
   }
   public function jadwal($hari=null){
      $ai = $this->ai;
      $hari = strtolower(trim($hari));
      (int)$now = date("N");
      $hari_arr = array("minggu","senin","selasa","rabu","kamis","jumat","sabtu","minggu");
      if($hari == null){
         $hari = date("N");
         $hari = str_replace(7,0,$hari);
         $hari = $hari_arr[$hari];
      }
      if(!in_array($hari,$hari_arr)){
         if(trim($hari) == "besok"){
            $now = $now+1;
            if($now == 8){
               $now = 1;
            }
         }
         else if(trim($hari) == "kemarin"){
            $now = $now-1;
            if($now == 0){
               $now = 7;
            }
         }
         else {
            return false;
         }
         $hari = str_replace(7,0,$now);
         $hari = $hari_arr[$hari];
      }
      $jadwal = [
         "senin"
            =>[
               "Seni Budaya",
               "Bahasa Indonesia",
               "Agama",
               "Matematika",
               "Sistem Operasi",
            ],
         "selasa"
            =>[
               "Pkn",
               "Sejarah Bahasa Indonesia",
               "Sistem Operasi",
               "Pemograman Web",
               "Penjas",
            ],
         "rabu"
            =>[
               "Sistem Operasi",
               "Bahasa Indonesia",
               "Agama",
               "Bahasa Indonesia",
            ],
         "kamis"
            =>[
               "Pemrograman Web",
               "Bahasa Inggris",
               "Mtk",
            ],
         "jumat"
            =>[
               "Simulasi Digital",
               "Prakarya dan KWH",
               "Tkj Dasar",
            ],
         "sabtu"
            =>[
               "Sistem Komputer",
               "Pemrograman Web",
               'Sejarah Indonesia',
               "Fisika"
            ],
         "minggu"
            =>[
               "Tidak sekolah",
            ],
      ];
      $jadwal_skr = $jadwal[$hari];
      $jadwal_skr = implode("<br>",$jadwal_skr);
      $ai->add_chat("Jadwal hari {$hari} adalah {$jadwal_skr}",2);
      //var_dump($jadwal_skr);
      return true;
   }
   public function wiki($query){
      $query = trim($query);
      $ai = $this->ai;
      $wiki = new Wikipedia;
      if($query == null){
         return false;
      }
      if(trim($query) == "--help"){
         $ai->add_chat("Gunakan command ini : cmd wiki [wiki]",2);
         return true;
      }
      if(!$wiki_data = $wiki->execute($query)){
         $ai->add_chat("Mohon maaf wiki yang anda cari tidak tersedia.",2);
         return false;
      }
      $judul = $wiki_data['title'];
      $content = $wiki_data['extract'];
      $ai->add_chat("Wikipedia Result {$query} :<br>".$judul.'<br>'.$content,2);
      return true;

   }
   public function cuaca($query){
      $query = trim($query);
      $ai = $this->ai;
      $weather = new Weather;
      if($query == null){
         return false;
      }
      if(trim($query) == "--help"){
         $ai->add_chat("Gunakan command ini : cmd cuaca [nama kota]",2);
         return true;
      }
      if($weather->execute($query)){
         $ai->add_chat("Data Cuaca ".$weather->execute($query),2);
         return true;
      }
      else {
         return false;
      }
   }
   public function delete(){
      $ai = $this->ai;
      $ai->truncate("chat");
      $ai->add_chat("Chat Berhasil dihapus",2);
      $translate = $this->translate(" Chat+Berhasil+dihapus",1);
      return "0 | 2 | ".$translate;
   }
   public function help(){
      $ai = $this->ai;
      $chat = "Fitur ini memberikan perintah kepada AI untuk melakukan suatu hal. Adapun command yang sudah ada saat ini adalah translate, learning dan hitung. Untuk menggunakannya dengan mengetikkan : cmd [nama command] [perintah]. Untuk petunjuk gunakan perintah --help";
      $ai->add_chat($chat,2);
      return true;
   }
   public function version(){
      $ai = $this->ai;
      $chat = "Version ".$this->ver;
      $ai->add_chat($chat,2);
      $chat_en = str_replace(" ","+",$chat);
      $translate = $this->translate(" ".$chat_en,1);
      return "0 | 2 | ".$translate;
   }
}
