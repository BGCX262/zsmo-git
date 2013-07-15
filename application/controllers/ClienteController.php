<?php

class ClienteController extends Zend_Controller_Action
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
      $clientes = new Application_Model_DbTable_Cliente();
      $arrayDatos = $clientes->indexMiniCliente();

      foreach ($arrayDatos as $key => $value) {
          if ($arrayDatos[$key]['cli_nombre'] == null ) {
               $arrayDatos[$key]['cli_nombre'] = "-";
          }
          if ($arrayDatos[$key]['cli_apellido_1'] == null ) {
               $arrayDatos[$key]['cli_apellido_1'] = "-";
          }
          if ( $arrayDatos[$key]['cli_apellido_2'] == null ) {
               $arrayDatos[$key]['cli_apellido_2'] = "-";
          }
          if ( $arrayDatos[$key]['cli_rut'] == null ) {
               $arrayDatos[$key]['cli_rut'] = "0";
          }
          
          $arrayDatos[$key]["acciones"] = 
          '<a href="'.
          $this->view->url(array('controller'=>'cliente','action'=>'edit', 'id'=>$arrayDatos[$key]['cli_id_cliente'])).
          '" class="btn btn-small">Editar</a>
          <a href="'.
          $this->view->url(array('controller'=>'cliente','action'=>'delete', 'id'=>$arrayDatos[$key]['cli_id_cliente'])).
          '" class="btn btn-small">Borrar</a>';
      }
      $this->view->clientesmini = $arrayDatos;
    }

    public function addAction()
    {
      $form = new Application_Form_Cliente();
      $form->submit->setLabel('Agregar nuevo cliente');
      $form->submit->setAttrib('class','btn btn-primary');
      $this->view->form = $form;
      if ($this->getRequest()->isPost()) {
          $formData = $this->getRequest()->getPost();
          if ($form->isValid($formData)) {
            
            $clienteArr = array(
              "cli_nombre"          => $form->getValue('cli_nombre'),
              "cli_rut"             => $form->getValue('cli_rut'),
              'cli_apellido_1'      => $form->getValue('cli_apellido_1'),
              'cli_apellido_2'      => $form->getValue('cli_apellido_2'),
              'cli_fono_1'          => $form->getValue('cli_fono_1'),
              'cli_fono_2'          => $form->getValue('cli_fono_2'),
              'cli_direccion'       => $form->getValue('cli_direccion'),
              'cli_lugar_de_trabajo'=> $form->getValue('cli_lugar_de_trabajo'),
              'cli_ciudad'          => $form->getValue('cli_ciudad') );
              
              $cliente = new Application_Model_DbTable_Cliente();
              $cliente->addCliente($clienteArr);
             
          } else {
              $form->populate($formData);
          }
      }
    }

    public function editAction()
    {
      $form = new Application_Form_Cliente();
      $form->submit->setLabel('Modificar cliente');
      $form->submit->setAttrib('class','btn btn-primary');
      $this->view->form = $form;
      if ( $this->getRequest()->isPost() ){
          $formData = $this->getRequest()->getPost();
          if ( $form->isValid($formData) ){
            $clienteArr = array(
              "cli_id_cliente"      => $form->getValue('cli_id_cliente'),
              "cli_nombre"          => $form->getValue('cli_nombre'),
              "cli_rut"             => $form->getValue('cli_rut'),
              'cli_apellido_1'      => $form->getValue('cli_apellido_1'),
              'cli_apellido_2'      => $form->getValue('cli_apellido_2'),
              'cli_fono_1'          => $form->getValue('cli_fono_1'),
              'cli_fono_2'          => $form->getValue('cli_fono_2'),
              'cli_direccion'       => $form->getValue('cli_direccion'),
              'cli_lugar_de_trabajo'=> $form->getValue('cli_lugar_de_trabajo'),
              'cli_ciudad'          => $form->getValue('cli_ciudad') );
              
              $cliente = new Application_Model_DbTable_Cliente();
              $cliente->updateCliente($clienteArr);

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
            $cliente = new Application_Model_DbTable_Cliente();
            $filaCliente= $cliente->getCliente($id);
            $form->populate( $filaCliente );
        }
      }
    }

    public function deleteAction()
    {
      $id_get = $this->getRequest()->getParam('id');
      $this->view->id_cliente_get = $id_get;
      
      $cliente = new Application_Model_DbTable_Cliente();
      $clienteArr = $cliente->getCliente($id_get);
      $this->view->data = $clienteArr;
      
      if ($this->getRequest()->isPost()) {
        $formData = $this->getRequest()->getPost();
        if ($formData['eliminar'] != null && $formData['eliminar'] == 'Eliminar') { // SI SE CONFIRMA LA ELIMINACION

          $cliente->deleteCliente( $formData['id_cliente'] );
        }else{ //CANCELAR ELIMINACION
          
        }
        
      }
    }



}









