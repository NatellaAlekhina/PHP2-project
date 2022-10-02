<?php

namespace App\Traits;

use App\Date\DateTime;

trait DeleteAt
{
    private ?DateTime $deletedAt;

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTime $deletedAt): ?self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function isDeleted(): bool
    {
        return !empty($this->deletedAt);
    }

}