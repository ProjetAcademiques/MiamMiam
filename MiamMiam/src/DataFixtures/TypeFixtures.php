<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{
    public const TYPE_FRUIT = 'type_fruit';
    public const TYPE_LEGUME = 'type_legume';
    public const TYPE_VIANDE = 'type_viande';
    public const TYPE_POISSON = 'type_poisson';
    public const TYPE_LAITAGE = 'type_laitage';
    public const TYPE_EPICERIE = 'type_epicerie';
    public const TYPE_BOISSON = 'type_boisson';
    
    public function load(ObjectManager $manager): void
    {
        $types = [
            ['nom' => 'Fruits', 'reference' => self::TYPE_FRUIT],
            ['nom' => 'Légumes', 'reference' => self::TYPE_LEGUME],
            ['nom' => 'Viandes', 'reference' => self::TYPE_VIANDE],
            ['nom' => 'Poissons', 'reference' => self::TYPE_POISSON],
            ['nom' => 'Laitages', 'reference' => self::TYPE_LAITAGE],
            ['nom' => 'Épicerie', 'reference' => self::TYPE_EPICERIE],
            ['nom' => 'Boissons', 'reference' => self::TYPE_BOISSON],
        ];
        
        foreach ($types as $typeData) {
            $type = new Type();
            $type->setNom($typeData['nom']);
            $manager->persist($type);
            
            // Référence pour être utilisée dans d'autres fixtures
            $this->addReference($typeData['reference'], $type);
        }

        $manager->flush();
    }
}
