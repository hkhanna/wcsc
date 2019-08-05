<?php

class Member
{
	var $lastName;
	var $firstName;
	var $password;
	var $email;
	var $phone;
	var $aim;
	var $position; // this is an array of all the positions held
	var $board; // this is an array of all the boards the member is a part of
	var $bday;
	var $term_start;
	var $term_end;
	var $bio; //this MUST be the last column in the database or the sql query for writeInfo2DB wont be formed correctly
	
	function Member($lastName = NULL)
	{
		if($lastName)
		{
			$dbh=mysql_connect ("hops.ucsd.edu", "wcsc", "wcsc45") or die ('Error: I cannot connect to the database in Member constructor because: ' . mysql_error());
			mysql_select_db("wcsc");
		
			$query = "SELECT * FROM members WHERE lastName='$lastName'";
			$dbResult = mysql_query($query, $dbh) or die('Error: I cannot execute query in Member constructor because: ' . mysql_error());

			$row = mysql_fetch_array($dbResult);
			
			foreach ($row as $key => $value)
			{
				if($key === 'position')
					$this->position = explode(":", $value);
				elseif($key === 'board')
					$this->board = explode(":", $value);
				else
					$this->$key = $value;
			}
			
			mysql_close($dbh);
		
		}
	}	
		
	function getInfo($info)
	{
		switch($info)
		{
			case 'name':
				return $this->firstName . ' ' . $this->lastName;
				break;
			case 'first name':
				return $this->firstName;
				break;
			case 'last name':
				return $this->lastName;
				break;
			case 'name and position':
				$returnStr = $this->firstName . ' ' . $this->lastName . " (" . $this->position[0];
				
				//change this to have unlimited positions
				if($this->position[1])
					$returnStr .= ", " . $this->position[1];
				if($this->position[2])
					$returnStr .= ", " . $this->position[2];
				$returnStr .= ")";
				return $returnStr;
				break;
			case 'term':
				return $this->term_start . " to " . $this->term_end;
				break;
			default:
				return $this->$info;
				break;
		}
	}
	
	function setInfo($field, $value)
	{
		$this->$field = $value;
	}
	
	function writeInfo2DB()
	{
		$dbh=mysql_connect ("hops.ucsd.edu", "wcsc", "wcsc45") or die ('Error: I cannot connect to the database in Member constructor because: ' . mysql_error());
		mysql_select_db("wcsc");
		
		$query = "SHOW COLUMNS FROM members";
		$dbResult = mysql_query($query, $dbh) or die('Error: I cannot execute select query in writeInfo2DB because: ' . mysql_error());

		$query = "UPDATE members SET ";
		
		while($col = mysql_fetch_row($dbResult))
		{
			if($col[0] === 'lastName' or $col[0] === 'position' or $col[0] === 'board')
				continue;
			
			$query .= "$col[0]='" . $this->$col[0] . "'";
		
			if($col[0] !== 'bio')
				$query .= ', ';
		}

		$query .= " WHERE lastname='" . $this->lastName . "'";
		
		$dbResult = mysql_query($query, $dbh) or die('Error: I cannot execute update query in writeInfo2DB because: ' . mysql_error());
		
		mysql_close($dbh);
	}
	
	function authenticated($password)
	{
		return ($password === $this->password) ? 1 : 0;
	}
	
	
	function hasAccess($accessCode)
	{

		$accesspos = explode(":", $accessCode);
		if(array_intersect($this->position, $accesspos))
			return 1;
		elseif(array_intersect($this->board, $accesspos))
			return 1;
		else
			return 0;
	}

};

function whoIs($position)
{
	$dbh=mysql_connect ("hops.ucsd.edu", "wcsc", "wcsc45") or die ('Error: I cannot connect to the database in Member constructor because: ' . mysql_error());
	mysql_select_db("wcsc");
		
	$query = "SELECT lastName FROM members WHERE INSTR(position, '$position')";
	$dbResult = mysql_query($query, $dbh) or die('Error: I cannot execute query in whoIs function because: ' . mysql_error());

	mysql_close($dbh);
	
	while($row = mysql_fetch_array($dbResult))
		$member[] = new Member($row[0]);
	
	return $member;
}

function writeHeader($title)
{
	print <<<END

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Warren College Student Council: $title</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="rollover.js"></script>
<link rel=StyleSheet href="style.css" type="text/css">
</head>

<body onload="preloadImages();">
<div id="main_table">
	<div id="wcsc-logo">
		<img src="images/wcsc_logo.gif" width="122" height="91" alt="" />
	</div>
	<div id="top-banner">
		<img src="images/top_banner.gif" width="598" height="90" alt="" />
	</div>
	<div id="text-area">

END;
}

