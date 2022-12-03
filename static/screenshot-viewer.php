<!DOCTYPE html>
<html>
	<head>
		<title>dirgames.com Screenshot Viewer</title>
		<style type="text/css">
			p {
				font-family: sans-serif;
				max-width: 40rem;
			}
		</style>
	</head>

	<body>
		<?php
		$basePath = "/editorial/";
		$imageFile = "/(.jpg|.jpeg|.png|.webp|.gif)$/i";

		$note1 = [
			"en" => "This page forces the screenshot to be displayed in an aspect ratio of ",
			"de" => "Diese Seite erzwingt die Darstellung des Bildes in einem Seitenverhältnis von "
		];
		$note2 = [
			"en" => "If you open the image directly, or download it and open it locally, it might not display properly.",
			"de" => "Falls Sie das Bild direkt öffnen, oder es herunterladen und lokal anzeigen, wird es unter Umständen nicht korrekt dargestellt."
		];

		function sanitizePath($path)
		{
			$path = explode('/', $path);
			$stack = array();
			foreach ($path as $seg) {
				if ($seg == '..') {
					array_pop($stack);
					continue;
				}
				if ($seg == '.') {
					continue;
				}
				$stack[] = $seg;
			}
			return implode('/', $stack);
		}

		$imagePath = sanitizePath($_GET["src"]);
		$imagePathRel = substr($imagePath, strlen($basePath));
		$lang = $_GET["lang"];
		$aspect = explode("-", $_GET["aspect"]);

		if (
			strpos($imagePath, $basePath) !== 0
			|| !file_exists($imagePathRel)
			|| !(preg_match($imageFile, $imagePath) > 0)
		) {
			echo ("\t\t<p>File not found.</p>\n\t</body>\n</html>");
			die();
		} elseif (!is_numeric($aspect[0]) || !is_numeric($aspect[1])) {
			echo ("\t\t<p>Illegal aspect ratio.</p>\n\t</body>\n</html>");
			die();
		} else {
			list($imageWidth) = getimagesize($imagePathRel);
			echo ("\t\t<img src=\"" . $imagePath . "\" style=\"width: " . $imageWidth . "px; aspect-ratio: " . intval($aspect[0]) . "/" . intval($aspect[1]) . ";\" />\n");
			echo ("\t\t<p>" . $note1[$lang] . intval($aspect[0]) . ":" . intval($aspect[1]) . ". " . $note2[$lang] . "</p>\n");
		}
		?>
	</body>
</html>
