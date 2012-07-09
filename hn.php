<?php
// display errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

//file where we write the content of HN
$filename = "hn.html";

//setup curl
$ch = curl_init("http://news.ycombinator.com/");
$fp = fopen($filename, "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

//execute curl
curl_exec($ch);
curl_close($ch);
fclose($fp);

//read context from file
$hn = file_get_contents($filename);

//set regexp pattern
$pattern = '/s="title"><a href=\"(.+?)\"(.+?)\/a>/';

//set replace string
$replacement = 's="title"><a href="${1}"${2}/a><span onClick="window.location=\'http://www.instapaper.com/text?u=\'+encodeURIComponent(\'${1}\');"> [ TXT ] </span>';

//replace pattern with string and print to browser
echo preg_replace($pattern, $replacement,$hn);

?>
