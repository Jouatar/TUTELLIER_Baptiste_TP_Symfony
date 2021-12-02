<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface as DataFixturesDependentFixtureInterface;

class ArticleFixtures extends Fixture implements DataFixturesDependentFixtureInterface
{
    public function load(ObjectManager $manager){
        $faker = Factory::create();
        $categories = $manager->getRepository(Category::class)->findAll();

        for ($i = 1; $i <= 10; $i++){
            $article = new Article();

            $sentence = $faker->sentence(4);
            $title = substr($sentence, 0, strlen($sentence) - 1);
            $index = rand(0, count($categories) - 1);
            $category = $categories[$index];

            $article->setTitle($title)
                    ->setAuthor($faker->name())
                    ->setContent($faker->text(1500))
                    ->setCreatedAt($faker->dateTimeThisYear())
                    ->setCategory($category);

            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getDependencies(){
        return [CategoryFixtures::class];
    }

}
