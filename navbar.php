<?php
/**
 * Created by PhpStorm.
 * User: sylva
 * Date: 22/06/2017
 * Time: 10:01
 */

if (!defined('VALID_REQUIRE') || VALID_REQUIRE !== true)
    die('Invalid include');

?>
<nav>
    <div class="container">
        <div class="nav-wrapper">
            <a href="index.php" class="brand-logo">MyForum</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <?php if ($connected === false): ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php else: ?>
                    <li><a href="disconnect.php">Disconnect</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
