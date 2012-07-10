<?php
// display errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

// file where we write the content of HN
$filename = "hn.html";

// set the url
$url = "http://news.ycombinator.com";

// get parameter from input and put it into url if it pass regexp
$page = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_REGEXP, array('options'=>array('regexp'=>'/(news2|\/x.*)/')) );
$url = is_null($page) ? $url : (0 === strpos($page,'/')) ? $url . $page : $url . '/' . $page;

// setup curl
$ch = curl_init($url);
$fp = fopen($filename, "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

// execute curl
curl_exec($ch);
curl_close($ch);
fclose($fp);

// read context from file
$hn = file_get_contents($filename);

// handling "More" link
$pattern='/f="([^"]*)"( rel="nofollow")?>More<\/a>/';
$replacement='f="?p=${1}">More</a>';
$hn = preg_replace($pattern, $replacement,$hn);

// do the magic
$pattern = '/s=\"title\"><a href=\"([^\?].+?)\"(.+?)\/a>/';
$replacement = 's="title"><a href="${1}"${2}/a><span onClick="window.location=\'http://www.instapaper.com/text?u=\'+encodeURIComponent(\'${1}\');"> [ TXT ] </span>';
// replace pattern with string and print to browser
echo preg_replace($pattern, $replacement,$hn);

?>
