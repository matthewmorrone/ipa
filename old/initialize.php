<?php

function initialize1() 
{

	//mysql_query("DROP TABLE IF EXISTS Coding") or die("Could not drop coding table. <br />".mysql_error());
	mysql_query("DROP TABLE IF EXISTS Existence") or die("Could not drop coding table. <br />".mysql_error());



	//mysql_query("CREATE TABLE Coding(
	//	IPANumber		varchar(255) PRIMARY KEY, 
	//	Type			text,
	//	Mechanism		text,
	//	Place		text,
	//	Manner		text,
	//	Voicing		text

	//);") or die("Could not create Coding table. <br />".mysql_error());

	//$lines = file("Coding_Consonants.txt");
	//for ($i = 0; $i < count($lines); $i++)
	//{
	//	$line = explode(",", $lines[$i]);
	//	mysql_query("INSERT INTO Coding VALUES('$line[0]', '$line[1]', '$line[2]', '$line[3]', '$line[4]', '$line[5]')");
	//}
	
	mysql_query("CREATE TABLE Existence(
		Type			text,
		Mechanism		text,
		Place		text,
		Manner		text,
		Voicing		text,
		Existence		text,
		English		text

	);") or die("Could not create Existence table. <br />".mysql_error());
	
	$lines = file("Existence_Consonants.txt");
	for ($i = 0; $i < count($lines); $i++)
	{
		$line = explode(",", $lines[$i]);
		mysql_query("INSERT INTO Existence VALUES('$line[0]', '$line[1]', '$line[2]', '$line[3]', '$line[4]', '$line[5]', $line[6]')");
	}
}

function connect() 
{
  	$host = mysql_connect("localhost", "root", "") or die(mysql_error());
  	if ($host)
  	{
  		$database = mysql_query('CREATE DATABASE IF NOT EXISTS ipa') or die(mysql_error());
		if ($database) {mysql_select_db('ipa') or die(mysql_error());}
  	} 
	else {die (mysql_error());}
}

function initialize2()
{
	mysql_query("DROP TABLE IF EXISTS consonants") or die(mysql_error());	

	mysql_query("CREATE TABLE consonants(number int PRIMARY KEY, type text, mechanism text, place text, manner text, voicing text);") or die(mysql_error());
	$lines = file("consonants.txt") or die(mysql_error()); 
	for ($i = 0; $i < count($lines); $i++)
	{
		$line = explode(" ", $lines[$i]); 
		mysql_query("INSERT INTO consonants VALUES('$line[0]', '$line[1]', '$line[2]', '$line[3]', '$line[4]', '$line[5]');") or die(mysql_error());
	}		
}

function printquery($query) 
{
	trim($query);
	$query = stripslashes($query);
	$result = mysql_query($query) or die(mysql_error());
	print "<table border='1px'>";
	$row = mysql_fetch_array($result);
	$num_rows = mysql_num_rows($result) or die(mysql_error());
	$num_fields = mysql_num_fields($result) or die(mysql_error());
	print "<tr>";
	$keys = array_keys($row) or die(mysql_error());
	for ($index = 0; $index < $num_fields; $index++) {print "<th>".$keys[2*$index+1]."</th>";}
 	print "</tr><tr>";
	$values = array_values($row) or die(mysql_error());
	for ($index = 0; $index < $num_fields; $index++) 
	{
		$value = htmlspecialchars($values[2 * $index + 1]);
		print "<td>".$value."</td>";
	}
	print "</tr>";	
	while ($row = mysql_fetch_array($result)) 
	{
  		print "<tr>";
		$values = array_values($row) or die(mysql_error());
		for ($index = 0; $index < $num_fields; $index++) 
		{
			$value = htmlspecialchars($values[2 * $index + 1]);
			print "<td>" . $value . "</td>";
		}
		print "</tr>";
	}
	print "</table>";
}

function getquery($query) 
{
	$result = mysql_query(stripslashes(trim($query))) or die(mysql_error());	
	$row = mysql_fetch_array($result) or die(mysql_error());
	$num_rows = mysql_num_rows($result) or die(mysql_error());
	$num_fields = mysql_num_fields($result) or die(mysql_error());
	$x = 0; $keys = array_keys($row) or die(mysql_error());
	for ($y = 0; $y < $num_fields; $y++) 	{$table[$x][$y] = $keys[2*$y+1];} $x++;
	while ($row = mysql_fetch_array($result)) 
	{
		$values = array_values($row) or die(mysql_error());
		for ($y = 0; $y < $num_fields; $y++) {$table[$x][$y] = $values[2*$y+1];} $x++; $y = 0;
	}
	return $table;
}


connect();
initialize();
//printquery('SELECT * FROM Coding');
//printquery('SELECT * FROM consonants');
printquery('SELECT * FROM Existence');
?>