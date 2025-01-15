<?php

namespace App\Tests;

use App\DataFixtures\UserFixture;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginControllerTest extends WebTestCase
{
    public function testNotAuthorizedRedirects(): void
    {
        $client = static::createClient();

        $router = static::getContainer()->get('router');

        $client->request(
            'GET', 
            $router->generate('app_service_offer_new')
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->assertResponseRedirects($router->generate('app_login'));

        $crawler = $client->followRedirect();

        $this->assertCount(1, $crawler->filter('input#username'));
        $this->assertCount(1, $crawler->filter('input#password'));
        $this->assertCount(1, $crawler->filter('button[type=submit]'));
    }

    public static function createAuthenticatedClient(): KernelBrowser
    {
        $client = static::createClient();
        
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(
            ['username' => UserFixture::USER_1_USERNAME_FIXTURE]
        );

        $client->loginUser($user);

        return $client;
    }
}
