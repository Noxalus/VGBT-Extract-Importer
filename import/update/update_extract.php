<?php
require_once('../../config/config.php');
require_once(__ROOT__ . 'config/mysql.php');
require_once(__ROOT__ . 'config/function.php');
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
</head>
<body>

<?php
if (isset($_GET['id']) && is_numeric($_GET['id']))
{
	// If form has already been submited
	if (!empty($_POST))
	{
		updateExtract($pdo, $_POST);
	}

	$id = $_GET['id'];

	// Get extract info for this id
	$sql = '
		SELECT
			e.id as id,
			e.name as name,
			e.game_id,
			e.composer_id,
			e.exclude,
			e.remix,
			e.famousness
		FROM 
			vgbt_extracts e
		WHERE
			e.id = ' . $id . '
	';

	$sql = $pdo->query($sql);

	$result = $sql->fetchObject();

	if ($result)
	{
		// Get all games and composers
		$games = getAllGames($pdo);
		$composers = getAllComposers($pdo);
	?>

	<form action="#" method="POST">
		<table style="width: 100%; text-align: center">
			<tr>
				<th>Title</th>
				<th>Game</th>
				<th>Composer</th>
				<th>Exclude</th>
				<th>Remix</th>
				<th>Famousness</th>
			</tr>

			<tr>
				<td><input type="text" name="extract_name" value="<?php echo $result->name; ?>" /></td>
				<td>
					<select name="extract_game">
						<?php
						foreach($games as $game)
						{
							echo '<option value="' . $game['id'] . '"';
							if($result->game_id == $game['id']) 
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
							if($result->composer_id == $composer['id']) 
								echo 'selected';
							echo '>' . $composer['name'] . '</option>';
						}
						?>
					</select>
				</td>
				<td><input type="checkbox" name="extract_exclude" <?php echo ($result->exclude) ? 'checked' : ''; ?> /></td>
				<td><input type="checkbox" name="extract_remix" <?php echo ($result->remix) ? 'checked' : ''; ?> /></td>
				<td><input type="text" name="extract_famousness" value="<?php echo $result->famousness; ?>" /></td>
			</tr>
		</table>

		<input type="hidden" name="extract_id" value="<?php echo $id; ?>" />

		<input type="submit" value="Update" style="width: 100%;" />
	</form>

	<?php
	}
	else
	{
		echo 'Sorry, but this extract doesn\'t exist!';
	}
}
else
{
	echo 'You have nothing to do here!';
}

?>

</body>
</html>