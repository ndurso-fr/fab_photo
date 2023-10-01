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
        /* Users */
        $password = '123456';
        // known users
        $this->createUser($manager, ['ROLE_USER', 'ROLE_ADMIN'], $password,
            'Nathalie', 'D\'urso',
            'ndurso', 'nathaliedurso@gmx.fr',
        true);

        // faker user
        for ($i = 0; $i < 10; $i++) {
            $this->createUser($manager, ['ROLE_USER'], $password);
        }

        $manager->flush();
    }


    /**
     * @param ObjectManager $manager
     * @param array $roles
     * @param string $password
     * @param string|null $fistName
     * @param string|null $lastName
     * @param string|null $pseudo
     * @param string|null $email
     * @return void
     */
    private function createUser(ObjectManager $manager,
                                array $roles,
                                string $password,
                                string $fistName=null,
                                string $lastName=null,
                                string $pseudo=null,
                                string $email=null,
                                bool $forceIsVerified=false
    ):void {
        $user = (new User())
            ->setFirstname($fistName ?? $this->faker->firstName())
            ->setLastname($lastName ?? $this->faker->lastName())
            ->setPseudo($pseudo ?? $this->faker->name())
            ->setEmail($email ?? $this->faker->email())
            ->setRoles($roles);

        if ($forceIsVerified) {
            $user->setIsVerified(true);
        }
        $hashPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashPassword);
        $manager->persist($user);
    }
}
