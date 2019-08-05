<?php
//TODO: expand description a bit?; expand committee functionality a bit (member roster etc); 
// action=members; open positions

include 'common.php';

if($_REQUEST['action'] == 'minutes')
{
	writeHeader('Legislative Board: Minutes');
	
	$minutesDir = opendir('./minutes/leg_board');
	while($minutesFile = readdir($minutesDir))
		$fileArr[] = $minutesFile;
	sort($fileArr);
	reset($fileArr);
	
	print("<h3><u>WCSC Legislative Board Minutes for 2004-2005</u></h3>");
	foreach($fileArr as $minutesFile)
	{
		if($minutesFile === '.' or $minutesFile === '..')
			continue;
			
		$minutesData = explode(".", $minutesFile);
		
		if($minutesData[5] == 'UNOFFICIAL')
			print("Unofficial until approved by council: ");
			
		if($minutesData[4] == '0405')
			print("<a href='minutes/leg_board/$minutesFile'>$minutesData[2]/$minutesData[3]/$minutesData[1]</a> (.doc)<br>");
	}
	
	print("<h3><u>WCSC Legislative Board Minutes for 2003-2004</u></h3>");
	reset($fileArr);
	foreach($fileArr as $minutesFile)
	{
		if($minutesFile === '.' or $minutesFile === '..')
			continue;
			
		$minutesData = explode(".", $minutesFile);
		
		if($minutesData[4] == '0304')
			print("<a href='minutes/leg_board/$minutesFile'>$minutesData[2]/$minutesData[3]/$minutesData[1]</a> (.doc)<br>");
	}
	closedir($minutesDir);
	
}
elseif($_REQUEST['action'] == 'members')
{
	writeHeader('Legislative Board: Members');
	print("This feature is coming soon.");
	
	//members
}
else
{
	writeHeader('Legislative Board');
	
	$president = whoIs('President');
	$vpi = whoIs('Vice President Internal');

?>

<div align="center"><h2>Legislative Board</h2>
<a href="documents/bylaws.Legislative_Board.2004.11.18.pdf">Bylaws of the WCSC Legislative Board (.pdf)</a>
<br><a href="documents/bylaws.Financial.2004.11.18.pdf">WCSC Financial Bylaws (.pdf)</a>
<br><a href="documents/roster.xls">Membership</a>
<br><a href="leg_board.php?action=minutes">Minutes</a></div>

<p>WCSC President - <a href="mailto:<?= $president[0]->getInfo('email'); ?>"><?= $president[0]->getInfo('name'); ?></a>
<br>WCSC Vice President Internal - <a href="mailto:<?= $vpi[0]->getInfo('email'); ?>"><?= $vpi[0]->getInfo('name'); ?></a>


<p>The WCSC Legislative Board is vested with the legislative authority for Warren College Student Council. It is comprised of elected and appointed members usually serving one-year terms. The board is chaired by the WCSC President, elected in the Spring. The board meetings are every Thursday at 5pm in the JK Wood Lounge in Warren College.

<p>The operations of the Legislative Board are governed by the Bylaws of the WCSC Legislative Board. 

<p>
<u>Committees</u>
<br>Legislative Board Appointments Committee - <a href="documents/charter.Legislative_Board_Appointments.2004.04.22.pdf">charter (.pdf)</a> 
<br>Constitution and Bylaws Review Committee - <a href="documents/charter.Constitution_and_Bylaws_Review.2004.05.06.pdf">charter (.pdf)</a> 
<br>Fundraising Committee - <a href="documents/charter.Fundraising.0000.00.00.pdf">charter (.pdf)</a> 


<?php
}

writeFooter('leg_board');
?>
