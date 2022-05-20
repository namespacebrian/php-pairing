<?php
/**
 * PHP version 8
 *
 * @category GameModels
 * @package  Pantheon\PhpPairing\Models
 * @author   Sara McCutcheon <sara@pantheon.io>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     https://pantheon.io
 */

namespace Pantheon\PhpPairing;

/**
 * Class Door
 *
 * @category Models
 * @package  Pantheon\PhpPairing\Models
 * @author   Sara McCutcheon <sara@pantheon.io>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     https://pantheon.io
 */
class Door
{
    /**
     * The door number of this door
     *
     * @var int
     */
    private $_door_number;
    /**
     * True if the door is open, false if it is closed
     *
     * @var boolean
     */
    private $_is_open = false;
    /**
     * True if the winning prize lies behind this door
     *
     * @var boolean
     */
    private $_is_winner;

    /**
     * Constructs the door
     *
     * @param int  $door_number The number affixed to this door
     * @param bool $is_winner   True if this door contains the winner, false if it
     *                          does not
     */
    public function __construct(int $door_number, bool $is_winner)
    {
        $this->_door_number = $door_number;
        $this->_is_winner = $is_winner;
    }

    /**
     * Returns the number of this door
     *
     * @return int The door number
     */
    public function getDoorNumber(): int
    {
        return $this->_door_number;
    }

    /**
     * Returns whether this door is open
     *
     * @return bool True if the door is open, false if it is closed
     */
    public function isOpen(): bool
    {
        return $this->_is_open;
    }

    /**
     * Tells the user whether the door is a winner or not
     *
     * @return bool True if the door is a winner, false if not
     */
    public function isWinner(): bool
    {
        return $this->_is_winner;
    }

    /**
     * Opens the door to reveal whether it is a winner or not.
     *
     * @return bool True if the door is a winner, false if not
     */
    public function open(): bool
    {
        $this->_is_open = true;
        return $this->isWinner();
    }
}
