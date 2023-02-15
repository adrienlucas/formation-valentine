<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageTest extends WebTestCase
{
    public static function provideUrls(): array
    {
        return [
            ['/', 'Hello world !'],
            ['/hello/adrien', 'Hello adrien !'],
            ['/hello', 'Hello world !'],
        ];
    }

    /**
     * @dataProvider provideUrls
     * @group functional
     */
    public function testItSaysHelloOnHomepageAndHellopage(string $url, string $expectedMessage): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
        $jsonResponse = $client->getResponse()->getContent();
        $response = json_decode($jsonResponse, true);

        $this->assertArrayHasKey('message', $response);
        $this->assertSame($expectedMessage, $response['message']);
    }

}
