<?php

class Application_Form_Cierrecaja extends Zend_Form
{

    public function init()
    {
        $this->setDisableLoadDefaultDecorators(true);
        $this->setName('cierrecaja')->setAttrib('class','form-horizontal')->setAttrib('enctype', 'multipart/form-data');

        //local al que pertenece el usuario
        $userInfo = Zend_Auth::getInstance()->getStorage()->read();
        $usuariohaslocales  = new Application_Model_DbTable_UsuarioHasLocal();
        $uhl = $usuariohaslocales->localVendedorUsuario($userInfo->usu_id_usuario);
        $local =  $uhl[0]['loc_nombre'];

        $loc_nombre =  new Zend_Form_Element_Hidden('loc_nombre');
        $loc_nombre->setValue($local);

// * * * * * * * * * * **  CIERRE DE CAJA * * * * * * ** * 
        
        //cajero
        $usu_id_usuario = new Zend_Form_Element_Select('usu_id_usuario');
        $usu_id_usuario->setAttrib('class','input-medium disabled');
        $usu_id_usuario->setValue($userInfo->usu_nombre.' '.$userInfo->usu_apellido_1.' '.$userInfo->usu_apellido_2);
        $usu_id_usuario->addMultiOption($userInfo->usu_id_usuario,$userInfo->usu_nombre.' '.$userInfo->usu_apellido_1.' '.$userInfo->usu_apellido_2);
        $usu_id_usuario->setDecorators(array( array('ViewHelper'), ));
        
        //fecha creacion (fecha actual)
        $hcj_fecha_creacion = new Zend_Form_Element_Text('hcj_fecha_creacion');
        $hcj_fecha_creacion->setAttrib('class','input-medium disabled');
        $hcj_fecha_creacion->setValue($userInfo->usu_nombre.' '.$userInfo->usu_apellido_1.' '.$userInfo->usu_apellido_2);
        $hcj_fecha_creacion->setDecorators(array( array('ViewHelper'), ));
        
        //fecha contable inicio(inicio del dia)
        $hcj_fecha_contable_inicio = new Zend_Form_Element_Text('hcj_fecha_contable_inicio');
        $hcj_fecha_contable_inicio->setAttrib('class','input-medium disabled');
        $hcj_fecha_contable_inicio->setValue('fecha inicio: getLastFecha+1sec');
        $hcj_fecha_contable_inicio->setDecorators(array( array('ViewHelper'), ));
        
        //fecha contable final(fin del dia)
        $hcj_fecha_contable_final = new Zend_Form_Element_Text('hcj_fecha_contable_final');
        $hcj_fecha_contable_final->setAttrib('class','input-medium disabled');
        $hcj_fecha_contable_final->setValue('fecha final: select manual');
        $hcj_fecha_contable_final->setDecorators(array( array('ViewHelper'), ));
        
        $cierrecaja_cb = new Zend_Form_Element_Checkbox('cierrecaja_cb');
        
// * * * * * * * * * * **  ENTREGA DE VENTAS * * * * * * ** *         
        $edv_monto_total = new Zend_Form_Element_Text('edv_monto_total');
        $edv_monto_total->setAttrib('class','input-large');
        $edv_monto_total->setDecorators(array( array('ViewHelper'), ));

        $edv_fecha = new Zend_Form_Element_Text('edv_fecha');
        $edv_fecha->setAttrib('class','input-large');
        $edv_fecha->setDecorators(array( array('ViewHelper'), ));

        $edv_monto_20mil = new Zend_Form_Element_Text('edv_monto_20mil');
        $edv_monto_20mil->setAttrib('class','input-large');
        $edv_monto_20mil->setDecorators(array( array('ViewHelper'), ));

        $edv_monto_10mil = new Zend_Form_Element_Text('edv_monto_10mil');
        $edv_monto_10mil->setAttrib('class','input-large');
        $edv_monto_10mil->setDecorators(array( array('ViewHelper'), ));
        
        $edv_monto_5mil = new Zend_Form_Element_Text('edv_monto_5mil');
        $edv_monto_5mil->setAttrib('class','input-large');
        $edv_monto_5mil->setDecorators(array( array('ViewHelper'), ));
        
        $edv_monto_2mil = new Zend_Form_Element_Text('edv_monto_2mil');
        $edv_monto_2mil->setAttrib('class','input-large');
        $edv_monto_2mil->setDecorators(array( array('ViewHelper'), ));

        $edv_monto_1mil = new Zend_Form_Element_Text('edv_monto_1mil');
        $edv_monto_1mil->setAttrib('class','input-large');
        $edv_monto_1mil->setDecorators(array( array('ViewHelper'), ));
        
        $edv_monto_500 = new Zend_Form_Element_Text('edv_monto_500');
        $edv_monto_500->setAttrib('class','input-large');
        $edv_monto_500->setDecorators(array( array('ViewHelper'), ));
        
        $edv_monto_otros_documentos = new Zend_Form_Element_Text('edv_monto_otros_documentos');
        $edv_monto_otros_documentos->setAttrib('class','input-large');
        $edv_monto_otros_documentos->setDecorators(array( array('ViewHelper'), ));
        
        $edv_monto_devoluciones = new Zend_Form_Element_Text('edv_monto_devoluciones');
        $edv_monto_devoluciones->setAttrib('class','input-large');
        $edv_monto_devoluciones->setDecorators(array( array('ViewHelper'), ));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setAttrib('class','btn btn-large btn-primary');
        $submit->setDecorators(array( array('ViewHelper'), ));
        
        $controllerFront = Zend_Controller_Front::getInstance();
        $returnUrl = $controllerFront->getRequest()->getHeader('REFERER');
        $this->addElement('hidden', 'returnUrl', array(
        'value' => $returnUrl
        ));
        
        $this->addElements(array(
            $loc_nombre,
// * * * * * * * * * * **  CIERRE DE CAJA * * * * * * ** * 
            $usu_id_usuario,
            $hcj_fecha_creacion,
            $hcj_fecha_contable_inicio,
            $hcj_fecha_contable_final,
            $cierrecaja_cb,
// * * * * * * * * * * **  ENTREGA DE VENTAS * * * * * * ** *         
            $edv_monto_total,
            $edv_fecha,
            $edv_monto_20mil,
            $edv_monto_10mil,
            $edv_monto_5mil,
            $edv_monto_2mil,
            $edv_monto_1mil,
            $edv_monto_500,
            $edv_monto_otros_documentos,
            $edv_monto_devoluciones,
// * * * * * * * * * * **  ENTREGA DE VENTAS * * * * * * **
            $submit
            ));
    }
}

