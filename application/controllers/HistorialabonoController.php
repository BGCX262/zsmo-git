<?php

class HistorialabonoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
      $abono = new Application_Model_DbTable_Historialabono();
      $arrayDatos = $abono->indexHistorialabono();

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
          if ( $arrayDatos[$key]['abo_id_abono'] == null ) {
               $arrayDatos[$key]['abo_id_abono'] = "0";
          }
          if ( $arrayDatos[$key]['abo_tipo_movimiento'] == null ) {
               $arrayDatos[$key]['abo_tipo_movimiento'] = "Ninguno";
          }

          $arrayDatos[$key]["nombre_completo"] = $arrayDatos[$key]['cli_nombre'].' '.$arrayDatos[$key]['cli_apellido_1'].' '.$arrayDatos[$key]['cli_apellido_2'];
          
          $arrayDatos[$key]["acciones"] = 
          '<a href="'.
          $this->view->url(array('controller'=>'historialabono','action'=>'add', 'id'=>$arrayDatos[$key]['cli_id_cliente'])).
          '" class="btn btn-small btn-primary">Agregar abono</a>
          <a href="'.
          $this->view->url(array('controller'=>'historialabono','action'=>'verdetalle', 'id'=>$arrayDatos[$key]['cli_id_cliente'])).
          '" class="btn btn-small"><i class="icon-eye-open"></i>Ver Detalle</a>';
      }
      $this->view->abonosmini = $arrayDatos;
    }

    public function addAction()
    {
      $form = new Application_Form_Historialabono();
      $form->submit->setLabel('Agregar nuevo abono/cliente');
      $form->submit->setAttrib('class','btn btn-primary');

      $form->cli_id_cliente->setAttrib('readonly','readonly');
      $form->cli_nombre->setAttrib('readonly','readonly');
      $form->cli_rut->setAttrib('readonly','readonly');
      $form->cli_apellido_1->setAttrib('readonly','readonly');
      $form->cli_apellido_2->setAttrib('readonly','readonly');
      
      $this->view->form = $form;
      $fecha = date('Y/m/d H:i:s');
            
      if ($this->getRequest()->isPost()) {    //SI EL FORMULARIO ES ENVIADO, SE RECIBE ACA
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
            $id_cliente = $cliente->addCliente($clienteArr);
              
            $abonoArr = array(
              "abo_cantidad"        => $form->getValue('abo_cantidad'),
              "abo_fecha"           => $fecha,
              "abo_tipo_movimiento" => $form->getValue('abo_tipo_movimiento'),
              "cli_id_cliente"      => $id_cliente
                );

            $abono = new Application_Model_DbTable_Historialabono();
            $abono->addHistorialabono($abonoArr);

          } else {
              $form->populate($formData);
          }
      }
      else {    //AL RECIBIR LA ID DE LA FILA, Llena el formulario con los datos de la BD
        $id = $this->_getParam('id', 0);
        if( $id > 0 ){
            $cliente = new Application_Model_DbTable_Cliente();
            $filaCliente= $cliente->getCliente($id);
            $form->populate( $filaCliente );
        }
      }
    }

    public function verdetalleAction()
    {
        // action body
    }

    public function addnewAction()
    {
      $form = new Application_Form_Historialabono();
      $form->submit->setLabel('Agregar nuevo abono/cliente');
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
            $id_cliente = $cliente->addCliente($clienteArr);

            $fecha = date('Y/m/d H:i:s');
              
            $abonoArr = array(
              "abo_cantidad"        => $form->getValue('abo_cantidad'),
              "abo_fecha"           => $fecha,
              "abo_tipo_movimiento" => $form->getValue('abo_tipo_movimiento'),
              "cli_id_cliente"      => $id_cliente
                );

            $abono = new Application_Model_DbTable_Historialabono();
            $abono->addHistorialabono($abonoArr);

          } else {
              $form->populate($formData);
          }
      }
    }


}







