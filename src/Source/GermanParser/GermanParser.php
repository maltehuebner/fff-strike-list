<?php declare(strict_types=1);

namespace App\Source\GermanParser;

use App\Model\StrikeEvent;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class GermanParser implements GermanParserInterface
{
    /** @var array $list */
    protected $list = [];

    public function __construct()
    {
        $this->loadData();
    }

    protected function createEventModel(string $eventLineContent): ?StrikeEvent
    {
        try {
            list($cityName, $time, $location) = explode(',', $eventLineContent);

            return new StrikeEvent(trim($cityName), $this->parseTime($time), trim($location));
        } catch (\ErrorException $exception) {
            return null;
        }
    }

    protected function loadData(): GermanParser
    {
        $client = new Client();
        $response = $client->get(self::LIST_URL);

        $html = $response->getBody();
        $crawler = new Crawler($response->getBody()->getContents());

        $crawler = $crawler->filter('table.wp-block-table tbody tr');

        foreach ($crawler as $eventRow) {
            $eventLineContent = $eventRow->textContent;

            if ($strikeEvent = $this->createEventModel($eventLineContent)) {
                $this->list[] = $strikeEvent;
            }
        }

        return $this;
    }

    protected function parseTime(string $time): \DateTime
    {
        preg_match( '/(\d{1,2})\:(\d{1,2})/' , $time, $matches);

        list($foo, $hour, $minute) = $matches;

        $dateTimeSpec = sprintf(self::DATE_TIME_SPEC, $hour, $minute);

        return new \DateTime($dateTimeSpec, new \DateTimeZone('Europe/Berlin'));
    }

    public function getStrikeList(): array
    {
        return $this->list;
    }

    public function enrichStrikeList(array $strikeList): array
    {
        /** @var StrikeEvent $strikeEvent1 */
        foreach ($strikeList as $strikeEvent1) {
            /** @var StrikeEvent $strikeEvent2 */
            foreach ($this->list as $strikeEvent2) {
                if ($strikeEvent1->getCityName() === $strikeEvent2->getCityName()) {
                    $strikeEvent1
                        ->setLatitude($strikeEvent2->getLatitude())
                        ->setLongitude($strikeEvent2->getLongitude())
                        ->setLocation($strikeEvent2->getLocation())
                        ->setDateTime($strikeEvent2->getDateTime());
                }
            }
        }
        return $strikeList;
    }
}