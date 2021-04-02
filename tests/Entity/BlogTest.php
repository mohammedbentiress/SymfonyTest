<?php

namespace App\Tests\Entity;

use App\Entity\Blog;
use App\Entity\Category;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class BlogTest extends KernelTestCase
{
    /**
     * Create and return an entity blog for test.
     */
    public function getEntityBlog(): Blog
    {
        $faker = Factory::create();
        $category = (new Category())
            ->setTitle('Category 1')
        ;

        return $blog = (new Blog())
            ->setTitle('Top 10 Most Useful Online Courses That Are Free')
            ->setCategory($category)
            ->setCover('https://miro.medium.com/max/700/0*RJx8Bih14Dk7_vD5')
            ->setVisible(true)
            ->setTrending(false)
            ->setPopular(false)
            ->setCreatedAt(new \DateTime())
            ->setIntroduction($faker->sentence($nbWords = rand(15, 30), $variableNbWords = true))
            ->setContent('<p>'.\implode('</p><p>', $faker->paragraphs(rand(20, 30))).'</p>')
        ;
    }

    public function assertHasErrors(Blog $blog, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($blog);
        $messages = [];
        /* @var ConstraintViolation $error */
        foreach ($messages as $message) {
            $messages[] = $error->getPropertyPath().' => '.$error->getMessage();
        }
        $this->assertCount($number, $errors, implode(',', $messages));
    }

    /**
     * Test for a valid blog entity.
     */
    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntityBlog(), 0);
    }

    /**
     * Test for an invalid title for blog entity.
     */
    public function testInvalidTitleBlog()
    {
        $this->assertHasErrors($this->getEntityBlog()->setTitle(''), 1);
    }

    /**
     * Test for an invalid content for blog entity.
     */
    public function testInvalidSlugBlog()
    {
        $this->assertHasErrors($this->getEntityBlog()->setContent(''), 1);
    }

//    /**
//     * Test for an invalid created at for blog entity.
//     */
//    public function testInvalidCreatedAtBlog()
//    {
//        $value = \DateTime::createFromFormat('U', time());
//        $this->assertHasErrors($this->getEntityBlog()->setCreatedAt($value), 1);
//    }

    /**
     * Test for an invalid introduction for blog entity.
     */
    public function testInvalidIntroductionBlog()
    {
        $this->assertHasErrors($this->getEntityBlog()->setIntroduction(''), 1);
    }
}
