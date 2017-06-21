<?php
/**
 * Created by PhpStorm.
 * User: sylva
 * Date: 21/06/2017
 * Time: 19:24
 */

session_start();
session_destroy();

setcookie('username', '', 0);
setcookie('password', '', 0);

header('Location: login.php');
exit();
