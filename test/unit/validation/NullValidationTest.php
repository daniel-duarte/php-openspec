<?php

use PHPUnit\Framework\TestCase;
use OpenSpec\SpecBuilder;
use OpenSpec\Spec\Type\NullSpec;
use OpenSpec\Spec\Type\Spec;
use OpenSpec\ParseSpecException;


final class NullValidationTest extends TestCase
{
    protected function getSpecInstance(): Spec
    {
        $specData = ['type' => 'null'];
        $spec = SpecBuilder::getInstance()->build($specData);

        return $spec;
    }

    protected function getValidValueInstance()
    {
        return null;
    }

    protected function getInvalidValueInstance()
    {
        return 'a string, invalid boolean value';
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
