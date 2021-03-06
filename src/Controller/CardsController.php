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
   * @Route("/cards/play/{iterations}/{dryrun}")
   */
    public function play( $iterations=1, $dryrun=false, Hand $hand)
    {
        $results = $hand->launch($iterations, (bool)$dryrun);
        $error = "There were a problem when validating this cards hand!";
        $datas = $hand->getDatas($results);
        return $this->render(
                   'cards/play.html.twig',
                   $datas
               );

    }
}
