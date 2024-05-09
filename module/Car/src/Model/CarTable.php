<?php

namespace Car\Model;

use Car\Contract\CarRepositoryContract;
use RuntimeException;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGatewayInterface;

class CarTable implements CarRepositoryContract
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(): ResultSet
    {
        return $this->tableGateway->select();
    }

    public function getCar($id): Car
    {
        $id = (int) $id;

        /** @var \Laminas\Db\ResultSetInterface $rowset */
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveCar(Car $car): void
    {
        $data = [
            'name' => $car->name,
            'model'  => $car->model,
        ];

        $id = (int) $car->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getCar($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update car with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteCar($id): void
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
