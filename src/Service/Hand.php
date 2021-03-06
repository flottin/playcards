<?php

// src/Service/Hand.php
namespace App\Service;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

use Psr\Log\LoggerInterface;

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
        "ACE"       => " 1",
        "TWO"       => " 2",
        "THREE"     => " 3",
        "FOUR"      => " 4",
        "FIVE"      => " 5",
        "SIX"       => " 6",
        "SEVEN"     => " 7",
        "EIGHT"     => " 8",
        "NINE"      => " 9",
        "TEN"       => "10",
        "JACK"      => " J",
        "QUEEN"     => " Q",
        "KING"      => " K"
    ];

    /*
    * the input array from the first call of webservice
    */
    private $distribution;

    /*
    * the output array result
    */
    private $sorted;

    /**
    *
    */
    public function __construct(LoggerInterface $logger=null)
    {
        $this->logger = $logger;
    }

    /**
    * use for dryrun
    * @param $iteration integer : num of webservice call
    * @return array results
    */
    public function launchTest($iteration = 1)
    {
      $d = '{"exerciceId":"5b14fbd6975a0c0e5ee75bcd","dateCreation":1528101846239,"candidate":{"candidateId":"586f4e7f975adeb8520a4b88","firstName":"Youssef","lastName":"Morjane"},"data":{"cards":[{"category":"SPADE","value":"SIX"},{"category":"SPADE","value":"SEVEN"},{"category":"DIAMOND","value":"TEN"},{"category":"HEART","value":"ACE"},{"category":"SPADE","value":"EIGHT"},{"category":"DIAMOND","value":"FOUR"},{"category":"HEART","value":"NINE"},{"category":"SPADE","value":"TWO"},{"category":"CLUB","value":"FIVE"},{"category":"CLUB","value":"FIVE"}],"categoryOrder":["DIAMOND","HEART","SPADE","CLUB"],"valueOrder":["ACE","TWO","THREE","FOUR","FIVE","SIX","SEVEN","EIGHT","NINE","TEN","JACK","QUEEN","KING"]},"name":"cards"}';
      $distribution = json_decode($d);

      $e = '[{"category":"DIAMOND","value":"FOUR"},{"category":"DIAMOND","value":"TEN"},{"category":"HEART","value":"ACE"},{"category":"HEART","value":"NINE"},{"category":"SPADE","value":"TWO"},{"category":"SPADE","value":"SIX"},{"category":"SPADE","value":"SEVEN"},{"category":"SPADE","value":"EIGHT"},{"category":"CLUB","value":"THREE"},{"category":"CLUB","value":"SIX"}]';
      $sorted = json_decode ($e);
      $results = [];
      for($i = 1; $i <= $iteration ; $i ++)
      {
        $results[] = [
        'distribution' => $distribution,
        'sorted' => $sorted,
        'error'=>false
        ] ;
      }
        return $results;
    }
    /**
    * launch the workflow :
    * call the first webservice any time needed
    * sort the response
    * push the response to the validation webservice
    * @param $iteration integer : num of webservice call
    * @return array : the result
    */
    public function launch($iteration = 1, $dryrun = false)
    {
      if (true === $dryrun )
      {
        return self::launchTest($iteration);
      }
      $results = [];
      $client = new Client([
          'curl' => [
              CURLOPT_SSL_VERIFYPEER => false
          ]
              ]
      );

      $requests = function ($total) {

          $uri = 'https://recrutement.local-trust.com/test/cards/586f4e7f975adeb8520a4b88';
          for ($i = 0; $i < $total; $i++) {
              yield new Request('GET', $uri);
          }
      };
      $logger = $this->logger;
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
                  var_dump($e->getMessage());
                $logger->error(__method__ . ' : ' . $e->getMessage());
                $results[] = ['error'=>true];
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
    * get datas for display
    * @param array $results
    * @return array enhanced result
    */
    public function getDatas($results)
    {
      $datas = array(
           'results'       => $results,
           'colors'       => self::COLORS,
           'categories'   => self::CATEGORIES,
           'height'       => self::HEIGHT
       );
       return $datas;
    }


    /**
    * push the result to the validation webservice
    * call the first webservice
    * @throws guzzle Exception
    * @return
    */
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

            'curl' => [CURLOPT_SSL_VERIFYPEER => false],

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
      if (!isset ($distribution->data)) return [];
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
