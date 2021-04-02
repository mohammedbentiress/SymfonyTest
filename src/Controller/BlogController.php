<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Comment;
use App\Form\AddCommentType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

     /**
      * Get  the blog specified by slug and being visible .
      * and add a comment to blog.
      *
      * @Route("/blog/{slug}", name="blog")
      *
      * @param SessionInterface $session
      * @param Request $request
      * @param Blog $blog
      * @param EntityManagerInterface $manager
      * @return Response
      */
    public function getVisibleBlog(SessionInterface $session, Request $request, Blog $blog, EntityManagerInterface $manager): Response
    {
        if (!$blog->getVisible()) {
            throw $this->createNotFoundException('The blog does not exist');
        }

        $related = $blog->getCategory()->getBlogs();
        $comment = new Comment();

        if (true === $session->has('USER')) {
            dump('user found in session');
            $user = $session->get('USER');
            $comment->setUsername($user['name'])
                ->setUserEmail($user['email']);
        } else {
            $user = [
                'name' => '',
                'email' => '',
            ];
        }

        $form = $this->createForm(AddCommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user['name'] = $comment->getUsername();
            $user['email'] = $comment->getUserEmail();
            $session->set('USER', $user);
            $comment->setValid(true)
                ->setBlog($blog)
                ->setCreatedAt(new \DateTime());
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'comment added');

            return $this->redirectToRoute('blog', ['slug' => $blog->getSlug()],Response::HTTP_CREATED);
        }

        return $this->render('blog/index.html.twig', [
              'blog' => $blog,
              'related' => $related,
              'form' => $form->createView(),
              'comments' => $blog->getValidComments(),
          ]);
    }

    /**
     * Performs the search of the blog by a given query.
     *
     * @Route("/search", name="search")
     *
     * @param Request        $request    the request instance
     * @param BlogRepository $repository the repository instance
     *
     * @return Response the response instance
     */
    public function search(Request $request, BlogRepository $repository): Response
    {
        $q = $request->query->get('q');
        $blogs = $repository->search([
            'term' => $q,
        ]);

        return $this->render('default/searched.html.twig', [
            'blogs' => $blogs,
        ]);
    }
}
