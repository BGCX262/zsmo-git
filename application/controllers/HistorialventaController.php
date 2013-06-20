<?php

class HistorialventaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
      $historialventa = new Application_Model_DbTable_Historialventa();
      $this->view->historialventas = $historialventa->fetchAll();
    }


}

