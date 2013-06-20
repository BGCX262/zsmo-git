<?php
class Application_Form_Mercaderia extends Zend_Form
{

    public function init()
    {
        $this->setName('mercaderia')->setAttrib('class','form-horizontal')->setAttrib('enctype', 'multipart/form-data');
// * * * * * * * * DATOS BASICOS DE LA MERCADERIA        
        $mer_id_mercaderia = new Zend_Form_Element_Hidden('mer_id_mercaderia');
        $mer_id_mercaderia->addFilter('Int');

        $fcp_id_familia_codigo_proveedor = new Zend_Form_Element_Select('fcp_id_familia_codigo_proveedor');
        $fcp_id_familia_codigo_proveedor->setLabel('Cód./Nombre del proveedor:');
        $filaDestinatario = new Application_Model_DbTable_Destinatario();
        foreach ($filaDestinatario->fetchAll(null, "des_nombre ASC") as $destinatario) :
          $fcp_id_familia_codigo_proveedor->addMultiOption( $destinatario->des_id_destinatario,  $destinatario->des_nombre );
        endforeach;
        
        $mer_codigo = new Zend_Form_Element_Text('mer_codigo');
        $mer_codigo->setAttrib('placeholder','Código SMO')->setLabel('Código SMO:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $mer_articulo = new Zend_Form_Element_Text('mer_articulo');
        $mer_articulo->setAttrib('placeholder','Cód. de Artículo FABRICANTE')->setLabel('Artículo:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $col_id_color = new Zend_Form_Element_Select('col_id_color');
        $col_id_color->setLabel('Color de la mercadería:');
        $filaColor = new Application_Model_DbTable_Color();
        foreach ($filaColor->fetchAll(null, "col_nombre ASC") as $color) :
          $col_id_color->addMultiOption( $color->col_id_color, $color->col_nombre );
        endforeach;
        
        $tal_id_talla = new Zend_Form_Element_Select('tal_id_talla');
        $tal_id_talla->setLabel('Rango de Tallas (Inicio):');

        $tal_id_talla2 = new Zend_Form_Element_Select('tal_id_talla2');
        $tal_id_talla2->setLabel('Rango de Tallas (Final):');

        $filaTalla = new Application_Model_DbTable_Talla();
        foreach ($filaTalla->fetchAll() as $talla) :
          $tal_id_talla->addMultiOption( $talla->tal_id_talla, $talla->tal_talla.' ('.$talla->tal_tipo.')' );
          $tal_id_talla2->addMultiOption( $talla->tal_id_talla, $talla->tal_talla.' ('.$talla->tal_tipo.')' );        
        endforeach;
        
// * * * * * * * DATOS DE LOGISTICA Y VENTA DE LA MERCADERIA
        $mer_costo = new Zend_Form_Element_Text('mer_costo');
        $mer_costo->setAttrib('placeholder','Costo de la mercadería')->setLabel('Costo:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $mer_tipo = new Zend_Form_Element_Select('mer_tipo');
        $mer_tipo->setLabel('Tipo:');
        $filaMercaderiatipo = new Application_Model_DbTable_Mercaderiatipo();
        foreach ($filaMercaderiatipo->fetchAll(null, "mti_mer_tipo ASC") as $mercaderiatipo) :
          $mer_tipo->addMultiOption( $mercaderiatipo->mti_mer_tipo,  $mercaderiatipo->mti_mer_tipo );
        endforeach;
        
        $mer_tamanno_tarea = new Zend_Form_Element_Text('mer_tamanno_tarea');
        $mer_tamanno_tarea->setAttrib('placeholder','Tamaño de la Tarea')->setLabel('Tamaño de la Tarea:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
/*        $mer_zona_bodega = new Zend_Form_Element_Text('mer_zona_bodega');
        $mer_zona_bodega->setAttrib('placeholder','Nombre de Zona en Bodega')->setLabel('Zona en bodega:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty'); */

        $mer_prioridad_venta = new Zend_Form_Element_Select('mer_prioridad_venta');
        $mer_prioridad_venta->setLabel('Prioridad de la venta:');
        $mer_prioridad_venta->addMultiOption("1","Prioridad Normal");
        $mer_prioridad_venta->addMultiOption("2","Prioridad Alta");
        $mer_prioridad_venta->addMultiOption("3","Prioridad Muy Alta");

        $mer_temporada = new Zend_Form_Element_Select('mer_temporada');
        $mer_temporada->setLabel('Temporada:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        $mer_temporada->addMultiOption("TODA ESTACION","Toda Estación");
        $mer_temporada->addMultiOption("VERANO","Verano");
        $mer_temporada->addMultiOption("INVIERNO","Invierno");
        $mer_temporada->addMultiOption("COLEGIAL","Colegial");
        $mer_temporada->addMultiOption("FIESTAS PATRIAS","Fiestas Patrias");
        
// * * * * * * * * *  DATOS AVANZADOS
        $mer_foto = new Zend_Form_Element_File('mer_foto');
        $mer_foto->setAttrib('placeholder','Foto')->setLabel('Agregar foto:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')
                ->setDestination(APPLICATION_PATH.'/../public/img/mercaderia')
                ->addValidator('Count', false, 1)->addValidator('Size', false, 1024000)->addValidator('Extension', false, 'jpg,jpeg,png,gif');
        
        $mer_nombre = new Zend_Form_Element_Text('mer_nombre');
        $mer_nombre->setAttrib('placeholder','Marca')->setLabel('Marca/Fábrica:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $mer_modelo = new Zend_Form_Element_Text('mer_modelo');
        $mer_modelo->setAttrib('placeholder','Modelo de mercadería')->setLabel('Modelo:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $mer_descripcion = new Zend_Form_Element_Text('mer_descripcion');
        $mer_descripcion->setAttrib('placeholder','Descripción')->setLabel('Descripción:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $mer_sexo = new Zend_Form_Element_Select('mer_sexo');
        $mer_sexo->setLabel('Mercadería para:')
                ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        $mer_sexo->addMultiOption("HOMBRE","Hombre");
        $mer_sexo->addMultiOption("MUJER","Mujer");
        $mer_sexo->addMultiOption("UNISEX","Unisex");
       
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array(
            $mer_id_mercaderia, $mer_codigo, $mer_descripcion, $fcp_id_familia_codigo_proveedor, 
            $tal_id_talla, $tal_id_talla2, $mer_costo, $mer_tipo, $col_id_color, $mer_modelo, $mer_articulo, $mer_foto,
            $mer_tamanno_tarea, $mer_prioridad_venta, $mer_nombre, $mer_sexo, $mer_temporada, $submit));

// * * * * GRUPO DESPLIEGUE DATOS BASICOS
        $this->addDisplayGroup(
          array( 'fcp_id_familia_codigo_proveedor', 'mer_codigo', 'mer_articulo',
              'col_id_color', 'tal_id_talla', 'tal_id_talla2', 'mer_nombre' ),
          'datos1',
          array('legend' => 'Datos Básicos') );
        
        $datos1 = $this->getDisplayGroup('datos1');
        
        $datos1->setDecorators(array(
          'FormElements',
          'Fieldset',
          array('HtmlTag',array('tag'=>'div','class'=>'span4 pull-left'))
        ));
        
// * * * * GRUPO DESPLIEGUE DATOS LOGISTICA Y VENTA
        $this->addDisplayGroup(
          array( 'mer_costo', 'mer_tipo', 'mer_tamanno_tarea',
                 'mer_prioridad_venta', 'mer_temporada' ),
          'datos2',
          array('legend' => 'Datos Logística y Venta') );
        
        $datos2 = $this->getDisplayGroup('datos2');
        
        $datos2->setDecorators(array(
          'FormElements',
          'Fieldset',
          array('HtmlTag',array('tag'=>'div','class'=>'span4 pull-left'))
        ));
        
// * * * * GRUPO DESPLIEGUE DATOS AVANZADOS
        $this->addDisplayGroup(
          array( 'mer_foto', 'mer_modelo', 'mer_descripcion', 'mer_sexo' ),
          'datos3',
          array('legend' => 'Datos Avanzados') );
        
        $datos3 = $this->getDisplayGroup('datos3');
        
        $datos3->setDecorators(array(
          'FormElements',
          'Fieldset',
          array('HtmlTag',array('tag'=>'div','class'=>'span4'))
        ));
    }
}

