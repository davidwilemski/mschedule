<?
require_once "class.scraper.php";

$s = new Scraper();


$html = file_get_contents("class-search.txt");

print "<pre>";
$tag_array = $s->readTags($html);

foreach($tag_array as $tag){
	if(strcasecmp($tag->name, "form") == 0){
		var_dump($tag);
	}
}


?>