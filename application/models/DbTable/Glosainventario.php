<?php

class Application_Model_DbTable_Glosainventario extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_glosa_inventario';

    public function getGlosainventario($nombre) {
        $row = $this->fetchRow('ghi_nombre = "'.$nombre.'"');
        if (!$row) {
            throw new Exception("No se encontró fila $nombre");
        }
        return $row->toArray();
    }

}

