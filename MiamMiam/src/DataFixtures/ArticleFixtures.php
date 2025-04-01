<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Type;
use App\Entity\Magasin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public const ARTICLE_POMME = 'article_pomme';
    public const ARTICLE_BANANE = 'article_banane';
    public const ARTICLE_CAROTTE = 'article_carotte';
    public const ARTICLE_STEAK = 'article_steak';
    public const ARTICLE_SAUMON = 'article_saumon';
    public const ARTICLE_LAIT = 'article_lait';
    public const ARTICLE_RIZ = 'article_riz';
    public const ARTICLE_EAU = 'article_eau';
    public const ARTICLE_PAIN = 'article_pain';
    public const ARTICLE_FROMAGE = 'article_fromage';

    public function load(ObjectManager $manager): void
    {
        $articles = [
            [
                'nom' => 'Pommes',
                'prix' => 2.99,
                'reference' => self::ARTICLE_POMME,
                'type' => TypeFixtures::TYPE_FRUIT,
                'magasins' => [MagasinFixtures::MAGASIN_CARREFOUR, MagasinFixtures::MAGASIN_LECLERC]
            ],
            [
                'nom' => 'Bananes',
                'prix' => 1.99,
                'reference' => self::ARTICLE_BANANE,
                'type' => TypeFixtures::TYPE_FRUIT,
                'magasins' => [MagasinFixtures::MAGASIN_LECLERC, MagasinFixtures::MAGASIN_LIDL]
            ],
            [
                'nom' => 'Carottes',
                'prix' => 1.49,
                'reference' => self::ARTICLE_CAROTTE,
                'type' => TypeFixtures::TYPE_LEGUME,
                'magasins' => [MagasinFixtures::MAGASIN_AUCHAN, MagasinFixtures::MAGASIN_INTERMARCHE]
            ],
            [
                'nom' => 'Steak haché',
                'prix' => 5.99,
                'reference' => self::ARTICLE_STEAK,
                'type' => TypeFixtures::TYPE_VIANDE,
                'magasins' => [MagasinFixtures::MAGASIN_CARREFOUR, MagasinFixtures::MAGASIN_AUCHAN]
            ],
            [
                'nom' => 'Filet de saumon',
                'prix' => 7.99,
                'reference' => self::ARTICLE_SAUMON,
                'type' => TypeFixtures::TYPE_POISSON,
                'magasins' => [MagasinFixtures::MAGASIN_CARREFOUR]
            ],
            [
                'nom' => 'Lait',
                'prix' => 1.09,
                'reference' => self::ARTICLE_LAIT,
                'type' => TypeFixtures::TYPE_LAITAGE,
                'magasins' => [MagasinFixtures::MAGASIN_LECLERC, MagasinFixtures::MAGASIN_LIDL]
            ],
            [
                'nom' => 'Riz',
                'prix' => 2.29,
                'reference' => self::ARTICLE_RIZ,
                'type' => TypeFixtures::TYPE_EPICERIE,
                'magasins' => [MagasinFixtures::MAGASIN_INTERMARCHE, MagasinFixtures::MAGASIN_AUCHAN]
            ],
            [
                'nom' => 'Eau minérale',
                'prix' => 0.79,
                'reference' => self::ARTICLE_EAU,
                'type' => TypeFixtures::TYPE_BOISSON,
                'magasins' => [MagasinFixtures::MAGASIN_CARREFOUR, MagasinFixtures::MAGASIN_LIDL]
            ],
            [
                'nom' => 'Baguette',
                'prix' => 0.95,
                'reference' => self::ARTICLE_PAIN,
                'type' => TypeFixtures::TYPE_EPICERIE,
                'magasins' => [MagasinFixtures::MAGASIN_LECLERC, MagasinFixtures::MAGASIN_INTERMARCHE]
            ],
            [
                'nom' => 'Emmental râpé',
                'prix' => 2.49,
                'reference' => self::ARTICLE_FROMAGE,
                'type' => TypeFixtures::TYPE_LAITAGE,
                'magasins' => [MagasinFixtures::MAGASIN_AUCHAN, MagasinFixtures::MAGASIN_LIDL]
            ],
        ];
        
        foreach ($articles as $articleData) {
            $article = new Article();
            $article->setNom($articleData['nom']);
            $article->setPrix($articleData['prix']);
            
            // Ajouter le type à l'article
            $type = $this->getReference($articleData['type'], Type::class);
            $article->addType($type);
            
            // Ajouter les magasins à l'article
            foreach ($articleData['magasins'] as $magasinRef) {
                $magasin = $this->getReference($magasinRef, Magasin::class);
                $article->addMagasin($magasin);
            }
            
            $manager->persist($article);
            
            // Référence pour être utilisée dans d'autres fixtures
            $this->addReference($articleData['reference'], $article);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TypeFixtures::class,
            MagasinFixtures::class,
        ];
    }
}