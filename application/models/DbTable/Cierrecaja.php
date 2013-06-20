<?php

class Application_Model_DbTable_Cierrecaja extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_cierre_caja';

    public function getCierrecaja($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('hcj_id_cierre_caja = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
    
    
    public function addCierrecaja($formData)
    {
      $data = array(
                'usu_id_usuario'              => $formData['usu_id_usuario'],
                'loc_id_local'                => $formData['loc_id_local'],
                'hcj_fecha_creacion'          => $formData['hcj_fecha_creacion'],
                'hcj_fecha_contable_inicio'   => $formData['hcj_fecha_contable_inicio'],
                'hcj_fecha_contable_final'    => $formData['hcj_fecha_contable_final']
        );
      //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
    

    public function getLastCierrecaja($id_local)
    {
//      $qry = "SELECT * FROM smo_cierre_caja WHERE loc_id_local = :local ORDER BY hcj_id_cierre_caja DESC LIMIT 1 OFFSET 0";

        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("cj"=>"smo_cierre_caja"), array("cj.*") )
               ->where('cj.loc_id_local = ?',$id_local)
               ->order('cj.hcj_id_cierre_caja DESC')
               ->limit(1, 0);      //   LIMIT 1 OFFSET 0
        $row = $this->fetchAll( $select );

        if (!$row) {
            throw new Exception("No se encontró filas: $id_local ");
        }
        return $row->toArray();
    }
}