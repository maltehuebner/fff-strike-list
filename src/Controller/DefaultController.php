<?php declare(strict_types=1);

namespace App\Controller;

use App\CoordEnricher\CoordEnricherInterface;
use App\StrikeListParser\StrikeListParserInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/list")
     */
    public function listAction(StrikeListParserInterface $strikeListParser, CoordEnricherInterface $coordEnricher, SerializerInterface $serializer): Response
    {
        $eventList = $strikeListParser->parse();

        $coordEnricher->loadCoords();

        return new Response($serializer->serialize($eventList, 'json'));
    }
}