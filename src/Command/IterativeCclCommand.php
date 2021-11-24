<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IterativeCclCommand extends Command
{
    protected const MATRIX_NULL_VALUE = 0;
    protected const VALID_INPUT_RANGE = [self::MATRIX_NULL_VALUE, 1];
    protected static $defaultName = 'app:iterative-ccl';
    protected static string $defaultDescription = 'Add a short description for your command';
    private int $widestColumnSize = 0;

    private array $matrix = [];

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $question = 'Please input the matrix first row (use space to separate values). Press enter when done!';
        $question .= PHP_EOL . "Rows will be automatically padded with " . self::MATRIX_NULL_VALUE . "'s if needed";

        while (true) {
            $answer = $io->ask($question);
            $question = 'Please input the next row!';
            if ($answer == PHP_EOL or $answer == null) {
                break;
            }

            $row = explode(' ', $answer);
            $isError = $this->isInvalid($row);

            if ($isError !== false) {
                $io->error("Invalid input at index $isError ->:" . $row[$isError]);
                continue;
            }

            $this->matrix[] = $row;
            $this->widestColumnSize = max($this->widestColumnSize, count($row));
        }

        if (count($this->matrix) == 0) {
            $io->warning('No input found!');
            return 0;
        }

        $this->widestColumnSize = $this->getWidestColumnSize($this->matrix);
        foreach ($this->matrix as &$row) {
            $row = array_pad($row, $this->widestColumnSize, self::MATRIX_NULL_VALUE);
        }

        // print matrix as table
        $io->note('Matrix:');
        $io->table([], $this->matrix);

        $this->iterativeCcl($this->matrix);
        $io->success('Bye!');

        return 0;
    }

    /**
     * Returns false if the row is valid (contains only numbers), otherwise returns the index of the first invalid value.
     * @return false|int
     */
    public function isInvalid(array $row)
    {
        $rowLength = count($row);
        for ($i = 0; $i < $rowLength; $i++) {
            if (!in_array($row[$i], self::VALID_INPUT_RANGE)) {
                return $i;
            }
        }

        return false;
    }

    private function getWidestColumnSize(array &$matrix): int
    {
        return count(max($matrix));
    }

}
