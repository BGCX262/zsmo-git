<?php

class Application_Model_DbTable_Historialventa extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_historial_venta';

    public function getHistorialventa($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('hve_id_historial_venta = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
}

