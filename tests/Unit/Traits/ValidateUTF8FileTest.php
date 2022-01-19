<?php

namespace Tests\Unit\Traits;

use App\Traits\ValidateUTF8File;
use Tests\TestCase;

class ValidateUTF8FileTest extends TestCase
{
    private $mock;
    public function setUp(): void
    {
        parent::setUp();
        $this->mock = $this->getMockForTrait(ValidateUTF8File::class);
    }
    /**
     * @group Traits
     * @group json
     * @test
     */
    public function isUtf8FileValid()
    {
        $this->assertTrue(
            $this->mock->isUTF8File('tests/resources/challenge-fileOk.json')
        );
    }
    /**
     * @group Traits
     * @group json
     * @test
     */
    public function isUtf8FileNoFileInvalid()
    {
        $this->assertFalse(
            $this->mock->isUTF8File('INEXISTENT/challenge-fileOk.json')
        );
    }
    /**
     * @group Traits
     * @group json
     * @test
     */
    public function isUtf8FileNullInvalid()
    {
        $this->assertFalse($this->mock->isUTF8File(''));
    }
}
