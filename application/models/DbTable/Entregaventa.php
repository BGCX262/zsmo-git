<?php

class Application_Model_DbTable_Entregaventa extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_entrega_de_venta';

  public function addEntregaventa($formData){
      $data = array(
        'hcj_id_cierre_caja'          => $formData['hcj_id_cierre_caja'],
        'usu_id_usuario'              => $formData['usu_id_usuario'],
        'edv_monto_total'             => $formData['edv_monto_total'],
        'edv_fecha'                   => $formData['edv_fecha'],
        'edv_monto_20mil'             => $formData['edv_monto_20mil'],
        'edv_monto_10mil'             => $formData['edv_monto_10mil'],
        'edv_monto_5mil'              => $formData['edv_monto_5mil'],
        'edv_monto_2mil'              => $formData['edv_monto_2mil'],
        'edv_monto_1mil'              => $formData['edv_monto_1mil'],
        'edv_monto_500'               => $formData['edv_monto_500'],
        'edv_monto_otros_documentos'  => $formData['edv_monto_otros_documentos'],
        'edv_monto_devoluciones'      => $formData['edv_monto_devoluciones']
);
       //var_dump($data);
      $this->insert($data);
  }
}

