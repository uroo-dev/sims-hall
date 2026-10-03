<?php

namespace App\VoltTests;

use VoltTest\Laravel\Contracts\VoltTestCase;
use VoltTest\Laravel\VoltTestManager;

class LoginTest implements VoltTestCase
{
    /**
     * Define the test scenario.
     */
    public function define(VoltTestManager $manager): void
    {
        // Set the target URL for the test
        $manager->target('http://localhost:8000');

        // Define your test scenario
        $scenario = $manager->scenario('LoginTest');

        // No routes selected
    }
}
