<?php declare(strict_types=1);

namespace App\Source\InternationalParser;

use App\LinkFactory\LinkFactoryInterface;
use App\Model\FffStrikeData;
use App\Model\StrikeEvent;
use App\Source\SourceLoaderInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\DomCrawler\Crawler;

class InternationalParser implements InternationalParserInterface
{
    /** @var SerializerInterface $serializer */
    protected $serializer;

    /** @var SourceLoaderInterface $sourceLoader */
    protected $sourceLoader;

    /** @var LinkFactoryInterface $linkFactory */
    protected $linkFactory;

    /** @var array $list */
    protected $list = [];

    public function __construct(LinkFactoryInterface $linkFactory, SourceLoaderInterface $sourceLoader, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->sourceLoader = $sourceLoader;
        $this->linkFactory = $linkFactory;

        $this->loadData();
    }

    protected function loadData(): InternationalParserInterface
    {
        $crawler = new Crawler($this->sourceLoader->load(self::MAP_URL));

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
                        ->setLongitude($fffStrikeData->getLon())
                        ->setLinks($this->linkFactory->grepLinks($fffStrikeData));
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

    protected function trimTownNames(string $town): string
    {
        $town = preg_replace('/[^[:alpha:], ]/', '', $town);
        $town = trim($town);
        $town = ucfirst($town);

        return $town;
    }

    protected function grepLinks(FffStrikeData $fffStrikeData): array
    {

        return [];
    }

    protected function convertModel(FffStrikeData $strikeData): StrikeEvent
    {
        try {
            $dateTime = new \DateTime($strikeData->getDate());
        } catch (\Exception $exception) {
            $dateTime = null;
        }

        return new StrikeEvent($this->trimTownNames($strikeData->getTown()),
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