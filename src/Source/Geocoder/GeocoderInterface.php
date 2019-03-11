<?php declare(strict_types=1);

namespace App\Source\Geocoder;

use App\Source\SourceInterface;

interface GeocoderInterface extends SourceInterface
{
    public function enrichStrikeList(array $strikeList): array;
}