<?php declare(strict_types=1);

namespace App\CoordEnricher;

use App\Model\FffStrikeData;
use App\Model\StrikeEvent;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\DomCrawler\Crawler;

class CoordEnricher implements CoordEnricherInterface
{
    /** @var SerializerInterface $serializer */
    protected $serializer;

    /** @var array $list */
    protected $list = [];

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function loadCoords(): CoordEnricherInterface
    {
        $client = new Client();
        $response = $client->get('https://fridaysforfuture.org/events/map');

        $crawler = new Crawler($response->getBody()->getContents());

        $crawler = $crawler->filter('body.page-map script');

        $source = $crawler->html();

        $source = str_replace(['var eventmap_data = ', '}];'], ['', '}]'], $source);

        $this->list = $this->serializer->deserialize($source, 'array<'.FffStrikeData::class.'>', 'json');

        return $this;
    }

    public function enrichEventList(array $eventList): array
    {
        /** @var StrikeEvent $strikeEvent */
        foreach ($eventList as $strikeEvent) {
            /** @var FffStrikeData $fffStrikeData */
            foreach ($this->list as $fffStrikeData) {
                if ($strikeEvent->getCityName() === $fffStrikeData->getTown()) {
                    $strikeEvent
                        ->setLatitude($fffStrikeData->getLat())
                        ->setLongitude($fffStrikeData->getLon());
                }
            }
        }

        return $eventList;
    }
}