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
        <form class="col s6 offset-s3" action="login.php" method="post">

            <div class="row">
                <div class="input-field col s12">
                    <input id="username-input" name="username" type="text" class="validate" />
                    <label for="username-input">Username or Email</label>
                </div>

                <div class="input-field col s12">
                    <input id="password-input" name="password" type="password" class="validate" />
                    <label for="password-input">Password</label>
                </div>

                <div class="input-field col s6">
                    <input class="waves-effect waves-light btn" type="submit" value="Connect" />
                </div>

                <div class="input-field col s6">
                    <input id="stay-connected-input" name="stay-connected" type="checkbox" />
                    <label for="stay-connected-input">Stay connected</label>
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
