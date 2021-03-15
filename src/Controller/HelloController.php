<?php


namespace App\Controller;



use App\Taxes\Detector;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController extends AbstractController
{



    /**
     * @Route("/hello/{prenom?world}" , name="hello")
     */
    public function hello(){

        return $this->render('hello.html.twig',['prenom' =>'toto']);

    }

    /**
     * @Route("/example", name="example")
     */
    public function example( ){

        return $this->render('example.html.twig' , ['age' => 33]);
    }



}