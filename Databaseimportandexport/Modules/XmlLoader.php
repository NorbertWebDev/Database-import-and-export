<?php
/*
 * Description: Load the xml file and process
 */
class XmlLoader
{
    /*
     * Description: Store the content of the xml file
     * Value type(s): Object
     */
    protected $oXmlFile = null;
    
    /*
     * Description: Store the current row's index of the xml file
     * Value type(s): Integer
     */
    protected static $iXmlFileCurrentRowIndex = 0;
    
    /*
     * Description: Store the current node's content of the xml file
     * Value type(s): Object
     */
    protected $oProduct = null;
    
    /*
     * Description: Store messages for the XmlLoader class
     * Value type(s): String, String
     */
    private static $sXmlImportFinished = "xml importálás befejeződött.";
    private static $sXmlImportLoadError = "Probléma merült fel az xml betöltése során.";
    
    public function __construct() 
    {
    }
	
    /*
     * Description: This function loads the xml file, and run the validations. Then the valid data goes to the database
     * Parameters: $sXmlFileContent
     * Value type(s): String
     */
    public function readXmlFile($sXmlFileContent)
    {
        /*
         * Description: Check that the xml file can be opened
         * Parameters: $sCSVFileName
         * Value type(s): String
        */
        if(is_null($sXmlFileContent) === false)
        {
                /*
                 * Description: Create an istance of the class called checkCSVContentValidations
                 */
                $this->checkCSVContentValidations = new checkCSVContentValidations();
                
                /*
                 * Description: Load xml data from a string variable
                 * Parameters: $sXmlFileContent
                 * Value type(s): String
                 */
                $this->oXmlFile = simplexml_load_string($sXmlFileContent);
                
                /*
                 * Description: Loop through the loaded xml file one object after another
                 * Parameters: $oXmlFile, $oProduct
                 * Value type(s): Object, Object
                 */
                foreach($this->oXmlFile->Product as $oProduct)
                {
                    /*
                     * Description: Call the validateCSVContent function of the checkCSVContentValidations class
                     * Parameters: $iProductId, $sName, $sRelatedProdIds
                     * Value type(s): Integer, String, String
                     */
                    $this->checkCSVContentValidations->validateCSVContent(intval($oProduct->Id), strval($oProduct->Name), strval($oProduct->RelatedProdIds));
						
                    self::$iXmlFileCurrentRowIndex++;
                }
                error_log(self::$sXmlImportFinished, 0);
        }
        else
        {
            error_log(self::$sXmlImportLoadError, 0);
        }
    }
    
    public function __destruct()
    {
    }
}
?>