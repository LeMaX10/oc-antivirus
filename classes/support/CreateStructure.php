<?php


namespace LeMaX10\Antivirus\Classes\Support;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use LeMaX10\Antivirus\Classes\Entities\Item;
use LeMaX10\Antivirus\Classes\Entities\ItemChange;

/**
 * Class CreateStructure
 * @package LeMaX10\Antivirus\Classes\Support
 */
class CreateStructure
{
    /**
     * @param \RecursiveIteratorIterator $iterator
     * @return Collection
     * @throws \Exception
     */
    public function __invoke(\RecursiveIteratorIterator $iterator): Collection
    {
        $structure = new Collection;
        $item      = new Item;

        foreach($iterator as $info) {
            $item = (clone $item)
                ->setFileName($info->getPathName())
                ->setMTime(new Carbon($info->getMTime()))
                ->setPerms($info->getPerms())
                ->setSize($info->getSize())
                ->setType($info->getType());

            $structure->put(md5($info->getPathName()), $item);
        }

        return $structure;
    }
}
