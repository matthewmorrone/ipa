<html>
<head>
	<title>Interactive IPA Chart</title>
</head>
<body>

<span id="dummy"></span>



</body>
</html>






<?php

$lookup=file('chartlookup_V3.txt') or die(mysql_error());
for ($x=0; $x<count($lookup); $x++) {$lookup[$x]=explode(',', $lookup[$x]);}
print('<div><span><table border="1px" id="Consonants" title="Consonants (Pulmonic)">');

print('<tr>'); $k=1;
print('<th></th>');
for ($i=1; $i<6; $i++) {print('<th colspan='.$lookup[$k][2].' rowspan='.$lookup[$k][3].'><a href=\''.$lookup[$k][4].'\' target="_blank">'.$lookup[$k][1].'</a></th>'); $k++;}
print('</tr>');

print('<tr>'); $k++;
print('<th></th>');
for ($i=2; $i<14; $i++) {print('<th colspan='.$lookup[$k][2].' rowspan='.$lookup[$k][3].'><input type="checkbox" checked="checked" name="place" value='.$i.' onchange="toggle(\'Consonants\', this, 1)"><br /><a href='.$lookup[$k][4].' target="_blank">'.$lookup[$k][1].'</a></input></th>'); $k++;}
print('</tr>');

for ($j=0; $j<11; $j++)
{
	print('<tr>');
	$caption=str_replace("http://en.wikipedia.org/wiki/", "", $lookup[$k][4]);
	$caption=str_replace("_", " ", $caption);
	$caption=ucwords($caption);
	$lookup[$k][5]=substr($lookup[$k][5], 0, strlen($lookup[$k][5])-1);
	print("<th title='".$caption."'  class='side' id='".$lookup[$k][1]."' colspan='".$lookup[$k][2]."' rowspan='".$lookup[$k][3]."' style='visibility: visible;'><input type='checkbox' checked='checked' name='place' value=".($j+2)." onchange='toggle(\"Consonants\", this, 2)'><a title='".$caption."' href='".$lookup[$k][4]."' target='_blank'>".$lookup[$k][1]."</a></input></th>"); 
	$k++;
	for ($i=1; $i<25; $i++)
	{
		if ($lookup[$k][1] != '') 
		{
			$caption=str_replace("http://en.wikipedia.org/wiki/", "", $lookup[$k][4]);
			$caption=str_replace("_", " ", $caption);
			$caption=ucwords($caption);
			if ($lookup[$k][6] != '') {$caption .= " (".$lookup[$k][6].")";}
			$lookup[$k][7]=substr($lookup[$k][7], 0, strlen($lookup[$k][7])-1);
			print("<td title='".$caption."' id='".$lookup[$k][1]."' colspan='".$lookup[$k][2]."' rowspan='".$lookup[$k][3]."' style='visibility: visible;'><a title='".$caption."' target='_blank' onmouseover='display(".$lookup[$k][1].")' onmouseout='display(\"\")' ondblclick='ipaappend(".$lookup[$k][1].")' onclick='playsound(\"".$lookup[$k][5]."\")'>".$lookup[$k][1]."</a></td>");
			$k++;
		}
		else {print("<td colspan='".$lookup[$k][2]."' rowspan='".$lookup[$k][3]."'></td>"); $k++;}
	}
	print('</tr>');
}

print("</table>");
print("<table id='vchoice' border='1px'><th><a href='http://en.wikipedia.org/wiki/Voice_onset_time'>Voicing</a></th><th><input type='checkbox' checked='checked' name='voice' value='1' onchange='toggle(\"Consonants\", this, 3)'><a href='http://en.wikipedia.org/wiki/Voicelessness'>Voiceless</a></input></th><th><input type='checkbox' checked='checked' name='voice' value='2' onchange='toggle(\"Consonants\", this, 3)'><a href='http://en.wikipedia.org/wiki/Voice_%28phonetics%29'>Voiced</a></input></th></tr></table>");

print("<textarea id='output'></textarea>");
print("<button onclick='document.getElementById('output').focus(); document.getElementById('output').select();' type='button'>Select</button>");
print("<button onclick='document.getElementById('output').value = \'\'' type='button'>Clear</button>");
print("<button onclick='copyToClipboard(document.getElementById('output').value)'>Copy</button>");
print("<button onclick='if ((document.getElementById('output').style.fontWeight == 'bold') || (document.getElementById('output').style.fontWeight == '700')) {document.getElementById('output').style.fontWeight = 'normal';} else {document.getElementById('output').style.fontWeight = 'bold';};' title='bold'><b>B</b></button>");
print("<button onclick='if (document.getElementById('output').style.fontStyle == 'italic') {document.getElementById('output').style.fontStyle = 'normal';} else {document.getElementById('output').style.fontStyle = 'italic';};' title='italic'><i>I</i></button>");
print("<button onclick='if (document.getElementById('output').style.textDecoration == 'underline') {document.getElementById('output').style.textDecoration = 'none';} else {document.getElementById('output').style.textDecoration = 'underline';};' title='underline'><u>U</u></button>");
print("<button onclick='if (document.getElementById('output').style.textDecoration == 'line-through') {document.getElementById('output').style.textDecoration = 'none';} else {document.getElementById('output').style.textDecoration = 'line-through';};' title='strikethrough'><s>S</s></button>");
print('</span><span>');

