<?php declare(strict_types=1);

namespace App\Controller;

use App\Model\StrikeEvent;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/list")
     */
    public function listAction(Request $request): Response
    {
        $client = new Client();
        $response = $client->get('https://fridaysforfuture.de/march15th/');

        $html = $response->getBody();
        $crawler = new Crawler($response->getBody()->getContents());

        $crawler = $crawler->filter('table.wp-block-table tbody tr');

        $eventList = [];

        foreach ($crawler as $eventRow) {
            $eventLineContent = $eventRow->textContent;

            try {
                list($cityName, $time, $location) = explode(',', $eventLineContent);

                $model = new StrikeEvent($cityName, new \DateTime(), $location);

                array_push($eventList, $model);
            } catch (\ErrorException $exception) {

            }
        }

        dump($eventList);

        return new Response('foo');
    }
}