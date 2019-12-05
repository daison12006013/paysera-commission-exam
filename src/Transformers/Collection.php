<?php

declare(strict_types=1);

namespace Daison\Paysera\Transformers;

class Collection
{
    private $record = [];

    protected $map = [
        'date'          => 0,
        'userId'        => 1,
        'userType'      => 2,
        'operationType' => 3,
        'amount'        => 4,
        'currency'      => 5,
    ];

    protected $values = [];

    public function __construct(array $record)
    {
        $this->record = $record;
    }

    public function __call($name, $args)
    {
        if (isset($this->map[$name])) {
            return $this->record[$this->map[$name]];
        }
    }

    public function setValue($key, $value)
    {
        $this->values[$key] = $value;

        return $this;
    }

    public function getValue($key)
    {
        return $this->values[$key];
    }
}