print('<table border="1px" id="Vowels" title="Vowels">');
$lookup=file('vowellookup.txt') or die(mysql_error());
for ($x=0; $x<count($lookup); $x++) {$lookup[$x]=explode(',', $lookup[$x]);}
print('<tr>'); $k=0;
print('<th></th>');
for ($i=0; $i<5; $i++) {print('<th colspan='.$lookup[$k][2].' rowspan='.$lookup[$k][3].'><input type="checkbox" checked="checked" name="frontness" value='.$i.' onchange="toggle(\'Vowels\', this, 1)"><br /><a href='.$lookup[$k][4].' target="_blank">'.$lookup[$k][1].'</a></input></th>'); $k++;}
print('</tr>');

for ($j=0; $j<7; $j++)
{
	print('<tr>');
	$caption=str_replace("http://en.wikipedia.org/wiki/", "", $lookup[$k][4]);
	$caption=str_replace("_", " ", $caption);
	$caption=ucwords($caption);
	$lookup[$k][5]=substr($lookup[$k][5], 0, strlen($lookup[$k][5])-1);
	print("<th title='".$caption."' class='side' id='".$lookup[$k][1]."' colspan='".$lookup[$k][2]."' rowspan='".$lookup[$k][3]."' style='visibility: visible;'><input type='checkbox' checked='checked' name='openness' value=".($j+2)." onchange='toggle(\"Vowels\", this, 2)'><a title='".$caption."' href='".$lookup[$k][4]."' target='_blank'>".$lookup[$k][1]."</a></input></th>"); 
	$k++;
	for ($i=0; $i<10; $i++)
	{
		if ($lookup[$k][1] != '') 
		{	
			if (($j == 3 && $i == 9) || ($j == 5 && $i == 9)) {$skip = true; continue;}
			$caption=str_replace("http://en.wikipedia.org/wiki/", "", $lookup[$k][4]);
			$caption=str_replace("_", " ", $caption);
			if ($lookup[$k][6] != '') {$caption .= " (".$lookup[$k][6].")";}
			$lookup[$k][7]=substr($lookup[$k][7], 0, strlen($lookup[$k][7])-1);
			print("<td title='".$caption."' id='".$lookup[$k][1]."' colspan='".$lookup[$k][2]."' rowspan='".$lookup[$k][3]."' style='visibility: visible;'><a title='".$caption."' target='_blank' onmouseover='display(".$lookup[$k][1].")' onmouseout='display(\"\")' ondblclick='ipaappend(".$lookup[$k][1].")' onclick='playsound(\"".$lookup[$k][5]."\")'>".$lookup[$k][1]."</a></td>");
			$k++;
		}
		else {print("<td colspan='".$lookup[$k][2]."' rowspan='".$lookup[$k][3]."'></td>"); $k++;}
		if ($skip) {continue;}
	}
	print('</tr>');
}

print("</table>");
print("<table border='1px' id='rchoice'><a href='http://en.wikipedia.org/wiki/Roundedness'><th>Roundedness</th></a><th><input type='checkbox' checked='checked' name='round' value='1' onchange='toggle(\"Vowels\", this, 3)'><a href='http://en.wikipedia.org/wiki/Less_rounded#Less_rounded'>Unrounded</a></input></th><th><input type='checkbox' checked='checked' name='voice' value='2' onchange='toggle(\"Vowels\", this, 3)'><a href='http://en.wikipedia.org/wiki/More_rounded#More_rounded'>Rounded</a></input></th></tr></table></span></div>");

print("<form method='post' action=''>");
print("<input type='radio' name='language' value=''>All</input><br />");
$dir = "languages"; 
$number = 0;
if (is_dir($dir)) 
{
	if ($dh = opendir($dir)) 
	{
		while (($file = readdir($dh)) !== false) 
		{
			if ($file == "." || $file == "..") {continue;}
			print("<input type='radio' name='language' value='".$file."'>".ucfirst(str_replace(".txt", "", $file))."</input><br />");
		}
	}
}
print("<input type='submit' value='Submit' />");
print("</form>");

?>