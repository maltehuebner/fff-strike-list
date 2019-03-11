<?php declare(strict_types=1);

namespace App\Controller;

use App\Source\Geocoder\GeocoderInterface;
use App\Source\GermanParser\GermanParserInterface;
use App\Source\InternationalParser\InternationalParserInterface;
use JMS\Serializer\SerializerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    const CACHE_KEY = 'strike-list';
    const CACHE_TTL = 1;

    /**
     * @Route("/list")
     */
    public function listAction(CacheInterface $cache, GermanParserInterface $germanParser, InternationalParserInterface $coordEnricher, SerializerInterface $serializer, GeocoderInterface $geocoder): JsonResponse
    {
        if ($cache->has(self::CACHE_KEY)) {
            return new JsonResponse($serializer->serialize($cache->get(self::CACHE_KEY), 'json'), 200, [], true);
        }

        $eventList = $germanParser->getStrikeList();

        $eventList = $coordEnricher->enrichStrikeList($eventList);

        $eventList = $geocoder->enrichStrikeList($eventList);

        $cache->set(self::CACHE_KEY, $eventList, self::CACHE_TTL);

        return new JsonResponse($serializer->serialize($eventList, 'json'), 200, [], true);
    }
}