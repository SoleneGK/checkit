<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Form\TaskCreationFormType;

class TaskCreationController extends AbstractController
{
	/**
	 * @Route("/taches/ajouter", name="task_creation")
	 */
	public function addTask()
	{
		$task = new Task();
		$form = $this->createForm(TaskCreationFormType::class, $task);


		return $this->render('app/task_creation.html.twig', [
			'taskCreationForm' =>$form->createView(),
		]);
	}

	// public function printTaskListByPriority() {
	// 	// Y a-t-il une demande de création de tâche ?
	// 	$data_send = true;

	// 	$data = [
	// 		'title' => "",
	// 		'description' => "",
	// 		'importance' => "",
	// 		'periodicity' => ""
	// 	];

	// 	if ($data_send == true) {
	// 		$data = [
	// 			'title' => "Prendre rdv chez le médecin",
	// 			'description' => "Apporter le carnet de santé",
	// 			'importance' => "critical",
	// 			'periodicity' => "none"
	// 		]; 

	// 		$creation_succeeded = true;

	// 		if ($creation_succeeded == true) {
	// 			return $this->render('logged/task_creation_succeeded.html.twig');
	// 		}
	// 		else {
	// 			return $this->render('logged/task_creation.html.twig', $data);
	// 		}
	// 	}
	// 	else {
	// 		return $this->render('logged/task_creation.html.twig', $data);
	// 	}
		
	// }
}