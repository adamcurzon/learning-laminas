<?php

namespace Car\Controller;

use Car\Model\CarTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Car\Form\CarForm;
use Car\Model\Car;
use Car\Event\CarCreatedEvent;

class CarController extends AbstractActionController
{
    // Add this property:
    private $table;

    // Add this constructor:
    public function __construct(CarTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'cars' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new CarForm();
        $form->get('submit')->setValue('Add');

        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $car = new Car();
        $form->setInputFilter($car->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $car->exchangeArray($form->getData());

        $car->setNamePrefixed();

        $this->table->saveCar($car);

        $this->getEventManager()->triggerEvent(new CarCreatedEvent($car));

        return $this->redirect()->toRoute('car');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('car', ['action' => 'add']);
        }

        try {
            $car = $this->table->getCar($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('car', ['action' => 'index']);
        }

        $form = new CarForm();
        $form->bind($car);
        $form->get('submit')->setAttribute('value', 'Edit');

        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($car->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->saveCar($car);
        } catch (\Exception $e) {
        }

        // Redirect to album list
        return $this->redirect()->toRoute('car', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('car');
        }

        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteCar($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('car');
        }

        return [
            'id'    => $id,
            'car' => $this->table->getCar($id),
        ];
    }
}
