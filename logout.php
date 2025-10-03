<?php

session_start();
include 'config.php';

unset($_SESSION[WP.'checklogin']);
unset($_SESSION[WP.'email']);
unset($_SESSION[WP.'id']);
unset($_SESSION[WP.'username']);
unset($_SESSION[WP.'password']);

header("location:{$base_url}/templates/index.php");
