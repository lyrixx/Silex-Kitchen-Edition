<?php
$url = 'http://www.mooseracing.com/products?productGroupId=73591&productId=144482';

$urlQueryStr = parse_url($url, PHP_URL_QUERY);
parse_str($urlQueryStr , $queryStr);

$xpath = xDoc($url);
$products = array();
$name = $xpath->query("//div[@id='productDetailsWrap']/h2");
$products['name'] = $name->item(0)->nodeValue;
$products['url'] = $url;
$products['product_id'] = $queryStr['productId'];

$partNumbers = array();
foreach( $xpath->query("//div[@id='partnumbers']/td[@class='partNumbers']") as $node)
{
$partNumbers[] = array($products['product_id'], $node->item(0)->nodeValue);
}
_d($partNumbers, '$partNumbers');
$products['part_numbers'] = $partNumbers;

$vehicles = array();
foreach( $xpath->query("//div[@id='fitment']/tr") as $node)
{
$vehicles[] = array(
'part_number'=> $node->getElementsByTagName('td')->item(4)->nodeValue,
'make'=> $node->getElementsByTagName('td')->item(1)->nodeValue,
);
}

$products['vehicles'] = $vehicles;

_d($products, '$products');


function _d($var, $test='Test')
{
echo "<br />$test: <pre>";
    print_r($var);
    echo '</pre>';
}

function xDoc($url)
{
$html = new DOMDocument();
$oldSetting = libxml_use_internal_errors( true );
libxml_clear_errors();
$html->loadHTMLFile($url);
$xpath = new DOMXPath($html);

return $xpath;
}