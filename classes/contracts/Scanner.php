<?php declare(strict_types=1);

namespace LeMaX10\Antivirus\Classes\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface Scanner
 * @package LeMaX10\Antivirus\Classes\Contracts
 */
interface Scanner
{
    /**
     * @param string $mode
     * @return Collection
     */
    public function scan(string $mode): Collection;

    /**
     * @param \RecursiveIteratorIterator $iterator
     * @return Collection
     */
    public function loadingStructure(\RecursiveIteratorIterator $iterator): Collection;

    /**
     * @param \RecursiveIteratorIterator $iterator
     * @return Collection
     */
    public function verificationStructure(\RecursiveIteratorIterator $iterator): Collection;

    /**
     * @param string $directory
     * @return $this
     */
    public function setDirectory(string $directory): self;

    /**
     * @return \RecursiveDirectoryIterator
     */
    public function getDirectory(): \RecursiveDirectoryIterator;

    /**
     * @param Repository $repository
     * @return $this
     */
    public function setRepository(Repository $repository): self;

    /**
     * @return Repository
     */
    public function getRepository(): Repository;
}
