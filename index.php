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


if (isset($_POST['name']) && isset($_POST['message']))
{
    $name = $_POST['name'];
    $message = $_POST['message'];

    $req = $db->prepare('INSERT INTO topics(user_id, name) VALUE (:userId, :name)');
    $req->bindParam(':userId', $connectedId);
    $req->bindParam(':name', $name);
    $req->execute();

    $topicId = $db->lastInsertId();

    $req = $db->prepare('INSERT INTO answers(user_id, topic_id, message) VALUE (:userId, :topicId, :message)');
    $req->bindParam(':userId', $connectedId);
    $req->bindParam(':topicId', $topicId);
    $req->bindParam(':message', $message);
    $req->execute();
}


$req = $db->query('SELECT * FROM topics');
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
        <table class="col s12 striped">
            <thead>
            <tr>
                <th>Topic's name</th>
                <th>Creator</th>
                <th>Answer's number</th>
                <th>Last update</th>
            </tr>
            </thead>

            <tbody>

                <?php while ($data = $req->fetch()):

                    $newReq = $db->prepare('SELECT * FROM users WHERE id = :id');
                    $newReq->bindParam(':id', $data['user_id']);
                    $newReq->execute();
                    $creatorUsername = $newReq->fetch()['username'];
                    $newReq->closeCursor();

                    $newReq = $db->prepare('SELECT * FROM answers WHERE topic_id = :id');
                    $newReq->bindParam(':id', $data['id']);
                    $newReq->execute();
                    $answersNumber = $newReq->rowCount();
                    $newReq->closeCursor();
                    ?>
                    <tr>
                        <td><a href="topic.php?id=<?= $data['id'] ?>"><?= $data['name'] ?></a></td>
                        <td><?= $creatorUsername ?></td>
                        <td><?= $answersNumber ?></td>
                        <td>
                            <?php
                                $newReq = $db->prepare('SELECT * FROM answers WHERE topic_id = :topicId ORDER BY id DESC LIMIT 1');
                                $newReq->bindParam(':topicId', $data['id']);
                                $newReq->execute();
                                echo $newReq->fetch()['date'];
                                $newReq->closeCursor();
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </tbody>
        </table>

        <form action="index.php" method="post" class="col s6 offset-s3">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="name-input" name="name" />
                    <label for="name-input">Topic's name</label>
                </div>
                <div class="input-field col s12">
                    <textarea class="materialize-textarea" id="message-input" name="message"></textarea>
                    <label for="message-input">Message</label>
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
