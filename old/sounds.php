<!doctype html>
<html lang=en>
	
	<head>
		<meta charset=utf-8>
		<title>Matthew Morrone's Homepage</title>
		<link rel="stylesheet" type="text/css" href="styles.css" />
		<script src="jquery.js"></script>
		<script>
		$(function() 
		{
			$("button").click(function(e) 
			{
				e.preventDefault();
				var song = $(this).next('audio').get(0);
				if (song.paused) {song.play();}
				else {song.pause();}
			});
		});
		</script>
	</head>
		
	<body>

<?php

$dir = "sounds"; 
$n = 1;
if (is_dir($dir)) 
{
	if ($dh = opendir($dir)) 
	{
		while (($file = readdir($dh)) !== false) 
		{
			if (strpos($file, ".mp3"))
			{
				print('<button id="button'.$n.'" class="play" title="'.$n.'">'.$file.'</button>');
				print('<audio id="audio'.$n.'" src="sounds/'.$file.'" preload=none></audio>');
				$n++;
			}
        }
	}
	closedir($dh);
}

?>

</body>
</html>