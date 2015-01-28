<?php
require_once('../../config/config.php');
require_once(__ROOT__ . 'config/mysql.php');
require_once(__ROOT__ . 'config/function.php');
?>

<h1>Display games</h1>

<?php	

$sql = 'SELECT
			g.name as game_name,
			g.release_date as release_date,
			gs.name as game_serie_name,
			c.name as console_name
		FROM 
			vgbt_games as g,
			vgbt_game_series as gs,
			vgbt_consoles as c,
			vgbt_game_console_links as gcl
		WHERE
			g.id = gcl.game_id AND
			c.id = gcl.console_id AND
			g.game_serie = gs.id
';

$results = $pdo->query($sql);
/*
$results = $results->fetchAll();

echo '<pre>';
print_r($results);
echo '</pre>';
*/

?>

<table style="width: 100%; text-align: center;">
	<tr>
		<th>Game</th>
		<th>Game serie</th>
		<th>Console(s)</th>
		<th>Release date</th>
	</tr>
	<?php
	while($data = $results->fetchObject())
	{
		?>
		<tr>
			<td><?php echo $data->game_name; ?></td>
			<td><?php echo $data->game_serie_name; ?></td>
			<td><?php echo $data->release_date; ?></td>
			<td><?php echo $data->console_name; ?></td>
		</tr>
		<?php
	}
	?>
</table>