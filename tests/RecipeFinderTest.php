<?php
/*
    PHPUnit test cases for RecipeFinder class
*/

class RecipeFinderTest extends PHPUnit_Framework_TestCase {
    
    private $recipeFinder;
    private $testInputDir;
    
    protected function setUp() {
        include_once(dirname(__FILE__).'/../classes/recipe-finder.class.php');
        $this->recipeFinder = new RecipeFinder();
        
        $this->testInputDir = dirname(__FILE__) . '/../test-input/';
    }
    
    public function testMissingInput() {
        //Both missing
        $result = $this->recipeFinder->findRecipe('whatever.csv', 'something.json');
        $this->assertEquals(FALSE, $result);
        
        //Ingredients missing
        $result = $this->recipeFinder->findRecipe('whatever.csv', $this->testInputDir.'sample/recipes.json');
        $this->assertEquals(FALSE, $result);
        
        //Recipe list missing
        $result = $this->recipeFinder->findRecipe($this->testInputDir.'sample/fridge-list.csv', 'something.json');
        $this->assertEquals(FALSE, $result);
    }
    
    public function testEmptyInput() {
        //Both empty
        $result = $this->recipeFinder->findRecipe($this->testInputDir.'empty/fridge-list.csv', $this->testInputDir.'empty/recipes.json');
        $this->assertEquals(FALSE, $result);
        
        //Ingredients empty
        $result = $this->recipeFinder->findRecipe($this->testInputDir.'empty/fridge-list.csv', $this->testInputDir.'sample/recipes.json');
        $this->assertEquals(FALSE, $result);
        
        //Recipe list empty
        $result = $this->recipeFinder->findRecipe($this->testInputDir.'sample/fridge-list.csv', $this->testInputDir.'empty/recipes.json');
        $this->assertEquals(FALSE, $result);
    }
    
    public function testNoMatchingIngredients() {
        //Fridge list has no matching ingredients
        $result = $this->recipeFinder->findRecipe($this->testInputDir.'no-matching-ingredients/fridge-list.csv', $this->testInputDir.'sample/recipes.json');
        $this->assertEquals('Order Takeout', $result);
    }
    
    public function testSortByUseByDate() {
        //Peanut Butter On Toast has closest sell by date
        $result = $this->recipeFinder->findRecipe($this->testInputDir.'use-by-date/fridge-list.csv', $this->testInputDir.'use-by-date/recipes.json');
        $this->assertEquals('Peanut Butter On Toast', $result);
    }
    
    public function testExpiredIngredient() {
        //Mixed salad has expired, so instead of salad sandwich we should get cheese on toast
        $result = $this->recipeFinder->findRecipe($this->testInputDir.'expired-ingredient/fridge-list.csv', $this->testInputDir.'expired-ingredient/recipes.json');
        $this->assertEquals('Grilled Cheese On Toast', $result);
    }
}
?>