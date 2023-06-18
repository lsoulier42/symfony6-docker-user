<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Trait\Timestampable;

abstract class AbstractEntity
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    public function __construct()
    {
        $this->setTimes();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}