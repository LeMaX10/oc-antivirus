<?php declare(strict_types=1);

namespace LeMaX10\Antivirus\Classes\Entities;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Entity
 * @package LeMaX10\Antivirus\Classes\Entities
 */
abstract class Entity implements Arrayable
{
    /**
     * @var array
     */
    protected $raw;

    /**
     * Entity constructor.
     * @param array $raw
     */
    public function __construct(array $raw = [])
    {
        $this->raw = $raw;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->raw;
    }
}
