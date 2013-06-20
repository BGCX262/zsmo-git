<?php

class VentaController extends Zend_Controller_Action
{

  public function init()
  {
      /* Initialize action controller here */
  }

  public function indexAction()
  {
      // action body
  }

  public function addAction()
  {
    $form = new Application_Form_Venta();
    $this->view->form = $form;
    if ( $this->getRequest()->isPost() ) {
      $formData = $this->getRequest()->getPost();

      if ( $form->isValid($formData) ){
        switch($formData['tip_id_tipo_pago']){
        case '2': // EFECTIVO
          $this->pago_efectivo($formData);
        break;
        case '3': // SMO CREDITO
          $this->pago_credito($formData);
        break;
        case '4': // REDCOMPRA
          $this->pago_redcompra($formData);
        break;
        case '5': // ABONO SMO
          $this->pago_abono($formData);
        break;
        case '6': // TARJETA CREDITO (TRANSBANK)
          $this->pago_tarjeta_1($formData);
        break;
        case '7': // TARJETA CREDITO (ABCDIN)
          $this->pago_tarjeta_2($formData);
        break;
        case '8': // TARJETA CREDITO (PRESTO)
          $this->pago_tarjeta_3($formData);
        break;
        case '9': // CHEQUE
          $this->pago_cheque($formData);
        break;
        default:
        break;
        };

//        $returnUrl = $formData['returnUrl'];
//        if ($returnUrl != '') {
//          $this->_helper->getHelper('Redirector')->setGotoUrl($returnUrl);
//        }
      } else {
        var_dump($formData);
        var_dump( $form->getErrors() );
        $form->populate($formData);
      }
    }
  }

  public function ajaxfrombdAction()
  {
    $this->_helper->layout()->disableLayout();
      if ($this->getRequest()->isPost()) {
        if ( $this->getRequest()->getPost('mercaderia') != null ){
          $mercaderia = $this->getRequest()->getPost('mercaderia');
          $datos = new Application_Model_DbTable_Mercaderia();
          $this->view->response = $datos->getMercaderia3( $mercaderia );
        }
        else if ( $this->getRequest()->getPost('id_mercaderia') != null ){
          $precio = $this->getRequest()->getPost('id_mercaderia');
          $fecha = date('Y-m-d h:i:s');
          $datos = new Application_Model_DbTable_Historialmercaderia();
          $this->view->response = $datos->getLastPrecio( $precio, $fecha );
        }
      }
  }

