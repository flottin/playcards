<?php
// src/Command/CreateUserCommand.php
namespace App\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputOption;
use Psr\Log\LoggerInterface;

use App\Service\Hand;

class PlayCommand extends Command
{
    public $hand;

    private $logger;

    protected function configure()
    {
      $this
        ->setName('play:cards')
        ->setDescription('Cards distribution')
        ->setHelp('Sort a set of cards : php bin/console play:cards --iteration=5')
        ->addOption(
          'iterations',
          null,
          InputOption::VALUE_OPTIONAL,
          'How many hands do you need?',
          1
      );

    }

    public function __construct(Hand $hand, LoggerInterface $logger)
    {
        $this->hand = $hand;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $iterations = $input->getOption('iterations');
        $outputStyle = new OutputFormatterStyle('black', 'green', array());
        $outputStyleRed = new OutputFormatterStyle('red', 'green', array());
        $output->getFormatter()->setStyle('black', $outputStyle);
        $output->getFormatter()->setStyle('red', $outputStyleRed);

        $results = $this->hand->launch($input->getOption('iterations'));
        foreach ($results as $num => $result)
        {
            if (false !== $result['error'])
            {
              $output->writeln("<error>There were a problem when validating this cards hand!</error>");
              $output->writeln("");
              $output->writeln("");
              $this->logger->error(__method__ . ' : ' . $result['error']);
              continue;
            }
            $it = $num + 1;
            $output->writeln('Iteration : ' . $it);
            $distribution = $result['distribution'];
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

            $sorted = $result['sorted'];
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
            $output->writeln("");
      }
    }
}
