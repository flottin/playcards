<?php

// src/Service/MessageGenerator.php
namespace App\Service;

class Hands
{
  public function get()
  {
    $res = '{"exerciceId":"5b132575975a0c0e5ee75a7d","dateCreation":1527981429687,"candidate":{"candidateId":"57187b7c975adeb8520a283c","firstName":"Othmane","lastName":"QABLAOUI"},"data":{"cards":[{"category":"CLUB","value":"SEVEN"},{"category":"DIAMOND","value":"EIGHT"},{"category":"SPADE","value":"EIGHT"},{"category":"CLUB","value":"KING"},{"category":"DIAMOND","value":"ACE"},{"category":"CLUB","value":"THREE"},{"category":"DIAMOND","value":"JACK"},{"category":"HEART","value":"FOUR"},{"category":"DIAMOND","value":"KING"},{"category":"DIAMOND","value":"TEN"}],"categoryOrder":["DIAMOND","HEART","SPADE","CLUB"],"valueOrder":["ACE","TWO","THREE","FOUR","FIVE","SIX","SEVEN","EIGHT","NINE","TEN","JACK","QUEEN","KING"]},"name":"cards"}';

    return json_decode($res);
  }

  public function set()
  {

  }
}
