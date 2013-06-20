<?php

class Application_Model_DbTable_Local extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_local';

    public function getLocal($nombreLocal){
      $select = $this->select()->setIntegrityCheck(false);
      $select->from( array("l"=>"smo_local"), array("l.*") )
             ->where('l.loc_nombre = ?',$nombreLocal);      //   LIMIT 1 OFFSET 0
      $row = $this->fetchAll( $select );

      if (!$row) {
          throw new Exception("No se encontró filas: $nombreLocal ");
      }
      return $row->toArray();
    }
    
    
    
}

