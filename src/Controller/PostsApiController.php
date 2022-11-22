<?php
namespace App\Controller;

use App\Entity\PostEntity;

class PostsApiController extends AbstractApiController
{
    public function index(array $params)
    {
        $params = $this->prepareListParams($params);
        if ($errors = $this->validateSchemaWithErrorReponse($params, 'PostsList.json')) {
            return $errors;
        }

        return [
            'requrest' => $params,
            'list' => $this->arrayToList(
                $this->serviceLocator->get('postRepo')->getList($params)
            ),
        ];
    }

    public function create(array $params)
    {
        if ($errors = $this->validateSchemaWithErrorReponse($params, 'PostCreate.json')) {
            return $errors;
        }

        if (!empty($params['image'])) {
            $imageService = $this->serviceLocator->get('imageStoreService');
            $newImagePath = $imageService->storeFromBase64String($params['image']);
            if ($newImagePath) {
                $params['image'] = $newImagePath;
            }
        }

        $params['createdBy'] = $this->serviceLocator->get('authUser')->getLoggedUserId();
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

        if (!empty($params['image'])) {
            $imageService = $this->serviceLocator->get('imageStoreService');
            $newImagePath = $imageService->storeFromBase64String($params['image']);
            if ($newImagePath) {
                $params['image'] = $newImagePath;

                // delete old image
                if ($post->image) {
                    $imageService->removeImage($post->image);
                }
            }
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

    public function months()
    {
        return [
            'list' => $this->serviceLocator->get('postRepo')->getMonths(),
        ];
    }

}
