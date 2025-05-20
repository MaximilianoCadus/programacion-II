<?php

require __DIR__ . '/namespace.php';

use Models\User;

$user = new User();
echo $user->sayHello();