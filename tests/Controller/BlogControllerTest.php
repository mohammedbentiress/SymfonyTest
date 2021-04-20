<?php

namespace App\Tests\Controller;

use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Panther\PantherTestCase;

class BlogControllerTest extends PantherTestCase
{
    /**
     * @dataProvider provideUrls
     */
    public function testGetVisibleBlogs($url)
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isSuccessful());
//        $this->assertSelectorTextContains('html h2', 'Nihil totam sed et quia fugiat amet rerum pariatur.');

//        $link = $crawler
//            ->filter('a:contains("Perferendis eos.")') // find all links with the text "Greet"
//            ->eq(1) // select the second link in the list
//            ->link()
//        ;
//        // and click it
//        $crawler = $client->click($link);

        // asserts that the response matches a given CSS selector.
//        $this->assertGreaterThan(0, $crawler->filter('h2')->count());

        $client->request('GET', '/blog/voluptatem-magnam-ut-nostrum');

        $this->assertTrue($client->getResponse()->isNotFound());
    }

    /**
     * Provide url for test.
     *
     * @return \string[][]
     */
    public function provideUrls(): array
    {
        return [
            ['/blog/nihil-totam-sed-et-quia-fugiat-amet-rerum-pariatur'],
            ['/search'],
            ['/blog/dicta-facere-corrupti-id-occaecati-a-ut-voluptatem'],
            // ...
        ];
    }

    /**
     * test for comment add form.
     */
    public function testAddCommentForm()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/blog/ipsam-qui-sint-iste-perferendis-laboriosam');
        $client->getMouse()->mouseMoveTo('.burger');
        $client->takeScreenshot('tests\Screenshots\hover_on_title.png');

        $client->waitForVisibility('.arrow-collapse');
        $client->getWebDriver()->findElement(WebDriverBy::className('arrow-collapse'))->click();
        $client->takeScreenshot('tests\Screenshots\click_on_menu.png');
        sleep(2);


        $form = $crawler->selectButton('submit')->form([
            'add_comment[username]' => 'mohammed',
            'add_comment[userEmail]' => 'test@test.com',
            'add_comment[content]' => 'content from test class',
        ]);




        sleep(2);
        $client->takeScreenshot('tests\Screenshots\before_submit.png');
        $result = $client->submit($form);
//        $crawler->selectButton('submit')->click();
        sleep(5);

        $client->takeScreenshot('tests\Screenshots\after_submit.png');

        $this->assertSelectorIsEnabled('.alert.alert-success');
    }

    /**
     * Test the user is in session.
     */
    public function testUserInSession()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/blog/ipsam-qui-sint-iste-perferendis-laboriosam');
        $session = new Session($crawler->getSession());
        $user = [
            'name' => 'mohammed',
            'email' => 'mohammed@test.com',
        ];
        $session->set('USER', $user);


        sleep(20);
    }
}
