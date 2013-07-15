<?php

class Application_Model_DbTable_Cliente extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_cliente';

    public function indexMiniCliente()
    {
        $select = $this->select()
                  ->from( array("smo_cliente"), array("cli_id_cliente", "cli_nombre", "cli_apellido_1", "cli_apellido_2", "cli_rut") )
                  ->where("cli_desactivado = ?","NO");
        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("No se encontraron filas.");
        }
        return $row->toArray();
    }
    
    public function addCliente($formData){
        //var_dump($formData);
      $this->insert($formData);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
    
    public function getCliente($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('cli_id_cliente = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
    
    public function updateCliente($formData){
        $data = array(
          "cli_nombre"          => $formData['cli_nombre'],
          "cli_rut"             => $formData['cli_rut'],
          'cli_apellido_1'      => $formData['cli_apellido_1'],
          'cli_apellido_2'      => $formData['cli_apellido_2'],
          'cli_fono_1'          => $formData['cli_fono_1'],
          'cli_fono_2'          => $formData['cli_fono_2'],
          'cli_direccion'       => $formData['cli_direccion'],
          'cli_lugar_de_trabajo'=> $formData['cli_lugar_de_trabajo'],
          'cli_ciudad'          => $formData['cli_ciudad'] );
        //var_dump($data);
        $this->update($data, 'cli_id_cliente = ' . (int)$formData['cli_id_cliente']);
    }
    
    public function deleteCliente($id_cliente){
        $data = array(
          "cli_desactivado"          => 'SI' );
        
        $this->update($data, 'cli_id_cliente = ' . (int)$id_cliente);
    }
}

