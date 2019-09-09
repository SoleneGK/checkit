<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted ("IS_AUTHENTICATED_REMEMBERED")
 */
class DisplayTaskController extends AbstractController
{
    /**
     * @Route("/taches/priorite", name="display_task_by_priority")
     * @Route("/taches)
     */
    public function index()
    {
        // Faire 4 requêtes pour récupérer les tâches

        return $this->render('display_task/index.html.twig', [
            'controller_name' => 'DisplayTaskController',
        ]);
    }
}
