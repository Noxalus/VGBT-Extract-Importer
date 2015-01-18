<?php

require_once('../../config/config.php');

if (!empty($_POST['game_serie_name']))
{
	require_once(__ROOT__ . 'config/mysql.php');

	$game_serie_name = $_POST['game_serie_name'];

	$sql = $pdo->prepare('SELECT id FROM vgbt_game_series WHERE name = ?');
	$sql->bindValue(1, $game_serie_name, PDO::PARAM_STR);
	$sql->execute();		
	$game_serie_id = $sql->fetchColumn();

	if(!$game_serie_id)
	{
		$insert = "
		INSERT INTO 
			vgbt_game_series(
				name,
				date
			) 
		VALUES(
			?,
			NOW()
		)";

		$sql = $pdo->prepare($insert);
		$sql->bindValue(1, $game_serie_name, PDO::PARAM_STR);

		try 
		{
			$success = $sql->execute();
		    if ($success)
		    {
				echo '<b>' . $game_serie_name . '</b> has been successfully added to the database!<br />';
				echo '<a href="add_game_serie.php">Add another</a>';
			}
		} catch (PDOException $e) 
		{
		    echo 'Error : ' . $e->getMessage();
		}
	}
	else
	{
		echo 'Error: <b>' . $game_serie_name . '</b> serie already exists in the database!';
	}
}
else
{
?>

<h1>Add a game serie</h1>

<form method="POST">
	<p>
		<label for="game_serie_name">Game serie name</label>
		<input type="text" name="game_serie_name" id="game_serie_name" />
	</p>

	<p>
		<input type="submit" value="Send" />
	</p>
</form>

<?php
}
?>