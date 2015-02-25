<?php

header("Content-type: text/xml;charset=utf-8");

require_once('../config/config.php');
require_once(__ROOT__ . '/config/mysql.php');
require_once(__ROOT__ . '/config/function.php');

if (!empty($_GET['extractNumber']))
{
	$result = getExtractNumber($pdo);

	echo '<?xml version="1.0" encoding="UTF-8"?>';
	echo "\n";
	echo '<extract_number>' . $result . '</extract_number>';
}
else if (isset($_GET['gameSerie']) && is_numeric($_GET['gameSerie']))
{
	$gameSerieId = $_GET['gameSerie'];
	
	if ($gameSerieId >= 0)
	{
		$games = getAllGamesFromGameSerie($pdo, $gameSerieId);

		echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
		echo "<games>\n";
		foreach ($games as $game) 
		{
			echo '	<game id="' . $game['id'] . '" game_serie_id="' . $gameSerieId . '">' . $game['name'] . '</game>' . "\n";
		}
		echo "</games>";
	}
	else
	{
		$gameSeries = getAllGameSeries($pdo);
		$gameSeries[] = array('id' => 0, 'name' => 'Others');

		echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
		echo "<game_series>\n";
		foreach ($gameSeries as $gameSerie) 
		{
			echo '  <game_serie id="' . $gameSerie['id'] . '" name="' . htmlspecialchars($gameSerie['name']) . '">' . "\n";

			$games = getAllGamesFromGameSerie($pdo, $gameSerie['id']);

			echo "    <games>\n";
			foreach ($games as $game) 
			{
				echo '      <game id="' . $game['id'] . '" name="' . htmlspecialchars($game['name']) . '">' . "\n";

				$extracts = getAllExtractsFromGame($pdo, $game['id']);

				echo "        <extracts>\n";
				foreach ($extracts as $extract) 
				{
					echo '          <extract id="' . $extract['id'] . '">' . htmlspecialchars($extract['name']) . '</extract>' . "\n";
				}
				echo "        </extracts>\n";

				echo '      </game>' . "\n";
			}
			echo "    </games>\n";

			echo '  </game_serie>' . "\n";
		}
		echo "</game_series>";
	}
}
else
{
	$possibleTypes = array('name', 'game', 'composer');

	$questionNumber = 10;
	$type = 'name';

	$games = array();
	$composers = array();

	if (!empty($_GET['questionNumber']) && is_numeric($_GET['questionNumber']) && $_GET['questionNumber'] > 0)
		$questionNumber = $_GET['questionNumber'];
	if (!empty($_GET['type']) && in_array($_GET['type'], $possibleTypes))
		$type = $_GET['type'];

	$excludeTitles = array();
	// Exclude titles
	if (!empty($_GET['excludeTitles']))
	{
		$excludeTitles = $_GET['excludeTitles'];
		$excludeTitles = explode(',', $excludeTitles);
	}

	$excludeGames = array();
	// Exclude games
	if (!empty($_GET['excludeGames']))
	{
		$excludeGames = $_GET['excludeGames'];
		$excludeGames = explode(',', $excludeGames);
	}

	// Exclude game series
	if (!empty($_GET['excludeGameSeries']))
	{
		$excludeGameSeries = $_GET['excludeGameSeries'];
		$excludeGameSeries = explode(',', $excludeGameSeries);

		for ($i = 0; $i < count($excludeGameSeries); $i++) 
		{ 
			$excludeGames = array_unique(array_merge($excludeGames, getAllGamesFromGameSerie($pdo, $excludeGameSeries[$i], true)));
		}
	}

	if ($type == 'name')
		$results = getRandomExtractQuiz($pdo, $questionNumber, $excludeGames, $excludeTitles);
	else if ($type == 'game')
	{
		$games = getAllGamesAsAssociativeArray($pdo, $excludeGames);
		$results = getRandomGameQuiz($pdo, $questionNumber, $excludeGames, $excludeTitles);
	}
	else if ($type == 'composer')
	{
		$composers = getAllComposersAsAssociativeArray($pdo);
		$results = getRandomComposerQuiz($pdo, $questionNumber, $excludeGames, $excludeTitles);
	}

	$questions = array();

	/*
	echo '<pre>';
	print_r($results);
	echo '</pre>';

	die();
	*/

	if ($type == 'name')
	{
		$counter = 0;
		$currentIndex = -1;

		$resultNumber = count($results);
		if ($resultNumber >= 4)
		{
			foreach ($results as $key => $value)
			{
				if ($counter % 4 == 0)
				{
					if ($resultNumber - $counter < 4)
						break;

					$currentIndex++;
					$questions[$currentIndex] = array(
						'answer' => rand(0, 3),
						'extract_id' => -1,
						'questions' => array()
					);
				}
				
				if ($counter % 4 == $questions[$currentIndex]['answer'])
					$questions[$currentIndex]['extract_id'] = $key;

				$questions[$currentIndex]['questions'][] = htmlspecialchars($value);
				$counter++;
			}

			$currentIndex++;
		}
	}
	else if ($type == 'game' || $type == 'composer')
	{
		$currentIndex = 0;
		foreach ($results as $key => $value)
		{
			$questions[$currentIndex] = array(
				'answer' => rand(0, 3),
				'extract_id' => $key,
				'questions' => array()
			);

			if ($type == 'game')
			{
				$name = htmlspecialchars($games[$value]);
				$copy = $games;
			}
			else if ($type == 'composer')
			{
				$name = htmlspecialchars($composers[$value]);
				$copy = $composers;
			}

			// We remove the id of the answer to avoid duplicate
			if (count($copy) > 1)
			{
				$keys = array_keys($copy, $name);
				if (count($keys) == 1)
					unset($copy[$keys[0]]);
			}

			for ($i = 0; $i < 4; $i++)
			{
				if ($i == $questions[$currentIndex]['answer'])
					$questions[$currentIndex]['questions'][] = $name;
				else
				{
					$random_key = array_rand($copy);
					$questions[$currentIndex]['questions'][] = $copy[$random_key];

					if (count($copy) > 1)
						unset($copy[$random_key]);
				}
			}

			$currentIndex++;
		}
	}

	$countQuestion = count($questions);
	if ($countQuestion < $questionNumber)
	{
		$results_copy = array();
		for ($i = $countQuestion; $i < $questionNumber; $i++)
		{
			if (count($results_copy) == 0)
				$results_copy = $results;

			// Answer extract id
			$key = array_rand($results_copy);

			unset($results_copy[$key]);

			$questions[$currentIndex] = array(
				'answer' => rand(0, 3),
				'extract_id' => $key,
				'questions' => array()
			);

			$name = $results[$key];
			$copy = $results;

			// Name correspond to extract name, game_id 
			// or composer_id according to type selected
			if ($type == 'game')
			{
				$name = htmlspecialchars($games[$name]);
				$copy = $games;
			}
			else if ($type == 'composer')
			{
				$name = htmlspecialchars($composers[$name]);
				$copy = $composers;
			}

			// We remove the id of the answer to avoid duplicate
			if (count($copy) > 1)
			{
				$keys = array_keys($copy, $name);
				if (count($keys) == 1)
					unset($copy[$keys[0]]);
			}

			for ($j = 0; $j < 4; $j++)
			{
				if ($j == $questions[$currentIndex]['answer'])
					$questions[$currentIndex]['questions'][] = $name;
				else
				{
					$random_key = array_rand($copy);

					$questions[$currentIndex]['questions'][] = $copy[$random_key];

					if (count($copy) > 1)
						unset($copy[$random_key]);
				}
			}

			$currentIndex++;
		}
	}

	/*
	echo '<pre>';
	print_r($questions);
	echo '</pre>';

	echo count($questions);

	echo '<pre>';
	print_r($questions);
	echo '</pre>';
	*/

	$output = '';
	$output .= '<?xml version="1.0" encoding="UTF-8"?>';
	$output .= "\n";
	$output .= '<quiz>';
	$output .= "\n";

	foreach ($questions as $value)
	{
		$output .= '	<question answer="' . $value['answer'] . '" id="' . $value['extract_id'] . '">';
		$output .= "\n";
		
		foreach ($value['questions'] as $data)
		{
			$output .= '		<answer>' . $data . '</answer>';
			$output .= "\n";	
		}

		$output .= '	</question>';
		$output .= "\n";
	}

	$output .= '</quiz>';

	writeLog('Quiz', $output);

	echo $output;
}