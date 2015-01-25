<?php

require_once('config.php');
require_once('mysql.php');
require_once(__ROOT__ . 'libs/getid3/getid3.php');

function getRandomQuiz($pdo, $questionNumber = 1, $type = 'nom')
{
	$sql = $pdo->query('
		SELECT 
			blind_extraits_id, 
			blind_extraits_' . $type . ' 
		FROM 
			site_blind_extraits 
		GROUP BY 
			blind_extraits_' . $type . ' 
		ORDER BY 
			RAND() 
		LIMIT 
			' . ($questionNumber + ($questionNumber * 3)));
	
	return $sql->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE);
}

function getMp3Data($input_dir)
{
	$getID3 = new getID3;

	$extracts_data = array();

	$dir = opendir($input_dir); 

	// Extract data from all mp3 files in the folder
	while($file = readdir($dir)) 
	{
		$complete_path = $input_dir . $file;

		if($file != '.' && $file != '..' && !is_dir($complete_path))
		{
			$extension = pathinfo($file, PATHINFO_EXTENSION);

			if (strtolower($extension) == 'mp3')
			{

				$extract_data = array();
				$extract_data['absolute_url'] = $complete_path;
				$extract_data['url'] = str_replace(__ROOT__, '', $complete_path);

				$md5 = md5_file($complete_path);

				$extract_data['md5'] = $md5;

				$data = $getID3->analyze($complete_path);

				$extract_data['filesize'] = $data['filesize'];
				$extract_data['bitrate'] = $data['bitrate'];
				$extract_data['sample_rate'] = $data['audio']['sample_rate'];
				$extract_data['encoding'] = $data['encoding'];
				$extract_data['playtime'] = $data['playtime_string'];

				$bitrate_mode = $data['audio']['bitrate_mode'];

				if ($bitrate_mode != 'cbr')
				{
					echo 'Error: This music has not a constant bitrate! (name: ' . $file . ', bitrate mode: ' . $bitrate_mode . ')';

					echo '<pre>';
					print_r($data);
					echo '</pre>';
					die();
				}

				$track_number = -1;
				$title = "";
				$album = "";
				$artist = "";
				$year = "";

				/*
				echo '<pre>';
				print_r($data);
				echo '</pre>';
				*/

				if (!empty($data['tags']['id3v2']))
				{
					$tags = $data['tags']['id3v2'];

					if (!empty($tags['track_number']))
					{
						$track_number = $tags['track_number'][0];
					
						$regex_pattern = "#([0-9]+)\s*\/\s*[0-9]+#";
						$matches = array();
						preg_match($regex_pattern, $track_number, $matches);

						if (count($matches) == 2)
							$track_number = (int)$matches[0];
					}

					if (!empty($tags['band']))
						$artist = $tags['band'][0];
					else if (!empty($tags['artist']))
						$artist = $tags['artist'][0];
					
					$title = $tags['title'][0];
					$album = $tags['album'][0];

					if (!empty($tags['year']))
						$year = $tags['year'][0];
				}
				else if (!empty($data['tags']['id3v1']))
				{
					$tags = $data['tags']['id3v1'];

					$title = $tags['title'][0];
					$album = $tags['album'][0];
					$artist = $tags['artist'][0];

					if (!empty($tags['year']))
						$year = $tags['year'][0];
				}
				else
				{
					$title = str_replace('.' . $extension, '', $file);
				}

				if ($track_number == -1)
				{
					$regex_pattern = "#([0-9]+)\s*-\s*(.*)#";
					$matches = array();
					preg_match($regex_pattern, $title, $matches);

					if (count($matches) == 3)
					{
						$track_number = (int)$matches[1];
						$title = $matches[2];
					}
				}

				// echo 'Track number: ' . $track_number . '<br />';
				// echo 'Title: ' . $title . '<br />';
				// echo 'Album: ' . $album . '<br />';
				// echo 'Artist: ' . $artist . '<br />';
				// echo 'Year: ' . $year . '<br />';

				$extract_data['track_number'] = $track_number;
				$extract_data['title'] = $title;
				$extract_data['album'] = $album;
				$extract_data['year'] = $year;
				$extract_data['artist'] = $artist;

				$extracts_data[] = $extract_data;
			}
		}
	}

	closedir($dir);

	return $extracts_data;
}

