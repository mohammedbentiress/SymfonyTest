<?php

namespace App\Tests\Form;

use App\Entity\Comment;
use App\Form\AddCommentType;
use Symfony\Component\Form\Test\TypeTestCase;

class AddCommentTypeTest extends TypeTestCase
{
    /**
     * Test the form addCommentType
     */
    public function testAddCommentForm()
    {
        $formData = [
            'add_comment[username]' => 'test',
            'add_comment[userEmail]' => 'test@test.com',
            'add_comment[content]' => 'content from test class',
        ];

        $comment = new Comment();
        $form = $this->factory->create(AddCommentType::class, $comment);
        // $form = $crawler->selectButton('submit')->form();
        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        $expected = (new Comment())
            ->setUsername('test')
            ->setUserEmail('test@test.com')
            ->setContent('content from test class')
        ;

        // check that $formData was modified as expected when the form was submitted
        $this->assertEquals($expected, $comment, 'the comment should be equal to the expected data');
    }
}
