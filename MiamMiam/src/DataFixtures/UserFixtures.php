<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_ADMIN = 'user_admin';
    public const USER_1 = 'user_1';
    public const USER_2 = 'user_2';
    public const USER_3 = 'user_3';
    public const USER_4 = 'user_4';
    public const USER_5 = 'user_5';

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
        // Ajout de la référence pour l'admin
        $this->addReference(self::USER_ADMIN, $admin);

        // utilisateurs
        $users = [
            ['email' => 'jean@example.com', 'pseudo' => 'Jean', 'password' => 'password123', 'reference' => self::USER_1],
            ['email' => 'marie@example.com', 'pseudo' => 'Marie', 'password' => 'password456', 'reference' => self::USER_2],
            ['email' => 'paul@example.com', 'pseudo' => 'Paul', 'password' => 'password789', 'reference' => self::USER_3],
            ['email' => 'sophie@example.com', 'pseudo' => 'Sophie', 'password' => 'passwordabc', 'reference' => self::USER_4],
            ['email' => 'thomas@example.com', 'pseudo' => 'Thomas', 'password' => 'passwordxyz', 'reference' => self::USER_5],
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
            // Ajout d'une référence pour chaque utilisateur
            $this->addReference($userData['reference'], $user);
        }

        $manager->flush();
    }
}
