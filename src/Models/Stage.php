<?php
/**
 * PHP version 8
 *
 * @category Models
 * @package  Pantheon\PhpPairing\Models
 * @author   Sara McCutcheon <sara@pantheon.io>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     https://pantheon.io
 */

namespace Pantheon\PhpPairing\Models;

/**
 * Class Stage
 *
 * @category Models
 * @package  Pantheon\PhpPairing\Models
 * @author   Sara McCutcheon <sara@pantheon.io>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     https://pantheon.io
 */
class Stage
{
    /**
     * The doors with with the game is played
     *
     * @var array
     */
    private array $_doors = [];

    /**
     * Stage constructor
     *
     * @param int $number_of_doors Number of doors in the game
     */
    public function __construct(int $number_of_doors)
    {
        $this->_installDoors($number_of_doors);
    }

    /**
     * Returns the set of doors that are closed
     *
     * @return array The list of closed, losing doors
     */
    public function getClosedDoors(): array
    {
        return $this->_getFilteredDoorList(
            function ($door) {
                return !$door->isOpen();
            }
        );
    }

    /**
     * Selects a door by its number
     *
     * @param int $door_number Number of the door to retrieve
     *
     * @return Door The door with this number
     */
    public function getDoor(int $door_number): Door
    {
        $matching_doors = $this->_getFilteredDoorList(
            function ($door) use ($door_number) {
                return $door->getDoorNumber() === $door_number;
            }
        );
        return array_shift($matching_doors);
    }

    /**
     * Determines the number of closed doors remaining
     *
     * @return int Number of remaining closed doors
     */
    public function getNumberOfClosedDoors(): int
    {
        return count(
            $this->_getFilteredDoorList(
                function ($door) {
                    return !$door->isOpen();
                }
            )
        );
    }

    /**
     * Opens one of the closed, losing doors
     *
     * @param Door|null $selected_door The contestant-selected door. If provided,
     *                                 the returned door will not be this door.
     *
     * @return Door $selected_losing_door The door opened to reveal the goat
     */
    public function getRandomLosingDoor(Door $selected_door = null): Door
    {
        return $this->getDoor(
            array_rand($this->_getClosedLosingDoors($selected_door))
        );
    }

    /**
     * Returns the set of doors that are both closed and not winners
     *
     * @param Door|null $selected_door The contestant-selected door. If provided,
     *                                 the returned door will not be this door.
     *
     * @return array The list of closed, losing doors
     */
    private function _getClosedLosingDoors(Door $selected_door = null): array
    {
        return $this->_getFilteredDoorList(
            function ($door) use ($selected_door) {
                return (
                    $selected_door === null
                    || $selected_door->getDoorNumber() !== $door->getDoorNumber()
                    )
                    && (!$door->isOpen() && !$door->isWinner());
            }
        );
    }

    /**
     * Filters the list of doors by a given function
     *
     * @param callable $filter_function The function by which to filter the doors
     *
     * @return array The list of doors matching the filter function.
     */
    private function _getFilteredDoorList(callable $filter_function): array
    {
        return array_filter($this->_doors, $filter_function);
    }

    /**
     * Initializes the Door objects for the game
     *
     * @param int $number_of_doors The number of doors to construct
     *
     * @return void
     * @throws \Exception
     */
    private function _installDoors(int $number_of_doors): void
    {
        $winning_door = $this->_pickWinningDoorNumber($number_of_doors);
        for ($i = 0; $i < $number_of_doors; $i++) {
            $this->_doors[] = new Door($i, $winning_door === $i);
        }
    }

    /**
     * Selects a winning door number
     *
     * @param int $number_of_doors The number of doors being constructed
     *
     * @return int The index of the winning door
     * @throws \Exception
     */
    private function _pickWinningDoorNumber(int $number_of_doors): int
    {
        return random_int(0, ($number_of_doors - 1));
    }
}