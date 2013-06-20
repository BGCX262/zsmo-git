<?php

class Application_Model_DbTable_Ventahasinventario extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_venta_has_inventario';

    public function addVentahasinventario($formData){
      $data = array(
          'inv_id_inventario'           => $formData[0],
          'ven_id_venta'                => $formData[1],
          'vhi_precio_total'            => $formData[2],
          'vhi_cantidad'                => $formData[3]
        );
      //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
}

