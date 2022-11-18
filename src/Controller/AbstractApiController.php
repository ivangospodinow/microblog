<?php
namespace App\Controller;

use App\Service\ServiceLocatorService;

abstract class AbstractApiController
{
    protected $serviceLocator;

    public function __construct(ServiceLocatorService $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    protected function arrayToList(array $result): array
    {
        $array = [];
        foreach ($result as $row) {
            $array[] = $row->getArrayCopy();
        }
        return $array;
    }

    protected function validateSchemaWithErrorReponse(array $params, string $schema)
    {
        $validator = new \JsonSchema\Validator;
        $objectToValidate = (object) $params;
        $validator->validate(
            $objectToValidate, (object)
            [
                '$ref' => 'file://' . realpath('schema/' . $schema),
            ]
        );

        if ($validator->isValid()) {
            return false;
        }

        return [
            'success' => false,
            'errors' => $validator->getErrors(),
        ];
    }

    protected function getLimit(array $params)
    {
        return $params['list']['limit'] ?? 10;
    }

    protected function getOffset(array $params)
    {
        return ($params['list']['page'] ?? 0) * $this->getLimit($params);
    }
}
