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
        'github' => 'Copying the github directory into the new package directory has been completed successfully.',
        'configFile' => 'Copying the config file into the new package directory has been completed successfully.',
        'installCommandFile' => 'Copying the src/Console/InstallCommand.php file into the new package directory has been completed successfully.',
        'mainClassFile' => 'Copying the src/NewPackage.php file into the new package directory has been completed successfully.',
        'facadeClassFile' => 'Copying the src/NewPackageFacade.php file into the new package directory has been completed successfully.',
        'providerClassFile' => 'Copying the src/NewPackageServiceProvider.php file into the new package directory has been completed successfully.',
        'helperFile' => 'Copying the src/helper.php file into the new package directory has been completed successfully.',
        'editorconfig' => 'Copying the .editorconfig file into the new package directory has been completed successfully.',
        'gitignore' => 'Copying the .gitignore file into the new package directory has been completed successfully.',
        'changelogMd' => 'Copying the CHANGELOG.md file into the new package directory has been completed successfully.',
        'licenseMd' => 'Copying the LICENSE.md file into the new package directory has been completed successfully.',
        'readmeMd' => 'Copying the README.md file into the new package directory has been completed successfully.',
        'todoMd' => 'Copying the TODO.md file into the new package directory has been completed successfully.',
        'tree' => 'Copying the TREE.txt file into the new package directory has been completed successfully.',
        'composerJson' => 'Copying the composer.json file into the new package directory has been completed successfully.',
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
        // .github..
        $this->github();

        // configFile..
        $this->configFile();

        // src..
        $this->src();

        // .editorconfig..
        $this->editorconfig();

        // gitignore..
        $this->gitignore();

        // CHANGELOG.md..
        $this->changelogMd();

        // LICENSE.md..
        $this->licenseMd();

        // README.md..
        $this->readmeMd();

        // TODO.md..
        $this->todoMd();

        // TREE.txt..
        $this->tree();

        // composer.json..
        $this->composerJson();
    }

    /**
     * .github.
     */
    protected function github(): void
    {
        if ((new Filesystem)->exists($this->packagePaths['new'].'/.github')) {
            (new Filesystem)->deleteDirectory($this->packagePaths['new'].'/.github');
        }

        (new Filesystem)->copyDirectory($this->packagePaths['current'].'/.github', $this->packagePaths['new'].'/.github');

        $this->writeln($this->messages['github']);
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
     * Src.
     */
    protected function src(): void
    {
        if ((new Filesystem)->exists($this->packagePaths['new'].'/src')) {
            (new Filesystem)->deleteDirectory($this->packagePaths['new'].'/src');
        }

        (new Filesystem)->makeDirectory($this->packagePaths['new'].'/src');

        $this->console();

        $this->mainClassFile();

        $this->facadeClassFile();

        $this->providerClassFile();

        $this->helperFile();
    }

    /**
     * Console.
     */
    protected function console(): void
    {
        if ((new Filesystem)->exists($this->packagePaths['new'].'/src/Console')) {
            (new Filesystem)->deleteDirectory($this->packagePaths['new'].'/src/Console');
        }

        (new Filesystem)->makeDirectory($this->packagePaths['new'].'/src/Console');

        $this->installCommandFile();
    }

    /**
     * Install command file.
     */
    protected function installCommandFile(): void
    {
        $this->checkNewPackageFiles(
            'src/Console/InstallCommand.php',
            'src/Console/InstallCommand.php'
        );

        $this->replaceInFile(
            Str::of($this->packageNames['current'])->lower()->title()->replace('-', '').'\\',
            Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'\\',
            $this->packagePaths['new'].'/'.'src/Console/InstallCommand.php'
        );

        $this->replaceInFile(
            Str::of($this->packageNames['current'])->lower()->title()->camel().':install',
            Str::of($this->packageNames['new'])->lower()->title()->camel().':install',
            $this->packagePaths['new'].'/'.'src/Console/InstallCommand.php'
        );

        $this->replaceInFile(
            'Install the '.Str::of($this->packageNames['current'])->lower()->title()->camel().' resources',
            'Install the '.Str::of($this->packageNames['new'])->lower()->title()->camel().' resources',
            $this->packagePaths['new'].'/'.'src/Console/InstallCommand.php'
        );

        $this->replaceInFile(
            Str::of($this->packageNames['current'])->lower()->title()->camel().' scaffolding installed successfully.',
            Str::of($this->packageNames['new'])->lower()->title()->camel().' scaffolding installed successfully.',
            $this->packagePaths['new'].'/'.'src/Console/InstallCommand.php'
        );

        $this->replaceInFile(
            $this->packageNames['current'].'.php',
            Str::of($this->packageNames['new'])->lower()->slug().'.php',
            $this->packagePaths['new'].'/'.'src/Console/InstallCommand.php'
        );

        $this->writeln($this->messages['installCommandFile']);
    }

    /**
     * Main class file.
     */
    protected function mainClassFile(): void
    {
        $this->checkNewPackageFiles(
            'src/'.Str::of($this->packageNames['current'])->lower()->title()->replace('-', '').'.php',
            'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'.php'
        );

        $this->replaceInFile(
            Str::of($this->packageNames['current'])->lower()->title()->replace('-', '').';',
            Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').';',
            $this->packagePaths['new'].'/'.'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'.php'
        );

        $this->replaceInFile(
            'class '.Str::of($this->packageNames['current'])->lower()->title()->replace('-', ''),
            'class '.Str::of($this->packageNames['new'])->lower()->title()->replace('-', ''),
            $this->packagePaths['new'].'/'.'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'.php'
        );

        $this->replaceInFile(
            $this->packageNames['current'],
            Str::of($this->packageNames['new'])->lower()->slug(),
            $this->packagePaths['new'].'/'.'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'.php'
        );

        $this->writeln($this->messages['mainClassFile']);
    }

    /**
     * Facade class file.
     */
    protected function facadeClassFile(): void
    {
        $this->checkNewPackageFiles(
            'src/'.Str::of($this->packageNames['current'])->lower()->title()->replace('-', '').'Facade.php',
            'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'Facade.php'
        );

        $this->replaceInFile(
            Str::of($this->packageNames['current'])->lower()->title()->replace('-', '').';',
            Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').';',
            $this->packagePaths['new'].'/'.'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'Facade.php'
        );

        $this->replaceInFile(
            'class '.Str::of($this->packageNames['current'])->lower()->title()->replace('-', '').'Facade',
            'class '.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'Facade',
            $this->packagePaths['new'].'/'.'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'Facade.php'
        );

        $this->replaceInFile(
            $this->packageNames['current'],
            Str::of($this->packageNames['new'])->lower()->slug(),
            $this->packagePaths['new'].'/'.'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'Facade.php'
        );

        $this->writeln($this->messages['facadeClassFile']);
    }

    /**
     * Provider class file.
     */
    protected function providerClassFile(): void
    {
        $this->checkNewPackageFiles(
            'src/'.Str::of($this->packageNames['current'])->lower()->title()->replace('-', '').'ServiceProvider.php',
            'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'ServiceProvider.php'
        );

        $this->replaceInFile(
            Str::of($this->packageNames['current'])->lower()->title()->replace('-', '').';',
            Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').';',
            $this->packagePaths['new'].'/'.'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'ServiceProvider.php'
        );

        $this->replaceInFile(
            'class '.Str::of($this->packageNames['current'])->lower()->title()->replace('-', '').'ServiceProvider',
            'class '.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'ServiceProvider',
            $this->packagePaths['new'].'/'.'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'ServiceProvider.php'
        );

        $this->replaceInFile(
            $this->packageNames['current'],
            Str::of($this->packageNames['new'])->lower()->slug(),
            $this->packagePaths['new'].'/'.'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'ServiceProvider.php'
        );

        $this->replaceInFile(
            Str::of($this->packageNames['current'])->lower()->title()->replace('-', '').'::class',
            Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'::class',
            $this->packagePaths['new'].'/'.'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'ServiceProvider.php'
        );

        $this->replaceInFile(
            '$this->commands([
        Console\InstallCommand::class,
        Console\PackageGeneratorCommand::class,
    ]);',
            '$this->commands([
        Console\InstallCommand::class,
    ]);',
            $this->packagePaths['new'].'/'.'src/'.Str::of($this->packageNames['new'])->lower()->title()->replace('-', '').'ServiceProvider.php'
        );

        $this->writeln($this->messages['providerClassFile']);
    }

    /**
     * Helper file.
     */
    protected function helperFile(): void
    {
        $this->checkNewPackageFiles(
            'src/helpers.php',
            'src/helpers.php'
        );

        $this->replaceInFile(
            $this->packageNames['current'],
            Str::of($this->packageNames['new'])->lower()->slug(),
            $this->packagePaths['new'].'/'.'src/helpers.php'
        );

        $this->replaceInFile(
            '$'.Str::of($this->packageNames['current'])->lower()->title()->camel(),
            '$'.Str::of($this->packageNames['new'])->lower()->title()->camel(),
            $this->packagePaths['new'].'/'.'src/helpers.php'
        );

        $this->writeln($this->messages['helperFile']);
    }

    /**
     * .editorconfig.
     */
    protected function editorconfig(): void
    {
        $fileName = '.editorconfig';

        $this->checkNewPackageFiles($fileName, $fileName);

        $this->writeln($this->messages['editorconfig']);
    }

    /**
     * gitignore.
     */
    protected function gitignore(): void
    {
        $fileName = '.gitignore';

        $this->checkNewPackageFiles($fileName, $fileName);

        $this->writeln($this->messages['gitignore']);
    }

    /**
     * CHANGELOG.md.
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
     * LICENSE.md.
     */
    protected function licenseMd(): void
    {
        $fileName = 'LICENSE.md';

        $this->checkNewPackageFiles($fileName, $fileName);

        $this->writeln($this->messages['licenseMd']);
    }

    /**
     * README.md.
     */
    protected function readmeMd(): void
    {
        $fileName = 'README.md';

        $this->checkNewPackageFiles($fileName, $fileName);

        $this->replaceInFile(
            $this->packageNames['current'],
            Str::of($this->packageNames['new'])->lower()->slug(),
            $this->packagePaths['new'].'/'.$fileName
        );

        $this->replaceInFile(
            'Simple+package+to+quickly+generate+basic+structure+for+other+laravel+packages.',
            Str::of($this->packageNames['new'])->lower()->slug().' description.',
            $this->packagePaths['new'].'/'.$fileName
        );

        $this->replaceInFile(
            'Simple package to quickly generate basic structure for other laravel packages.',
            Str::of($this->packageNames['new'])->lower()->slug().' description.',
            $this->packagePaths['new'].'/'.$fileName
        );

        $this->replaceInFile(
            Str::of($this->packageNames['current'])->lower()->title()->camel().':install',
            Str::of($this->packageNames['new'])->lower()->title()->camel().':install',
            $this->packagePaths['new'].'/'.$fileName
        );

        $this->replaceInFile(
            Str::of($this->packageNames['current'])->lower()->title()->replace('-', ' ').' Page',
            Str::of($this->packageNames['new'])->lower()->title()->replace('-', ' ').' Page',
            $this->packagePaths['new'].'/'.$fileName
        );

        $this->replaceInFile(
            'This package was generated using the [new-package](https://github.com/ahmet-shen/new-package).',
            'This package was generated using the ['.$this->packageNames['current'].'](https://github.com/ahmet-shen/'.$this->packageNames['current'].').',
            $this->packagePaths['new'].'/'.$fileName
        );

        $this->writeln($this->messages['readmeMd']);
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
     * TREE.txt.
     */
    protected function tree(): void
    {
        $fileName = 'TREE.txt';

        $this->checkNewPackageFiles($fileName, $fileName);

        $this->writeln($this->messages['tree']);
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
            Str::of($this->packageNames['current'])->lower()->title()->replace('-', ''),
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
