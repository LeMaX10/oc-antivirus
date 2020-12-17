<?php declare(strict_types=1);

namespace LeMaX10\Antivirus\Classes\Repository;


use Illuminate\Support\Collection;
use LeMaX10\Antivirus\Classes\Contracts\Repository;
use LeMaX10\Antivirus\Classes\Contracts\RepositoryItem;
use LeMaX10\Antivirus\Classes\Entities\Item;

/**
 * Class SnapshotRepository
 * @package LeMaX10\Antivirus\Classes\Repository
 */
class SnapshotRepository extends LocalRepository implements Repository
{
    /**
     *
     */
    private const PATH = 'antivirus.snapshot';

    /**
     * @var bool
     */
    protected $encrypt = true;

    /**
     * @var string
     */
    protected $entity = Item::class;

    /**
     * @return string
     */
    protected function getPath(): string
    {
        return storage_path(static::PATH);
    }

    /**
     * {@inheritDoc}
     */
    public function findByPath(string $path): ?RepositoryItem
    {
        return $this->getData()->first(static function(RepositoryItem $value, string $key) use($path): bool {
           return $key === md5($path);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function findAllByPath(string $path): Collection
    {
        return $this->getData()->where(static function(RepositoryItem  $value, string $key) use ($path): bool {
           return strpos($value->getFileName(), $path) !== false;
        });
    }

    /**
     * @param string $key
     * @param RepositoryItem $value
     * @return Repository
     */
    public function add(string $key, RepositoryItem $value): Repository
    {
        return parent::add(md5($key), $value);
    }

    /**
     * @param string $key
     * @return Repository
     */
    public function delete(string $key): Repository
    {
        return parent::delete(md5($key));
    }
}
