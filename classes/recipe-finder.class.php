<?php
class RecipeFinder {
    
    //List of ingredients in the fridge
    private $fridgeListCsv = null;
    private $fridgeList = array();
    
    //List of recipes
    private $recipesJson = null;
    private $recipes = array();
    
    //Holds any error message
    private $errorStr = '';
    
    function __construct($fridgeList, $recipes) {
        $this->fridgeListCsv = $fridgeList;
        $this->recipesJson = $recipes;
    }
    
    private function _parseFridgeList() {
        
    }
    
    public function getError() {
        return $this->errorStr;
    }
    
}
?>