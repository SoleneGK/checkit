<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Task;
use App\Form\TaskFormType;

use Psr\Log\LoggerInterface;

/**
 * @IsGranted ("IS_AUTHENTICATED_REMEMBERED")
 */
class TaskController extends AbstractController
{
	/**
	 * @Route("/tache/ajouter", name="task_creation")
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

			return $this->render('task/task_creation_success.html.twig');
		}
		else
		{
			return $this->render('task/task_creation.html.twig', [
				'taskForm' => $form->createView(),
			]);
		}
	}
	
	/**
	 * @Route("/tache/{task_id}/modifier", name="task_modification")
	 */
	public function modifyTask(Request $request, int $task_id, LoggerInterface $logger): Response
	{
		$user_id = $this->getUser()->getId();

		$entityManager = $this->getDoctrine()->getManager();
		$original_task = $entityManager->getRepository(Task::class)->findWithUserVerification($task_id, $user_id);

		// Check that the task is owned by current user
		if (count($original_task) > 0)
		{
			$original_task = $original_task[0];
			$logger->info($original_task->getPriority()->getId());
			$displayed_task = new Task();
			$displayed_task->copy($original_task);

			$form = $this->createForm(TaskFormType::class);
			$form->handleRequest($request);

			if ($form->isSubmitted())
			{
				$displayed_task = $form->getData();

				if ($form->isValid())
				{
					$original_task->import($displayed_task);
					$entityManager->flush();

					return $this->redirectToRoute('index');
				}
				else
				{
					return $this->render('task/task_modification.html.twig', [
						'taskForm' => $form->createView(),
						'task' => $displayed_task,
					]);
				}
			}
			else
			{
				return $this->render('task/task_modification.html.twig', [
					'taskForm' => $form->createView(),
					'task' => $displayed_task,
				]);
			}
		}
		else
		{
			return $this->redirectToRoute('index');
		}
	}

	/**
	 * @Route("/tache/{task_id}", name="task_details")
	 */
	public function displayTaskDetails(Task $task, int $task_id)
	{
		$user = $this->getUser();

		if ($task->getOwner()->getId() == $user->getId()) {
			return $this->render('task/task_details.html.twig', [
				'task' => $task,
			]);    
		}
		else
		{
			return $this->redirectToRoute('index');
		}
	}
}
