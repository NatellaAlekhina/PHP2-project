<?php

namespace App\Traits;

use App\Date\DateTime;

trait CreatedAt
{
    private DateTime $createdAt;

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): ?self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


}