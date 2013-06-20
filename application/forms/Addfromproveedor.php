<?php

class Application_Form_Addfromproveedor extends Zend_Form
{

    public function init()
    {
        $this->setDisableLoadDefaultDecorators(true);
/*   
        $this->setDecorators(array(
        array('ViewScript', array('viewScript' => 'frmAddfromproveedor.phtml','datos'=>$this->_arrDatos)),'Form'
        )); */

        $this->setName('addfromproveedor')->setAttrib('class','form-horizontal')->setAttrib('enctype', 'multipart/form-data');
// * * * * * * * * DATOS PRIMERA COLUMNA
        $tra_id_transaccion = new Zend_Form_Element_Hidden('tra_id_transaccion');
        $tra_id_transaccion->addFilter('Int');
        
        $ctr_id_transporte = new Zend_Form_Element_Hidden('ctr_id_transporte');
        $ctr_id_transporte->addFilter('Int');

        $des_id_destinatario = new Zend_Form_Element_Select('des_id_destinatario');
        $des_id_destinatario->setLabel('Cód./Nombre del proveedor:')->setAttrib('onChange','getDataSalida(this.value)');
        $filaDestinatario = new Application_Model_DbTable_Destinatario();
        foreach ($filaDestinatario->getPorTipoDestinatario("PROVEEDOR") as $destinatario) :
          $des_id_destinatario->addMultiOption( $destinatario->des_id_destinatario,  $destinatario->des_nombre );
        endforeach;
      
        $des_rut = new Zend_Form_Element_Text('des_rut');
        $des_rut->setAttrib('placeholder','RUT')->setLabel('RUT Proveedor:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $des_direccion = new Zend_Form_Element_Text('des_direccion');
        $des_direccion->setAttrib('placeholder','Dirección')->setLabel('Dirección Salida:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $ctr_ciudad_salida = new Zend_Form_Element_Text('ctr_ciudad_salida');
        $ctr_ciudad_salida->setAttrib('placeholder','Ciudad Salida')->setLabel('Ciudad Salida:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $ctr_fecha_salida = new Zend_Form_Element_Text('ctr_fecha_salida');
        $ctr_fecha_salida->setAttrib('placeholder','Fecha Salida')->setLabel('Fecha Salida:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $ctr_nombre = new Zend_Form_Element_Select('ctr_nombre');
        $ctr_nombre->setAttrib('placeholder','Transportista')->setLabel('Nombre del Transporte:');
        $ctr_nombre->addMultiOption( 'Solón Chávez', 'Solón Chávez' );
        $ctr_nombre->addMultiOption( 'EGT/Turbus',  'EGT/Turbus' );
        
        $ctr_costo = new Zend_Form_Element_Text('ctr_costo');
        $ctr_costo->setAttrib('placeholder','Costo')->setLabel('Costo del Transporte:');
        
// * * * * * * * DATOS SEGUNDA COLUMNA
        
        $des_id_destinatario2 = new Zend_Form_Element_Select('des_id_destinatario2');
        $des_id_destinatario2->setLabel('Cód./Nombre del destinatario:')->setAttrib('onChange','getDataLlegada(this.value)');
        $filaDestinatario2 = new Application_Model_DbTable_Destinatario();
        foreach ($filaDestinatario2->getPorTipoDestinatario("EMPRESA") as $destinatario2) :
          $des_id_destinatario2->addMultiOption( $destinatario2->des_id_destinatario,  $destinatario2->des_nombre );
        endforeach;
        
        $des_direccion2 = new Zend_Form_Element_Text('des_direccion2');
        $des_direccion2->setAttrib('placeholder','Dirección')->setLabel('Dirección Llegada:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $des_rut2 = new Zend_Form_Element_Text('des_rut2');
        $des_rut2->setAttrib('placeholder','RUT')->setLabel('RUT Destinatario:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $dop_giro = new Zend_Form_Element_Text('dop_giro');
        $dop_giro->setAttrib('placeholder','Giro del negocio')->setLabel('Giro:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $ctr_ciudad_llegada = new Zend_Form_Element_Text('ctr_ciudad_llegada');
        $ctr_ciudad_llegada->setAttrib('placeholder','Ciudad')->setLabel('Ciudad de Llegada:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $des_comuna = new Zend_Form_Element_Text('des_comuna');
        $des_comuna->setAttrib('placeholder','Comuna')->setLabel('Comuna:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $des_region = new Zend_Form_Element_Text('des_region');
        $des_region->setAttrib('placeholder','Región')->setLabel('Región:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

// * * * * * * * DATOS TERCERA COLUMNA
        
        $dop_numero_identificador = new Zend_Form_Element_Text('dop_numero_identificador');
        $dop_numero_identificador->setAttrib('placeholder','Nro. Factura')->setLabel('Nro. Factura:')
                ->addFilter('StripTags')->addFilter('StringTrim');
        
        $dop_orden_compra = new Zend_Form_Element_Text('dop_orden_compra');
        $dop_orden_compra->setAttrib('placeholder','Nro. Orden Compra')->setLabel('Nro. Orden de Compra:')
                ->addFilter('StripTags')->addFilter('StringTrim');
        
        $dos_numero_identificador = new Zend_Form_Element_Text('dos_numero_identificador');
        $dos_numero_identificador->setAttrib('placeholder','Nro. Guía')->setLabel('Nro. Guía de Despacho:')
                ->addFilter('StripTags')->addFilter('StringTrim');

        $ctr_fecha_llegada = new Zend_Form_Element_Text('ctr_fecha_llegada');
        $ctr_fecha_llegada->setAttrib('placeholder','Fecha Llegada')->setLabel('Fecha llegada:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $mpa_id_modalidad_pago = new Zend_Form_Element_Select('mpa_id_modalidad_pago');
        $mpa_id_modalidad_pago->setLabel('Condiciones:');
        $filaModalidadPago = new Application_Model_DbTable_Modalidadpago();
        foreach ($filaModalidadPago->fetchAll() as $filaModalidad) :
          $mpa_id_modalidad_pago->addMultiOption( $filaModalidad->mpa_id_modalidad_pago,  $filaModalidad->mpa_codigo );
        endforeach;
        
// * * * * * * LLENAR DATOS DE FACTURA (INVENTARIO)
        $mer_codigo = new Zend_Form_Element_Text('mer_codigo');
        $mer_codigo->setDecorators(array( array('ViewHelper'), ));
        $mer_codigo->setAttrib("tabindex", "1");
        $mer_codigo->setAttrib("class", "jumper");
        
        $btn_mer_codigo = new Zend_Form_Element_Button('btn_mer_codigo');
        $btn_mer_codigo->setAttrib('class','btn jumper')->setAttrib('onClick','getDataMercaderia()');;
        $btn_mer_codigo->setLabel('<i class="icon-arrow-right"></i>')->setAttrib( 'escape', false );
        $btn_mer_codigo->setDecorators(array( array('ViewHelper'), ));
        $btn_mer_codigo->setAttrib("tabindex", "2");
        
        $mer_articulo = new Zend_Form_Element_Text('mer_articulo');
        $mer_articulo->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $mer_articulo->setAttrib('class','span2 disabled')->setAttrib('readonly','readonly');
        $mer_articulo->setDecorators(array( array('ViewHelper'), ));
        
        $col_nombre = new Zend_Form_Element_Text('col_nombre');
        $col_nombre->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $col_nombre->setAttrib('class','span2 disabled')->setAttrib('readonly','readonly');
        $col_nombre->setDecorators(array( array('ViewHelper'), ));
        
        $mer_foto =  new Zend_Form_Element_Hidden('mer_foto');
        $mer_foto->setValue('');
        
        //num cajas -> input que no es del formulario, se actualiza con javascript
        $f_num_cajas =  new Zend_Form_Element_Text('f_num_cajas');
        $f_num_cajas->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $f_num_cajas->setAttrib('class','span1 jumper');
        $f_num_cajas->setAttrib('onchange','calcularTotalCajas()');
        $f_num_cajas->setValue('0');
        $f_num_cajas->setDecorators(array( array('ViewHelper'), ));
        $f_num_cajas->setAttrib("tabindex", "3");
        
        //total de mercaderia -> input que no es del formulario, se actualiza con javascript
        $f_total_mercaderia =  new Zend_Form_Element_Text('f_total_mercaderia');
        $f_total_mercaderia->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $f_total_mercaderia->setAttrib('class','disabled span2')->setAttrib('readonly','readonly');
        $f_total_mercaderia->setDecorators(array( array('ViewHelper'), ));
        
        //costo
        $mer_costo =  new Zend_Form_Element_Text('mer_costo');
        $mer_costo->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $mer_costo->setAttrib('class','disabled span2')->setAttrib('readonly','readonly');
        $mer_costo->setValue('0');
        $mer_costo->setDecorators(array( array('ViewHelper'), ));
        
        //acción -> SUBMIT
        $f_accion= new Zend_Form_Element_Button('f_accion');
        $f_accion->setAttrib('class','btn btn-primary');

        //LISTA DE INVENTARIO AGREGADO (STRING)
        $stringMercanciaInput= new Zend_Form_Element_Hidden('stringMercanciaInput');
        $stringMercanciaInput->setValue('');
        
        //SUMA SUBTOTAL
        $t_suma_subtotal =  new Zend_Form_Element_Hidden('t_suma_subtotal');
        $t_suma_subtotal->addFilter('Int');
        
        //monto neto
        $t_neto =  new Zend_Form_Element_Text('t_neto');
        $t_neto->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $t_neto->setAttrib('class','disabled span2')->setAttrib('readonly','readonly');
        $t_neto->setDecorators(array( array('ViewHelper'), ));
        
        //subtotal
        $t_subtotal =  new Zend_Form_Element_Text('t_subtotal');
        $t_subtotal->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $t_subtotal->setAttrib('class','disabled span2')->setAttrib('readonly','readonly');
        $t_subtotal->setDecorators(array( array('ViewHelper'), ));
        
        //IVA
        $t_iva =  new Zend_Form_Element_Text('t_iva');
        $t_iva->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $t_iva->setDecorators(array( array('ViewHelper'), ));
        $t_iva->setAttrib('class','span2')->setAttrib('onchange','calcularValoresTotales()');
        $t_iva->setValue('19');
        
        //descuento
        $t_descuento =  new Zend_Form_Element_Text('t_descuento');
        $t_descuento->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $t_descuento->setDecorators(array( array('ViewHelper'), ));
        $t_descuento->setAttrib('class','span2')->setAttrib('onchange','calcularValoresTotales()');
        
        //total
        $t_total =  new Zend_Form_Element_Text('t_total');
        $t_total->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim');
        $t_total->setAttrib('class','disabled span2')->setAttrib('readonly','readonly');
        $t_total->setDecorators(array( array('ViewHelper'), ));
        
// * * * * * FORMULARIO (submit+urlretorno)
        $tipoSubmit = new Zend_Form_Element_Hidden('tipoSubmit');
        $tipoSubmit->setDecorators(array( array('ViewHelper'), ));

        $controllerFront = Zend_Controller_Front::getInstance();
        $returnUrl = $controllerFront->getRequest()->getHeader('REFERER');
        $this->addElement('hidden', 'returnUrl', array(
        'value' => $returnUrl
        ));
        
        $this->addElements(array(
//cabecera de factura/guia despacho
            $tra_id_transaccion,
            $ctr_id_transporte,
// 1ra fila
            $des_id_destinatario,
            $des_direccion,
            $des_rut,
            $ctr_ciudad_salida,
            $ctr_fecha_salida,
            $ctr_nombre,
            $ctr_costo,
// 2da fila
            $des_id_destinatario2,
            $des_direccion2,
            $des_rut2,
            $dop_giro,
            $ctr_ciudad_llegada,
            $des_comuna,
            $des_region,
// 3ra fila
            $dop_numero_identificador,
            $dop_orden_compra,
            $dos_numero_identificador,
            $ctr_fecha_llegada,
            $mpa_id_modalidad_pago,
//Inventario( filas de Factura)
            $mer_codigo,
            $btn_mer_codigo,
            $mer_articulo,
            $mer_foto,
            $col_nombre,
            $mer_costo,
            $f_num_cajas,
            $f_total_mercaderia,
//datos del formulario
            $f_accion,
            $stringMercanciaInput,
            $t_suma_subtotal,
            $t_neto,
            $t_subtotal,
            $t_descuento,
            $t_iva,
            $t_total,

//submit                       
            $tipoSubmit));
    }

}