<?php

namespace Car\Model;

use Car\Model\Car;
use Car\Model\CarTable;
use Laminas\Db\ResultSet\ResultSet;
use Psr\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CarTableFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CarTable
    {

        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Car());
        $tableGateway = new TableGateway('car', $dbAdapter, null, $resultSetPrototype);
        return new CarTable($tableGateway);
    }
}
