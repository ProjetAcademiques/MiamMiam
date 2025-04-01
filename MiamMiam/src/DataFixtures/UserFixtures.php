<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // administrateur
        $admin = new User();
        $admin->setEmail('minh@ad.fr');
        $admin->setPseudo('minh@ad.fr');
        $admin->setPassword(password_hash('minh@ad.fr', PASSWORD_BCRYPT));
        $admin->setDateCreation(new \DateTime());
        $admin->setIsAdmin(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // utilisateurs
        $users = [
            ['email' => 'jean@example.com', 'pseudo' => 'Jean', 'password' => 'password123'],
            ['email' => 'marie@example.com', 'pseudo' => 'Marie', 'password' => 'password456'],
            ['email' => 'paul@example.com', 'pseudo' => 'Paul', 'password' => 'password789'],
            ['email' => 'sophie@example.com', 'pseudo' => 'Sophie', 'password' => 'passwordabc'],
            ['email' => 'thomas@example.com', 'pseudo' => 'Thomas', 'password' => 'passwordxyz'],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setPseudo($userData['pseudo']);
            $user->setPassword(password_hash($userData['password'], PASSWORD_BCRYPT));
            $user->setDateCreation(new \DateTime());
            $user->setIsAdmin(false);
            $user->setRoles([]);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
