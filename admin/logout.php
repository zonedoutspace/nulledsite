<?php
include '../config/config.php';

AdminLogout();

//session_start();
session_destroy();
header("Location:login.php");

?>