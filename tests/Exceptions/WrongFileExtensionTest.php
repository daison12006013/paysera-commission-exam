<?php

namespace Daison\Paysera\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Daison\Paysera\Exceptions\WrongFileExtension;

class WrongFileExtensionTest extends TestCase
{
    public function testWithCustomMessage()
    {
        $message = 'this is a message';

        try {
            throw new WrongFileExtension($message);
        } catch (WrongFileExtension $e) {
            $this->assertEquals($e->getMessage(), $message);
        }
    }

    public function testWithNoMessage()
    {
        try {
            throw new WrongFileExtension(null);
        } catch (WrongFileExtension $e) {
            $this->assertEquals($e->getMessage(), 'Wrong file extension.');
        }

        try {
            throw new WrongFileExtension(null, 0, 'jpg');
        } catch (WrongFileExtension $e) {
            $this->assertEquals(
                $e->getMessage(),
                'Expected extension must be [jpg] format'
            );
        }
    }
}
