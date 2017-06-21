<?php
/**
 * Created by PhpStorm.
 * User: sylva
 * Date: 21/06/2017
 * Time: 15:34
 */

define('VALID_REQUIRE', true);

require_once 'db.php';
require_once 'connected.php';

if (!isset($_GET['id']) && is_numeric($_GET['id']))
    die('Invalid id');

$id = $_GET['id'];

$req = $db->prepare('SELECT * FROM topics WHERE id = ?');
$req->execute(array($id));

$topic = $req->fetch();

echo $topic['name'];

$req->closeCursor();

?>
<br />
<br />
<?php

$req = $db->prepare('SELECT * FROM answers WHERE topic_id = ?');
$req->execute(array($topic['id']));

while ($data = $req->fetch())
{
    echo $data['message'];
    echo '<br />';
}

$req->closeCursor();
