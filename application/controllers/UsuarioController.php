<?php

class UsuarioController extends Zend_Controller_Action
{

    public function init() {
      if(!Zend_Auth::getInstance()->hasIdentity())  
      {  
          $this->_redirect('/login/index');  
      }
    }

    public function indexAction() {
      $usuarios = new Application_Model_DbTable_Usuarios();
      $arrayDatos = $usuarios->indexMiniUsuario();
      foreach ($arrayDatos as $key => $value) {
          if (is_null($value)) {
               $arrayDatos[$key] = "";
          }
          $arrayDatos[$key]["acciones"] = 
          '<a href="'.
          $this->view->url(array('controller'=>'usuario','action'=>'edit', 'id'=>$arrayDatos[$key]['usu_id_usuario'])).
          '" class="btn btn-small">Editar</a>
          <a href="'.
          $this->view->url(array('controller'=>'usuario','action'=>'delete', 'id'=>$arrayDatos[$key]['usu_id_usuario'])).
          '" class="btn btn-small">Borrar</a>';
      }
      $this->view->usuariosmini = $arrayDatos;
    }
    
    public function addAction() {
      $form = new Application_Form_Usuario();
      $form->submit->setLabel('Agregar nuevo usuario');
      $form->submit->setAttrib('class','btn btn-primary');
      $this->view->form = $form;
      if ($this->getRequest()->isPost()) {
          $formData = $this->getRequest()->getPost();
          if ($form->isValid($formData)) {
              $nombre =     $form->getValue('usu_nombre');
              $rut =        $form->getValue('usu_rut');
              $apellido_1 = $form->getValue('usu_apellido_1');
              $apellido_2 = $form->getValue('usu_apellido_2');
              $fono_1 =     $form->getValue('usu_fono_1');
              $fono_2 =     $form->getValue('usu_fono_2');
              $direccion =  $form->getValue('usu_direccion');
              $ciudad =     $form->getValue('usu_ciudad');
              $password =   $form->getValue('usu_password');
              $perfil =     $form->getValue('per_id_perfil');
              $comision =   $form->getValue('usu_porcentaje_comision');
              
              $usuarios = new Application_Model_DbTable_Usuarios();
              $usuarios->addUsuario($rut, $nombre, $apellido_1, $apellido_2,
            $fono_1, $fono_2, $direccion, $ciudad, $password, $comision);

              $idUsuario = $usuarios->getUsuario2($rut);
              
              $uhp = new Application_Model_DbTable_UsuarioHasPerfil();
              foreach ($perfil as $d) :
                $uhp->addUhp($d, $idUsuario['usu_id_usuario']);
              endforeach;
              
              $this->_helper->redirector('index');
          } else {
              $form->populate($formData);
          }
      }
    }

    public function editAction(){
      $form = new Application_Form_Usuario();
      $form->submit->setLabel('Modificar usuario');
      $form->submit->setAttrib('class','btn btn-primary');
      $this->view->form = $form;
      if ( $this->getRequest()->isPost() ){
          $formData = $this->getRequest()->getPost();
          if ( $form->isValid($formData) ){
              $id =         $form->getValue('usu_id_usuario');
              $nombre =     $form->getValue('usu_nombre');
              $rut =        $form->getValue('usu_rut');
              $apellido_1 = $form->getValue('usu_apellido_1');
              $apellido_2 = $form->getValue('usu_apellido_2');
              $fono_1 =     $form->getValue('usu_fono_1');
              $fono_2 =     $form->getValue('usu_fono_2');
              $direccion =  $form->getValue('usu_direccion');
              $ciudad =     $form->getValue('usu_ciudad');
              $password =   $form->getValue('usu_password');
              $perfil =     $form->getValue('per_id_perfil');
              $comision =   $form->getValue('usu_porcentaje_comision');

              $usuarios = new Application_Model_DbTable_Usuarios();
              $usuarios->updateUsuario($id, $rut, $nombre, $apellido_1, $apellido_2,
            $fono_1, $fono_2, $direccion, $ciudad, $password, $comision);

              $uhp = new Application_Model_DbTable_UsuarioHasPerfil();
              $uhp->deleteUhp($id);   //se eliminan los perfiles asociados y se agregan de nuevo
              foreach ($perfil as $idPerfil) :
                $uhp->addUhp( $idPerfil, $id );
              endforeach;
              
              $this->_helper->redirector('index');
          } else {
              $form->populate($formData);
          }
      } else {    //Llena el formulario con los datos de la BD
        $id = $this->_getParam('id', 0);
        if( $id > 0 ){
            $user = new Application_Model_DbTable_Usuarios();
            $filaUser= $user->getUsuario($id);
            $form->populate( $filaUser );

            $perfilesUser = new Application_Model_DbTable_UsuarioHasPerfil();
            $pUser= $perfilesUser->perfilesUsuario($id)->toArray();
            $listaPerfilesUser=array();
            foreach ($pUser as $pU) :
              $filaPerfil = explode(',',$pU['per_id_perfil']);
              array_push( $listaPerfilesUser, $filaPerfil[0] );
            endforeach;
            $form->per_id_perfil->setValue( $listaPerfilesUser );
        }
      }
    }

    public function deleteAction()
    {
      if ($this->getRequest()->isPost()) {
          $del = $this->getRequest()->getPost('del');
          if ($del == 'Si') {
              $id = $this->getRequest()->getPost('id');
              $perfil = new Application_Model_DbTable_UsuarioHasPerfil();
              $perfil->deleteUhp($id);
              $usuario = new Application_Model_DbTable_Usuarios();
              $usuario->deleteUsuario($id);

          }
          $this->_helper->redirector('index');
      } else {
          $id = $this->_getParam('id', 0);
          $usuarios = new Application_Model_DbTable_Usuarios();
          $this->view->usuarios = $usuarios->getUsuario($id);
      }
    }
    

    public function indexajaxAction()
    {
      //indica que esta accion no usará layout.phtml como plantilla del sistema
      $this->_helper->layout->disableLayout();
      //indica que la accion no usará la vista .phtml
      $this->_helper->viewRenderer->setNoRender();

      $usuarios = new Application_Model_DbTable_Usuarios();
      $rows = $usuarios->fetchAll()->toArray();
 //funcion de zend framewrok que me codifica el listado para formato Json
      $json = Zend_Json::encode($rows);
      echo $json;
      //echo "probando";
    }
}









