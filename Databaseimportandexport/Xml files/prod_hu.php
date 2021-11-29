<?php
$sXmlInput = <<<XML
<?xml version="1.0"?><Products>
    <Product>
        <Id>123</Id>
        <Name>Supersecret component</Name>
        <RelatedProdIds>321,412</RelatedProdIds>
    </Product>
    <Product>
        <Id>321</Id>
        <Name>Something additional</Name>
        <RelatedProdIds></RelatedProdIds>
    </Product>
    <Product>
        <Id>412</Id>
        <Name>Extra part</Name>
        <RelatedProdIds></RelatedProdIds>
    </Product>
</Products>
XML;
?>