<?php
require_once('../../config/config.php');
require_once(__ROOT__ . 'config/mysql.php');
require_once(__ROOT__ . 'config/function.php');
?>

<h1>Display games</h1>

<?php	
if (!empty($_POST['game_name']) && !empty($_POST['game_serie']))
{
	$game_name = $_POST['game_name'];
	$game_serie_id = $_POST['game_serie'];
	$release_date = $_POST['release_date'];
	$console_id = $_POST['console'];

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
				game_serie,
				release_date,
				date
			) 
		VALUES(
			?,
			?,
			?,
			NOW()
		)";

		$sql = $pdo->prepare($insert);
		$sql->bindValue(1, $game_name, PDO::PARAM_STR);
		if ($game_serie_id != -1)
			$sql->bindValue(2, $game_serie_id, PDO::PARAM_STR);
		else
			$sql->bindValue(2, null, PDO::PARAM_STR);
		$sql->bindValue(3, $release_date, PDO::PARAM_STR);

		try 
		{
			$success = $sql->execute();
		    if ($success)
		    {
				echo '<b>' . $game_name . '</b> has been successfully added to the database!<br>';

				$last_insert_id = $pdo->lastInsertId();

				$insert = "
					INSERT INTO 
						vgbt_game_console_links(
							game_id,
							console_id
						) 
					VALUES(
						?,
						?
				)";

				$sql = $pdo->prepare($insert);
				$sql->bindValue(1, $last_insert_id, PDO::PARAM_INT);
				$sql->bindValue(2, $console_id, PDO::PARAM_INT);

				$success = $sql->execute();
			    if ($success)
					echo '<b>' . $game_name . '</b> has been successfully linked with its console into the database!<br>';
			}
		} catch (PDOException $e) 
		{
		    echo 'Error : ' . $e->getMessage();
		}

		echo '<a href="add_game.php">Add another</a>';
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

	$consoles = getAllConsoles($pdo);
?>

<form method="POST">
	<p>
		<label for="game_name">Game name</label>
		<input type="text" name="game_name" id="game_name" />
	</p>

	<p>
		<p>
			<label for="console">Console</label>
			<select name="console" id="console">
				<option value="-1">None</option>
				<?php
				foreach($consoles as $console)
				{
					echo '<option value="' . $console['id'] . '">' . $console['name'] . '</option>';
				}
				?>
			</select>
		</p>
		<p>
			<input type="date" name="release_date" />
		</p>
		<p>
			<select name="game_serie" id="game_serie">
				<option value="-1">None</option>
				<?php
				foreach($game_series as $game_serie)
				{
					echo '<option value="' . $game_serie['id'] . '">' . $game_serie['name'] . '</option>';
				}
				?>
			</select>
			<a href="add_game_serie.php">Add</a>
		</p>
	</p>

	<p>
		<input type="submit" value="Send" />
	</p>
</form>

<?php
}
?>