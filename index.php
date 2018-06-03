
<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class PlayCommand extends Command
{
    protected function configure()
    {
      $this
      // the name of the command (the part after "bin/console")
      ->setName('play:cards')

      // the short description shown while running "php bin/console list"
      ->setDescription('Cards distribution')

      // the full command description shown when running the command with
      // the "--help" option
      ->setHelp('Sort a set of cards');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...


      // ...
      $outputStyle = new OutputFormatterStyle('black', 'green', array());

      $outputStyleRed = new OutputFormatterStyle('red', 'green', array());
      $output->getFormatter()->setStyle('black', $outputStyle);
      $output->getFormatter()->setStyle('red', $outputStyleRed);


      // spade
      //$output->writeln("<black> 10\u{2660} </>");
      // heart
      //$output->writeln("<red> 10\u{2665} </>");
      //club
      //$output->writeln("<black> 10\u{2663} </>");
      //diamond
      //$output->writeln("<red> 10\u{2666} </>");

$distribution = '{"exerciceId":"5b132575975a0c0e5ee75a7d","dateCreation":1527981429687,"candidate":{"candidateId":"57187b7c975adeb8520a283c","firstName":"Othmane","lastName":"QABLAOUI"},"data":{"cards":[{"category":"CLUB","value":"SEVEN"},{"category":"DIAMOND","value":"EIGHT"},{"category":"SPADE","value":"EIGHT"},{"category":"CLUB","value":"KING"},{"category":"DIAMOND","value":"ACE"},{"category":"CLUB","value":"THREE"},{"category":"DIAMOND","value":"JACK"},{"category":"HEART","value":"FOUR"},{"category":"DIAMOND","value":"KING"},{"category":"DIAMOND","value":"TEN"}],"categoryOrder":["DIAMOND","HEART","SPADE","CLUB"],"valueOrder":["ACE","TWO","THREE","FOUR","FIVE","SIX","SEVEN","EIGHT","NINE","TEN","JACK","QUEEN","KING"]},"name":"cards"}';


$distributions = json_decode($distribution);
foreach ($distributions->data->categoryOrder as $category)
{
  var_dump($category);
}



      $cards = [];
      $spades = ['1','6','8','K'];
      $hearts = ['Q', 'J'];
      $clubs = ['2', 'Q'];
      $diamonds = ['3', '9'];

      $out = ' ';
      foreach ($spades as $card)
      {
        $out .= $card . "\u{2660} ";

      }
      $output->write("<black>$out</>");

      $out = ' ';
      foreach ($hearts as $card)
      {
        $out .= $card . "\u{2665} ";

      }
      $output->write("<red>$out</>");
$output->writeln("");
    }
}
$application = new Application();

// ... register commands

$application->add(new PlayCommand());
$application->run();
