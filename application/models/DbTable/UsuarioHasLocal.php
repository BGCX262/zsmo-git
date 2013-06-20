<?php

class Application_Model_DbTable_UsuarioHasLocal extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_usuario_has_local';

  public function localesUsuario($id_usuario)
  {
    $id = (int) $id_usuario;
    $select = $this->select()->setIntegrityCheck(false);
    $select->from(array('l' => 'smo_local'), array('l.loc_nombre'));
    $select->join(array('uhl' => 'smo_usuario_has_local'), 'uhl.loc_id_local = l.loc_id_local', array());
    $select->where('uhl.usu_id_usuario = ?', $id);

   $row = $this->fetchAll($select);
    if (!$row) {
    return false;
    }
    return $row->toArray();
  }
  
  public function localVendedorUsuario($id_usuario)
  {
    $id = (int) $id_usuario;
    $bodega = "Bodega Principal";
    $select = $this->select()->setIntegrityCheck(false);
    $select->from(array('l' => 'smo_local'), array('l.loc_nombre'));
    $select->join(array('uhl' => 'smo_usuario_has_local'), 'uhl.loc_id_local = l.loc_id_local', array());
    $select->where('l.loc_nombre != ?', $bodega);
    $select->where('uhl.usu_id_usuario = ?', $id);

   $row = $this->fetchAll($select);
    if (!$row) {
    return false;
    }
    return $row->toArray();
  }
}

