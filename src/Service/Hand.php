<?php

// src/Service/Hand.php
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

    private $distribution;

    private $sorted;

    public function launch()
    {
        $this->distribution = self::get();
        $this->sorted = self::sort($this->distribution);
        self::set($this->sorted, $this->distribution);
    }

    public function getDistribution()
    {
        return $this->distribution;
    }

    public function getSorted()
    {
        return $this->sorted;
    }

    public function get()
    {
        $client = new \GuzzleHttp\Client();
        $url = 'https://recrutement.local-trust.com/test/cards/586f4e7f975adeb8520a4b88';
        $res = $client->request('GET', $url);
        return json_decode($res->getBody());
    }

    public function set($sorted, $distribution)
    {
        $exerciceId = $distribution->exerciceId;
        foreach($distribution->data as $k=>$v)
        {
            if ($k == 'cards')
            {
                $d[$k] = $sorted;
            }
            else {
                $d[$k] = $v;
            }
        }
        $params = json_encode($d);
        $url = "https://recrutement.local-trust.com/test/{$exerciceId}";
        $client = new \GuzzleHttp\Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

        $response = $client->post($url,
            ['body' => $params]
        );
        return $response;
    }

    public function sort($distribution)
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
