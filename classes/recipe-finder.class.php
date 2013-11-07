<?php
class RecipeFinder {
    
    private $fridgeListCsv = null;
    private $recipesJson = null;
    
    function __construct($fridgeList, $recipes) {
        $this->fridgeListCsv = $fridgeList;
        $this->recipesJson = $recipes;
    }
    
    public function findRecipe() {
        return 'Salad';
    }
}
?>