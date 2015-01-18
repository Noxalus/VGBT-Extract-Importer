<h1>Add composer</h1>

<?php
require_once('../../config/config.php');
require_once(__ROOT__ . '/libs/getid3/getid3.php');

require_once(__ROOT__ . 'config/mysql.php');
require_once(__ROOT__ . 'config/function.php');

$input_dir = MEDIA_INPUT_FOLDER . 'Final Fantasy 3 OST/';
$output_dir = MEDIA_OUTPUT_FOLDER;

$extracts_data = getMp3Data($input_dir);

$composers = getAllComposers($pdo);

$missing_composers = array();

foreach($extracts_data as $data)
{
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

echo '<pre>';
print_r($missing_composers);
echo '</pre>';