<?php

class Application_Model_DbTable_Tipopagohasventa extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_tipo_pago_has_venta';

    public function addTipopagohasventa($formData){
      $data = array(
            'tip_id_tipo_pago'            => $formData[0],
            'ven_id_venta'                => $formData[1],
            'tphv_monto'                  => $formData[2],
            'tphv_codigo_cheque'          => $formData[3],
            'tphv_cant_cuotas'            => $formData[4],
            'tphv_observacion_smo'        => $formData[5],
        );
      //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
}
