<?php
/**
 * Created by PhpStorm.
 * User: sylva
 * Date: 21/06/2017
 * Time: 15:33
 */

define('VALID_REQUIRE', true);

require_once 'db.php';

if (isset($_POST['username']) && isset($_POST['password']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

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
    <input type="submit" value="connect" />
</form>
