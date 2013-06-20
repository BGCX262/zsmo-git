<?php

class Application_Model_DbTable_Inventario extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_inventario';

    public function getInventario($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('inv_id_inventario = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }

    public function getInventarioPorFiltro($bodega,$codigo,$talla)
    {
/*
SELECT
i.*
FROM
smo_inventario i,
smo_mercaderia m,
smo_bodega b,
smo_inv_estado ie,
smo_talla t
WHERE
i.mer_id_mercaderia = m.mer_id_mercaderia AND
i.bod_id_bodega = b.bod_id_bodega AND
i.inve_id_inv_estado = ie.inve_id_inv_estado AND
t.tal_id_talla = m.tal_id_talla AND
b.bod_nombre = 'Bodega José Andrés' AND
m.mer_codigo = '58001' AND
t.tal_talla = '30' AND
ie.inve_nombre = 'Disponible'
 */      
      $investado='Disponible';
      $select = $this->select()->setIntegrityCheck(false);
      $select->from( array('i' => 'smo_inventario'), array("i.*") );
      $select->join( array("m"=>"smo_mercaderia"),"i.mer_id_mercaderia = m.mer_id_mercaderia", array("") );
      $select->join( array("b"=>"smo_bodega"),"i.bod_id_bodega = b.bod_id_bodega", array("") );
      $select->join( array("ie"=>"smo_inv_estado"),"i.inve_id_inv_estado = ie.inve_id_inv_estado", array("") );
      $select->join( array("t"=>"smo_talla"),"t.tal_id_talla = m.tal_id_talla", array("") );
      $select->where("b.bod_nombre = ?",$bodega);
      $select->where("m.mer_codigo = ?",$codigo);
      $select->where("t.tal_talla = ?",$talla);
      $select->where("ie.inve_nombre = ?",$investado);

      $row = $this->fetchAll( $select );
      if (!$row) {
          throw new Exception("No se encontró fila");
      }
      return $row->toArray();
    }
    
    public function getInventario2($id_transaccion) //get arreglos/matriz con inventarios,pasando por transaccion has inventario
    {
      $id = (int)$id_transaccion;
      $select = $this->select()->setIntegrityCheck(false);
      $select->from( array('i' => 'smo_inventario'), array("i.*") );
      $select->join( array("iht"=>"smo_inventario_has_transaccion"),"i.inv_id_inventario = iht.inv_id_inventario", array("") );
      $select->where("iht.tra_id_transaccion = ?",$id);
      $row = $this->fetchAll( $select );
      if (!$row) {
          throw new Exception("No se encontró fila $id");
      }
      return $row->toArray();
    }

    public function getDetalleInventario(){
  
      $select = $this->select()->setIntegrityCheck(false);
      $select->from( array('i' => 'smo_inventario'), array(new Zend_Db_Expr('COUNT(DISTINCT i.cjt_id_caja_tarea) as caja_tarea'), new Zend_Db_Expr('SUM(i.inv_cantidad) as cantidad'),'i.inv_fecha') );
      $select->join( array('e' => 'smo_inv_estado'), 'i.inve_id_inv_estado = e.inve_id_inv_estado', array('e.inve_nombre') );
      $select->join( array('b' => 'smo_bodega'), 'i.bod_id_bodega = b.bod_id_bodega', array('b.bod_nombre') );
      $select->join( array('m' => 'smo_mercaderia'), 'i.mer_id_mercaderia = m.mer_id_mercaderia', array('m.mer_codigo','m.mer_articulo','m.mer_costo','m.mer_foto') );
      $select->join( array('c' => 'smo_color'), 'm.col_id_color = c.col_id_color', array('c.col_nombre') );
      $select->group('i.inve_id_inv_estado');
      //$select->distinct();
      $rows = $this->fetchAll($select);
        if (!$rows) {
          return false;
        }else{
          return $rows->toArray();
        }
    }
    public function getDetalleInventario2(){
  
      $select = $this->select()->setIntegrityCheck(false);
      $select->from( array('i' => 'smo_inventario'), array(new Zend_Db_Expr('SUM(i.inv_cantidad) as cantidad'),'i.inv_fecha') );
      $select->join( array('e' => 'smo_inv_estado'), 'i.inve_id_inv_estado = e.inve_id_inv_estado', array('e.inve_nombre') );
      $select->join( array('b' => 'smo_bodega'), 'i.bod_id_bodega = b.bod_id_bodega', array('b.bod_nombre') );
      $select->join( array('m' => 'smo_mercaderia'), 'i.mer_id_mercaderia = m.mer_id_mercaderia', array('m.mer_codigo','m.mer_articulo','m.mer_costo','m.mer_foto') );
      $select->join( array('t' => 'smo_talla'), 't.tal_id_talla = m.tal_id_talla', array('t.tal_talla') );
      $select->join( array('c' => 'smo_color'), 'm.col_id_color = c.col_id_color', array('c.col_nombre') );
      $select->group('i.mer_id_mercaderia');
      //$select->distinct();
      $rows = $this->fetchAll($select);
        if (!$rows) {
          return false;
        }else{
          return $rows->toArray();
        }
    }
    
    public function addInventario($formData){
      $data = array(
            'mer_id_mercaderia'       => $formData['mer_id_mercaderia'],
            'bod_id_bodega'           => $formData['bod_id_bodega'],
            'inve_id_inv_estado'      => $formData['inve_id_inv_estado'],
            'cjt_id_caja_tarea'       => $formData['cjt_id_caja_tarea'],
            'inv_cantidad'            => $formData['inv_cantidad'],
            'inv_fecha'               => $formData['inv_fecha']
        );
        //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
    
    public function getListaPorCajaInventario($id_caja_tarea, $bodega, $inv_estado, $completa ){

        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("i"=>"smo_inventario"),array("i.*") )
               ->join( array("e"=>"smo_inv_estado"),"i.inve_id_inv_estado = e.inve_id_inv_estado", array("") ) 
               ->join( array("b"=>"smo_bodega"),"i.bod_id_bodega = b.bod_id_bodega", array("") )
               ->where("e.inve_nombre = ?",$inv_estado)
               ->where("b.bod_nombre = ?",$bodega)
               ->where("i.cjt_id_caja_tarea = ?",$id_caja_tarea);
//               ->join( array("ct"=>"smo_caja_tarea"),"i.cjt_id_caja_tarea = ct.cjt_id_caja_tarea AND ct.cjt_completa = ".$completa, array("") );
        $select->distinct();
        $select->order("i.cjt_id_caja_tarea ASC");
        $row = $this->fetchAll( $select );
        
        if (!$row) {
            throw new Exception("No se encontró filas: $id_caja_tarea, $bodega, $inv_estado, $completa ");
        }
        return $row->toArray();
    }
    
    public function updateInventario($formData){
      $data = array(
            'mer_id_mercaderia'       => $formData['mer_id_mercaderia'],
            'bod_id_bodega'           => $formData['bod_id_bodega'],
            'inve_id_inv_estado'      => $formData['inve_id_inv_estado'],
            'cjt_id_caja_tarea'       => $formData['cjt_id_caja_tarea'],
            'inv_cantidad'            => $formData['inv_cantidad'],
            'inv_fecha'               => $formData['inv_fecha']
        );
      //  var_dump($data);
      $this->update($data, 'inv_id_inventario = ' . (int)$formData['inv_id_inventario']);
      return $formData['inv_id_inventario'];
      
    }
}