<?php

class MercaderiaController extends Zend_Controller_Action
{

    public function init()
    {
      if(!Zend_Auth::getInstance()->hasIdentity())  
      {  
          $this->_redirect('/login/index');  
      }
    }

    public function indexAction() {
      $mercaderias = new Application_Model_DbTable_Mercaderia();
      $arrayDatos = $mercaderias->indexMiniMercaderia();
      foreach ($arrayDatos as $key => $value) {
          if (is_null($value)) {
               $arrayDatos[$key] = "";
          }
          $arrayDatos[$key]["acciones"] = 
          '<a href="'.
          $this->view->url(array('controller'=>'mercaderia','action'=>'edit', 'id'=>$arrayDatos[$key]['mer_id_mercaderia'])).
          '" class="btn btn-small">Editar</a>
          <a href="'.
          $this->view->url(array('controller'=>'mercaderia','action'=>'delete', 'id'=>$arrayDatos[$key]['mer_id_mercaderia'])).
          '" class="btn btn-small">Borrar</a>';
      }
      $this->view->mercaderiamini = $arrayDatos;
    }

    public function addAction() {
      $mercaderia = new Application_Model_DbTable_Mercaderia();
      $origen = $mercaderia->fetchAll(null, "mer_codigo ASC")->toArray();
      $origenArray=array();
      foreach ($origen as $ori) :
        $filaOrigen = explode(',',$ori['mer_codigo']);
        array_push( $origenArray , strtoupper( $filaOrigen[0] ) );
      endforeach;
      $this->view->origen = json_encode($origenArray) ;
      
      $form = new Application_Form_Mercaderia();
      $form->submit->setLabel('Agregar nueva Mercadería');
      $form->submit->setAttrib('class','btn btn-primary');
      $this->view->form = $form;
      
      if ($this->getRequest()->isPost()) {
          $formData = $this->getRequest()->getPost();
          if ($form->isValid($formData)) {
              $mer_codigo =                       $form->getValue('mer_codigo');
              $mer_nombre =                       $form->getValue('mer_nombre');
              $mer_descripcion =                  $form->getValue('mer_descripcion');
              $fcp_id_familia_codigo_proveedor =  $form->getValue('fcp_id_familia_codigo_proveedor');
              $tal_id_talla =                     $form->getValue('tal_id_talla');
              $tal_id_talla2 =                    $form->getValue('tal_id_talla2');
              $mer_costo =                        $form->getValue('mer_costo');
              $mer_tipo =                         $form->getValue('mer_tipo');
              $col_id_color =                     $form->getValue('col_id_color');
              $mer_modelo =                       $form->getValue('mer_modelo');
              $mer_articulo =                     $form->getValue('mer_articulo');
//              $mer_foto =                         $form->getValue('mer_foto');
              $mer_tamanno_tarea =                $form->getValue('mer_tamanno_tarea');
              $mer_prioridad_venta =              $form->getValue('mer_prioridad_venta');
              $mer_sexo =                         $form->getValue('mer_sexo');
              $mer_temporada =                    $form->getValue('mer_temporada');
              
              if (!$form->mer_foto->receive()) {
                  print "Error recibiendo el archivo";
              }
              $nombreFoto = $form->mer_foto->getValue();

              $mercaderias = new Application_Model_DbTable_Mercaderia();
              
              for($talla = $tal_id_talla; $talla <= $tal_id_talla2 ;$talla++){
                echo $talla;
                $mercaderias->addMercaderia($mer_codigo, $mer_nombre, $mer_descripcion, $fcp_id_familia_codigo_proveedor, 
                $talla, $mer_costo, $mer_tipo, $col_id_color, $mer_modelo, $mer_articulo, $nombreFoto,
                $mer_tamanno_tarea, $mer_prioridad_venta, $mer_sexo, $mer_temporada);
              }
              $this->_helper->redirector('index');
          } else {
              $form->populate($formData);
          }
      }
    }

