<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $title;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $description;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $start_date;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $end_date;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $owner;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Priority")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $priority;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Periodicity")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $periodicity;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $last_execution_date;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;
	
		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): self
	{
		$this->description = $description;
	
		return $this;
	}

	public function getStartDate(): ?\DateTimeInterface
	{
		return $this->start_date;
	}

	public function setStartDate(\DateTimeInterface $start_date): self
	{
		$this->start_date = $start_date;
	
		return $this;
	}

	public function getEndDate(): ?\DateTimeInterface
	{
		return $this->end_date;
	}

	public function setEndDate(?\DateTimeInterface $end_date): self
	{
		$this->end_date = $end_date;
	
		return $this;
	}

	public function getOwner(): ?User
	{
		return $this->owner;
	}

	public function setOwner(?User $owner): self
	{
		$this->owner = $owner;
	
		return $this;
	}

	public function getPriority(): ?Priority
	{
		return $this->priority;
	}

	public function setPriority(?Priority $priority): self
	{
		$this->priority = $priority;
	
		return $this;
	}

	public function getPeriodicity(): ?Periodicity
	{
		return $this->periodicity;
	}

	public function setPeriodicity(?Periodicity $Periodicity): self
	{
		$this->periodicity = $Periodicity;
	
		return $this;
	}

	/**
	 * Copy all the attributes of another task
	 */
	public function copy(Task $original_task)
	{
		$this->import($original_task);
		$this->id = $original_task->getId();
		$this->setOwner($original_task->getOwner());
	}

	/**
	 * Import all modifiable attributes from another task
	 */
	public function import(Task $original_task)
	{
		$this->setTitle($original_task->getTitle());
		$this->setDescription($original_task->getDescription());
		$this->setStartDate($original_task->getStartDate());
		$this->setEndDate($original_task->getEndDate());
		$this->setPriority($original_task->getPriority());
		$this->setPeriodicity($original_task->getPeriodicity());
	}

	/**
	 * Determines if a task is active. It depends of the periodicity, the start date and the last execution dat
	 * If start_date < current_date
	 * 		If periodicity is unique (code = U)
	 * 			The task is active if last execution date is null
	 * 		If periodicity is daily, weekly or monthly (second character of periodicity code is 0)
	 *			The task is active if last execution date is null
	 *			or last execution date < midnight of the current day / week / month
	 * 		If periodicity is a specific day (second character of periodicity code is higher than 0)
	 *			The task is active if the specific day is the current day
	 *			and last execution date < midnight of the current day
	 */
	public function isActive(): bool
	{
		$periodicity = $this->getPeriodicity()->getCode();
		$is_active = false;
		$current_date = new \DateTime();

		if ($this->getStartDate() < $current_date)
		{
			if ($periodicity == "U")
			{
				if (is_null($this->getLastExecutionDate()) == true)
				{
					$is_active = true;
				}
			}
			else if ($periodicity[1] == '0')
			{
				if (is_null($this->getLastExecutionDate()) == true)
				{
					$is_active = true;
				}
				else
				{
					if ($periodicity[0] == 'D')
					{
						if ($this->getLastExecutionDate() < $this->getCurrentDayAtMidnight())
						{
							$is_active = true;
						}
					}
					else if ($periodicity[1] == 'W')
					{
						if ($this->getLastExecutionDate() < $this->getFirstDayOfCurrentWeek())
						{
							$is_active = true;
						}
					}
					else
					{
						if ($this->getLastExecutionDate() < $this->getFirstDayOfCurrentMonth())
						{
							$is_active = true;
						}
					}
				}
			}
			else
			{
				if ($this->periodicityInstantIsToday() == true)
				{
					$is_active = true;
				}
			}
		}

		return $is_active;
	}

	private function getCurrentDayAtMidnight()
	{
		$current_date = new DateTime();
		$current_date->setTime(0, 0, 0);

		return $current_date;
	}

	private function getFirstDayOfCurrentWeek()
	{
		$first_day_current_week = new DateTime();
		$first_day_current_week->setTime(0, 0, 0);

		$current_week_day = $first_day_current_week->format('w');
		
		if ($current_week_day == 0)
		{
			$days_to_substract = 6;
		}
		else
		{
			$days_to_substract = - ($current_week_day - 1);
		}

		$interval = new DateInterval('P' . $days_to_substract . 'D');
		$first_day_current_week->sub($interval);
		
		return $first_day_current_week;
	}

	private function getFirstDayOfCurrentMonth()
	{
		$first_day_current_month = new DateTime();
		$first_day_current_month->setTime(0, 0, 0);

		$current_month = $first_day_current_month->format('m');
		$current_year = $first_day_current_month->format('Y');

		$first_day_current_month->setDate(1, $current_month, $current_year);

		return $first_day_current_month;
	}

	/**
	 * If the periodicity is every specific day of the week or the month, tell if it is today
	 */
	private function periodicityInstantIsToday()
	{
		$is_today = false;
		$periodicity_code = $this->getPeriodicity()->getCode();
		$day_of_activation = substr($periodicity_code, 1);
		$today = new DateTime();

		if ($periodicity_code[0] == 'W')
		{
			$current_day_of_the_week = $today->format('w') + 1;

			if ($day_of_activation == $current_day_of_the_week)
			{
				$is_today = true;
			}
		}
		else
		{
			$current_day_of_the_month = $today->format('d');

			if ($day_of_activation == $current_day_of_the_month)
			{
				$is_today = true;
			}
		}

		return $is_today;
	}

	/**
	 * Validate the task by updating the last execution date
	 */
	public function validate()
	{
		$this->setLastExecutionDate(new \DateTime());
	}

	/**
	 * 
	 */

	public function toLog()
	{
		$log = "id : ";
		if (is_null($this->getId()))
		{
			$log .= "null";
		}
		else
		{
			$log .= $this->getId();
		}
		$log .= " / ";
	
		$log .= "title : ";
		if (is_null($this->getTitle()))
		{
			$log .= "null";
		}
		else
		{
			$log .= $this->getTitle();
		}
		$log .= " / ";
	
		$log .= "description : ";
		if (is_null($this->getDescription()))
		{
			$log .= "null";
		}
		else
		{
			$log .= $this->getDescription();
		}
		$log .= " / ";
		
		$log .= "startDate : ";
		if (is_null($this->getStartDate()))
		{
			$log .= "null";
		}
		else
		{
			$log .= $this->getStartDate()->format('d-m-Y');
		}
		$log .= " / ";
	
		$log .= "endDate : ";
		if (is_null($this->getEndDate()))
		{
			$log .= "null";
		}
		else
		{
			$log .= $this->getEndDate()->format('d-m-Y');
		}
		$log .= " / ";
	
		$log .= "owner : ";
		if (is_null($this->getOwner()))
		{
			$log .= "null";
		}
		else
		{
			$log .= $this->getOwner()->getId();
		}
		$log .= " / ";
	
		$log .= "priority : ";
		if (is_null($this->getPriority()))
		{
			$log .= "null";
		}
		else
		{
			$log .= $this->getPriority()->getName();
		}
		$log .= " / ";
	
		$log .= "periodicity : ";
		if (is_null($this->getPeriodicity()))
		{
			$log .= "null";
		}
		else
		{
			$log .= $this->getPeriodicity()->getName();
		}
	
		return $log;
	}

	public function getLastExecutionDate(): ?\DateTimeInterface
	{
		return $this->last_execution_date;
	}

	public function setLastExecutionDate(?\DateTimeInterface $last_execution_date): self
	{
		$this->last_execution_date = $last_execution_date;

		return $this;
	}
}
