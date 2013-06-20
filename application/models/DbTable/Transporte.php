<?php

class Application_Model_DbTable_Transporte extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_tra_transporte';

    public function getTransporte($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('ctr_id_transporte = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
    
    public function addTransporte($formData){
      $data = array(
            'ctr_costo'               => $formData['ctr_costo'],
            'ctr_nombre'              => $formData['ctr_nombre'],
            'ctr_fecha_salida'        => $formData['ctr_fecha_salida'],
            'ctr_fecha_llegada'       => $formData['ctr_fecha_llegada'],
            'ctr_ciudad_salida'       => $formData['ctr_ciudad_salida'],
            'ctr_ciudad_llegada'      => $formData['ctr_ciudad_llegada'],
            'ctr_direccion_salida'    => $formData['des_direccion'],
            'ctr_direccion_llegada'   => $formData['des_direccion2']
        );
        //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
    
    public function updateTransporte($formData){
      $data = array(
            'ctr_costo'               => $formData['ctr_costo'],
            'ctr_nombre'              => $formData['ctr_nombre'],
            'ctr_fecha_salida'        => $formData['ctr_fecha_salida'],
            'ctr_fecha_llegada'       => $formData['ctr_fecha_llegada'],
            'ctr_ciudad_salida'       => $formData['ctr_ciudad_salida'],
            'ctr_ciudad_llegada'      => $formData['ctr_ciudad_llegada'],
            'ctr_direccion_salida'    => $formData['des_direccion'],
            'ctr_direccion_llegada'   => $formData['des_direccion2']
        );
        //var_dump($data);
      $this->update($data, 'ctr_id_transporte = ' . (int)$formData['ctr_id_transporte']);
      return $formData['ctr_id_transporte'];
    }
}