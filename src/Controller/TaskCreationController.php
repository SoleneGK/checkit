<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Task;
use App\Form\TaskFormType;

class TaskCreationController extends AbstractController
{
	/**
	 * @IsGranted ("IS_AUTHENTICATED_REMEMBERED")
	 * @Route("/taches/ajouter", name="task_creation")
	 */
	public function addTask(Request $request): Response
	{
		$task = new Task();
		$task->setStartDate(new \DateTime());
		$task->setOwner($this->getUser());
		$form = $this->createForm(TaskFormType::class, $task);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$task = $form->getData();

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($task);
			$entityManager->flush();

			return $this->render('task_creation_success.html.twig');
		}

		return $this->render('app/task_creation.html.twig', [
			'taskForm' =>$form->createView(),
		]);
	}
}