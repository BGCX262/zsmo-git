<?php

class TransaccionController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
      $transacciones = new Application_Model_DbTable_Transaccion();
      $transaccionesArr = $transacciones->getDetalleTransaccion();
      for($i=0;$i<count($transaccionesArr); $i++){
        if($transaccionesArr[$i]['tra_tipo_motivo'] == "Desde Proveedor (Borrador)"){
          $transaccionesArr[$i]['acciones']= 
           '<button class="btn btn-info btn-mini" title="Detalles" onclick="verPdf('.$transaccionesArr[$i]['tra_id_transaccion'].',1)"><i class="icon-search icon-white"></i></button>
            <button class="btn btn-primary btn-mini" title="Terminar"><i class="icon-ok icon-white"></i></button>
            <button class="btn btn-danger btn-mini" title="Descartar"><i class="icon-remove icon-white"></i></button>';
        }
        else if ($transaccionesArr[$i]['tra_tipo_motivo'] == "Desde Proveedor"){
          $transaccionesArr[$i]['acciones']=
           '<button class="btn btn-info btn-mini" title="Detalles" onclick="verPdf('.$transaccionesArr[$i]['tra_id_transaccion'].',2)"><i class="icon-search icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Terminar"><i class="icon-ok icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Descartar"><i class="icon-remove icon-white"></i></button>';
        }
        else if ($transaccionesArr[$i]['tra_tipo_motivo'] == "Hacia Local (En Tránsito)"){
          $transaccionesArr[$i]['acciones']=
           '<button class="btn btn-info btn-mini" title="Detalles" onclick="verPdf('.$transaccionesArr[$i]['tra_id_transaccion'].',2)"><i class="icon-search icon-white"></i></button>
            <button class="btn btn-primary btn-mini" title="Terminar" onclick="aceptarTransaccion('.$transaccionesArr[$i]['tra_id_transaccion'].')"><i class="icon-ok icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Descartar"><i class="icon-remove icon-white"></i></button>';
        }
        else if ($transaccionesArr[$i]['tra_tipo_motivo'] == "Hacia Local (Finalizada)"){
          $transaccionesArr[$i]['acciones']=
           '<button class="btn btn-info btn-mini" title="Detalles" onclick="verPdf('.$transaccionesArr[$i]['tra_id_transaccion'].',2)"><i class="icon-search icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Terminar"><i class="icon-ok icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Descartar"><i class="icon-remove icon-white"></i></button>';
        }
        else{
          $transaccionesArr[$i]['acciones']=
           '<button class="btn disabled btn-mini" title="Detalles" onclick="verPdf('.$transaccionesArr[$i]['tra_id_transaccion'].',2)"><i class="icon-search icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Terminar"><i class="icon-ok icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Descartar"><i class="icon-remove icon-white"></i></button>';
        }
      }
      $this->view->transacciones = $transaccionesArr;
    }

    public function ajaxfrombdAction()
    {
      $this->_helper->layout()->disableLayout();
        if ($this->getRequest()->isPost()) {
          if( $this->getRequest()->getPost('destinatario') != null ){
            $destinatario = $this->getRequest()->getPost('destinatario');
            $datos = new Application_Model_DbTable_Destinatario();
            $this->view->response = $datos->getDestinatarioPorId( $destinatario );
          }
          else if ( $this->getRequest()->getPost('mercaderia') != null ){
            $mercaderia = $this->getRequest()->getPost('mercaderia');
            $datos = new Application_Model_DbTable_Mercaderia();
            $this->view->response = $datos->getMercaderia3( $mercaderia );
          }
        }
    }

    public function addfromproveedorAction()
    {
      $form = new Application_Form_Addfromproveedor();
      $this->view->form = $form;
      if ($this->getRequest()->isPost()) {
          $formData = $this->getRequest()->getPost();
          if ($form->isValid($formData)) {

            $bodega= new Application_Model_DbTable_Bodega();
            $cajatarea= new Application_Model_DbTable_Cajatarea();
            $destinatario= new Application_Model_DbTable_Destinatario();
            $documentoPrincipal= new Application_Model_DbTable_Documentoprincipal();
            $docHasDestinatario = new Application_Model_DbTable_Documentohasdestinatario();
            $documentoSecundario = new Application_Model_DbTable_Documentosecundario();
            $inventario = new Application_Model_DbTable_Inventario();
            $investado= new Application_Model_DbTable_Investado();
            $mercaderia= new Application_Model_DbTable_Mercaderia();
            $talla= new Application_Model_DbTable_Talla();
            $transaccion = new Application_Model_DbTable_Transaccion();
            $transporte= new Application_Model_DbTable_Transporte();

            $principalHasSecundario= new Application_Model_DbTable_Principalhassecundario();
            $inventarioHasTransaccion = new Application_Model_DbTable_Inventariohastransaccion();
            $glosaInventario = new Application_Model_DbTable_Glosainventario();
            $historialInventario = new Application_Model_DbTable_Historialinventario();
            
            $usuarioInfo = Zend_Auth::getInstance()->getStorage()->read();
            
            $fecha = date('Y/m/d H:i:s');
            if($formData['tipoSubmit'] == "borrador"){
              $investadoArr = $investado->getInvestado2("Borrador");
            }else{
              $investadoArr = $investado->getInvestado2("Disponible");    //ESTADO DEL INVENTARIO, DEPENDE DEL BOTON DE SUBMIT                
            }
            $id_investado= $investadoArr['inve_id_inv_estado'];
            
            $filaInventario = explode('-',$formData['stringMercanciaInput']);
            //0 => string '',1 => codigo,  2 => cant cajas, 3 al 8 => talla/cantidad, 9 => total mercaderia, 10 => subtotal, 11 => ''
            for($i=0;$i<count($filaInventario);$i++){
              $columnaInventario =  explode('|',$filaInventario[$i]);
              for($j=3,$k=0;$j<9;$j++,$k++){
                $duplaCurva = explode(',',$columnaInventario[$j]);
                $tallaCurva[$k]= $duplaCurva[0];                // TALLAS
                $cantidadCurva[$k]= $duplaCurva[1];             // CANTIDAD
                if($k==0){
                  $curva= $duplaCurva[1];
                }
                else{
                  $curva.= ','.$duplaCurva[1];
                }
              }
              $columnaInventario['curva'] = $curva;
              $columnaInventario['completa'] = "1" ; //1 COMPLETA, 0 INCOMPLETA
              for($h=0;$h<$columnaInventario[2];$h++){    // cantidad de cajas
                $id_cajaTarea[$h] = $cajatarea->addCajatarea($columnaInventario);      // CAJA TAREA OK ! >cada caja es una distinta fisicamente
              }
              
              $bodegaArr = $bodega->getBodega2("Bodega Principal");    // BODEGA, depende del destinatario
              $id_bodega = $bodegaArr['bod_id_bodega'];

              $glosaInventarioArr = $glosaInventario->getGlosainventario("Transacción Desde Proveedor");
              
              for($k=0;$k<6 && $tallaCurva[$k] !='0' ;$k++){
                $tallaArr = $talla->getTalla2($tallaCurva[$k]);
                $mercaderiaArr = $mercaderia->getMercaderia4($columnaInventario[1],$tallaArr['tal_id_talla']); // get MERCADERIA, depende del codigoSMO y talla
                $id_mercaderia = $mercaderiaArr[0]['mer_id_mercaderia'];
                    //var_dump($mercaderiaArr);
                  for($h=0;$h<$columnaInventario[2];$h++){    // cantidad de cajas
                    $inventarioArr = array(
                        "mer_id_mercaderia"   => $id_mercaderia,
                        "bod_id_bodega"       => $id_bodega,
                        "inve_id_inv_estado"  => $id_investado,
                        "cjt_id_caja_tarea"   => $id_cajaTarea[$h],
                        "inv_cantidad"        => $cantidadCurva[$k],
                        "inv_fecha"           => $fecha);
                    $id_inventario= $inventario->addInventario($inventarioArr);       // AGREGA INVENTARIO
                    //$indice =($k+1) + ( ($k+1)*($h+1) );
                    $indice = $k + (6*$h);
                    $id_inventarioArr[$indice]= $id_inventario;

                    $historialInventarioArr = array(
                    'inv_id_inventario'       => $id_inventario,
                    'ghi_id_glosa_inventario' => $glosaInventarioArr['ghi_id_glosa_inventario'],
                    'hii_entrada'             => $cantidadCurva[$k],
                    'hii_salida'              => '0',
                    'hii_total'               => $cantidadCurva[$k],
                    'hii_fecha'               => $fecha,
                    'hii_descripcion'         => 'Transacción inicial, cantidad: '.$cantidadCurva[$k].' del inventario: '.$id_inventario.' por Usuario:'.$usuarioInfo->usu_rut,
                    'hii_id_padre_historia'   => null
                    );
                    $historialInventario->addHistorialinventario($historialInventarioArr);                     // HISTORIAL INVENTARIO

                  }
                }
            }
            
            if($formData['dop_numero_identificador'] == '' && $formData['dos_numero_identificador'] != '' ){   //seleccionar tipo de documento principal  = factura/guiadespacho
              $formData['dop_tipo_doc_principal'] = "Guía de Despacho";
              $formData['dop_numero_identificador'] = $formData['dos_numero_identificador'];
              $formData['dos_numero_identificador'] = "";
            }else if ($formData['dop_numero_identificador'] != '' && $formData['dos_numero_identificador'] == ''){
              $formData['dop_tipo_doc_principal'] = "Factura";
            }else if ($formData['dop_numero_identificador'] != '' && $formData['dos_numero_identificador'] != ''){
              $formData['dop_tipo_doc_principal'] = "Factura";
            }else{
              $formData['dop_tipo_doc_principal'] = "No disponible";
            }
            $formData['fecha'] = $fecha;
            $destinatarioArr2 = $destinatario->getDestinatarioPorId( $formData['des_id_destinatario'] );   //get nombre de salida
            $destinatarioArr2->toArray();
            $formData['des_salida'] = $destinatarioArr2[0]['des_nombre'];
            $destinatarioArr = $destinatario->getDestinatarioPorId( $formData['des_id_destinatario2'] );  //get nombre de llegada
            $destinatarioArr->toArray();
            $formData['dop_empresa_relacionada'] = $destinatarioArr[0]['des_nombre'];
            $formData['dop_tipo_motivo'] = "Entrega";
            $id_docPrincipal= $documentoPrincipal->addDocumentoPrincipal($formData);    // AGREGA DOCUMENTO PRINCIPAL

            $time_salida  = DateTime::createFromFormat('d/m/Y', $formData['ctr_fecha_salida'])->format('Y/m/d H:i:s');
            $time_llegada = DateTime::createFromFormat('d/m/Y', $formData['ctr_fecha_llegada'])->format('Y/m/d H:i:s');
            $formData['ctr_fecha_salida'] = $time_salida;
            $formData['ctr_fecha_llegada'] = $time_llegada;
            $id_transporte= $transporte->addTransporte($formData);                                       // AGREGA TRANSPORTE

//ORIGEN
            $docHasDestinatarioArr['dop_id_documento_principal'] = $id_docPrincipal;
            $docHasDestinatarioArr['des_id_destinatario'] = $formData['des_id_destinatario'];
            $docHasDestinatarioArr['dhdo_tipo'] = 'Origen';
            $docHasDestinatario->addDocumentohasdestinatario($docHasDestinatarioArr);
//DESTINATARIO
            $docHasDestinatarioArr['des_id_destinatario'] = $formData['des_id_destinatario2'];
            $docHasDestinatarioArr['dhdo_tipo'] = 'Destinatario';
            $docHasDestinatario->addDocumentohasdestinatario($docHasDestinatarioArr);     // DOCUMENTO HAS DESTINATARIO

            if($formData['dos_numero_identificador'] !=''){
              $id_docSecundario = $documentoSecundario->addDocumentoSecundario( $formData['dos_numero_identificador'] );  // DOCUMENTO SECUNDARIO  >>> TEST DESDE ACA
              $prinHasSecArr['id_principal'] = $id_docPrincipal;
              $prinHasSecArr['id_secundario'] = $id_docSecundario;
              $principalHasSecundario->addPrincipalHasSecundario($prinHasSecArr) ;                                    // DOCUMENTO PRINCIPAL HAS SECUNDARIO
            }
            
            if($formData['tipoSubmit'] == "borrador"){
              $formData['tra_tipo_motivo'] = "Desde Proveedor (Borrador)";                            
            }else{
              $formData['tra_tipo_motivo'] = "Desde Proveedor";              
            }
            
            $transaccionArr = array(
            'dop_id_documento_principal'    => $id_docPrincipal,
            'ctr_id_transporte'             => $id_transporte,
            'tra_tipo_motivo'               => $formData['tra_tipo_motivo'],
            'tra_fecha_inicio'              => $fecha,
            'tra_fecha_finalizacion'        => $fecha,
            'tra_rut_autorizado_inicio'     => $usuarioInfo->usu_rut,
            'tra_rut_autorizado_final'      => $usuarioInfo->usu_rut,
            'tra_tipo'                      => $formData['dop_tipo_doc_principal'],
            'tra_raw_mercaderia_input'      => $formData['stringMercanciaInput'] );
            $id_transaccion = $transaccion->addTransaccion($transaccionArr);                                // TRANSACCION
            
            for($i=0; $i<count($id_inventarioArr); $i++){
            $invHasTransaccionArr = array(
            'tra_id_transaccion'     => $id_transaccion,
            'inv_id_inventario'      => $id_inventarioArr[$i],
            'iht_fecha'              => $fecha );
            $inventarioHasTransaccion->addInventariohastransaccion($invHasTransaccionArr) ;                 // INVENTARIO HAS TRANSACCION
            }
          
            //$this->_helper->redirector('index');
            $returnUrl = $form->getElement('returnUrl')->getValue();
            if (!empty($returnUrl)) {
              $this->_helper->getHelper('Redirector')->setGotoUrl($returnUrl);
            }
            
          } else {
              $form->populate($formData);
          }
      }
    }

    public function addtolocalAction()
    {
      $form = new Application_Form_Addtolocal();
      $this->view->form = $form;
      if ($this->getRequest()->isPost()) {
          $formData = $this->getRequest()->getPost();
          if ($form->isValid($formData)) {

            $cajatarea= new Application_Model_DbTable_Cajatarea();
            $destinatario= new Application_Model_DbTable_Destinatario();
            $documentoPrincipal= new Application_Model_DbTable_Documentoprincipal();
            $docHasDestinatario = new Application_Model_DbTable_Documentohasdestinatario();
            $documentoSecundario = new Application_Model_DbTable_Documentosecundario();
            $inventario = new Application_Model_DbTable_Inventario();
            $investado= new Application_Model_DbTable_Investado();
            $transaccion = new Application_Model_DbTable_Transaccion();
            $transporte= new Application_Model_DbTable_Transporte();
            $principalHasSecundario= new Application_Model_DbTable_Principalhassecundario();
            $inventarioHasTransaccion = new Application_Model_DbTable_Inventariohastransaccion();
            $usuarioInfo = Zend_Auth::getInstance()->getStorage()->read();
            
            $fecha = date('Y/m/d H:i:s');
            if($formData['tipoSubmit'] == "procesar"){$investadoArr = $investado->getInvestado2("En tránsito");}
            $id_investado= $investadoArr['inve_id_inv_estado'];
            
//MANEJO DE INVENTARIO, SE DIVIDE EN FILAS CON LOS DATOS DE LA GUIA DESPACHO/FACTURA
            $filaInventario = explode('-',$formData['stringMercanciaInput']);
//INDICES DE LA FILA DE INVENTARIO: 0 => string '',1 => codigo,  2 => cant. cajas, 3 al 8 => talla/cantidad, 9 => total mercaderia, 10 => subtotal, 11 => ''
            for($i=0;$i<count($filaInventario);$i++){
              $columnaInventario =  explode('|',$filaInventario[$i]);     // SEPARA LAS COLUMNAS 3 a 8, que contienen TALLA/CANTIDAD
              for($j=3,$k=0;$j<9;$j++,$k++){
                $duplaCurva = explode(',',$columnaInventario[$j]);
                $tallaCurva[$k]= $duplaCurva[0];                // ARREGLO DE TALLAS
                $cantidadCurva[$k]= $duplaCurva[1];             // ARREGLO DE CANTIDAD
                if($k==0){
                  $curva= $duplaCurva[1];
                }
                else{
                  $curva.= ','.$duplaCurva[1];
                }
              }
              $columnaInventario['curva'] = $curva;
              $columnaInventario['completa'] = "1" ; //1 COMPLETA, 0 INCOMPLETA
//No se agrega una caja nueva, ya que existe en la bodega principal,
//hay que buscar si existe la cantidad de cajas de la mercaderia a enviar a local, 
//solamente son cuando el estado de inventario es 'disponible'
//buscar lista de Cajas para MARCAR COMO 'EN TRANSITO'
              $listaCajasInv = $cajatarea->getListaCajatarea($columnaInventario['1'], "Bodega Principal", "Disponible", "1") ;
              $cantCajasInv = count( $listaCajasInv );
              if($columnaInventario[2] <= $cantCajasInv){   //si se pide menos que la cant de cajas disponibles en bodega
                for($h=0;$h<$columnaInventario[2];$h++){    // cantidad de cajas pedidas
//buscar lista de inventario para cada caja a MARCAR COMO 'EN TRANSITO', ordenar por id de caja ASC
                  $listaInventarioPorCaja = $inventario->getListaPorCajaInventario($listaCajasInv[$h]['cjt_id_caja_tarea'], "Bodega Principal", "Disponible", "1");
                  for($g=0;$g<count($listaInventarioPorCaja);$g++){ //Para cada talla de la caja
                    $listaInventarioPorCaja[$g]['inve_id_inv_estado']= $id_investado;
                    $listaInventarioPorCaja[$g]['inv_fecha']= $fecha;
                    $id_inventario = $inventario->updateInventario($listaInventarioPorCaja[$g]);       // EDITA INVENTARIO, ESTADO = 'en transito'
                    $indice = $g + (6*$h);
                    $id_inventarioArr[$indice]= $id_inventario;
                  }
                }
              }
            }
            
            if($formData['dop_numero_identificador'] == '' && $formData['dos_numero_identificador'] != '' ){   //seleccionar tipo de documento principal  = factura/guiadespacho
              $formData['dop_tipo_doc_principal'] = "Guía de Despacho";
              $formData['dop_numero_identificador'] = $formData['dos_numero_identificador'];
              $formData['dos_numero_identificador'] = "";
            }else if ($formData['dop_numero_identificador'] != '' && $formData['dos_numero_identificador'] == ''){
              $formData['dop_tipo_doc_principal'] = "Factura";
            }else if ($formData['dop_numero_identificador'] != '' && $formData['dos_numero_identificador'] != ''){
              $formData['dop_tipo_doc_principal'] = "Factura";
            }else{
              $formData['dop_tipo_doc_principal'] = "No disponible";
            }
            $formData['fecha'] = $fecha;
            $destinatarioArr2 = $destinatario->getDestinatarioPorId( $formData['des_id_destinatario'] );   //get nombre de salida
            $destinatarioArr2->toArray();
            $formData['des_salida'] = $destinatarioArr2[0]['des_nombre'];
            $destinatarioArr = $destinatario->getDestinatarioPorId( $formData['des_id_destinatario2'] );  //get nombre de llegada
            $destinatarioArr->toArray();
            $formData['dop_empresa_relacionada'] = $destinatarioArr[0]['des_nombre'];
            $formData['dop_tipo_motivo'] = "Entrega";
            $id_docPrincipal= $documentoPrincipal->addDocumentoPrincipal($formData);    // AGREGA DOCUMENTO PRINCIPAL

            $time_salida  = $fecha;
            $time_llegada = DateTime::createFromFormat('d/m/Y', '01/01/2000')->format('Y/m/d H:i:s');   //AL INGRESAR AUN NO SE SABE LA FECHA DE LLEGADA
            $formData['ctr_fecha_salida'] = $time_salida;
            $formData['ctr_fecha_llegada'] = $time_llegada;
            $id_transporte= $transporte->addTransporte($formData);                                       // AGREGA TRANSPORTE

//ORIGEN
            $docHasDestinatarioArr['dop_id_documento_principal'] = $id_docPrincipal;
            $docHasDestinatarioArr['des_id_destinatario'] = $formData['des_id_destinatario'];
            $docHasDestinatarioArr['dhdo_tipo'] = 'Origen';
            $docHasDestinatario->addDocumentohasdestinatario($docHasDestinatarioArr);     // DOCUMENTO HAS DESTINATARIO (Origen)
//DESTINATARIO
            $docHasDestinatarioArr['des_id_destinatario'] = $formData['des_id_destinatario2'];
            $docHasDestinatarioArr['dhdo_tipo'] = 'Destinatario';
            $docHasDestinatario->addDocumentohasdestinatario($docHasDestinatarioArr);     // DOCUMENTO HAS DESTINATARIO (Destinatario/llegada)

            if($formData['dos_numero_identificador'] !=''){
              $id_docSecundario = $documentoSecundario->addDocumentoSecundario( $formData['dos_numero_identificador'] );  // DOCUMENTO SECUNDARIO
              $prinHasSecArr['id_principal'] = $id_docPrincipal;
              $prinHasSecArr['id_secundario'] = $id_docSecundario;
              $principalHasSecundario->addPrincipalHasSecundario($prinHasSecArr) ;                                    // DOCUMENTO PRINCIPAL HAS SECUNDARIO
            }
            
            if($formData['tipoSubmit'] == "procesar"){
              $formData['tra_tipo_motivo'] = "Hacia Local (En Tránsito)";                            
            }else if($formData['tipoSubmit'] == "finalizar") {
              $formData['tra_tipo_motivo'] = "Hacia Local (Finalizada)";              
            }
            
            $transaccionArr = array(
            'dop_id_documento_principal'    => $id_docPrincipal,
            'ctr_id_transporte'             => $id_transporte,
            'tra_tipo_motivo'               => $formData['tra_tipo_motivo'],
            'tra_fecha_inicio'              => $fecha,
            'tra_fecha_finalizacion'        => '',
            'tra_rut_autorizado_inicio'     => $usuarioInfo->usu_rut,
            'tra_rut_autorizado_final'      => '',
            'tra_tipo'                      => $formData['dop_tipo_doc_principal'],
            'tra_raw_mercaderia_input'      => $formData['stringMercanciaInput'] );
            $id_transaccion = $transaccion->addTransaccion($transaccionArr);                                // TRANSACCION
            
            for($i=0; $i<count($id_inventarioArr); $i++){
            $invHasTransaccionArr = array(
            'tra_id_transaccion'     => $id_transaccion,
            'inv_id_inventario'      => $id_inventarioArr[$i],
            'iht_fecha'              => $fecha );
            $inventarioHasTransaccion->addInventariohastransaccion($invHasTransaccionArr) ;                 // INVENTARIO HAS TRANSACCION
            }
          
            //$this->_helper->redirector('index');
            $returnUrl = $form->getElement('returnUrl')->getValue();
            if (!empty($returnUrl)) {
              $this->_helper->getHelper('Redirector')->setGotoUrl($returnUrl);
            }
            
          } else {
              $form->populate($formData);
          }
      }
    }

  public function addtoclienteAction()
  {
      // action body
  }

  public function addfromlocalAction()
  {
      // action body
  }

  public function editAction()
  {
      // action body
  }

  public function deleteAction()
  {
      // action body
  }

    public function indexlocalAction()
    {
      $transacciones = new Application_Model_DbTable_Transaccion();
      $transaccionesArr = $transacciones->getDetalleTransaccion2();
      for($i=0;$i<count($transaccionesArr); $i++){
        if ($transaccionesArr[$i]['tra_tipo_motivo'] == "Hacia Local (En Tránsito)"){
          $transaccionesArr[$i]['acciones']=
           '<button class="btn btn-info btn-mini" title="Detalles" onclick="verPdf('.$transaccionesArr[$i]['tra_id_transaccion'].',2)"><i class="icon-search icon-white"></i></button>
            <button class="btn btn-primary btn-mini" title="Terminar" onclick="aceptarTransaccion('.$transaccionesArr[$i]['tra_id_transaccion'].')"><i class="icon-ok icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Descartar"><i class="icon-remove icon-white"></i></button>';
        }
        else if ($transaccionesArr[$i]['tra_tipo_motivo'] == "Hacia Local (Finalizada)"){
          $transaccionesArr[$i]['acciones']=
           '<button class="btn btn-info btn-mini" title="Detalles" onclick="verPdf('.$transaccionesArr[$i]['tra_id_transaccion'].',2)"><i class="icon-search icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Terminar"><i class="icon-ok icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Descartar"><i class="icon-remove icon-white"></i></button>';
        }
        else{
          $transaccionesArr[$i]['acciones']=
           '<button class="btn disabled btn-mini" title="Detalles"><i class="icon-search icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Terminar"><i class="icon-ok icon-white"></i></button>
            <button class="btn disabled btn-mini" title="Descartar"><i class="icon-remove icon-white"></i></button>';
        }
      }
      $this->view->transacciones = $transaccionesArr;
    }

  public function aceptartransaccionAction()
  {
    $this->view->test= $this->getRequest()->getParams();
    $form = new Application_Form_Addtolocal();
    $this->view->form = $form;
    if ($this->getRequest()->isPost()) {
      $formData = $this->getRequest()->getPost();
      if ($form->isValid($formData)) {
        $aceptar = $this->getRequest()->getPost('aceptar');
        if ($aceptar == 'Confirmar') {      // SI SE CONFIRMA LA TRANSACCION
          $transaccion =              new Application_Model_DbTable_Transaccion();
          $bodega=                    new Application_Model_DbTable_Bodega();
          $documentoPrincipal=        new Application_Model_DbTable_Documentoprincipal();
          $glosaInventario =            new Application_Model_DbTable_Glosainventario();
          $historialInventario =        new Application_Model_DbTable_Historialinventario();
          $inventario =               new Application_Model_DbTable_Inventario();
          $investado=                 new Application_Model_DbTable_Investado();
          $transporte=                new Application_Model_DbTable_Transporte();

          $usuarioInfo =              Zend_Auth::getInstance()->getStorage()->read();
          $fecha =                    date('Y/m/d H:i:s'); 
          $id_transaccion = $formData['id_transaccion'];
                    
          $transaccionArr = $transaccion->getTransaccion($id_transaccion);            //get datos de transaccion
          $inventarioArr = $inventario->getInventario2($id_transaccion);              //get arreglos/matriz con inventarios,pasando por transaccion has inventario
          $documentoPrincipalArr = $documentoPrincipal->getDocumentoprincipal( $transaccionArr['dop_id_documento_principal'] );  //get documento principal
          $transporteArr = $transporte->getTransporte($transaccionArr['ctr_id_transporte']);               //get transporte
         
          for($i=0;$i<$inventarioArr;$i++){
            //* * * ADD HISTORIAL INVENTARIO, CAMBIO A LOCAL => INVENTARIO = 0
            $glosaInventarioArr = $glosaInventario->getGlosainventario("Transacción Hacia Local");
            $lastHistorial = $historialInventario->getLastHistorialinventario($inventarioArr[$i]['inv_id_inventario']); //busca al ultimo hijo del historial del inventario,
            //$lastHistorial-> [0]['hii_id_historial_inventario']/inv_id_inventario/ghi_id_glosa_inventario/ hii_entrada/hii_salida/hii_total/hii_fecha/ hii_descripcion/hii_id_padre_historia
            $resultadoInventarioArr = array(
                "inv_id_inventario"         => $inventarioArr[$i]['inv_id_inventario'],
                "ghi_id_glosa_inventario"   => $glosaInventarioArr['ghi_id_glosa_inventario'],
                "hii_entrada"               => '0',
                "hii_salida"                => $lastHistorial[0]['hii_total'],
                "hii_total"                 => '0',
                "hii_fecha"                 => $fecha,
                "hii_descripcion"           => 'Transacción hacia Local, cantidad: '.$lastHistorial[0]['hii_total'].
                ' del inventario: '.$inventarioArr[$i]['inv_id_inventario'].' por Usuario: '.$usuarioInfo->usu_rut,
                "hii_id_padre_historia"     => $lastHistorial[0]['hii_id_historial_inventario']);
            $nuevo_id_historial_inventario = $historialInventario->addHistorialInventario( $resultadoInventarioArr );  //se ingresa nuevo historial donde el padre es el id sacado, inventario=0

            //* * * ADD INVENTARIO NUEVO, CAMBIO A LOCAL
            $nuevoInventarioArr = $inventarioArr[$i];
            $nuevoInventarioArr['bod_id_bodega'] = $bodega->getBodega2($documentoPrincipalArr['dop_nom_des_llegada']);
            $nuevoInventarioArr['inve_id_inv_estado'] = $investado->getInvestado2("Disponible");
            $nuevoInventarioArr['inv_fecha'] = $fecha ;
            $nuevo_id_inventario= $inventario->addInventario($nuevoInventarioArr[$i]);

            //* * * ADD HISTORIAL INVENTARIO, CAMBIO A LOCAL => INVENTARIO = LAST BODEGA
            $glosaInventarioArr2 = $glosaInventario->getGlosainventario("Transacción desde Bodega Principal");
            $lastHistorialArr = $historialInventario->getLastHistorialinventario($inventarioArr[$i]['inv_id_inventario']); //busca al ultimo hijo del historial del inventario,
            //$lastHistorial-> [0]['hii_id_historial_inventario']/inv_id_inventario/ghi_id_glosa_inventario/ hii_entrada/hii_salida/hii_total/hii_fecha/ hii_descripcion/hii_id_padre_historia
            $resultadoInventarioArr2 = array(
                "inv_id_inventario"         => $nuevo_id_inventario,
                "ghi_id_glosa_inventario"   => $glosaInventarioArr2['ghi_id_glosa_inventario'],
                "hii_entrada"               => $lastHistorialArr[0]['hii_total'],
                "hii_salida"                => '0',
                "hii_total"                 => $lastHistorialArr[0]['hii_total'],
                "hii_fecha"                 => $fecha,
                "hii_descripcion"           => 'Transacción desde Bodega Principal, cantidad: '.$lastHistorialArr[0]['hii_total'].
                ' del inventario: '.$inventarioArr[$i]['inv_id_inventario'].' por Usuario: '.$usuarioInfo->usu_rut,
                "hii_id_padre_historia"     => $nuevo_id_historial_inventario);
            $historialInventario->addHistorialInventario( $resultadoInventarioArr2 );  //se ingresa nuevo historial donde el padre es el id sacado, inventario=0

            //* * * UPDATE INVENTARIO VIEJO, CAMBIO A LOCAL => INVENTARIO = 0
            $inventarioArr[$i]['inv_cantidad']= '0';
            $inventarioArr[$i]['inve_id_inv_estado']= $investado->getInvestado2("Depletado");
            $inventarioArr[$i]['inv_fecha']= $fecha;
            $inventario->updateInventario($inventarioArr[$i]);       // EDITA INVENTARIO, CANTIDAD = 0
          }
        //* * * TRANSACCION ESTADO = HACIA LOCAL (FINALIZADA)
        $transaccionArr['tra_tipo_motivo'] = 'Hacia Local (Finalizada)';
        $transaccionArr['tra_fecha_finalizacion'] = $fecha;
        $transaccionArr['tra_rut_autorizado_final'] = $usuarioInfo->usu_rut;
        $transaccion->updateTransaccion($transaccionArr);
        
        }else if($aceptar == 'Rechazar'){   // SI SE RECHAZA LA TRANSACCION

        }
      //* * * ACTUALIZA FECHA DE LLEGADA EN TRANSPORTE
      $transporteArr['ctr_fecha_llegada'] = $fecha;
      $transporte->updateTransporte($transporteArr);
      } else {
        $form->populate($formData);
      }
    }
  }
  
    public function imprimirAction(){
        $id = $this->_getParam('id', 0);
        $borrador = $this->_getParam('borrador', 0);
        
        $objTrans   = new Application_Model_DbTable_Transaccion();
        $objDocPri  = new Application_Model_DbTable_Documentoprincipal();
        $objDocDest = new Application_Model_DbTable_Documentohasdestinatario();
        $objDest    = new Application_Model_DbTable_Destinatario();
        $objTraInv  = new Application_Model_DbTable_Inventariohastransaccion();
        $objInv     = new Application_Model_DbTable_Inventario();
        $objMer     = new Application_Model_DbTable_Mercaderia();
        $objTalla   = new Application_Model_DbTable_Talla();
        $objTrpo    = new Application_Model_DbTable_Transporte();
        $objPrSec   = new Application_Model_DbTable_Principalhassecundario();
        $objDos     = new Application_Model_DbTable_Documentosecundario();
        $objCol     = new Application_Model_DbTable_Color();
        
        $arr_trans      = $objTrans->getTransaccion($id);
        $arr_doc_pri    = $objDocPri->getDocumentoprincipal($arr_trans['dop_id_documento_principal']);
        $arr_doc_dest   = $objDocDest->listarRegistros($arr_doc_pri['dop_id_documento_principal']);
        $arr_transporte = $objTrpo->getTransporte($arr_trans['ctr_id_transporte']);
        
        $arr_doc_pri['dop_fecha'] = $this->getDescFecha(substr(new Zend_Date($arr_doc_pri['dop_fecha'], 'dd/MM/YYYY'),0,10));
        
        $arr_transporte['ctr_fecha_salida'] = $this->getDescFecha(substr(new Zend_Date($arr_transporte['ctr_fecha_salida'], 'dd/MM/YYYY'),0,10));
        $arr_transporte['ctr_fecha_llegada'] = $this->getDescFecha(substr(new Zend_Date($arr_transporte['ctr_fecha_llegada'], 'dd/MM/YYYY'),0,10));
        
        foreach($arr_doc_dest as $doc_dest){
            $arr    = $objDest->getDestinatario($doc_dest->des_id_destinatario);
            
            if(strtolower($doc_dest->dhdo_tipo)=='origen'){
                $arr_dest_origen    = $arr;
            }
            else{
                $arr_dest_llegada    = $arr;
            }
        }
        
        $arr_tran_inv   = $objTraInv->listarRegistros($id);
        
        $arr_det        = array();
        $ind_det        = 0;
        $arr_cod_mer    = array();
        foreach($arr_tran_inv as $tra_inv){
            $arr_inv    = $objInv->getInventario($tra_inv->inv_id_inventario);
            $arr_mer    = $objMer->getMercaderia($arr_inv['mer_id_mercaderia']);
            $arr_tall   = $objTalla->getRegistro($arr_mer['tal_id_talla']);
            
            if(!in_array($arr_mer['mer_codigo'],$arr_cod_mer)){
                
                $arr_color  = $objCol->getColor($arr_mer['col_id_color']);
                
                array_push($arr_cod_mer,$arr_mer['mer_codigo']);
                
                $arr_det[$ind_det]['col_nombre']    = $arr_color['col_nombre'];
                $arr_det[$ind_det]['mer_codigo']    = $arr_mer['mer_codigo'];
                $arr_det[$ind_det]['mer_articulo']  = $arr_mer['mer_articulo'];
                $arr_det[$ind_det]['mer_costo']  = ($arr_mer['mer_costo']*$arr_inv['inv_cantidad']);
                $arr_det[$ind_det]['talla']         = array($arr_tall['tal_talla']);
                $arr_det[$ind_det]['inv_cantidad']  = array($arr_inv['inv_cantidad']);
                $ind_det++;
            }
            else{
                $pos    = array_search($arr_mer['mer_codigo'], $arr_cod_mer);
                array_push($arr_det[$pos]['talla'],$arr_tall['tal_talla']);
                array_push($arr_det[$pos]['inv_cantidad'],$arr_inv['inv_cantidad']);
                
                $arr_det[$pos]['mer_costo']+=($arr_mer['mer_costo']*$arr_inv['inv_cantidad']);
            }
        }
        
        $arr_doc_sec    = $objPrSec->listarRegistros($arr_doc_pri['dop_id_documento_principal']);
        
        foreach($arr_doc_sec as $doc_sec){
            $arr_dos    = $objDos->getDocumentosecundario($doc_sec->dos_id_documento_secundario);
        }
        
        //$arr_doc_pri['monto_neto']  = $arr_doc_pri['dop_monto_total']-$arr_doc_pri['dop_iva'];
        
        if($borrador==1){
            $this->view->borrador   = "BORRADOR";
        }
        else{
            $this->view->borrador   = "";
        }
        
        $this->view->arr_trans      = $arr_trans;
        $this->view->arr_doc_pri    = $arr_doc_pri;
        $this->view->arr_doc_dest   = $arr_doc_dest;
        $this->view->arr_dest_ori   = $arr_dest_origen;
        $this->view->arr_dest_lle   = $arr_dest_llegada;
        $this->view->arr_det        = $arr_det;
        $this->view->arr_transporte = $arr_transporte;
        $this->view->arr_dos        = $arr_dos;
        
        
        ob_start();
		?>
        <page>
		<?php echo $this->view->render('transaccion/imprimirpdf.phtml');?>
        </page>
		<?php
		$html = ob_get_clean();
        
        require_once("html2pdf/html2pdf.class.php");
		$html2pdf	= new HTML2PDF('P', 'CARTA', 'es',true,'ISO-8859-1');
        
        //$html2pdf->RotatedText(35,190,'W a t e r m a r k   d e m o',45);
        $html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->writeHTML($html,false);
        
		$this->view->pdf   = $html2pdf;
		$this->view->render('transaccion/imprimir.phtml');
    }
    
    private function getDescFecha($fecha){
        $arrFecha   = explode("/",$fecha);
        
        switch($arrFecha[1]){
            case 1;
                $mes    = "Enero";
            break;
            case 2;
                $mes    = "Febrero";
            break;
            case 3;
                $mes    = "Marzo";
            break;
            case 4;
                $mes    = "Abril";
            break;
            case 5;
                $mes    = "Mayo";
            break;
            case 6;
                $mes    = "Junio";
            break;
            case 7;
                $mes    = "Julio";
            break;
            case 8;
                $mes    = "Agosto";
            break;
            case 9;
                $mes    = "Septiembre";
            break;
            case 10;
                $mes    = "Octubre";
            break;
            case 11;
                $mes    = "Noviembre";
            break;
            case 12;
                $mes    = "Diciembre";
            break;
            default;
                $mes ="INVALIDO";
            break;
        }
        
        $des_fecha  = $arrFecha[0]." de ".$mes." del ".$arrFecha[2];
        
        return $des_fecha;
    }
  
}


