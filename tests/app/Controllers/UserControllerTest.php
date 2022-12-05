<?php

namespace Tests\app\Controllers;

use Framework\HTTP\Status;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    public function testCreate()
    {

    }

    public function testShow()
    {

    }

    public function testReplace()
    {

    }

    public function testUpdate()
    {

    }

    public function testDelete()
    {

    }

    public function testIndex()
    {
        $this->app->runHttp('http://localhost:8080/api/users');
        self::assertResponseStatusCode(Status::OK);
    }
}
