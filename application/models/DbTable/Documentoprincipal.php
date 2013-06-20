<?php

class Application_Model_DbTable_Documentoprincipal extends Zend_Db_Table_Abstract
{
    protected $_name = 'smo_documento_principal';

    public function getDocumentoprincipal($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('dop_id_documento_principal = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
    
    public function addDocumentoPrincipal($formData){
      $data = array(
            'mpa_id_modalidad_pago'               => $formData['mpa_id_modalidad_pago'],
            'dop_numero_identificador'            => $formData['dop_numero_identificador'],
            'dop_fecha'                           => $formData['fecha'],
            'dop_giro'                            => $formData['dop_giro'],
            'dop_monto_total'                     => $formData[ 't_neto'],
            'dop_descuento_porcentaje_total'      => $formData[ 't_descuento'],
            'dop_iva'                             => $formData[ 't_iva'],
            'dop_monto_total_calculado'           => $formData[ 't_total'],
            'dop_tipo'                            => $formData['dop_tipo_doc_principal'],
            'dop_estado_pago'                     => $formData[''],
            'dop_orden_compra'                    => $formData['dop_orden_compra'],
            'dop_empresa_relacionada'             => $formData[ 'dop_empresa_relacionada'],
            'dop_tipo_motivo'                     => $formData['dop_tipo_motivo'],
            'dop_nom_des_salida'                  => $formData['des_salida'],
            'dop_nom_des_llegada'                 => $formData['dop_empresa_relacionada']
        );
        //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
    
}