<?php
require_once('../../config/config.php');
require_once(__ROOT__ . 'config/mysql.php');
require_once(__ROOT__ . 'config/function.php');
?>

<?php	

$sql = '
	SELECT
		e.id as id,
		e.name as name,
		e.exclude as exclude,
		e.remix as remix,
		e.famousness as famousness,
		com.name as composer_name,
		g.name as game_name,
		g.release_date as release_date,
		gs.name as game_serie_name,
		con.name as console_name,
		a.name as album_name,
		eal.track_number as track_number,
		eal.disc_number as disc_number
	FROM 
		vgbt_extracts e,
		vgbt_composers com,
		vgbt_games g,
		vgbt_game_series gs,
		vgbt_consoles con,
		vgbt_albums a,
		vgbt_game_console_links gcl,
		vgbt_extract_album_links eal
	WHERE
		e.game_id = g.id AND
		e.composer_id = com.id AND
		g.id = gcl.game_id AND
		con.id = gcl.console_id AND
		eal.extract_id = e.id AND
		eal.album_id = a.id AND
		g.game_serie = gs.id
';


$results = $pdo->query($sql);
echo '<p style="font-size: large; text-align: center;"><b>' . $results->rowCount() . '<b> extracts</p>';

/*
$results = $results->fetchAll();

echo '<pre>';
print_r($results);
echo '</pre>';
*/

?>


<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Import extracts</title>
  <link rel="stylesheet" href="../../style/style.css">
  <script src="../../js/jquery-2.1.3.min.js"></script>
  <script src="../../js/script.js"></script>
</head>
<body>

<table style="width: 100%; text-align: center;">
	<tr>
		<th>Exclude ?</th>
		<th>Remix ?</th>
		<th>Famousness</th>
		<th>Name</th>
		<th>Composer</th>
		<th>Game</th>
		<th>Game serie</th>
		<th>Console(s)</th>
		<th>Release date</th>
		<th>Album</th>
		<th>Disc</th>
		<th>Track number</th>
		<th>File</th>
		<th>Actions</th>
	</tr>
	<?php
	while($data = $results->fetchObject())
	{
		?>
		<tr id="extract_<?php echo $data->id; ?>">
			<td class="extract_exclude"><?php echo ($data->exclude) ? 'Y' : 'N';?></td>
			<td class="extract_remix"><?php echo ($data->remix) ? 'Y' : 'N'; ?></td>
			<td class="extract_famousness"><?php echo $data->famousness; ?></td>
			<td class="extract_name"><?php echo $data->name; ?></td>
			<td class="extract_composer"><?php echo $data->composer_name; ?></td>
			<td class="extract_game"><?php echo $data->game_name; ?></td>
			<td class="extract_game_serie"><?php echo $data->game_serie_name; ?></td>
			<td class="extract_release_date"><?php echo $data->release_date; ?></td>
			<td class="extract_console"><?php echo $data->console_name; ?></td>
			<td class="extract_album"><?php echo $data->album_name; ?></td>
			<td class="extract_disc_number"><?php echo $data->disc_number; ?></td>
			<td class="extract_track_number"><?php echo $data->track_number; ?></td>
			<td>
				<audio preload="none" controls>
			  		<source src="<?php echo str_replace(__ROOT__, '', MEDIA_OUTPUT_FOLDER . $data->id . '.mp3'); ?>" type="audio/mpeg">
					Your browser does not support the audio element.
				</audio>
			</td>
			<td class="extract_action">
				<a href="#" onclick="createUpdateForm(<?php echo $data->id; ?>)">Update</a>
			</td>
		</tr>
		<?php
	}
	?>
</table>

</body>