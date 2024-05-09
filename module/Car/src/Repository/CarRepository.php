<?php

namespace Car\Repository;

use Car\Model\Car;
use Car\Model\CarTable;
use Car\Contract\CarRepositoryContract;
use Laminas\Cache\Storage\StorageInterface;
use Laminas\Cache\Service\StorageAdapterFactoryInterface;

class CarRepository implements CarRepositoryContract
{
    const CACHE_KEY_PREFIX = 'car_';

    private StorageInterface $cache;

    public function __construct(private CarTable $carTable, private StorageAdapterFactoryInterface $storageFactory)
    {
        // TODO: Refactor this to env variables
        $this->cache = $storageFactory->createFromArrayConfiguration([
            'adapter' => 'redis',
            'options' => [
                'server' => [
                    'host' => '127.0.0.1',
                    'port' => 6379,
                ],
                'ttl' => 3600,
            ],
        ]);
    }

    public function fetchAll()
    {
        return $this->carTable->fetchAll();
    }

    public function getCar(int $id): Car
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $id;

        if ($this->cache->hasItem($cacheKey)) {
            return unserialize($this->cache->getItem($cacheKey));
        }

        $car = $this->carTable->getCar($id);

        $this->cache->setItem($cacheKey, serialize($car));

        return $car;
    }

    public function saveCar(Car $car): void
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $car->id;

        if ($this->cache->hasItem($cacheKey)) {
            $this->cache->removeItem($cacheKey);
        }

        $this->carTable->saveCar($car);
    }

    public function deleteCar(int $id): void
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $id;

        if ($this->cache->hasItem($cacheKey)) {
            $this->cache->removeItem($cacheKey);
        }

        $this->carTable->deleteCar($id);
    }
}
