<?php

namespace App\Tests\Controller;

use App\Repository\RateServiceRepository;
use App\Repository\ServiceOfferRepository;
use App\Tests\LoginControllerTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ServiceOfferControllerTest extends WebTestCase
{
    public function testNewRenders(): void
    {
        $client = LoginControllerTest::createAuthenticatedClient();

        $router = static::getContainer()->get('router');

        $crawler = $client->request(
            'GET', 
            $router->generate('app_service_offer_new')
        );

        $this->assertResponseIsSuccessful();

        $this->assertCount(1, $crawler->filter('input#service_offer_email'));
        $this->assertCount(1, $crawler->filter('select#service_offer_rateService'));
        $this->assertCount(1, $crawler->filter('button[type=submit]'));
    }

    public function testNewRendersErrors(): void
    {
        $client = LoginControllerTest::createAuthenticatedClient();

        $router = static::getContainer()->get('router');

        $crawler = $client->request(
            'GET', 
            $router->generate('app_service_offer_new')
        );

        $this->assertResponseIsSuccessful();

        $crawler = $client->submitForm('service_offer_submit',[
            'service_offer[email]' => 'notvalidemail@',
            'service_offer[rateService]' => ''
        ]);

        $this->assertCount(1, $crawler->filter('input#service_offer_email + .invalid-feedback'));
        $this->assertCount(1, $crawler->filter('select#service_offer_rateService + .invalid-feedback'));
    }

    public function testNewWorkflow(): void
    {
        $client = LoginControllerTest::createAuthenticatedClient();

        $router = static::getContainer()->get('router');

        $crawler = $client->request(
            'GET', 
            $router->generate('app_service_offer_new')
        );

        $this->assertResponseIsSuccessful();

        /** @var ServiceOfferRepository $serviceOfferRepository */
        $serviceOfferRepository = $this->getContainer()->get(ServiceOfferRepository::class);

        $countServiceOffersBefore = $serviceOfferRepository->createQueryBuilder('so')
            ->select('count(so.id)')
            ->getQuery()->getSingleScalarResult()
        ;

        /** @var RateServiceRepository $rateServiceRepository */
        $rateServiceRepository = $this->getContainer()->get(RateServiceRepository::class);

        $rateSerivice = $rateServiceRepository->findOneBy([]);

        if (!$rateSerivice) {
            $this->fail('No rateService for test provided');
        }

        $testEmail = 'valid.email@at.ru';

        $crawler = $client->submitForm('service_offer_submit',[
            'service_offer[email]' => $testEmail,
            'service_offer[rateService]' => $rateSerivice->getId()
        ]);

        $countServiceOffersAfter = $serviceOfferRepository->createQueryBuilder('so')
            ->select('count(so.id)')
            ->getQuery()->getSingleScalarResult()
        ;

        $similarServiceOffers = $serviceOfferRepository->findBy(['email' => $testEmail, 'rateService' => $rateSerivice]);

        $this->assertEquals($countServiceOffersBefore + 1, $countServiceOffersAfter);
        $this->assertGreaterThan(0, count($similarServiceOffers));
    }
}
