<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0;$i<10;$i++){
            $category = new Category();
            $category->setName('Category '.$i);
            $manager->persist($category);
        }
        $manager->flush();
        for ($j=0;$j<1000;$j++){
            $categories=$manager->getRepository(Category::class)->findAll();
            $product = new Product();
            $product->setDescription('Description '.$j);
            $product->setName('Product '.$j);
            $product->setPrice(rand(100,1000));
            $product->setCategories($categories[rand(0,sizeof($categories)-1)]);
            $manager->persist($product);
        }


        $manager->flush();
    }
}
