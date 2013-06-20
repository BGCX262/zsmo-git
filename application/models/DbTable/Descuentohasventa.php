<?php

class Application_Model_DbTable_Descuentohasventa extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_descuento_has_venta';

    public function addDescuentohasventa($formData){
      $data = array(
            'des_id_descuento'            => $formData[0],
            'ven_id_venta'                => $formData[1],
            'dhv_monto_final_calculado'   => $formData[2]
        );
      //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
}
