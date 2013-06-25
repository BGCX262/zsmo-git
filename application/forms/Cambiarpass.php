<?php

class Application_Form_Cambiarpass extends Zend_Form
{

    public function init()
    {

        $this->setName('cambiarpass')->setAttrib('class','form-horizontal');
        
        $usu_id_usuario = new Zend_Form_Element_Hidden('usu_id_usuario');
        $usu_id_usuario->addFilter('Int');

        $usu_password_old = new Zend_Form_Element_Password('usu_password_old');
        $usu_password_old->setAttrib('placeholder','Password')->setLabel('Password:')
               ->setRequired(true)->addFilter('StripTags')->addFilter('StringTrim')->addValidator('NotEmpty');
        
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
            $usu_id_usuario,
            $usu_password_old,
            $usu_password,
            $usu_password_2,
            $submit));

    
    }


}

