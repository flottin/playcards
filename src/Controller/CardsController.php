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
   * @Route("/cards/play/{iterations}")
   */
    public function play( $iterations=1, Hand $hand)
    {
        $results = $hand->launch($iterations);
        $error = "There were a problem when validating this cards hand!";
        $datas = array(
             'results'       => $results,
             'colors'       => Hand::COLORS,
             'categories'   => Hand::CATEGORIES,
             'height'       => Hand::HEIGHT
         );
        return $this->render(
                   'cards/play.html.twig',
                   $datas
               );

    }
}
