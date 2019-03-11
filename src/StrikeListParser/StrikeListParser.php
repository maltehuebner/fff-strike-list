<?php declare(strict_types=1);

namespace App\StrikeListParser;

use App\Model\StrikeEvent;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class StrikeListParser implements StrikeListParserInterface
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

            try {
                list($cityName, $time, $location) = explode(',', $eventLineContent);

                $model = new StrikeEvent($cityName, new \DateTime(), $location);

                $eventList[] = $model;
            } catch (\ErrorException $exception) {

            }
        }

        return $eventList;
    }
}