<?php

namespace App\Tests\Controller;

use App\Entity\ServiceOffer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ServiceOfferControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/service/offer/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(ServiceOffer::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('ServiceOffer index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'service_offer[date_created]' => 'Testing',
            'service_offer[email]' => 'Testing',
            'service_offer[user]' => 'Testing',
            'service_offer[rateService]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new ServiceOffer();
        $fixture->setDate_created('My Title');
        $fixture->setEmail('My Title');
        $fixture->setUser('My Title');
        $fixture->setRateService('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('ServiceOffer');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new ServiceOffer();
        $fixture->setDate_created('Value');
        $fixture->setEmail('Value');
        $fixture->setUser('Value');
        $fixture->setRateService('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'service_offer[date_created]' => 'Something New',
            'service_offer[email]' => 'Something New',
            'service_offer[user]' => 'Something New',
            'service_offer[rateService]' => 'Something New',
        ]);

        self::assertResponseRedirects('/service/offer/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate_created());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getRateService());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new ServiceOffer();
        $fixture->setDate_created('Value');
        $fixture->setEmail('Value');
        $fixture->setUser('Value');
        $fixture->setRateService('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/service/offer/');
        self::assertSame(0, $this->repository->count([]));
    }
}
