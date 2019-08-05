<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<TITLE>WCSC Website Poll</TITLE>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >

<STYLE type="text/css">
  body {
    font-family: monospace;
  }
</STYLE>

</HEAD>
<BODY>
<h2>Special message from Senor Harry:</h2> 

POLLING IS CLOSED, MY STOUT FRIENDS. 
<?php

//if(!$_REQUEST['submit'])
if(!1)
{
	
?>

<h1><center>WCSC Website Poll</center></h1>
Please select which of the following 5 WCSC Website designs you like. You may select more than one. Select 'No Confidence' if you do not approve of any of the designs. <br><br>Thanks,<br>Harry

<form action="designpoll.php" method=post>
<input type=checkbox name=vote[] value=1>Design #1:<br>
<img src=wcscwebpoc1.gif><hr><br>

<input type=checkbox name=vote[] value=2>Design #2:<br>
<img src=wcscwebpoc2.gif><hr><br>

<input type=checkbox name=vote[] value=3>Design #3:<br>
<img src=wcscwebpoc3.gif><hr><br>

<input type=checkbox name=vote[] value=4>Design #4:<br>
<img src=wcscwebpoc4.gif><hr><br>

<input type=checkbox name=vote[] value=5>Design #5:<br>
<img src=wcscwebpoc5.gif><hr><br>

<input type=submit name=submit value="Submit Vote"> <input type=submit name=submit value="No Confidence">

<?php 

}
//else
if(!1)
{
	$voteRecordFile = fopen("vrf.txt", "a");
	
	if($_REQUEST['submit'] == 'No Confidence')
		fwrite($voteRecordFile, $_SERVER['REMOTE_ADDR'] . ":\tNo Confidence\n");
	elseif(!count($_REQUEST['vote']))
		fwrite($voteRecordFile, $_SERVER['REMOTE_ADDR'] . ":\t" . "Vote Not Cast\n");
	else	
		fwrite($voteRecordFile, $_SERVER['REMOTE_ADDR'] . ":\t" . join(', ', $_REQUEST['vote']) . "\n");

	fclose($voteRecordFile);
	
	print("Thanks for your vote!<br>\n");
} 

?>


</BODY>
</HTML>