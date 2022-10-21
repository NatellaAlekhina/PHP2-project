<?php

namespace App\Traits;

use App\Date\DateTime;

trait UpdateAt
{
    private ?DateTime $updatedAt;


    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): ?self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

}

