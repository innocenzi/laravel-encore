<?php

namespace Innocenzi\LaravelEncore;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

class Encore
{
    /**
     * Reads a JSON file.
     */
    protected function readJsonFile(string $file)
    {
        return \json_decode(\file_get_contents($this->getBuildPath($file)), true);
    }

    /**
     * Get the build path.
     */
    protected function getBuildPath(string $file = ''): string
    {
        $path = \public_path(\config('encore.output_path') . DIRECTORY_SEPARATOR . $file);

        if (!\file_exists($path)) {
            throw new \LogicException(sprintf('%s not found. Did you forget to run Webpack Encore, or did you change its build path? Expected "%s".', $file, $path));
        }

        return $path;
    }

    /**
     * Get entries generated by Webpack Encore.
     */
    protected function getEntries(string $entryName, string $entryType, callable $format): ?Htmlable
    {
        ['entrypoints' => $entrypoints ] = $this->readJsonFile(\config('encore.files.entrypoints'));

        if (!($entries = Arr::get($entrypoints, "$entryName.$entryType"))) {
            return null;
        }

        return new HtmlString(implode('', array_map($format, $entries)));
    }

    /**
     * Get the HTML link tags required to import the styles.
     */
    public function getLinkTags(string $entryName): ?Htmlable
    {
        return $this->getEntries($entryName, 'css', function (string $link) {
            return sprintf('<link ref="stylesheet" href="%s"/>', $link);
        });
    }

    /**
     * Get the HTML tags required to import the scripts.
     */
    public function getScriptTags(string $entryName, bool $noDefer = false): ?Htmlable
    {
        return $this->getEntries($entryName, 'js', function (string $link) use ($noDefer) {
            return sprintf('<script src="%s" %s></script>', $link, $noDefer ?: 'defer');
        });
    }

    /**
     * Get the asset for the given url.
     */
    public function asset(string $path): ?string
    {
        return Arr::get($this->readJsonFile(\config('encore.files.manifest')), $path) ?? $path;
    }
}
