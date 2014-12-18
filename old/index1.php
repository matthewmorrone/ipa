<?php
// error_reporting(0);
include("functions.php");
if (!$_GET) {$_GET["language"] = "IPA";}
?>
<!doctype html>
<html lang=en>
	<head>
		<meta charset=utf-8>
		<title><?php if ($_GET) {if ($_GET["language"] != "all" && $_GET["language"] != "IPA" && $_GET["language"] != "") {print(ucfirst(str_replace(".txt", "", $_GET["language"])).": ");}} ?>An Interactive IPA Chart</title>
		<script src="../../index/jquery.js"></script>
		<script src="functions.js?<?php echo(date("H:i:s"));?>"></script>
		<link rel="shortcut icon" type="image/x-icon" href="psi.gif"></link>
		<link rel="stylesheet" type="text/css" href="styles.css"></link>
	</head>
	<body>
		<div id=container>
			<div id=header>
				<a href="/" class=image><img src="psi.gif" /></a>
				<?php if ($_GET) {if ($_GET["language"] != "all" && $_GET["language"] != "IPA" && $_GET["language"] != "") {print(ucfirst(str_replace(".txt", "", $_GET["language"])).": ");}} ?>
				An Interactive IPA Chart
			</div>
			<div id=content>
				<div id="outer">
					<div id="middle">
						<div id="inner">
							<div id=col1>
								<?php
									$language = "all";
									if ($_GET) {$language = $_GET["language"];}
									if ($_GET["language"] != "all" && $_GET["language"] != "")
									{
										$file = file("languages/".$_GET["language"].".txt");
										$letters = array();
										foreach ($file as $letter)
										{
											$array = explode(",", $letter);
											$letters[trim($array[1])] = trim($array[0]);
										}
									}
									language_select($file, $language);
								?>
								<p id=display></p>
							</div>
							<div id=col2>
								<div class="block">
									<div class="centered">
										<span><?php print_chart("consonants.txt", $language, $letters); ?></span>
									</div>
								</div>
							</div>
							<div id=col3>
								<div id=sec1>
									<div class="block">
										<div class=centered>
											<span><?php print_chart("vowels.txt", $language, $letters); ?></span>
										</div>
									</div>
								</div>
								<div id=sec2>
									<?php other_tables(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<div id=outtext>
				<textarea id="output"></textarea>
				<button onclick="$("#"+"output")[0].focus(); $("#"+"output")[0].select();" type="button">Select</button>
				<button onclick="$("#"+"output")[0].value = ''" type="button">Clear</button>
				<button onclick="copyToClipboard($("#"+"output")[0].value)">Copy</button>
				<button onclick="if (($("#"+"output")[0].style.fontWeight == "bold") || ($("#"+"output")[0].style.fontWeight == "700")) {$("#"+"output")[0].style.fontWeight = "normal";} else {$("#"+"output")[0].style.fontWeight = "bold";};" title="bold"><b>B</b></button>
				<button onclick="if ($("#"+"output")[0].style.fontStyle == "italic") {$("#"+"output")[0].style.fontStyle = "normal";} else {$("#"+"output")[0].style.fontStyle = "italic";};" title="italic"><i>I</i></button>
				<button onclick="if ($("#"+"output")[0].style.textDecoration == "underline") {$("#"+"output")[0].style.textDecoration = "none";} else {$("#"+"output")[0].style.textDecoration = "underline";};" title="underline"><u>U</u></button>
				<button onclick="if ($("#"+"output")[0].style.textDecoration == "line-through") {$("#"+"output")[0].style.textDecoration = "none";} else {$("#"+"output")[0].style.textDecoration = "line-through";};" title="strikethrough"><s>S</s></button>
			</div>
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