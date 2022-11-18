<?php
namespace App\Controller;

use App\Entity\PostEntity;

class PostsApiController extends AbstractApiController
{
    public function index(array $params)
    {
        if ($errors = $this->validateSchemaWithErrorReponse($params, 'PostsList.json')) {
            return $errors;
        }

        $limit = $this->getLimit($params);
        $offset = $this->getOffset($params);

        return [
            'requrest' => $params,
            'list' => $this->arrayToList(
                $this->serviceLocator->get('postRepo')->getList(
                    $limit,
                    $offset
                )
            ),
        ];
    }

    public function create(array $params)
    {
        if ($errors = $this->validateSchemaWithErrorReponse($params, 'PostCreate.json')) {
            return $errors;
        }

        $repo = $this->serviceLocator->get('postRepo');
        $post = new PostEntity($params);
        $repo->save($post);

        return [
            'success' => true,
            'id' => $post->getId(),
        ];
    }

    public function update(array $params, array $routeParams)
    {
        if (!isset($routeParams['id'])) {
            throw new \Exception('id is required');
        }

        $id = $routeParams['id'];
        $params['id'] = (int) $id;

        if ($errors = $this->validateSchemaWithErrorReponse($params, 'PostUpdate.json')) {
            return $errors;
        }

        $repo = $this->serviceLocator->get('postRepo');
        $post = $repo->find($id);

        if (!$post) {
            return [
                'success' => false,
                'errors' => [
                    [
                        'property' => 'id',
                        'message' => 'Post does not exists.',
                    ],
                ],
            ];
        }

        $post->exchangeArray($params);
        $repo->save($post);

        return [
            'success' => true,
            'id' => $post->getId(),
        ];
    }

    public function delete(array $params, array $routeParams)
    {
        if (!isset($routeParams['id'])) {
            throw new \Exception('id is required');
        }

        $id = $routeParams['id'];
        $repo = $this->serviceLocator->get('postRepo');
        $post = $repo->find($id);

        if (!$post) {
            return [
                'success' => false,
                'errors' => [
                    [
                        'property' => 'id',
                        'message' => 'Post is already deleted.',
                    ],
                ],
            ];
        }

        $repo->delete($post);

        return [
            'success' => true,
            'id' => $post->getId(),
        ];
    }
}