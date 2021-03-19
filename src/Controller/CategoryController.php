<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * 
     */

     /**
      * get category by slug
      * @Route("/category/{slug}", name="category")
      *
      * @param Category $category
      * @return Response
      */
    public function index(Category $category): Response
    {
        if (null == $category) {
            throw $this->createNotFoundException('The category does not exist');
        }

        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
}
