<?php


namespace LeMaX10\Antivirus\Classes\Entities;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use LeMaX10\Antivirus\Classes\Contracts\Diff;
use LeMaX10\Antivirus\Classes\Contracts\RepositoryItem;

/**
 * Class Item
 * @package LeMaX10\Antivirus\Classes\Entities
 */
class Item extends Entity implements RepositoryItem
{
    /**
     * @return string
     */
    public function getFileName(): string
    {
        return (string) Arr::get($this->raw, 'filename');
    }

    /**
     * @return Carbon
     */
    public function getMTime(): Carbon
    {
        return new Carbon($this->raw['mtime']);
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return (int) Arr::get($this->raw, 'size');
    }

    /**
     * @return string
     */
    public function getPerms(): string
    {
        return (string) Arr::get($this->raw, 'perms');
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return (string) Arr::get($this->raw, 'type');
    }

    public function getChanges(): Diff
    {
        $changes = Arr::get($this->raw, 'changes');
        if (null === $changes) {
            $changes = new ItemChange;
        }

        return $changes;
    }

    /**
     * @param string $filename
     * @return RepositoryItem
     */
    public function setFileName(string $filename): RepositoryItem
    {
        $this->raw['filename'] = $filename;
        return $this;
    }

    /**
     * @param Carbon $date
     * @return RepositoryItem
     */
    public function setMTime(Carbon $date): RepositoryItem
    {
        $this->raw['mtime'] = $date;
        return $this;
    }

    /**
     * @param int $size
     * @return RepositoryItem
     */
    public function setSize(int $size): RepositoryItem
    {
        $this->raw['size'] = $size;
        return $this;
    }

    /**
     * @param string $perms
     * @return RepositoryItem
     */
    public function setPerms(string $perms): RepositoryItem
    {
        $this->raw['perms'] = substr(sprintf('%o', $perms), -4);
        return $this;
    }

    /**
     * @param string $type
     * @return RepositoryItem
     */
    public function setType(string $type): RepositoryItem
    {
        $this->raw['type'] = $type;

        return $this;
    }

    public function setChanges(Diff $diff): RepositoryItem
    {
        $this->raw['changes'] = $diff;
        return $this;
    }
}
