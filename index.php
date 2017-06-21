<?php
/**
 * Created by PhpStorm.
 * User: sylva
 * Date: 21/06/2017
 * Time: 15:20
 */

session_start();
define('VALID_REQUIRE', true);

require_once 'db.php';
require_once 'connected.php';

if ($connected === false)
{
    header('Location: login.php');
    exit();
}



$req = $db->query('SELECT * FROM topics');

while ($data = $req->fetch())
{
    echo '<a href="topic.php?id=' . $data['id'] . '">' . $data['name'] . '</a>';
    echo '<br />';
}

$req->closeCursor();
