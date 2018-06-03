<?php
// src/Command/CreateUserCommand.php
namespace App\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputOption;

use App\Service\Hand;

class PlayCommand extends Command
{
    public $hand;

    protected function configure()
    {
      $this
      ->setName('play:cards')
      ->setDescription('Cards distribution')
      ->setHelp('Sort a set of cards')
      ->addOption(
        'iterations',
        null,
        InputOption::VALUE_OPTIONAL,
        'How many times should the message be printed?',
        1
    );
    ;
    }

    public function __construct(Hand $hand)
    {
        $this->hand = $hand;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        for ($i = 0; $i < $input->getOption('iterations'); $i++) {
    //$output->writeln($i);
}
        $outputStyle = new OutputFormatterStyle('black', 'green', array());
        $outputStyleRed = new OutputFormatterStyle('red', 'green', array());
        $output->getFormatter()->setStyle('black', $outputStyle);
        $output->getFormatter()->setStyle('red', $outputStyleRed);
        try {
            $this->hand->launch();

            $distribution = $this->hand->getDistribution();
            $out = 'Category Order : ';
            foreach ($distribution->data->categoryOrder as $value)
            {
                $out .= $value . ' ';
            }
            $output->writeln($out);
            $out = 'Height Order : ';
            foreach ($distribution->data->valueOrder as $value)
            {
                $out .= $value . ' ';
            }
            $output->writeln($out);
            $output->writeln('Hand input : ');
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

            $sorted = $this->hand->getSorted();
            $output->writeln('Hand output : ');
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
        catch (\Exception $e)
        {
            $output->writeln("<error>There were a problem when validating this cards hand!</error>");
        }
    }
}
