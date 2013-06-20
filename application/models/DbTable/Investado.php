<?php

class Application_Model_DbTable_Investado extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_inv_estado';

    public function getInvestado2($nombre) {
        $row = $this->fetchRow('inve_nombre = "'.$nombre.'"');
        if (!$row) {
            throw new Exception("No se encontró fila $nombre");
        }
        return $row->toArray();
    }
}

