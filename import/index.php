<?php

require_once('../config/config.php');
require_once(__ROOT__ . '/config/mysql.php');

require_once(__ROOT__ . '/config/mysql.php');
require_once(__ROOT__ . '/config/function.php');

require_once(__ROOT__ . '/libs/getid3/getid3.php');
require_once(__ROOT__ . '/libs/getid3/write.php'); 

$input_dir = MEDIA_INPUT_FOLDER . 'Bravely Default Flying Fairy/DISC 1/';
$output_dir = MEDIA_OUTPUT_FOLDER;

$extracts_data = getMp3Data($input_dir);

$extracts_to_link = array();

$games = getAllGames($pdo);
$composers = getAllComposers($pdo);
$albums = getAllAlbums($pdo);

$missing_composers = array();
$missing_albums = array();

foreach($extracts_data as $data)
{
	//$extracts_to_link[] = insertExtract($pdo, $data);

	$extracts_to_link[] = $data;

	if (!empty($data['artist']))
	{
		$exist = false;
		foreach($composers as $composer)
		{
			if (strtolower($data['artist']) == strtolower($composer['name']))
			{
				$exist = true;
				break;
			}
		}

		if ($exist == false && !in_array($data['artist'], $missing_composers))
		{
			$missing_composers[] = $data['artist'];
		}
	}

	if (!empty($data['album']))
	{
		$exist = false;
		foreach($albums as $album)
		{
			if (strtolower($data['album']) == strtolower($album['name']))
			{
				$exist = true;
				break;
			}
		}

		if ($exist == false && !in_array($data['album'], $missing_albums))
		{
			$missing_albums[] = $data['album'];
		}
	}
}

?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Titre de la page</title>
  <link rel="stylesheet" href="../style/style.css">
  <script src="../js/jquery-2.1.3.min.js"></script>
  <script src="../js/script.js"></script>
</head>
<body>

<h1>Import extracts</h1>

<?php
if (count($missing_composers) > 0)
{
?>
	<p>
		<b>Warning</b>: One or more of the processed files composer are not in the database, 
		please <a href="#" onclick="databaseInsert('add/add_composer.php', {'composer_name': '<?php echo $missing_composers[0]; ?>'})">add</a> 
		them before to import each extract!
	</p>
<?php
}

if (count($missing_albums) > 0)
{
?>
	<p>
		<b>Warning</b>: One or more of the processed files album are not in the database, 
		please <a href="#" onclick="databaseInsert('add/add_album.php', {'album_name': '<?php echo $missing_albums[0]; ?>'})">add</a> 
		them before to import each extract!
	</p>
<?php
}
?>

<form action="#" method="post">
	<table style="width: 100%; text-align: center;">
		<tr>
			<th>Track number</th>
			<th>Title</th>
			<th>Album</th>
			<th>Game</th>
			<th>Composer</th>
			<th>File</th>
		</tr>
<?php
foreach($extracts_to_link as $data)
{
?>
		<tr>
			<td><input name="extract_track_number" value="<?php echo $data['track_number']; ?>" /></td>
			<td><input name="extract_name" value="<?php echo $data['title']; ?>" /></td>
			<td>
				<select name="extract_album">
				<?php
					foreach($albums as $album)
					{
						echo '<option value="' . $album['id'] . '"';
						if($data['album'] == $album['name']) 
							echo 'selected';
						echo '>' . $album['name'] . '</option>';
					}
				?>
				</select>
			</td>
			<td>
				<select name="extract_game">
				<?php
					foreach($games as $game)
					{
						echo '<option value="' . $game['id'] . '"';
						if(false) 
							echo 'selected';
						echo '>' . $game['name'] . '</option>';
					}
				?>
				</select>
			</td>
			<td>
				<select name="extract_composer">
				<?php
					foreach($composers as $composer)
					{
						echo '<option value="' . $composer['id'] . '"';
						if($data['artist'] == $composer['name']) 
							echo 'selected';
						echo '>' . $composer['name'] . '</option>';
					}
				?>
				</select>
			</td>
			<td>
				<audio preload="none" controls>
		  		<source src="<?php echo $data['url']; ?>" type="audio/mpeg">
				Your browser does not support the audio element.
			</audio>
			</td>
		</tr>
<?php
}
?>
	</table>
	<input type="submit" value="Send" />
</form>

</body>
</html>