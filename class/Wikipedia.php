<?php
/**
 * Wikipedia Class
 */
class Wikipedia
{
   private function child_result($data){
      foreach($data as $inner) {
         $result[key($inner)] = current($inner);
      }
      return $result;
   }
   public function execute($query){
      $query = strtolower(urlencode(trim($query)));
      $c = curl_init("https://id.wikipedia.org/w/api.php?action=query&prop=extracts%7Cinfo&exintro&titles={$query}&format=json&explaintext&redirects&inprop=url");
      curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);
      curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
      $html = curl_exec($c);
      $a = json_decode($html,true);
      //var_dump($a["query"]);
      $page_id = $this->child_result($a["query"]["pages"]);
      if(empty($page_id["pageid"])){
         return false;
      }
      $page = $a["query"]["pages"][$page_id['pageid']];
      return array("title"=>$page['title'],"extract"=>$page["extract"]);
   }
}
