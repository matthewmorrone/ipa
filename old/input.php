<html>
<head>
<title>Language Submission</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
<?php include("functions.php"); error_reporting(0); ?>
</head>
<a href="index.php" class="email">Back</a><br />
<?php

if ($_POST)
{
	print_r($_POST);
	$filename = $_POST["filename"].".txt";
	$file = fopen("new_languages/".$filename, 'w');
	$lines = $_POST["letters"];
	foreach ($lines as $i)
	{
		fwrite($file, $i."\r\n");
	}
	fclose($file);
}

function chart($file)
{
	$lookup=file($file) or die(mysql_error());
	for ($x=0; $x<count($lookup); $x++) {$lookup[$x]=explode(',', $lookup[$x]);}
	$x = $lookup[0][0]; $y = $lookup[0][1];
	$k=1;
	$name = ucfirst(str_replace(".txt", "", $filename));
	print('<table border="1px" id="'.$name.'" title="'.$name.'">');
	print('<tr>'); $k++;
	print('<th></th>');
	for ($i=0; $i<(($x/2)); $i++) {print('<th colspan="2"><a href='.$lookup[$k][2].' target="_blank">'.$lookup[$k][1].'</a></th>'); $k++;}
	print('</tr>');
	for ($j=0; $j<$y; $j++)
	{
		print('<tr>');
		$caption=str_replace("http://en.wikipedia.org/wiki/", "", $lookup[$k][2]);
		$caption=str_replace("_", " ", $caption);
		$caption=ucwords($caption);
		$lookup[$k][3]=substr($lookup[$k][3], 0, strlen($lookup[$k][3])-1);
		print("<th title='".$caption."' class='side' id='".$lookup[$k][1]."' style='visibility: visible;'><a title='".$caption."' href='".$lookup[$k][2]."' target='_blank'>".$lookup[$k][1]."</a></th>"); 
		$k++;
		for ($i=1; $i<$x+1; $i++)
		{
			if ($lookup[$k][1] != '')
			{
				if ($language = "all")
				{
					$caption=str_replace("http://en.wikipedia.org/wiki/", "", $lookup[$k][2]);
					$caption=str_replace("_", " ", $caption);
					$caption=ucwords($caption);
					if ($lookup[$k][4] != '') {$caption .= " (".$lookup[$k][4].")";}
					$lookup[$k][5]=substr($lookup[$k][5], 0, strlen($lookup[$k][5])-1);
					print("<td title='".$caption."' id='".$lookup[$k][1]."' style='visibility: visible;'><input type='checkbox' name='letters[]' id='".$lookup[$k][1]."' value='".$lookup[$k][1].", ".$lookup[$k][4]."'>".$lookup[$k][1]."</input></a></td>");
				}
			}
			else {print("<td></td>");}
			$k++;
		}
		print('</tr>');
	}
	print("</table>");
}

print("<form method='post' action=''>");
chart("consonants.txt");
chart("vowels.txt");
print("</table><input type='text' name='filename' /><br /><input type='submit' value='Submit'></form>");
?>


<!-- 
<form name="f1">
Modifications to your coffee order:<br >
&nbsp; <input type="checkbox" name="cb1">
<span onClick="document.f1.cb1.checked=(! document.f1.cb1.checked);">
Add whipped cream to your drink (add $0.50)</span>
</form>
 -->


</body>
</html>