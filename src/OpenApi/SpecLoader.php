<?php

namespace OpenApi;

use GenericEntity\FactorySingleton;
use GenericEntity\SpecException;
use Symfony\Component\Yaml\Yaml;


class SpecLoader
{
    const OPENAPI_SPEC_NAME = 'OpenApi';

    protected $_openApiSpec = null;

    public function __construct()
    {
        $this->_openApiSpec = $this->_loadOpenApiSpec();
    }

    public function load($specFile)
    {
        $errors = [];

        $userSpec = Yaml::parseFile($specFile);
        if ($userSpec === null) {
            $errors[] = "Not valid Yaml format in file '$specFile'.";
        } else {

            try {
                $openapiUserSpec = $this->_parse($userSpec);
            } catch (\GenericEntity\SpecException $ex) {
                $errors = $ex->getErrors();
                $openapiUserSpec = null;
            }
        }

        if (count($errors) > 0) {
            throw new SpecException("Invalid spec in file '$specFile'.", $errors);
        }

        return $openapiUserSpec;
    }

    protected function _parse($userSpec)
    {
        $factory = FactorySingleton::getInstance();

        return $factory->createEntity(static::OPENAPI_SPEC_NAME, $userSpec);
    }

    protected function _loadOpenApiSpec()
    {
        $entityFactory = FactorySingleton::getInstance();

        $metaSpecOpenApi = [
            'openapi'      => ['type' => 'string'],
            'info'         => ['type' => 'object',
                               'fields' => [
                                   'title'          => ['type' => 'string'],
                                   'description'    => ['type' => 'string'],
                                   'termsOfService' => ['type' => 'string'],
                                   'contact'        => ['type' => 'object',
                                                 'fields' => [
                                                     'name'  => ['type' => 'string'],
                                                     'url'   => ['type' => 'string'],
                                                     'email' => ['type' => 'string'],
                                                 ]
                                   ],
                                   'license'        => ['type' => 'object',
                                                 'fields' => [
                                                     'name'  => ['type' => 'string'],
                                                     'url'   => ['type' => 'string'],
                                                 ]
                                   ],
                                   'version'        => ['type' => 'string']
                               ],
            ],
            'servers'      => ['type'  => 'array',
                               'items' => []
            ],
//            'paths'        => ['type' => 'object'],
//            'components'   => ['type' => 'object'],
//            'security'     => ['type' => 'array'],
//            'tags'         => ['type' => 'array'],
//            'externalDocs' => ['type' => 'object'],
        ];

        $openApiSpec = $entityFactory->createSpec(static::OPENAPI_SPEC_NAME, $metaSpecOpenApi, false);

        return $openApiSpec;
    }
}