    public function editAction() {
      $form = new Application_Form_Mercaderia();
      $form->submit->setLabel('Modificar Mercadería');
      $form->submit->setAttrib('class','btn btn-primary');

      $mercaderia = new Application_Model_DbTable_Mercaderia();
      $origen = $mercaderia->fetchAll(null, "mer_codigo ASC")->toArray();
      $origenArray=array();
      foreach ($origen as $ori) :
        $filaOrigen = explode(',',$ori['mer_codigo']);
        array_push( $origenArray , strtoupper( $filaOrigen[0] ) );
      endforeach;
      $this->view->origen = json_encode($origenArray) ;
      $this->view->form = $form;
      if ($this->getRequest()->isPost()) {
          $formData = $this->getRequest()->getPost();
          if ($form->isValid($formData)) {
              $mer_id_mercaderia =                $form->getValue('mer_id_mercaderia');
              $mer_codigo =                       $form->getValue('mer_codigo');
              $mer_nombre =                       $form->getValue('mer_nombre');
              $mer_descripcion =                  $form->getValue('mer_descripcion');
              $fcp_id_familia_codigo_proveedor =  $form->getValue('fcp_id_familia_codigo_proveedor');
              $tal_id_talla =                     $form->getValue('tal_id_talla');
              $mer_costo =                        $form->getValue('mer_costo');
              $mer_tipo =                         $form->getValue('mer_tipo');
              $col_id_color =                     $form->getValue('col_id_color');
              $mer_modelo =                       $form->getValue('mer_modelo');
              $mer_articulo =                     $form->getValue('mer_articulo');
              $mer_foto =                         $form->getValue('mer_foto');
              $mer_tamanno_tarea =                $form->getValue('mer_tamanno_tarea');
              $mer_prioridad_venta =              $form->getValue('mer_prioridad_venta');
              $mer_sexo =                         $form->getValue('mer_sexo');
              $mer_temporada =                    $form->getValue('mer_temporada');
              
              if (!$form->mer_foto->receive()) {
                  print "Error recibiendo el archivo";
              }
              $nombreFoto = $form->mer_foto->getValue();    
              $mercaderias = new Application_Model_DbTable_Mercaderia();
              $mercaderias->updateMercaderia($mer_id_mercaderia, $mer_codigo, $mer_nombre, $mer_descripcion, $fcp_id_familia_codigo_proveedor, 
                 $tal_id_talla, $mer_costo, $mer_tipo, $col_id_color, $mer_modelo, $mer_articulo, $nombreFoto,
                 $mer_tamanno_tarea, $mer_prioridad_venta, $mer_sexo, $mer_temporada);
              $this->_helper->redirector('index');
          } else {
              $form->populate($formData);
          }
      } else {    //Llena el formulario con los datos de la BD
        $id = $this->_getParam('id', 0);
        if( $id > 0 ){
            $mercaderia2 = new Application_Model_DbTable_Mercaderia();
            $filaMercaderia= $mercaderia2->getMercaderia($id);
            $form->populate( $filaMercaderia );
/*
            $perfilesUser = new Application_Model_DbTable_UsuarioHasPerfil();
            $pUser= $perfilesUser->perfilesUsuario($id)->toArray();
            $listaPerfilesUser=array();
            foreach ($pUser as $pU) :
              $filaPerfil = explode(',',$pU['per_id_perfil']);
              array_push( $listaPerfilesUser, $filaPerfil[0] );
            endforeach;
            $form->per_id_perfil->setValue( $listaPerfilesUser );
 */
        }
      }
    }
    
    public function deleteAction() {
      if ($this->getRequest()->isPost()) {
          $del = $this->getRequest()->getPost('del');
          if ($del == 'Si') {
              $id = $this->getRequest()->getPost('id');
              $mercaderia = new Application_Model_DbTable_Mercaderia();
              $mercaderia->deleteMercaderia($id);
          }
          $this->_helper->redirector('index');
      } else {
          $id = $this->_getParam('id', 0);          
          $mercaderia = new Application_Model_DbTable_Mercaderia();
          $this->view->mercaderia = $mercaderia->getMercaderia($id);
      }
    }
}







