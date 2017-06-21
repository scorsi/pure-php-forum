<?php
/**
 * Created by PhpStorm.
 * User: sylva
 * Date: 21/06/2017
 * Time: 15:35
 */

if (!defined('VALID_REQUIRE') || VALID_REQUIRE !== true)
    die('Invalid include');

try
{
    $db = new PDO('mysql:host=localhost;dbname=pure-php-forum;charset=utf8', 'root');
}
catch (Exception $exception)
{
    die('Failed to connect to the database.');
}
