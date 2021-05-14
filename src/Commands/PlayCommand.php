<?php
/**
 * PHP version 8
 *
 * @category GameCommands
 * @package  Pantheon\PhpPairing\Commands
 * @author   Sara McCutcheon <sara@pantheon.io>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     https://pantheon.io
 */

namespace Pantheon\PhpPairing\Commands;

use Pantheon\PhpPairing\Models\Game;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class PlayCommand
 *
 * @category GameCommands
 * @package  Pantheon\PhpPairing\Commands
 * @author   Sara McCutcheon <sara@pantheon.io>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     https://pantheon.io
 */
class PlayCommand extends Command
{
    protected static $defaultName = 'play';

    /**
     * The game model that will be played with
     *
     * @var Game
     */
    private $_game;

    /**
     * Configures the PlayCommand with a description and help text
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Runs the Monty Hall game')
            ->setHelp('This command will run the Monty Hall game');
        $this->_game = new Game();
    }

    /**
     * Executes the game command
     *
     * @param InputInterface  $input  The input stream
     * @param OutputInterface $output The output stream
     *
     * @return int The status will be 0 if the game was won and 1 if it was lost.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $game = &$this->_game;
        $helper = $this->getHelper('question');

        $select_door = function () use (
            $input,
            $output,
            $game,
            $helper
        ) {
            $door_selection_options = $game->getDoorSelectionOptions();
            if (count($door_selection_options) > 1) {
                $question = new ChoiceQuestion(
                    'Select a door from these options:',
                    $game->getDoorSelectionOptions()
                );
                $door_selection = $helper->ask($input, $output, $question);
            } else {
                $door_selection  = array_shift($door_selection_options);
            }
            $output->writeln("Your selected door is # {$door_selection}");
            $game->selectDoor($door_selection);
        };

        $select_door();

        while ($game->getNumberOfClosedDoors() > 2) {
            $opened_door = $game->openRandomLosingDoor();
            $output->writeln(
                'Door # ' . $opened_door->getDoorNumber()
                . ' has been opened and behind it is a goat.'
            );
            $remaining_closed_doors = $game->getClosedDoorNumbers();
            $question = new ConfirmationQuestion(
                'One of these remaining doors is a winner: '
                . implode(', ', $remaining_closed_doors)
                . PHP_EOL . 'Will you change your selection?' . PHP_EOL,
            );
            $will_reselect = $helper->ask($input, $output, $question);
            if ($will_reselect) {
                $select_door();
            }
        }

        $opened_door = $game->openSelectedDoor()->getDoorNumber();
        $output->writeln("You open door # {$opened_door}...");
        if ($game->isWon()) {
            $output->writeln('You won the car!');
            return Command::SUCCESS;
        }
        $output->writeln('You got the goat!');
        return Command::FAILURE;
    }
}