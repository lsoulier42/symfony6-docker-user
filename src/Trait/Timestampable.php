<?php

namespace App\Trait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

trait Timestampable
{
    #[Column(options: ["default" => 'CURRENT_TIMESTAMP'])]
    protected ?DateTimeImmutable $createdAt = null;

    #[Column(options: ["default" => 'CURRENT_TIMESTAMP'])]
    protected ?DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[PrePersist]
    #[PreUpdate]
    public function setTimes(): self
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTimeImmutable());
        }
        $this->setUpdatedAt(new DateTimeImmutable());
        return $this;
    }
}