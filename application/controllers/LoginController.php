<?php

class LoginController extends Zend_Controller_Action
{

  public function init()
  {
  }

  public function indexAction()
  {
    $form = new Application_Form_Login();
    $request = $this->getRequest();
    if ($request->isPost() )
    {
//      if ($form->isValid($this->_getAllParams()))
        if ($form->isValid($request->getPost()))
      {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter(); 
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $authAdapter
          ->setTableName('smo_usuario')
          ->setIdentityColumn('usu_rut')
          ->setCredentialColumn('usu_passwd')
          ->setCredentialTreatment('md5(CONCAT(?,usu_passwd_salt))');
      
      $authAdapter
          ->setIdentity($form->getValue('rut'))
          ->setCredential( $form->getValue('pass') );

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);

        if($result->isValid()){
          // get all info about this user from the login table  // ommit only the password, we don't need that  
          $userInfo = $authAdapter->getResultRowObject(null, 'password');  
  
          // the default storage is a session with namespace Zend_Auth  
          $authStorage = $auth->getStorage();  
          $authStorage->write($userInfo); 
          return $this->_helper->redirector->gotoSimple('index','index');
          //$this->_redirect('view/index/index');
        }
        else{
          $errorMessage = "Datos Incorrectos, intente de nuevo.";
        }
      }
    }
    $this->view->form = $form;
    $this->view->errorMessage = $errorMessage;
  }

  public function loginAction()
  {

  }

  public function logoutAction()
  {
    Zend_Auth::getInstance()->clearIdentity();
    return $this->_helper->redirector->gotoSimple('index','login');
  }  
}

?>
