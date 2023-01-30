<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $randomCategory = random_int(0, 4);
            $product = new Product();
            $product->setName(str_replace(['.'], [''],$faker->sentence(2)));
            $product->setSlug(strtolower(str_replace([' '], ['-'], $product->getName())));
            $product->setImage('https://via.placeholder.com/150');
            $product->setPrice($faker->randomFloat(2, 0, 1000));
            $product->setDescription($faker->paragraph());
            $product->setCategory($this->getReference("category$randomCategory"));
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getOrder(): array
    {
        return [
            Category::class,
        ];
    }
}
