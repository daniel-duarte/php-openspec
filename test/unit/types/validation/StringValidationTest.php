<?php

use PHPUnit\Framework\TestCase;
use OpenSpec\SpecBuilder;
use OpenSpec\Spec\Type\TypeSpec;
use OpenSpec\SpecLibrary;


final class StringValidationTest extends TestCase
{
    protected function getSpecInstance(): TypeSpec
    {
        $specData = ['type' => 'string'];
        $spec = SpecBuilder::getInstance()->build($specData, new SpecLibrary());

        return $spec;
    }

    protected function getValidValueInstance()
    {
        return 'a testing string';
    }

    protected function getInvalidValueInstance()
    {
        return 54321;
    }

    public function testValidValue()
    {
        $spec  = $this->getSpecInstance();
        $value = $this->getValidValueInstance();

        $result = $spec->validate($value);

        $this->assertTrue($result, "Given value not recognized by the spec, even when it should.");
    }

    public function testInvalidValue()
    {
        $spec  = $this->getSpecInstance();
        $value = $this->getInvalidValueInstance();

        $result = $spec->validate($value);

        $this->assertTrue(!$result, "Given value recognized by the spec, even when it should not.");
    }
}
