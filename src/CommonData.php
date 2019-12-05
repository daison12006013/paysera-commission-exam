<?php

declare(strict_types=1);

namespace Daison\Paysera;

abstract class CommonData
{
    protected $path;

    abstract protected function collections(): array;

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getContents()
    {
        return file_get_contents($this->path);
    }
}
