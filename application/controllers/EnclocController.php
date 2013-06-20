<?php

class EnclocController extends Zend_Controller_Action
{

    public function init()
    {
      if(!Zend_Auth::getInstance()->hasIdentity())
          {  
              $this->_redirect('/login/index');  
          }
      else{
        $userInfo = Zend_Auth::getInstance()->getStorage()->read();

        $usuariohasperfiles = new Application_Model_DbTable_UsuarioHasPerfil();
        $this->view->usuariohasperfiles = $usuariohasperfiles->perfilesUsuario($userInfo->usu_id_usuario);
        
        $this->view->id = $userInfo->usu_id_usuario;
        $this->view->rut = $userInfo->usu_rut;
        $this->view->nombre = $userInfo->usu_nombre;
        $this->view->apellido_1 = $userInfo->usu_apellido_1;
        $this->view->apellido_2 = $userInfo->usu_apellido_2;
      }
    }

    public function indexAction()
    {
        // action body
    }

    public function inventariobodegaAction()
    {
        // action body
    }

    public function transaccionesAction()
    {
        // action body
    }


}





