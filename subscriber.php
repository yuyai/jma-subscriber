<?php 
$method = $_SERVER['REQUEST_METHOD']; 
 
// subscribe (or unsubscribe) a feed from the HUB 
if ($method == 'GET') { 
 $hubmode = $_REQUEST['hub_mode']; 
 $hubchallenge = $_REQUEST['hub_challenge']; 
 if ($hubmode == 'subscribe' || $hubmode == 'unsubscribe') { 
   // response a challenge code to the HUB 
   header('HTTP/1.1 200 "OK"', null, 200); 
   header('Content-Type: text/plain'); 
   echo $hubchallenge; 
 } else { 
   header('HTTP/1.1 404 "Not Found"', null, 404); 
 } 
} 
 
// receive a feed from the HUB 
if ($method == 'POST') { 
  // feed Receive 
  $string = file_get_contents("php://input"); 
  // feed Parse & XML GET 
  if (FALSE === ($feed = simplexml_load_string($string))) { 
    exit("feed Parse ERROR"); 
  } 
  error_log($string,0);
  foreach ($feed->entry as $entry) { 
    error_log("TEST ERROR!!",0);
    $url = $entry->link['href']; 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    //$fp = fopen(basename($url), "w");
    $path = '/tmp/'.basename($url);
    die ("path: $path");
    $fp = @fopen($path, "w") or
      die ("Failed opening file: error was '$php_errormsg'");
    curl_setopt($ch, CURLOPT_FILE, $fp); 
    curl_exec($ch); 
    curl_close($ch); 
    fclose($fp); 
  } 
}

?>
