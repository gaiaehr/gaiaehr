<?php
//	header("Content-type: text/xml; charset=utf-8");
//print '<pre>';

require_once('rxNormApi.php');

$new = new rxNormApi();
$new->setOutputType('json');
//$new->setOutputType('xml');
//// Example use base on RxNormRest Api Documentation
//// http://rxnav.nlm.nih.gov/RxNormRestAPI.html
//
//echo $new->findRxcuiByString('lipitor');
echo '<pre>';
//print_r(json_decode($new->findRxcuiByID('umlscui','C0487782'), true));
//print_r(json_decode($new->getSpellingSuggestions('tilenal'), true));
//print_r(json_decode($new->getRxConceptProperties('131725'), true));
//print_r(json_decode($new->getRelatedByType('SBD+SBDF','174742'), true));
//print_r(json_decode($new->getAllRelatedInfo('866350'), true));
//print_r(json_decode($new->getDrugs('cymbalta'), true));
//print_r(json_decode($new->getNDCs('213269'), true));
//print_r(json_decode($new->getRxNormVersion(), true));
//print_r(json_decode($new->getIdTypes(), true));
//print_r(json_decode($new->getRelaTypes(), true));
//print_r(json_decode($new->getSourceTypes(), true));
//print_r(json_decode($new->getTermTypes(), true));

//// doesn't properly process source_list variable, hard to test without valid tokens...
//print_r(json_decode($new->getProprietaryInformation('xhruziw05Y','MSH+RXNORM','261455'), true));
//print_r(json_decode($new->getMultiIngredBrand('8896+20610'), true));
//// disabled because its big!
print_r(json_decode($new->getDisplayTerms(),true));
//print_r(json_decode($new->getStrength('315246'), true));
//print_r(json_decode($new->getQuantity('315246'), true));
//print_r(json_decode($new->getUNII('161'), true));
//print_r(json_decode($new->getSplSetId('umlscui','C0487782'), true));
//print_r(json_decode($new->findRemapped('105048'), true));


//// Debug information
//die(print_r($new).print_r(get_object_vars($new)).print_r(get_class_methods(get_class($new))));