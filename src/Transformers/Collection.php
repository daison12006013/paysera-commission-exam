<?php

declare(strict_types=1);

namespace Daison\Paysera\Transformers;

/**
 * @author Daison Carino <daison12006013@gmail.com>
 */
class Collection
{
    /**
     * Undocumented variable.
     *
     * @var array
     */
    private $record = [];

    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $map = [
        'date'          => 0,
        'userId'        => 1,
        'userType'      => 2,
        'operationType' => 3,
        'amount'        => 4,
        'currency'      => 5,
    ];

    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $values = [];

    /**
     * Undocumented function.
     */
    public function __construct(array $record)
    {
        $this->record = $record;
    }

    /**
     * Undocumented function.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __call($name, array $args)
    {
        if (isset($this->map[$name])) {
            return $this->record[$this->map[$name]];
        }
    }

    /**
     * Undocumented function.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function setValue($key, $value)
    {
        $this->values[$key] = $value;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getValue($key)
    {
        if (!isset($this->values[$key])) {
            return null;
        }

        return $this->values[$key];
    }
}
