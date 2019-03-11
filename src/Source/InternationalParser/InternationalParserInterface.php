<?php declare(strict_types=1);

namespace App\Source\InternationalParser;

interface InternationalParserInterface
{
    const MAP_URL = 'https://fridaysforfuture.org/events/map';

    public function enrichEventList(array $eventList): array;
}