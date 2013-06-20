<?php

class UsuarioHasPerfilController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
      $usuariohasperfiles = new Application_Model_DbTable_UsuarioHasPerfil();
      $this->view->usuariohasperfiles = $usuariohasperfiles->perfilesUsuario("10");
    }

}

