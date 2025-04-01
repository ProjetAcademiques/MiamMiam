<?php

namespace App\DataFixtures;

use App\Entity\Liste;
use App\Entity\ListeArticle;
use App\Entity\User;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ListeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->createListeForUser(UserFixtures::USER_ADMIN, 'Courses du weekend', [
            ['article' => ArticleFixtures::ARTICLE_POMME, 'quantite' => 5],
            ['article' => ArticleFixtures::ARTICLE_LAIT, 'quantite' => 2],
            ['article' => ArticleFixtures::ARTICLE_PAIN, 'quantite' => 3],
        ], $manager);
        
        $this->createListeForUser(UserFixtures::USER_1, 'Courses hebdomadaires', [
            ['article' => ArticleFixtures::ARTICLE_BANANE, 'quantite' => 3],
            ['article' => ArticleFixtures::ARTICLE_CAROTTE, 'quantite' => 1],
            ['article' => ArticleFixtures::ARTICLE_STEAK, 'quantite' => 2],
        ], $manager);
        
        $this->createListeForUser(UserFixtures::USER_2, 'Soirée entre amis', [
            ['article' => ArticleFixtures::ARTICLE_FROMAGE, 'quantite' => 2],
            ['article' => ArticleFixtures::ARTICLE_EAU, 'quantite' => 4],
            ['article' => ArticleFixtures::ARTICLE_PAIN, 'quantite' => 2],
        ], $manager);
        
        $this->createListeForUser(UserFixtures::USER_3, 'Repas de famille', [
            ['article' => ArticleFixtures::ARTICLE_SAUMON, 'quantite' => 1],
            ['article' => ArticleFixtures::ARTICLE_RIZ, 'quantite' => 1],
            ['article' => ArticleFixtures::ARTICLE_CAROTTE, 'quantite' => 2],
        ], $manager);
        
        $this->createListeForUser(UserFixtures::USER_4, 'Petit déjeuner', [
            ['article' => ArticleFixtures::ARTICLE_PAIN, 'quantite' => 1],
            ['article' => ArticleFixtures::ARTICLE_LAIT, 'quantite' => 1],
        ], $manager);
        
        $this->createListeForUser(UserFixtures::USER_5, 'BBQ du dimanche', [
            ['article' => ArticleFixtures::ARTICLE_STEAK, 'quantite' => 4],
            ['article' => ArticleFixtures::ARTICLE_EAU, 'quantite' => 6],
            ['article' => ArticleFixtures::ARTICLE_FROMAGE, 'quantite' => 1],
        ], $manager);

        $manager->flush();
    }
    
    private function createListeForUser(string $userRef, string $nom, array $articles, ObjectManager $manager): void
    {
        $liste = new Liste();
        $liste->setNom($nom);
        
        $user = $this->getReference($userRef, User::class);
        $liste->addUser($user);
        
        $liste->setDateCreation(new \DateTime());
        $manager->persist($liste);
        
        foreach ($articles as $articleData) {
            $listeArticle = new ListeArticle();
            $listeArticle->setListe($liste);
            $listeArticle->setArticle($this->getReference($articleData['article'], Article::class));
            $listeArticle->setQuantite($articleData['quantite']);
            $listeArticle->setDateAjout(new \DateTime());
            
            $manager->persist($listeArticle);
        }
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ArticleFixtures::class,
        ];
    }
}