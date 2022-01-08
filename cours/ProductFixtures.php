<?php

namespace Product\DataFixtures;
use Faker\Factory;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 1; $i <= 10; $i++)
        {
            $product = new Product();

            $sentence = $faker->sentence(4);
            $title = substr($sentence, 0, strlen($sentence) - 1);
            $product->setTitle($title)
                ->setPrice($faker->randomNumber(2))
                ->setDescription($faker->text(3000))
                ->setCreatedAt($faker->dateTimeThisYear());

            $manager->persist($product);
        }

        $manager->flush();
    }
}
