<?php

class Application_Model_DbTable_Transaccion extends Zend_Db_Table_Abstract
{
    protected $_name = 'smo_transaccion';

    public function getTransaccion($id) {
        $id = (int)$id;
        $row = $this->fetchRow('tra_id_transaccion = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
    
    public function getDetalleTransaccion(){
/*
SELECT DISTINCT t.tra_id_transaccion, t.tra_tipo_motivo, t.tra_fecha_inicio, t.tra_fecha_finalizacion, t.tra_rut_autorizado_final, dp.dop_nom_des_salida AS des_salida, dp.dop_nom_des_llegada AS des_llegada, m.mer_codigo, m.mer_articulo, m.col_id_color, c.col_nombre
FROM smo_documento_principal dp, smo_transaccion t, smo_inventario_has_transaccion iht, smo_inventario i, smo_mercaderia m, smo_color c
WHERE t.dop_id_documento_principal = dp.dop_id_documento_principal
AND t.tra_id_transaccion = iht.tra_id_transaccion
AND iht.inv_id_inventario = i.inv_id_inventario
AND i.mer_id_mercaderia = m.mer_id_mercaderia
AND m.col_id_color = c.col_id_color
 */
      $select = $this->select()->setIntegrityCheck(false);
      $select->from( array('t' => 'smo_transaccion'), array('t.tra_id_transaccion','t.tra_tipo_motivo','t.tra_fecha_inicio','t.tra_fecha_finalizacion','t.tra_rut_autorizado_final') );
      $select->join( array('dp' => 'smo_documento_principal'), 't.dop_id_documento_principal = dp.dop_id_documento_principal', array('dp.dop_nom_des_salida as des_salida','dp.dop_nom_des_llegada as des_llegada') );
      $select->join( array('iht' => 'smo_inventario_has_transaccion'), 't.tra_id_transaccion = iht.tra_id_transaccion', array() );
      $select->join( array('i' => 'smo_inventario'), 'iht.inv_id_inventario = i.inv_id_inventario', array() );
      $select->join( array('m' => 'smo_mercaderia'), 'i.mer_id_mercaderia = m.mer_id_mercaderia', array('m.mer_codigo','m.mer_articulo','m.col_id_color') );
      $select->join( array('c' => 'smo_color'), 'm.col_id_color = c.col_id_color', array('c.col_nombre') );
      $select->distinct();
      $rows = $this->fetchAll($select);
        if (!$rows) {
          return false;
        }else{
          return $rows->toArray();
        }
    }

    public function getDetalleTransaccion2(){   // PARA ENCLOC
/*
SELECT DISTINCT t.tra_id_transaccion, t.tra_tipo_motivo, t.tra_fecha_inicio, t.tra_fecha_finalizacion, t.tra_rut_autorizado_final, dp.dop_nom_des_salida AS des_salida, dp.dop_nom_des_llegada AS des_llegada, m.mer_codigo, m.mer_articulo, m.col_id_color, c.col_nombre
FROM smo_documento_principal dp, smo_transaccion t, smo_inventario_has_transaccion iht, smo_inventario i, smo_mercaderia m, smo_color c
WHERE t.dop_id_documento_principal = dp.dop_id_documento_principal
AND t.tra_id_transaccion = iht.tra_id_transaccion
AND iht.inv_id_inventario = i.inv_id_inventario
AND i.mer_id_mercaderia = m.mer_id_mercaderia
AND m.col_id_color = c.col_id_color
WHERE dp.dop_nom_des_salida = "Bodega Principal"
 */
      $salida= "Bodega Principal";
      $select = $this->select()->setIntegrityCheck(false);
      $select->from( array('t' => 'smo_transaccion'), array('t.tra_id_transaccion','t.tra_tipo_motivo','t.tra_fecha_inicio','t.tra_fecha_finalizacion','t.tra_rut_autorizado_final') );
      $select->join( array('dp' => 'smo_documento_principal'), 't.dop_id_documento_principal = dp.dop_id_documento_principal', array('dp.dop_nom_des_salida as des_salida','dp.dop_nom_des_llegada as des_llegada') );
      $select->join( array('iht' => 'smo_inventario_has_transaccion'), 't.tra_id_transaccion = iht.tra_id_transaccion', array() );
      $select->join( array('i' => 'smo_inventario'), 'iht.inv_id_inventario = i.inv_id_inventario', array() );
      $select->join( array('m' => 'smo_mercaderia'), 'i.mer_id_mercaderia = m.mer_id_mercaderia', array('m.mer_codigo','m.mer_articulo','m.col_id_color') );
      $select->join( array('c' => 'smo_color'), 'm.col_id_color = c.col_id_color', array('c.col_nombre') );
      $select->where( "dp.dop_nom_des_salida = ?", $salida );
      $select->distinct();
      $rows = $this->fetchAll($select);
        if (!$rows) {
          return false;
        }else{
          return $rows->toArray();
        }
    }
    
    public function addTransaccion($formData){
      $data = array(
            'dop_id_documento_principal'    => $formData['dop_id_documento_principal'],
            'ctr_id_transporte'             => $formData['ctr_id_transporte'],
            'tra_tipo_motivo'               => $formData['tra_tipo_motivo'],
            'tra_fecha_inicio'              => $formData['tra_fecha_inicio'],
            'tra_fecha_finalizacion'        => $formData['tra_fecha_finalizacion'],
            'tra_rut_autorizado_inicio'     => $formData['tra_rut_autorizado_inicio'],
            'tra_rut_autorizado_final'      => $formData['tra_rut_autorizado_final'],
            'tra_tipo'                      => $formData['tra_tipo']
        );
        //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
    
    public function updateTransaccion($formData){
      $data = array(
            'dop_id_documento_principal'    => $formData['dop_id_documento_principal'],
            'ctr_id_transporte'             => $formData['ctr_id_transporte'],
            'tra_tipo_motivo'               => $formData['tra_tipo_motivo'],
            'tra_fecha_inicio'              => $formData['tra_fecha_inicio'],
            'tra_fecha_finalizacion'        => $formData['tra_fecha_finalizacion'],
            'tra_rut_autorizado_inicio'     => $formData['tra_rut_autorizado_inicio'],
            'tra_rut_autorizado_final'      => $formData['tra_rut_autorizado_final'],
            'tra_tipo'                      => $formData['tra_tipo']
        );
        //var_dump($data);
      $this->update($data, 'tra_id_transaccion = ' . (int)$formData['tra_id_transaccion']);
      return $formData['tra_id_transaccion'];
    }
}