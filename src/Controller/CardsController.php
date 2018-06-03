<?php
// src/Controller/CardsController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\Hand;

class CardsController extends Controller
{
  /**
   * @Route("/cards/play")
   */
    public function play(Hand $hand)
    {
        $distribution   = $hand->get();
        $sorted         = $hand->getSorted($distribution);

        return $this->render(
                   'cards/play.html.twig',
                   array('sorted' => $sorted, 'distribution' => $distribution->data->cards)
               );

    }
}
