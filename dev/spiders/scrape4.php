<?

$folder = "C:\\Documents and Settings\\Kyle\\Desktop\\wadata\\";

for($i = 0; $i < 4; $i++){
	$fp[$i] = fopen($folder."scrape".$i, 'wb');
	fwrite($fp[$i], "cd ~/Private/wadata\n");
}
for($i = 0; $i < 268; $i++){
	fwrite($fp[floor($i/70)], "lynx -accept_all_cookies -cmd_script=script.wa.sections.$i.txt");
}