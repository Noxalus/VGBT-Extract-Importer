<?php

header("Content-type: text/xml;charset=utf-8");  

require_once('../config.php');
require_once(__ROOT__ . 'config/mysql.php');
require_once(__ROOT__ . 'config/fonctions.php');

$possibleTypes = array('nom', 'jeu', 'compositeur');

$questionNumber = $_GET['questionNumber'];
$type = $_GET['type'];

$questionNumber = 10;
$type = 'nom';

if (!empty($_GET['questionNumber']) && is_numeric($_GET['questionNumber']) && $_GET['questionNumber'] > 0)
	$questionNumber = $_GET['questionNumber'];
if (!empty($_GET['type']) && in_array($_GET['type'], $possibleTypes))
	$type = $_GET['type'];

$results = getRandomQuiz($pdo, $questionNumber, $type);

/*
echo '<pre>';
print_r($results);
echo '</pre>';
*/

$questions = array();

$counter = 0;
$currentIndex = -1;
foreach ($results as $key => $value)
{
	if ($counter % 4 == 0)
	{
		$currentIndex++;
		$questions[$currentIndex] = array(
			'answer' => rand(0, 3),
			'questions' => array()
		);
	}

	$value = explode('|', $value);
	$value = $value[0];
	$questions[$currentIndex]['questions'][] = array($key, htmlspecialchars($value));
	$counter++;
}

$countQuestion = count($questions);
if ($countQuestion < $questionNumber)
{
	for ($i = $countQuestion; $i < $questionNumber; $i++)
	{
		for ($j = 0; $j < 4; $j++)
		{
			if ($j % 4 == 0)
			{
				$currentIndex++;
				$questions[$currentIndex] = array(
					'answer' => rand(0, 3),
					'questions' => array()
				);
			}

			$key = array_rand($results);
			$value = $results[$key];
			$value = explode('|', $value);
			$value = $value[0];
			$questions[$currentIndex]['questions'][] = array($key, htmlspecialchars($value));
		}
	}
}

//echo count($questions);

/*
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
	echo '	<question answer="' . $value['answer'] . '" id="' . $value['questions'][$value['answer']][0] . '">';
	echo "\n";
	
	foreach ($value['questions'] as $data)
	{
		echo '		<answer>' . $data[1] . '</answer>';
		echo "\n";	
	}

	echo '	</question>';
	echo "\n";
}

echo '</quiz>';