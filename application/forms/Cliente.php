<?php
class Application_Form_Cliente extends Zend_Form
{
    public function init()
    {
        $this->setName('cliente')->setAttrib('class','form-horizontal');
        
        $cli_id_cliente = new Zend_Form_Element_Hidden('cli_id_cliente');
        $cli_id_cliente->addFilter('Int');
        
        $cli_nombre = new Zend_Form_Element_Text('cli_nombre');
        $cli_nombre ->setAttrib('placeholder','Nombres')
                  ->setLabel('Nombres:')
                  ->setRequired(true)->addFilter('StripTags')
                  ->addFilter('StringTrim')
                  ->addValidator('NotEmpty');
        
        $cli_apellido_1 = new Zend_Form_Element_Text('cli_apellido_1');
        $cli_apellido_1->setAttrib('placeholder','Apellido Paterno')->setLabel('Apellido Paterno:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $cli_apellido_2 = new Zend_Form_Element_Text('cli_apellido_2');
        $cli_apellido_2->setAttrib('placeholder','Apellido Paterno')->setLabel('Apellido Paterno:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $cli_rut = new Zend_Form_Element_Text('cli_rut');
        $cli_rut->setAttrib('placeholder','RUT')->setLabel('RUT:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $cli_fono_1 = new Zend_Form_Element_Text('cli_fono_1');
        $cli_fono_1->setAttrib('placeholder','Teléfono Móvil')->setLabel('Teléfono Móvil:')
               ->addFilter('StripTags')->addFilter('StringTrim');
        
        $cli_fono_2 = new Zend_Form_Element_Text('cli_fono_2');
        $cli_fono_2->setAttrib('placeholder','Teléfono Fijo')->setLabel('Teléfono Fijo:')
               ->addFilter('StripTags')->addFilter('StringTrim');

        $cli_direccion = new Zend_Form_Element_Text('cli_direccion');
        $cli_direccion->setAttrib('placeholder','Dirección')->setLabel('Dirección:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $cli_lugar_de_trabajo = new Zend_Form_Element_Text('cli_lugar_de_trabajo');
        $cli_lugar_de_trabajo->setAttrib('placeholder','Dirección')->setLabel('Lugar de trabajo:')
               ->addFilter('StripTags')->addFilter('StringTrim');
        
        $cli_ciudad = new Zend_Form_Element_Text('cli_ciudad');
        $cli_ciudad->setAttrib('placeholder','Ciudad')->setLabel('Ciudad:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array(
            $cli_id_cliente,
            $cli_rut,
            $cli_nombre,
            $cli_apellido_1,
            $cli_apellido_2,
            $cli_fono_1,
            $cli_fono_2,
            $cli_direccion,
            $cli_lugar_de_trabajo,
            $cli_ciudad,
            $submit));
    }
}

?>