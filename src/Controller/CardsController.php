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
        var_dump($iterations);
        $error = false;
        try {
            $hand->launch();
        }
        catch (\Exception $e)
        {
            $error = "There were a problem when validating this cards hand!";
        }
        $distribution   = $hand->getDistribution();
        $sorted         = $hand->getSorted();
        $datas = array(
             'sorted'       => $sorted,
             'distribution' => $distribution,
             'colors'       => Hand::COLORS,
             'categories'   => Hand::CATEGORIES,
             'height'       => Hand::HEIGHT,
             'error'        => $error
         );
        return $this->render(
                   'cards/play.html.twig',
                   $datas
               );

    }
}
