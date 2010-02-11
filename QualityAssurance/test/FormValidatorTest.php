<?php

require_once 'test_config.php';
require_once 'formValidator.php';

class formValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider emailProvider
     */
    public function testIsEmailAddress($a,$b)
    {
        $fv = new form_validator();
        $this->assertTrue($fv->isEmailAddress($a,$b));
    }

    public function emailProvider()
    {
        return array(
          array("martijnetje@gmail.com",""),
          array("brown.k.martin@gmail.com",""),
          array("idontwantyourmail",""),
          array("martijnetje+testing@gmail.com","")
        );
    }

    /**
     *
     * @dataProvider alphaProvider
     */
    public function testIsAlpha($a,$b)
    {
        $fv = new form_validator();
        $this->assertTrue($fv->isAlpha($a,$b));
    }

    public function alphaProvider()
    {
        return array(
            array("martin24",""),
            array("24",""),
            array(24,""),
            array("martin$24",""),
            array("michael_jordan",""),
            array("_michael_jordan",""),
            array("24_martin","")
        );
    }
}
?>