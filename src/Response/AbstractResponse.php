<?php

namespace APP\Response;

use JsonSerializable;

abstract class AbstractResponse implements JsonSerializable
{
    /*protected const SUCCESS = true;

    public function send(): void
    {
        $data = ['success' => static::SUCCESS] + $this->payload();
       // header('Content-Type: application/json');
        echo json_encode($data, JSON_THROW_ON_ERROR);
    }*/

        abstract protected function payload(): array;

        public function jsonSerialize(): mixed
        {
            return
            [
                'success' => static::SUCCESS,
                ...$this->payload()
            ];
        }
}