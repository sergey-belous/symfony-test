<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setUsername('user')
            ->setRoles(['ROLE_USER'])
        ;

        $passwordHasherFactory = new PasswordHasherFactory([
            PasswordAuthenticatedUserInterface::class => ['algorithm' => 'auto'],
        ]);
        $passwordHasher = new UserPasswordHasher($passwordHasherFactory);
        $hashedPassword = $passwordHasher->hashPassword($user, 'password');
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->flush();
    }
}