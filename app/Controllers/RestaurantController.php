<?php

namespace App\Controllers;

use App\Entity\AddressEntity;
use App\Entity\RestaurantEntity;
use App\Models\AddressesModel;
use App\Models\RestaurantsModel;
use Framework\HTTP\Response;
use Framework\MVC\Controller;
use JsonException;

/**
 * Restaurant Controller
 */
class RestaurantController extends Controller
{
    /**
     * @var string
     */
    protected string $modelClass = RestaurantsModel::class;

    /**
     * @throws JsonException
     */
    public function index(): Response
    {
        $errors = $this->validate($this->request->getGet(), [
            'page' => 'optional|number|greater:0',
            'perPage' => 'optional|number|greater:0',
        ]);

        if (!empty($errors)) {
            return $this->response->setJson($errors);
        }

        $page = !empty($this->request->getGet('page', FILTER_SANITIZE_NUMBER_INT)) ? $this->request->getGet('page', FILTER_SANITIZE_NUMBER_INT) : 1;
        $perPage = !empty($this->request->getGet('perPage', FILTER_SANITIZE_NUMBER_INT)) ? $this->request->getGet('perPage', FILTER_SANITIZE_NUMBER_INT) : 10;

        return $this->response->setJson(
            $this->model->paginate($page, $perPage)
        );
    }

    /**
     * @throws JsonException
     */
    public function create(): Response
    {
        $data = array_merge($this->request->getPost(), $this->request->getFiles());

        $errors = $this->validate($data, [
            'image' => 'image|ext:jpg,png',
            'name' => 'required|string|maxLength:128',
            'businessHours' => 'required|string|maxLength:256',
            'addresses' => 'required|json'
        ], [
            'image' => lang('restaurants.image'),
            'name' => lang('restaurants.name'),
            'businessHours' => lang('restaurants.businessHours'),
            'addresses' => lang('restaurants.addresses')
        ]);

        if (!empty($errors)) {
            return $this->response->setJson([
                'errors' => $errors
            ]);
        }

        $addressesErrors = [];
        $addresses = json_decode($this->request->getPost('addresses'), true);

        foreach ($addresses as $index => $address) {
            $addressErrors = $this->validate($address, [
                'state' => 'required|maxLength:48',
                'city' => 'required|maxLength:64',
                'district' => 'optional|maxLength:64',
                'address' => 'required|maxLength:128',
                'number' => 'required|number|greater:0|maxLength:5',
                'complement' => 'required|maxLength:256'
            ], [
                'state' => lang('restaurants.state', ['index' => $index + 1]),
                'city' => lang('restaurants.city', ['index' => $index + 1]),
                'district' => lang('restaurants.district', ['index' => $index + 1]),
                'address' => lang('restaurants.address', ['index' => $index + 1]),
                'number' => lang('restaurants.number', ['index' => $index + 1]),
                'complement' => lang('restaurants.complement', ['index' => $index + 1])
            ]);

            if (!empty($addressErrors)) {
                $addressesErrors[] = $addressErrors;
            }
        }

        if (!empty($addressesErrors)) {
            return $this->response->setJson([
                'errors' => $addressesErrors
            ]);
        }

        if (!is_null($this->model->getRestaurantByName($this->request->getPost('name')))) {
            return $this->response->setJson([
                'errors' => lang('restaurants.nameRegistered')
            ]);
        }

        $data = [];

        $image = $this->request->getFile('image');
        if ($image->isValid()) {
            $filename = $this->request->getPost('name') . '.' . $image->getExtension();
            $directory = STORAGE_DIR . 'uploads' . DIRECTORY_SEPARATOR . uniqid($this->request->getPost('name'), true);
            $filepath = $directory . DIRECTORY_SEPARATOR . $filename;

            if (!is_dir($directory)) {
                mkdir($directory, 0755);
            }

            if (!$image->move($filepath, true)) {
                return $this->response->setJson([
                    'errors' => lang('base.unexpected')
                ]);
            }

            $data['image'] = $filename;
        }

        foreach ($this->request->getPost() as $input => $value) {
            $data[$input] = $value;
        }

        $created = $this->model->create(new RestaurantEntity($data));

        if ($created === false) {
            return $this->response->setJson([
                'errors' => lang('base.unexpected')
            ]);
        }

        $addressModel = new AddressesModel();

        foreach ($addresses as $address) {
            $address['idRestaurant'] = $created;
            $addressModel->create(new AddressEntity($address));
        }

        return $this->response->setJson([
            'restaurantId' => $created
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     */
    public function show(int $id): Response
    {
        $restaurant = $this->model->find($id);

        return $this->response->setJson(
            $restaurant->getJsonVars()
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
            'restaurantId' => $updated
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
            'restaurantId' => $replaced
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
                'error' => lang('restaurants.deleteError')
            ]);
        }

        return $this->response->setJson([
            'error' => lang('restaurants.deleteSuccess')
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
            $restaurant = $this->model->find($arguments[0]);

            if (is_null($restaurant)) {
                return $this->response->setJson([
                    'error' => lang('restaurants.restaurantNotFound')
                ]);
            }
        }
        return null;
    }
}
