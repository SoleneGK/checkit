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
     * Copy the attributes of another task
     */
    public function copy(Task $original_task)
    {
        $this->id = $original_task->getId();
        $this->setTitle($original_task->getTitle());
        $this->setDescription($original_task->getDescription());
        $this->setStartDate($original_task->getStartDate());
        $this->setEndDate($original_task->getEndDate());
        $this->setOwner($original_task->getOwner());
        $this->setPriority($original_task->getPriority());
        $this->setPeriodicity($original_task->getPeriodicity());
    }
}
