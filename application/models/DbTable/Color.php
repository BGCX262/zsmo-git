<?php

class Application_Model_DbTable_Color extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_color';

    public function getColor($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('col_id_color = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
}

