<?php

class Application_Form_Venta extends Zend_Form
{

    public function init()
    {
        $this->setDisableLoadDefaultDecorators(true);
        $this->setName('venta')->setAttrib('class','form-horizontal')->setAttrib('enctype', 'multipart/form-data');

        //local al que pertenece el usuario
        $userInfo = Zend_Auth::getInstance()->getStorage()->read();
        $usuariohaslocales  = new Application_Model_DbTable_UsuarioHasLocal();
        $uhl = $usuariohaslocales->localVendedorUsuario($userInfo->usu_id_usuario);
        $local =  $uhl[0]['loc_nombre'];

        $loc_nombre =  new Zend_Form_Element_Hidden('loc_nombre');
        $loc_nombre->setValue($local);

 
// * * * * * * * * ASIGNAR VENDEDOR (TAB 1) * * * * * * * * * *
        $usu_id_usuario = new Zend_Form_Element_Select('usu_id_usuario');
        $usu_id_usuario->setAttrib('class','input-large primero');
        $usu_id_usuario->setDecorators(array( array('ViewHelper'), ));
        $filaVendedor = new Application_Model_DbTable_Usuarios();
        foreach ($filaVendedor->getUsuarioPorPerfilLocal("Vendedor Fijo",$local) as $vendedor) :
          $usu_id_usuario->addMultiOption( $vendedor->usu_id_usuario,  $vendedor->usu_nombre.' '.$vendedor->usu_apellido_1.' '.$vendedor->usu_apellido_2 );
        endforeach;
        foreach ($filaVendedor->getUsuarioPorPerfilLocal("Vendedor Auxiliar",$local) as $vendedor) :
          $usu_id_usuario->addMultiOption( $vendedor->usu_id_usuario,  $vendedor->usu_nombre.' '.$vendedor->usu_apellido_1.' '.$vendedor->usu_apellido_2 );
        endforeach;
        
// * * * * * AGREGAR FILA DE MERCADERIA A LA VENTA (TAB 2)* * * * *
        $mer_codigo = new Zend_Form_Element_Text('mer_codigo');
        $mer_codigo->setDecorators(array( array('ViewHelper'), ));
        $mer_codigo->setAttrib("tabindex", "1");
        $mer_codigo->setAttrib("class", "input-small jumper1 primero");
        
        $btn_mer_codigo = new Zend_Form_Element_Button('btn_mer_codigo');
        $btn_mer_codigo->setAttrib('class','btn jumper1')->setAttrib('onClick','getDataMercaderia()');
        $btn_mer_codigo->setLabel('<i class="icon-arrow-right"></i>')->setAttrib( 'escape', false );
        $btn_mer_codigo->setDecorators(array( array('ViewHelper'), ));
        $btn_mer_codigo->setAttrib("tabindex", "2");
        
        $f_num_mercaderia =  new Zend_Form_Element_Text('f_num_mercaderia');
        $f_num_mercaderia->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $f_num_mercaderia->setAttrib('class','input-mini jumper1');
        $f_num_mercaderia->setAttrib('onchange','calcularTotalMonto()');
        $f_num_mercaderia->setValue('1');
        $f_num_mercaderia->setDecorators(array( array('ViewHelper'), ));
        $f_num_mercaderia->setAttrib("tabindex", "3");
       
        $mer_articulo = new Zend_Form_Element_Text('mer_articulo');
        $mer_articulo->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $mer_articulo->setAttrib('class','input-small disabled')->setAttrib('readonly','readonly');
        $mer_articulo->setDecorators(array( array('ViewHelper'), ));
        
        $col_nombre = new Zend_Form_Element_Text('col_nombre');
        $col_nombre->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $col_nombre->setAttrib('class','input-small disabled')->setAttrib('readonly','readonly');
        $col_nombre->setDecorators(array( array('ViewHelper'), ));
        
        $mer_foto =  new Zend_Form_Element_Hidden('mer_foto');
        $mer_foto->setValue('');

        $hme_precio = new Zend_Form_Element_Text('hme_precio');
        $hme_precio->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $hme_precio->setAttrib('class','input-small disabled')->setAttrib('readonly','readonly');
        $hme_precio->setDecorators(array( array('ViewHelper'), ));

        //total de mercaderia -> input que no es del formulario, se actualiza con javascript
        $f_total_mercaderia =  new Zend_Form_Element_Text('f_total_mercaderia');
        $f_total_mercaderia->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $f_total_mercaderia->setAttrib('class','input-small disabled')->setAttrib('readonly','readonly');
        $f_total_mercaderia->setDecorators(array( array('ViewHelper'), ));

// * * * * * * * * FORMA DE PAGO (TAB 3) * * * * * * * * * *
        $tip_id_tipo_pago = new Zend_Form_Element_Select('tip_id_tipo_pago');
        $tip_id_tipo_pago->setAttrib('class','input-large primero jumper2')
                ->setAttrib('onChange','calcularPago()')
                ->setAttrib("tabindex", "1");
        $filaTipoPago = new Application_Model_DbTable_Tipopago();
        foreach ($filaTipoPago->fetchAll() as $tipoPago) :
          $tip_id_tipo_pago->addMultiOption($tipoPago->tip_id_tipo_pago,$tipoPago->tip_nombre);
        endforeach;
        $tip_id_tipo_pago->setDecorators(array( array('ViewHelper'), ));

        $f_pago_monto =  new Zend_Form_Element_Text('f_pago_monto');
        $f_pago_monto->setRequired(true);
        $f_pago_monto->setAttrib('class','input-medium jumper2')
                ->setAttrib('onChange','calcularVuelto()')
                ->setAttrib("tabindex", "2");
        $f_pago_monto->setDecorators(array( array('ViewHelper'), ));

// * * * * * * * * FORMA DE PAGO EXTRA (TAB 3) * * * * * * * * * *
        $tphv_codigo_cheque =  new Zend_Form_Element_Text('tphv_codigo_cheque');
        $tphv_codigo_cheque->setAttrib('class','input-large jumper2')
                ->setAttrib("tabindex", "3");
        $tphv_codigo_cheque->setDecorators(array( array('ViewHelper'), ));
        
        $tphv_cant_cuotas = new Zend_Form_Element_Select('tphv_cant_cuotas');
        $tphv_cant_cuotas->setAttrib('class','input-large jumper2')
                ->setAttrib('onChange','calcularMontoCuotas()')
                ->setAttrib("tabindex", "3");
        $tphv_cant_cuotas->addMultiOption("0","-");
        $tphv_cant_cuotas->addMultiOption("1","1 Cuota");
        $tphv_cant_cuotas->addMultiOption("2","2 Cuotas");
        $tphv_cant_cuotas->addMultiOption("3","3 Cuotas");
        $tphv_cant_cuotas->setDecorators(array( array('ViewHelper'), ));

        $f_monto_cuota =  new Zend_Form_Element_Text('f_monto_cuota');
        $f_monto_cuota->setAttrib('class','disabled input-large')
                ->setAttrib('readonly','readonly');
        $f_monto_cuota->setDecorators(array( array('ViewHelper'), ));
        
        $tphv_observacion_smo = new Zend_Form_Element_Textarea('tphv_observacion_smo');
        $tphv_observacion_smo->setAttrib('class','input-large jumper2')
                ->setAttrib('rows', '2')
                ->setAttrib('onChange','calcularMontoCuotas()')
                ->setAttrib("tabindex", "4");
        $tphv_observacion_smo->setDecorators(array( array('ViewHelper'), ));
        
// * * * * * * * * APLICAR DESCUENTO (TAB 4) * * * * * * * * * *
        $des_id_descuento = new Zend_Form_Element_Select('des_id_descuento');
        $des_id_descuento->setAttrib('class','input-large primero jumper3')
                ->setAttrib('onChange','calcularDescuento()');
        $filaDcto = new Application_Model_DbTable_Descuento();
        foreach ($filaDcto->fetchAll() as $dcto) :
          $des_id_descuento->addMultiOption($dcto->des_id_descuento,$dcto->des_tipo.' ('.$dcto->des_porcentaje.'%)');
        endforeach;
        $des_id_descuento->setAttrib("tabindex", "1")
                ->setDecorators(array( array('ViewHelper'), ));
        
        $f_descuento_monto =  new Zend_Form_Element_Text('f_descuento_monto');
        $f_descuento_monto->setRequired(true)->setValue("0");
        $f_descuento_monto->setAttrib('class','input-medium jumper3');
        $f_descuento_monto->setAttrib("tabindex", "2")
                ->setDecorators(array( array('ViewHelper'), ));
   
// * * * * * * * * SUBTOTALES DE LA VENTA (ABAJO DE TABS) * * * * * * * *        
        $f_total =  new Zend_Form_Element_Text('f_total');
        $f_total->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $f_total->setAttrib('class','disabled input-small')->setAttrib('readonly','readonly');
        $f_total->setDecorators(array( array('ViewHelper'), ));
        
        $f_dcto =  new Zend_Form_Element_Text('f_dcto');
        $f_dcto->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $f_dcto->setAttrib('class','disabled input-small')->setAttrib('readonly','readonly');
        $f_dcto->setDecorators(array( array('ViewHelper'), ));
        
        $f_vuelto =  new Zend_Form_Element_Text('f_vuelto');
        $f_vuelto->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $f_vuelto->setAttrib('class','disabled input-small')->setAttrib('readonly','readonly');
        $f_vuelto->setDecorators(array( array('ViewHelper'), ));
        
        $f_total_final =  new Zend_Form_Element_Text('f_total_final');
        $f_total_final->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $f_total_final->setAttrib('class','disabled input-small')->setAttrib('readonly','readonly');
        $f_total_final->setDecorators(array( array('ViewHelper'), ));

// * * * * * * * * SUBMIT * * * * * * * *        
        //LISTA DE INVENTARIO AGREGADO (STRING)
        $stringMercanciaInput= new Zend_Form_Element_Hidden('stringMercanciaInput');
        $stringMercanciaInput->setValue('');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setAttrib('class','btn btn-large btn-primary btn-block primero');
        $submit->setLabel('F I N A L I Z A R  V E N T A');
        $submit->setDecorators(array( array('ViewHelper'), ));
        
        $controllerFront = Zend_Controller_Front::getInstance();
        $returnUrl = $controllerFront->getRequest()->getHeader('REFERER');
        $this->addElement('hidden', 'returnUrl', array(
        'value' => $returnUrl
        ));
        
        $this->addElements(array(
            $loc_nombre,
// * * * * * * * * ASIGNAR VENDEDOR (TAB 1) * * * * * * * * * *
            $usu_id_usuario,    // select lista de vendedores
// * * * * * * * * AGREGAR FILA DE MERCADERIA A LA VENTA (TAB 2) * * *
            $mer_codigo,
            $btn_mer_codigo,
            $f_num_mercaderia,
            $mer_articulo,
            $col_nombre,
            $mer_foto,
            $hme_precio,
            $f_total_mercaderia,
// * * * * * * * * FORMA DE PAGO (TAB 3) * * * * * * * * * *
            $tip_id_tipo_pago,
            $f_pago_monto,
// * * * * * * * * FORMA EXTRA DE PAGO (TAB 3) * * * * * * * * * *
            $tphv_codigo_cheque,
            $tphv_cant_cuotas,
            $f_monto_cuota,
            $tphv_observacion_smo,
// * * * * * * * * APLICAR DESCUENTO (TAB 4) * * * * * * * * * *
            $des_id_descuento,
            $f_descuento_monto,

// * * * * * * * * SUBTOTALES DE LA VENTA * * * * * * * *  
            $f_total,
            $f_dcto,
            $f_vuelto,
            $f_total_final,
// * * * * * * * * SUBMIT (TAB 5) * * * * * * * * * 
            $stringMercanciaInput,
            $submit));
    }


}