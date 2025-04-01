<?php

namespace App\DataFixtures;

use App\Entity\Magasin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MagasinFixtures extends Fixture
{
    public const MAGASIN_CARREFOUR = 'magasin_carrefour';
    public const MAGASIN_LECLERC = 'magasin_leclerc';
    public const MAGASIN_AUCHAN = 'magasin_auchan';
    public const MAGASIN_INTERMARCHE = 'magasin_intermarche';
    public const MAGASIN_LIDL = 'magasin_lidl';

    public function load(ObjectManager $manager): void
    {
        $magasins = [
            ['nom' => 'Carrefour', 'reference' => self::MAGASIN_CARREFOUR],
            ['nom' => 'Leclerc', 'reference' => self::MAGASIN_LECLERC],
            ['nom' => 'Auchan', 'reference' => self::MAGASIN_AUCHAN],
            ['nom' => 'Intermarché', 'reference' => self::MAGASIN_INTERMARCHE],
            ['nom' => 'Lidl', 'reference' => self::MAGASIN_LIDL],
        ];
        
        foreach ($magasins as $magasinData) {
            $magasin = new Magasin();
            $magasin->setNom($magasinData['nom']);
            $manager->persist($magasin);
            
            // Référence pour être utilisée dans d'autres fixtures
            $this->addReference($magasinData['reference'], $magasin);
        }

        $manager->flush();
    }
}
