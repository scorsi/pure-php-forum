<?php
/**
 * Created by PhpStorm.
 * User: sylva
 * Date: 21/06/2017
 * Time: 15:33
 */

session_start();
define('VALID_REQUIRE', true);

require_once 'db.php';
require_once 'connected.php';

if ($connected === true)
{
    header('Location: index.php');
    exit();
}



if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']))
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $req = $db->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
    $req->bindParam(':username', $username);
    $req->bindParam(':email', $email);
    $req->execute();

    if ($req->rowCount() > 0)
    {
        echo 'An account with theses information already exists.';
        header('Location: login.php');
        exit();
    }

    $password = password_hash($password, PASSWORD_BCRYPT);

    $req = $db->prepare('INSERT INTO users(username, email, password) VALUE(:username, :email, :password)');
    $req->bindParam(':username', $username);
    $req->bindParam(':email', $email);
    $req->bindParam(':password', $password);
    $req->execute();

    echo "Successfully registered.";
    header('Location: login.php');
    exit();
}

?>

<form action="register.php" method="post">
    <label for="username-input">Username: </label><input id="username-input" name="username" type="text" placeholder="username" /><br />
    <label for="email-input">Email: </label><input id="email-input" name="email" type="text" placeholder="email" /><br />
    <label for="password-input">Password: </label><input id="password-input" name="password" type="password" placeholder="password" /><br />
    <input type="submit" value="connect" />
</form>
