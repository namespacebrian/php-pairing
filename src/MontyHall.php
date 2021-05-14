<?php
/**
 * PHP version 8
 *
 * @category Game
 * @package  Pantheon\PhpPairing
 * @author   Sara McCutcheon <sara@pantheon.io>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     https://pantheon.io
 */

namespace Pantheon\PhpPairing;

use Pantheon\PhpPairing\Commands\PlayCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MontyHall
 *
 * @category Game
 * @package  Pantheon\PhpPairing
 * @author   Sara McCutcheon <sara@pantheon.io>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     https://pantheon.io
 */
class MontyHall
{
    const NAME_OF_THE_GAME = 'Monty Hall';
    const VERSION_NUMBER = '0.1.0';

    /**
     * The Symfony Console Application instance
     *
     * @var Application
     */
    private $_app;

    /**
     * Constructs the application and runs its sole command
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $command = new PlayCommand();
        $this->_app = new Application(self::NAME_OF_THE_GAME, self::VERSION_NUMBER);
        $this->_app->add($command);
        $this->_app->setDefaultCommand($command->getName());
    }

    /**
     * Runs the Console application
     *
     * @return void
     * @throws \Exception
     */
    public function run(): void
    {
        $this->_app->run();
    }
}