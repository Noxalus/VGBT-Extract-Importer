<?php

require_once('../../config/config.php');
require_once(__ROOT__ . '/config/mysql.php');

require_once(__ROOT__ . '/config/mysql.php');
require_once(__ROOT__ . '/config/function.php');

require_once(__ROOT__ . '/libs/getid3/getid3.php');
require_once(__ROOT__ . '/libs/getid3/write.php'); 

?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Insert extracts</title>
  <link rel="stylesheet" href="../style/style.css">
  <script src="../js/jquery-2.1.3.min.js"></script>
  <script src="../js/script.js"></script>
</head>
<body>

<h1>Insert extracts</h1>
	
<?php 

/*
echo '<pre>';
print_r($_POST);
echo '</pre>';
*/

if (!empty($_POST['extract_track_number']) && is_array($_POST['extract_track_number']))
{
/*
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
*/
	$extract_number = count($_POST['extract_track_number']);

	if (!empty($_POST['extract_name']) && is_array($_POST['extract_name']) && count($_POST['extract_name']) == $extract_number &&
		!empty($_POST['extract_album_id']) && is_array($_POST['extract_album_id']) && count($_POST['extract_album_id']) == $extract_number &&
		!empty($_POST['extract_game_id']) && is_array($_POST['extract_game_id']) && count($_POST['extract_game_id']) == $extract_number &&
		!empty($_POST['extract_composer_id']) && is_array($_POST['extract_composer_id']) && count($_POST['extract_composer_id']) == $extract_number &&
		!empty($_POST['extract_data_url']) && is_array($_POST['extract_data_url']) && count($_POST['extract_data_url']) == $extract_number &&
		!empty($_POST['extract_data_md5']) && is_array($_POST['extract_data_md5']) && count($_POST['extract_data_md5']) == $extract_number &&
		!empty($_POST['extract_data_filesize']) && is_array($_POST['extract_data_filesize']) && count($_POST['extract_data_filesize']) == $extract_number &&
		!empty($_POST['extract_data_bitrate']) && is_array($_POST['extract_data_bitrate']) && count($_POST['extract_data_bitrate']) == $extract_number &&
		!empty($_POST['extract_data_sample_rate']) && is_array($_POST['extract_data_sample_rate']) && count($_POST['extract_data_sample_rate']) == $extract_number &&
		!empty($_POST['extract_data_encoding']) && is_array($_POST['extract_data_encoding']) && count($_POST['extract_data_encoding']) == $extract_number &&
		!empty($_POST['extract_data_playtime']) && is_array($_POST['extract_data_playtime']) && count($_POST['extract_data_playtime']) == $extract_number)
	{
		for ($i = 0; $i < $extract_number; $i++)
		{
			$extract_linked_data = array(
				'track_number' => $_POST['extract_track_number'][$i],
				'name' => $_POST['extract_name'][$i],
				'album_id' => $_POST['extract_album_id'][$i],
				'game_id' => $_POST['extract_game_id'][$i],
				'composer_id' => $_POST['extract_composer_id'][$i]
			);

			$extract_data = array(
				'url' => $_POST['extract_data_url'][$i],
				'md5' => $_POST['extract_data_md5'][$i],
				'filesize' => $_POST['extract_data_filesize'][$i],
				'bitrate' => $_POST['extract_data_bitrate'][$i],
				'sample_rate' => $_POST['extract_data_sample_rate'][$i],
				'encoding' => $_POST['extract_data_encoding'][$i],
				'playtime' => $_POST['extract_data_playtime'][$i],
			);

			insertExtract($pdo, $extract_data, $extract_linked_data);
		}
	}
}

?>

</body>
</html>