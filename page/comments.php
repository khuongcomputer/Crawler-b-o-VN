<?php

$q = $sql->query("SELECT `id`,`url`,`md5` FROM `comments` WHERE `done`=0 ORDER BY `id` ASC LIMIT 0,1");
$d = $q->fetch_array();
$sql->query(update('comments',['done'=>1],'done',"`id`={$d['id']}"));

echo "Getting comments from <b>{$d['url']}</b><br>";

$html = file_post_contents($d['url']);

if(strstr($d['url'],'vnexpress.net')){ // get vnexpress comments
	$content = vne_extract_comments($html);
	write_file(config('data.comments').$d['md5'].'.txt',$content);
	echo "Got comments!";
}
?>

<meta http-equiv="refresh" content="1">