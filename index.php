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

    <table class="striped">
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
                    <td>0</td>
                </tr>
            <?php endwhile; ?>

        </tbody>
    </table>

</div>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js"></script>
</body>
</html>
<?php
$req->closeCursor();
