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



if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['stay-connected']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stayConnected = $_POST['stay-connected'];

    $req = $db->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
    $req->bindParam(':username', $username);
    $req->bindParam(':email', $username);
    $req->execute();

    while ($data = $req->fetch())
    {
        if (password_verify($password, $data['password']))
        {
            $req->closeCursor();
            header('Location: index.php');
            $_SESSION['username'] = $data['username'];
            $_SESSION['password'] = $data['password'];
            if ($stayConnected == true)
            {
                setcookie('username', $data['username'], time() + 3600 * 24 * 7, null, null, false, true);
                setcookie('password', $data['password'], time() + 3600 * 24 * 7, null, null, false, true);
            }
            exit();
        }
    }
    echo 'invalid username/email or password';
    $req->closeCursor();
}
?>

<form action="login.php" method="post">
    <label for="username-input">Username or email: </label><input id="username-input" name="username" type="text" placeholder="username or email" /><br />
    <label for="password-input">Password: </label><input id="password-input" name="password" type="password" placeholder="password" /><br />
    <label for="stay-connected-inpot">Stay connected: </label><input id="stay-connected-input" name="stay-connected" type="checkbox" /><br />
    <input type="submit" value="connect" />
</form>
