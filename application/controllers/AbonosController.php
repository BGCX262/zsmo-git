<?php

class AbonosController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    $form = new Application_Form_Venta();
    $this->view->form = $form;
    }


}

