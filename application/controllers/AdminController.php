<?php

class AdminController extends Zend_Controller_Action
{
    public function init(){
      if(!Zend_Auth::getInstance()->hasIdentity()){  
              $this->_redirect('/login/index');  
      }else{
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
    }

    public function transaccionesAction(){
      $form = new Application_Form_Filtrotransaccion();
      
      $destinatarios = new Application_Model_DbTable_Destinatario();
      $origen = $destinatarios->fetchAll(null, "des_nombre ASC")->toArray();
      $origenArray=array();
      foreach ($origen as $ori) :
        $filaOrigen = explode(',',$ori['des_nombre']);
        array_push( $origenArray , strtoupper( $filaOrigen[0] ) );
      endforeach;
      $this->view->origen = json_encode($origenArray) ;
      
      $this->view->form = $form;
    }
    
    public function cierrescajaAction()
    {
    }
    
    public function mantenedoresAction()
    {
    }

    public function basedatosAction()
    {
    }
}