<?php

namespace Tests;

use App\Services\contracts\RandomInterface;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery\MockInterface;
use Ramsey\Uuid\UuidFactoryInterface;

abstract class TestCase extends BaseTestCase
{
    public $uuid = '0193df2c-fbb5-700a-894e-cab034252cf0';
    public $token = 'testToken45687664324563167';
    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(UuidFactoryInterface::class, function (MockInterface $mock) {
            $mock->allows('uuid7')->andReturns($this->uuid);
        });
        $this->mock(RandomInterface::class, function (MockInterface $mock) {
            $mock->allows('randomString')->andReturns($this->token);
        });
    }
}
