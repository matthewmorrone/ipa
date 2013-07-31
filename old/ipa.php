<?php

function language_select()
{
	print("<select name='language' id='language'>");
	print("<option value='all'>All</option>");
	$dir = "languages"; $number = 0;
	if (is_dir($dir)) {if ($dh = opendir($dir))
		{while (($file = readdir($dh)) !== false)
			{if ($file == "." || $file == ".." || substr($file, 0, 1) == ".") {continue;}
			$name = str_replace(".txt", "", $file);
			print("<option value='".$name."'>".ucfirst($name)."</option>");}}}
	print("</select>");
}

if ($_POST["mode"] == 1)
{
    language_select();
}



?>