<?php

ini_set('max_execution_time', 0);
ini_set('memory_limit', '1G');
error_reporting(E_ERROR);

echo "1.Write now\n";
echo "2.From file\n";
echo "3.From URL\n";
echo "\n==============================================\n";
echo "Your choice ~> ";
$jawab = trim(fgets(STDIN));
if($jawab == "1"){
         echo "Write now ~> ";
         $isi = trim(fgets(STDIN));
}
else if($jawab == "2"){
echo "File Name  ~> ";
$nama = trim(fgets(STDIN));
if(file_exists($nama)){
        $isi = file_get_contents($nama);
}
else {
    echo "File does not exist\n";
}
}
else if($jawab == "3"){
      echo "URL name ~> ";
      $url = trim(fgets(STDIN));
      if(filter_var($url,FILTER_VALIDATE_URL) !== FALSE){
            $isi = file_get_contents($url);
}
     else {
       echo "That is not a valid URL\n";
}
}
else {
    echo "You can only choose 1,2 or 3\n";
}

$pastebin = "https://pastebin.com/post.php";

$pastebins = file_get_contents($pastebin);
$letaktoken = (stripos($pastebins,"csrf_token_post")) + 24;
$token = substr($pastebins,$letaktoken,56);

$data1 = "csrf_token_post=".$token;
$data2 = "submit_hidden=submit_hidden";
$data3 = "paste_code=".$isi;
$data4 = "paste_format=1";
$data5 = "paste_expire_date=N";
$data6 = "paste_private=0";
$data7 = "paste_name=";

$fulldata = "$data1&$data2&$data3&$data4&$data5&$data6&$data7";

// HELPER METHODS
function initCurlRequest($reqType, $reqURL, $reqBody = '', $headers = array()) {
    if (!in_array($reqType, array('GET', 'POST', 'PUT', 'DELETE'))) {
        throw new Exception('Curl first parameter must be "GET", "POST", "PUT" or "DELETE"');
    }

    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $reqURL);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $reqType);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $reqBody);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, true);
    
   	$body = curl_exec($ch);

   	// extract header
   	$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($body, 0, $headerSize);
	$header = getHeaders($header);

	// extract body
	$body = substr($body, $headerSize);

    curl_close($ch);
    
    return [$header, $body];
}

function getHeaders($respHeaders) {
    $headers = array();

    $headerText = substr($respHeaders, 0, strpos($respHeaders, "\r\n\r\n"));

    foreach (explode("\r\n", $headerText) as $i => $line) {
        if ($i === 0) {
            $headers['http_code'] = $line;
        } else {
            list ($key, $value) = explode(': ', $line);

            $headers[$key] = $value;
        }
    }

    return $headers;
}

// MAIN
$reqBody = '';
$headers = array();
list($header, $body) = initCurlRequest('POST', $pastebin, $fulldata, $headers);

echo '<pre>';
print_r($header);
echo $header;
echo $body;
echo '</pre>';

?>
