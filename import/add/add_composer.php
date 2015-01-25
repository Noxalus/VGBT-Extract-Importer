<?php

require_once('../../config/config.php');

if (!empty($_POST['composer_name']))
{
	require_once(__ROOT__ . 'config/mysql.php');

	$composer_name = $_POST['composer_name'];

	$sql = $pdo->prepare('SELECT id FROM vgbt_composers WHERE name = ?');
	$sql->bindValue(1, $composer_name, PDO::PARAM_STR);
	$sql->execute();		
	$composer_id = $sql->fetchColumn();

	if(!$composer_id)
	{
		$insert = "
		INSERT INTO 
			vgbt_composers(
				name,
				date
			) 
		VALUES(
			?,
			NOW()
		)";

		$sql = $pdo->prepare($insert);
		$sql->bindValue(1, $composer_name, PDO::PARAM_STR);

		try 
		{
			$success = $sql->execute();
		    if ($success)
		    {
				echo '<b>' . $composer_name . '</b> has been successfully added to the database!<br />';
				echo '<a href="add_composer.php">Add another</a>';
			}
		} catch (PDOException $e) 
		{
		    echo 'Error : ' . $e->getMessage();
		}
	}
	else
	{
		echo 'Error: <b>' . $composer_name . '</b> already exists in the database!';
	}
}
else
{
?>

<h1>Add a composer</h1>

<form method="POST">
	<p>
		<label for="composer_name">Composer name</label>
		<input type="text" name="composer_name" id="composer_name" value="<?php if (!empty($_GET['name'])) echo $_GET['name']; else echo "" ?>" />
	</p>

	<p>
		<input type="submit" value="Send" />
	</p>
</form>

<?php
}
?>