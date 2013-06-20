<?php

class Application_Model_DbTable_Cajatarea extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_caja_tarea';

    public function getListaCajatarea($codigo, $bodega, $inv_estado, $completa ){
//SELECT DISTINCT i . *
//FROM smo_inventario i, smo_caja_tarea ct
//WHERE i.mer_id_mercaderia =19
//AND i.bod_id_bodega =1
//AND i.inve_id_inv_estado =2
//AND ct.cjt_completa =1
        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("ct"=>"smo_caja_tarea" ), array("") )
               ->join( array("i"=>"smo_inventario"), "i.cjt_id_caja_tarea = ct.cjt_id_caja_tarea", array("i.*") )
               ->join( array("m"=>"smo_mercaderia"), "i.mer_id_mercaderia = m.mer_id_mercaderia", array("") )
               ->join( array("e"=>"smo_inv_estado"), "i.inve_id_inv_estado = e.inve_id_inv_estado", array("") )
               ->join( array("b"=>"smo_bodega"),"i.bod_id_bodega = b.bod_id_bodega", array("") )
               ->where("m.mer_codigo = ?",$codigo)
               ->where("e.inve_nombre = ?", $inv_estado) 
               ->where("b.bod_nombre = ?", $bodega) 
               ->where("ct.cjt_completa = ?",$completa );
        $select->distinct();
        $select->order("i.cjt_id_caja_tarea ASC");
        $row = $this->fetchAll( $select );
        
        if (!$row) {
            throw new Exception("No se encontró filas: $codigo, $bodega, $inv_estado");
        }
        return $row->toArray();
    }
    
    public function getCajatarea2($tamanno_tarea,$zona_bodega,$curva,$completa) {
                
        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("ct"=>"smo_caja_tarea"), array("ct.*") )
               ->where('ct.cjt_tamanno_tarea = ?',$tamanno_tarea)
               ->where('ct.cjt_zona_bodega = ?',$zona_bodega)
               ->where('ct.cjt_curva = ?',$curva)
               ->where('ct.cjt_completa = ?',$completa);
        $row = $this->fetchAll( $select );
        
        if (!$row) {
            throw new Exception("No se encontró fila $tamanno_tarea,$zona_bodega,$curva, $completa");
        }
        return $row->toArray();
    }
    
    public function addCajatarea($formData){
      $tamanno_tarea = intval( $formData[9] ) / intval( $formData[2] ); //CANT MERCADERIA / CANT CAJAS
      $data = array(
            'cjt_tamanno_tarea'     => $tamanno_tarea,
            'cjt_zona_bodega'       => '',
            'cjt_curva'             => $formData['curva'],
            'cjt_completa'          => $formData['completa']
          );
        //var_dump($formData);
        //var_dump($data);
        $this->insert($data);
        $lastInsertId = $this->getAdapter()->lastInsertId();
        return $lastInsertId;
    }
}

