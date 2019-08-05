<?php
include 'common.php';

//increment harry counter

setcookie('visited', '1');

if(!$_REQUEST['visited'] && !isset($_REQUEST['ignorecount']))
{
		$counter = file('harrycounter');
		$counter[0] += 1;
		$countFile = fopen('harrycounter', 'w');
		fwrite($countFile, $counter[0] . "\n");
		fwrite($countFile, $counter[1]);
		fclose($countFile);
}

//end increment harry counter

writeHeader('Home');

print("<h2><center>Current WCSC News</center></h2>\n");
$news = file("frontnews.txt");
for($index = 1; $index <= count($news); $index++)
{
	$printStr = stripslashes(nl2br($news[$index]));
	print("$printStr");
}

print("<hr><font size=-2>$news[0]</font>");
print("<br><br>You may still view the old website by clicking <a href=oldsite>here</a>.\n");

writeFooter('home');
?>
