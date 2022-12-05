<?php

namespace App\Controllers;

use App\Models\ProductsModel;
use Framework\HTTP\Response;
use Framework\MVC\Controller;
use JsonException;

class ProductController extends Controller
{
    /**
     * @var string
     */
    protected string $modelClass = ProductsModel::class;

    /**
     * @throws JsonException
     */
    public function index(): Response
    {
        $errors = $this->validate($this->request->getGet(), [
            'limit' => 'optional|int|greater:{0}',
            'offset' => 'optional|int|greater:{0}'
        ]);

        if (!empty($errors)) {
            return $this->response->setJson($errors);
        }

        return $this->response->setJson(
            $this->model->findAll(
                $this->request->getGet('limit'),
                $this->request->getGet('offset')
            )
        );
    }

    /**
     * @throws JsonException
     */
    public function create(): Response
    {
        $created = $this->model->create(
            $this->request->getPost()
        );

        if ($created === false) {
            return $this->response->setJson(
                $this->model->getErrors()
            );
        }

        return $this->response->setJson([
            'productId' => $created
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     */
    public function show(int $id): Response
    {
        $product = $this->model->find($id);

        return $this->response->setJson(
            $product->getJsonVars()
        );
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     */
    public function update(int $id): Response
    {
        $updated = $this->model->update($id, $this->request->getPost());

        if ($updated === false) {
            return $this->response->setJson(
                $this->model->getErrors()
            );
        }

        return $this->response->setJson([
            'productId' => $updated
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     */
    public function replace(int $id): Response
    {
        $replaced = $this->model->replace($id, $this->request->getPost());

        if ($replaced === false) {
            return $this->response->setJson(
                $this->model->getErrors()
            );
        }

        return $this->response->setJson([
            'productId' => $replaced
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     */
    public function delete(int $id): Response
    {
        $deleted = $this->model->delete($id);

        if ($deleted === false) {
            return $this->response->setJson([
                'error' => lang('products.deleteError')
            ]);
        }

        return $this->response->setJson([
            'error' => lang('products.deleteSuccess')
        ]);
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return Response|null
     * @throws JsonException
     */
    protected function beforeAction(string $method, array $arguments): ?Response
    {
        if (!in_array($method, ['index', 'create'])) {
            $product = $this->model->find($arguments[0]);

            if (is_null($product)) {
                return $this->response->setJson([
                    'error' => lang('products.productNotFound')
                ]);
            }
        }
        return null;
    }
}