/** Insert function **/

function insertExtract($pdo, $extract_data, $extract_linked_data)
{

	echo '<pre>';
	print_r($extract_data);
	echo '</pre>';

	echo '<pre>';
	print_r($extract_linked_data);
	echo '</pre>';

	echo '<hr />';

	$sql = $pdo->prepare('SELECT COUNT(*) FROM vgbt_extracts WHERE md5= ?');
	$sql->bindValue(1, $extract_data['md5'], PDO::PARAM_STR);

	$success = $sql->execute();
	if ($success)
	{
		$exist = $sql->fetchColumn() > 0;

		if (!$exist)
		{
			$insert = "
			INSERT INTO 
				vgbt_extracts(
					name,
					game_id,
					composer_id,
					md5,
					size,
					bitrate,
					sample_rate,
					encoding,
					play_time,
					date			
				) 
			VALUES(
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				NOW()
			)";

			$sql = $pdo->prepare($insert);

			$sql->bindValue(1, $extract_linked_data['name'], PDO::PARAM_STR);
			$sql->bindValue(2, $extract_linked_data['game_id'], PDO::PARAM_INT);
			$sql->bindValue(3, $extract_linked_data['composer_id'], PDO::PARAM_INT);
			$sql->bindValue(4, $extract_data['md5'], PDO::PARAM_STR);
			$sql->bindValue(5, $extract_data['filesize'], PDO::PARAM_INT);
			$sql->bindValue(6, $extract_data['bitrate'], PDO::PARAM_INT);
			$sql->bindValue(7, $extract_data['sample_rate'], PDO::PARAM_INT);
			$sql->bindValue(8, $extract_data['encoding'], PDO::PARAM_STR);
			$sql->bindValue(9, $extract_data['playtime'], PDO::PARAM_STR);

			$success = $sql->execute();

			if ($success)
			{
				$extract_id = $pdo->lastInsertId();

				// We link the external data related to this extract
				/*
				linkExtractName($pdo, $extractId, $extractNameId);
				linkExtractGame($pdo, $extractId, $extract_linked_data['game_id'])
				linkExtractComposer($pdo, $extractId, $extract_linked_data['composer_id']);
				*/
				linkExtractAlbum($pdo, $extract_id, $extract_linked_data['album_id'], $extract_linked_data['track_number']);

				// We move the mp3 file to output folder
				$output_complete_path = MEDIA_OUTPUT_FOLDER . $extract_id . '.mp3';
				copy($extract_data['url'], $output_complete_path);

				// Initialize getID3 tag-writing module 
				$tagwriter = new getid3_writetags;
				$tagwriter->filename = $output_complete_path;
				$tagwriter->remove_other_tags = true;

				if ($tagwriter->WriteTags()) 
				{
					if (!empty($tagwriter->warnings)) 
					{ 
						echo 'There were some warnings:<br>' . implode('<br><br>', $tagwriter->warnings) . '<br>'; 
					}
				} 
				else 
				{ 
					echo 'Failed to write tags!<br>' . implode('<br><br>', $tagwriter->errors) . '<br>'; 
				}

				echo '<hr />';
			}
		}
		else
		{
			echo 'Error: This mp3 file already exists in the database!<br>';
		}
	}
}

/** Link functions **/

function linkExtractName($pdo, $extract_id, $name_id)
{
	if (!empty($extract_id) && is_numeric($extract_id) && $extract_id >= 0 &&
		!empty($name_id) && is_numeric($name_id) && $name_id >= 0)
	{
		$sql = '
			INSERT INTO
				vgbt_extract_name_links(
					extract_id,
					name_id
				)
			VALUES(
				?,
				?
			)
		';

		$pdo->prepare($sql);

		$sql->bindValue(1, $extract_id, PDO::PARAM_INT);
		$sql->bindValue(2, $name_id, PDO::PARAM_INT);

		$success = $sql->execute();

		if ($success)
		{
			echo 'The name has been linked with its extract! (extract id: ' . $extract_id . ', name id: ' . $name_id . ')<br>';
		}
		else
		{
			echo 'Error: The name has not been linked with its extract! (extract id: ' . $extract_id . ', name id: ' . $name_id . ')<br>';
		}
	}
}

