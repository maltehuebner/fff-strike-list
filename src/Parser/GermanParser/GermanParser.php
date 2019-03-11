<?php declare(strict_types=1);

namespace App\Parser\GermanParser;

use App\Model\StrikeEvent;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class GermanParser implements GermanParserInterface
{
    public function parse(): array
    {
        $client = new Client();
        $response = $client->get(self::LIST_URL);

        $html = $response->getBody();
        $crawler = new Crawler($response->getBody()->getContents());

        $crawler = $crawler->filter('table.wp-block-table tbody tr');

        $eventList = [];

        foreach ($crawler as $eventRow) {
            $eventLineContent = $eventRow->textContent;

            if ($strikeEvent = $this->createEventModel($eventLineContent)) {
                $eventList[] = $strikeEvent;
            }
        }

        return $eventList;
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

    protected function parseTime(string $time): \DateTime
    {
        preg_match( '/(\d{1,2})\:(\d{1,2})/' , $time, $matches);

        list($foo, $hour, $minute) = $matches;

        $dateTimeSpec = sprintf(self::DATE_TIME_SPEC, $hour, $minute);

        return new \DateTime($dateTimeSpec, new \DateTimeZone('Europe/Berlin'));
    }
}