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
<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body>

<?php require_once 'navbar.php'; ?>

<div class="container">

    <div class="row">
        <form class="col s6 offset-s3" action="register.php" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input id="username-input" name="username" type="text" class="validate" />
                    <label for="username-input">Username</label>
                </div>

                <div class="input-field col s12">
                    <input id="email-input" name="email" type="text" class="validate" />
                    <label for="email-input">Email</label>
                </div>

                <div class="input-field col s12">
                    <input id="password-input" name="password" type="password" class="validate" />
                    <label for="password-input">Password</label>
                </div>

                <div class="input-field col s12">
                    <input class="waves-effect waves-light btn" type="submit" value="Register" />
                </div>
            </div>
        </form>
    </div>

</div>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js"></script>
</body>
</html>

