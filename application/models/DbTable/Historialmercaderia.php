<?php

class Application_Model_DbTable_Historialmercaderia extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_historial_mercaderia';

    public function getLastPrecio($mer_id_mercaderia, $fecha) {   //Obtiene precio segun una fecha de actualizacion
        $id = (int)$mer_id_mercaderia;
        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("h"=>"smo_historial_mercaderia"), array("h.*") )
              ->where('h.mer_id_mercaderia = ?', $id )
              ->where('h.hme_fecha_inicial < ?', $fecha)->orWhere('h.hme_fecha_final > ?', $fecha)
              ->order('h.hme_id_historial_mercaderia DESC')
              ->limit(1, 0);      //   LIMIT 1 OFFSET 0
        $row = $this->fetchAll( $select );
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row;
        //var_dump($row);
    }
    
}

