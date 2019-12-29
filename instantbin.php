<?php

// Coded By :  @irfan_vrn
// Github : https://github.com/TheValeHack/

include("ua.php");

class pastebin {

      protected function getToken(){
         $source = file_get_contents("https://pastebin.com");
         if(preg_match_all('#csrf_token_post" value="(.*?)">#',$source,$arrayToken)){
             foreach($arrayToken[1] as $finaltoken){
                $ftoken = $finaltoken;
}
}
        else {
            $ftoken = "\033[91mSomething Wrong";
}
        return $ftoken;
}

      protected function takeHeaders($headersss){
           $resp = array();
           $headerpos = strpos($headersss,"\r\n\r\n");
           $headerContent = substr($headersss,0,$headerpos);

           return $headerContent;
}

      public function curlRequest($ua,$data,$reff = "https://pastebin.com/"){
          $fulldata = array(
            'csrf_token_post' => $this->getToken(),
            'submit_hidden' => 'submit_hidden',
            'paste_code' => $data,
            'paste_format' => '1',
            'paste_expire_date' => 'N',
            'paste_private' => '0',
            'paste_folder' => '0',
            'paste_name' => '',
            'submit' => 'Create New Paste'
);
         $headerss = array();
         $headerss[] = 'Content-Type: multipart/form-data';

         $ch = curl_init();
         curl_setopt($ch,CURLOPT_URL,"https://pastebin.com/post.php");
         curl_setopt($ch,CURLOPT_USERAGENT,$ua);
         curl_setopt($ch,CURLOPT_REFERER,$reff);
         curl_setopt($ch,CURLOPT_HEADER,1);
         curl_setopt($ch,CURLOPT_HTTPHEADER,$headerss);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
         curl_setopt($ch,CURLOPT_POST,1);
         curl_setopt($ch,CURLOPT_POSTFIELDS,$fulldata);
         curl_setopt($ch,CURLOPT_TIMEOUT,50);
         $output = curl_exec($ch);

            $hsize = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
            $header = substr($output,0,$hsize);
            $headers = $this->takeHeaders($header);

         curl_close($ch);

           return $headers;
}
}

$stop = TRUE;

while($stop){
echo "
\033[96m ___           _              _    ____  _
|_ _|_ __  ___| |_ __ _ _ __ | |_ | __ )(_)_ __
 | || '_ \/ __| __/ _` | '_ \| _ _|  _ \| | '_ \
 | || | | \__ \ || (_| | | | | |_ | |_) | | | | |
|___|_| |_|___/\__\__,_|_| |_|\__\|____/|_|_| |_|\n\n\n\033[93mAuthor : @irfan_vrn\n\n";
echo "\033[93m1. Write Now\n";
echo "2. From File\n";
echo "3. From URL\n\n";
echo "======================================================\n\n";
echo "Your Choose ~> ";
$c = trim(fgets(STDIN));
if($c == '1'){
    echo "Write Now ~> ";
    $isi = trim(fgets(STDIN));
}
else if ($c == '2'){
    echo "File Name ~> ";
    $fname = trim(fgets(STDIN));
       if(file_exists($fname)){
          $isi = file_get_contents($fname);
}
       else {
         echo "File Doesn't Exist\n";
}
}
else if ($c == '3'){
      echo "URL ~> ";
      $url = trim(fgets(STDIN));
      if(!filter_var($url,FILTER_VALIDATE_URL) === FALSE){
          $isi = file_get_contents($url);
}
      else {
          echo "URL Doesn't Valid\n";
}
}
else {

      echo "Choose 1,2,3 only\n";

}

if(!empty($isi)){
$paste = new pastebin();
$postRequest = $paste->curlRequest($useragent,$isi);
if(preg_match_all("#pastebin_posted=(.*?);#",$postRequest,$pathLoc)){
foreach($pathLoc[1] as $path){
         echo "\n========================================================\n";
         echo "\033[92m\n[200] SUCCESS\n";
         echo "\033[95m\nPastebin URL ~> https://pastebin.com/".$path;
         echo "\nRaw ~> https://pastebin.com/raw/".$path;
         echo "\nClone ~> https://pastebin.com/index/".$path;
         echo "\nEmbed ~> https://pastebin.com/embed/".$path;
         echo "\nPrint ~> https://pastebin.com/print/".$path;
         echo "\nDownload ~> https://pastebin.com/dl/".$path;
}
}
else {

      echo "\033\n[91mSomething Wrong :(\n";

}
}
else {
    echo "\033[91mSomething Wrong\n";
}
echo "\n\n\033[93mAgain ? [y/n] ~> ";
$again = trim(fgets(STDIN));
if($again == "n"){
     $stop = FALSE;
}
}
?>
