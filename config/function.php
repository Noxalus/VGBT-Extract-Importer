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
			$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

			if ($extension == 'mp3')
			{

				$extract_data = array();
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
					echo "Error: This music has not a constant bitrate!";
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
						$track_number = $tags['track_number'][0];

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

function insertExtract($pdo, $data)
{
	$sql = $pdo->prepare('SELECT COUNT(*) FROM vgbt_extracts WHERE md5= ?');
	$sql->bindValue(1, $data['md5'], PDO::PARAM_STR);

	$success = $sql->execute();
	if ($success)
	{
		$exist = $sql->fetchColumn() > 0;

		if (!$exist)
		{
			$insert = "
			INSERT INTO 
				vgbt_extracts(
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
				NOW()
			)";

			$sql = $pdo->prepare($insert);
			$sql->bindValue(1, $data['md5'], PDO::PARAM_STR);
			$sql->bindValue(2, $data['filesize'], PDO::PARAM_INT);
			$sql->bindValue(3, $data['bitrate'], PDO::PARAM_INT);
			$sql->bindValue(4, $data['sample_rate'], PDO::PARAM_INT);
			$sql->bindValue(5, $data['encoding'], PDO::PARAM_STR);
			$sql->bindValue(6, $data['playtime'], PDO::PARAM_STR);

			$success = $sql->execute();

			if ($success)
			{
				$last_insert_id = $pdo->lastInsertId();

				// We move the mp3 file to output folder
				$output_complete_path = MEDIA_OUTPUT_FOLDER . $last_insert_id . '.mp3';
				copy($data['url'], $output_complete_path);

				// Initialize getID3 tag-writing module 
				$tagwriter = new getid3_writetags;
				$tagwriter->filename = $output_complete_path;
				$tagwriter->remove_other_tags = true;

				if ($tagwriter->WriteTags()) 
				{ 
					$data['id'] = $last_insert_id;

					if (!empty($tagwriter->warnings)) 
					{ 
						echo 'There were some warnings:<br>' . implode('<br><br>', $tagwriter->warnings) . '<br>'; 
					} 
					
					return $data; 
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

?>