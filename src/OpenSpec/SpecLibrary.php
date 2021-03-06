<?php

namespace OpenSpec;

use OpenSpec\Spec\Spec;


class SpecLibrary
{
    private $_specs = [];

    public function hasSpec(string $name): bool
    {
        return array_key_exists($name, $this->_specs);
    }

    public function registerSpec(string $name, Spec $spec): SpecLibrary
    {
        if ($this->hasSpec($name)) {
            throw new SpecLibraryException();
        }

        $this->_specs[$name] = $spec;

        return $this;
    }

    public function registerSpecFromData(string $name, array $specData): SpecLibrary
    {
        $spec = SpecBuilder::getInstance()->build($specData, $this);

        return $this->registerSpec($name, $spec);
    }

    public function unregisterSpec($name)
    {
        if (!$this->hasSpec($name)) {
            throw new SpecLibraryException();
        }

        $spec = $this->_specs[$name];
        unset($this->_specs[$name]);

        return $spec;
    }

    // @todo check if this method is useful
    public function getSpecsCount(): int
    {
        return count($this->_specs);
    }

    public function unregisterAll()
    {
        $this->_specs = [];
    }

    public function getSpec($name): Spec
    {
        if (!$this->hasSpec($name)) {
            throw new SpecLibraryException();
        }

        return $this->_specs[$name];
    }

    public function validateValue(string $specName, $value): bool
    {
        $errors = $this->validateValueGetErrors($specName, $value);

        return count($errors) === 0;
    }

    public function validateValueGetErrors(string $specName, $value): array
    {
        try {
            $this->getSpec($specName)->parse($value);
        } catch (ParseSpecException $ex) {
            return $ex->getErrors();
        }

        return [];
    }
}
