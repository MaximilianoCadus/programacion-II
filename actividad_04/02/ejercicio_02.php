<?php

require __DIR__ . '/ns_models.php';

use Models\Employee;

$Employee = new Employee();
echo $Employee->work();