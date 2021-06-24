<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Pet;
use App\Entity\Product;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName('product '.$i);
            $product->setDescription('desc '.$i);
            $product->setPrice(mt_rand(10, 100));
            $product->setImage("4ac8afbd184b24c29003f6123fb52e3c.png");
            $manager->persist($product);
        }

        for ($i = 0; $i < 20; $i++) {
            $pet = new Pet();
            $pet->setName('pet '.$i);
            $pet->setSpecies('species '.$i);
            $pet->setBreed('breed '.$i);
            $pet->setAge($i);
            $pet->setWeight($i);      
            if ($i%2 == 0 ) {
                $pet->setSex('female');
                $pet->setAdoptedAt(new \DateTimeImmutable);
                $pet->setImage("450f9c2b6cc505cb99b617235793bca9.jpg.");
            } else {
                $pet->setImage("f39600adad341cb6402d7de581cd5ab6.jpg.");
                $pet->setSex('male');
            }
            
            $manager->persist($pet);
        }

        for ($i = 0; $i < 20; $i++) {
            $article = new Article();
            $article->setTitle('article '.$i);
            $article->setDescription('desc '.$i);
            $manager->persist($article);
        }
       

        $manager->flush();
    }
}
