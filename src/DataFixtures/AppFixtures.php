<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = (new User())
                ->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setPseudo($this->faker->name())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER']);

            $hashPassword = $this->passwordHasher->hashPassword(
                $user,
                '123456'
            );
            $user->setPassword($hashPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
