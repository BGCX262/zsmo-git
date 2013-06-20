<?php

class Application_Model_DbTable_Inventariohastransaccion extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_inventario_has_transaccion';

    public function addInventariohastransaccion($formData){
      $data = array(
            'tra_id_transaccion'     => $formData['tra_id_transaccion'],
            'inv_id_inventario'      => $formData['inv_id_inventario'],
            'iht_fecha'              => $formData['iht_fecha']
        );
        //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
    
    public function listarRegistros($id_tran=''){
        $sql    = $this->select();
        
        if($id_tran!=''){
            $sql->where("tra_id_transaccion = ?", $id_tran);
        }
        
        $results    = $this->fetchAll($sql);
        
        return $results;
    }
    
}

