<?php
function file_post_contents($url)
{
$curl = curl_init();
  // Setup headers - I used the same headers from Firefox version 2.0.0.6
  // below was split up because php.net said the line was too long. :/
  $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
  $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
  $header[] = "Cache-Control: max-age=0";
  $header[] = "Connection: keep-alive";
  $header[] = "Keep-Alive: 5";
  $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
  $header[] = "Accept-Language: en-us,en;q=0.5";
  $header[] = "Pragma: "; // browsers keep this blank.

  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506; InfoPath.1; .NET CLR 1.1.4322)');
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
  //curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
  curl_setopt($curl, CURLOPT_AUTOREFERER, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_TIMEOUT, 30);
  //if($cookie!="") curl_setopt($curl, CURLOPT_COOKIEFILE, "$cookie");

  $html = curl_exec($curl); // execute the curl command
  curl_close($curl);
  return $html;
}

function extract_urls($html){
	global $sql;
	$dom = new DOMDocument();
	@$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);
	$hrefs = $xpath->evaluate("/html/body//a");
	
	$urls = [];
	for($i = 0; $i < $hrefs->length; $i++){
		$href = $hrefs->item($i);
		$url = $href->getAttribute('href');
		$url = filter_var($url, FILTER_SANITIZE_URL);
		// validate url
		if(!filter_var($url, FILTER_VALIDATE_URL) === false && strstr($url,'vnexpress.net') && !strstr($url,'//e.vnexpress.net')){
			if(strstr($url,'.html')) $url = substr($url,0,strpos($url,'.html')+5);
			$urls[] = $url;
			$md5 = md5($url);
			$sql->query(insert('urls',['url'=>$url,'md5'=>$md5],'url,md5'));
		}
	}
	return $urls;
}

function vne_extract_content($html){
	$dom = new DOMDocument();
	@$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);
	$contents = $xpath->evaluate("//div[@id='left_calculator']/div[@class='fck_detail width_common block_ads_connect']");
	$data = '';
	for($i = 0; $i < $contents->length; $i++){
		$content = $contents->item($i)->nodeValue;
		$content = str_replace('<br>',"\n",$content);
		$content = str_replace('<br/>',"\n",$content);
		$content = str_replace('<br />',"\n",$content);
		$content = strip_tags($content);
		$content = str_replace("\t","",$content);
		while(strstr($content,"\n\n")){
			$content = str_replace("\n\n","\n",$content);
		}
		$data.=$content."\n";
	}
	return trim($data);
}

function vne_extract_comments($html){
	
	$html = str_replace("/**/ typeof nothing === 'function' && nothing(","",$html);
	$html = str_replace(");","",$html);
	$data = json_decode($html,true);

	$content = '';
	for($i=0; $i<sizeof($data['data']['items']); $i++){
		$cnt = $data['data']['items'][$i]['content'];
		$content.= $cnt."\n";
	}
	$content = str_replace('<br>',"\n",$content);
	$content = str_replace('<br/>',"\n",$content);
	$content = str_replace('<br />',"\n",$content);
	$content=trim($content);
	return $content;
}

function write_file($file,$content){
	if($content=='') return '';
	$f = fopen($file,'w');
	fwrite($f,$content);
	fclose($f);
}