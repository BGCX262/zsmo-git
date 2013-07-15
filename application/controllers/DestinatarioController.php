<?php

class DestinatarioController extends Zend_Controller_Action
{

    public function init()
    {
      if(!Zend_Auth::getInstance()->hasIdentity())  
      {
          $this->_redirect('/login/index');  
      }
    }

    public function indexAction()
    {
      $destinatario = new Application_Model_DbTable_Destinatario();
      $arrayDatos = $destinatario->indexMiniDestinatario();

      foreach ($arrayDatos as $key => $value) {
          if ($arrayDatos[$key]['des_nombre'] == null ) {
               $arrayDatos[$key]['des_nombre'] = "-";
          }
          if ($arrayDatos[$key]['des_ciudad'] == null ) {
               $arrayDatos[$key]['des_ciudad'] = "-";
          }
          if ( $arrayDatos[$key]['des_tipo'] == null ) {
               $arrayDatos[$key]['des_tipo'] = "-";
          }
          if ( $arrayDatos[$key]['des_telefono'] == null ) {
               $arrayDatos[$key]['des_telefono'] = "-";
          }
          if ( $arrayDatos[$key]['des_rut'] == null ) {
               $arrayDatos[$key]['des_rut'] = "0";
          }
          
          $arrayDatos[$key]["acciones"] = 
          '<a href="'.
          $this->view->url(array('controller'=>'destinatario','action'=>'edit', 'id'=>$arrayDatos[$key]['des_id_destinatario'])).
          '" class="btn btn-small">Editar</a>
          <a href="'.
          $this->view->url(array('controller'=>'destinatario','action'=>'delete', 'id'=>$arrayDatos[$key]['des_id_destinatario'])).
          '" class="btn btn-small">Borrar</a>';
      }
      $this->view->destinatariosmini = $arrayDatos;
    }

    public function addAction()
    {
      $form = new Application_Form_Destinatario();
      $form->submit->setLabel('Agregar nuevo destinatario');
      $form->submit->setAttrib('class','btn btn-primary');
      $this->view->form = $form;
      if ($this->getRequest()->isPost()) {
          $formData = $this->getRequest()->getPost();
          if ($form->isValid($formData)) {
            
            $destinatarioArr = array(
              "des_nombre"          => $form->getValue('des_nombre'),
              "des_rut"             => $form->getValue('des_rut'),
              'des_direccion'       => $form->getValue('des_direccion'),
              'des_ciudad'          => $form->getValue('des_ciudad'),
              'des_telefono'        => $form->getValue('des_telefono'),
              'des_tipo'            => $form->getValue('des_tipo'),
              'des_comuna'          => $form->getValue('des_comuna'),
              'des_region'          => $form->getValue('des_region'),
              'des_contacto'        => $form->getValue('des_contacto')
              );
              
              $destinatario = new Application_Model_DbTable_Destinatario();
              $destinatario->addDestinatario($destinatarioArr);
             
              //FINALIZADO
              $form->submit->setAttrib('class','btn disabled');
              echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cambio realizado.</div>';
          } else {
              $form->populate($formData);
              echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error.</div>';
          }
      }
    }

    public function editAction()
    {
      $form = new Application_Form_Destinatario();
      $form->submit->setLabel('Modificar destinatario');
      $form->submit->setAttrib('class','btn btn-primary');
      $this->view->form = $form;
      if ( $this->getRequest()->isPost() ){
          $formData = $this->getRequest()->getPost();
          if ( $form->isValid($formData) ){
            $destinatarioArr = array(
              "des_id_destinatario" => $form->getValue('des_id_destinatario'),
              "des_nombre"          => $form->getValue('des_nombre'),
              "des_rut"             => $form->getValue('des_rut'),
              'des_direccion'       => $form->getValue('des_direccion'),
              'des_ciudad'          => $form->getValue('des_ciudad'),
              'des_telefono'        => $form->getValue('des_telefono'),
              'des_tipo'            => $form->getValue('des_tipo'),
              'des_comuna'          => $form->getValue('des_comuna'),
              'des_region'          => $form->getValue('des_region'),
              'des_contacto'        => $form->getValue('des_contacto')
            );
              
              $destinatario = new Application_Model_DbTable_Destinatario();
              $destinatario->updateDestinatario($destinatarioArr);

              //FINALIZADO
              $form->submit->setAttrib('class','btn disabled');
              echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cambio realizado.</div>';
          } else {
              $form->populate($formData);
              echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error.</div>';
          }
      } else {    //Llena el formulario con los datos de la BD
        $id = $this->_getParam('id', 0);
        if( $id > 0 ){
            $destinatario = new Application_Model_DbTable_Destinatario();
            $filaDestinatario= $destinatario->getDestinatario($id);
            $form->populate( $filaDestinatario );
        }
      }
    }
    
    public function deleteAction()    //DESACTIVADO = SI
    {
      $id_get = $this->getRequest()->getParam('id');
      $this->view->id_destinatario_get = $id_get;
      
      $destinatario = new Application_Model_DbTable_Destinatario();
      $destinatarioArr = $destinatario->getDestinatario($id_get);
      $this->view->data = $destinatarioArr;
      
      if ($this->getRequest()->isPost()) {
        $formData = $this->getRequest()->getPost();
        if ($formData['eliminar'] != null && $formData['eliminar'] == 'Eliminar') { // SI SE CONFIRMA LA ELIMINACION

          $destinatario->deleteDestinatario( $formData['id_destinatario'] );
        }else{ //CANCELAR ELIMINACION
          
        }
        
      }
    }

}







