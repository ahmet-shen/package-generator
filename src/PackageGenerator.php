<?php

namespace AhmetShen\PackageGenerator;

class PackageGenerator
{
    /**
     * The package name.
     *
     * @var string
     */
    public const NAME = 'package-generator';

    /**
     * The package version.
     *
     * @var string
     */
    public const VERSION = 'v0.0.0';

    /**
     * Get the name of the package.
     */
    public function packageName(): string
    {
        return static::NAME;
    }

    /**
     * Get the version number of the package.
     */
    public function packageVersion(): string
    {
        return static::VERSION;
    }

    /**
     * The config value.
     */
    public function configValue(string $configKeyName): mixed
    {
        return config($this->packageName().'.'.$configKeyName);
    }
}
