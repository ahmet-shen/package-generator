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
     */
    protected array $packageNames = [
        'current' => 'package-generator',
        'new' => null,
    ];

    /**
     * Current & new package path.
     */
    protected array $packagePaths = [
        'current' => null,
        'new' => null,
    ];

    /**
     * Messages.
     */
    protected array $messages = [
        'setNewPackageName' => 'The new package name definition process completed successfully.',
        'setPackagePaths' => 'The package path definition process completed successfully.',
        'checkNewPackageFolder' => 'The new package directory check completed successfully.',
        'changelogMd' => 'Copying the CHANGELOG.md file into the new package directory has been completed successfully.',
        'configFile' => 'Copying the config file into the new package directory has been completed successfully.',
        'todoMd' => 'Copying the TODO.md file into the new package directory has been completed successfully.',
        'composerJson' => 'Copying the composer.json file into the new package directory has been completed successfully.',
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

        // newPackageConfigurations..
        $this->newPackageConfigurations();

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
        if (! (new Filesystem)->exists($this->packagePaths['new'])) {
            (new Filesystem)->makeDirectory($this->packagePaths['new']);
        }

        $this->writeln($this->messages['checkNewPackageFolder']);
    }

    /**
     * New package configurations.
     */
    protected function newPackageConfigurations(): void
    {
        // CHANGELOG.md..
        $this->changelogMd();

        // config/package-generator.php..
        $this->configFile();

        // TODO.md..
        $this->todoMd();

        // composer.json..
        $this->composerJson();
    }

    /**
     * CHANGELOG.md
     */
    protected function changelogMd(): void
    {
        $fileName = 'CHANGELOG.md';

        $this->checkNewPackageFiles($fileName, $fileName);

        $this->replaceInFile('`'.$this->packageNames['current'].'`', '`'.Str::of($this->packageNames['new'])->lower()->slug().'`', $this->packagePaths['new'].'/'.$fileName);

        $this->replaceInFile(
            '[Unreleased](https://github.com/ahmet-shen/'.$this->packageNames['current'].')',
            '[Unreleased](https://github.com/ahmet-shen/'.Str::of($this->packageNames['new'])->lower()->slug().')',
            $this->packagePaths['new'].'/'.$fileName
        );

        $this->replaceInFile(
            '[v0.0.0](https://github.com/ahmet-shen/'.$this->packageNames['current'].') - 202X-',
            '[v0.0.0](https://github.com/ahmet-shen/'.Str::of($this->packageNames['new'])->lower()->slug().') - '.date('Y').'-',
            $this->packagePaths['new'].'/'.$fileName
        );

        $this->writeln($this->messages['changelogMd']);
    }

    /**
     * Config file.
     */
    protected function configFile(): void
    {
        if ((new Filesystem)->exists($this->packagePaths['new'].'/config')) {
            (new Filesystem)->deleteDirectory($this->packagePaths['new'].'/config');
        }

        (new Filesystem)->makeDirectory($this->packagePaths['new'].'/config');

        $this->checkNewPackageFiles('config/'.$this->packageNames['current'].'.php', 'config/'.Str::of($this->packageNames['new'])->lower()->slug().'.php');

        $this->writeln($this->messages['configFile']);
    }

    /**
     * Todo.md
     */
    protected function todoMd(): void
    {
        $fileName = 'TODO.md';

        $this->checkNewPackageFiles($fileName, $fileName);

        $this->replaceInFile('`'.$this->packageNames['current'].'`', '`'.Str::of($this->packageNames['new'])->lower()->slug().'`', $this->packagePaths['new'].'/'.$fileName);

        $this->writeln($this->messages['todoMd']);
    }

    /**
     * Composer.json
     */
    protected function composerJson(): void
    {
        $fileName = 'composer.json';

        $this->checkNewPackageFiles($fileName, $fileName);

        $this->replaceInFile(
            '"name": "ahmet-shen/'.$this->packageNames['current'].'",',
            '"name": "ahmet-shen/'.Str::of($this->packageNames['new'])->lower()->slug().'",',
            $this->packagePaths['new'].'/'.$fileName
        );

        $this->replaceInFile(
            '"description": "Simple package to quickly generate basic structure for other laravel packages.",',
            '"description": "'.Str::of($this->packageNames['new'])->lower()->slug().' description.",',
            $this->packagePaths['new'].'/'.$fileName);

        $this->replaceInFile(
            '"keywords": ["ahmet-shen", "'.$this->packageNames['current'].'"],',
            '"keywords": ["ahmet-shen", "'.Str::of($this->packageNames['new'])->lower()->slug().'"],',
            $this->packagePaths['new'].'/'.$fileName);

        $this->replaceInFile(
            '"homepage": "https://github.com/ahmet-shen/'.$this->packageNames['current'].'",',
            '"homepage": "https://github.com/ahmet-shen/'.Str::of($this->packageNames['new'])->lower()->slug().'",',
            $this->packagePaths['new'].'/'.$fileName);

        $this->replaceInFile(
            '"issues": "https://github.com/ahmet-shen/'.$this->packageNames['current'].'/issues",',
            '"issues": "https://github.com/ahmet-shen/'.Str::of($this->packageNames['new'])->lower()->slug().'/issues",',
            $this->packagePaths['new'].'/'.$fileName);

        $this->replaceInFile(
            '"source": "https://github.com/ahmet-shen/'.$this->packageNames['current'].'"',
            '"source": "https://github.com/ahmet-shen/'.Str::of($this->packageNames['new'])->lower()->slug().'"',
            $this->packagePaths['new'].'/'.$fileName);

        $this->replaceInFile(
            'PackageGenerator',
            Str::of($this->packageNames['new'])->lower()->title()->replace('-', ''),
            $this->packagePaths['new'].'/'.$fileName);

        $this->writeln($this->messages['composerJson']);
    }

    /**
     * Check new package files.
     */
    protected function checkNewPackageFiles(string $currentFileName, string $newFileName): void
    {
        if ((new Filesystem)->exists($this->packagePaths['new'].'/'.$newFileName)) {
            (new Filesystem)->delete($this->packagePaths['new'].'/'.$newFileName);
        }

        (new Filesystem)->copy($this->packagePaths['current'].'/'.$currentFileName, $this->packagePaths['new'].'/'.$newFileName);
    }

    /**
     * Replace in file.
     */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        (new Filesystem)->replaceInFile($search, $replace, $path);
    }
}
