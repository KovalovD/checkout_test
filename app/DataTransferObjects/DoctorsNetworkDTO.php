<?php

namespace App\DataTransferObjects;

readonly class DoctorsNetworkDTO
{
    public function __construct(
        private string $specializationName,
        private ?int $minYoe,
        private ?int $maxYoe,
    ) {

    }

    public function getSpecializationName(): string
    {
        return $this->specializationName;
    }

    public function getMinYoe(): ?int
    {
        return $this->minYoe;
    }

    public function getMaxYoe(): ?int
    {
        return $this->maxYoe;
    }
}
