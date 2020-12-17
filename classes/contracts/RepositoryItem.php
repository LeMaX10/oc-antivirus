<?php


namespace LeMaX10\Antivirus\Classes\Contracts;


use Carbon\Carbon;
use Illuminate\Support\Arr;

/**
 * Interface RepositoryItem
 * @package LeMaX10\Antivirus\Classes\Contracts
 */
interface RepositoryItem
{
    /**
     * @return string
     */
    public function getFileName(): string;

    /**
     * @return Carbon
     */
    public function getMTime(): Carbon;

    /**
     * @return int
     */
    public function getSize(): int;

    /**
     * @return string
     */
    public function getPerms(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return Diff
     */
    public function getChanges(): Diff;

    /**
     * @param string $filename
     * @return $this
     */
    public function setFileName(string $filename): self;

    /**
     * @param Carbon $date
     * @return $this
     */
    public function setMTime(Carbon $date): self;

    /**
     * @param int $size
     * @return $this
     */
    public function setSize(int $size): self;

    /**
     * @param string $perms
     * @return $this
     */
    public function setPerms(string $perms): self;

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self;

    /**
     * @param Diff $diff
     * @return $this
     */
    public function setChanges(Diff $diff): self;
}
