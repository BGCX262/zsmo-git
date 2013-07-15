<?php

class Application_Model_DbTable_Historialabono extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_historial_abono';

    public function indexHistorialabono()
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select    ->from( array('ha' => 'smo_historial_abono'), array("ha.abo_id_abono", "ha.abo_tipo_movimiento"))
                  ->joinRight( array('c' => 'smo_cliente'), 'c.cli_id_cliente = ha.cli_id_cliente' , array("c.cli_id_cliente", "c.cli_nombre", "c.cli_apellido_1", "c.cli_apellido_2", "c.cli_rut") )
                  ->where("c.cli_desactivado = ?","NO");
        
        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("No se encontraron filas.");
        }
        return $row->toArray();
    }
    
    public function addHistorialabono($abonoArr)
    {
        //var_dump($formData);
      $formData = $abonoArr;
      $this->insert($formData);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
}