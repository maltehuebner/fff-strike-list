<?php declare(strict_types=1);

namespace App\Source;

interface SourceLoaderInterface
{
    const ADAPTER_KEY = 'url-cache';

    public function load(string $url): string;
}