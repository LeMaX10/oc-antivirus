<?php declare(strict_types=1);


namespace LeMaX10\Antivirus\Classes;

use Illuminate\Support\Arr;
use LeMaX10\Antivirus\Classes\Contracts\Configuration as ConfigurationContract;
use LeMaX10\Antivirus\Classes\Contracts\Repository as RepositoryContract;

/**
 * Class Configuration
 * @package LeMaX10\Antivirus\Classes
 */
class Configuration implements ConfigurationContract
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Configuration constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->config['directory'];
    }

    /**
     * @return array
     */
    public function getWhitelist(): array
    {
        return $this->config['whiteList'];
    }

    /**
     * @return array
     */
    public function getExcludeList(): array
    {
        return $this->config['excludeList'];
    }

    /**
     * @return RepositoryContract
     */
    public function getRepository(): RepositoryContract
    {
        return new $this->config['repository'];
    }

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return (array) Arr::get($this->config, 'extensions');
    }
}
