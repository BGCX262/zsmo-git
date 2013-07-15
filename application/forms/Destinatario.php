<?php

class Application_Form_Destinatario extends Zend_Form
{

    public function init()
    {
        $this->setName('destinatario')->setAttrib('class','form-horizontal');
        
        $des_id_destinatario = new Zend_Form_Element_Hidden('des_id_destinatario');
        $des_id_destinatario->addFilter('Int');
        
        $des_nombre = new Zend_Form_Element_Text('des_nombre');
        $des_nombre ->setAttrib('placeholder','Nombre')
                  ->setLabel('Nombre:')
                  ->setRequired(true)->addFilter('StripTags')
                  ->addFilter('StringTrim')
                  ->addValidator('NotEmpty');

        $des_rut = new Zend_Form_Element_Text('des_rut');
        $des_rut->setAttrib('placeholder','RUT')->setLabel('RUT:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $des_direccion = new Zend_Form_Element_Text('des_direccion');
        $des_direccion->setAttrib('placeholder','Dirección')->setLabel('Dirección:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $des_ciudad = new Zend_Form_Element_Text('des_ciudad');
        $des_ciudad->setAttrib('placeholder','Ciudad')->setLabel('Ciudad:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        
        $des_telefono = new Zend_Form_Element_Text('des_telefono');
        $des_telefono->setAttrib('placeholder','Teléfono')->setLabel('Teléfono:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $des_tipo = new Zend_Form_Element_Select('des_tipo');
        $des_tipo->setAttrib('placeholder','Tipo')->setLabel('Tipo:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        $des_tipo->addMultiOption( "CLIENTE", "Cliente" );
        $des_tipo->addMultiOption( "PROVEEDOR", "Proveedor" );
        $des_tipo->addMultiOption( "INTERNO", "Tienda SMO (Interno)" );
        
        $des_comuna = new Zend_Form_Element_Text('des_comuna');
        $des_comuna->setAttrib('placeholder','Comuna')->setLabel('Comuna:')
               ->addFilter('StripTags')->addFilter('StringTrim');
        
        $des_region = new Zend_Form_Element_Text('des_region');
        $des_region->setAttrib('placeholder','Región')->setLabel('Región:')
               ->addFilter('StripTags')->addFilter('StringTrim');

        $des_contacto = new Zend_Form_Element_Text('des_contacto');
        $des_contacto->setAttrib('placeholder','Contacto')->setLabel('Contacto:')
               ->addFilter('StripTags')->addFilter('StringTrim');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array(
            $des_id_destinatario,
            $des_nombre,
            $des_rut,
            $des_direccion,
            $des_ciudad,
            $des_telefono,
            $des_tipo,
            $des_comuna,
            $des_region,
            $des_contacto,
            $submit));
    }


}

