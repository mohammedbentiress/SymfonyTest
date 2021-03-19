<?php

namespace App\Controller;

use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * home page.
     *
     * @Route("/home", name="home")
     *
     * @Route("/", name="default")
     */
    public function index(BlogRepository $blogRepo, CategoryRepository $categoryRepo): Response
    {
        // find the last created blogs
        $last_blogs = $blogRepo->findBy([], ['createdAt' => 'DESC'], 6, 0);

        $categories = $categoryRepo->findBy(['id' => [25, 26]]);

        $popularBlogs = $blogRepo->findBy(['popular' => true, 'visible' => true]);
        $trendingBlogs = $blogRepo->findBy(['trending' => true, 'visible' => true]);

        return $this->render('default/index.html.twig', [
            'popular' => $popularBlogs,
            'last' => $last_blogs,
            'trends' => $trendingBlogs,
            'categories' => $categories,
        ]);
    }

    /**
     * diplay menu nav.
     *
     * @Route("/menu", name="menu")
     */
    public function menu(CategoryRepository $categoryRepo): Response
    {
        $categories = $categoryRepo->findAll();

        return $this->render('default/menu.html.twig', [
            'categories' => $categories,
        ]);
    }
}
