<?php

class CierrecajaController extends Zend_Controller_Action
{

  public function init()
  {
      /* Initialize action controller here */
  }

  public function indexAction()
  {
      // action body
    $cierrecaja = new Application_Model_DbTable_Cierrecaja();
    $this->view->cierrescaja = $cierrecaja->fetchAll();
  }

  public function addAction()
  {
    $form = new Application_Form_Cierrecaja();
    $this->view->form = $form;

    if ( $this->getRequest()->isPost() ) {
      $formData = $this->getRequest()->getPost();

      if ( $form->isValid($formData) ){
        $cierreCaja = new Application_Model_DbTable_Cierrecaja();
        $entregaVenta = new Application_Model_DbTable_Entregaventa();
        $local = new Application_Model_DbTable_Local();
        $localArr = $local->getLocal($formData['loc_nombre']);
        $id_local = $localArr[0]['loc_id_local'];
        $fecha = date('Y-m-d h:i:s');
        if($formData['cierrecaja_cb'] == '1'){
          //guardar datos de cierre caja
          $cierreCajaArr = array(
                'usu_id_usuario'              => $formData['usu_id_usuario'],
                'loc_id_local'                => $id_local,
                'hcj_fecha_creacion'          => $fecha,
                'hcj_fecha_contable_inicio'   => $formData['hcj_fecha_contable_inicio'],
                'hcj_fecha_contable_final'    => $formData['hcj_fecha_contable_final']
                );
          //var_dump($cierreCajaArr);
          $id_cierreCaja= $cierreCaja->addCierrecaja($cierreCajaArr);
        }
        else
        {
          $lastCierreCajaArr= $cierreCaja->getLastCierrecaja($id_local);
         
          //var_dump($lastCierreCajaArr);
          $id_cierreCaja = $lastCierreCajaArr[0]['hcj_id_cierre_caja'];
        }
        //guardar datos de entrega de ventas
        $entregaventaArr = array(
              'hcj_id_cierre_caja'          => $id_cierreCaja,
              'usu_id_usuario'              => $formData['usu_id_usuario'],
              'edv_monto_total'             => $formData['edv_monto_total'],
              'edv_fecha'                   => $fecha,
              'edv_monto_20mil'             => $formData['edv_monto_20mil'],
              'edv_monto_10mil'             => $formData['edv_monto_10mil'],
              'edv_monto_5mil'              => $formData['edv_monto_5mil'],
              'edv_monto_2mil'              => $formData['edv_monto_2mil'],
              'edv_monto_1mil'              => $formData['edv_monto_1mil'],
              'edv_monto_500'               => $formData['edv_monto_500'],
              'edv_monto_otros_documentos'  => $formData['edv_monto_otros_documentos'],
              'edv_monto_devoluciones'      => $formData['edv_monto_devoluciones']
              );
         //var_dump($entregaventasArr);
        $entregaVenta->addEntregaventa($entregaventaArr);
        
//      $returnUrl = $formData['returnUrl'];
//      if ($returnUrl != '') {
//        $this->_helper->getHelper('Redirector')->setGotoUrl($returnUrl);
//      }
      }else{
        $form->populate($formData);
      } 
    }
  }

}