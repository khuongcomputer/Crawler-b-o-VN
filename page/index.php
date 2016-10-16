<?php

$q = $sql->query("SELECT `id`,`url`,`md5` FROM `urls` WHERE `done`=0 ORDER BY `id` ASC LIMIT 0,1");
$d = $q->fetch_array();
$sql->query(update('urls',['done'=>1],'done',"`id`={$d['id']}"));

$html = file_post_contents($d['url']);

$urls = extract_urls($html);
echo "Crawl <b>{$d['url']}</b><br />";
echo "Found ".sizeof($urls)." URLs<br />";

if(strstr($d['url'],'.html')){
	if(strstr($d['url'],'vnexpress.net')){ // get vnexpress news
		$content = vne_extract_content($html);
		write_file(config('data.news').$d['md5'].'.txt',$content);
		if(strstr($html,'VNE.Comment.setOptions')){
			$comm = substr($html,strpos($html,'VNE.Comment.setOptions'));
			
			$cnf_id = substr($comm,strpos($comm,'objectid:')+strlen('objectid:'));	
			$cnf_id = trim(substr($cnf_id,0,strpos($cnf_id,',')));
			
			$cnf_site = substr($comm,strpos($comm,'siteid:')+strlen('siteid:'));
			$cnf_site = trim(substr($cnf_site,0,strpos($cnf_site,',')));
			
			$cnf_cat = substr($comm,strpos($comm,'categoryid:')+strlen('categoryid:'));
			$cnf_cat = trim(substr($cnf_cat,0,strpos($cnf_cat,',')));
			
			$comment_url = "http://usi.saas.vnexpress.net/index/get?callback=nothing&offset=0&limit=1000&frommobile=0&sort=like&objectid={$cnf_id}&objecttype=1&siteid={$cnf_site}&categoryid={$cnf_cat}";
			echo "Got comment url: ".$comment_url;
			$comment_md5 = md5($comment_url);
			
			$sql->query(insert('comments',['url'=>$comment_url,'md5'=>$comment_md5],'url,md5'));
		}
	}
}
?>
<meta http-equiv="refresh" content="1">