<?php

declare(strict_types=1);

namespace Daison\Paysera\Parsers;

use Daison\Paysera\Contracts\ShouldProvideCollection;
use Daison\Paysera\Transformers\Collection;

/**
 * @author Daison Carino <daison12006013@gmail.com>
 */
class Arrayable implements ShouldProvideCollection
{
    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $collections = [];

    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Undocumented function.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function collections(): array
    {
        if (empty($this->collections)) {
            foreach ($this->data as $datum) {
                $this->collections[] = new Collection($datum);
            }
        }

        return $this->collections;
    }
}
