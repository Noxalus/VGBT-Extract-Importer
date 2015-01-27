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

	if ($type == 'name')
		$results = getRandomExtractQuiz($pdo, $questionNumber);
	else if ($type == 'game')
	{
		$games = getAllGamesAsAssociativeArray($pdo);
		$results = getRandomGameQuiz($pdo, $questionNumber);
	}
	else if ($type == 'composer')
	{
		$composers = getAllComposersAsAssociativeArray($pdo);
		$results = getRandomComposerQuiz($pdo, $questionNumber);
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
		foreach ($results as $key => $value)
		{
			if ($counter % 4 == 0)
			{
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

	echo '<?xml version="1.0" encoding="UTF-8"?>';
	echo "\n";
	echo '<quiz>';
	echo "\n";

	foreach ($questions as $value)
	{
		echo '	<question answer="' . $value['answer'] . '" id="' . $value['extract_id'] . '">';
		echo "\n";
		
		foreach ($value['questions'] as $data)
		{
			echo '		<answer>' . $data . '</answer>';
			echo "\n";	
		}

		echo '	</question>';
		echo "\n";
	}

	echo '</quiz>';
}