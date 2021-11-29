<?php
/*
 * Description: Validate content of the CSV file with validations
 */
class CheckCSVContentValidations
{
    /*
     * Description: Store that the import process is allowed to be executed
     * Value type(s): Boolean
     */
    private static $bIsImportProcessAllowed = true;

    /*
     * Description: Mark what(CSV value(s)) variable(s) can't be with null value
     * Value type(s): Boolean
     */
    private static $bCanBeNullProductId = false;
    private static $bCanBeNullName = false;
    private static $bCanBeNullRelatedProdIds = true;
    
    /*
     * Description: Store the value to validate, and the variable can be with null value or not
     * Value type(s): String or Integer, Boolean
     */
    private static $vValueToTest = null;
    private static $bValueCanBeNull = null;
    
    public function __construct()
    {
        
    }
    
    /*
     * Description: Validate the value existance and null or not null is allowed
     * Parameters: $vValueToTest, $bValueCanBeNull
     * Value type(s): String or Integer, Boolean
     */
    private function isVariableDeclared($vValueToTest, $bValueCanBeNull) 
    {
        /*
         * Description: Do basic validation steps for given value from the CSV file
         * Parameters: $vValueToTest, $bValueCanBeNull
         * Value type(s): Varying, Boolean
         */
        if(isset($vValueToTest))
        {
            if(gettype($vValueToTest) === "NULL")
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            if($bValueCanBeNull === true)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    
    /*
     * Description: Validate the value for Name from the CSV file
     * Parameters: $iProductId, $bCanBeNullProductId
     * Value type(s): Integer, Boolean
     */
    protected function validateProductId($iProductId, $bCanBeNullProductId)
    {
        /*
         * Description: Do validation step for Name from the CSV file
         * Parameters: $iProductId, $bCanBeNullProductId
         * Value type(s): Integer, Boolean
         */
        if($this->isVariableDeclared($iProductId, $bCanBeNullProductId) === true)
        {
            if(is_int(intval($iProductId)) === true)
            {
                if($iProductId > 0)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
    /*
     * Description: Validate the value for Category from the CSV file
     * Parameters: $sName, $bCanBeNullName
     * Value type(s): String, Boolean
     */
    protected function validateName($sName, $bCanBeNullName)
    {
        /*
         * Description: Do validation step for Category from the CSV file
         * Parameters: $sName, $bCanBeNullName
         * Value type(s): String, Boolean
         */
        if($this->isVariableDeclared($sName, $bCanBeNullName) === true)
        {
            if(is_string($sName) === true)
            {
                if(strlen($sName) > 0 && strlen($sName) < 120)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
    /*
     * Description: Validate the value for Price from the CSV file
     * Parameters: $sRelatedProdIds, $bCanBeNullRelatedProdIds
     * Value type(s): String, Boolean
     */
    protected function validateDescription($sRelatedProdIds, $bCanBeNullRelatedProdIds)
    {
        /*
         * Description: Do validation step for Price from the CSV file
         * Parameters: $sRelatedProdIds, $bCanBeNullRelatedProdIds
         * Value type(s): String, Boolean
         */
        if($this->isVariableDeclared($sRelatedProdIds, $bCanBeNullRelatedProdIds) === true)
        {
            if(is_string($sRelatedProdIds) === true)
            {
                if(strlen($sRelatedProdIds) > 0 && strlen($sRelatedProdIds) < 100)
                {
                    return true;
                }
                else
                {
                    if($bCanBeNullRelatedProdIds === true)
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else
            {
                if($bCanBeNullRelatedProdIds === true)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        else
        {
            return false;
        }
    }
    
    /*
     * Description: Run the validations for CSV file
     * Parameters: $iProductId, $sName, $sRelatedProdIds
     * Value type(s): Integer, String, String
     */
    public function validateCSVContent($iProductId, $sName, $sRelatedProdIds)
    {
        // Validate Name from CSV file
        if($this->validateProductId($iProductId, self::$bCanBeNullProductId) === false)
        {
            self::$bIsImportProcessAllowed = false;
        }
        
        // Validate Category from CSV file
        if($this->validateName($sName, self::$bCanBeNullName) === false)
        {
            self::$bIsImportProcessAllowed = false;
        }
        
        // Validate Price from CSV file
        if($this->validateDescription($sRelatedProdIds, self::$bCanBeNullRelatedProdIds) === false)
        {
            self::$bIsImportProcessAllowed = false;
        }
        
        // If import is allowed then run the import to data storage process, otherwise not
        if(self::$bIsImportProcessAllowed === true)
        {
            /*
             * Description: Create an istance of the class called DataStorage
             */
            $oDataImport = new DataStorage();
            
            /*
             * Description: Call the startImport function of the DataStorage class
             * Parameters: $iProductId, $sName, $sRelatedProdIds
             * Value type(s): Integer, String, String
             */
            $oDataImport->startImport($iProductId, $sName, $sRelatedProdIds);
        }
        else
        {
            return false;
        }
    }

    public function __destruct()
    {
        
    }
}
?>