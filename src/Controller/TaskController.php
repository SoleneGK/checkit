<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Task;
use App\Form\TaskFormType;

/**
 * @IsGranted ("IS_AUTHENTICATED_REMEMBERED")
 */
class TaskController extends AbstractController
{
	/**
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

			return $this->render('task/task_creation_success.html.twig');
		}

		return $this->render('task/task_creation.html.twig', [
			'taskForm' =>$form->createView(),
		]);
    }
    
    /**
     * @Route("/taches/{id}/modifier", name="task_modification")
     */
    public function modifyTask(Request $request): Response
    {
        $task = new Task();

    }

    /**
     * @Route("/taches/{id}", name="task_details")
     */
    public function displayTaskDetails(Task $task)
    {
        $user = $this->getUser();

        if ($task->getOwner()->getId() != $user->getId()) {
            return $this->redirectToRoute('index');
        }

        return $this->render('task/task_details.html.twig', [
            'task' => $task,
        ]);
    }
}
