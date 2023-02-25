<?php

namespace App\Controller\Api\Admin;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/api/admin/tree/category', name: 'app_api_admin_tree_category')]
    public function index(EntityManagerInterface $em): Response
    {
        $food = new Category();
        $food->setTitle('Food');

        $fruits = new Category();
        $fruits->setTitle('Fruits');
        $fruits->setParent($food);

        $vegetables = new Category();
        $vegetables->setTitle('Vegetables');
        $vegetables->setParent($food);

        $carrots = new Category();
        $carrots->setTitle('Carrots');
        $carrots->setParent($vegetables);

        $em->persist($food);
        $em->persist($fruits);
        $em->persist($vegetables);
        $em->persist($carrots);
        $em->flush();
//        $repo = $em->getRepository(Category::class);
////
////        $food = $repo->findOneByTitle('Food');
////        $count = $repo->childCount($food);
//        $parent = $repo->findOneBy(['title' => 'Vegetables', 'parent' => 1]);
//        $potatoes = new Category();
//        $potatoes->setTitle('Potatoes');
//        $potatoes->setParent($parent);
//        $em->persist($potatoes);
//        $em->flush();
//        $count = $repo->childCount($parent);
//
//        dd($count);

//        dd($repo->findBy(['parent'=>null]));
//        $path = $repo->getPath($carrots);
//        $children = $repo->children($food);

        return $this->json($carrots,
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }


}
