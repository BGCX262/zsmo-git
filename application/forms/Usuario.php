<?php
class Application_Form_Usuario extends Zend_Form
{
    public function init()
    {
        $this->setName('usuario')->setAttrib('class','form-horizontal');
        
        $usu_id_usuario = new Zend_Form_Element_Hidden('usu_id_usuario');
        $usu_id_usuario->addFilter('Int');
        
        $usu_nombre = new Zend_Form_Element_Text('usu_nombre');
        $usu_nombre->setAttrib('placeholder','Nombres')->setLabel('Nombres:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $usu_apellido_1 = new Zend_Form_Element_Text('usu_apellido_1');
        $usu_apellido_1->setAttrib('placeholder','Apellido Paterno')->setLabel('Apellido Paterno:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $usu_apellido_2 = new Zend_Form_Element_Text('usu_apellido_2');
        $usu_apellido_2->setAttrib('placeholder','Apellido Materno')->setLabel('Apellido Materno:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $usu_rut = new Zend_Form_Element_Text('usu_rut');
        $usu_rut->setAttrib('placeholder','RUT')->setLabel('RUT:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $usu_fono_1 = new Zend_Form_Element_Text('usu_fono_1');
        $usu_fono_1->setAttrib('placeholder','Teléfono Móvil')->setLabel('Teléfono Móvil:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $usu_fono_2 = new Zend_Form_Element_Text('usu_fono_2');
        $usu_fono_2->setAttrib('placeholder','Teléfono Fijo')->setLabel('Teléfono Fijo:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $usu_direccion = new Zend_Form_Element_Text('usu_direccion');
        $usu_direccion->setAttrib('placeholder','Dirección')->setLabel('Dirección:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $usu_ciudad = new Zend_Form_Element_Text('usu_ciudad');
        $usu_ciudad->setAttrib('placeholder','Ciudad')->setLabel('Ciudad:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');

        $usu_porcentaje_comision = new Zend_Form_Element_Select('usu_porcentaje_comision');
        $usu_porcentaje_comision->setLabel('Pctje. de comisión:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        $usu_porcentaje_comision->addMultiOption("0","0%");
        $usu_porcentaje_comision->addMultiOption("3","3%");
        $usu_porcentaje_comision->addMultiOption("5","5%");

        $per_id_perfil = new Zend_Form_Element_Multiselect('per_id_perfil');
        $per_id_perfil->setLabel('Perfil(es) de usuario:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')
               ->setAttrib('multiple','multiple');
        $filaPerfil = new Application_Model_DbTable_Perfil();
        foreach ($filaPerfil->fetchAll() as $per) :
          $per_id_perfil->addMultiOption($per->per_id_perfil,$per->per_nombre);
        endforeach;
        
        $usu_password = new Zend_Form_Element_Password('usu_password');
        $usu_password->setAttrib('placeholder','Password')->setLabel('Password:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
        $usu_password_2 = new Zend_Form_Element_Password('usu_password_2');
        $usu_password_2->setAttrib('placeholder','Reingrese el Password')->setLabel('Password (2):')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        $usu_password_2->addValidator('Identical', false, array('token' => 'usu_password'));        


        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array(
            $usu_id_usuario, $usu_rut, $usu_nombre, $usu_apellido_1, $usu_apellido_2,
            $usu_fono_1, $usu_fono_2, $usu_direccion, $usu_ciudad, $usu_password,
            $usu_password_2, $per_id_perfil, $usu_porcentaje_comision, $submit));

//************* contacto
        $this->addDisplayGroup(
          array( 'usu_nombre', 'usu_apellido_1', 'usu_apellido_2', 'usu_fono_1', 'usu_fono_2', 'usu_direccion', 'usu_ciudad' ),
          'personal' );
        
        $personal = $this->getDisplayGroup('personal');
        
        $personal->setDecorators(array(
          'FormElements',
          'Fieldset',
          array('HtmlTag',array('tag'=>'div','class'=>'span4'))
        ));

//************* sistema
        $this->addDisplayGroup(
          array( 'usu_rut', 'usu_password', 'usu_password_2', 'per_id_perfil', 'usu_porcentaje_comision' ),
          'sistema' );
        
        $sistema = $this->getDisplayGroup('sistema');
        
        $sistema->setDecorators(array(
          'FormElements',
          'Fieldset',
          array('HtmlTag',array('tag'=>'div','class'=>'span4'))
        ));

    }
}

?>