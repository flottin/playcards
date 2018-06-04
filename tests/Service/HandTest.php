<?php
// tests/Util/CalculatorTest.php
namespace App\Tests\Service;

use App\Service\Hand;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class HandTest extends TestCase
{
    public function testSorted()
    {

        $d = '{"exerciceId":"5b14fbd6975a0c0e5ee75bcd","dateCreation":1528101846239,"candidate":{"candidateId":"586f4e7f975adeb8520a4b88","firstName":"Youssef","lastName":"Morjane"},"data":{"cards":[{"category":"SPADE","value":"SIX"},{"category":"SPADE","value":"SEVEN"},{"category":"DIAMOND","value":"TEN"},{"category":"HEART","value":"ACE"},{"category":"SPADE","value":"EIGHT"},{"category":"DIAMOND","value":"FOUR"},{"category":"HEART","value":"NINE"},{"category":"SPADE","value":"TWO"},{"category":"CLUB","value":"THREE"},{"category":"CLUB","value":"SIX"}],"categoryOrder":["DIAMOND","HEART","SPADE","CLUB"],"valueOrder":["ACE","TWO","THREE","FOUR","FIVE","SIX","SEVEN","EIGHT","NINE","TEN","JACK","QUEEN","KING"]},"name":"cards"}';
        $distribution = json_decode($d);

        $e = '[{"category":"DIAMOND","value":"FOUR"},{"category":"DIAMOND","value":"TEN"},{"category":"HEART","value":"ACE"},{"category":"HEART","value":"NINE"},{"category":"SPADE","value":"TWO"},{"category":"SPADE","value":"SIX"},{"category":"SPADE","value":"SEVEN"},{"category":"SPADE","value":"EIGHT"},{"category":"CLUB","value":"THREE"},{"category":"CLUB","value":"SIX"}]';
        $expected = json_decode ($e);
        $hand = new Hand();
        $result = $hand->sort($distribution);

        $this->assertEquals($expected, $result);
    }

    public function testBadsorted()
    {
        $d = '{"exerciceId":"5b14fbd6975a0c0e5ee75bcd","dateCreation":1528101846239,"candidate":{"candidateId":"586f4e7f975adeb8520a4b88","firstName":"Youssef","lastName":"Morjane"},"data":{"cards":[{"category":"SPADE","value":"SIX"},{"category":"SPADE","value":"SEVEN"},{"category":"DIAMOND","value":"TEN"},{"category":"HEART","value":"ACE"},{"category":"SPADE","value":"EIGHT"},{"category":"DIAMOND","value":"FOUR"},{"category":"HEART","value":"NINE"},{"category":"SPADE","value":"TWO"},{"category":"CLUB","value":"THREE"},{"category":"CLUB","value":"SIX"}],"categoryOrder":["DIAMOND","HEART","SPADE","CLUB"],"valueOrder":["ACE","TWO","THREE","FOUR","FIVE","SIX","SEVEN","EIGHT","NINE","TEN","JACK","QUEEN","KING"]},"name":"cards"}';
        $distribution = json_decode($d);

        $e = '[{"category":"DIAMOND","value":"FOURFIVESIX"},{"category":"DIAMOND","value":"TEN"},{"category":"HEART","value":"ACE"},{"category":"HEART","value":"NINE"},{"category":"SPADE","value":"TWO"},{"category":"SPADE","value":"SIX"},{"category":"SPADE","value":"SEVEN"},{"category":"SPADE","value":"EIGHT"},{"category":"CLUB","value":"THREE"},{"category":"CLUB","value":"SIX"}]';
        $expected = json_decode ($e);
        $hand = new Hand();
        $result = $hand->sort($distribution);
        $this->assertNotEquals($expected, $result);
    }
}
