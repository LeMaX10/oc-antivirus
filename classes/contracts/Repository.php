<?php declare(strict_types=1);

namespace LeMaX10\Antivirus\Classes\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface Repository
 * @package LeMaX10\Antivirus\Classes\Contracts
 */
interface Repository
{
    /**
     * @param string $path
     * @return RepositoryItem|null
     */
    public function findByPath(string $path): ?RepositoryItem;

    /**
     * @param string $path
     * @return Collection
     */
    public function findAllByPath(string $path): Collection;
}
