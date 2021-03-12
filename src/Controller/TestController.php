<?php


namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

class TestController
{


    /**
     * @Route("/" , name="index")
     */
    public function index()
    {
        dd("Ca fonctionne");
    }

}