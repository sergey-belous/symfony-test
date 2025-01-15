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
    public const USER_1_USERNAME_FIXTURE = 'user';
    public const USER_1_PASSWORD_FIXTURE = 'password';
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setUsername(self::USER_1_USERNAME_FIXTURE)
            ->setRoles(['ROLE_USER'])
        ;

        $passwordHasherFactory = new PasswordHasherFactory([
            PasswordAuthenticatedUserInterface::class => ['algorithm' => 'auto'],
        ]);
        $passwordHasher = new UserPasswordHasher($passwordHasherFactory);
        $hashedPassword = $passwordHasher->hashPassword($user, self::USER_1_PASSWORD_FIXTURE);
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->flush();
    }
}