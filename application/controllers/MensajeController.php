<?php

class MensajeController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $mensaje = new Application_Model_DbTable_Mensaje();
        
        $arrayDatos = $mensaje->getIndexMensaje();
        
      foreach ($arrayDatos as $key => $value) {
          if (is_null($value)) {
               $arrayDatos[$key] = "";
          }
          $arrayDatos[$key]["acciones"] = 
          '<a href="'.
          $this->view->url(array('controller'=>'mensaje','action'=>'edit', 'id'=>$arrayDatos[$key]['men_id_mensaje'])).
          '" class="btn btn-small">Editar</a>
          <a href="'.
          $this->view->url(array('controller'=>'mensaje','action'=>'delete', 'id'=>$arrayDatos[$key]['men_id_mensaje'])).
          '" class="btn btn-small">Borrar</a>';
      }
        
        $this->view->mensaje = $arrayDatos;
    }

    public function addAction()
    {
      $form = new Application_Form_Mensaje();
      $form->submit->setLabel('Agregar nuevo Mensaje');
      $form->submit->setAttrib('class','btn btn-primary');
      $this->view->form = $form;
      
      if ($this->getRequest()->isPost()) {
          $formData = $this->getRequest()->getPost();
          if ($form->isValid($formData)) {
              $men_fecha =           $form->getValue('men_fecha');
              $men_mensaje =         $form->getValue('men_mensaje');
              $usu_id_usuario =      $form->getValue('usu_id_usuario');

              $mensajes = new Application_Model_DbTable_Mensaje();
              $mensajes->addMensaje($men_fecha, $men_mensaje, $usu_id_usuario);
              $this->_helper->redirector('index');
          } else {
              $form->populate($formData);
          }
      }
      
    }

    public function editAction()
    {
      $form = new Application_Form_Mensaje();
      $form->submit->setLabel('Modificar Mensaje');
      $form->submit->setAttrib('class','btn btn-primary');
      $this->view->form = $form;
      
      if ($this->getRequest()->isPost()) {
          $formData = $this->getRequest()->getPost();
          if ($form->isValid($formData)) {
              $men_id_mensaje =       $form->getValue('men_id_mensaje');
              $men_fecha =           $form->getValue('men_fecha');
              $men_mensaje =          $form->getValue('men_mensaje');
              $usu_id_usuario =       $form->getValue('usu_id_usuario');

              $mensajes = new Application_Model_DbTable_Mensaje();
              $mensajes->updateMensaje($men_id_mensaje, $men_fecha, $men_mensaje, $usu_id_usuario);
              $this->_helper->redirector('index');
          } else {
              $form->populate($formData);
          }
      } else {    //Llena el formulario con los datos de la BD
        $id = $this->_getParam('id', 0);
        if( $id > 0 ){
            $filas = new Application_Model_DbTable_Mensaje();
            $filaDatos= $filas->getMensaje($id);
            $form->populate( $filaDatos );
        }
      }
    }

    public function deleteAction()
    {
      if ($this->getRequest()->isPost()) {
          $del = $this->getRequest()->getPost('del');
          if ($del == 'Si') {
              $id = $this->getRequest()->getPost('id');
              $mensaje = new Application_Model_DbTable_Mensaje();
              $mensaje->deleteMensaje($id);
          }
          $this->_helper->redirector('index');
      } else {
          $id = $this->_getParam('id', 0);          
          $mensaje = new Application_Model_DbTable_Mensaje();
          $this->view->mensaje = $mensaje->getMensaje($id);
      }
    }


}







