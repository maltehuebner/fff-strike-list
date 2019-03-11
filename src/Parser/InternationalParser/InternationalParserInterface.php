<?php declare(strict_types=1);

namespace App\Parser\InternationalParser;

interface InternationalParserInterface
{
    public function enrichEventList(array $eventList): array;
}