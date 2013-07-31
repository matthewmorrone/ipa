<?php 
	include("functions.php");
	if (!$_GET) 
	{
		$_GET["language"] = "IPA";
	} 
?>
<!doctype html>
<html>
	<head>
		<title>
		<?php 
		if ($_GET) 
		{
			if ($_GET["language"] != "all" && $_GET["language"] != "IPA" && $_GET["language"] != "") 
			{
				print(ucfirst(str_replace(".txt", "", $_GET["language"])).": ");
			}
		} 
		?>
		IPA Chart
		</title>
		<link rel="shortcut icon" type="image/x-icon" href="psi.gif"></link>
		<link rel="stylesheet" type="text/css" href="styles.css"></link>
		<script src="jquery.js"></script>
	</head>
	<body>
		<div id="container">
			<div id=header>
				<a href="/" class=image><img src="psi.gif" /></a>
				<?php 
				if ($_GET) 
				{
					if ($_GET["language"] != "all" && $_GET["language"] != "IPA" && $_GET["language"] != "") 
					{
						print(ucfirst(str_replace(".txt", "", $_GET["language"])).": ");
					}
				} 
				?>
				IPA Chart
			</div>	
			<div>
				<div>
					<div><?php print_chart("consonants.txt", $language, $letters); ?></div>
				</div>
				<div>
					<div><?php print_chart("vowels.txt", $language, $letters); ?></div>
				</div>
			</div>				
				
				

			<div id=outtext>
				<button onclick='$("#"+"output").focus(""); $("#"+"output").select();' type="button">Select</button>
				<button onclick='$("#"+"output").val("")' type="button">Clear</button>
				<textarea id="output"></textarea>
			</div>
			<div id=footer>
				Please read the <a href='about.html' class="email">about</a> page.<br />
				Feel free to <a href='input.php' class="email">submit a language</a> for addition to the chart.<br />
				Make sure your browser is set to standard <a href='http://www.unc.edu/~jlsmith/ipa-fonts.html' class="email">Unicode</a> fonts in order for the page to properly render.<br />
				This utility is not configured for Internet Explorer. Designed in <a href='http://www.getfirefox.net/' class="email">Mozilla Firefox</a>.<br />
				Please contact me with any suggestions for features or any errors encountered.<br />
				Created: 10 Feb 2012, Last Updated: 14 Feb 2012<br />
				©2012 Matthew Morrone <a class="email" href="mailto:mam315@pitt.edu">mam315@pitt.edu</a>
			</div>
		</div>
	</body>
</html>