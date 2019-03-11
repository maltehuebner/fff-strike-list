<?php declare(strict_types=1);

namespace App\Source\InternationalParser;

use App\Source\SourceInterface;

interface InternationalParserInterface extends SourceInterface
{
    const MAP_URL = 'https://fridaysforfuture.org/events/map';
}