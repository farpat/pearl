<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CategoryFixtures extends Fixture
{
    public const COUNT = 10;
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= self::COUNT; $i++) {
            $category = (new Category)
                ->setName(sprintf("Category << %s >>", $this->faker->words(mt_rand(2, 5), true)));
            $this->addReference(CategoryFixtures::class . '_' . $i, $category);
            $manager->persist($category);
        }
        $manager->flush();
    }
}
