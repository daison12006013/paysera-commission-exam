<?php

namespace Daison\Paysera\Tests\Filesystem;

use Daison\Paysera\Filesystem\Local;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class LocalTest extends TestCase
{
    public function testFilesystem()
    {
        $fs = new Local;
        $fs->setPath($path = __DIR__ . '/../sample.txt');

        $this->assertEquals($fs->getPath(), $path);
        $this->assertEquals($fs->getContents(), "Lorem ipsum dolor\n");
    }

    public function testNotExistingFile()
    {
        $fs = new Local;

        try {
            $fs->setPath($path = __DIR__ . '/sample.jpg');
        } catch (FileNotFoundException $e) {
            $this->assertEquals(
                $e->getMessage(),
                'File "' . $path . '" could not be found.'
            );
        }
    }
}
