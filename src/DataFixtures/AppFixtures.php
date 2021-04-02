<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    const NBR_BLOGS = 6;
    const NBR_CATEGORIES = 8;
    const NBR_COMMENTS = 6;

    /**
     * @var
     */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * load fake categories, blogs, comments to database.
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < self::NBR_CATEGORIES; ++$i) {
            $category = new Category();
            $nbrWords = rand(1, 2);
            $category->setTitle($this->faker->sentence($nbrWords, $variableNbWords = true));
            for ($j = 0; $j < rand(2, self::NBR_BLOGS); ++$j) {
                $blog = new Blog();
                $nbrSentences = rand(20, 30);
                $nbWords = rand(2, 8);
                $blog->setTitle($this->faker->sentence($nbWords, $variableNbWords = true))
                    ->setIntroduction($this->faker->sentence($nbWords = rand(15, 30), $variableNbWords = true))
                    ->setContent('<p>'.\implode('</p><p>', $this->faker->paragraphs($nbrSentences)).'</p>')
                    ->setCreatedAt($this->faker->dateTime($max = 'now', $timezone = null))
                    ->setCover('https://picsum.photos/781/502')
                    ->setPopular($this->faker->boolean($chanceOfGettingTrue = 30))
                    ->setTrending($this->faker->boolean($chanceOfGettingTrue = 20))
                    ->setVisible($this->faker->boolean($chanceOfGettingTrue = 90))
                    ->setCategory($category);
                for ($k = 0; $k < rand(1, self::NBR_COMMENTS); ++$k) {
                    $comment = new Comment();
                    $days = (new \DateTime())->diff($blog->getCreatedAt())->days;
                    $nbSentences = rand(3, 5);
                    $comment->setContent($this->faker->paragraph($nbSentences))
                            ->setUsername($this->faker->name)
                            ->setUserEmail($this->faker->email)
                            ->setValid($this->faker->boolean($chanceOfGettingTrue = 85))
                            ->setCreatedAt($this->faker->dateTimeBetween('-'.$days.' days'))
                            ->setBlog($blog);
                    $manager->persist($comment);
                }
                $manager->persist($blog);
            }
            $manager->persist($category);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}