/* VERSION MIX DE "PROCESAR" TRANSACCION Y "FINALIZAR"
 *     public function addtolocalAction()
    {
      $form = new Application_Form_Addtolocal();
      $this->view->form = $form;
      if ($this->getRequest()->isPost()) {
          $formData = $this->getRequest()->getPost();
          if ($form->isValid($formData)) {



            if($formData['tipoSubmit'] == "procesar"){
              $investadoArr = $investado->getInvestado2("En proceso");
            }else{
              $investadoArr = $investado->getInvestado2("Disponible");    //ESTADO DEL INVENTARIO, DEPENDE DEL BOTON DE SUBMIT                
            }
            $id_investado= $investadoArr['inve_id_inv_estado'];
            $glosaInventarioArr = $glosaInventario->getGlosainventario("Transacción Hacia Local");
            $filaInventario = explode('-',$formData['stringMercanciaInput']);

            $bodegaArr = $bodega->getBodega3( $formData['des_id_destinatario'] );    // BODEGA, depende del destinatario
            $id_bodega_principal = $bodegaArr['bod_id_bodega'];
            $bodegaArr2 = $bodega->getBodega3( $formData['des_id_destinatario2'] );    // BODEGA, depende del destinatario
            $id_bodega = $bodegaArr2['bod_id_bodega'];
            
            for($i=0;$i<count($filaInventario);$i++){
              $columnaInventario =  explode('|',$filaInventario[$i]);
              for($j=3,$k=0;$j<9;$j++,$k++){
                $duplaCurva = explode(',',$columnaInventario[$j]);
                $tallaCurva[$k]= $duplaCurva[0];                // TALLAS
                $cantidadCurva[$k]= $duplaCurva[1];             // CANTIDAD
                if($k==0){
                  $curva= $duplaCurva[1];
                }
                else{
                  $curva.= ','.$duplaCurva[1];
                }
              }
              $columnaInventario['curva'] = $curva;
              $columnaInventario['completa'] = "1" ; //1 COMPLETA, 0 INCOMPLETA
  
//No se agrega una caja nueva, ya que existe en la bodega principal,
//hay que buscar si existe la cantidad de cajas de la mercaderia a enviar a local, 
//solamente son cuando el estado de inventario es 'disponible'
//buscar lista de Cajas para restar
//buscar lista de inventario para cada caja a restar, ordenar por id de caja ASC
//modificar cada inventario, restandole la cantidad total de la caja
//buscar historial del inventario, guardar ID del historial padre
//ADD nuevo historial, asignar historial padre
              $listaCajasInv = $cajatarea->getListaCajatarea($columnaInventario['1'], "Bodega Principal", "Disponible", "1") ;
              $cantCajasInv = count( $listaCajasInv );
              if($columnaInventario[2] <= $cantCajasInv){   //si se pide menos que la cant de cajas disponibles en bodega
                for($h=0;$h<$columnaInventario[2];$h++){    // cantidad de cajas pedidas
                  $listaInventarioPorCaja = $inventario->getListaPorCajaInventario($listaCajasInv[$h]['cjt_id_caja_tarea'], "Bodega Principal", "Disponible", "1");
                  for($g=0;$g<count($listaInventarioPorCaja);$g++){ //Para cada talla de la caja
                    $lastHistorial = $historialInventario->getLastHistorialinventario($listaInventarioPorCaja[$g]['inv_id_inventario']); //busca al ultimo hijo del historial del inventario,
                    //$lastHistorial-> [0]['hii_id_historial_inventario']/inv_id_inventario/ghi_id_glosa_inventario/ hii_entrada/hii_salida/hii_total/hii_fecha/ hii_descripcion/hii_id_padre_historia
                    $resultadoInventarioArr = array(
                        "inv_id_inventario"         => $listaInventarioPorCaja[$g]['inv_id_inventario'],
                        "ghi_id_glosa_inventario"   => $glosaInventarioArr['ghi_id_glosa_inventario'],
                        "hii_entrada"               => '0',
                        "hii_salida"                => $lastHistorial[0]['hii_total'],
                        "hii_total"                 => '0',
                        "hii_fecha"                 => $fecha,
                        "hii_descripcion"           => 'Transacción hacia Local, cantidad: '.$lastHistorial[0]['hii_total'].
                        ' del inventario: '.$listaInventarioPorCaja[$g]['inv_id_inventario'].' por Usuario: '.$usuarioInfo->usu_rut,
                        "hii_id_padre_historia"     => $lastHistorial[0]['hii_id_historial_inventario']);
                    $historialInventario->addHistorialInventario( $resultadoInventarioArr );  //se ingresa nuevo historial donde el padre es el id sacado
                    $listaInventarioPorCaja[$g]['inv_cantidad']= '0';
                    $listaInventarioPorCaja[$g]['inve_id_inv_estado']= $investado->getInvestado2("Depletado");
                    $listaInventarioPorCaja[$g]['inv_fecha']= $fecha;
                    $id_inventario = $inventario->updateInventario($listaInventarioPorCaja[$g]);       // EDITA INVENTARIO, CANTIDAD = 0
                    $indice = $g + (6*$h);
                    $id_inventarioArr[$indice]= $id_inventario;
                  }
                }
              }

            }
            
            if($formData['dop_numero_identificador'] == '' && $formData['dos_numero_identificador'] != '' ){   //seleccionar tipo de documento principal  = factura/guiadespacho
              $formData['dop_tipo_doc_principal'] = "Guía de Despacho";
              $formData['dop_numero_identificador'] = $formData['dos_numero_identificador'];
              $formData['dos_numero_identificador'] = "";
            }else if ($formData['dop_numero_identificador'] != '' && $formData['dos_numero_identificador'] == ''){
              $formData['dop_tipo_doc_principal'] = "Factura";
            }else if ($formData['dop_numero_identificador'] != '' && $formData['dos_numero_identificador'] != ''){
              $formData['dop_tipo_doc_principal'] = "Factura";
            }else{
              $formData['dop_tipo_doc_principal'] = "No disponible";
            }
            $formData['fecha'] = $fecha;
            $destinatarioArr2 = $destinatario->getDestinatarioPorId( $formData['des_id_destinatario'] );   //get nombre de salida
            $destinatarioArr2->toArray();
            $formData['des_salida'] = $destinatarioArr2[0]['des_nombre'];
            $destinatarioArr = $destinatario->getDestinatarioPorId( $formData['des_id_destinatario2'] );  //get nombre de llegada
            $destinatarioArr->toArray();
            $formData['dop_empresa_relacionada'] = $destinatarioArr[0]['des_nombre'];
            $formData['dop_tipo_motivo'] = "Entrega";
            $id_docPrincipal= $documentoPrincipal->addDocumentoPrincipal($formData);    // AGREGA DOCUMENTO PRINCIPAL

            $time_salida  = DateTime::createFromFormat('d/m/Y', $formData['ctr_fecha_salida'])->format('Y/m/d H:i:s');
//            $time_llegada = DateTime::createFromFormat('d/m/Y', $formData['ctr_fecha_llegada'])->format('Y/m/d H:i:s');   //ESPERAR A QUE SE ACEPTE DESDE EL LOCAL
            $time_llegada = DateTime::createFromFormat('d/m/Y', '01/01/2000')->format('Y/m/d H:i:s');   //AL INGRESAR AUN NO SE SABE LA FECHA DE LLEGADA
            $formData['ctr_fecha_salida'] = $time_salida;
            $formData['ctr_fecha_llegada'] = $time_llegada;
            $id_transporte= $transporte->addTransporte($formData);                                       // AGREGA TRANSPORTE

//ORIGEN
            $docHasDestinatarioArr['dop_id_documento_principal'] = $id_docPrincipal;
            $docHasDestinatarioArr['des_id_destinatario'] = $formData['des_id_destinatario'];
            $docHasDestinatarioArr['dhdo_tipo'] = 'Origen';
            $docHasDestinatario->addDocumentohasdestinatario($docHasDestinatarioArr);
//DESTINATARIO
            $docHasDestinatarioArr['des_id_destinatario'] = $formData['des_id_destinatario2'];
            $docHasDestinatarioArr['dhdo_tipo'] = 'Destinatario';
            $docHasDestinatario->addDocumentohasdestinatario($docHasDestinatarioArr);     // DOCUMENTO HAS DESTINATARIO

            if($formData['dos_numero_identificador'] !=''){
              $id_docSecundario = $documentoSecundario->addDocumentoSecundario( $formData['dos_numero_identificador'] );  // DOCUMENTO SECUNDARIO
              $prinHasSecArr['id_principal'] = $id_docPrincipal;
              $prinHasSecArr['id_secundario'] = $id_docSecundario;
              $principalHasSecundario->addPrincipalHasSecundario($prinHasSecArr) ;                                    // DOCUMENTO PRINCIPAL HAS SECUNDARIO
            }
            
            if($formData['tipoSubmit'] == "procesar"){
              $formData['tra_tipo_motivo'] = "Hacia Local (En Proceso)";                            
            }else if($formData['tipoSubmit'] == "finalizar") {
              $formData['tra_tipo_motivo'] = "Hacia Local (Finalizada)";              
            }
            
            $transaccionArr = array(
            'dop_id_documento_principal'    => $id_docPrincipal,
            'ctr_id_transporte'             => $id_transporte,
            'tra_tipo_motivo'               => $formData['tra_tipo_motivo'],
            'tra_fecha_inicio'              => $fecha,
            'tra_fecha_finalizacion'        => '',
            'tra_rut_autorizado_inicio'     => $usuarioInfo->usu_rut,
            'tra_rut_autorizado_final'      => '',
            'tra_tipo'                      => $formData['dop_tipo_doc_principal'] );
            $id_transaccion = $transaccion->addTransaccion($transaccionArr);                                // TRANSACCION
            
            for($i=0; $i<count($id_inventarioArr); $i++){
            $invHasTransaccionArr = array(
            'tra_id_transaccion'     => $id_transaccion,
            'inv_id_inventario'      => $id_inventarioArr[$i],
            'iht_fecha'              => $fecha );
            $inventarioHasTransaccion->addInventariohastransaccion($invHasTransaccionArr) ;                 // INVENTARIO HAS TRANSACCION
            }
          
            //$this->_helper->redirector('index');
            $returnUrl = $form->getElement('returnUrl')->getValue();
            if (!empty($returnUrl)) {
              $this->_helper->getHelper('Redirector')->setGotoUrl($returnUrl);
            }
            
          } else {
              $form->populate($formData);
          }
      }
    }
 */

