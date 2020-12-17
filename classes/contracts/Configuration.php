<?php declare(strict_types=1);

namespace LeMaX10\Antivirus\Classes\Contracts;

use LeMaX10\Antivirus\Classes\Contracts\Repository as RepositoryContract;

/**
 * Interface Configuration
 * @package LeMaX10\Antivirus\Classes\Contracts
 */
interface Configuration
{
    /**
     * @return string
     */
    public function getDirectory(): string;

    /**
     * @return array
     */
    public function getWhitelist(): array;

    /**
     * @return array
     */
    public function getExcludeList(): array;

    /**
     * @return RepositoryContract
     */
    public function getRepository(): RepositoryContract;

    /**
     * @return array
     */
    public function getExtensions(): array;
}