function linkExtractGame($pdo, $extract_id, $game_id)
{
	if (!empty($extract_id) && is_numeric($extract_id) && $extract_id >= 0 &&
		!empty($game_id) && is_numeric($game_id) && $game_id >= 0)
	{
		$sql = '
			INSERT INTO
				vgbt_extract_game_links(
					extract_id,
					game_id
				)
			VALUES(
				?,
				?
			)
		';

		$sql = $pdo->prepare($sql);

		$sql->bindValue(1, $extract_id, PDO::PARAM_INT);
		$sql->bindValue(2, $game_id, PDO::PARAM_INT);

		$success = $sql->execute();

		if ($success)
		{
			echo 'The game has been linked with its extract! (extract id: ' . $extract_id . ', name id: ' . $game_id . ')<br>';
		}
		else
		{
			echo 'Error: The game has not been linked with its extract! (extract id: ' . $extract_id . ', name id: ' . $game_id . ')<br>';
		}
	}
}

function linkExtractComposer($pdo, $extract_id, $composer_id)
{
	if (!empty($extract_id) && is_numeric($extract_id) && $extract_id >= 0 &&
		!empty($composer_id) && is_numeric($composer_id) && $composer_id >= 0)
	{
		$sql = '
			INSERT INTO
				vgbt_extract_game_links(
					extract_id,
					composer_id
				)
			VALUES(
				?,
				?
			)
		';

		$sql = $pdo->prepare($sql);

		$sql->bindValue(1, $extract_id, PDO::PARAM_INT);
		$sql->bindValue(2, $composer_id, PDO::PARAM_INT);

		$success = $sql->execute();

		if ($success)
		{
			echo 'The composer has been linked with its extract! (extract id: ' . $extract_id . ', name id: ' . $composer_id . ')<br>';
		}
		else
		{
			echo 'Error: The composer has not been linked with its extract! (extract id: ' . $extract_id . ', name id: ' . $composer_id . ')<br>';
		}
	}
}

function linkExtractAlbum($pdo, $extract_id, $album_id, $track_number)
{
	if (!empty($extract_id) && is_numeric($extract_id) && $extract_id >= 0 &&
		!empty($album_id) && is_numeric($album_id) && $album_id >= 0 &&
		!empty($track_number) && is_numeric($track_number) && $track_number >= 0)
	{
		$sql = '
			INSERT INTO
				vgbt_extract_album_links(
					extract_id,
					album_id,
					track_number
				)
			VALUES(
				?,
				?,
				?
			)
		';

		$sql = $pdo->prepare($sql);

		$sql->bindValue(1, $extract_id, PDO::PARAM_INT);
		$sql->bindValue(2, $album_id, PDO::PARAM_INT);
		$sql->bindValue(3, $track_number, PDO::PARAM_INT);

		$success = $sql->execute();

		if ($success)
		{
			echo 'The link between extract and album has successfully been created! 
			(extract id: ' . $extract_id . ', name id: ' . $album_id . ', track number: ' . $track_number . ')<br>';
		}
		else
		{
			echo 'Error: The link between extract and album has failed to be created! 
			(extract id: ' . $extract_id . ', name id: ' . $album_id . ', track number: ' . $track_number . ')<br>';
		}
	}
}


/** Database functions **/

function getAllComposers($pdo)
{
	$sql = $pdo->query('SELECT id, name FROM vgbt_composers');

	return $sql->fetchAll();
}

function getAllGames($pdo)
{
	$sql = $pdo->query('SELECT id, name FROM vgbt_games');

	return $sql->fetchAll();
}

function getAllAlbums($pdo)
{
	$sql = $pdo->query('SELECT id, name FROM vgbt_albums');

	return $sql->fetchAll();
}

function getAllConsoles($pdo)
{
	$sql = $pdo->query('SELECT id, name FROM vgbt_consoles');

	return $sql->fetchAll();
}

?>