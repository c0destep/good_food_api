<?php

namespace App\Controllers;

use App\Entity\UserEntity;
use App\Models\UsersModel;
use Framework\Crypto\Password;
use Framework\HTTP\Response;
use Framework\MVC\Controller;
use JsonException;
use SodiumException;

/**
 * User Controller
 */
class UserController extends Controller
{
    /**
     * @var string
     */
    protected string $modelClass = UsersModel::class;

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
     * @throws SodiumException
     */
    public function create(): Response
    {
        $errors = $this->validate($this->request->getPost(), [
            'email' => 'required|email',
            'password' => 'required|regex:/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8\,64})/'
        ], [
            'email' => lang('users.email'),
            'password' => lang('users.password')
        ]);

        if (!empty($errors)) {
            return $this->response->setJson([
                'errors' => $errors
            ]);
        }

        if (!is_null($this->model->getUserByEmail($this->request->getPost('email')))) {
            return $this->response->setJson([
                'errors' => lang('users.emailRegistered')
            ]);
        }

        $data = [];

        foreach ($this->request->getPost() as $input => $value) {
            if ($input === 'password') {
                $data[$input] = Password::hash($value);
            } else {
                $data[$input] = $value;
            }
        }

        $created = $this->model->create(new UserEntity($data));

        if ($created === false) {
            return $this->response->setJson([
                'errors' => lang('base.unexpected')
            ]);
        }

        return $this->response->setJson([
            'userId' => $created
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     */
    public function show(int $id): Response
    {
        $user = $this->model->find($id);
        return $this->response->setJson($user);
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     * @throws SodiumException
     */
    public function update(int $id): Response
    {
        $errors = $this->validate($this->request->getParsedBody(), [
            'email' => 'optional|email',
            'password' => 'optional|regex:/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8\,64})/'
        ], [
            'email' => lang('users.email'),
            'password' => lang('users.password')
        ]);

        if (!empty($errors)) {
            return $this->response->setJson([
                'errors' => $errors
            ]);
        }

        if (!is_null($this->request->getParsedBody('email'))) {
            $userEmail = $this->model->getUserByEmail($this->request->getParsedBody('email'));

            if (!is_null($userEmail) && ($userEmail->id !== $id)) {
                return $this->response->setJson([
                    'errors' => lang('users.emailRegistered')
                ]);
            }
        }

        $data = [];

        foreach ($this->request->getParsedBody() as $input => $value) {
            if ($input === 'password') {
                $data[$input] = Password::hash($value);
            } else {
                $data[$input] = $value;
            }
        }

        $updated = $this->model->update($id, $data);

        if ($updated === false) {
            return $this->response->setJson([
                'errors' => lang('base.unexpected')
            ]);
        }

        return $this->response->setJson([
            'userId' => $updated
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     * @throws SodiumException
     */
    public function replace(int $id): Response
    {
        $errors = $this->validate($this->request->getParsedBody(), [
            'email' => 'required|email',
            'password' => 'required|regex:/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8\,64})/'
        ], [
            'email' => lang('users.email'),
            'password' => lang('users.password')
        ]);

        if (!empty($errors)) {
            return $this->response->setJson([
                'errors' => $errors
            ]);
        }

        if (!is_null($this->request->getParsedBody('email'))) {
            $userEmail = $this->model->getUserByEmail($this->request->getParsedBody('email'));

            if (!is_null($userEmail) && ($userEmail->id !== $id)) {
                return $this->response->setJson([
                    'errors' => lang('users.emailRegistered')
                ]);
            }
        }

        $data = [];

        foreach ($this->request->getParsedBody() as $input => $value) {
            if ($input === 'password') {
                $data[$input] = Password::hash($value);
            } else {
                $data[$input] = $value;
            }
        }

        $replaced = $this->model->replace($id, $data);

        if ($replaced === false) {
            return $this->response->setJson([
                'errors' => lang('base.unexpected')
            ]);
        }

        return $this->response->setJson([
            'userId' => $replaced
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
                'errors' => lang('users.deleteError')
            ]);
        }

        return $this->response->setJson([
            'success' => lang('users.deleteSuccess')
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
            $user = $this->model->find($arguments[0]);

            if (is_null($user)) {
                return $this->response->setJson([
                    'errors' => lang('users.userNotFound')
                ]);
            }
        }
        return null;
    }
}
