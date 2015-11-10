<?php

error_reporting(0);

function echoecho() {
	//print out tables and dicts as nice tables
	foreach (func_get_args() as $i) {
		if (is_array($i)) {
			echo "<pre>";
			print_r($i); //var_dump($i);
			echo "</pre>";
		} else {
			echo $i;
		}
		echo "<br />";
	}
}
function xerror($query) {
	print("<br /><br />Error At:<br />" . $query . "<br />" . mysql_error() . "<br />");
}
function chomp($query, $c) {
	return substr($query, 0, strlen($query) - $c);
}

function load_sounds() {
	print("<section>");
	$dir = "sounds";
	$n = 1;
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (strpos($file, ".mp3")) {
					print('<button id="button' . $n . '" class="play" title="' . $n . '" value="' . $file . '"></button>');
					print('<audio id="sounds/' . $file . '" src="sounds/' . $file . '" preload=none></audio>');
					$n++;
				}
			}
		}
		closedir($dh);
	}
	print("</section>");
}
function languages($dir) {
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			echo ("<select id='language'>");
			echo ("<option>All</option>");
			while (($file = readdir($dh)) !== false) {
				if ($file == "." || $file == ".." || substr($file, 0, 1) == ".") {continue;}
				echo ("<option value='$file' ");
				if ($file == "IPA.txt") {
					echo ("selected");
				}
				echo (">" . ucfirst(chomp($file, 4)) . "</option>");
			}
			echo ("</select>");
		}
	}
}
function search($lookup, $letters) {foreach ($letters as $l) {if ($l[0] == $lookup) {return 1;}}}
function print_chart($reference) {
	$filename = $_POST["filename"];
	$name = ucfirst(chomp($filename, 4));
	$letters = file("languages/" . $filename);
	foreach ($letters as &$letter) {
		$letter = explode(", ", $letter);
	}
	$lookup = file("tables/" . $reference . ".txt");
	for ($x = 0; $x < count($lookup); $x++) {
		$lookup[$x] = explode(',', $lookup[$x]);
	}

	$x = $lookup[0][0];
	$y = $lookup[0][1];
	$k = 1;

	echo "<div class='half'>";
	print('<table id="' . $reference . '">');
	print('<tr>');
	$k += 2;
	print('<th></th>');
	for ($i = 0; $i < ($x / 2); $i++) //&nbsp;
	{
		$caption = str_replace("http://en.wikipedia.org/wiki/", "", $lookup[$k][2]);
		$caption = str_replace("_", " ", $caption);
		$caption = ucwords($caption);
		print('<th colspan="2" class="rotate" id="' . $lookup[$k][1] . '"><input type="checkbox" checked="checked" style="display: none;"><div><a href="' . $lookup[$k][2] . '" title="' . $caption . '">' . $lookup[$k][1] . '</a></div></input></th>');
		$k++;
	}
	print('</tr>');
	for ($j = 0; $j < $y; $j++) {
		print('<tr>');
		$caption = str_replace("http://en.wikipedia.org/wiki/", "", $lookup[$k][2]);
		$caption = str_replace("_", " ", $caption);
		$caption = ucwords($caption);
		$lookup[$k][3] = chomp($lookup[$k][3], 1);
		print("<th class='side' id='" . $lookup[$k][1] . "'><input type='checkbox' checked='checked' style='display: none;'><div><a href='" . $lookup[$k][2] . "' title='" . $caption . "'>" . $lookup[$k][1] . "</a></div></input></th>");
		$k++;
		for ($i = 1; $i < $x + 1; $i++) {
			if ($lookup[$k][1] != '' && (search($lookup[$k][1], $letters) || $filename == "All")) {
				$caption = str_replace("http://en.wikipedia.org/wiki/", "", $lookup[$k][2]);
				$caption = str_replace("_", " ", $caption);
				$caption = ucwords($caption);
				if ($lookup[$k][4] != '') {
					$caption .= " (" . $lookup[$k][4] . ")";
				}
				$lookup[$k][5] = substr($lookup[$k][5], 0, strlen($lookup[$k][5]) - 1);
				print("<td number='" . $lookup[$k][4] . "' title='" . $caption . "' id='" . $lookup[$k][1] . "'><a href='" . $lookup[$k][2] . "' title='" . $caption . "' class='sound' target='_blank'>" . $lookup[$k][1] . "</a>"); // onmouseover='display(".$lookup[$k][1].")' onmouseout='display(\"\")' class=sound ondblclick='ipaappend(".$lookup[$k][1].")'
				if ($lookup[$k][3] != "") {
					print('<audio id="' . $lookup[$k][3] . '" src="' . $lookup[$k][3] . '" preload=none></audio>');
				}
				print("</td>");
			} else {
				print("<td></td>");
			}
			$k++;
		}
		print('</tr>');
	}
	print("</table>");
	print("<table id='" . $lookup[1][0] . "' class=sub><tr><th class='third'><input type='checkbox' checked='checked' style='display: none;'><a title='" . $lookup[1][1] . "'>" . $lookup[1][1] . "</a></input></th><th class='third'><input type='checkbox' checked='checked' style='display: none;'><a title='" . $lookup[1][2] . "'>" . $lookup[1][2] . "</a></input></th></tr></table>");
	echo "</div>";
}
function other_tables($name) {
	$file = file("tables/" . strtolower($name) . ".txt");
	$result = "<table>";
	$result .= "<tr><th style='text-align: center;' colspan='" . count(explode(",", $file[0])) . "'>$name</th></tr>";
	foreach ($file as $line) {
		$result .= "<tr>";
		$line = explode(",", $line);
		foreach ($line as $key => $part) {

			if ($key % 2 == 0) {
				$result .= "<td class=\"clickah\">" . $part . "</td>";

			} else {
				$result .= "<td>" . $part . "</td>";
			}
		}
		$result .= "</tr>";
	}
	$result .= "</table>";
	return $result;
}

$mode = $_POST["mode"];
$dir = "languages";

if ($mode == 1) {
	languages($dir);
}
if ($mode == 2) {
	print_chart("consonants");
	print_chart("vowels");
}
if ($mode == 3) {
	$filename = $_POST["filename"] . ".txt";
	$file = fopen("new_languages/" . $filename, 'w');
	$lines = $_POST["letters"];
	print_r($lines);
	foreach ($lines as $i) {
		fwrite($file, $i . "\r\n");
	}
	fclose($file);
	echo "Thank you for submitting the $filename language!";
}
if ($mode == 4) {
	echo other_tables($_POST["table"]);
}

?>