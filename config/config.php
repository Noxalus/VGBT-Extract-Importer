<?php

define('__ROOT__', str_replace('\\', '/', dirname(dirname(__FILE__))) . '/');

define('PDO_DATABASE', 'vgbt');
define('PDO_USER', 'root');
define('PDO_PASSWORD', '');

define('MEDIA_FOLDER', __ROOT__ . '/media/');
define('MEDIA_INPUT_FOLDER', MEDIA_FOLDER . '/input/');
define('MEDIA_OUTPUT_FOLDER', MEDIA_FOLDER . '/output/');
?>