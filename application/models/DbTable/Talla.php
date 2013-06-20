<?php

class Application_Model_DbTable_Talla extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_talla';

    public function getTalla2($id) {
        $row = $this->fetchRow('tal_talla = "'.$id.'"');
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
    
    public function getRegistro($id) {
        $row = $this->fetchRow('tal_id_talla = "'.$id.'"');
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
}

