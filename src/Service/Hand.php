<?php

// src/Service/MessageGenerator.php
namespace App\Service;

class Hand
{
    const CATEGORIES = [
        "DIAMOND"   => "\u{2666}",
        "SPADE"     => "\u{2660}",
        "HEART"     => "\u{2665}",
        "CLUB"      => "\u{2663}"
    ];

    const COLORS= [
        "DIAMOND"   => "red",
        "SPADE"     => "black",
        "HEART"     => "red",
        "CLUB"      => "black"
    ];
    const HEIGHT = [
        "ACE"       => "1",
        "TWO"       => "2",
        "THREE"     => "3",
        "FOUR"      => "4",
        "FIVE"      => "5",
        "SIX"       => "6",
        "SEVEN"     => "7",
        "EIGHT"     => "8",
        "NINE"      => "9",
        "TEN"       => "10",
        "JACK"      => "J",
        "QUEEN"     => "Q",
        "KING"      => "K"
    ];

  public function get()
  {
    $res = '{"exerciceId":"5b132575975a0c0e5ee75a7d","dateCreation":1527981429687,"candidate":{"candidateId":"57187b7c975adeb8520a283c","firstName":"Othmane","lastName":"QABLAOUI"},"data":{"cards":[{"category":"CLUB","value":"SEVEN"},{"category":"DIAMOND","value":"EIGHT"},{"category":"SPADE","value":"EIGHT"},{"category":"CLUB","value":"KING"},{"category":"DIAMOND","value":"ACE"},{"category":"CLUB","value":"THREE"},{"category":"DIAMOND","value":"JACK"},{"category":"HEART","value":"FOUR"},{"category":"DIAMOND","value":"KING"},{"category":"DIAMOND","value":"TEN"}],"categoryOrder":["DIAMOND","HEART","SPADE","CLUB"],"valueOrder":["ACE","TWO","THREE","FOUR","FIVE","SIX","SEVEN","EIGHT","NINE","TEN","JACK","QUEEN","KING"]},"name":"cards"}';

    return json_decode($res);
  }

  public function set()
  {

  }

  public function getSorted($distribution)
  {
      $res = [];
      foreach ($distribution->data->categoryOrder as $category)
      {
          foreach ($distribution->data->cards as $card)
          {
              if ($card->category === $category)
              {
                  $res [$category][] = $card;
              }
          }
      }
      $results = [];
      foreach($res as $category => $cards)
      {
          foreach ($distribution->data->valueOrder as $value)
          {
            foreach ($cards as $card)
            {
                if ($value === $card->value)
                {
                    $results[] = $card;
                }
            }
          }
      }

      return $results;
     
  }
}
