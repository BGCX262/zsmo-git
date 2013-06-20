<?php

class Application_Form_Mensaje extends Zend_Form
{

    public function init()
    {
        $this->setName('mensaje')->setAttrib('class','form-horizontal');
        
        $men_id_mensaje = new Zend_Form_Element_Hidden('men_id_mensaje');
        $men_id_mensaje->addFilter('Int');
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        $usuario = $identity->usu_id_usuario;

        $usu_id_usuario = new Zend_Form_Element_Hidden('usu_id_usuario');
        $usu_id_usuario->addFilter('Int')->setValue( $usuario );
        
        $men_mensaje = new Zend_Form_Element_Textarea('men_mensaje');
        $men_mensaje->setAttrib('placeholder','Ingrese el nuevo mensaje')->setLabel('Mensaje:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty')
                ->setAttrib('cols', '60')
                ->setAttrib('rows', '4');
        
        $date = date('Y/m/d H:i:s');
        $men_fecha = new Zend_Form_Element_Text('men_fecha');
        $men_fecha->setValue( $date )->setLabel('Fecha:')->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array(
            $men_id_mensaje, $usu_id_usuario, $men_fecha, $men_mensaje, $submit));

//************* contacto
        $this->addDisplayGroup(
          array( 'men_id_mensaje', 'men_fecha', 'men_mensaje', 'submit' ),
          'mensaje',
          array('legend' => 'Mensaje') );
        
        $mensaje = $this->getDisplayGroup('mensaje');
        
        $mensaje->setDecorators(array(
          'FormElements',
          'Fieldset',
          array('HtmlTag',array('tag'=>'div','class'=>'span4'))
        ));
      
    }


}

