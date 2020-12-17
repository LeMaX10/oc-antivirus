<?php declare(strict_types=1);

namespace LeMaX10\Antivirus;

use LeMaX10\Antivirus\Classes\Commands\ScanCommand;
use LeMaX10\Antivirus\Classes\Configuration;
use LeMaX10\Antivirus\Classes\Loader;
use LeMaX10\Antivirus\Classes\Scanner;
use System\Classes\PluginBase;

/**
 * Class Plugin
 * @package LeMaX10\Antivirus
 */
class Plugin extends PluginBase
{

    /**
     * @return array|string[]
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Antivirus',
            'description' => 'Simple antivirus plugin for OctoberCMS',
            'author'      => 'Vladimir Pyankov',
            'icon'        => 'icon-leaf'
        ];
    }

    public function register(): void
    {
        parent::register();

        if ($this->app->runningInConsole()) {
            $this->registerConsoleCommand('lemax10.antivirus.scan', ScanCommand::class);
        }
    }

    public function boot(): void
    {
        parent::boot();

        $this->app->singleton(\LeMaX10\Antivirus\Classes\Contracts\Scanner::class, static function() {
            $config = new Configuration(config('lemax10.antivirus::scanner'));
            return new Scanner($config);
        });
    }

    /**
     * @return array
     */
    public function registerComponents(): array
    {
        return [

        ];
    }
}
