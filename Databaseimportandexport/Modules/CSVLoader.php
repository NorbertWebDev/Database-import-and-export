<?php
/*
 * Description: Load the CSV file and process
 */
class CSVLoader
{
    /*
     * Description: Store the content of the CSV file
     * Value type(s): Object
     */
    protected $oCSVFile = null;
    
    /*
     * Description: Store the needed delimiter variations depend on the country
     * Value type(s): Array
     */
    protected $aCountryCodes = array("BG"=>"\t", "HU"=>";", "RO"=>"\t");
    
    /*
     * Description: Store the country code from the aCountryCodes array
     * Value type(s): String
     */
    protected $sCSVFileCountryCode = null;
    
    /*
     * Description: Store max line length and the delimiter for the loaded CSV file
     * Value type(s): Integer, String
     */
    protected $iLoadedCSVFileLineLength = 0;
    protected $sLoadedCSVFileDelimiter = null;
    
    /*
     * Description: Store the current row's index of the CSV file
     * Value type(s): Integer
     */
    protected static $iCSVFileCurrentRowIndex = 0;
    
    /*
     * Description: Store the current row of the CSV file and the array's length which holds the current row data
     * Value type(s): Array, Integer
     */
    protected $aCSVFileLine = array();
    protected $iCSVFileLineLength = 0;
   
    /*
     * Description: Store the header row's index of the CSV file
     * Value type(s): Integer
     */
    protected static $iCSVFileHeaderRowIndex = 0;
    
    /*
     * Description: Store optional Item code variables of the CSV file
     * Value type(s): Integer
     */
    protected $iItemCode = null;
    protected static $iItemCodeIndex = 8;
    
    /*
     * Description: Store an instance of the checkCSVContentValidations class
     * Value type(s): Object
     */
    protected $oCheckCSVContentValidations = null;
    
    /*
     * Description: Store messages for the CSVLoader class
     * Value type(s): String, String, String
     */
    private static $sCSVImportFinished = "CSV importálás befejeződött.";
    private static $sCSVImportWrongDelimiter = "Érvénytelen, vagy nem ismert CSV fájl határoló.";
    private static $sCSVImportLoadError = "Probléma merült fel a CSV fájl betöltése során.";
    
    public function __construct() 
    {}
    
    /*
     * Description: This function loads the CSV file, and run the validations. Then the valid data goes to the database
     * Parameters: $sCSVFileName
     * Value type(s): String
     */
    protected function checkCSVFileDelimiter($sCSVFileName)
    {
        // Get the partner's name from the CSV's file name
        $this->sCSVFileCountryCode = $sCSVFileName;
        $this->sCSVFileCountryCode = substr($this->sCSVFileCountryCode, -6, 2);

        // Loop through the allowed country codes(partner names) and check that is allowed or not
        foreach($this->aCountryCodes as $sCountryCodeName => $sCountryCodeDelimiter)
        {
            /*
             * Description: Check that the delimiter is allowed or not which is used depend on country
             *              and the related delimiter in the CSV file
             * Parameters: $sCSVFileCountryCode, $sCountryCodeName
             * Value type(s): String, String
             */
            if(strtoupper($this->sCSVFileCountryCode) === $sCountryCodeName)
            {
                $this->sLoadedCSVFileDelimiter = $sCountryCodeDelimiter;
                
                return true;
            }
        }
        return false;
    }

    /*
     * Description: This function loads the CSV file, and run the validations. Then the valid data goes to the database
     * Parameters: $sCSVFileName, $sCSVFileMethod
     * Value type(s): String, String
     */
    public function readCSVFile($sCSVFileName,$sCSVFileMethod)
    {
        /*
         * Description: Check that the CSV file can be opened
         * Parameters: $sCSVFileName, $sCSVFileMethod
         * Value type(s): String, String
        */
        if(is_readable($sCSVFileName) === true)
        {
            /*
             * Description: Check that the CSV file contains an allowed delimiter or not
             * Parameters: $sCSVFileName
             * Value type(s): String
             */
            if($this->checkCSVFileDelimiter($sCSVFileName) === true)
            {
                /*
                 * Description: Create an istance of the class called checkCSVContentValidations
                 */
                $this->oCheckCSVContentValidations = new checkCSVContentValidations();
                
                /*
                 * Description: Create an istance of the class called SplFileObject
                 * Parameters: $sCSVFileName, $sCSVFileMethod
                 * Value type(s): String, String
                 */
                $this->oCSVFile = new SplFileObject($sCSVFileName, $sCSVFileMethod);
                
                /*
                 * Description: Set the flags for the istance of the class called SplFileObject
                 * Parameters: READ_CSV, SKIP_EMPTY, DROP_NEW_LINE
                 * Value type(s): Param, Param, Param
                 */
                $this->oCSVFile->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
                
                /*
                 * Description: Loop through the loaded CSV file one line after another
                 * Parameters: $oCSVFile, $iLoadedCSVFileLineLength, $sLoadedCSVFileDelimiter
                 * Value type(s): Object, Integer, String
                 */
                while($this->oCSVFile->fgetcsv($this->sLoadedCSVFileDelimiter))
                {
                    // Load the current row from the CSV file 
                    $this->aCSVFileLine = $this->oCSVFile->current();
                    
                    // Get the number of items in the current row of the CSV file
                    $this->iCSVFileLineLength = count($this->aCSVFileLine);
                    
                    // Leave out the header row of the CSV file
                    if(self::$iCSVFileCurrentRowIndex > self::$iCSVFileHeaderRowIndex)
                    {
                        /*
                         * Description: Call the validateCSVContent function of the checkCSVContentValidations class
                         * Parameters: $iProductId, $sName, $sRelatedProdIds
                         * Value type(s): Integer, String, String
                         */
                        $this->oCheckCSVContentValidations->validateCSVContent($this->aCSVFileLine['0'], $this->aCSVFileLine['1'], $this->aCSVFileLine['2']);

                        // Clear the data what is not needed anymore
                        unset($this->aCSVFileLine);
                    }
                    self::$iCSVFileCurrentRowIndex++;
                }
                error_log(self::$sCSVImportFinished, 0);
            }
            else
            {
                error_log(self::$sCSVImportWrongDelimiter, 0);
            }
        }
        else
        {
            error_log(self::$sCSVImportLoadError, 0);
        }
    }
    
    public function __destruct()
    {
    }
}
?>