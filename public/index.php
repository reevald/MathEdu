<?php
function get_url($addon) {
	// $pageURL = 'http';
	// if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	// $pageURL .= "://";
	$pageURL = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
	if ($_SERVER["SERVER_PORT"] != "80") {
 		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 	} else {
  	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	$pageURL .= $addon;
	return $pageURL;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Deploy</title>
    <meta name="description" content="Aplikasi Belajar Bangun Datar melalui Game Tebak Gambar Berbasis Artificial Intelligence">
    <link rel="stylesheet" href="<?=get_url('css/app-output.css')?>">
</head>
<body>
	<h1 class="text-purple-700"><?php echo "Hello World : " ?><?= get_url('index.php')?></h1>
</body>
</html>