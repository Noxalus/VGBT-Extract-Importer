<?php
require_once('../../config/config.php');
require_once(__ROOT__ . 'config/mysql.php');
?>

<h1>Add a game</h1>

<?php	
if (!empty($_POST['game_name']) && !empty($_POST['game_serie']))
{

	$game_name = $_POST['game_name'];
	$game_serie_id = $_POST['game_serie'];
	$spin_off = !empty($_POST['spin_off']);

	$sql = $pdo->prepare('SELECT id FROM vgbt_games WHERE name = ?');
	$sql->bindValue(1, $game_name, PDO::PARAM_STR);
	$sql->execute();		
	$game_id = $sql->fetchColumn();

	if(!$game_id)
	{
		$insert = "
		INSERT INTO 
			vgbt_games(
				name,
				spin_off,
				date
			) 
		VALUES(
			?,
			?,
			NOW()
		)";

		$sql = $pdo->prepare($insert);
		$sql->bindValue(1, $game_name, PDO::PARAM_STR);
		$sql->bindValue(2, $spin_off, PDO::PARAM_BOOL);

		try 
		{
			$success = $sql->execute();
		    if ($success)
		    {
				echo '<b>' . $game_name . '</b> has been successfully added to the database!';

				$last_insert_id = $pdo->lastInsertId();

				// We insert the link between the new game and the game serie
				if ($game_serie_id != -1)
				{
					$insert = "
					INSERT INTO 
						vgbt_game_game_serie_links(
							id_game,
							id_game_serie
						) 
					VALUES(
						?,
						?
					)";

					$sql = $pdo->prepare($insert);
					$sql->bindValue(1, $last_insert_id, PDO::PARAM_INT);
					$sql->bindValue(2, $game_serie_id, PDO::PARAM_INT);

					$success = $sql->execute();
				    if ($success)
						echo '<b>' . $game_name . '</b> has been successfully linked with its game serie into the database!';
				}
			}
		} catch (PDOException $e) 
		{
		    echo 'Error : ' . $e->getMessage();
		}
	}
	else
	{
		echo 'Error: This game is already in the database!';
	}
}
else
{
	$sql = $pdo->query('SELECT id, name FROM vgbt_game_series');
	
	$game_series = array();
	while($data = $sql->fetch())
	{
		$game_series[] = array('id' => $data['id'], 'name' => $data['name']);
	}
?>

<form method="POST">
	<p>
		<label for="game_name">Game name</label>
		<input type="text" name="game_name" id="game_name" />
	</p>

	<p>
		<label for="game_serie">Game serie</label>
		<select name="game_serie" id="game_serie">
			<option value="-1">None</option>
			<?php
			foreach($game_series as $data)
			{
				echo '<option value="' . $data['id'] . '">' . $data['name'] . '</option>';
			}
			?>
		</select>
		<a href="add_game_serie.php">Add</a>
	</p>

	<p>
		<label for="spin_off">Spin-off?</label>
		<input type="checkbox" name="spin_off" id="spin_off" />
	</p>

	<p>
		<input type="submit" value="Send" />
	</p>
</form>

<?php
}
?>