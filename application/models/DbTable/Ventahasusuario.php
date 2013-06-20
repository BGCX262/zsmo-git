<?php

class Application_Model_DbTable_Ventahasusuario extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_venta_has_usuario';

    public function addVentahasusuario($formData){
      $data = array(
            'ven_id_venta'                => $formData[0],
            'usu_id_usuario'              => $formData[1],
            'vhu_monto_comision'          => $formData[2],
            'vhu_tipo_perfil'             => $formData[3]
        );
      //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
}