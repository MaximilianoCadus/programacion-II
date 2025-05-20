<?php

namespace Models;

require __DIR__ . '/ns_base.php';

use Base\Person;

class Employee extends Person
{
    public function work()
    {
        return "Working...\n";
    }
}