  // EFECTIVO
  private function pago_efectivo($formData){
    $fecha = date('Y/m/d H:i:s');
    //buscar id del cliente
    $id_cliente = "1";
    //buscar id del credito
    $id_credito = "1";
    //talonario boleta, buscar por local y num boleta
    $id_talonario = "1";
    //entrega de ventas default
    $id_entrega_venta = "1";
    //guardar venta
    $ventaArr = array(
                    $id_cliente,
                    $id_credito,
                    $id_talonario,
                    $id_entrega_venta,
                    $fecha,
                    $formData['f_total_final']
                    );
    $venta= new Application_Model_DbTable_Venta();
    $id_venta = $venta->addVenta($ventaArr);
    //guardar descuento has venta
    $descuentohasventa = new Application_Model_DbTable_Descuentohasventa();
    $descuentohasventaArr = array(
                    $formData['des_id_descuento'], //$id_descuento,
                    $id_venta,
                    $formData['f_total_final']
                    );
    $descuentohasventa->addDescuentohasventa($descuentohasventaArr);
    //guardar tipo de pago has venta
    $tipopagohasventa = new Application_Model_DbTable_Tipopagohasventa();
    $tipopagohasventaArr = array(
                    $formData['tip_id_tipo_pago'],  //tip_id_tipo_pago
                    $id_venta,                      //ven_id_venta
                    $formData['f_total_final'],     //tphv_monto
                    "",                            //tphv_codigo_cheque
                    "",                            //tphv_cant_cuotas
                    ""                             //tphv_observacion_smo
                    );
    $tipopagohasventa->addTipopagohasventa($tipopagohasventaArr);
    //guardar comisiones (cajero + vendedor) en venta has usuario
    $ventahasusuario = new Application_Model_DbTable_Ventahasusuario();
    $usuario = new Application_Model_DbTable_Usuarios();
    $usuarioData = $usuario->getUsuario($formData['usu_id_usuario']);
    $perfil = new Application_Model_DbTable_UsuarioHasPerfil();
    $perfilData = $perfil->perfilesUsuario($formData['usu_id_usuario'])->toArray();
    $ventahasusuarioArrVendedor = array(    //vendedor
                    $id_venta,
                    $formData['usu_id_usuario'],
                    $formData['f_total_final'] * ( $usuarioData['usu_porcentaje_comision']/100), //monto comision
                    $perfilData[0]['per_id_perfil'] //vhu_tipo_perfil = 7: vendedor fijo / 8: vendedor auxiliar
                    );
    $ventahasusuario->addVentahasusuario($ventahasusuarioArrVendedor);
    $usuarioInfo = Zend_Auth::getInstance()->getStorage()->read();
    $perfilDataCajero = $perfil->perfilesUsuario($usuarioInfo->usu_id_usuario)->toArray();
    $ventahasusuarioArrCajero = array(    //cajero
                    $id_venta,
                    $usuarioInfo->usu_id_usuario,
                    $formData['f_total_final'] * ( $usuarioInfo->usu_porcentaje_comision/100), //monto comision
                    $perfilDataCajero[0]['per_id_perfil'] //vhu_tipo_perfil = 7: vendedor fijo / 8: vendedor auxiliar
                    );
    $ventahasusuario->addVentahasusuario($ventahasusuarioArrCajero);
    //guardar historial venta
    //no se ha devuelto nada, por lo tanto historial venta = null
    //guardar venta has inventario
    $inventario = new Application_Model_DbTable_Inventario();
    $ventahasinventario = new Application_Model_DbTable_Ventahasinventario();
    $mercaderia = new Application_Model_DbTable_Mercaderia();
    $bodega = new Application_Model_DbTable_Bodega();
    $historialInventario = new Application_Model_DbTable_Historialinventario();
    $filaInventario = explode('-',$formData['stringMercanciaInput']);
    $glosaInventario = new Application_Model_DbTable_Glosainventario();
// stringMercanciaInput	|58001|2|34|44000|-|58001|2|34|44000|
    for($i=0;$i<count($filaInventario);$i++){
      $filaInventario[$i] = explode('|',$filaInventario[$i]);
      $nomBodega = "Bodega ".$formData['loc_nombre'];
      $inventarioArr= $inventario->getInventarioPorFiltro($nomBodega, $filaInventario[$i][1], $filaInventario[$i][3]);
      $id_inventario = $inventarioArr[0]['inv_id_inventario'];
      $mercaderiaArr= $mercaderia->getMercaderia3($filaInventario[$i][1])->toArray();
      $id_mercaderia = $mercaderiaArr[0]['mer_id_mercaderia'];
      $bodegaArr= $bodega->getBodega2($nomBodega);
      $id_bodega = $bodegaArr['bod_id_bodega'];
      $ventahasinventarioArr = array(
                    $id_inventario,
                    $id_venta,
                    $filaInventario[$i][4],
                    $filaInventario[$i][2]
                    );
      $ventahasinventario->addVentahasinventario($ventahasinventarioArr);
    //guardar historial inventario
      $glosaInventarioArr= $glosaInventario->getGlosainventario("Venta");
      $historialLast= $historialInventario->getLastHistorialinventario($id_inventario);
      $newHiiTotal = $historialLast[0]['hii_total']-$filaInventario[$i][2]; //resta del ultimo historial menos la venta
      $historialInventarioArr = array(
      'inv_id_inventario'       => $id_inventario,
      'ghi_id_glosa_inventario' => $glosaInventarioArr['ghi_id_glosa_inventario'],
      'hii_entrada'             => '0',
      'hii_salida'              => $filaInventario[$i][2],
      'hii_total'               => $newHiiTotal,
      'hii_fecha'               => $fecha,
      'hii_descripcion'         => 
                                'Venta de mercadería ('.$filaInventario[$i][1].'|'.$filaInventario[$i][2].'|'.
                                $filaInventario[$i][3].'|'.$filaInventario[$i][4].'), cantidad: '.$filaInventario[$i][2].
                                ' del inventario: '.$id_inventario.' por Usuario:'.$usuarioInfo->usu_rut,
      'hii_id_padre_historia'   => $historialLast[0]['hii_id_historial_inventario']
      );
      $historialInventario->addHistorialinventario($historialInventarioArr);

      //guardar inventario
      if($newHiiTotal == '0'){
        $newInvEstado = '3';  //depleted
      }else if($newHiiTotal <= 0){
        $newInvEstado = '5';  //error
      }else{
        $newInvEstado = '1';  //disponible
      }
      $inventarioArr2 = array(
            'inv_id_inventario'       => $id_inventario,
            'mer_id_mercaderia'       => $id_mercaderia,
            'bod_id_bodega'           => $id_bodega,
            'inve_id_inv_estado'      => $newInvEstado, //Disponible si historial inventario cantidad !=0
            'cjt_id_caja_tarea'       => "1", //cuando este defragmentada, caja tarea = 1 o no?
            'inv_cantidad'            => $newHiiTotal, //valor que viene desde historial inventario
            'inv_fecha'               => $fecha
            );
            var_dump($inventarioArr2);
      //$inventario->updateInventario($inventarioArr2);
    }

  }

  //REDCOMPRA
  private function pago_redcompra($formData){
    pago_efectivo($formData);
  }
  //CHEQUE
  private function pago_cheque($formData){
    var_dump($formData);
  }
  //SMO CREDITO
  private function pago_credito($formData){
    var_dump($formData);
  }
  //ABONO
  private function pago_abono($formData){
    var_dump($formData);
  }
  //TARJETA CREDITO TRANKBANK
  private function pago_tarjeta_1($formData){
    var_dump($formData);
  }
  //TARJETA CREDITO ABCDIN
  private function pago_tarjeta_2($formData){
    var_dump($formData);
  }
  //TARJETA CREDITO PRESTO
  private function pago_tarjeta_3($formData){
    var_dump($formData);
  }
}