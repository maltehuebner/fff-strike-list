<?php declare(strict_types=1);

namespace App\CoordEnricher;

use App\Model\FffStrikeData;
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

    public function loadCoords(): void
    {
        $client = new Client();
        $response = $client->get('https://fridaysforfuture.org/events/map');

        $crawler = new Crawler($response->getBody()->getContents());

        $crawler = $crawler->filter('body.page-map script');

        $source = $crawler->html();

        $source = str_replace(['var eventmap_data = ', '}];'], ['', '}]'], $source);

        $this->list = $this->serializer->deserialize($source, 'array<'.FffStrikeData::class.'>', 'json');
    }
}