/*
 * Description: Database import and export
 * Author: Kovács Norbert
 * Date: 2020/10/13
 * Version: 1.0
*/

******************************
**          Readme          **
******************************

// Database import and export //

Description: 		This software is made for importing data to database via csv or xml file, and
-----------		and also exporting data from the database. Don't forget that if you use it,
                        you always use at your own risk.
					

Part 1 - Requirements
---------------------

Apache latest version
PHP latest version
Oracle MySQL Database server
Oracle MySQL Workbench or phpMyAdmin

Part 2 - Database
-----------------

Import the Databaseimportandexport.sql to the Oracle MySQL Database server


Part 3 - Start the import, or export, or both processes
---------------------------------

1.	Visit the url which is depend on the destination where that file is
2.	Your url should look like this http://127.0.0.1/Databaseimportandexport/ImportAndExporter.php


Part 4 - The CSV importer to database function can be used with the following steps:
-----------------------------------------------------------------------------------

    1. Check that the databaseimportandexport schema created and the products table also

    2. You need to uncomment the number 24th and 31th lines in the ImportAndExporter.php file.
        
        readCSVFile
        -----------
            2.1 First parameter is the path.
            2.2 Second parameter is what mode the file going to be accessed.
            2.3 One CSV file at a time is the recommended, otherwise at your
            own risk.

Part 5 - The Xml importer to database function can be used with the following steps:
-----------------------------------------------------------------------------------

    1. Check that the databaseimportandexport schema created and the products table also

    2. You need to uncomment the number 36th and 43th lines in the ImportAndExporter.php file.
        
        readXmlFile
        -----------
        2.1 First parameter is the variable which the xml string is stored

Part 6 - The exporter from database to html function can be used with the following steps:
-----------------------------------------------------------------------------------

    1. Check that the databaseimportandexport schema created and the products table also

    2. You need to uncomment the number 48th and 53th lines in the ImportAndExporter.php file.


Thank you for using it!

Please tell your thoughts about the Importer / exporter.