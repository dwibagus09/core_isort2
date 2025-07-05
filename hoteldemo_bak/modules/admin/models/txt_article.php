<?php 
echo htmlentities(stripslashes($this->article["headline"]),ENT_QUOTES,"UTF-8");
if($this->article["priority"]==10) {
	echo "\nUpdated: ".$this->article["modify_date_time"]; 
} else { 
	echo "\nPublished: ".$this->article["pubdate"];
}
if(!empty($this->article["byline"])) {
	echo "\nBy: ".stripslashes($this->article["byline"]);
	if(!empty($this->article["source"])) { 
		echo "\n".htmlentities(stripslashes($this->article["source"]),ENT_QUOTES,"UTF-8"); 
	}
}
$article = str_replace("<p>","\n",htmlentities(stripslashes($this->article["article"])),ENT_QUOTES,"UTF-8");
$article = str_replace("</p>","",stripslashes($article));
echo "\n".$article;
?>