#!/usr/bin/env php
<?php
namespace Pantheon\PhpPairing;

//use Pantheon\PhpPairing\Game;
//spl_autoload_register(function ($class) {
//  // Adapt this depending on your directory structure
//  $parts = explode('\\', $class);
//  include "./src/" . end($parts) . '.php';
//});

include "./src/Door.php";
include "./src/Game.php";
include "./src/Stage.php";

$game = new Game();

//if(empty($argv[1]) || !is_numeric($argv[1])) {
//  echo "\n";
//  echo "Usage: " . $argv[0] . " <integer>\n";
//  echo "\n";
//  exit(1);
//}
//echo "You chose door #" . $argv[1] . "\n\n";
//$game->selectDoor($argv[1]);

echo "\n";

function select_door($game) {
  $door_selection_options = $game->getDoorSelectionOptions();
  if (count($door_selection_options) > 1) {
    $question = 'Select a door from these options [' . implode(', ', $game->getDoorSelectionOptions()) . "] ";

    $door_selection = readline($question);
  } else {
    $door_selection  = array_shift($door_selection_options);
  }
  echo "Your selected door is # {$door_selection}\n\n";
  $game->selectDoor((int) $door_selection);
};
select_door($game);



while ($game->getNumberOfClosedDoors() > 2) {
  $opened_door = $game->openRandomLosingDoor();
  echo 'Door # ' . $opened_door->getDoorNumber()
    . ' has been opened and behind it is a goat.' . "\n";

  $remaining_closed_doors = $game->getClosedDoorNumbers();

  echo PHP_EOL . 'One of these remaining doors is a winner: '
    . implode(', ', $remaining_closed_doors) . "\n\n";

  $question =  'Will you change your selection? (yes/no) ';

  $will_reselect = '';
  while(strtolower(trim($will_reselect)) != "yes" && strtolower(trim($will_reselect)) != "no") {
    $will_reselect = readline($question);
  }
//  echo "\n You said $will_reselect\n\n";

  if (strtolower(trim($will_reselect)) == "yes") {
    select_door($game);
  }
}

$opened_door = $game->openSelectedDoor()->getDoorNumber();
echo "You open door # {$opened_door}...\n";
if ($game->isWon()) {
  echo "You won the car!\n\n";
  exit(0);
}
echo "You got the goat :sad-trombone:\n\n";
