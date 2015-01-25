<?php
require_once('../../config/config.php');
require_once(__ROOT__ . 'config/mysql.php');
require_once(__ROOT__ . 'config/function.php');
?>

<h1>Display extracts</h1>

<?php	

$sql = '
	SELECT
		e.id as id,
		e.name as name,
		com.name as composer_name,
		g.name as game_name,
		g.release_date as release_date,
		gs.name as game_serie_name,
		con.name as console_name,
		a.name as album_name,
		eal.track_number as track_number
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
		eal.album_id = a.id
';


$results = $pdo->query($sql);
echo 'Result number: ' . $results->rowCount() . '<br>';

/*
$results = $results->fetchAll();

echo '<pre>';
print_r($results);
echo '</pre>';
*/

?>

<table style="width: 100%; text-align: center;">
	<tr>
		<th>Track number</th>
		<th>Name</th>
		<th>Composer</th>
		<th>Game</th>
		<th>Game serie</th>
		<th>Console(s)</th>
		<th>Release date</th>
		<th>Album</th>
		<th>File</th>
	</tr>
	<?php
	while($data = $results->fetchObject())
	{
		?>
		<tr>
			<td><?php echo $data->track_number; ?></td>
			<td><?php echo $data->name; ?></td>
			<td><?php echo $data->composer_name; ?></td>
			<td><?php echo $data->game_name; ?></td>
			<td><?php echo $data->game_serie_name; ?></td>
			<td><?php echo $data->release_date; ?></td>
			<td><?php echo $data->console_name; ?></td>
			<td><?php echo $data->album_name; ?></td>
			<td>
				<audio preload="none" controls>
			  		<source src="<?php echo str_replace(__ROOT__, '', MEDIA_OUTPUT_FOLDER . $data->id . '.mp3'); ?>" type="audio/mpeg">
					Your browser does not support the audio element.
				</audio>
			</td>
		</tr>
		<?php
	}
	?>
</table>