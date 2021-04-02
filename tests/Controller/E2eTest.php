<?php

namespace App\Tests;

use App\Controller\BlogController;
use Symfony\Component\Panther\PantherTestCase;

class E2eTest extends PantherTestCase
{
    /**
     * Functional test on getVisibleBlog methode.
     */
    public function testGetVisibleBlog(): void
    {
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $client->request('GET', '/blog/nihil-totam-sed-et-quia-fugiat-amet-rerum-pariatur');
        sleep(2);

        // Use any PHPUnit assertion, including the ones provided by Symfony
        $this->assertPageTitleContains('Nihil totam sed et quia fugiat amet rerum pariatur.');
        sleep(2);

        $this->assertSelectorTextContains('h2', 'Nihil totam sed et quia fugiat amet rerum pariatur.');
        sleep(2);

        $client->request('GET', '/blog/voluptatem-magnam-ut-nostrum');
        sleep(2);

    }

}
