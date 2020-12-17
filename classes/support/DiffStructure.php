<?php declare(strict_types=1);

namespace LeMaX10\Antivirus\Classes\Support;


use Illuminate\Support\Collection;
use LeMaX10\Antivirus\Classes\Contracts\RepositoryItem;
use LeMaX10\Antivirus\Classes\Entities\ItemChange;

/**
 * Class DiffStructure
 * @package LeMaX10\Antivirus\Classes\Support
 */
class DiffStructure
{
    /**
     * @var Collection
     */
    protected $snapshot;

    /**
     * @var Collection
     */
    protected $structure;

    /**
     * @var Collection
     */
    protected $changes;

    /**
     * DiffStructure constructor.
     * @param Collection $snapshot
     * @param Collection $structure
     */
    public function __construct(Collection $snapshot, Collection $structure)
    {
        $this->snapshot  = $snapshot;
        $this->structure = $structure;
    }


    /**
     *
     */
    public function run()
    {
        $this->changes    = new Collection(
            $this->findNewFilesInStructure()->merge($this->findDeletedFilesInStructure())
        );

        $this->changes = $this->changes->merge($this->findChange());
    }

    /**
     * @return Collection
     */
    public function getChanges(): Collection
    {
        return $this->changes;
    }

    /**
     * @return Collection
     */
    protected function findChange(): Collection
    {
        return $this->snapshot->map(function(RepositoryItem $item, string $key): ?RepositoryItem {
            if ($this->changes->has($key) || $item->getType() === 'dir') {
                return null;
            }

            /** @var RepositoryItem $itemOriginal */
            $itemOriginal = $this->structure->get($key);
            $diff = (new ItemChange)
                ->setMTime(
                    (string) $item->getMTime()->getTimestamp(),
                    (string) $itemOriginal->getMTime()->getTimestamp()
                )
                ->setSize(
                    (string) $item->getSize(),
                    (string) $itemOriginal->getSize()
                )
                ->setChmod(
                    $item->getPerms(),
                    $itemOriginal->getPerms()
                );
            if (!$diff->isModify()) {
                unset($diff);
                return null;
            }

            return $item->setChanges($diff);
        })->filter();
    }

    /**
     * @return Collection
     */
    protected function findNewFilesInStructure(): Collection
    {
        return $this->structure
            ->diffKeys($this->snapshot)
            ->transform(static function(RepositoryItem $item): RepositoryItem {
                return $item->setChanges((new ItemChange)->setIsNew(true));
            });
    }

    /**
     * @return Collection
     */
    protected function findDeletedFilesInStructure(): Collection
    {
        return $this->snapshot
            ->diffKeys($this->structure)
            ->transform(static function(RepositoryItem $item): RepositoryItem {
                return $item->setChanges((new ItemChange)->setIsDelete(true));
            });
    }
}
