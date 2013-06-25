<?php

class Application_Model_DbTable_Usuarios extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_usuario';

    public function getUsuarioPorPerfilLocal($perfil, $local){
      $select = $this->select()->setIntegrityCheck(false);
      $select->from( array('u' => 'smo_usuario'), array('u.usu_id_usuario','u.usu_nombre','u.usu_apellido_1','u.usu_apellido_2') );
      $select->join( array('uhp' => 'smo_usuario_has_perfil'), 'u.usu_id_usuario = uhp.usu_id_usuario', array() );
      $select->join( array('uhl' => 'smo_usuario_has_local'), 'u.usu_id_usuario = uhl.usu_id_usuario', array() );
      $select->join( array('p' => 'smo_perfil'), 'p.per_id_perfil = uhp.per_id_perfil', array('p.per_id_perfil','p.per_nombre') );
      $select->join( array('l' => 'smo_local'), 'l.loc_id_local = uhl.loc_id_local', array('l.loc_id_local','l.loc_nombre') );
      $select->where('p.per_nombre = ?',$perfil);
      $select->where('l.loc_nombre = ?',$local);
      $select->distinct();
      $rows = $this->fetchAll($select);
        if (!$rows) {
          return false;
        }else{
          //var_dump($rows);
          return $rows;
        }
    }
    
    public function indexMiniUsuario()
    {
        $select = $this->select()
                  ->from( array("smo_usuario"), array("usu_id_usuario", "usu_nombre", "usu_apellido_1", "usu_apellido_2", "usu_rut") );
        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("No se encontraron filas.");
        }
        return $row->toArray();
    }
    
    public function getUsuario($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('usu_id_usuario = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }

    public function getUsuario2($rut)
    {
        $row = $this->fetchRow('usu_rut = "' . $rut.'"');
        if (!$row) {
            throw new Exception("No se encontró $rut");
        }
        return $row->toArray();
    }
    
    public function addUsuario($rut, $nombre, $apellido_1, $apellido_2,
            $fono_1, $fono_2, $direccion, $ciudad, $password, $comision)
    {
      $salt = md5( uniqid(rand(), TRUE));
      $data = array(
            'usu_rut' => $rut,
            'usu_nombre' => $nombre,
            'usu_apellido_1' => $apellido_1,
            'usu_apellido_2' => $apellido_2,
            'usu_fono_1' => $fono_1,
            'usu_fono_2' => $fono_2,
            'usu_direccion' => $direccion,
            'usu_passwd' => md5($password.$salt),
            'usu_passwd_salt' => $salt,
            'usu_porcentaje_comision' => $comision,
            'usu_ciudad' => $ciudad,
        );
        $this->insert($data);
    }
    
    public function updateUsuario($id, $rut, $nombre, $apellido_1, $apellido_2,
                                  $fono_1, $fono_2, $direccion, $ciudad, $password, $comision){
 //     $salt = md5( uniqid(rand(), TRUE));
      $data = array(
            'usu_rut' => $rut,
            'usu_nombre' => $nombre,
            'usu_apellido_1' => $apellido_1,
            'usu_apellido_2' => $apellido_2,
            'usu_fono_1' => $fono_1,
            'usu_fono_2' => $fono_2,
            'usu_direccion' => $direccion,
//            'usu_passwd' => md5($password.$salt),
//            'usu_passwd_salt' => $salt,
            'usu_porcentaje_comision' => $comision,
            'usu_ciudad' => $ciudad
        );
        $this->update($data, 'usu_id_usuario = ' . (int)$id);
    }
    
    public function cambiarpassUsuario($id, $password){
      $salt = md5( uniqid(rand(), TRUE));
      $data = array(
            'usu_passwd' => md5($password.$salt),
            'usu_passwd_salt' => $salt,
        );
      $this->update($data, 'usu_id_usuario = ' . (int)$id);
    }
    
    public function deleteUsuario($id)
    {
        $this->delete('usu_id_usuario = ' . (int)$id);
    }
}