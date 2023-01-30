<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName(str_replace(['.'], [''],$faker->sentence(1)));
            $category->setSlug(strtolower(str_replace([' '], ['-'], $category->getName())));
            $this->addReference("category$i", $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
