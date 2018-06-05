<?php
// src/Command/CreateUserCommand.php
namespace App\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputOption;
use App\Service\Hand;
use Twig_Environment;

class PlayCommand extends Command
{
    private $hand;

    private $twig;

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
      )
      ->addOption(
        'dryrun',
        null,
        InputOption::VALUE_OPTIONAL,
        'Launch with fake datas?',
        false
      );
    }

    public function __construct(Hand $hand, Twig_Environment $twig)
    {
        $this->hand = $hand;
        $this->twig = $twig;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $dryrun               = (bool)$input->getOption('dryrun');
      $iterations           = $input->getOption('iterations');
      $outputStyle          = new OutputFormatterStyle('black', 'green', array());
      $outputStyleRed       = new OutputFormatterStyle('red', 'white', array());
      $outputStyleCardBlack = new OutputFormatterStyle('black', 'white', array());
      $outputStyleValidated = new OutputFormatterStyle('green', 'default', array());
      $output->getFormatter()->setStyle('carpet', $outputStyle);
      $output->getFormatter()->setStyle('red', $outputStyleRed);
      $output->getFormatter()->setStyle('black', $outputStyleCardBlack);
      $output->getFormatter()->setStyle('validated', $outputStyleValidated);
      $results = $this->hand->launch($iterations, (bool)$dryrun);
      $datas = $this->hand->getDatas($results);
      $template = $this->twig->render('command/play.twig', $datas);
      $output->writeln( $template );
    }
}
