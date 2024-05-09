<?php

namespace Car\Contract;

use Car\Model\Car;

interface CarRepositoryContract
{
    /* @return Car[] */
    public function fetchAll();

    public function getCar(int $id): Car;

    public function saveCar(Car $car): void;

    public function deleteCar(int $id): void;
}
