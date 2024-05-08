<?php

namespace Car\Contract;

use Car\Model\Car;

interface CarRepositoryContract
{
    /* @return Car[] */
    public function getAll(): array;

    public function get(int $id): Car;
}
