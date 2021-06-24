<?php

namespace App\DataFixtures;


use Faker;
use App\Entity\Pet;
use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Product;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\File;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $finderProducts = new Finder();
        $finderPets = new Finder();

        $myProductImages = [];
        $myPetsImages = [];

        $sourceProductsFolder = "public/img/exemples/products/";
        $sourcePetsFolder = "public/img/exemples/pets/";
        $destFolder = "public/img/upload/";

        // Rechercher les images de produits
        $files = $finderProducts->files()->in($sourceProductsFolder);
        foreach ($files as $file) {
            // $absoluteFilePath = $file->getRealPath();
            $fileNameWithExtension = $file->getRelativePathname();
            $myProductImages[] = $fileNameWithExtension;
        }

        // Rechercher les images des animaux
        $files = $finderPets->files()->in($sourcePetsFolder);
        foreach ($files as $file) {
            // $absoluteFilePath = $file->getRealPath();
            $fileNameWithExtension = $file->getRelativePathname();
            $myPetsImages[] = $fileNameWithExtension;
        }

        // Affectations des produits en base de données
        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName($faker->name);
            $product->setDescription(implode(' ', $faker->words()));
            $product->setPrice($faker->randomFloat(2, 10, 100));

            // Nom de l'image + extension
            $nbAlea = mt_rand(0, count($myProductImages)-1);
            // Copier l'image
            copy($sourceProductsFolder.$myProductImages[$nbAlea], $destFolder.$myProductImages[$nbAlea]);
            // Renomer L'image
            $newName = md5(uniqid()).'.jpg';
            rename($destFolder.$myProductImages[$nbAlea], $destFolder.$newName);
            // Insérer en base de données
            $product->setImage($newName);
            $manager->persist($product);
        }

        // Affectations des animaux en base de données
        for ($i = 0; $i < 20; $i++) {
            $pet = new Pet();
            $pet->setName($faker->name);
            $pet->setSpecies($faker->jobTitle); 
            $pet->setBreed($faker->state);
            $pet->setAge($faker->randomFloat(0, 1, 25));
            $pet->setWeight($faker->randomFloat(0, 1, 25));
            
            // Nom de l'image + extension
            $nbAlea = mt_rand(0, count($myPetsImages)-1);
            // Copier l'image
            
            copy($sourcePetsFolder.$myPetsImages[$nbAlea], $destFolder.$myPetsImages[$nbAlea]);
            // Renomer L'image
            $newName = md5(uniqid()).'.jpg';
            rename($destFolder.$myPetsImages[$nbAlea], $destFolder.$newName);
            // Insérer en base de données
            $pet->setImage($newName);

            if ($i%2 == 0 ) {
                $pet->setSex('female');
                $pet->setAdoptedAt(new \DateTimeImmutable);
            } else {
                $pet->setSex('male');
            }
            
            $manager->persist($pet);
        }

        for ($i = 0; $i < 20; $i++) {
            $article = new Article();
            $article->setTitle($faker->name);
            $article->setDescription(implode(' ', $faker->words()));
            $manager->persist($article);
        }
        $manager->flush();
    }
}
