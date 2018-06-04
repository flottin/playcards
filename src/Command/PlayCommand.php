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

    public function __construct(Hand $hand)
    {
        $this->hand = $hand;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $iterations = $input->getOption('iterations');
        $outputStyle = new OutputFormatterStyle('black', 'green', array());
        $outputStyleRed = new OutputFormatterStyle('red', 'white', array());
        $outputStyleCard = new OutputFormatterStyle('red', 'white', array());
        $outputStyleCardBlack = new OutputFormatterStyle('black', 'white', array());
        $output->getFormatter()->setStyle('carpet', $outputStyle);
        $output->getFormatter()->setStyle('red', $outputStyleRed);
        $output->getFormatter()->setStyle('card', $outputStyleCard);
        $output->getFormatter()->setStyle('black', $outputStyleCardBlack);
        $results = $this->hand->launch($input->getOption('iterations'));
        $greenCarpet = '<carpet>'.str_pad(' ', 40).'</>';
        foreach ($results as $num => $result)
        {
            if (false !== $result['error'])
            {
              $output->writeln("<error>There were a problem when validating this cards hand!</error>");
              $output->writeln("");
              $output->writeln("");

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

            $output->writeln($greenCarpet);
            foreach ($distribution->data->cards as $card)
            {

                $out = "<carpet> </><";
                $out .= Hand::COLORS[$card->category];
                $out .= ">";
                $out .= Hand::HEIGHT[$card->value];
                $out .= Hand::CATEGORIES[$card->category];
                $out .= "</><carpet> </>";
                $output->write($out);
            }
            $output->writeln("");
            $output->writeln($greenCarpet);
            $output->writeln("");

            $sorted = $result['sorted'];
            $output->writeln('Hand output : ');
            $output->writeln($greenCarpet);
            foreach ($sorted as $card)
            {
                $out = "<carpet> </><";
                $out .= Hand::COLORS[$card->category];
                $out .= ">";
                $out .= Hand::HEIGHT[$card->value];
                $out .= Hand::CATEGORIES[$card->category];
                $out .= "</><carpet> </>";

                $output->write($out);
            }
            $output->writeln("");
            $output->writeln($greenCarpet);
            $output->writeln("");
            $output->writeln("");
      }
    }
}
