<?php

require_once('config.php');
require_once('mysql.php');
require_once(__ROOT__ . 'libs/getid3/getid3.php');

/** API **/

function getExtractNumber($pdo, $real = false)
{
	$sql = 'SELECT COUNT(*) FROM vgbt_extracts';

	if (!$real)
		$sql .=  ' WHERE exclude = false';

	$sql = $pdo->query($sql);
	
	return $sql->fetchColumn();
}

function getWherePredicate($excludeGames)
{
	$where = 'WHERE e.exclude = false';

	if ($excludeGames != null && is_array($excludeGames))
	{
		foreach($excludeGames as $excludeGameId) 
			$where .= ' AND e.game_id != ' . $excludeGameId;
	}

	return $where;
}

function getRandomExtractQuiz($pdo, $questionNumber = 1, $excludeGames = null)
{
	$where = getWherePredicate($excludeGames);

	$sql = $pdo->query('
		SELECT 
			e.id, 
			e.name
		FROM
			vgbt_extracts e,
			vgbt_games g
			' . $where . '
		GROUP BY 
			e.name 
		ORDER BY 
			RAND()
		LIMIT 
			' . ($questionNumber + ($questionNumber * 3))
	);
	
	return $sql->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE);
}

function getRandomGameQuiz($pdo, $questionNumber = 1, $excludeGames = null)
{
	$where = getWherePredicate($excludeGames);

	$request = '
		SELECT
			e.id, 
			e.game_id
		FROM 
			vgbt_extracts e,
			vgbt_games g 
			' . $where . '
		GROUP BY 
			e.name 
		ORDER BY 
			RAND() 
		LIMIT 
			' . $questionNumber;

	$sql = $pdo->query($request);
	
	return $sql->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE);
}

function getRandomComposerQuiz($pdo, $questionNumber = 1, $excludeGames = null)
{
	$where = getWherePredicate($excludeGames);

	$sql = $pdo->query('
		SELECT
			e.id, 
			e.composer_id
		FROM 
			vgbt_extracts e,
			vgbt_games g 
			' . $where . '
		GROUP BY 
			e.name 
		ORDER BY 
			RAND() 
		LIMIT 
			' . $questionNumber
	);
	
	return $sql->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE);
}

/** MP3 **/

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
				$extract_data['url'] = str_replace(__ROOT__, '/', $complete_path);

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

				$regex_pattern = "#([0-9]+).*\s*-\s*(.*)#";
				$matches = array();
				preg_match($regex_pattern, $title, $matches);

				if (count($matches) == 3)
				{
					$track_number = (int)$matches[1];
					$title = $matches[2];
				}
				
				if (strlen($track_number) > 2)
				{
					$track_number = substr($track_number, 1);
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

/** Insert functions **/

function insertExtract($pdo, $extract_data, $extract_linked_data)
{
	/*
	echo '<pre>';
	print_r($extract_data);
	echo '</pre>';

	echo '<pre>';
	print_r($extract_linked_data);
	echo '</pre>';

	echo '<hr />';
	*/

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
				linkExtractAlbum($pdo, $extract_id, $extract_linked_data['album_id'], $extract_linked_data['disc_number'], $extract_linked_data['track_number']);

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

					return true;
				} 
				else 
				{ 
					echo 'Failed to write tags!<br>' . implode('<br><br>', $tagwriter->errors) . '<br>'; 
				}
			}
		}
		else
		{
			echo 'Error: This mp3 file already exists in the database!<br>';
		}
	}

	return false;
}

