<?php

class CajeroController extends Zend_Controller_Action
{

    public function init()
    {
      if(!Zend_Auth::getInstance()->hasIdentity()){  
        $this->_redirect('/login/index');  
      }else{
        $userInfo = Zend_Auth::getInstance()->getStorage()->read();
        $usuariohasperfiles = new Application_Model_DbTable_UsuarioHasPerfil();
        $usuariohaslocales  = new Application_Model_DbTable_UsuarioHasLocal();
        $this->view->usuariohasperfiles = $usuariohasperfiles->perfilesUsuario($userInfo->usu_id_usuario);
        $this->view->usuariohaslocales = $usuariohaslocales->localesUsuario($userInfo->usu_id_usuario);
        
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

    public function ventasAction()
    {

    }

    public function inventarioAction()
    {
        // action body
    }

    public function cierrecajaAction()
    {
        // action body
    }

    public function cambiosanulacionesAction()
    {
        // action body
    }

    public function abonosAction()
    {
        // action body
    }

    public function creditoAction()
    {
        // action body
    }

    public function menucajeroAction()
    {
        // action body
    }


}















