<?php

class Application_Form_Login extends Zend_Form
{
    public function init()
    {
?>
<style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #fff;
      }

      .form-signin {
        max-width: 400px;
        padding: 210px 29px 29px;
        margin: 0 auto 20px;
        background-color: #E31F24;
        background-image: url("/img/logosmo01.png");
        background-repeat: no-repeat;
        background-position: top center;
        border: 0px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
</style>
<?php
        $this->setAttrib('class','form-signin');
        $this->addElement(
                'text','rut', array(
                    'class'=>'input-block-level',
                    'placeholder'=>'RUT',
                    'required'=>true
                )
        );
        
        $this->addElement(
                'password', 'pass', array(
                    'class'=>'input-block-level',
                    'placeholder'=>'Password',
                    'required'=>true
                )
        );
        
        $this->addElement(
                'submit', 'Entrar', array(
                    'class'=>'btn btn-large btn-inverse',
                    'id'=>'send',
                )
        ); 

    }
}
?>