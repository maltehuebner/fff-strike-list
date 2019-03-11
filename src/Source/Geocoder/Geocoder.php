<?php declare(strict_types=1);

namespace App\Source\Geocoder;

use App\Model\StrikeEvent;
use Geocoder\Model\Coordinates;
use Geocoder\Plugin\PluginProvider;
use Geocoder\Query\GeocodeQuery;

class Geocoder implements GeocoderInterface
{
    /** @var PluginProvider $provider */
    protected $provider;

    public function __construct(PluginProvider $provider)
    {
        $this->provider = $provider;
    }

    protected function geocodeCityLocation(StrikeEvent $strikeEvent): ?Coordinates
    {
        $phrase = sprintf('%s, %s', $strikeEvent->getLocation(), $strikeEvent->getCityName());

        return $this->geocode($phrase);
    }

    protected function geocodeCity(StrikeEvent $strikeEvent): ?Coordinates
    {
        $phrase = sprintf('%s', $strikeEvent->getCityName());

        return $this->geocode($phrase);

    }

    protected function geocode(string $phrase): ?Coordinates
    {
        $query = GeocodeQuery::create($phrase);

        $result = $this->provider->geocodeQuery($query);

        if (!$result->isEmpty()) {
            return $result->first()->getCoordinates();
        }

        return null;
    }

    protected function geocodeStrikeEvent(StrikeEvent $strikeEvent): StrikeEvent
    {
        if ($strikeEvent->getLatitude() && $strikeEvent->getLongitude()) {
            return $strikeEvent;
        }

        $coord = $this->geocodeCityLocation($strikeEvent) ?? $this->geocodeCity($strikeEvent);

        if ($coord) {
            $strikeEvent->setLatitude($coord->getLatitude())->setLongitude($coord->getLongitude());
        }

        return $strikeEvent;
    }

    public function enrichStrikeList(array $strikeList): array
    {
        /** @var StrikeEvent $event */
        foreach ($strikeList as $event) {
            if (!$event->getLatitude() || !$event->getLongitude()) {
                $this->geocodeStrikeEvent($event);
            }
        }

        return $strikeList;
    }

    public function getStrikeList(): array
    {
        return [];
    }
}