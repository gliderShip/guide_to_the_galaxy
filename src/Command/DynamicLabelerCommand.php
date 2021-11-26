<?php

namespace App\Command;

use App\Model\Matrix;
use App\Service\ConnectedComponentLabeler;
use App\Service\RecursiveLabeler;
use App\Service\StackLabeler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DynamicLabelerCommand extends Command
{
    protected static $defaultName = 'app:dynamic-labeler';
    protected static string $defaultDescription = 'Connected-component labeling';

    private ConnectedComponentLabeler $labeler;

    public function __construct(StackLabeler $labeler)
    {
        parent::__construct();
        $this->labeler = $labeler;
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $question = 'Please input the matrixElements first row (use space to separate values). Press enter when done!'.PHP_EOL;
        $question .=  "Rows will be automatically padded with " . Matrix::BACKGROUND_ELEMENT_VALUE . "'s if needed";

        $matrixElements = [];
        $endOfInput = false;

        while (!$endOfInput) {
            $answer = $io->ask($question);
            $question = 'Please input the next row!';

            if ($answer == PHP_EOL || $answer == null) {
                $endOfInput = true;
                continue;
            }

            $row = explode(' ', $answer);
            $error = Matrix::isIllegalRow($row);

            if ($error !== false) {
                $io->error("Invalid input at index $error ->:" . $row[$error]);
                continue;
            }

            $matrixElements[] = $row;
        }

        if (count($matrixElements) == 0) {
            $io->warning('No input found!');
            return 0;
        }

        $matrix = new Matrix($matrixElements);

        // print matrixElements as table
        $io->table([new TableCell("Matrix", ['colspan' => $matrix->getTotalColumns()])], $matrix->getMatrix());

        $groups = $this->labeler->getGroupsNr($matrix, $io);
        $io->success(sprintf('Found %d groups', $groups));
        $io->success('Bye!');

        return 0;
    }

}
