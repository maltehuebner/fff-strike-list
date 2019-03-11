<?php declare(strict_types=1);

namespace App\Parser\GermanParser;

interface GermanParserInterface
{
    const LIST_URL = 'https://fridaysforfuture.de/march15th/';
    const DATE_TIME_SPEC = '2019-03-15 %d:%d:00';

    public function parse(): array;
}