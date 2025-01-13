<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\RateService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RateServiceFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $serviceFlat = (new RateService())
            ->setName('Оценка стоимости квартиры.')
            ->setCost('2200.00')
        ;
        $manager->persist($serviceFlat);

        $serviceAuto = (new RateService())
            ->setName('Оценка стоимости автомобиля.')
            ->setCost('1200.00')
        ;
        $manager->persist($serviceAuto);

        $serviceBusiness = (new RateService())
            ->setName('Оценка стоимости бизнеса.')
            ->setCost('3000.00')
        ;
        $manager->persist($serviceBusiness);

        $manager->flush();
    }
}