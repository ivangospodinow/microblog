<?php
namespace App\Controller;

use App\Service\ServiceLocatorService;
use stdClass;

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

    protected function prepareListParams(array $params): array
    {
        if (isset($params['list']['limit'])) {
            $params['list']['limit'] = (int) $params['list']['limit'];
        }
        if (isset($params['list']['page'])) {
            $params['list']['page'] = (int) $params['list']['page'];
        }
        return $params;
    }

    protected function validateSchemaWithErrorReponse(array $params, string $schema)
    {
        $validator = new \JsonSchema\Validator;
        $objectToValidate = empty($params) ? new stdClass : json_decode(json_encode($params));

        $validator->validate(
            $objectToValidate,
            (object) [
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
        return (($params['list']['page'] ?? 1) - 1) * $this->getLimit($params);
    }
}
