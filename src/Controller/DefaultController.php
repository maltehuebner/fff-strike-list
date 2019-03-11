<?php declare(strict_types=1);

namespace App\Controller;

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
    public function listAction(StrikeListParserInterface $strikeListParser, SerializerInterface $serializer): Response
    {
        $eventList = $strikeListParser->parse();

        return new Response($serializer->serialize($eventList, 'json'));
    }
}