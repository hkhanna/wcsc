<?php
//TODO: news archives, allow access to

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
?>
<table border=0 width=550px>
	
<?php

$dbh=mysql_connect ("hops.ucsd.edu", "wcsc", "wcsc45") or die ('Error: I cannot connect to the database because: ' . mysql_error());
mysql_select_db("wcsc");
		
$query = "SELECT author, subject, body, post_time FROM news ORDER BY post_id DESC LIMIT 5";
$dbResult = mysql_query($query, $dbh) or die('Error: I cannot execute query in Member constructor because: ' . mysql_error());

while($row = mysql_fetch_array($dbResult, MYSQL_ASSOC))
{
	$author = new Member($row['author']);
	
	print("<tr><td style=\"background-color: black\">$row[subject]<br><font size=-3>" . date("l, F j, Y g:ia", revertTimeStamp($row['post_time'])) . "<br>");
	print("Posted by <a href=\"mailto:" . $author->getInfo('email') . "\">" . $author->getInfo('name and position') . "</a></font></td>");
	print("<tr><td>" . nl2br($row['body']) . "<br><br></td></tr>");
}

print("</table>");

writeFooter('home');
?>
