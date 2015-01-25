<?php

require_once('../../config/config.php');

if (!empty($_POST['album_name']))
{
	require_once(__ROOT__ . 'config/mysql.php');

	$album_name = $_POST['album_name'];

	$sql = $pdo->prepare('SELECT id FROM vgbt_albums WHERE name = ?');
	$sql->bindValue(1, $album_name, PDO::PARAM_STR);
	$sql->execute();		
	$album_id = $sql->fetchColumn();

	if(!$album_id)
	{
		$insert = "
		INSERT INTO 
			vgbt_albums(
				name,
				date
			) 
		VALUES(
			?,
			NOW()
		)";

		$sql = $pdo->prepare($insert);
		$sql->bindValue(1, $album_name, PDO::PARAM_STR);

		try 
		{
			$success = $sql->execute();
		    if ($success)
		    {
				echo '<b>' . $album_name . '</b> has been successfully added to the database!<br />';
				echo '<a href="add_album.php">Add another</a>';
			}
		} catch (PDOException $e) 
		{
		    echo 'Error : ' . $e->getMessage();
		}
	}
	else
	{
		echo 'Error: <b>' . $album_name . '</b> album already exists in the database!';
	}
}
else
{
?>

<h1>Add an album</h1>

<form method="POST">
	<p>
		<label for="album_name">Album name</label>
		<input type="text" name="album_name" id="album_name" value="<?php if (!empty($_GET['name'])) echo $_GET['name']; else echo "" ?>" />
	</p>

	<p>
		<input type="submit" value="Send" />
	</p>
</form>

<?php
}
?>