<?php declare(strict_types=1);

namespace App\Controller;

use App\StrikeListParser\StrikeListParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/list")
     */
    public function listAction(StrikeListParserInterface $strikeListParser): Response
    {
        $eventList = $strikeListParser->parse();

        dump($eventList);

        return new Response('foo');
    }
}