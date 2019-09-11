<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Task;

/**
 * @IsGranted ("IS_AUTHENTICATED_REMEMBERED")
 */
class EditionModeController extends AbstractController
{
	/**
	 * @Route("/edition", name="edition_mode")
	 */
	public function display()
	{
		return $this->redirectToRoute('edition_mode_by_priority');
	}

	/**
	 * @Route("/edition/priorite", name="edition_mode_by_priority")
	 */
	public function displayTasksByPriority()
	{
		$user_id = $this->getUser()->getId();
		$entityManager = $this->getDoctrine()->getManager();
		$task_repo = $entityManager->getRepository(Task::class);

		// get tasks lists
		$critical_tasks = $task_repo->findAllByPriorityAndOwner(4, $user_id);
		$important_tasks = $task_repo->findAllByPriorityAndOwner(3, $user_id);
		$normal_tasks = $task_repo->findAllByPriorityAndOwner(2, $user_id);
		$optional_tasks = $task_repo->findAllByPriorityAndOwner(1, $user_id);

		if (count($critical_tasks) == 0 && count($important_tasks) == 0 && count($normal_tasks) == 0 && count($optional_tasks) == 0)
		{
			return $this->render('display_task/no_task.html.twig', [
				'display_mode' => 'edition',
				'sort_mode' => 'priority',
			]);
		}
		else
		{
			// check if tasks are active
			$this->isActive($critical_tasks);
			$this->isActive($important_tasks);
			$this->isActive($normal_tasks);
			$this->isActive($optional_tasks);

			return $this->render('edition_mode/priority.html.twig', [
				'display_mode' => 'edition',
				'sort_mode' => 'priority',
				'critical_tasks' => $critical_tasks,
				'important_tasks' => $important_tasks,
				'normal_tasks' => $normal_tasks,
				'optional_tasks' => $optional_tasks,
			]);
		}
	}

	/**
	 * @Route("/edition/periodicite", name="edition_mode_by_periodicity")
	 */
	public function displayTasksByPeriodicity()
	{
		$user_id = $this->getUser()->getId();
		$entityManager = $this->getDoctrine()->getManager();
		$task_repo = $entityManager->getRepository(Task::class);

		// get tasks lists
		$unique_tasks = $task_repo->findAllByPeriodicityAndOwner("U", $user_id);
		$daily_tasks = $task_repo->findAllByPeriodicityAndOwner("D0", $user_id);
		$weekly_tasks = $task_repo->findAllByPeriodicityAndOwner("W%", $user_id);
		$monthly_tasks = $task_repo->findAllByPeriodicityAndOwner("M%", $user_id);

		if (count($unique_tasks) == 0 && count($daily_tasks) == 0 && count($weekly_tasks) == 0 && count($monthly_tasks) == 0)
		{
			return $this->render('display_task/no_task.html.twig', [
				'display_mode' => 'edition',
				'sort_mode' => 'periodicity',
			]);
		}
		else
		{
			// check if tasks are active
			$this->isActive($unique_tasks);
			$this->isActive($daily_tasks);
			$this->isActive($weekly_tasks);
			$this->isActive($monthly_tasks);

			return $this->render('edition_mode/periodicity.html.twig', [
				'display_mode' => 'edition',
				'sort_mode' => 'periodicity',
				'unique_tasks' => $unique_tasks,
				'daily_tasks' => $daily_tasks,
				'weekly_tasks' => $weekly_tasks,
				'monthly_tasks' => $monthly_tasks,
			]);
		}
	}

	/**
	 * Set the property active to the correct value
	 */
	private function isActive($tasks_list) {
		foreach ($tasks_list as $task ) {
			$task->isActive();
		}
	}
}
