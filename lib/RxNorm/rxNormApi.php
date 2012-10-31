<?php
require_once('APIBaseClass.php');
// SOAP/REST ... implementing REST with SOAP style method selection. Enjoy! Only supports XML for now.
// use _apiHelper($query path) instead of _request  for quick json support.
class rxNormApi extends APIBaseClass
{
	// would like to some day implement this in SOAP...
	public static $api_url = 'http://rxnav.nlm.nih.gov/REST';

	public $sourceTypes = Array(
		'GS', 'MDDB', 'MMSL', 'MMX', 'MSH', 'MTHFDA', 'MTHSPL', 'NDDF', 'NDFRT', 'RXNORM', 'SNOMEDCT', 'VANDF',
	);

	public $idTypes = Array(
		'AMPID', 'GCN_SEQNO', 'GFC', 'GPPC', 'HIC_SEQN', 'LISTING_SEQ_NO', 'MESH', 'MMSL_CODE', 'NDC', 'NUI', 'SNOMEDCT', 'SPL_SET_ID', 'UMLSCUI', 'UNII_CODE', 'VUID'
	);

	public $relaTypes = Array(
		'consists_of', 'constitutes', 'contained_in', 'contains', 'dose_form_of', 'form_of', 'has_dose_form', 'has_form', 'has_ingredient', 'has_ingredients', 'has_part', 'has_precise_ingredient', 'has_quantified_form', 'has_tradename', 'ingredient_of', 'ingredients_of', 'inverse_isa', 'isa', 'part_of', 'precise_ingredient_of', 'quantified_form_of', 'reformulated_to', 'reformulation_of', 'tradename_of'
	);

	public function __construct($url = null)
	{
		$this->output_type = 'xml';
		parent::new_request(($url ? $url : self::$api_url));
		//	self::discover_api();
	}

	function discover_api()
	{
		// designed to run the built in api functions (if the exist) to get valid values for some api method calls
		$idTypes       = new SimpleXMLElement($this->getIdTypes());
		$this->idTypes = xml_to_array($idTypes, 'idTypeList', 'idName');
		$relaTypes       = new SimpleXMLElement($this->getRelaTypes());
		$this->relaTypes = xml_to_array($relaTypes, 'relationTypeList', 'relationType');
		$sourceTypes       = new SimpleXMLElement($this->getSourceTypes());
		$this->sourceTypes = xml_to_array($sourceTypes, 'sourceTypeList', 'sourceName');

	}

	public function setOutputType($type)
	{
		if($type != $this->output_type) $this->output_type = ($type != 'xml' ? 'json' : 'xml');
	}

	public function setRxcui($rxcui)
	{
		$this->rxcui = $rxcui;
	}

	public function _request($path, $method, $data = null)
	{
		if($this->output_type == 'json') {
			return parent::_request($path, $method, $data, "Accept:application/json");
		} else {
			return parent::_request($path, $method, $data, "Accept:application/xml");
		}
	}

	public function getRxcui($rxcui = null)
	{
		if($rxcui != null) return $rxcui; elseif($this->rxcui) return $this->rxcui;
	}

	public function findRxcuiByString($name, $searchString = null, $searchType = null, $source_list = null, $allSourcesFlag = null)
	{
		$data['name'] = $name;
		if($allSourcesFlag = !null){
			$data['allsrc'] = $allSourcesFlag;
			if($source_list != null && $allSourcesFlag == 1) $data['srclist'] = $source_list;
		}
		if($searchString != null) $data['search'] = $searchString;
		return self::_request("/rxcui", 'GET', $data);

	}

	public function findRxcuiByID($idType, $id, $allSourcesFlag = null)
	{
		$data['idtype'] = $idType;
		$data['id']     = $id;
		if($allSourcesFlag != null) $data['allsrc'] = $allSourcesFlag;
		return self::_request("/rxcui?idtype=$idType&id=$id&allsrc=$allSourcesFlag", 'GET');
	}

	public function getSpellingSuggestions($searchString)
	{
		return self::_request("/spellingsuggestions?name=$searchString", 'GET');
	}

	public function getRxConceptProperties($rxcui = null)
	{
		$rxcui = self::getRxcui($rxcui);
		return self::_request('/rxcui/' . $rxcui . '/properties', 'GET');
	}

	public function getRelatedByRelationship($relationship_list, $rxcui = null)
	{
		$rxcui = self::getRxcui($rxcui);
		return self::_request("/rxcui/$rxcui/related?rela=$relationship_list", 'GET');
	}

	public function getRelatedByType($type_list, $rxcui = null)
	{
		$rxcui = self::getRxcui($rxcui);
		return self::_request("/rxcui/$rxcui/related?tty=$type_list", 'GET');
	}

	public function getAllRelatedInfo($rxcui = null)
	{
		$rxcui = self::getRxcui($rxcui);
		return self::_request("/rxcui/$rxcui/allrelated", 'GET');
	}

	public function getDrugs($name)
	{
		return self::_request("/drugs?name=$name", 'GET');
	}

	public function getNDCs($rxcui = null)
	{
		$rxcui = self::getRxcui($rxcui);
		return self::_request("/rxcui/$rxcui/ndcs", 'GET');
	}

	public function getRxNormVersion()
	{
		return self::_request('/version', 'GET');
	}

	public function getIdTypes()
	{
		return self::_request('/idtypes', 'GET');
	}

	public function getRelaTypes()
	{
		return self::_request('/relatypes', 'GET');
	}

	public function getSourceTypes()
	{
		return self::_request("/sourcetypes", 'GET');
	}

	public function getTermTypes()
	{
		return self::_request("/termtypes", 'GET');
	}

	public function getProprietaryInformation($proxyTicket, $source_list = null, $rxcui = null)
	{
		$rxcui          = self::getRxcui($rxcui);
		$data['ticket'] = $proxyTicket;
		if($source_list != null) $data['srclist'] = $source_list;
		return self::_request("/rxcui/$rxcui/proprietary", 'GET', $data);
	}

	public function getMultiIngredBrand($rxcui_list)
	{
		return self::_request("/brands?ingredientids=$rxcui_list", 'GET');
	}

	// assume this is get display names too ??
	public function getDisplayTerms()
	{
		return self::_request("/displaynames", 'GET');
	}

	public function getStrength($rxcui = null)
	{
		$rxcui = self::getRxcui($rxcui);
		return self::_request("/rxcui/$rxcui/strength", 'GET');
	}

	public function getQuantity($rxcui = null)
	{
		$rxcui = self::getRxcui($rxcui);
		return self::_request("/rxcui/$rxcui/quantity", 'GET');
	}

	public function getUNII($rxcui = null)
	{
		$rxcui = self::getRxcui($rxcui);
		return self::_request("/rxcui/$rxcui/unii", 'GET');
	}

	public function getSplSetId($idType, $id, $allsrc = null)
	{
		$data['idtype'] = $idType;
		$data['id']     = $id;
		if($allsrc != null) $data['allsrc'] = $allsrc;
		return self::_request("/rxcui", 'GET', $data);
	}

	public function findRemapped($rxcui = null)
	{
		return self::_request("/remap/$rxcui", 'GET');
	}

}