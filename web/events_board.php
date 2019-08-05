<?php
include 'common.php';
if($_REQUEST['action'] == 'minutes')
{
	writeHeader('Events Board: Minutes');
	
	$minutesDir = opendir('./minutes/events_board');
	while($minutesFile = readdir($minutesDir))
		$fileArr[] = $minutesFile;
	sort($fileArr);
	reset($fileArr);
	
	print("<h3><u>WCSC Events Board Minutes for 2004-2005</u></h3>");
	foreach($fileArr as $minutesFile)
	{
		if($minutesFile === '.' or $minutesFile === '..')
			continue;
			
		$minutesData = explode(".", $minutesFile);
		
		if($minutesData[5] == 'UNOFFICIAL')
			print("Unofficial until approved by council: ");
			
		if($minutesData[4] == '0405')
			print("<a href='minutes/events_board/$minutesFile'>$minutesData[2]/$minutesData[3]/$minutesData[1]</a> (.doc)<br>");
	}
	
	closedir($minutesDir);
	
}
else
{
writeHeader('Events Board');
$evchair = whoIs('Events Board Chair');
?>

<div align="center"><h2>Events Board</h2>
<a href="documents/bylaws.Events_Board.2004.09.30.pdf">Bylaws of the WCSC Events Board (.pdf)</a>
<br><a href="document/roster.xls">Membership</a>
<br><a href="events_board.php?action=minutes">Minutes</a></div>

<p>Events Board Chair - <a href="mailto:<?= $evchair[0]->getInfo('email'); ?>"><?= $evchair[0]->getInfo('name'); ?></a>


<p>The WCSC Events Board is charged with planning events and activities for the Warren College population. It is comprised of 5 members who are elected in the spring and 5 members appointed in the fall. All Events Board members serve one-year terms. The board is chaired by the Events Board Chair, elected in the Spring. The Chair has a vote on Legislative Board but has no vote on Events Board.

<p>The operations of the Events Board Board are governed by the Bylaws of the WCSC Events Board. The Board meets every week at 5pm in JK Wood Lounge.



<?php
}
writeFooter('events_board');
?>
