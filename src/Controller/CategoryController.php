<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{





    /**
     * @Route("/admin/category/create", name="category_create")
     */
    public function create( Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setSlug(strtolower($slugger->slug($category->getName())));


            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('homepage');


        }
        $formView = $form->createView();
        return $this->render('category/create.html.twig', [

            'formView' => $formView
        ]);
    }

    /**
     * @Route("/admin/category/{id}/edit", name="category_edit")
     *
     */
    public function edit($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em , Security $security)
    {

        $category = $categoryRepository->find($id);

        if(!$category) {
            throw new NotFoundHttpException("Cette cat??gorie n'existe pas");
        }


        $form = $this->createForm(CategoryType::class, $category);




        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();


            return $this->redirectToRoute('homepage');
        }

            $formView = $form->createView();


            return $this->render('category/edit.html.twig', [
                'category' => $category,
                'formView' => $formView
            ]);
        }

}
