<?php

class RecipeFinderTest extends PHPUnit_Framework_TestCase {
    
    private $recipeFinder;
    private $testInputDir;
    
    protected function setUp() {
        include_once(dirname(__FILE__).'/../classes/recipe-finder.class.php');
        $this->recipeFinder = new RecipeFinder();
        
        $this->testInputDir = dirname(__FILE__) . '../test-input/';
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
}
?>