<?php

require_once 'test_config.php';
require_once LIB.'FormValidator.php';

class FormValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider emailProvider
     */
    public function testIsEmailAddress($a,$b,$c)
    {
        $fv = new FormValidator();
        $this->assertTrue($fv->isEmailAddress($a,$b,$c));
    }

    public function emailProvider()
    {
        $handle = fopen(TEST_CASES . 'lib/' . get_class($this) . '/emailProvider.txt', 'r');
        
        if ($handle === false)
        {
            return false;
        }

        $testCases = array();

        while (!feof($handle))
        {
            $testCase = fgets($handle);
            array_push($testCases,explode(",",$testCase));
        }
        
        fclose($handle);

        return $testCases;
    }
}
?>