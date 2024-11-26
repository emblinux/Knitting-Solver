<?php

$masche1_img = ["RM" => "<img src='images/white.jpg' />", "LM" => "<img src='images/black.jpg' />"];
$masche2_img = ["RM" => "<img src='images/black.jpg' />", "LM" => "<img src='images/white.jpg' />"];

$masche1 = ["RM" => ".", "LM" => "#"];
$masche2 = ["RM" => "#", "LM" => "."];

$knitting_pattern = "";
$output = (isset($_POST["output"]) && $_POST["output"] == "image" ? true : false);
$rotate = (isset($_POST["rotate"]) && $_POST["rotate"] == "direction" ? true : false);

$masche_call1 = "masche1".($output == true ? "_img":"");
$masche_call2 = "masche2".($output == true ? "_img":"");

$content_output = "";

if(isset($_POST["solve"]) && $_POST["solve"] == "Solve" && isset($_POST["knitting_pattern"]) && strlen($_POST["knitting_pattern"]) > 0) {
	$knitting_pattern = $_POST["knitting_pattern"];
	$pattern_array = explode("\n", $knitting_pattern);
	if(count($pattern_array) > 0) {
		if($rotate == true) {
			$pattern_array = array_reverse($pattern_array);
		}
		$content_output .= '<div style="font-family: monospace;">';
		foreach($pattern_array as $rc => $pattern) {
			$zeile_array = explode(",", $pattern);
			if($rc % 2) {
				$zeile_array = array_reverse($zeile_array);
			}
			foreach($zeile_array as $zeile_element) {
				$ln = trim(preg_replace("(rM|lM)", "", $zeile_element));
				$lm = strtoupper(trim(preg_replace("/\d+([a-z])/i", "$1", $zeile_element)));
				for ($m = 0; $m < $ln; $m++) {
					if($rc % 2)
						$content_output .=  $rotate == true ? $$masche_call1[$lm] : $$masche_call2[$lm];
					else
						$content_output .=  $rotate == true ? $$masche_call2[$lm] : $$masche_call1[$lm];
				}
			}
			$content_output .=  "<br />\n";
		}
		$content_output .=  "</div>";
	}
}

if(strlen($content_output) > 0) {
	echo "Ergebnis: <br />\n";
	echo $content_output;
	echo "<hr />";
}

echo '<h3>Knitting Solver</h3>'."\n";
echo '<p>Das Strickmuster muss folgendes Format haben: [Anzahl der Maschen][Art der Masche] mit Komma getrennt. Beispiel: 30LM, 2RM  bedeutet 30 Linke Maschen und dann 2 Rechte Maschen.</p>'."\n";
echo '<p>Jede neue Reihe muss mit einer neuen Zeile beginnen (Zeilenumbruch).</p>'."\n";
echo '<p>Hier ein kleines Beispiel:.</p>'."\n";
echo "<div>70LM <br /> 70RM <br /> 2LM, 2RM, 4LM <br /> 70LM</div>"."\n";
echo '<form method="post" action="index.php">'."<br />\n";
echo 'Muster eingeben: '."<br />\n";
echo '<textarea name="knitting_pattern" cols="100" rows="20">'.$knitting_pattern.'</textarea>'."<br />\n";

echo '<input type="checkbox" name="output" id="output" value="image" '.($output == true ? "checked" : "").' /> '."\n";
echo '<label for="output">Ausgabe als Grafik</label>'."<br />\n";

echo '<input type="checkbox" name="rotate" id="rotate" value="direction" '.($rotate == true ? "checked" : "").' /> '."\n";
echo '<label for="rotate">Ausgabe drehen</label>'."<br />\n";

echo '<input type="submit" name="solve" value="Solve" />'."<br />\n";
echo '</form>'."<br />\n";
?>
