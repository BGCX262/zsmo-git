<?php

class Application_Model_DbTable_Venta extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_venta';

    public function addVenta($formData){
      $data = array(
            'cli_id_cliente'           => $formData[0],
            'cre_id_credito'           => $formData[1],
            'bol_id_talonario_boleta'  => $formData[2],
            'edv_id_entrega_ventas'    => $formData[3],
            'ven_fecha'                => $formData[4],
            'ven_monto_total'          => $formData[5]
        );
      //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
}