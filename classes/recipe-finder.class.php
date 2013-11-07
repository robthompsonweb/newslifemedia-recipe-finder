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
        $fridgeListHandle = @fopen($this->fridgeListCsv, 'r');
        if($fridgeListHandle === FALSE) {
            $this->errorStr = 'Unable to open fridge-list';
            return FALSE;
        }
        
        $currentTimestamp = time();
        while(($row = fgetcsv($fridgeListHandle, 1000, ',')) !== FALSE) {
            //Check its a valid item
            if(count($row) != 4) {
                continue;
            }
            
            //Replace / by . in the date so its not presumed to be in American format by php
            $useBy = strtotime(str_replace('/', '.', $row[3]));
            
            //Check its still in date - ignore out of date items
            if($useBy > $currentTimestamp)  {
                //Index by item so easier to find matches
                $this->fridgeList[strtolower($row[0])] = array(
                    'amount' => $row[1],
                    'unit' => $row[2],
                    'use-by' => $useBy,
                );
            }
        }
        fclose($fridgeListHandle);
        
        //Do we have something in the fridge to work with?
        if(count($this->fridgeList) < 1) {
            $this->errorStr = 'fridge-list is empty';
            return FALSE;
        }
        
        return TRUE;
    }
    
    private function _parseRecipes() {
        $recipeListStr = @file_get_contents($this->recipesJson);
        if($recipeListStr === FALSE) {
            $this->errorStr = "Unable to open recipe-list";
            return FALSE;
        }
        
        $this->recipes = json_decode($recipeListStr);
        
        if(!is_array($this->recipes) OR empty($this->recipes)) {
            $this->errorStr = "Unable to parse recipe-list or its empty";
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function findRecipe() {
        if(!$this->_parseFridgeList()) {
            return FALSE;
        }
        if(!$this->_parseRecipes()) {
            return FALSE;
        }
        
        //For each recipe check if we have all the ingredients in the fridge
        $matchingRecipeIndexes = array();
        foreach($this->recipes as $recipeIndex=>$recipe) {
            $ingredientsInThisRecipeMatched = 0;
            
            foreach($recipe->ingredients as $ingredient) {
                if(isset($this->fridgeList[strtolower($ingredient->item)])) {
                    $fridgeItem = $this->fridgeList[strtolower($ingredient->item)];
                    
                    //Check we have the same units and enough in the fridge
                    if($ingredient->unit == $fridgeItem['unit'] AND $ingredient->amount <= $fridgeItem['amount']) {
                       $ingredientsInThisRecipeMatched++;
                    }
                }
            }
            
            //Do we have all the ingredits for the recipe?
            if($ingredientsInThisRecipeMatched == count($recipe->ingredients)) {
                $matchingRecipeIndexes[] = $recipeIndex;
            }
        }
        
        print_r($matchingRecipeIndexes);
    }
    
    public function getError() {
        return $this->errorStr;
    }
    
}
?>