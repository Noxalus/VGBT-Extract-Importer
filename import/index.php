<?php

require_once('../config/config.php');
require_once(__ROOT__ . '/config/mysql.php');

require_once(__ROOT__ . '/config/mysql.php');
require_once(__ROOT__ . '/config/function.php');

require_once(__ROOT__ . '/libs/getid3/getid3.php');
require_once(__ROOT__ . '/libs/getid3/write.php'); 

$games = getAllGames($pdo);

?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Import extracts</title>
  <link rel="stylesheet" href="../style/style.css">
  <script src="../js/jquery-2.1.3.min.js"></script>
  <script src="../js/script.js"></script>
</head>
<body>

<h1>Import extracts</h1>

<p>
	<a href="add/add_album.php" target="_blank">Add an album</a>
</p>

<?php
if (!empty($_POST['input_dir']) && 
	(is_dir($_POST['input_dir']) || is_dir(__ROOT__ . $_POST['input_dir']) || is_dir(MEDIA_INPUT_FOLDER . $_POST['input_dir'])) && 
	!empty($_POST['game_id']))
{
	$input_dir = '';
	if (is_dir($_POST['input_dir']))
		$input_dir =  __ROOT__ . $_POST['input_dir'] . '/';
	else if (is_dir(__ROOT__ . $_POST['input_dir']))
		$input_dir = __ROOT__ . $_POST['input_dir'] . '/';
	else if (is_dir(MEDIA_INPUT_FOLDER . $_POST['input_dir']))
		$input_dir = MEDIA_INPUT_FOLDER . $_POST['input_dir'] . '/';

	$output_dir = MEDIA_OUTPUT_FOLDER;

	$extracts_data = getMp3Data($input_dir);

	$extracts_to_link = array();

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
	<form action="insert/insert_extract.php" method="post">

		<table style="width: 100%; text-align: center;">
			<tr>
				<th>Track number</th>
				<th>Title</th>
				<th>Album</th>
				<th>Disc</th>
				<th>Game</th>
				<th>Composer</th>
				<th>File</th>
			</tr>
			<tr>
				<th>-</th>
				<th>-</th>
				<td>
					<select onchange="changeAllSelect('album_select', this.value)">
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
					<select onchange="changeAllSelect('disc_select', this.value)">
						<?php
						for ($i = 1; $i < 6; $i++)
						{
							echo '<option value="' . $i . '">' . $i . '</option>';
						}
						?>
					</select>
				</td>
				<td>
					<select onchange="changeAllSelect('game_select', this.value)">
						<?php
						foreach($games as $game)
						{
							echo '<option value="' . $game['id'] . '"';
							if($_POST['game_id'] == $game['id']) 
								echo 'selected';
							echo '>' . $game['name'] . '</option>';
						}
						?>
					</select>
				</td>
				<td>
					<select onchange="changeAllSelect('composer_select', this.value)">
						<?php
						foreach($composers as $composer)
						{
							echo '<option value="' . $composer['id'] . '"';
							if($data['artist'] == $composer['id']) 
								echo 'selected';
							echo '>' . $composer['name'] . '</option>';
						}
						?>
					</select>					
				</td>
				<th>-</th>
			</tr>
<?php
		foreach($extracts_to_link as $data)
		{
?>
			<tr>
				<td><input name="extract_track_number[]" value="<?php echo $data['track_number']; ?>" /></td>
				<td><input name="extract_name[]" value="<?php echo ucfirst($data['title']); ?>" /></td>
				<td>
					<select name="extract_album_id[]" class="album_select">
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
					<select name="extract_disc_number[]" class="disc_select">
						<?php
						for ($i = 1; $i < 6; $i++)
						{
							echo '<option value="' . $i . '">' . $i . '</option>';
						}
						?>
					</select>
				</td>
				<td>
					<select name="extract_game_id[]" class="game_select">
					<?php
						foreach($games as $game)
						{
							echo '<option value="' . $game['id'] . '"';
							if($_POST['game_id'] == $game['id']) 
								echo 'selected';
							echo '>' . $game['name'] . '</option>';
						}
					?>
					</select>
				</td>
				<td>
					<select name="extract_composer_id[]" class="composer_select">
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

			<input name="extract_data_url[]" type="hidden" value="<?php echo $data['absolute_url']; ?>" />
			<input name="extract_data_md5[]" type="hidden" value="<?php echo $data['md5']; ?>" />
			<input name="extract_data_filesize[]" type="hidden" value="<?php echo $data['filesize']; ?>" />
			<input name="extract_data_bitrate[]" type="hidden" value="<?php echo $data['bitrate']; ?>" />
			<input name="extract_data_sample_rate[]" type="hidden" value="<?php echo $data['sample_rate']; ?>" />
			<input name="extract_data_encoding[]" type="hidden" value="<?php echo $data['encoding']; ?>" />
			<input name="extract_data_playtime[]" type="hidden" value="<?php echo $data['playtime']; ?>" />
<?php
		}
?>
		</table>

		<input type="submit" value="Send" style="width: 100%;" />
	</form>
	<?php
}
else
{
	if (!empty($_POST['input_dir']))
	{
		echo 'This folder doesn\'t exist: <b>' . $_POST['input_dir'] . '</b><br>';
	}
?>
	<p>
		<a href="add/add_game.php">Add a game</a>
	</p>

	<form action="#" method="post">
		<p>
			<label for="input_dir">Path</label>
			<input type="text" name="input_dir" id="input_dir" />
		</p>
		<p>
			<label for="game_id">Game</label>
			<select name="game_id" id="game_id">
				<?php
				foreach ($games as $game)
				{
					echo '<option value="' . $game['id'] . '">' . $game['name'] . '</option>';
				}
				?>
			</select>
		</p>

		<input type="submit" value="Send" />
	</form>

<?php
}
?>
	

</body>
</html>