function writeFooter($selected)
{
	
	$categories = array('home', 'constitution', 'leg_board', 'events_board', 'judicial_board', 'elections', 'pictures', 'calendar', 'forms_docs', 'member_area');
	foreach($categories as $value)
	{
		($selected == $value) ? $img[$value] = $value . '-s.gif' : $img[$value] = $value . '.gif';
		
		$over_string = $value . '_over';
		($selected == $value) ? $img[$over_string] = $value . '-over-s.gif' : $img[$over_string] = $value . '-over.gif';
	}
	
	$counter = file('harrycounter');
	
	print <<<END
	<br><br>
	<center><font size=-3><br><br><br><br>
	$counter[0] folks have peeped this website since $counter[1]<br>
	Contact <a href="mailto:wcscweb@ucsd.edu">Harry Khanna</a> for website-related things<br>
	Check out the website <a href="todo.txt">todo list</a> to see what's in store<br>
  Copyright (c) 2004 Regents of the University of California. All rights reserved.<br><br>
  <a href="http://validator.w3.org/check?uri=referer"><img border="0" src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01!" height="31" width="88"></a> <a href="http://jigsaw.w3.org/css-validator/"><img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!"></a>
  </font></center>
	</div>
	<div id="home">
		<a href="index.php"
			onmouseover="changeImages('home', 'images/$img[home_over]'); return true;"
			onmouseout="changeImages('home', 'images/$img[home]'); return true;">
			<img name="home" src="images/$img[home]" width="122" height="21" border="0" alt="" /></a>
	</div>
	<div id="constitution">
		<a href="documents/constitution.Warren_College_Student_Body.2004.10.16.pdf"
			onmouseover="changeImages('constitution', 'images/$img[constitution_over]'); return true;"
			onmouseout="changeImages('constitution', 'images/$img[constitution]'); return true;">
			<img name="constitution" src="images/$img[constitution]" width="122" height="23" border="0" alt="" /></a>
	</div>
	<div id="leg-board">
		<a href="leg_board.php"
			onmouseover="changeImages('leg_board', 'images/$img[leg_board_over]'); return true;"
			onmouseout="changeImages('leg_board', 'images/$img[leg_board]'); return true;">
			<img name="leg_board" src="images/$img[leg_board]" width="122" height="21" border="0" alt="" /></a>
	</div>
	<div id="events-board">
		<a href="events_board.php"
			onmouseover="changeImages('events_board', 'images/$img[events_board_over]'); return true;"
			onmouseout="changeImages('events_board', 'images/$img[events_board]'); return true;">
			<img name="events_board" src="images/$img[events_board]" width="122" height="21" border="0" alt="" /></a>
	</div>
	<div id="judicial-board">
		<a href="judicial_board.php"
			onmouseover="changeImages('judicial_board', 'images/$img[judicial_board_over]'); return true;"
			onmouseout="changeImages('judicial_board', 'images/$img[judicial_board]'); return true;">
			<img name="judicial_board" src="images/$img[judicial_board]" width="122" height="21" border="0" alt="" /></a>
	</div>
	<div id="elections">
		<a href="elections.php"
			onmouseover="changeImages('elections', 'images/$img[elections_over]'); return true;"
			onmouseout="changeImages('elections', 'images/$img[elections]'); return true;">
			<img name="elections" src="images/$img[elections]" width="122" height="22" border="0" alt="" /></a>
	</div>
	<div id="pictures">
		<a href="pictures.php"
			onmouseover="changeImages('pictures', 'images/$img[pictures_over]'); return true;"
			onmouseout="changeImages('pictures', 'images/$img[pictures]'); return true;">
			<img name="pictures" src="images/$img[pictures]" width="122" height="21" border="0" alt="" /></a>
	</div>
	<div id="calendar">
		<a href="calendar.php"
			onmouseover="changeImages('calendar', 'images/$img[calendar_over]'); return true;"
			onmouseout="changeImages('calendar', 'images/$img[calendar]'); return true;">
			<img name="calendar" src="images/$img[calendar]" width="122" height="21" border="0" alt="" /></a>
	</div>
	<div id="forms-docs">
		<a href="forms_docs.php"
			onmouseover="changeImages('forms_docs', 'images/$img[forms_docs_over]'); return true;"
			onmouseout="changeImages('forms_docs', 'images/$img[forms_docs]'); return true;">
			<img name="forms_docs" src="images/$img[forms_docs]" width="122" height="21" border="0" alt="" /></a>
	</div>
	<div id="member-area">
		<a href="member_area.php"
			onmouseover="changeImages('member_area', 'images/$img[member_area_over]'); return true;"
			onmouseout="changeImages('member_area', 'images/$img[member_area]'); return true;">
			<img name="member_area" src="images/$img[member_area]" width="122" height="21" border="0" alt="" /></a>
	</div>
	<div id="menu-bottom">
		<img src="images/menu_bottom.gif" width="122" height="57" alt="" />
	</div>
</div>
</body>
</html>
	
END;
}

function revertTimeStamp($timestamp)
{
	$year=substr($timestamp,0,4);
	$month=substr($timestamp,4,2);
	$day=substr($timestamp,6,2);
	$hour=substr($timestamp,8,2);
	$minute=substr($timestamp,10,2);
	$second=substr($timestamp,12,2);
	$newdate=mktime($hour,$minute,$second,$month,$day,$year);
	return $newdate;
}
    
?>