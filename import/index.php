<?php

require_once('../config/config.php');
require_once(__ROOT__ . '/config/mysql.php');

require_once(__ROOT__ . '/config/mysql.php');
require_once(__ROOT__ . '/config/function.php');

require_once(__ROOT__ . '/libs/getid3/getid3.php');
require_once(__ROOT__ . '/libs/getid3/write.php'); 

$input_dir = MEDIA_INPUT_FOLDER . 'Final Fantasy 3 OST/';
$output_dir = MEDIA_OUTPUT_FOLDER;

$extracts_data = getMp3Data($input_dir);

$extracts_to_link = array();

$games = getAllGames($pdo);
$composers = getAllComposers($pdo);

$missing_composers = array();

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
}

if (count($missing_composers) > 0)
{
	echo '<b>Warning</b>: One or more of the processed files are not in the database, please <a href="COUCOU">add</a> them before to import each extract!';
}

echo '
<form action="#" method="post">
	<table style="width: 100%; text-align: center;">
		<tr>
			<th>Track number</th>
			<th>Title</th>
			<th>Game</th>
			<th>Composer</th>
			<th>File</th>
		</tr>
';
foreach($extracts_to_link as $data)
{
	echo '
		<tr>
			<td><input name="extract_track_number" value="' . $data['track_number'] . '" /></td>
			<td><input name="extract_name" value="' . $data['title'] . '" /></td>
			<td>
				<select name="extract_game">';
					foreach($games as $game)
					{
						echo '<option value="' . $game['id'] . '">' . $game['name'] . '</option>';
					}
				echo 
				'</select>
			</td>
			<td>
				<select name="extract_composer">';
					foreach($composers as $composer)
					{
						echo '<option value="' . $composer['id'] . '">' . $composer['name'] . '</option>';
					}
				echo 
				'</select>
			</td>
			<td>
				<audio preload="none" controls>
		  		<source src="' . $data['url'] . '" type="audio/mpeg">
				Your browser does not support the audio element.
			</audio>
			</td>
		</tr>';
}

echo '
	</table>
	<input type="submit" value="Send" />
</form>
';

?>