<?php

if (! function_exists('packageName')) {
    /**
     * Package name.
     *
     * @throws Exception
     */
    function packageName(string $packageName = 'package-generator'): mixed
    {
        $packageGenerator = app('package-generator');

        return match ($packageName) {
            'package-generator' => $packageGenerator->packageName(),
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
        $packageGenerator = app('package-generator');

        return match ($packageName) {
            'package-generator' => $packageGenerator->packageVersion(),
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

        $packageGenerator = app('package-generator');

        return match ($packageName) {
            'package-generator' => $packageGenerator->configValue($configKeyName),
            default => throw new Exception(
                message: "$packageName is not supported yet.",
            ),
        };
    }
}
