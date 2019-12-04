<?php

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

    public function setRawFee($rawFee)
    {
        $this->rawFee = $rawFee;

        return $this;
    }

    public function setFinalFee($finalFee)
    {
        $this->finalFee = $finalFee;

        return $this;
    }
}
