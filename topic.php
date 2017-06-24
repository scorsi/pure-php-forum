<?php
/**
 * Created by PhpStorm.
 * User: sylva
 * Date: 21/06/2017
 * Time: 15:34
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



if (!isset($_GET['id']) || !is_numeric($_GET['id']))
    die('Invalid id');

$id = $_GET['id'];

$req = $db->prepare('SELECT * FROM topics WHERE id = ?');
$req->execute(array($id));
if ($req->rowCount() == 0)
    die('Invalid id');
$topic = $req->fetch();
$req->closeCursor();



if (isset($_POST['answer']))
{
    $req = $db->prepare('INSERT INTO answers(user_id, topic_id, message) VALUE (:userId, :topicId, :message)');
    $req->bindParam(':userId', $connectedId);
    $req->bindParam(':topicId', $topic['id']);
    $req->bindParam(':message', $_POST['answer']);
    $req->execute();
}


$req = $db->prepare('SELECT * FROM answers WHERE topic_id = ?');
$req->execute(array($topic['id']));

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
        <div class="col s12">
            <h3><?= $topic['name'] ?></h3>
        </div>
        <div class="col s12">
            <ul class="collection">
                <?php while ($data = $req->fetch()):
                    $newReq = $db->prepare('SELECT * FROM users WHERE id = :id');
                    $newReq->bindParam(':id', $data['user_id']);
                    $newReq->execute();
                    $creatorUsername = $newReq->fetch()['username'];
                    $newReq->closeCursor();
                    ?>
                    <li class="collection-item">
                        <div class="row">
                            <div class="col s4"><?= $creatorUsername ?></div>
                            <div class="col s8">
                                <div class="row">
                                    <div class="col s12"><?= $data['date'] ?></div>
                                    <div class="col s12"><?= $data['message'] ?></div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
        <form action="topic.php?id=<?= $topic['id'] ?>" method="post" class="col s6 offset-s3">
            <div class="row">
                <div class="input-field col s12">
                    <textarea class="materialize-textarea" id="answer-input" name="answer"></textarea>
                    <label for="answer-input">Message</label>
                </div>
                <div class="input-field col s12">
                    <input class="waves-effect waves-light btn" type="submit" value="Answer" />
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
<?php
$req->closeCursor();
