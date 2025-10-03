<?php

$base_url = 'http://localhost/AI-Musicgen';

$db_hsot = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'Musicgen';

$conn = mysqli_connect($db_hsot,$db_user,$db_pass,$db_name) or die('connection failed');

define('WP','mylogin2024');
