<?php declare(strict_types=1);

namespace LeMaX10\Antivirus\Classes\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use LeMaX10\Antivirus\Classes\Contracts\RepositoryItem;
use LeMaX10\Antivirus\Classes\Contracts\Scanner;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ScanCommand
 * @package LeMaX10\Antivirus\Classes\Commands
 */
class ScanCommand extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'antivirus:scan';

    /**
     * @var string The console command description.
     */
    protected $description = 'Scan project';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle(Scanner $scanner)
    {
        $this->line('Scan...');

        $items = $scanner->scan($this->option('init') ? 'init' : 'verification');

        if (!$this->option('no-output')) {
            $filesFound     = $items->filter($this->counterFilter('file'))->count();
            $directoryFound = $items->filter($this->counterFilter('dir'))->count();

            $this->line('Scanning complete.');

            if ($this->option('init')) {
                $this->line('Found files: ' . $filesFound . ' and directories: ' . $directoryFound);
                $this->line('Snapshot is created');
            } else {
                $this->line('Found changed files: ' . $filesFound . ' and directories: ' . $directoryFound);
                if ($items->isNotEmpty()) {
                    $this->makeTable($items);
                } else {
                    $this->info('Not found changes');
                }
            }
        }
    }

    /**
     * @param Collection $items
     */
    protected function makeTable(Collection $items): void
    {
        $this->table([
            'Hash',
            'Path',
            'isNew',
            'isDelete',
            'isModifyTime',
            'isModifySize',
            'isModifyChmod'
        ], $items->transform(function(RepositoryItem $item, string $key): array {
            $item->setFileName(str_replace(base_path(), '', $item->getFileName()));
            return [
                $key,
                $item->getFileName(),
                $this->makeCellValue($item->getChanges()->isNew()),
                $this->makeCellValue($item->getChanges()->isDelete()),
                $this->makeCellValue($item->getChanges()->isChangeMTime()),
                $this->makeCellValue($item->getChanges()->isChangeSize()),
                $this->makeCellValue($item->getChanges()->isChangeChmod())
            ];
        })->all());
    }

    /**
     * @param bool $value
     * @return string
     */
    protected function makeCellValue(bool $value): string
    {
        return $value ? 'Y' : 'N';
    }

    /**
     * @param string $string
     * @param null $style
     * @param null $verbosity
     */
    public function line($string, $style = null, $verbosity = null)
    {
        if (!$this->option('no-output')) {
            parent::line($string, $style, $verbosity);
        }
    }

    /**
     * @param string $fileType
     * @return \Closure
     */
    private function counterFilter(string $fileType): \Closure {
        return static function(RepositoryItem $item) use($fileType): bool {
            return $item->getType() === $fileType;
        };
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['init', null, InputOption::VALUE_OPTIONAL || InputOption::VALUE_NONE, 'Scan full project and create snapshot structure'],
            ['no-output', '-f', InputOption::VALUE_OPTIONAL || InputOption::VALUE_NONE, 'No console output'],
            ['notify', '-s', InputOption::VALUE_OPTIONAL || InputOption::VALUE_NONE, 'Notify the administrator about invalid structure']
        ];
    }

}
