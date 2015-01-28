<?php

require_once('../../config/config.php');
require_once(__ROOT__ . '/config/mysql.php');

require_once(__ROOT__ . '/config/mysql.php');
require_once(__ROOT__ . '/config/function.php');

require_once(__ROOT__ . '/libs/getid3/getid3.php');
require_once(__ROOT__ . '/libs/getid3/write.php'); 

?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Insert extracts</title>
  <link rel="stylesheet" href="../style/style.css">
  <script src="../js/jquery-2.1.3.min.js"></script>
  <script src="../js/script.js"></script>
</head>
<body>

<h1>Insert extracts</h1>
	
<?php
insert_extracts($pdo, $_POST);
?>

</body>
</html>