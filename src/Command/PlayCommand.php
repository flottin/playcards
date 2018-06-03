<?php
// src/Command/CreateUserCommand.php
namespace App\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

use App\Service\Hand;

class PlayCommand extends Command
{
    public $hand;

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

    public function __construct(Hand $hand)
    {
        $this->hand = $hand;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputStyle = new OutputFormatterStyle('black', 'green', array());
        $outputStyleRed = new OutputFormatterStyle('red', 'green', array());
        $output->getFormatter()->setStyle('black', $outputStyle);
        $output->getFormatter()->setStyle('red', $outputStyleRed);

        $distribution = $this->hand->get();
        $sorted = $this->hand->getSorted($distribution);

//var_dump($sorted);
//die;
//var_dump($distribution->data->cards);
//var_dump($distribution->data);

        foreach ($distribution->data->cards as $card)
        {
            $out = "<";
            $out .= Hand::COLORS[$card->category];
            $out .= "> ";
            $out .= Hand::HEIGHT[$card->value];
            $out .= Hand::CATEGORIES[$card->category];
            $out .= "</>";

            $output->write($out);
        }
        $output->writeln("");


        foreach ($sorted as $card)
        {
            $out = "<";
            $out .= Hand::COLORS[$card->category];
            $out .= "> ";
            $out .= Hand::HEIGHT[$card->value];
            $out .= Hand::CATEGORIES[$card->category];
            $out .= "</>";

            $output->write($out);
        }
        $output->writeln("");
    }
}
