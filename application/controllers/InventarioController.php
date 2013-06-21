<?php

class InventarioController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
      $inventario = new Application_Model_DbTable_Inventario();
      $inventariosArr = $inventario->getDetalleInventario();

      for($i=0;$i<count($inventariosArr); $i++){
          $inventariosArr[$i]['acciones']=
           '<button class="btn btn-info btn-mini" title="Foto" id="botonFoto'.$i.'" data-toggle="modal" onClick="cargarFoto(\''.$inventariosArr[$i]['mer_foto'].'\',\''.$i.'\')"><i class="icon-search icon-white"></i></button>
            <button class="btn btn-info btn-mini" title="Detalles"><i class="icon-list icon-white"></i></button>
            <button class="btn btn-warning btn-mini" title="Agregar Excepción"><i class="icon-edit icon-white"></i></button>';
      }
      $this->view->inventarios = $inventariosArr;
    }

    public function indexlocalAction()
    {
        // action body
      $inventario = new Application_Model_DbTable_Inventario();
      $inventariosArr = $inventario->getDetalleInventario2();

      for($i=0;$i<count($inventariosArr); $i++){
          $inventariosArr[$i]['acciones']=
           '<button class="btn btn-info btn-mini" title="Foto" id="botonFoto'.$i.'" data-toggle="modal" onClick="cargarFoto(\''.$inventariosArr[$i]['mer_foto'].'\',\''.$i.'\')"><i class="icon-search icon-white"></i></button>
            <button class="btn btn-info btn-mini" title="Detalles"><i class="icon-list icon-white"></i></button>';
      }
      $this->view->inventarios = $inventariosArr;
    }


}