<?php
/*
 * Title: Database import and export
 * Description: Import, or export, or both to database or from it
 * Author: Kovács Norbert
 * Created on: 2020-10-13
 * Version: 1.0
 */

/*
 * Description: Load all the files what's needed for import,or export, or both
 */
require_once 'Xml files/prod_hu.php';
require_once 'Modules/CSVLoader.php';
require_once 'Modules/CheckCSVContentValidations.php';
require_once 'Modules/DataExporter.php';
require_once 'Modules/DataStorage.php';
require_once 'Modules/HtmlTags.php';
require_once 'Modules/XmlLoader.php';

/*
 * Description: Create an istance of the class called CSVLoader
 */
//$oCSVLoad = new CSVLoader();

/*
 * Description: Run the CSV importer process
 * Parameters: File name with path, Mode of openning the CSV file
 * Value type(s): String, String
 */
//$oCSVLoad->readCSVFile('CSV files/prod_hu.csv', 'r');

/*
 * Description: Create an istance of the class called XmlLoader
 */
//$oXmlLoad = new XmlLoader();

/*
 * Description: Run the Xml importer process
 * Parameters: Variable with the Xml string
 * Value type(s): String
 */
//$oXmlLoad->readXmlFile($sXmlInput);

/*
 * Description: Create an istance of the class called DataExporter
 */
//$oDataExporter = new DataExporter();

/*
 * Description: Run the export data process
 */
//$oDataExporter->getExportData();
?>