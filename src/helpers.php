<?php

if (! function_exists('packageName')) {
    /**
     * Package name.
     *
     * @throws Exception
     */
    function packageName(string $packageName = 'package-generator'): mixed
    {
        return match ($packageName) {
            'package-generator' => PackageGenerator::packageName(),
            default => throw new Exception(
                message: "$packageName is not supported yet.",
            ),
        };
    }
}

if (! function_exists('packageVersion')) {
    /**
     * Package version.
     *
     * @throws Exception
     */
    function packageVersion(string $packageName = 'package-generator'): mixed
    {
        return match ($packageName) {
            'package-generator' => PackageGenerator::packageVersion(),
            default => throw new Exception(
                message: "$packageName is not supported yet.",
            ),
        };
    }
}

if (! function_exists('configValue')) {
    /**
     * Config value.
     *
     * @throws Exception
     */
    function configValue(string $configKeyName, string $packageName = 'package-generator'): mixed
    {
        $configKeyName = str($configKeyName)->lower()->snake();

        return match ($packageName) {
            'package-generator' => PackageGenerator::configValue($configKeyName),
            default => throw new Exception(
                message: "$packageName is not supported yet.",
            ),
        };
    }
}
