<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CheckItController extends AbstractController
{  
    /**
    * @Route("/", name="index")
    */
    public function printLoginPage()
    {
        return $this->render('logout/login.html.twig');
    }
}
