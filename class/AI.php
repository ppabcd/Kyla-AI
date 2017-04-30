<?php
//require_once("apriori.php");
/**
 * AI Class
 */
//

class AI
{
   protected $db;
   protected $apriori;
   function __construct()
   {
      $this->db = new Mysqli("localhost","root","","ai") or die(mysqli_errno());
      //$this->apriori = new Apriori;
      date_default_timezone_set("Asia/Jakarta");
   }
   private function clearStoredResults(){
      $db = $this->db;
      do {
           if ($res = $db->store_result()) {
             $res->free();
           }
        } while ($db->more_results() && $db->next_result());
   }
   public function filter($data){
      $db = $this->db;
      $data = mysqli_real_escape_string($db,$data);
      return preg_replace('/[^A-Za-z ?-[] !]/s',' ',$data);
   }
   //Query Builder
   public function truncate($table){
      $db = $this->db;
      $db->query("TRUNCATE TABLE {$table}") or die($db->error);
      return true;
   }
   public function get_data($table,$select="*",$where="",$type="object"){
      $db = $this->db;
      $query = "SELECT {$select},id_{$table} FROM {$table} ";
      if($where){
         $query .= "WHERE {$where}";
      }
      $query = $db->query($query) or die($db->error);
      $i = 0;
      $fetch = "fetch_{$type}";
      while($row = $query->{$fetch}()){
         if($type == "object")
         $data[$row->{"id_".$table}] = $row;
         else if($type="assoc")
         $data[$row["id_".$table]] = $row;
         else
         $data[$i] = $row;
         $i++;
      }
      return $data;
   }
   public function post_data($table,$data,$where=""){
      $db = $this->db;
      $map = "";
      if(is_array($data)){
         $val = "'".implode("','",array_map(array($db, 'real_escape_string'), $data))."'";
         $key = "".implode(",",array_map(array($db, 'real_escape_string'), array_keys($data)))."";
         $map = "({$key})";
      }
      else {
         $val = "'".array_map(array($db, 'real_escape_string'), $data)."'";
      }
      $query = "INSERT INTO {$table} {$map} VALUES({$val}) ";
      if($where){
         $query .= "WHERE {$where}";
      }
      $query = $db->query($query) or die($db->error.$query);
      return true;
   }
   public function delete_data($table,$where){
      $db = $this->db;
      $query = "DELETE {$table} WHERE {$where}";
      $db->query($query) or die($db->error);
      return true;
   }
   private function query($query){
      $db = $this->db;
      $query = $db->query($query) or die($db->error);
      return true;
   }
   private function child_result($data){
      foreach($data as $inner) {
         $result[key($inner)] = current($inner);
      }
      return $result;
   }
   //AI
   public function input_messages($data){
      //Mengambil data kata
      $ass = $this->get_data("kata","kata,hit","","assoc");
      //Mengubah jenis data
      foreach($ass as $key=>$value){
         $all[$value["kata"]] = $value["kata"];
      }
      //Melakukan filter
      $d = $this->filter($data);
      //Memecah kalimat menjadi kata dan huruf kecil semua
      $d = explode(" ",strtolower($data));
      //Tanggal Sekarang
      $date = date("Y-m-d");
      //Melooping berdasarkan jumlah kata
      for ($i=0; $i <count($d); $i++) {
         if($d[$i] != null):
         //Jika kata berada pada database
         if(in_array($d[$i],$all)){
            //Mengambil data pada database
            $query1 = $this->get_data("kata","hit,id_kata,kata","kata='{$d[$i]}'","assoc");
            //Mengubah jenis data
            $result = $this->child_result($query1);
            //Menambahkan angka hit
            $hit = $result['hit']+1;
            //Melakukan update kata
            $this->query("UPDATE kata SET hit='{$hit}',update_date='".date("Y-m-d")."' WHERE kata='{$d[$i]}';") or die($db->error);
         }
         else {
            //Data yang akan di post ke database
            $post_data = [
               "id_kata"=>"",
               "kata"=>$d[$i],
               "hit"=>1,
               "create_date"=>$date,
               "update_date"=>$date,
            ];
            //Melakukan post data ke dalam database
            $this->post_data("kata",$post_data);
         }
         endif;
      }
      return true;
   }
   public function add_chat($content,$id_user){
      $content = $this->filter(trim($content));
      $data = [
         "chat_id"=>'',
         "id_user"=>$id_user,
         "content"=>$content,
         "time"=>date("Y-m-d H:i:s"),
      ];
      $this->post_data("chat",$data);
      return true;
   }
   public function list_chat(){
      $db = $this->db;
      $query = "SELECT chat.content,chat.time,user.username FROM chat INNER JOIN user ON chat.id_user=user.id_user ORDER BY time";
      $query = $db->query($query) or die($db->error);
      while ($row = $query->fetch_object()) {
         $data[] = $row;
      }
      return json_encode($data);
   }
   public function list_filter(){
      $db = $this->db;
      $query = "SELECT filter FROM filter ORDER BY filter";
      $query = $db->query($query) or die($db->error);
      while ($row = $query->fetch_object()) {
         $data[] = $row;
         //var_dump($data);
      }
      return json_encode($data);
   }
   public function list_kalimat(){
      $db = $this->db;
      $query = "SELECT kalimat.id_kata,response.response,kalimat.id_kalimat,response.id_response FROM kalimat INNER JOIN response ON kalimat.id_response=response.id_response";
      $query = $db->query($query) or die($db->error);
      $json = array();
      while ($row = $query->fetch_object()) {
         $data['id_kalimat'] = $row->id_kalimat;
         $data['id_response'] = $row->id_response;
         $data['id_kata'] = $this->decode_kalimat($row->id_kata);
         $data['response'] = $this->decode_kalimat($row->response);
         array_push($json,$data);
      }
      return json_encode($json);
   }
   public function add_filter_kata($kalimat,$pemisah=" "){
      $db = $this->db;
      $ass = $this->get_data("filter","filter","","assoc");
      //Mengubah jenis data
      foreach($ass as $key=>$value){
         $all[$value["filter"]] = $value["filter"];
      }
      $kalimat = $this->filter($kalimat);
      $kalimat = explode($pemisah,strtolower($kalimat));
      for ($i=0; $i < count($kalimat); $i++) {
         if(!in_array($kalimat[$i],$all)){
            $query = $db->query("INSERT INTO filter VALUES('','{$kalimat[$i]}')") or die($db->error);
         }
      }
      return true;
   }
   public function filter_kata($kalimat){
      $db = $this->db;
      $kalimat = $this->filter($kalimat);
      $kalimat = str_replace("0","o",$kalimat);
      $kalimat = str_replace("3","e",$kalimat);
      $kalimat = str_replace("4","a",$kalimat);
      $kalimat = str_replace("1","i",$kalimat);
      $kalimat = explode(" ",strtolower($kalimat));
      $query = $db->query("SELECT filter FROM filter");
      $filter = array();
      while($row = $query->fetch_object()){
         $filter[$row->filter] = $row->filter;
      }
      for ($i=0; $i < count($kalimat); $i++) {
         if(in_array($kalimat[$i],$filter)){
            return false;
         }
      }
      return true;
   }
   public function get_messages($kalimat){
      $db = $this->db;
      if($kalimat == null){
         return false;
      }
      $this->input_messages($kalimat);
      $kalimat = $this->filter($kalimat);
      $this->clearStoredResults();
      $kalimat = explode(" ",$kalimat);
      for ($i=0; $i <count($kalimat); $i++) {
         if($kalimat[$i] != null):
            $encode[$i] = $this->encode_kata($kalimat[$i]);
         endif;
      }
      $encode = implode(",",$encode);
      //var_dump($encode);
      //$query = $db->query("SELECT id_kalimat,id_response,id_kata FROM kalimat WHERE id_kata LIKE '%{$encode}%'") or die($db->error);
      $query = $db->query("SELECT id_kalimat,id_response,id_kata FROM kalimat WHERE id_kata") or die($db->error);
      if($query->num_rows ==0){
         return false;
      }
      $n = 0;
      $res = null;
      while($row = $query->fetch_object()){
         similar_text($encode,$row->id_kata,$s);
         if($s>=70){
            if($s>$n){
               $n = $s;
               $id = $row->id_kalimat;
               $res = $row->id_response;
            }
         }
      }
      if($res == null){
         return false;
      }
      $query = $db->query("SELECT response FROM response WHERE id_response={$res}") or die($db->error);
      if($query->num_rows == 0){
         return false;
      }
      while($row = $query->fetch_object()){
         return $row->response;
      }
   }
   public function add_response($respon, $kalimat){
      $db = $this->db;
      $ass = $this->get_data("response","response","","assoc");
      //Mengubah jenis data
      foreach($ass as $key=>$value){
         $all[$value["response"]] = $value["response"];
      }
      //Melakukan filter
      $respon = $this->filter($respon);
      if($respon == null){
         return false;
      }
      $this->input_messages($respon);
      $this->clearStoredResults();
      $respon = $this->encode_kalimat($respon);
      $date = date("Y-m-d");
      if(in_array($respon,$all)){
         //Mengambil data pada database
         $query1 = $this->get_data("response","id_response,response","response='{$respon}'","assoc");
         //Mengubah jenis data
         $result = $this->child_result($query1);
         //Menambahkan angka hit
         $id_response = $result['id_response'];
      }
      else {
         $db->query("INSERT INTO response VALUES('','{$respon}','{$date}','{$date}')") or die($db->error);
         $id_response = mysqli_insert_id($db);
      }
      $data = [
         "id_kalimat"=>'',
         "id_kata"=>$kalimat,
         "id_response"=>$id_response,
      ];
      $this->post_data("kalimat",$data);
      return true;
   }
   //Encode & Decode
   public function encode_kalimat($data){
      $data = $this->filter($data);
      $data = explode(" ",$data);
      for ($i=0; $i <count($data) ; $i++) {
         if($data[$i] != null){
            $data[$i] = $this->encode_kata($data[$i]);
         }
      }
      return implode(",",$data);
   }
   public function decode_kalimat($data){
      $data = $this->filter($data);
      $data = explode(",",$data);
      for ($i=0; $i <count($data) ; $i++) {
         $data[$i] = $this->decode_kata($data[$i]);
      }
      return implode(" ",$data);
   }
   private function encode_kata($data){
      $db = $this->db;
      $this->clearStoredResults();
      $query = $this->get_data("kata","id_kata","kata='{$data}'","assoc");
      $child = $this->child_result($query);
      return $child['id_kata'];
   }
   private function decode_kata($data){
      $db = $this->db;
      $this->clearStoredResults();
      $query = $this->get_data("kata","kata","id_kata='{$data}'","assoc");
      $child = $this->child_result($query);
      return $child['kata'];
   }
   //Status server
   public function status(){
      return true;
   }
}
