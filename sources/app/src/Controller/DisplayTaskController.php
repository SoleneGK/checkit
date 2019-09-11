<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Task;

/**
 * @IsGranted ("IS_AUTHENTICATED_REMEMBERED")
 */
class DisplayTaskController extends AbstractController
{
	/**
	 * @Route("/taches", name="display_tasks")
	 */
	public function display(string $order = "priorite")
	{
		return $this->redirectToRoute('display_tasks_by_priority');
	}

	/**
	 * @Route("/taches/priorite", name="display_tasks_by_priority")
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
				'display_mode' => 'normal',
				'sort_mode' => 'priority',
			]);
		}
		else
		{
			// filter active tasks
			$filtered_critical_tasks = $this->filterActiveTasks($critical_tasks);
			$filtered_important_tasks = $this->filterActiveTasks($important_tasks);
			$filtered_normal_tasks = $this->filterActiveTasks($normal_tasks);
			$filtered_optional_tasks = $this->filterActiveTasks($optional_tasks);

			return $this->render('display_task/priority.html.twig', [
				'display_mode' => 'normal',
				'sort_mode' => 'priority',
				'critical_tasks' => $filtered_critical_tasks,
				'important_tasks' => $filtered_important_tasks,
				'normal_tasks' => $filtered_normal_tasks,
				'optional_tasks' => $filtered_optional_tasks,
			]);
		}
	}

	/**
	 * @Route("/taches/periodicite", name="display_tasks_by_periodicity")
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

		if (count($critical_tasks) == 0 && count($important_tasks) == 0 && count($normal_tasks) == 0 && count($optional_tasks) == 0)
		{
			return $this->render('display_task/no_task.html.twig', [
				'display_mode' => 'normal',
				'sort_mode' => 'periodicity',
			]);
		}
		else
		{
			// filter active tasks
			$filtered_unique_tasks = $this->filterActiveTasks($unique_tasks);
			$filtered_daily_tasks = $this->filterActiveTasks($daily_tasks);
			$filtered_weekly_tasks = $this->filterActiveTasks($weekly_tasks);
			$filtered_monthly_tasks = $this->filterActiveTasks($monthly_tasks);

			return $this->render('display_task/periodicity.html.twig', [
				'display_mode' => 'normal',
				'sort_mode' => 'periodicity',
				'unique_tasks' => $filtered_unique_tasks,
				'daily_tasks' => $filtered_daily_tasks,
				'weekly_tasks' => $filtered_weekly_tasks,
				'monthly_tasks' => $filtered_monthly_tasks,
			]);
		}
	}

	private function filterActiveTasks($task_list)
	{
		$filtered_list = [];

		foreach ($task_list as $task)
		{
			if ($task->isActive() == true)
			{
				$filtered_list[] = $task;
			}
		}

		return $filtered_list;
	}
}
