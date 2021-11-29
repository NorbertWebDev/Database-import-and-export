<?php
/*
 * Description: Get data from a data storage, which is a database for now
 */
Class DataExporter
{
    /*
     * Description: Create an istance of the class called DataStorage
     */
    private static $oDataExport;
    
    /*
     * Description: Store the name of target data for example GetRelatedProduct
     * Value type(s): String
     */       
    private static $sDataNameTarget;
    
    /*
     * Description: Store the result data from the startExport getProduct
     * Value type(s): Array
     */
    private static $aExportDataGetProducts;

    /*
     * Description: Store the result data from the startExport getRelatedProduct
     * Value type(s): Array
     */
    private static $aExportDataGetRelatedProduct;
    
    /*
     * Description: Store the result data from the startExport
     * Value type(s): Array
     */
    private static $aExportData;

    /*
     * Description: Store the minimum number of rows where from the export process is allowed
     * Value type(s): Array
     */
    private static $aExportDataRowsMinLimit = 0;
    
    /*
     * Description: Store the current row from the result data of startExport
     * Value type(s): Array, Array
     */
    private static $aGetProductsCurrentRow;
    private static $aGetRelatedProductCurrentRow;
    
    /*
     * Description: Store related product ids from the current row from the result data of startExport getProduct
     * Value type(s): Array
     */
    private static $aGetProductsCurrentRelatedIds;
    
    /*
     * Description: Store related product id from the current row from the result data of startExport getProduct
     * Value type(s): Array
     */
    private static $aGetProductsCurrentRelatedId;
    
    /*
     * Description: Store related product ids delimiter for the current row from the result data of startExport getProduct
     * Value type(s): Array
     */
    private static $aGetProductsRelatedIdsDelimiter = ",";
    
    /*
     * Description: Store related product label for the current row from the result data of startExport getProduct
     * Value type(s): String
     */
    private static $sExportProcessRelatedProductsLabel = "Related products";
    
    /*
     * Description: Store messages for the whole export process
     * Value type(s): String
     */
    private static $sExportProcessNoProducts = "Nem találhatóak termékek az adatbázisban.";
    
    /*
     * Description: Store the gathered fully processed data from the whole export process
     * Value type(s): String
     */
    private static $sExportProcessedData;
    
    public function __construct()
    {
        /*
         * Description: Create an istance of the class called DataStorage
         */
        self::$oDataExport = new DataStorage();
    }
    
    /*
     * Description: Get product(s) from the database
    */
    protected function getProduct()
    {
        self::$sDataNameTarget = "GetProducts";
        
        self::$aExportDataGetProducts = null;
        
        /*
         * Description: Call the startExport function of the DataStorage class
         * Parameters: $iProductId, $sDataNameTarget
         * Value type(s): Null, String
         */
        self::$aExportDataGetProducts = self::$oDataExport->startExport(null, self::$sDataNameTarget);
    }
    
    /*
     * Description: Get related product(s) from the database
     * Parameters: $iProductId
     * Value type(s): Integer
    */
    protected function getRelatedProduct($iProductId)
    {
        self::$sDataNameTarget = "GetRelatedProduct";
        
        self::$aExportDataGetRelatedProduct = null;
        
        /*
         * Description: Call the startExport function of the DataStorage class
         * Parameters: $iProductId, $sDataNameTarget
         * Value type(s): Integer, String
         */
        self::$aExportDataGetRelatedProduct = self::$oDataExport->startExport($iProductId, self::$sDataNameTarget);
    }
    
    /*
     * Description: Validate result existence by row number
     * Parameters: $aExportData
     * Value type(s): Array
    */
    protected function getNumberOfRows($aExportData)
    {
        self::$aExportData = null;
        
        self::$aExportData = $aExportData;
        
        if(self::$aExportData)
        {
            if(self::$aExportData->num_rows > self::$aExportDataRowsMinLimit)
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
    
    /*
     * Description: Process and create the Html output from the result of the database
    */
    protected function processExportData()
    {
        $this->getProduct();
        if($this->getNumberOfRows(self::$aExportDataGetProducts) === true)
        {
            self::$sExportProcessedData = null;
            
            $oHtmlTags = new HtmlTags();

            self::$sExportProcessedData .= $oHtmlTags::$sUlOpen;
            
            while (self::$aGetProductsCurrentRow = self::$aExportDataGetProducts->fetch_assoc())
            {
                self::$sExportProcessedData .= $oHtmlTags::$sLiOpen.self::$aGetProductsCurrentRow["Product id"].$oHtmlTags::$sLiClose;

                self::$sExportProcessedData .= $oHtmlTags::$sUlOpen;
                
                self::$sExportProcessedData .= $oHtmlTags::$sLiOpen.self::$aGetProductsCurrentRow["Name"].$oHtmlTags::$sLiClose;
                self::$sExportProcessedData .= $oHtmlTags::$sLiOpen.self::$sExportProcessRelatedProductsLabel.$oHtmlTags::$sLiClose;
                
                self::$aGetProductsCurrentRelatedIds = null;

                self::$aGetProductsCurrentRelatedIds = strval(self::$aGetProductsCurrentRow["Related Product ids"]);
                self::$aGetProductsCurrentRelatedIds = explode(self::$aGetProductsRelatedIdsDelimiter, self::$aGetProductsCurrentRelatedIds);

                if(count(self::$aGetProductsCurrentRelatedIds) > self::$aExportDataRowsMinLimit)
                {
                    self::$aGetProductsCurrentRelatedId = null;

                    self::$sExportProcessedData .= $oHtmlTags::$sUlOpen;
                    
                    foreach (self::$aGetProductsCurrentRelatedIds as self::$aGetProductsCurrentRelatedId)
                    {
                        $this->getRelatedProduct(self::$aGetProductsCurrentRelatedId);
                        
                        if($this->getNumberOfRows(self::$aExportDataGetRelatedProduct) === true)
                        {
                            while (self::$aGetRelatedProductCurrentRow = self::$aExportDataGetRelatedProduct->fetch_assoc())
                            {
                                self::$sExportProcessedData .= $oHtmlTags::$sLiOpen.self::$aGetRelatedProductCurrentRow["Name"].$oHtmlTags::$sLiClose;
                                
                                self::$oDataExport->nextResult();
                            }
                        }
                    }
                    
                    self::$sExportProcessedData .= $oHtmlTags::$sUlClose;
                }
                
                self::$sExportProcessedData .= $oHtmlTags::$sUlClose;
            }
            
            self::$sExportProcessedData .= $oHtmlTags::$sUlClose;
            
            error_log(self::$sExportProcessedData, 0);
            
            return self::$sExportProcessedData;
        }
        else
        {
            error_log(self::$sExportProcessNoProducts, 0);
            
            return false;
        }
    }
    
    /*
     * Description: Start the whole export process
    */
    public function getExportData()
    {
        $this->processExportData();
    }
    
    public function __destruct()
    {}
}
?>