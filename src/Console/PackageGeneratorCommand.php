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
    protected $signature = 'package:generator {vendorName=ahmet-shen} {packageName=package-name} {packageDescription=package-name description.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a package from ahmet-shen/package-generator repository';

    /**
     * Package properties.
     */
    protected array $packageProperties = [
        'from' => [
            'vendorName' => 'ahmet-shen',
            'packageName' => 'package-generator',
            'packageDescription' => 'Simple package to quickly generate basic structure for other laravel packages.',
            'packagePath' => null,
            'packageClassFileName' => 'PackageGenerator',
        ],
        'to' => [
            'vendorName' => null,
            'packageName' => null,
            'packageDescription' => null,
            'packagePath' => null,
            'packageClassFileName' => null,
        ],
    ];

    /**
     * Default messages.
     */
    protected array $defaultMessages = [
        'setPackageProperties' => '01 -> The package properties definition process completed successfully.',
        'checkPackageFolder' => '02 -> The package directory check completed successfully.',
        'editorconfig' => '03 -> Copying the .editorconfig file into the package directory has been completed successfully.',
        'gitignore' => '04 -> Copying the .gitignore file into the package directory has been completed successfully.',
        'changelogMd' => '05 -> Copying the CHANGELOG.md file into the package directory has been completed successfully.',
        'licenseMd' => '06 -> Copying the LICENSE.md file into the package directory has been completed successfully.',
        'readmeMd' => '07 -> Copying the README.md file into the package directory has been completed successfully.',
        'todoMd' => '08 -> Copying the TODO.md file into the package directory has been completed successfully.',
        'tree' => '09 -> Copying the TREE.txt file into the package directory has been completed successfully.',
        'composerJson' => '10 -> Copying the composer.json file into the package directory has been completed successfully.',
        'github' => '11 -> Copying the .github directory into the package directory has been completed successfully.',
        'configFile' => '12 -> Copying the config/package-generator.php file into the package directory has been completed successfully.',
        'installCommandFile' => '13 -> Copying the src/Console/InstallCommand.php file into the package directory has been completed successfully.',
        'mainClassFile' => '14 -> Copying the src/PackageGenerator.php file into the package directory has been completed successfully.',
        'facadeClassFile' => '15 -> Copying the src/PackageGeneratorFacade.php file into the package directory has been completed successfully.',
        'providerClassFile' => '16 -> Copying the src/PackageGeneratorServiceProvider.php file into the new package directory has been completed successfully.',
        'helpersFile' => '17 -> Copying the src/helpers.php file into the new package directory has been completed successfully.',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // started..
        $this->writeln('------------------------------------------------------------------------------------------');

        // setPackageProperties..
        $this->setPackageProperties();

        // checkPackageFolder..
        $this->checkPackageFolder();

        // packageConfigurations..
        $this->packageConfigurations();

        // finished..
        $this->writeln('------------------------------------------------------------------------------------------');
    }

    /**
     * Write ln.
     */
    protected function writeln(string $line): void
    {
        echo $line.PHP_EOL;
    }

    /**
     * Set package properties.
     */
    protected function setPackageProperties(): void
    {
        $this->packageProperties['from']['packagePath'] = base_path('vendor/'.$this->packageProperties['from']['vendorName'].'/'.$this->packageProperties['from']['packageName']);

        $this->packageProperties['to']['vendorName'] = Str::of($this->argument('vendorName'))->lower()->slug();
        $this->packageProperties['to']['packageName'] = Str::of($this->argument('packageName'))->lower()->slug();
        $this->packageProperties['to']['packageDescription'] = Str::of($this->argument('packageDescription'))->lower()->replace('-', ' ')->title();
        $this->packageProperties['to']['packagePath'] = base_path('packages/'.$this->packageProperties['to']['vendorName'].'/'.$this->packageProperties['to']['packageName']);
        $this->packageProperties['to']['packageClassFileName'] = Str::of($this->packageProperties['to']['packageName'])->lower()->title()->replace('-', '');

        $this->writeln($this->defaultMessages['setPackageProperties']);
    }

    /**
     * Check package folder.
     */
    protected function checkPackageFolder(): void
    {
        $vendorFolderName = base_path('packages/'.$this->packageProperties['to']['vendorName']);

        $packageFolderName = base_path('packages/'.$this->packageProperties['to']['vendorName'].'/'.$this->packageProperties['to']['packageName']);

        // vendorName..
        if (! (new Filesystem)->exists($vendorFolderName)) {
            (new Filesystem)->makeDirectory($vendorFolderName);
        }

        // packageName..
        if (! (new Filesystem)->exists($packageFolderName)) {
            (new Filesystem)->makeDirectory($packageFolderName);
        }

        $this->writeln($this->defaultMessages['checkPackageFolder']);
    }

    /**
     * Package configurations.
     */
    protected function packageConfigurations(): void
    {
        // .editorconfig..
        $this->editorconfig();

        // .gitignore..
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

        // .github..
        $this->github();

        // configFile..
        $this->configFile();

        // src..
        $this->src();
    }

    /**
     * .Editorconfig.
     */
    protected function editorconfig(): void
    {
        $fileName = '.editorconfig';

        $this->checkPackageFiles($fileName, $fileName);

        $this->writeln($this->defaultMessages['editorconfig']);
    }

    /**
     * .Gitignore.
     */
    protected function gitignore(): void
    {
        $fileName = '.gitignore';

        $this->checkPackageFiles($fileName, $fileName);

        $this->writeln($this->defaultMessages['gitignore']);
    }

    /**
     * Changelog.md.
     */
    protected function changelogMd(): void
    {
        $fileName = 'CHANGELOG.md';

        $this->checkPackageFiles($fileName, $fileName);

        $this->writeln($this->defaultMessages['changelogMd']);
    }

    /**
     * License.md.
     */
    protected function licenseMd(): void
    {
        $fileName = 'LICENSE.md';

        $this->checkPackageFiles($fileName, $fileName);

        $this->writeln($this->defaultMessages['licenseMd']);
    }

    /**
     * Readme.md.
     */
    protected function readmeMd(): void
    {
        $fileName = 'README.md';

        $this->checkPackageFiles($fileName, $fileName);

        $this->writeln($this->defaultMessages['readmeMd']);
    }

    /**
     * Todo.md.
     */
    protected function todoMd(): void
    {
        $fileName = 'TODO.md';

        $this->checkPackageFiles($fileName, $fileName);

        $this->writeln($this->defaultMessages['todoMd']);
    }

    /**
     * Tree.txt.
     */
    protected function tree(): void
    {
        $fileName = 'TREE.txt';

        $this->checkPackageFiles($fileName, $fileName);

        $this->writeln($this->defaultMessages['tree']);
    }

    /**
     * Composer.json.
     */
    protected function composerJson(): void
    {
        $fileName = 'composer.json';

        $this->checkPackageFiles($fileName, $fileName);

        $this->writeln($this->defaultMessages['composerJson']);
    }

    /**
     * .Github.
     */
    protected function github(): void
    {
        $folderName = '.github';

        if ((new Filesystem)->exists($this->packageProperties['to']['packagePath'].'/'.$folderName)) {
            (new Filesystem)->deleteDirectory($this->packageProperties['to']['packagePath'].'/'.$folderName);
        }

        (new Filesystem)->copyDirectory($this->packageProperties['from']['packagePath'].'/'.$folderName, $this->packageProperties['to']['packagePath'].'/'.$folderName);

        $this->writeln($this->defaultMessages['github']);
    }

    /**
     * Config file.
     */
    protected function configFile(): void
    {
        $folderName = 'config';

        if ((new Filesystem)->exists($this->packageProperties['to']['packagePath'].'/'.$folderName)) {
            (new Filesystem)->deleteDirectory($this->packageProperties['to']['packagePath'].'/'.$folderName);
        }

        (new Filesystem)->makeDirectory($this->packageProperties['to']['packagePath'].'/'.$folderName);

        $this->checkPackageFiles($folderName.'/'.$this->packageProperties['from']['packageName'].'.php', $folderName.'/'.$this->packageProperties['to']['packageName'].'.php');

        $this->writeln($this->defaultMessages['configFile']);
    }

    /**
     * Src.
     */
    protected function src(): void
    {
        if ((new Filesystem)->exists($this->packageProperties['to']['packagePath'].'/src')) {
            (new Filesystem)->deleteDirectory($this->packageProperties['to']['packagePath'].'/src');
        }

        (new Filesystem)->makeDirectory($this->packageProperties['to']['packagePath'].'/src');

        $this->console();

        $this->mainClassFile();

        $this->facadeClassFile();

        $this->providerClassFile();

        $this->helpersFile();
    }

    /**
     * Console.
     */
    protected function console(): void
    {
        if ((new Filesystem)->exists($this->packageProperties['to']['packagePath'].'/src/Console')) {
            (new Filesystem)->deleteDirectory($this->packageProperties['to']['packagePath'].'/src/Console');
        }

        (new Filesystem)->makeDirectory($this->packageProperties['to']['packagePath'].'/src/Console');

        $this->installCommandFile();
    }

    /**
     * Main class file.
     */
    protected function mainClassFile(): void
    {
        $this->checkPackageFiles('src/'.$this->packageProperties['from']['packageClassFileName'].'.php', 'src/'.$this->packageProperties['to']['packageClassFileName'].'.php');

        $this->writeln($this->defaultMessages['mainClassFile']);
    }

    /**
     * Facade class file.
     */
    protected function facadeClassFile(): void
    {
        $this->checkPackageFiles('src/'.$this->packageProperties['from']['packageClassFileName'].'Facade.php', 'src/'.$this->packageProperties['to']['packageClassFileName'].'Facade.php');

        $this->writeln($this->defaultMessages['facadeClassFile']);
    }

    /**
     * Provider class file.
     */
    protected function providerClassFile(): void
    {
        $this->checkPackageFiles('src/'.$this->packageProperties['from']['packageClassFileName'].'ServiceProvider.php', 'src/'.$this->packageProperties['to']['packageClassFileName'].'ServiceProvider.php');

        $this->writeln($this->defaultMessages['providerClassFile']);
    }

    /**
     * Helpers file.
     */
    protected function helpersFile(): void
    {
        $fileName = 'src/helpers.php';

        $this->checkPackageFiles($fileName, $fileName);

        $this->writeln($this->defaultMessages['helpersFile']);
    }

    /**
     * Install command file.
     */
    protected function installCommandFile(): void
    {
        $fileName = 'src/Console/InstallCommand.php';

        $this->checkPackageFiles($fileName, $fileName);

        $this->writeln($this->defaultMessages['installCommandFile']);
    }

    /**
     * Check package files.
     */
    protected function checkPackageFiles(string $fromFileName, string $toFileName): void
    {
        if ((new Filesystem)->exists($this->packageProperties['to']['packagePath'].'/'.$toFileName)) {
            (new Filesystem)->delete($this->packageProperties['to']['packagePath'].'/'.$toFileName);
        }

        (new Filesystem)->copy($this->packageProperties['from']['packagePath'].'/'.$fromFileName, $this->packageProperties['to']['packagePath'].'/'.$toFileName);
    }

    /**
     * Replace in file.
     */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        (new Filesystem)->replaceInFile($search, $replace, $path);
    }
}
