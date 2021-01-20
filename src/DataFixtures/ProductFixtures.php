<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ProductFixtures extends Fixture
{
    public const COUNT = 1000;
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= self::COUNT; $i++) {
            /** @var Category $category */
            $key = $i === 1000 ? 10 : ($i / 100) + 1;
            $category = $this->getReference(CategoryFixtures::class . '_' . (int)$key);
            $product = (new Product)
                ->setName(sprintf('Product << %s >>', $this->faker->words(1, 3)))
                ->setDescription($this->faker->paragraph)
                ->setPictureUrl($this->faker->imageUrl())
                ->setPrice($this->faker->randomFloat(2))
                ->setCategory($category);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
