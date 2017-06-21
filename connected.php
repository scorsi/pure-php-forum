<?php
/**
 * Created by PhpStorm.
 * User: sylva
 * Date: 21/06/2017
 * Time: 15:34
 */

if (!defined('VALID_REQUIRE') || VALID_REQUIRE !== true)
    die('Invalid include');

$connected = true;

if (!isset($_SESSION['username']) || !isset($_SESSION['password']))
{
    if (isset($_COOKIE['username']) && isset($_COOKIE['password']))
    {
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['password'] = $_COOKIE['password'];
    }
    else
        $connected = false;
}

if ($connected === true)
{
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $req = $db->prepare('SELECT * FROM users WHERE username = :username');
    $req->bindParam(':username', $username);
    $req->execute();

    if ($req->rowCount() == 0)
    {
        header('Location: login.php');
        exit();
    }

    $connected = false;

    while ($data = $req->fetch())
    {
        if ($password == $data['password'])
            $connected = true;
    }
    $req->closeCursor();
}
