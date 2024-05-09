<?php

namespace Car\Repository;

use Application\Service\LoggerService;
use Car\Model\Car;
use Car\Model\CarTable;
use Car\Contract\CarRepositoryContract;
use Laminas\Cache\Storage\StorageInterface;
use Laminas\Cache\Service\StorageAdapterFactoryInterface;
use Laminas\ServiceManager\ServiceManager;

class CarRepository implements CarRepositoryContract
{
    const CACHE_KEY_PREFIX = 'car_';

    private StorageInterface $cache;

    public function __construct(private CarTable $carTable, private StorageAdapterFactoryInterface $storageFactory, private LoggerService $logger, private ServiceManager $serviceManager)
    {
        $this->cache = $storageFactory->createFromArrayConfiguration($serviceManager->get('config')['redis-cache']);
    }

    public function fetchAll()
    {
        return $this->carTable->fetchAll();
    }

    public function getCar(int $id): Car
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $id;

        try {
            if ($this->cache->hasItem($cacheKey)) {
                return unserialize($this->cache->getItem($cacheKey));
            }
        } catch (\Exception $e) {
            $this->logger->error("Cache couldn't get item", [$cacheKey, $e->getMessage()]);
            return $this->carTable->getCar($id);
        }

        $car = $this->carTable->getCar($id);

        $this->cache->setItem($cacheKey, serialize($car));

        $this->logger->info("Car cache set", $car->getArrayCopy());

        return $car;
    }

    public function saveCar(Car $car): void
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $car->id;

        try {
            if ($this->cache->hasItem($cacheKey)) {
                $this->cache->removeItem($cacheKey);
                $this->logger->info("Car cache removed", $car->getArrayCopy());
            }
        } catch (\Exception $e) {
            $this->logger->error("Cache couldn't remove item", [$cacheKey, $e->getMessage()]);
        }

        $this->carTable->saveCar($car);
    }

    public function deleteCar(int $id): void
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $id;

        try {
            if ($this->cache->hasItem($cacheKey)) {
                $this->cache->removeItem($cacheKey);
                $this->logger->info("Car cache removed", $car->getArrayCopy());
            }
        } catch (\Exception $e) {
            $this->logger->error("Cache couldn't remove item", [$cacheKey, $e->getMessage()]);
        }

        $this->carTable->deleteCar($id);
    }
}
