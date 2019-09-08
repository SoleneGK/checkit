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
}
