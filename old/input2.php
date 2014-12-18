<?php 

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

function initialize()
{
	$dir = "data";
	if (is_dir($dir)) 
	{
		if ($dh = opendir($dir)) 
		{
			while (($file = readdir($dh)) !== false) 
			{
				if ($file[0] == ".") {continue;}
				try 
				{
					inserttable($dir."/".$file);
					print $dir."/".$file." added. <br />";
				}
				catch(exception $e) 
				{
					print $dir."/".$file." not added. <br />"; 
					continue;
				}
				
			}
		}
	}
}

function inserttable($file)
{
	$lines = file($file);
	$query = "DROP TABLE IF EXISTS ".trim($lines[0]);
	mysql_query($query);
	print $lines[0]."<br />";
	$error = "Could not create ".$lines[0].".<br />";
	$query = "CREATE TABLE ".$lines[0]."(Number int PRIMARY KEY, ".trim($lines[1]).")" or die($error.mysql_error());
	$lines[1] = explode(", ", $lines[1]); 
	print $query."<br />";
	mysql_query($query) or die($error.mysql_error());
	for ($i = 2; $i < count($lines); $i++)
	{
		$line = $lines[$i];
		$line = explode(", ", $line); 
		$index = $i - 1;
		$query = "INSERT INTO ".$lines[0]." VALUES(\"$index\", ";
		for ($j = 0; $j < count($line); $j++) 
		{
			$line[$j] = addslashes($line[$j]);
			$query .= "\"".$line[$j]."\", ";
		}
		$query = substr($query, 0, strlen($query)-3);
		$query .= ("\")");
		//print $query."<br />";
		mysql_query($query) or die(count($lines[1])." ".count($line)." ".$query."<br />".mysql_error()); 
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

?>

<html>
<head></head>
<body>

<?php
connect();
initialize();
//printquery("SELECT * FROM chartlookup");
?>

</body>
</html>