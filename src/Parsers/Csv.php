<?php

namespace Daison\Paysera\Parsers;

use Daison\Paysera\CommonData;
use Daison\Paysera\Transformers\Collection;

class Csv extends CommonData
{
    public function collections(): array
    {
        return $this->parse();
    }

    protected function parse()
    {
        $ret = [];

        foreach ($this->getRecords() as $record) {
            $ret[] = new Collection($record);
        }

        return $ret;
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
