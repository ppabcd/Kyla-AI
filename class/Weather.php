<?php
/**
 * Weather Class
 */
class Weather
{

   function execute($query){
      $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
      $yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.trim($query).'")';
      $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
      // Make call with cURL
      $session = curl_init($yql_query_url);
      curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
      $json = curl_exec($session);
      // Convert JSON to PHP object
       $phpObj =  json_decode($json,true);
       if($phpObj["query"]["count"] == 0){
          return false;
       }
       $data_date = $phpObj["query"]["results"]["channel"]["item"]["forecast"];
       $num_rows = count($data_date);
       $data = "";
       //var_dump($data_date);
      // die();
      $cuaca = [
         "Thunderstorms"=>"Petir",
         "Scattered Thunderstorms"=>"Petir Tersebar",
         "Mostly Cloudy"=>"Sebagian Besar Berawan",
         "Partly Cloudy"=>"Sebagian Berawan",
         "Sunny"=>"Cerah",
      ];
       for ($i=0; $i <$num_rows ; $i++) {
          if(!empty($cuaca[$data_date[$i]["text"]])){
             $cuaca_temp = $cuaca[$data_date[$i]["text"]];
          }
          else {
             $cuaca_temp = $data_date[$i]["text"];
          }
          $data .= $data_date[$i]["day"]." ".$data_date[$i]["date"]." - ".$cuaca_temp."<br>";
       }

       //$datas = $phpObj["query"]["results"]["channel"]["item"]["description"];
       $data_lokasi = $phpObj["query"]["results"]["channel"]["location"];
       $data_lokasi = implode(", ",$data_lokasi);
       //$datas = str_replace("<![CDATA[<img src=\"http://l.yimg.com/a/i/us/we/52/4.gif\"/>","",$data);
       //$datas = str_replace("]]>","",$data);
       $data = $data_lokasi."<br><br>".$data;
       return $data;
   }
}

    /*$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
    $yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="tangerang")';
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
    // Make call with cURL
    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
    $json = curl_exec($session);
    // Convert JSON to PHP object
     $phpObj =  json_decode($json);
    var_dump($phpObj);*/
