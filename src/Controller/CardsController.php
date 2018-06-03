<?php
// src/Controller/CardsController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\Hands;

class CardsController extends Controller
{
  /**
   * @Route("/cards/play")
   */
    public function play(Hands $hands)
    {
      var_dump($hands->get());
    
        $articles= [];
        return $this->render(
                   'cards/play.html.twig',
                   array('articles' => $articles)
               );

    }
}
