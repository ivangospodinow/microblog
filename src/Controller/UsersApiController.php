<?php
namespace App\Controller;

use App\Entity\UserEntity;

class UsersApiController extends AbstractApiController
{
    public function index(array $params)
    {
        if ($errors = $this->validateSchemaWithErrorReponse($params, 'UsersList.json')) {
            return $errors;
        }

        $limit = $this->getLimit($params);
        $offset = $this->getOffset($params);

        return [
            'requrest' => $params,
            'list' => $this->arrayToList(
                $this->serviceLocator->get('userRepo')->getList(
                    $limit,
                    $offset
                )
            ),
        ];
    }

    public function create(array $params)
    {
        if ($errors = $this->validateSchemaWithErrorReponse($params, 'UserCreate.json')) {
            return $errors;
        }

        $repo = $this->serviceLocator->get('userRepo');

        // so that username is not changed between check it is available and updating it
        $repo->transactionStart();
        if ($repo->getByUserName($params['username'])) {
            return [
                'success' => false,
                'errors' => [
                    [
                        'property' => 'username',
                        'message' => 'Username already exists. Please try a different one or log in.',
                    ],
                ],
            ];
        }

        $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);

        $user = new UserEntity($params);
        $this->serviceLocator->get('userRepo')->save($user);

        $repo->transactionCommit();

        return [
            'success' => true,
            'id' => $user->getId(),
        ];
    }

    public function update(array $params, array $routeParams)
    {
        if (!isset($routeParams['id'])) {
            throw new \Exception('id is required');
        }

        $id = $routeParams['id'];
        $params['id'] = (int) $id;

        if ($errors = $this->validateSchemaWithErrorReponse($params, 'UserUpdate.json')) {
            return $errors;
        }

        $repo = $this->serviceLocator->get('userRepo');
        $user = $repo->find($id);

        if (!$user) {
            return [
                'success' => false,
                'errors' => [
                    [
                        'property' => 'id',
                        'message' => 'Username does not exists.',
                    ],
                ],
            ];
        }

        // so that username is not changed between check it is available and updating it
        $repo->transactionStart();

        $userForUserName = $repo->getByUserName($params['username']);
        if ($userForUserName && $user->id !== $userForUserName->id) {
            return [
                'success' => false,
                'errors' => [
                    [
                        'property' => 'username',
                        'message' => 'Username already exists. Please try a different one.',
                    ],
                ],
            ];
        }

        if (isset($params['password']) && !empty($params['password'])) {
            $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
        }

        $user->exchangeArray($params);
        $repo->save($user);

        $repo->transactionCommit();

        return [
            'success' => true,
            'id' => $user->getId(),
        ];
    }

    public function delete(array $params, array $routeParams)
    {
        if (!isset($routeParams['id'])) {
            throw new \Exception('id is required');
        }

        $id = $routeParams['id'];
        $repo = $this->serviceLocator->get('userRepo');
        $user = $repo->find($id);

        if (!$user) {
            return [
                'success' => false,
                'errors' => [
                    [
                        'property' => 'id',
                        'message' => 'User is already deleted.',
                    ],
                ],
            ];
        }

        $repo->delete($user);

        return [
            'success' => true,
            'id' => $user->getId(),
        ];
    }

    protected function arrayToList(array $result): array
    {
        $array = parent::arrayToList($result);
        foreach ($array as $key => $pair) {
            unset($array[$key]['password']);
        }
        return $array;
    }
}
