<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

class BasicTest extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();

        $client->request('GET', '/');

        self::assertPageTitleContains('Login');
    }
}
