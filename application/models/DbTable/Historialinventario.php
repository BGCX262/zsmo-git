<?php

class Application_Model_DbTable_Historialinventario extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_historial_inventario';

    public function addHistorialinventario($formData){
      $data = array(
            'inv_id_inventario'       => $formData['inv_id_inventario'],
            'ghi_id_glosa_inventario' => $formData['ghi_id_glosa_inventario'],
            'hii_entrada'             => $formData['hii_entrada'],
            'hii_salida'              => $formData['hii_salida'],
            'hii_total'               => $formData['hii_total'],
            'hii_fecha'               => $formData['hii_fecha'],
            'hii_descripcion'         => $formData['hii_descripcion'],
            'hii_id_padre_historial'   => $formData['hii_id_padre_historia']);
       // var_dump($data);
      $this->insert($data);
    }
    
    public function getLastHistorialinventario($id_inventario){
//SELECT *
//FROM `smo_historial_inventario`
//WHERE `inv_id_inventario` = 347
//ORDER BY hii_id_padre_historial DESC
//LIMIT 0 , 1
        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("hi"=>"smo_historial_inventario"), array("hi.*") )
               ->where('hi.inv_id_inventario = ?',$id_inventario)
               ->order('hi.hii_id_padre_historial DESC')
               ->limit(1, 0);      //   LIMIT 1 OFFSET 0
        $row = $this->fetchAll( $select );
        
        if (!$row) {
            throw new Exception("No se encontró filas: $id_inventario ");
        }
        return $row->toArray();
    }

}