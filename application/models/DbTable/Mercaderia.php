<?php

class Application_Model_DbTable_Mercaderia extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_mercaderia';

    public function indexMiniMercaderia() {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("m"=>"smo_mercaderia"), array("m.mer_id_mercaderia", "m.mer_codigo", "m.mer_articulo") )
               ->join( array("d"=>"smo_gde_destinatario"),"m.fcp_id_familia_codigo_proveedor = d.des_id_destinatario", array("d.des_id_destinatario","d.des_nombre") )
               ->join( array("c"=>"smo_color"),"m.col_id_color = c.col_id_color", array("c.*") );
        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("No se encontraron filas.");
        }
        return $row->toArray();
    }
    
    public function getMercaderia($id) {
        $id = (int)$id;
        $row = $this->fetchRow('mer_id_mercaderia = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
    
    public function getMercaderia2($mer_articulo) {
        $row = $this->fetchRow('mer_articulo = "' . $mer_articulo.'"');
        if (!$row) {
            throw new Exception("No se encontró fila $mer_articulo");
        }
        return $row->toArray();
    }
    
    public function getMercaderia3($mer_codigo) {
        $id = (int)$mer_codigo;
        
        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("m"=>"smo_mercaderia"), array("m.mer_id_mercaderia", "m.mer_codigo", "m.mer_articulo", "m.mer_foto", "m.mer_costo") )
              ->join( array("t"=>"smo_talla"),"m.tal_id_talla = t.tal_id_talla",array("t.*"))
              ->join( array("c"=>"smo_color"),"m.col_id_color = c.col_id_color", array("c.col_nombre") )
              ->where( 'm.mer_codigo = ?', $id );
        $row = $this->fetchAll( $select );
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row;
    }
    
    public function getMercaderia4($mer_codigo, $tal_id_talla) {        
        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("m"=>"smo_mercaderia"), array("m.*") )
               ->where('m.mer_codigo = ?',$mer_codigo)
               ->where('m.tal_id_talla = ?',$tal_id_talla);
        $row = $this->fetchAll( $select );
        if (!$row) {
            throw new Exception("No se encontró fila $mer_codigo,$tal_id_talla");
        }
        return $row->toArray();
    }

    
    public function addMercaderia($mer_codigo, $mer_nombre,$mer_descripcion, $fcp_id_familia_codigo_proveedor, 
            $talla, $mer_costo, $mer_tipo, $col_id_color, $mer_modelo, $mer_articulo, $nombreFoto,
            $mer_tamanno_tarea, $mer_prioridad_venta, $mer_sexo, $mer_temporada) {
      $data = array(
            'mer_codigo'                      => $mer_codigo,
            'mer_nombre'                      => $mer_nombre,
            'mer_descripcion'                 => $mer_descripcion,
            'fcp_id_familia_codigo_proveedor' => $fcp_id_familia_codigo_proveedor,
            'tal_id_talla'                    => $talla,
            'mer_costo'                       => $mer_costo,
            'mer_tipo'                        => $mer_tipo,
            'col_id_color'                    => $col_id_color,
            'mer_modelo'                      => $mer_modelo,
            'mer_articulo'                    => $mer_articulo,
            'mer_foto'                        => $nombreFoto,
            'mer_tamanno_tarea'               => $mer_tamanno_tarea,
            'mer_prioridad_venta'             => $mer_prioridad_venta,
            'mer_sexo'                        => $mer_sexo,
            'mer_temporada'                   => $mer_temporada
        );
      $this->insert($data);
    }
    
    public function updateMercaderia($mer_id_mercaderia, $mer_codigo, $mer_nombre,$mer_descripcion, $fcp_id_familia_codigo_proveedor, 
            $talla, $mer_costo, $mer_tipo,  $col_id_color, $mer_modelo, $mer_articulo,  $mer_foto,
            $mer_tamanno_tarea, $mer_prioridad_venta, $mer_sexo, $mer_temporada) {
       $data = array(
            'mer_codigo'                      => $mer_codigo,
            'mer_nombre'                      => $mer_nombre,
            'mer_descripcion'                 => $mer_descripcion,
            'fcp_id_familia_codigo_proveedor' => $fcp_id_familia_codigo_proveedor,
            'tal_id_talla'                    => $talla,
            'mer_costo'                       => $mer_costo,
            'mer_tipo'                        => $mer_tipo,
            'col_id_color'                    => $col_id_color,
            'mer_modelo'                      => $mer_modelo,
            'mer_articulo'                    => $mer_articulo,
            'mer_foto'                        => $mer_foto,
            'mer_tamanno_tarea'               => $mer_tamanno_tarea,
            'mer_prioridad_venta'             => $mer_prioridad_venta,
            'mer_sexo'                        => $mer_sexo,
            'mer_temporada'                   => $mer_temporada
        );
        $this->update($data, 'mer_id_mercaderia = ' . (int)$mer_id_mercaderia);
    }
    
    public function deleteMercaderia($mer_id_mercaderia){
        $this->delete('mer_id_mercaderia = ' . (int)$mer_id_mercaderia);
    }
}