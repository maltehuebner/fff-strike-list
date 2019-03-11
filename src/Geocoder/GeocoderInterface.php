<?php declare(strict_types=1);

namespace App\Geocoder;

use App\Model\StrikeEvent;

interface GeocoderInterface
{
    public function geocodeStrikeEvent(StrikeEvent $strikeEvent): StrikeEvent;
}