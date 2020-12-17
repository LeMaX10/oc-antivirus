<?php declare(strict_types=1);

namespace LeMaX10\Antivirus\Classes;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use LeMaX10\Antivirus\Classes\Contracts\Configuration;
use LeMaX10\Antivirus\Classes\Contracts\Repository as RepositoryContract;
use LeMaX10\Antivirus\Classes\Contracts\Scanner as ScannerContract;
use LeMaX10\Antivirus\Classes\Entities\Item;
use LeMaX10\Antivirus\Classes\Support\CreateStructure;
use LeMaX10\Antivirus\Classes\Support\DiffStructure;

/**
 * Class Scanner
 * @package LeMaX10\Antivirus\Classes
 */
class Scanner implements ScannerContract
{
    /**
     * @var RepositoryContract
     */
    protected $repository;

    /**
     * @var \RecursiveDirectoryIterator
     */
    protected $directory;

    /**
     * @var Collection
     */
    protected $structure;

    /**
     * @var Collection
     */
    protected $excludelist;

    /**
     * @var Collection
     */
    protected $whitelist;

    /**
     * @var array
     */
    protected $extensions = [];

    /**
     * Scanner constructor.
     */
    public function __construct(Configuration $config)
    {
        $this->setConfig($config);
    }

    /**
     * @param string $mode
     */
    public function scan(string $mode = 'verification'): Collection
    {
        $iterator = new \RecursiveIteratorIterator($this->directoryFilter());

        if ($mode === 'init') {
            $collection = $this->loadingStructure($iterator);
            $this->repository->save();
        } elseif ($mode === 'verification') {
            $collection = $this->verificationStructure($iterator);
        }

        return $collection;
    }

    /**
     * @param \RecursiveIteratorIterator $iterator
     * @return bool
     * @throws \Exception
     */
    public function loadingStructure(\RecursiveIteratorIterator $iterator): Collection
    {
        // asdadsa
        $structure = (new CreateStructure)($iterator);
        $this->repository->setData($structure);

        return $this->repository->getData();
    }

    /**
     * @param \RecursiveIteratorIterator $iterator
     * @return bool
     */
    public function verificationStructure(\RecursiveIteratorIterator $iterator): Collection
    {
        $structure = (new CreateStructure)($iterator);
        $diff = new DiffStructure($this->repository->getData(), $structure);
        $diff->run();

        return $diff->getChanges();
    }

    /**
     * @param string $directory
     * @return ScannerContract
     */
    public function setDirectory(string $directory = null): ScannerContract
    {
        $this->directory = new \RecursiveDirectoryIterator($directory);

        return $this;
    }

    /**
     * @return \RecursiveDirectoryIterator
     */
    public function getDirectory(): \RecursiveDirectoryIterator
    {
        return $this->directory;
    }

    /**
     * @param RepositoryContract $loader
     * @return ScannerContract
     */
    public function setRepository(RepositoryContract $repository): ScannerContract
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return RepositoryContract
     */
    public function getRepository(): RepositoryContract
    {
        return $this->repository;
    }

    /**
     * @param array $list
     * @return $this|ScannerContract
     */
    public function setExcludeList(array $list): ScannerContract
    {
        $this->excludelist = (new Collection($list))->transform(static function(string $path): string {
            return base_path($path);
        });

        return $this;
    }

    /**
     * @param array $list
     * @return $this|ScannerContract
     */
    public function setWhiteList(array $list): ScannerContract
    {
        $this->whitelist = (new Collection($list))->transform(static function(string $path): string {
            return base_path($path);
        });

        return $this;
    }

    public function setExtensions(array $extensions): ScannerContract
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * @param Configuration $configuration
     * @return $this|ScannerContract
     */
    public function setConfig(Configuration $configuration): ScannerContract
    {
        $this->setDirectory($configuration->getDirectory())
             ->setExcludeList($configuration->getExcludeList())
             ->setWhiteList($configuration->getWhitelist())
             ->setRepository($configuration->getRepository())
             ->setExtensions($configuration->getExtensions());

        return $this;
    }


    /**
     * test
     * @return \RecursiveCallbackFilterIterator
     */
    protected function directoryFilter(): \RecursiveCallbackFilterIterator
    {
        return new \RecursiveCallbackFilterIterator($this->getDirectory(), function ($current, $key, $iterator) {
            $disableExtension = !empty($current->getExtension()) &&
                !in_array($current->getExtension(), $this->extensions, true);

            $isExclude = $this->excludelist->filter(static function(string $path) use($current): bool {
                return '..' === $current->getBaseName() || $path === $current->getPathName();
            })->isEmpty();

            $isWhite = $this->whitelist->filter(static function(string $path) use($current): bool {
                return strpos($current->getPathName(), $path) !== false;
            })->isNotEmpty();

            return $isExclude && $isWhite && !$disableExtension;
        });
    }
}
