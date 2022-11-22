<?php
namespace App\Controller;

class LoginApiController extends AbstractApiController
{
    public function login(array $params)
    {
        if ($errors = $this->validateSchemaWithErrorReponse($params, 'UserLogin.json')) {
            return $errors;
        }

        $repo = $this->serviceLocator->get('userRepo');
        $user = $repo->getByUserName($params['username']);

        if (!$user || !password_verify($params['password'], $user->password)) {
            return [
                'success' => false,
                'errors' => [
                    [
                        'property' => 'username',
                        'message' => 'Invalid username or password',
                    ],
                ],
            ];
        }

        $data = $user->getArrayCopy();
        unset($data['password']);
        $data['token'] = $this->serviceLocator->get('authUser')->toSession($user);

        return [
            'success' => true,
            'user' => $data,
        ];

    }
}
