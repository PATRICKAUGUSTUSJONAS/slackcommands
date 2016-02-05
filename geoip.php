<?php
// Usage: /geoip ip|host
$_TOKEN=''; // Put token here
$text = $_POST['text'];
$command = $_POST['command'];
header("Content-type: application/json");


if($_POST['token'] != $_TOKEN) {
  echo "Invalid token.";
}
else {
  if($text=="")
    echo "Invalid string.";
  else {
    $reply = array(
        "response_type" => "",
        "text" => '',
        "attachments" => array("text"=>"")
    );

      $r=geoip_record_by_name($text);
$response="
$text is located in $r[city], $r[region] $r[country_code] on $r[continent_code]\n
Area Code is $r[area_code] and zip is $r[postal_code]\n
";

      $reply["text"]="\n\n$response\n";

      $url = $_POST["response_url"];
      $content = json_encode($reply);

      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Content-type: application/json"));
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
      $json_response = curl_exec($curl);

      $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

      curl_close($curl);
      $response = json_decode($json_response, true);

  }
}




