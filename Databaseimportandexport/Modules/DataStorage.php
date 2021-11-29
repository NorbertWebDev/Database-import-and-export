<?php
/*
 * Description: Store data to a data storage, which is a database for now
 */
Class DataStorage
{
    /*
     * Description: Connection parameters to the data storage
     * Value type(s): String
     */
    private static $sDataHostName = "127.0.0.1";
    private static $sDataHostUsername = "root";
    private static $sDataHostUserPassword = "6Ma788312";
    private static $sDataHostDatabaseName = "databaseimportandexport";
    private static $sDataHostPortNumber = '3306';

    /*
     * Description: Store the MySql object for database actions
     * Value type(s): Object
     */
    private static $oDataStorage;

    /*
     * Description: Store the result of the get type SQL query string
     * Value type(s): Null or String
     */
    private static $vGetDSSQLResult;
    
    /*
     * Description: Store the get products SQL query string
     * Value type(s): Null or String
     */
    private static $vGetProductsDSSQL;
    
    /*
     * Description: Store the get related product SQL query string
     * Value type(s): Null or String
     */
    private static $vGetRelatedProductDSSQL;
    
    /*
     * Description: Store the insert SQL query string
     * Value type(s): Null or String
     */
    private static $vInsertDSSQL;
    
    /*
     * Description: Store the SQL query string what will be run
     * Value type(s): String
     */    
    private $sDSSQL;
    
    /*
     * Description: Store the SQL query DDL or DML type by HTTP or HTTPS method
     * Value type(s): String
     */       
    private $sDSSQLType;

    /*
     * Description: Store the SQL query DDL or DML type by HTTP or HTTPS method
     * Value type(s): String
     */       
    private static $sDSSQLGetType = "GET";
    private static $sDSSQLInsertType = "PUT"; 
    
    /*
     * Description: Store the target data name for example GetProducts
     * Value type(s): String
     */       
    private static $sDataName;
    
    /*
     * Description: Store the target data name for example GetProducts
     * Value type(s): Mixed
     */ 
    private static $vGetSQLQueryResult;
    
    /*
     * Description: Store messages for the DataStorage class
     * Value type(s): String, String, String
     */
    private static $sCSVImportDataConnectError = "Nem sikerült csatlakozni az adatbázishoz!";
    private static $sCSVGetDataSuccess = "Sikeres adat lekérdezés az adatbázisból.";
    private static $sCSVGetDataProcessTerminated = "Adat lekérdezési folyamat leállt.";
    private static $sCSVImportDataSuccess = "Sikeres importálás az adatbázisba.";
    private static $sCSVImportDataProcessTerminated = "Importálási folyamat leállt.";
    
    public function __construct()
    {
        /*
         * Description: Connect to the data storage
         * Parameters: $sDataHostName, $sDataHostUsername, $sDataHostUserPassword, $sDataHostDatabaseName, $sDataHostPortNumber
         * Value type(s): String
        */
        self::$oDataStorage = new mysqli(self::$sDataHostName, self::$sDataHostUsername, self::$sDataHostUserPassword, self::$sDataHostDatabaseName, self::$sDataHostPortNumber);
    }
    
    /*
     * Description: Check that the connection is made
     */
    protected function checkConnection()
    {
        /*
         * Description: Do the step(s) when a connection established or not
         * Parameters: $oDataStorage
         * Value type(s): Object
        */
        if(self::$oDataStorage->connect_error)
        {
            error_log(self::$sCSVImportDataConnectError, 0);
            
            return false;
        }
        else 
        {
            return true;
        }
    }
    
    /*
     * Description: Check and prepare the values for the insert SQL query string
     * Parameters: $sValueToCheck
     * Value type(s): String
     */
    protected function checkSQLValues($sValueToCheck)
    {
        // If it has no value then make it null, but when the opposite the value must be properly prepared for SQL query
        $sValueToCheck = isset($sValueToCheck) ? "'".self::$oDataStorage->real_escape_string(utf8_encode($sValueToCheck))."'" : "NULL";
        
        return $sValueToCheck;
    }
    
    /*
     * Description: Make the get products SQL query string
     */
    protected function makeGetProductsSQLQuery()
    {   
        // Set the default value
        self::$vGetProductsDSSQL = null;
        
        /*
         * Description: Start to make the SQL query string
         */
        self::$vGetProductsDSSQL = "SELECT * FROM databaseimportandexport.get_products";
    }

    /*
     * Description: Make the get related product SQL query string
     * Parameters: $iProductId
     * Value type(s): Integer
     */
    protected function makeGetRelatedProductSQLQuery($iProductId)
    {   
        // Set the default value
        self::$vGetRelatedProductDSSQL = null;
        
        /*
         * Description: Start to make the SQL query string
         * Parameters: $iProductId, $sName, $sRelatedProdIds
         * Value type(s): Integer, String, String
         */
        self::$vGetRelatedProductDSSQL = "call databaseimportandexport.get_related_product";
        
        self::$vGetRelatedProductDSSQL .= "(".$this->checkSQLValues($iProductId);
        self::$vGetRelatedProductDSSQL .= ")";
    }    
    
    /*
     * Description: Make the insert product SQL query string
     * Parameters: $iProductId, $sName, $sRelatedProdIds
     * Value type(s): Integer, String, String
     */
    protected function makeInsertProductSQLQuery($iProductId, $sName, $sRelatedProdIds)
    {   
        // Set the default value
        self::$vInsertDSSQL = null;
        
        /*
         * Description: Start to make the SQL query string
         * Parameters: $iProductId, $sName, $sRelatedProdIds
         * Value type(s): Integer, String, String
         */
        self::$vInsertDSSQL = "call databaseimportandexport.insert_product";
        
        self::$vInsertDSSQL .= "(".$this->checkSQLValues($iProductId).",".$this->checkSQLValues($sName).",";
        self::$vInsertDSSQL .= $this->checkSQLValues($sRelatedProdIds);
        self::$vInsertDSSQL .= ")";
    }
    
    /*
     * Description: Run the SQL query string
     * Parameters: $sDSSQL, $sDSSQLType
     * Value type(s): String, String
     */
    protected function runSQLQuery($sDSSQL, $sDSSQLType)
    {
        /*
         * Description: Do the step(s) after the query is executed
         * Parameters: $oDataStorage, $vInsertDSSQL
         * Value type(s): Object, Null or String
         */
        if(is_string($sDSSQL) === TRUE)
        {
            if($sDSSQLType === self::$sDSSQLGetType)
            {
                self::$vGetDSSQLResult = self::$oDataStorage->query($sDSSQL, MYSQLI_STORE_RESULT);
                
                error_log(self::$sCSVGetDataSuccess, 0);
                
                return self::$vGetDSSQLResult;
            }
            else if($sDSSQLType === self::$sDSSQLInsertType)
            {
                /*
                 * Description: Do the step(s) after the query is executed
                 * Parameters: $oDataStorage, $vInsertDSSQL
                 * Value type(s): Object, Null or String
                 */
               if(self::$oDataStorage->query($sDSSQL) === TRUE)
               {
                    error_log(self::$sCSVImportDataSuccess, 0);
                
                    return true;
               }
               else
               {
                    error_log("Hiba: " . $sDSSQL . "<br>" . self::$oDataStorage->error, 0);

                    return false;
               }
            }
        }
        else
        {
            error_log("Hiba: " . $sDSSQL . "<br>" . self::$oDataStorage->error, 0);
            
            return false;
        }
    }
    
    /*
     * Description: Start to run the whole import process
     * Parameters: $iProductId, $sName, $sRelatedProdIds
     * Value type(s): Integer, String, String
     */
    public function startImport($iProductId, $sName, $sRelatedProdIds)
    {
        /*
         * Description: Do the step(s) when a connection established or not
        */
        if($this->checkConnection() === true)
        {
            /*
             * Description: Call the SQL query string maker
             * Parameters: $iProductId, $sName, $sRelatedProdIds
             * Value type(s): Integer, String, String
             */
            $this->makeInsertProductSQLQuery($iProductId, $sName, $sRelatedProdIds);
            
            // Description: Run the SQL query string
            $this->runSQLQuery(self::$vInsertDSSQL, self::$sDSSQLInsertType);
        }
        else
        {
            error_log(self::$sCSVImportDataProcessTerminated, 0);
        }
    }
    
    /*
     * Description: Start to run the whole export process
     * Parameters: $iProductId, $sDataName
     * Value type(s): Integer, String
     */
    public function startExport($iProductId, $sDataName)
    {
        self::$sDataName = null;
        self::$vGetSQLQueryResult = null;
        
        /*
         * Description: Do the step(s) when a connection established or not
         */
        if($this->checkConnection() === true)
        {
            self::$sDataName = $sDataName;
            
            if(is_string(self::$sDataName) === true)
            {
                if(self::$sDataName === "GetProducts")
                {
                    /*
                     * Description: Call the get product SQL query string maker
                     */
                    $this->makeGetProductsSQLQuery();

                    /* Description: Run the SQL query string
                     * Parameters: $vGetSQLQueryResult, $vGetProductsDSSQL, $sDSSQLGetType
                     * Value type(s): Mixed, Mixed, String
                    */
                    self::$vGetSQLQueryResult = $this->runSQLQuery(self::$vGetProductsDSSQL, self::$sDSSQLGetType);

                    return self::$vGetSQLQueryResult;
                }
                else if(self::$sDataName === "GetRelatedProduct")
                {
                    /*
                     * Description: Call the get related product SQL query string maker
                     * Parameters: $iProductId
                     * Value type(s): Integer
                     */
                    $this->makeGetRelatedProductSQLQuery($iProductId);
                    
                    /* Description: Run the SQL query string
                     * Parameters: $vGetSQLQueryResult, $vGetRelatedProductDSSQL, $sDSSQLGetType
                     * Value type(s): Mixed, Mixed, String
                    */
                    self::$vGetSQLQueryResult = $this->runSQLQuery(self::$vGetRelatedProductDSSQL, self::$sDSSQLGetType);
                    
                    return self::$vGetSQLQueryResult;
                }
                else
                {
                    error_log(self::$sCSVGetDataProcessTerminated, 0);
                
                    return false;
                }
            }
            else
            {
                error_log(self::$sCSVGetDataProcessTerminated, 0);
                
                return false;
            }
        }
        else
        {
            error_log(self::$sCSVGetDataProcessTerminated, 0);
        }
    }
    
    /*
     * Description: Call the next result from the data result
     */
    public function nextResult()
    {
        self::$oDataStorage->next_result();
    }
    
    public function __destruct() 
    {
    }
}
?>