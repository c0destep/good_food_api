<?php

namespace App\Controllers;

use App\Models\UsersModel;
use Framework\HTTP\Response;
use Framework\MVC\Controller;
use JsonException;

class AuthenticationController extends Controller
{
    /**
     * @var string
     */
    protected string $modelClass = UsersModel::class;

    /**
     * @throws JsonException
     */
    public function login(): Response
    {
        return $this->response->setJson([
            'status' => 'running'
        ]);
    }

    /**
     * @throws JsonException
     */
    public function register(): Response
    {
        return $this->response->setJson([
            'status' => 'running'
        ]);
    }
}
