<?php
// IMPORTANT
$env_dev = 'production';

function get_url($addon) {
	if($env_dev == 'production'){
		$base_url = 'https://mathedu-ariga.herokuapp.com/';
	}else{
		$base_url = 'http://localhost/MathEdu/public/';
	}
	$base_url .= $addon;
	return $base_url;
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
	<h1 class="text-purple-700"><?php echo "Hello World : " ?><?= get_url('')?></h1>
</body>
</html>