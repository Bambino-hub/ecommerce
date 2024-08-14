<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $faker = \Faker\Factory::create();
        // $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));

        // return a string that contains a url like 'https://picsum.photos/800/600/'
        // $faker->imageUrl(width: 800, height: 600);

        // // download a properly sized image from picsum into a file with a file path like '/tmp/13b73edae8443990be1aa8f1a483bc27.jpg'
        // $filePath = $faker->image(dir: '/tmp', width: 640, height: 480);
        // // $product = new Product();
        // // $manager->persist($product);

        $manager->flush();
    }
}