<?php

// src/Service/Hand.php
namespace App\Service;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Hand
{
    /*
    * use for final display the symbol of the category
    */
    const CATEGORIES = [
        "DIAMOND"   => "\u{2666}",
        "SPADE"     => "\u{2660}",
        "HEART"     => "\u{2665}",
        "CLUB"      => "\u{2663}"
    ];
    /*
    * use for final display the color of the category
    */
    const COLORS= [
        "DIAMOND"   => "red",
        "SPADE"     => "black",
        "HEART"     => "red",
        "CLUB"      => "black"
    ];
    /*
    * use for final display the height of the category
    */
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

    /*
    * the input array from the first call of webservice
    */
    private $distribution;

    /*
    * the input array from the first call of webservice
    */
    private $sorted;


    /**
    * launch the workflow :
    * call the first webservice any time
    * sort the response
    * push the response to the validation webservice
    * @param $iteraton integer num of webservice call
    * @return void
    */
    public function launch($iteration = 1)
    {

      $results = [];
      $client = new Client();

      $requests = function ($total) {

          $uri = 'https://recrutement.local-trust.com/test/cards/586f4e7f975adeb8520a4b88';
          for ($i = 0; $i < $total; $i++) {
              yield new Request('GET', $uri);
          }
      };
      $pool = new Pool($client, $requests($iteration), [
          'concurrency' => 5,
          'fulfilled' => function ($response, $index) use (&$results, &$logger)  {
              // this is delivered each successful response
              $distribution = json_decode($response->getBody()->getContents());
              $sorted = self::sort($distribution);
              try {
                self::set($sorted, $distribution);
                $results[] = [
                  'distribution' => $distribution,
                  'sorted' => $sorted,
                  'error'=>false
                  ] ;
              }
              catch (\Exception $e)
              {
                $results[] = ['error'=>$e->getMessage()];
              }
          },
          'rejected' => function ($reason, $index) {
              // this is delivered each failed request
          },
      ]);

      // Initiate the transfers and create a promise
      $promise = $pool->promise();

      // Force the pool of requests to complete.
      $promise->wait();

      return $results;
    }


    /**
    * launch the workflow :
    * call the first webservice
    * sort the response
    * push the response to the validation webservice
    * @deprecated
    * @return void
    */
    public function launch2()
    {
        $this->distribution = self::get();
        $this->sorted = self::sort($this->distribution);
        self::set($this->sorted, $this->distribution);
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

    /**
    * sort the cards according height and category order
    * @param array : return of webservice
    * @return array : the data of cards sorted
    */
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

    /**
    * get the return of the webservice
    * @return array
    */
    public function getDistribution()
    {
        return $this->distribution;
    }
    /**
    * get the hand sorted
    * @return array
    */
    public function getSorted()
    {
        return $this->sorted;
    }
}
