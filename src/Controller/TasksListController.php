<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TasksListController extends AbstractController
{
    /**
     * @Route("/taches/priorite", name="task_list")
     */
    public function printTaskListByPriority() {
        return $this->render('logged/task_list_by_priority.html.twig');
    }
}