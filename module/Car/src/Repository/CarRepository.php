<?php

namespace Car\Repository;

use Car\Model\Car;
use Car\Contract\CarRepositoryContract;

class CarRepository implements CarRepositoryContract
{
    const CACHE_KEY_PREFIX = 'car_';

    public function __construct(private StorageInterface $cache, private CarTable $carTable)
    {
    }

    public function getAll(): array
    {
        // TODO: implement get all method
        return [];
    }

    public function get(int $id): Car
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $id;

        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        $car = $this->carTable->getCar($id);

        $this->cache->setItem($cacheKey, $car);

        return $car;
    }
}
