<?php

class Application_Model_DbTable_Bodega extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_bodega';

    public function getBodega( $id ){
        $row = $this->fetchRow('bod_id_bodega = "' . $id.'"');
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
    
    public function getBodega2($nombre) {
        $row = $this->fetchRow('bod_nombre = "' . $nombre.'"');
        if (!$row) {
            throw new Exception("No se encontró fila $nombre");
        }
        return $row->toArray();
    }
    
    public function getBodega3( $id_destinatario ){
        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("b"=>"smo_bodega"), array("b.*") )
               ->join( array("d"=>"smo_gde_destinatario"),"b.bod_nombre = d.des_nombre", array() )
               ->where("d.des_id_destinatario = ?", $id_destinatario );
        $select->distinct();
        $row = $this->fetchAll( $select );
        
        if (!$row) {
            throw new Exception("No se encontró filas: $id_destinatario");
        }
        return $row->toArray();
    }
}

