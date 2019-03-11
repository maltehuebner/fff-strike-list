<?php declare(strict_types=1);

namespace App\Source\InternationalParser;

use App\Model\FffStrikeData;
use App\Model\StrikeEvent;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\DomCrawler\Crawler;

class InternationalParser implements InternationalParserInterface
{
    /** @var SerializerInterface $serializer */
    protected $serializer;

    /** @var array $list */
    protected $list = [];

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;

        $this->loadData();
    }

    protected function loadData(): InternationalParserInterface
    {
        $client = new Client();
        $response = $client->get(self::MAP_URL);

        $crawler = new Crawler($response->getBody()->getContents());

        $crawler = $crawler->filter('body.page-map script');

        $source = $crawler->html();

        $source = str_replace(['var eventmap_data = ', '}];'], ['', '}]'], $source);

        $this->list = $this->serializer->deserialize($source, 'array<'.FffStrikeData::class.'>', 'json');

        return $this;
    }

    public function enrichStrikeList(array $eventList): array
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

    protected function convertList(): array
    {
        $strikeList = [];

        /** @var FffStrikeData $data */
        foreach ($this->list as $data) {
            $strikeList[] = $this->convertModel($data);
        }

        return $strikeList;
    }

    protected function convertModel(FffStrikeData $strikeData): StrikeEvent
    {
        try {
            $dateTime = new \DateTime($strikeData->getDate());
        } catch (\Exception $exception) {
            $dateTime = null;
        }

        return new StrikeEvent($strikeData->getTown(),
            $dateTime,
            $strikeData->getLocation(),
            $strikeData->getLat(),
            $strikeData->getLon());

    }

    public function getStrikeList(): array
    {
        return $this->convertList();
    }
}