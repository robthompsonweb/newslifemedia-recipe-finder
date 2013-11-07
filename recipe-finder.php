<?php
include(dirname(__FILE__).'/classes/recipe-finder.class.php');

//Check we have the correct inputs
if(count($argv) != 3) {
    echo "Incorrect arguments. Correct usage: recipe-finder.php fridge-list recipe-list";
    exit;
}

$recipeFinder = new RecipeFinder($argv[1], $argv[2]);
$recipe = $recipeFinder->findRecipe();

if($recipe !== FALSE) {
    echo $recipe;
}
else {
    echo $recipeFinder->getError();
}

?>