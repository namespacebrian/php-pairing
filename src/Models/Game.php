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
 * Class Game
 *
 * @category Models
 * @package  Pantheon\PhpPairing\Models
 * @author   Sara McCutcheon <sara@pantheon.io>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     https://pantheon.io
 */
class Game
{
    const NUMBER_OF_DOORS = 3;

    /**
     * True when the game has been completed
     *
     * @var bool
     */
    private $_is_complete = false;
    /**
     * True if the game has been won
     *
     * @var bool
     */
    private $_is_won;
    /**
     * The index of the door selected by the player
     *
     * @var int
     */
    private $_selected_door;
    /**
     * The stage on which the game is played
     *
     * @var Stage
     */
    private $_stage;

    /**
     * Game constructor
     */
    public function __construct()
    {
        $this->_setStage(self::NUMBER_OF_DOORS);
    }

    /**
     * Returns the list of closed door numbers for selection
     *
     * @return array The numbers of all closed doors
     */
    public function getClosedDoorNumbers(): array
    {
        return array_map(
            function ($door) {
                return $door->getDoorNumber();
            },
            $this->_stage->getClosedDoors()
        );
    }
    /**
     * Returns the list of closed door numbers for selection
     *
     * @return array The numbers of all selectable closed doors
     */
    public function getDoorSelectionOptions(): array
    {
        $available_doors = array_filter(
            $this->_stage->getClosedDoors(),
            function ($door) {
                return empty($this->_selected_door)
                    || (
                        $door->getDoorNumber() !==
                        $this->_selected_door->getDoorNumber()
                    );
            }
        );
        return array_map(
            function ($door) {
                return $door->getDoorNumber();
            },
            $available_doors
        );
    }

    /**
     * Determines the number of closed doors remaining
     *
     * @return int Number of remaining closed doors
     */
    public function getNumberOfClosedDoors(): int
    {
        return $this->_stage->getNumberOfClosedDoors();
    }

    /**
     * Determines the number of doors available for selection
     *
     * @return int Number of doors available for selection
     */
    public function getNumberOfDoorOptions(): int
    {
        return count($this->getDoorSelectionOptions());
    }

    /**
     * Determines whether the game has been won or not
     *
     * @return bool True if the game has been won, false if not.
     */
    public function isWon(): bool
    {
        return $this->_is_won;
    }

    /**
     * Opens one of the closed, losing doors
     *
     * @return Door $selected_losing_door The door opened to reveal the goat
     */
    public function openRandomLosingDoor(): Door
    {
        $losing_door = $this->_stage->getRandomLosingDoor($this->_selected_door);
        $losing_door->open();
        return $losing_door;
    }

    /**
     * Opens the player-selected door and concludes the game
     *
     * @return Door The selected door which was just opened
     */
    public function openSelectedDoor(): Door
    {
        $this->_selected_door->open();
        $this->_is_won = $this->_selected_door->isWinner();
        $this->_is_complete = true;
        return $this->_selected_door;
    }

    /**
     * Sets the player-selected door
     *
     * @param int $door_selection The index of the door selected by the player
     *
     * @return Door The door selected
     */
    public function selectDoor(int $door_selection): Door
    {
        $this->_selected_door = $this->_stage->getDoor($door_selection);
        return $this->_selected_door;
    }

    /**
     * Sets up the Stage object for the game
     *
     * @param int $number_of_doors Number of doors in the game
     *
     * @return Stage The stage on which the game is played
     */
    private function _setStage(int $number_of_doors): Stage
    {
        $this->_stage = new Stage($number_of_doors);
        return $this->_stage;
    }
}