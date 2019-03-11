<?php declare(strict_types=1);

namespace App\StrikeListParser;

interface StrikeListParserInterface
{
    const LIST_URL = 'https://fridaysforfuture.de/march15th/';

    public function parse(): array;
}