<?php declare(strict_types=1);
namespace LeMaX10\Antivirus\Classes\Repository;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use LeMaX10\Antivirus\Classes\Contracts\Repository;
use LeMaX10\Antivirus\Classes\Contracts\RepositoryItem;
use LeMaX10\Antivirus\Classes\Entities\Item;
use LeMaX10\Antivirus\Classes\SnapshotRepository;

/**
 * Class LocalRepository
 * @package LeMaX10\Antivirus\Classes\Repository
 */
abstract class LocalRepository
{
    /**
     * @var Collection
     */
    protected $items;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var bool
     */
    protected $encrypt = false;

    /**
     * @var string
     */
    protected $entity;

    /**
     * SnapshotRepository constructor.
     */
    public function __construct()
    {
        $this->fileSystem = app('files');
    }

    /**
     * @return string
     */
    abstract protected function getPath(): string;

    /**
     * @return void
     */
    protected function load(): void
    {
        if (!$this->items && $this->fileSystem->exists($this->getPath())) {
            $fileData = $this->fileSystem->get($this->getPath());
            if (!empty($fileData) && $this->encrypt) {
                $fileData = decrypt($fileData);
            }

            $this->items = (new Collection((array) json_decode((string) $fileData, true)))
                ->mapInto($this->entity);
        }
    }

    /**
     * @param Collection $items
     * @return LocalRepository
     */
    public function setData(Collection $items): LocalRepository
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getData(): Collection
    {
        $this->load();
        return $this->items;
    }

    /**
     * @param string $key
     * @param RepositoryItem $value
     * @return $this|Repository
     */
    public function add(string $key, RepositoryItem $value): Repository
    {
        $this->load();
        $this->items->put($key, $value);

        return $this;
    }

    /**
     * @param string $key
     * @return $this|Repository
     */
    public function delete(string $key): Repository
    {
        $this->load();
        $this->items->forget($key);

        return $this;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->items) {
            return false;
        }

        $this->items->sort();
        $this->fileSystem->delete($this->getPath());

        $data = $this->getData()->toJson();
        if ($this->encrypt) {
            $data = encrypt($data);
        }

        $this->fileSystem->put($this->getPath(), $data, LOCK_EX);
        return true;
    }
}
