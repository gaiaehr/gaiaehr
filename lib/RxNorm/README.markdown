Overview
========
RxNorm API PHP Library in REST with SOAP style methods

A Semantic Navigation Tool for Clinical Drugs 

http://rxnav.nlm.nih.gov/ 
Usage
=====
<pre>
// Base API Class

require 'APIBaseClass.php';

require 'rxNormApi.php';

$new = new rxNormApi();

// Example use basd on RxNormRest Api Documentation 
// http://rxnav.nlm.nih.gov/RxNormRestAPI.html

echo $new->findRxcuiByString('lipitor');

echo $new->findRxcuiByID('umlscui','C0487782');

echo $new->getSpellingSuggestions('ambienn');

echo $new->getRxConceptProperties('131725');

// Example of setting json output
$new->setOutputType('json');

echo $new->getRelatedByType('SBD+SBDF','174742');

echo $new->getAllRelatedInfo('866350');

echo $new->getDrugs('cymbalta');

echo $new->getNDCs('213269');
// Example of changing output     
$new->setOutputType('xml');

echo $new->getRxNormVersion();

echo $new->getIdTypes();

echo $new->getRelaTypes();

echo $new->getSourceTypes();

echo $new->getTermTypes();

// doesn't properly process source_list variable, hard to test without valid tokens...
echo $new->getProprietaryInformation('xhruziw05Y','MSH+RXNORM','261455');

echo $new->getMultiIngredBrand('8896+20610');

// disabled because its big!
//echo $new->getDisplayTerms();

echo $new->getStrength('315246');

echo $new->getQuantity('207716');

echo $new->getUNII('161');

echo $new->getSplSetId('umlscui','C0487782');

echo $new->findRemapped('105048');

// Debug information
die(print_r($new).print_r(get_object_vars($new)).print_r(get_class_methods(get_class($new))));

</pre>