<?php

declare(strict_types=1);

namespace Daison\Paysera\Parsers;

use Daison\Paysera\CommonData;
use Daison\Paysera\Transformers\Collection;

class Csv extends CommonData
{
    protected $collections = [];

    public function collections(): array
    {
        return $this->collections;
    }

    public function parse()
    {
        foreach ($this->getRecords() as $record) {
            $this->collections[] = new Collection($record);
        }

        return $this;
    }

    protected function getRecords()
    {
        $data = [];
        $fp   = fopen($this->getPath(), 'rb');

        while (!feof($fp)) {
            if (is_array($appendable = fgetcsv($fp))) {
                $data[] = $appendable;
            }
        }

        fclose($fp);

        return $data;
    }
}
