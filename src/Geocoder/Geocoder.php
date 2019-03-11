<?php declare(strict_types=1);

namespace App\Geocoder;

use App\Model\StrikeEvent;
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

    public function geocodeStrikeEvent(StrikeEvent $strikeEvent): StrikeEvent
    {
        if ($strikeEvent->getLatitude() && $strikeEvent->getLongitude()) {
            return $strikeEvent;
        }

        $searchPhrase = sprintf('%s, %s', $strikeEvent->getLocation(), $strikeEvent->getCityName());
        $query = GeocodeQuery::create($searchPhrase);

        $result = $this->provider->geocodeQuery($query);

        if (!$result->isEmpty()) {
            $coord = $result->first()->getCoordinates();

            if ($coord) {
                $strikeEvent->setLatitude($coord->getLatitude())->setLongitude($coord->getLongitude());
            }
        }

        return $strikeEvent;
    }

    public function geocodeEventList(array $eventList): array
    {
        /** @var StrikeEvent $event */
        foreach ($eventList as $event) {
            if (!$event->getLatitude() || !$event->getLongitude()) {
                $this->geocodeStrikeEvent($event);
            }
        }

        return $eventList;
    }
}