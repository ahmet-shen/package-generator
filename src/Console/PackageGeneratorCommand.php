<?php

namespace AhmetShen\PackageGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class PackageGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:generator {newPackageName=new-package}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new package from ahmet-shen/package-generator';

    /**
     * Current & new package name.
     *
     * @var array
     */
    protected array $packageNames = [
        'current' => 'package-generator',
        'new' => null,
    ];

    /**
     * Current & new package path.
     *
     * @var array
     */
    protected array $packagePaths = [
        'current' => null,
        'new' => null,
    ];

    /**
     * Messages.
     *
     * @var array
     */
    protected array $messages = [
        'setNewPackageName' => 'The new package name definition process completed successfully.',
        'setPackagePaths' => 'The package path definition process completed successfully.',
        'checkNewPackageFolder' => 'The new package directory check completed successfully.',
        '' => '',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // started..
        $this->writeln('------');

        // setNewPackageName..
        $this->setNewPackageName();

        // setPackagePaths..
        $this->setPackagePaths();

        // checkNewPackageFolder..
        $this->checkNewPackageFolder();

        // ..

        // finished..
        $this->writeln('------');
    }

    /**
     * Write ln.
     */
    protected function writeln(string $line): void
    {
        echo $line.PHP_EOL;
    }

    /**
     * Set new package name.
     */
    protected function setNewPackageName(): void
    {
        $this->packageNames['new'] = $this->argument('newPackageName');

        $this->writeln($this->messages['setNewPackageName']);
    }

    /**
     * Set package paths.
     */
    protected function setPackagePaths(): void
    {
        $this->packagePaths['current'] = base_path('vendor/ahmet-shen/'.$this->packageNames['current']);

        $this->packagePaths['new'] = base_path('packages/ahmet-shen/'.Str::of($this->packageNames['new'])->lower()->slug());

        $this->writeln($this->messages['setPackagePaths']);
    }

    /**
     * Check new package folder.
     */
    protected function checkNewPackageFolder(): void
    {
        if (! (new Filesystem())->exists($this->packagePaths['new'])) {
            (new Filesystem())->makeDirectory($this->packagePaths['new']);
        }

        $this->writeln($this->messages['checkNewPackageFolder']);
    }
}
