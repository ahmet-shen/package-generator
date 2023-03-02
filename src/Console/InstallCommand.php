<?php

namespace AhmetShen\PackageGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packageGenerator:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the packageGenerator resources';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->configFiles();

        $this->info('packageGenerator scaffolding installed successfully.');

        $this->comment('The installation process was carried out successfully. Please visit your web page.');
    }

    /**
     * Copy config files.
     */
    protected function configFiles(): void
    {
        (new Filesystem())->copy(__DIR__.'/../../config/package-generator.php', config_path('package-generator.php'));
    }
}
