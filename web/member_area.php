<?php
include 'common.php';

// LOGIN PAGE; IF LASTNAME COOKIE IS SET, DONT SHOW THIS PAGE //
if(!$_REQUEST['lastName'])
{
	writeHeader('Member Area Login');
?>

<div align="center">
<h2>Member Area Login</h2>
<form action="<?=$_SERVER['PHP_SELF']?>" method=POST>
<p>Last Name: <input type=text name="lastName" size=15>
<br>Password: <input type=password name="password" size=10>
<br><br><br><input type=submit name=action value=Login><br>
Note: You must have cookies enabled to use the member area.
</form>
</div>

<?php
}
// UPLOAD AND POST PICS //
elseif($_REQUEST['action'] == 'postpics')
{
	$user = new Member($_REQUEST['lastName']);
	writeHeader('Member Area: Post News');
	
	if(!$user->authenticated($_REQUEST['password']))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	if(!$user->hasAccess('Executive Board'))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	
	
}
// POST NEWS PAGE - OFFLINE//
elseif($_REQUEST['action'] == 'postnews')
{
	$user = new Member($_REQUEST['lastName']);
	writeHeader('Member Area: Post News');
	
	if(!$user->authenticated($_REQUEST['password']))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	if(!$user->hasAccess('Executive Board'))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	print <<<HERE
		<h2><center>Member Area: Post News</center></h2>
		<form action="$_SERVER[PHP_SELF]?action=postnews_submit" method=POST>
		Subject: <input type=text name=subject size=50><br><br>
		Body:<br><textarea name=body rows=6 cols=50></textarea><br><br>
		<input type=submit value="Submit News">
HERE;

}
// SUBMIT POSTED NEWS - OFFLINE//
elseif($_REQUEST['action'] == 'postnews_submit')
{
	$user = new Member($_REQUEST['lastName']);
	writeHeader('Member Area: Submit Post News');
	
	if(!$user->authenticated($_REQUEST['password']))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	if(!$user->hasAccess('Executive Board'))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	$dbh=mysql_connect ("hops.ucsd.edu", "wcsc", "wcsc45") or die ('Error: I cannot connect to the database in postnews_submit because: ' . mysql_error());
	mysql_select_db("wcsc");
		
	$query = "INSERT INTO news SET author='" . $user->getInfo('last name') . "', poster_ip='$_ENV[REMOTE_ADDR]', subject='$_REQUEST[subject]', body='$_REQUEST[body]'";
	$dbResult = mysql_query($query, $dbh) or die('Error: I cannot execute query in postnews_submit because: ' . mysql_error());

	print("<h2><center>News Submitted!</center></h2>");

	mysql_close($dbh);
}
// MODIFY FRONT PAGE //
elseif($_REQUEST['action'] == 'modifyfront')
{
	$user = new Member($_REQUEST['lastName']);
	writeHeader('Member Area: Modify Front Page');
	
	if(!$user->authenticated($_REQUEST['password']))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	if(!$user->hasAccess('Executive Board'))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	print("<h2><center>Member Area: Modify Front Page</center></h2>\n");
	print("<form action=\"$_SERVER[PHP_SELF]?action=modifyfront_submit\" method=POST>");
	print("<br><textarea name=news rows=6 cols=50>");
	$frontnews = file("frontnews.txt");
	for($index = 1; $index <= count($frontnews); $index++)
		print(stripslashes($frontnews[$index]));
	print("</textarea><br><br>\n");
	print("<input type=submit value=\"Submit Changes\">\n");
	
}
// SUBMIT MODIFIED FRONT PAGE//
elseif($_REQUEST['action'] == 'modifyfront_submit')
{
	$user = new Member($_REQUEST['lastName']);
	writeHeader('Member Area: Submit Post News');
	
	if(!$user->authenticated($_REQUEST['password']))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	if(!$user->hasAccess('Executive Board'))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	$author = $user->getInfo('name and position');
	$authorEmail = $user->getInfo('email');
	$currentTime = date('D M m, Y \a\t g:ia');
	$frontnews = "Last Edited by <a href=\"mailto:$authorEmail\">$author</a> on $currentTime\n";
	$frontnews .= $_REQUEST['news'];
		
	$newsFile = fopen("frontnews.txt", 'w');
	fwrite($newsFile, $frontnews);
	fclose($newsFile);
	
	print("<h2><center>Changes Submitted!</center></h2>");

}
// SPECIAL HARRY FUNCTION //
elseif($_REQUEST['action'] == 'harryfunc')
{
	$user = new Member($_REQUEST['lastName']);
	writeHeader('Member Area: Harry Only');
	
	if(!$user->authenticated($_REQUEST['password']))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	if(!$user->hasAccess('Webmaster'))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	//test functions and stuff go here
	$yeah = opendir("./minutes/leg_board");
	while($what = readdir($yeah))
	{
		if($what === '.' or $what === '..')
			continue;
			
		$okay = explode(".", $what); //0-minutes 1-mo 2-day 3-yr 4-doc
		$newfile = "minutes.20$okay[3].$okay[1].$okay[2].0304.doc";
		//rename("./minutes/leg_board/$what", "./minutes/leg_board/$newfile");
		
	}
	
	closedir($yeah);	
	print("michael moore is fat lol");

}
// EDIT PROFILE AND ACCOUNT SETTINGS PAGE //
elseif($_REQUEST['action'] == 'editprofile')
{
	$user = new Member($_REQUEST['lastName']);
	writeHeader('Member Area: Edit Your Profile and Account Settings');
	
	if(!$user->authenticated($_REQUEST['password']))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	print("<h2><center>Member Area: Edit Your Profile and Account Settings</center></h2>");
	print("<form action=$_SERVER[PHP_SELF]?action=submitprofile method=POST>");
	print("<strong>Name and Position:</strong> " . $user->getInfo('name and position'));
	print("<br><br><strong>Term:</strong> " . $user->getInfo('term'));
	print("<br><br><strong>Change Your Password (leave blank to not change it):</strong> <input type=password name=newpassword size=10>");
	print("<br><br><strong>Email:</strong> <input type=text name=email  size=20 value=\"" . $user->getInfo('email') . "\">");
	print("<br><br><strong>Phone Number (enter as nnnnnnnnnn):</strong> <input type=text name=phone maxlength=10 size=10 value=\"" . $user->getInfo('phone') . "\">");
	print("<br><br><strong>AIM:</strong> <input type=text name=aim size=20 value=\"" . $user->getInfo('aim') . "\">");
	print("<br><br><strong>Birthday (YYYY-MM-DD):</strong> <input type=text name=bday maxlength=10 size=10 value=" . $user->getInfo('bday') . ">");	
	print("<br><br><strong>Picture:</strong> (coming soon)");
	print("<br><br><strong>Bio:</strong><br><textarea name=bio rows=6 cols=50>" . $user->getInfo('bio') . "</textarea>");
	print("<br><br><input type=submit value='Submit Changes'> <input type=reset value='Cancel Changes'>");
}
// SUBMIT PROFILE AND ACCOUNT SETTINGS //
elseif($_REQUEST['action'] == 'submitprofile')
{
	$user = new Member($_REQUEST['lastName']);
	
	if(!$user->authenticated($_REQUEST['password']))
	{
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
	
	if($_REQUEST['newpassword'])
	{
		$user->setInfo('password', $_REQUEST['newpassword']);
		setcookie('password', "");
		setcookie('lastName', "");
	}
	
	writeHeader('Member Area: Profile and Account Settings Changes Submit');
	
	
	if(preg_match("/^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $_REQUEST['email']))
		$user->setInfo('email', $_REQUEST['email']);
	else
		print("Error: The submitted email address is invalid. Not recording email change.<br>");
	
	$user->setInfo('phone', $_REQUEST['phone']);
	
	$user->setInfo('aim', $_REQUEST['aim']);
	
	if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/i", $_REQUEST['bday']))
		$user->setInfo('bday', $_REQUEST['bday']);
	else
		print("Error: The submitted birthday is invalid. Not recording birthday.<br>");
	
	$user->setInfo('bio', $_REQUEST['bio']);
	
	$user->writeInfo2DB();
	
	print("<h2>Except for any errors displayed above, your changes have been saved.</h2>");
	
	
}
// LOGOUT: UNSET COOKIES AND REDIRECT TO MAIN PAGE //
elseif($_REQUEST['action'] == 'logout')
{
	setcookie('lastName', "");
	setcookie('password', "");
	
	header("Location: http://wcsc.ucsd.edu/");
}
// MAIN MEMBER AREA WITH ALL THE LINKS //
else
{
	$user = new Member($_REQUEST['lastName']);
	
	if(!$user->authenticated($_REQUEST['password']))
	{
		writeHeader('Member Area');
		print("<h1><center>Access Denied</center></h1>");
		writeFooter('member_area');
		die;
	}
		
	setcookie('lastName', $_REQUEST['lastName']);
	setcookie('password', $_REQUEST['password']);
	
	writeHeader('Member Area');
		
	print("<center><h2>Member Area</h2></center>\n");
	print("Greetings " . $user->getInfo('name and position') . " to the member area of the WCSC Website. You have access to the following links:<br><ul>\n");

	//upload/post pictures link
	//if($user->hasAccess('Executive Board'))
		//print("<li><a href=$_SERVER[PHP_SELF]?action=postpics>Upload pictures to the Pictures page</a><br>\n");

	//post news link - offline
	//if($user->hasAccess('Executive Board'))
	//	print("<li><a href=$_SERVER[PHP_SELF]?action=postnews>Post news on the front page</a><br>\n");
	
	//modify front page
	if($user->hasAccess('Executive Board'))
		print("<li><a href=$_SERVER[PHP_SELF]?action=modifyfront>Modify the news on the front page</a><br>\n");
		
	//change account settings
	print("<li><a href=$_SERVER[PHP_SELF]?action=editprofile>Edit your profile and account settings</a><br>\n");
	
	//logout
	print("<li><a href=$_SERVER[PHP_SELF]?action=logout>Logout</a><br>\n");
	
	print("</ul>\n");
}

writeFooter('member_area');
?>