function insert_extracts($pdo, $post)
{
	if (!empty($post['extract_track_number']) && is_array($post['extract_track_number']))
	{
		$extract_number = count($post['extract_track_number']);

		if (!empty($post['extract_name']) && is_array($post['extract_name']) && count($post['extract_name']) == $extract_number &&
			!empty($post['extract_album_id']) && is_array($post['extract_album_id']) && count($post['extract_album_id']) == $extract_number &&
			!empty($post['extract_game_id']) && is_array($post['extract_game_id']) && count($post['extract_game_id']) == $extract_number &&
			!empty($post['extract_composer_id']) && is_array($post['extract_composer_id']) && count($post['extract_composer_id']) == $extract_number &&
			!empty($post['extract_data_url']) && is_array($post['extract_data_url']) && count($post['extract_data_url']) == $extract_number &&
			!empty($post['extract_data_md5']) && is_array($post['extract_data_md5']) && count($post['extract_data_md5']) == $extract_number &&
			!empty($post['extract_data_filesize']) && is_array($post['extract_data_filesize']) && count($post['extract_data_filesize']) == $extract_number &&
			!empty($post['extract_data_bitrate']) && is_array($post['extract_data_bitrate']) && count($post['extract_data_bitrate']) == $extract_number &&
			!empty($post['extract_data_sample_rate']) && is_array($post['extract_data_sample_rate']) && count($post['extract_data_sample_rate']) == $extract_number &&
			!empty($post['extract_data_encoding']) && is_array($post['extract_data_encoding']) && count($post['extract_data_encoding']) == $extract_number &&
			!empty($post['extract_data_playtime']) && is_array($post['extract_data_playtime']) && count($post['extract_data_playtime']) == $extract_number)
		{
			for ($i = 0; $i < $extract_number; $i++)
			{
				$extract_linked_data = array(
					'disc_number' => $post['extract_disc_number'][$i],
					'track_number' => $post['extract_track_number'][$i],
					'name' => $post['extract_name'][$i],
					'album_id' => $post['extract_album_id'][$i],
					'game_id' => $post['extract_game_id'][$i],
					'composer_id' => $post['extract_composer_id'][$i]
				);

				$extract_data = array(
					'url' => $post['extract_data_url'][$i],
					'md5' => $post['extract_data_md5'][$i],
					'filesize' => $post['extract_data_filesize'][$i],
					'bitrate' => $post['extract_data_bitrate'][$i],
					'sample_rate' => $post['extract_data_sample_rate'][$i],
					'encoding' => $post['extract_data_encoding'][$i],
					'playtime' => $post['extract_data_playtime'][$i],
				);

				$success = insertExtract($pdo, $extract_data, $extract_linked_data);

				if (!$success)
				{
					echo 'Fail to insert the following extract: <br>';

					echo '<pre>';
					print_r($extract_data);
					echo '</pre>';

					echo '<pre>';
					print_r($extract_linked_data);
					echo '</pre>';

					echo '<hr />';
				}
			}
		}
	}
}

/** Update functions **/

function updateExtract($pdo, $post)
{
	if (!empty($post['extract_id']) && !empty($post['extract_name']) && !empty($post['extract_game']) && 
		!empty($post['extract_composer']) && !empty($post['extract_famousness']))
	{
		$extract_id = $post['extract_id'];
		$extract_name = $post['extract_name'];
		$extract_game = $post['extract_game'];
		$extract_composer = $post['extract_composer'];
		$extract_exclude = isset($post['extract_exclude']) ? 1 : 0;
		$extract_remix = isset($post['extract_remix']) ? 1 : 0;
		$extract_famousness = $post['extract_famousness'];

		$update = '
			UPDATE
				vgbt_extracts
			SET
				name = "' . $extract_name . '",
				game_id = ' . $extract_game . ',
				composer_id = ' . $extract_composer . ',
				exclude = ' . $extract_exclude . ',
				remix = ' . $extract_remix . ',
				famousness = ' . $extract_famousness . '
			WHERE
				id = ' . $extract_id . '
		';

		$result = $pdo->exec($update);

		if ($result)
		{
			echo 'Data successfully updated!<br>';
		}
		else
		{
			echo 'Fail to update the data...<br>';
		}
	}
	else
	{
		echo 'Sorry but the post data doesn\'t contain all needed informations.<br>';
	}

	return false;
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

function getAllGamesFromGameSerie($pdo, $gameSerie, $onlyId = false)
{
	$select = 'id';
	$where = 'WHERE game_serie';

	if ($gameSerie == 0)
		$where .= ' IS NULL';
	else
		$where .= ' = ' . $gameSerie;

	if (!$onlyId)
		$select .= ', name';

	$sql = $pdo->query('SELECT ' . $select . ' FROM vgbt_games ' . $where);

	if ($onlyId)
		return $sql->fetchAll(PDO::FETCH_COLUMN);
	else
		return $sql->fetchAll();
}

function getAllExtractsFromGame($pdo, $game)
{
	$select = 'id, name';
	$where = 'WHERE game_id = ' . $game;

	$sql = $pdo->query('SELECT ' . $select . ' FROM vgbt_extracts ' . $where);

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

function getAllGameSeries($pdo)
{
	$sql = $pdo->query('SELECT id, name FROM vgbt_game_series');

	return $sql->fetchAll();
}

function getAllComposersAsAssociativeArray($pdo)
{
	$sql = $pdo->query('SELECT id, name FROM vgbt_composers');

	$composers = array();

	while ($data = $sql->fetch())
	{
		$composers[$data['id']] = $data['name'];
	}

	return $composers;
}

function getAllGamesAsAssociativeArray($pdo, $excludeGames = null)
{
	$where = '';
	if ($excludeGames != null && is_array($excludeGames))
	{
		$where = 'WHERE id != ' . $excludeGames[0];
		foreach($excludeGames as $excludeGameId) 
			$where .= ' AND id != ' . $excludeGameId;
	}

	$sql = $pdo->query('SELECT id, name FROM vgbt_games ' . $where);

	$games = array();

	while ($data = $sql->fetch())
	{
		$games[$data['id']] = $data['name'];
	}

	return $games;
}

// Utils

function printArray($array)
{
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

function writeLog($label, $text)
{
	$filename = $label . '-' . date('d_m_Y-h_i_s') . '.log';
	$filepath = __ROOT__ . 'logs/' . $filename;

	file_put_contents($filepath, $text);
}

?>