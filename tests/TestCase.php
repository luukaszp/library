<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use JWTAuth;
use JWTFactory;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}