<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;
use function Amp\Iterator\toArray;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $images=[
            "https://placehold.co/250/orange/white.png?text=Product\nimage",
            "https://placehold.co/250/orange/white.png?text=Product\nimage",
            "https://placehold.co/250/orange/white.png?text=Product\nimage"
        ];
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
            $product->setImages($images);
            try {
                $category=$categories[random_int(0, count($categories) - 1)];
                $product->setPrice(random_int(100, 1000));
                $product->setCategory($category);
            } catch (RandomException $e) {
                echo $e->getMessage();
            }
            $manager->persist($product);
            $manager->persist($category);
        }


        $manager->flush();
    }
}
