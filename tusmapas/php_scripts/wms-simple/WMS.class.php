<?php


require_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/".'MapUtils.class.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/".'user_exceptions/NotWmsException.class.php';
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/NotAuthorizedException.class.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/".'user_exceptions/WmsWithoutBBoxException.class.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/".'user_exceptions/XmlException.class.php';
//revisar mapbender/http//classes
class WMS {

	protected $timeOut = 100;

	private $httpType = "get";
	private $httpVersion = "1.0";
	private $httpPostData;
	private $httpContentType;
	private $httpPostFieldsNumber;

	protected $file;
	
	
	
	protected $url;
	
	public function getUrl(){
		return $this->url;
	}
	
	
	protected $friendlyUrl = null;
	
	public function getFriendlyUrl(){
		if($this->friendlyUrl == null){
//			$this->friendlyUrl = MapUtils::singleton()->toPrettyUrl($this->wms_title);
			$this->friendlyUrl = Util::text2url($this->wms_title);
		}
		return $this->friendlyUrl;
	}
	
	/*
	 * WMS Capabilities
	 * */
	protected $wms_getcapabilities_doc;
	
	public function getWmsCapabilitiesDoc(){
		return $this->wms_getcapabilities_doc;
	}
	
	
	protected $wms_getcapabilities;
	
	public function getWmsCapabilities(){
		return $this->wms_getcapabilities;
	}
	
	
	protected $wms_upload_url;

	public function getWmsUploadUrl(){
		return $this->wms_upload_url;
	}
	
	
	
	protected $wms_version;
	
	public function getWmsVersion(){
		return $this->wms_version;
	}
	
	
	protected $wms_name;
	
	public function getWmsName(){
		return $this->wms_name;
	}
	
	
	protected $wms_title;
	
	public function getWmsTitle(){
		return $this->wms_title;
	}
	
	protected $wms_abstract;
	
	public function getWmsAbstract(){
		return $this->wms_abstract;
	}
	

	protected $fees;
	
	public function getFees(){
		return $this->fees;
	}


	protected $accessconstraints;

	public function getAccessConstraints(){
		return $this->accessconstraints;
	}
	
	
	

	/*
	 * contact data (person and addresses)
	 * */
	protected $contactperson;
	
	public function getContactPerson(){
		return $this->contactperson;
	}
	
	
	protected $contactposition;
	
	public function getContactPosition(){
		return $this->contactposition;
	}
	
	protected $contactorganization;
	
	public function getContactOrganization(){
		return $this->contactorganization;
	}
	
	
	protected $address;
	public function getAddress(){
		return $this->address;
	}
	
	protected $city;

	public function getCity(){
		return $this->city;
	}
	protected $stateorprovince;

	public function getStateOrProvince(){
		return $this->stateorprovince;
	}
	
	
	protected $postcode;
	
	public function getPostCode(){
		return $this->postcode;
	}
	
	
	protected $country;
	
	public function getCountry(){
		return $this->country;
	}
	
	
	protected $contactvoicetelephone;
	
	public function getTelephone(){
		return $this->contactvoicetelephone;
	}
	
	protected $contactfacsimiletelephone;
	
	public function getFax(){
		return $this->contactfacsimiletelephone;
	}
	
	protected $contactelectronicmailaddress;
	
	public function getMail(){
		return $this->contactelectronicmailaddress;
	}
	

/*
 *
 * getmap section
 */
	protected $wms_getmap;
	
	public function getWmsGetMap(){
		return $this->wms_getmap;
	}
	
	
	protected $wms_getfeatureinfo;
	
	public function getWmsGetFeatureInfo(){
		return $this->wms_getfeatureinfo;
	}
	protected $wms_getlegendurl;
	public function getWmsGetLegendUrl(){
		return $this->wms_getlegendurl;
	}
	
	protected $wms_supportsld;
	
	public function supportSld(){
		return $this->wms_supportsld;
	}
	
	protected $wms_userlayer;
	
	public function getWmsUserLayer(){
		return $this->wms_userlayer;
	}
	
	protected $wms_userstyle;
	
	public function getWmsUserStyle(){
		return $this->wms_userstyle;
	}
	
	protected $wms_remotewfs;
	
	public function getWmsRemoteWfs(){
		return $this->wms_remotewfs;
	}
	

	protected $default_epsg;
	
	public function getDefaultEpsg(){
		return $this->default_epsg;
	}
	
	protected $wms_status;
	
	public function getWmsStatus(){
		return $this->wms_status;
	}
	
	
	
	protected $wms_keyword = array();
	
	public function getKeywords(){
		return $this->wms_keyword;
	}
	
	public function getKeywordsAsText(){
		
		$solution = "";
		$numKeywords = sizeof($this->wms_keyword);
		
		if($numKeywords > 0){
			for($i = 0; $i < $numKeywords - 1; $i++){
				$solution .= $this->wms_keyword[$i] .= ",";
			}
			$solution .= $this->wms_keyword[$numKeywords - 1];
		}
		
		return $solution;
	}
	
	
	
	protected $data_type = array();
	
	public function getDataType(){
		return $this->data_type;
	}
	
	protected $data_format = array();
	
	public function getDataFormat(){
		return $this->data_format;
	}

	protected $objLayer = array();
	
	public function getLayers(){
		return $this->objLayer;
	}
	
	public function getLayerNames(){
		$solution = "";
		$numLyrs = sizeof($this->objLayer);
		
		if($numLyrs > 0){
			for($i = 0; $i < $numLyrs - 1; $i++){
				$solution .= ($this->objLayer[$i]->layer_name . ";");
			}
			$solution .= $this->objLayer[$i]->layer_name;
		}
		return $solution;
	}
	
	public function getLayerTitles(){
		$solution = "";
		$numLyrs = sizeof($this->objLayer);
		
		if($numLyrs > 0){
			for($i = 0; $i < $numLyrs - 1; $i++){
				$solution .= ($this->objLayer[$i]->layer_title . ";");
			}
			$solution .= $this->objLayer[$i]->layer_title;
		}
		return $solution;
	}
	
	
	protected $wms_srs = array();

	public function getSrsArray(){
		return $this->wms_srs;
	}
	
	public function getSrsAsText(){
		$solution = "";
		$numSrs = sizeof($this->wms_srs);
		
		if($numSrs > 0){
			for($i = 0; $i < $numSrs - 1; $i++){
				$solution .= ($this->wms_srs[$i] . ";");
			}
			$solution .= $this->wms_srs[$i];
		}
		return $solution;
	}
	
	
	
	public function getLatLonBBox(){
		$bbox = array();
		
		$numLyrs = sizeof($this->objLayer);
		
		if($numLyrs > 0){
			for($i = 0; $i < $numLyrs - 1; $i++){
				$layer = $this->objLayer[$i];
				
				$numBBox = sizeof($layer->layer_epsg);
				
				for($j = 0; $j < $numBBox; $j++){
					$layerEpsg = $layer->layer_epsg[$j];
					
					if($layerEpsg["epsg"] == "EPSG:4326"){
						$minx = $layerEpsg["minx"];
						$miny = $layerEpsg["miny"];
						$maxx = $layerEpsg["maxx"];
						$maxy = $layerEpsg["maxy"];
						
						$bbox["minx"] = $minx;
						$bbox["maxx"] = $maxx;
						$bbox["miny"] = $miny;
						$bbox["maxy"] = $maxy;
						
						break;
					}
				}//for j
			}//for i
			
		}//if numLyrs > 0
		if(sizeof($bbox) == 0){
			//this wms service dont have latlonboundingbox against the spec
			throw new WmsWithoutBBoxException("Wms service without latlonboundingbox");
		}
		return $bbox;
	}
	
	
	protected $queryable = false;
	
	public function isQueryable(){
		$numLyrs = sizeof($this->objLayer);
		if($numLyrs > 0){
			for($i = 0; $i < $numLyrs - 1; $i++){
				$layer = $this->objLayer[$i];
				if($layer->layer_queryable){
					return true;
				}
			}//for	
		}//if
		return false;	
	}
	
	
	protected $taxonomic_topic;
	
	public function getTaxonomicTopic(){
		return $this->taxonomic_topic;
	}
	
	public function setTaxonomicTopic($topic){
		$this->taxonomic_topic = $topic;
	}
	
	
	 

	public function __construct($url, $connectionType){
		
		$this->url = $url;
		
		$this->load($url, $connectionType);

		$this->createObjFromXML($url);
	}
	


	public function createObjFromXML($url){


		if ($this->file =='401') {
			throw new NotAuthorizedException("<br>HTTP Error:<b> - Authorization required. This seems to be a service which needs HTTP Authentication!</b><br>");
		}

		if(!$this->file){
			return false;
		}
			
		$values = null;
		$tags = null;

		$this->wms_getcapabilities_doc = $this->file;
		$this->wms_upload_url = $url;

		
		$parser = xml_parser_create("");
		xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
		xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
		xml_parser_set_option($parser,XML_OPTION_TARGET_ENCODING,"UTF-8");
		xml_parse_into_struct($parser,$this->file,$values,$tags);
		$code = xml_get_error_code($parser);
		if ($code) {
			$line = xml_get_current_line_number($parser);
			throw new XmlException(xml_error_string($code) .  " in line " . $line);
		}
		xml_parser_free($parser);
		

		$section = null;
		$subsection = null;
		$format = null;
		$cnt_format = 0;
		$parent = array();
		$myParent = array();
		$cnt_layer = -1;
		$request = null;
		$layer_style = array();
		$cnt_styles = -1;
		
		$dataurl;
		$metadataurl;



		foreach ($values as $element) {
			
			if(mb_strtoupper($element["tag"]) == "WMT_MS_CAPABILITIES" && $element[type] == "open"){//1.1 or 1.3
				$this->wms_version = $element[attributes][version];
			}
			
			if(mb_strtoupper($element["tag"]) == "WMS_CAPABILITIES" && $element[type] == "open"){//3.0
				$this->wms_version = $element[attributes][version];
			}
			
			
			
			if(mb_strtoupper($element["tag"]) == "NAME" && $element[level] == '3'){
				$this->wms_name = $this->stripEndlineAndCarriageReturn($element[value]);
			}
				
			if(mb_strtoupper($element["tag"]) == "TITLE" && $element[level] == '3'){
				$this->wms_title = $this->stripEndlineAndCarriageReturn($element[value]);
			}
			if(mb_strtoupper($element["tag"]) == "ABSTRACT" && $element[level] == '3'){
				$this->wms_abstract = $this->stripEndlineAndCarriageReturn($element[value]);
			}
				
			if(mb_strtolower($element["tag"]) == "fees"){
				$this->fees = $element[value];
			}
				
			if(mb_strtolower($element["tag"]) == "accessconstraints"){
				$this->accessconstraints = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "contactperson"){
				$this->contactperson = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "contactposition"){
				$this->contactposition = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "contactorganization"){
				$this->contactorganization = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "address"){
				$this->address = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "city"){
				$this->city = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "stateorprovince"){
				$this->stateorprovince = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "postcode"){
				$this->postcode = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "country"){
				$this->country = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "contactvoicetelephone"){
				$this->contactvoicetelephone = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "contactfacsimiletelephone"){
				$this->contactfacsimiletelephone = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "contactelectronicmailaddress"){
				$this->contactelectronicmailaddress = $element[value];
			}
			if(mb_strtolower($element["tag"]) == "keyword" && $section != 'layer'){
				$this->wms_keyword[count($this->wms_keyword)] = $element[value];
			}
			
			
			if($this->wms_version == null){
				throw new NotWmsException("La petición getCapabilities no devuelve un XML con WMT_MS_CAPABILITIES");
			}
				
			//map or getmap section

			if($this->wms_version == "1.0.0"){

				if(mb_strtoupper($element["tag"]) == "MAP" && $element[type] == "open"){
					$section = "map";
				}
				if($section == "map" && mb_strtoupper($element["tag"]) == "GET"){
					$this->wms_getmap = $element[attributes][onlineResource];
				}
				if($section == "map" && mb_strtoupper($element["tag"]) == "FORMAT" && $element[type] == "open"){
					$format = "map";
				}
				if(mb_strtoupper($element["tag"]) != "FORMAT" && $section == "map" && $format == "map"){
					if (!$this->formatExists("map", trim($element["tag"]))) {
						$this->data_type[$cnt_format] = "map";
						$this->data_format[$cnt_format] = trim($element["tag"]);
						$cnt_format++;
					}
				}

				if(mb_strtoupper($element["tag"]) == "FORMAT" && $element[type] == "close"){
					$format = "";
				}
				if(mb_strtoupper($element["tag"]) == "MAP" && $element[type] == "close"){
					$section = "";
				}

			}else{

				if(mb_strtoupper($element["tag"]) == "GETMAP" && $element[type] == "open"){
					$section = "map";
				}

				if($section == "map" && mb_strtoupper($element["tag"]) == "GET" && $element[type] == "open"){
					$request = "get";
				}

				if($section == "map" && $request == "get" && mb_strtoupper($element["tag"]) == "ONLINERESOURCE"){
					$this->wms_getmap = $element[attributes]["xlink:href"];
				}

				if($section == "map" && mb_strtoupper($element["tag"]) == "FORMAT"){
					if (!$this->formatExists("map", trim($element[value]))) {
						$this->data_type[$cnt_format] = "map";
						$this->data_format[$cnt_format] = trim($element[value]);
						$cnt_format++;
					}
				}

				if($section == "map" && mb_strtoupper($element["tag"]) == "GET" && $element[type] == "close"){
					$request = "";
				}
				if(mb_strtoupper($element["tag"]) == "GETMAP" && $element[type] == "close"){
					$section = "";
				}
			}
				

			//capabilities section
			
			if($this->wms_version == "1.0.0"){
				if(mb_strtoupper($element["tag"]) == "CAPABILITIES" && $element[type] == "open"){
					$section = "capabilities";
				}
				if($section == "capabilities" && mb_strtoupper($element["tag"]) == "GET"){
					$this->wms_getcapabilities = $element[attributes][onlineResource];
				}
				if(mb_strtoupper($element["tag"]) == "CAPABILITIES" && $element[type] == "close"){
					$section = "";
				}
			}else{

				if(mb_strtoupper($element["tag"]) == "GETCAPABILITIES" && $element[type] == "open"){
					$section = "capabilities";
				}
				if($section == "capabilities" && mb_strtoupper($element["tag"]) == "GET" && $element[type] == "open"){
					$request = "get";
				}
				if($section == "capabilities" && $request == "get" && mb_strtoupper($element["tag"]) == "ONLINERESOURCE"){
					$this->wms_getcapabilities = $element[attributes]["xlink:href"];
				}
				if($section == "capabilities" && mb_strtoupper($element["tag"]) == "GET" && $element[type] == "close"){
					$request = "";
				}
				if(mb_strtoupper($element["tag"]) == "GETCAPABILITIES" && $element[type] == "close"){
					$section = "";
				}
			}
				
				
			//feature info section
			if($this->wms_version == "1.0.0"){
				if(mb_strtoupper($element["tag"]) == "FEATUREINFO" && $element[type] == "open"){
					$section = "featureinfo";
				}
				if($section == "featureinfo" && mb_strtoupper($element["tag"]) == "GET"){
					$this->wms_getfeatureinfo = $element[attributes][onlineResource];
				}
				if($section == "featureinfo" && mb_strtoupper($element["tag"]) == "FORMAT" && $element[type] == "open"){
					$format = "featureinfo";
				}
				if(mb_strtoupper($element["tag"]) != "FORMAT" && $section == "featureinfo" && $format == "featureinfo"){
					if (!$this->formatExists("featureinfo", trim($element["tag"]))) {
						$this->data_type[$cnt_format] = "featureinfo";
						$this->data_format[$cnt_format] = trim($element["tag"]);
						$cnt_format++;
					}
				}
				if(mb_strtoupper($element["tag"]) == "FORMAT" && $element[type] == "close"){
					$format = "";
				}
				if(mb_strtoupper($element["tag"]) == "FEATUREINFO" && $element[type] == "close"){
					$section = "";
				}
			}
			else{
				if(mb_strtoupper($element["tag"]) == "GETFEATUREINFO" && $element[type] == "open"){
					$section = "featureinfo";
				}
				if($section == "featureinfo" && mb_strtoupper($element["tag"]) == "GET" && $element[type] == "open"){
					$request = "get";
				}
				if($section == "featureinfo" && $request == "get" && mb_strtoupper($element["tag"]) == "ONLINERESOURCE"){
					$this->wms_getfeatureinfo = $element[attributes]["xlink:href"];
				}
				if($section == "featureinfo" && mb_strtoupper($element["tag"]) == "FORMAT"){
					if (!$this->formatExists("featureinfo", trim($element[value]))) {
						$this->data_type[$cnt_format] = "featureinfo";
						$this->data_format[$cnt_format] = trim($element[value]);
						$cnt_format++;
					}
				}
				if($section == "featureinfo" && mb_strtoupper($element["tag"]) == "GET" && $element[type] == "close"){
					$request = "";
				}
				if(mb_strtoupper($element["tag"]) == "GETFEATUREINFO" && $element[type] == "close"){
					$section = "";
				}
			}
				
			/*
			 *EXCEPCTION SECTION
			 */
			if($this->wms_version == "1.0.0"){
				if(mb_strtoupper($element["tag"]) == "EXCEPTION" && $element[type] == "open"){
					$section = "exception";
				}
				if($section == "exception" && mb_strtoupper($element["tag"]) == "FORMAT" && $element[type] == "open"){
					$format = "exception";
				}
				if(mb_strtoupper($element["tag"]) != "FORMAT" && $section == "exception" && $format == "exception"){
					$this->data_type[$cnt_format] = "exception";
					$this->data_format[$cnt_format] = trim($element["tag"]);
					$cnt_format++;
				}
				if($section == "exception" && mb_strtoupper($element["tag"]) == "FORMAT" && $element[type] == "close"){
					$format = "";
				}
				if(mb_strtoupper($element["tag"]) == "EXCEPTION" && $element[type] == "close"){
					$section = "";
				}
			}
			else{
				if(mb_strtoupper($element["tag"]) == "EXCEPTION" && $element[type] == "open"){
					$section = "exception";
				}
				if($section == "exception" && mb_strtoupper($element["tag"]) == "FORMAT"){
					$this->data_type[$cnt_format] = "exception";
					$this->data_format[$cnt_format] = trim($element[value]);
					$cnt_format++;
				}
				if(mb_strtoupper($element["tag"]) == "EXCEPTION" && $element[type] == "close"){
					$section = "";
				}
			}
				
			/*
			 *  LEGEND GRAPHICS SECTION
			 */
			if($this->wms_version == "1.0.0"){
				 
			}
			else{
				if(mb_strtoupper($element["tag"]) == "GETLEGENDGRAPHIC" && $element[type] == "open"){
					$section = "legend";
				}
				if($section == "legend" && mb_strtoupper($element["tag"]) == "GET" && $element[type] == "open"){
					$request = "get";
				}
				if($section == "legend" && $request == "get" && mb_strtoupper($element["tag"]) == "ONLINERESOURCE"){
					$this->wms_getlegendurl = $element[attributes]["xlink:href"];
				}
				if($section == "legend" && mb_strtoupper($element["tag"]) == "GET" && $element[type] == "close"){
					$request = "";
				}
				if(mb_strtoupper($element["tag"]) == "GETLEGENDGRAPHIC" && $element[type] == "close"){
					$section = "";
				}
			}
			/*
			 * SUPPORT SLD SECTION
			 */
			if(mb_strtoupper($element["tag"]) == "USERDEFINEDSYMBOLIZATION" && $element[type] == "complete"){
				$this->wms_supportsld = $element[attributes]["SupportSLD"];
				$this->wms_userlayer = $element[attributes]["UserLayer"];
				$this->wms_userstyle = $element[attributes]["UserStyle"];
				$this->wms_remotewfs = $element[attributes]["RemoteWFS"];
			}

			/*
			 * LAYER SECTION
			 **/
			if(mb_strtoupper($element["tag"]) == "LAYER"){
				$section = "layer";
				if ($element[type] == "open") {
					$cnt_epsg = -1;
					//new for resolving metadataurls and dataurls
					$cnt_metadataurl = -1;
					$cnt_dataurl = -1;
					$cnt_layer++;
					$parent[$element[level]+1] = $cnt_layer;
					$myParent[$cnt_layer]= $parent[$element[level]];
					
					$this->addLayer($cnt_layer,$myParent[$cnt_layer]);
					$this->objLayer[$cnt_layer]->layer_queryable = $element[attributes][queryable];
				}
				/*
				else if ($element[type] == "close") {
					//"tag" closing
				}
				*/
			}
			
			/* 
			 * attribution 
			 */
			if(mb_strtoupper($element["tag"]) == "ATTRIBUTION"){
				if ($element[type] == "open") {
					$section = "attribution";
				}
				if ($element[type] == "close") {
					$section = "layer";
				}
			}
			
			/* 
			 * styles 
			 * */       
			$legendurl = null;
			if(mb_strtoupper($element["tag"]) == "STYLE"){
				$section = "style";
				if($cnt_layer != $layer_style){
					$layer_style = $cnt_layer;
					$cnt_styles = -1;
				}
				if ($element[type] == "open") {
					$cnt_styles++;
				}
				if ($element[type] == "close") {
					$section = "layer";
				}
			}
			if($section == "style"){
				if(mb_strtoupper($element["tag"]) == "NAME"){
					$this->objLayer[$cnt_layer]->layer_style[$cnt_styles]["name"] = ($element[value] ? $element[value] : 'default');
				}
				if(mb_strtoupper($element["tag"]) == "TITLE"){
					$this->objLayer[$cnt_layer]->layer_style[$cnt_styles]["title"] = ($element[value] ? $element[value] : '');
				}
				if(mb_strtoupper($element["tag"]) == "LEGENDURL" && $element[type] == "open"){
					$legendurl = true;
				}
				if($legendurl && mb_strtoupper($element["tag"]) == "FORMAT"){
					$this->objLayer[$cnt_layer]->layer_style[$cnt_styles]["legendurlformat"] = $element[value];
				}
				if($legendurl && mb_strtoupper($element["tag"]) == "ONLINERESOURCE"){
					$this->objLayer[$cnt_layer]->layer_style[$cnt_styles]["legendurl"] = $element[attributes]["xlink:href"];
				}
				if(mb_strtoupper($element["tag"]) == "LEGENDURL" && $element[type] == "close"){
					$legendurl = false;
				}
			}
/* 
 * end of styles 
 **/
			
			
			
			if($section == "layer")
			{
				
				
				if(mb_strtoupper($element["tag"]) == "NAME"){
					$this->objLayer[$cnt_layer]->layer_name = $element[value];
				}
				
				if(mb_strtoupper($element["tag"]) == "TITLE"){
					$this->objLayer[$cnt_layer]->layer_title = $this->stripEndlineAndCarriageReturn($element[value]);
				}
				
				if(mb_strtoupper($element["tag"]) == "ABSTRACT"){
					$this->objLayer[$cnt_layer]->layer_abstract = $this->stripEndlineAndCarriageReturn($element[value]);
				}
				if(mb_strtoupper($element["tag"]) == "KEYWORD"){
					array_push($this->objLayer[$cnt_layer]->layer_keyword, trim($element[value]));
				}
				
				if(mb_strtoupper($element["tag"]) == "DATAURL" && $element[type] == "open"){
					$dataurl = true;
					$cnt_dataurl++;
				}
				
				if($dataurl && mb_strtoupper($element["tag"]) == "ONLINERESOURCE"){
					$this->objLayer[$cnt_layer]->layer_dataurl[$cnt_dataurl]->href = $element[attributes]["xlink:href"];
				}
				
				if(mb_strtoupper($element["tag"]) == "DATAURL" && $element[type] == "close"){
					$dataurl = false;
				}
				

				if(mb_strtoupper($element["tag"]) == "METADATAURL" && $element[type] == "open"){
					$metadataurl = true;
					$cnt_metadataurl++;
					$this->objLayer[$cnt_layer]->layer_metadataurl[$cnt_metadataurl]->type = $element[attributes]["type"];
				}
				if($metadataurl && mb_strtoupper($element["tag"]) == "FORMAT"){
					$this->objLayer[$cnt_layer]->layer_metadataurl[$cnt_metadataurl]->format = $element[value];
				}
				if($metadataurl && mb_strtoupper($element["tag"]) == "ONLINERESOURCE"){
					$this->objLayer[$cnt_layer]->layer_metadataurl[$cnt_metadataurl]->href = $element[attributes]["xlink:href"];
				}
				if(mb_strtoupper($element["tag"]) == "METADATAURL" && $element[type] == "close"){
					$metadataurl = false;
				}
		
				//WMS 1.1.1
				if(mb_strtoupper($element["tag"]) == "SRS"){
					// unique srs only, see http://www.mapbender.org/index.php/Arrays_with_unique_entries
					$this->wms_srs = array_keys(array_flip(array_merge($this->wms_srs, explode(" ", strtoupper($element[value])))));
				}
				//WMS 1.3
				else if(mb_strtoupper($element["tag"]) == "CRS"){
					// unique srs only, see http://www.mapbender.org/index.php/Arrays_with_unique_entries
					$this->wms_srs = array_keys(array_flip(array_merge($this->wms_srs, explode(" ", strtoupper($element[value])))));
				}
				
				if(mb_strtoupper($element["tag"]) == "LATLONBOUNDINGBOX"){
					$cnt_epsg++;
					$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["epsg"] = "EPSG:4326";
					$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["minx"] = $element[attributes][minx];
					$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["miny"] = $element[attributes][miny];
					$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["maxx"] = $element[attributes][maxx];
					$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["maxy"] = $element[attributes][maxy];
					
				}else if(mb_strtoupper($element["tag"]) == "EX_GEOGRAPHICBOUNDINGBOX" && $element[type] == "open"){
					$cnt_epsg++;
					$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["epsg"] = "EPSG:4326";
					
					$subsection = "ex_geographicboundingbox";
					/*
					<EX_GeographicBoundingBox>
						<westBoundLongitude>-2.77407</westBoundLongitude>
						<eastBoundLongitude>-2.76326</eastBoundLongitude>
						<southBoundLatitude>42.2577</southBoundLatitude>
						<northBoundLatitude>42.2691</northBoundLatitude>
					</EX_GeographicBoundingBox>
					*/
					
				}else if(mb_strtoupper($element["tag"]) == "EX_GEOGRAPHICBOUNDINGBOX" && $element[type] == "close"){
					$subsection = "";
				}
				
				
				
				//WMS 1.3
				
				if($subsection == "ex_geographicboundingbox"){
					if(mb_strtoupper($element["tag"]) == "WESTBOUNDLONGITUDE"){
						$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["minx"] = $element[value];
					}
					
					if(mb_strtoupper($element["tag"]) == "EASTBOUNDLONGITUDE"){
						$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["maxx"] = $element[value] ;
					}
					
					if(mb_strtoupper($element["tag"]) == "SOUTHBOUNDLATITUDE"){
						$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["miny"] = $element[value];
					}
					
					if(mb_strtoupper($element["tag"]) == "NORTHBOUNDLATITUDE"){
						$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["maxy"] = $element[value];
					}
					
				}
				
				
				
				
				if(mb_strtoupper($element["tag"]) == "BOUNDINGBOX" && $element[attributes][SRS] != "EPSG:4326"){
					$cnt_epsg++;
					$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["epsg"] = $element[attributes][SRS];
					$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["minx"] = $element[attributes][minx];
					$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["miny"] = $element[attributes][miny];
					$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["maxx"] = $element[attributes][maxx];
					$this->objLayer[$cnt_layer]->layer_epsg[$cnt_epsg]["maxy"] = $element[attributes][maxy];
					// a default epsg for mapbender
					if($cnt_layer == 0 && $this->default_epsg == 0 && mb_strlen(trim($element[attributes][SRS]))>= 10){
						$this->default_epsg = $cnt_epsg;
					}
				}
				if(mb_strtoupper($element["tag"]) == "SCALEHINT"){
					if($element[attributes][max]>1000) $max = 0; else $max = $element[attributes][max];
					if($element[attributes][min]>1000) $min = 0; else $min = $element[attributes][min];
					$this->objLayer[$cnt_layer]->layer_minscale = round(($min * 2004.3976484406788493955738891127));
					$this->objLayer[$cnt_layer]->layer_maxscale = round(($max * 2004.3976484406788493955738891127));
					$this->objLayer[$cnt_layer]->layer_minscale = sprintf("%u", $this->objLayer[$cnt_layer]->layer_minscale);
					$this->objLayer[$cnt_layer]->layer_maxscale = sprintf("%u", $this->objLayer[$cnt_layer]->layer_maxscale);
				}
				
						
				
			}else {
				continue;
			}
		}
		
		
		
		if(!$this->wms_title || $this->wms_title == "" || !$this->wms_getmap || $this->wms_getmap == ""){
			$this->wms_status = false;
			
			//FIXME Lanzar una excepcion o ver que pasa aqui
			//$this->optimizeWMS();
			//$e = new mb_exception("class_wms: createObjFromXML: WMS " . $url . " could not be loaded.");
			return false;
		}
		else{
			$this->wms_status = true;
			$this->optimizeWMS();
			return true;
		}
	}
	
	
 	function addLayer($id,$parent){	
		$this->objLayer[count($this->objLayer)] = new layer($id,$parent);
	}
	
	function optimizeWMS() {
		/*define defaults for wms-version 1.0.0*/
		$map_default_ok = false;
		$featureinfo_default_ok = false;
		$exception_default_ok = false;
		
		if($this->wms_version == "1.0.0"){
			$map_default = "PNG";
			$featureinfo_default = "MIME";
			$exception_default = "INIMAGE";
		}
		/*define defaults for wms-version 1.1.0 and 1.1.1*/
		else{
			$map_default = "image/png";
			$featureinfo_default = "text/html";
			$exception_default = "application/vnd.ogc.se_inimage";
		}
		
		
		/*if the rootlayer has no epsg...*/
		if($this->objLayer[0]->layer_epsg[0]["epsg"] == ""){
			$this->objLayer[0]->layer_epsg = $this->objLayer[1]->layer_epsg;
			
			for($i=0;$i<count($this->objLayer[0]->layer_epsg);$i++){
				for($j=1; $j<count($this->objLayer); $j++){
					if($this->objLayer[0]->layer_epsg[$i]["epsg"] == $this->objLayer[$j]->layer_epsg[$i]["epsg"]){
						if($this->objLayer[$j]->layer_epsg[$i]["minx"]<$this->objLayer[0]->layer_epsg[$i]["minx"]){
							$this->objLayer[0]->layer_epsg[$i]["minx"] = $this->objLayer[$j]->layer_epsg[$i]["minx"];
						}
						if($this->objLayer[$j]->layer_epsg[$i]["miny"]<$this->objLayer[0]->layer_epsg[$i]["miny"]){
							$this->objLayer[0]->layer_epsg[$i]["miny"] = $this->objLayer[$j]->layer_epsg[$i]["miny"];
						}
						if($this->objLayer[$j]->layer_epsg[$i]["maxx"]>$this->objLayer[0]->layer_epsg[$i]["maxx"]){
							$this->objLayer[0]->layer_epsg[$i]["maxx"] = $this->objLayer[$j]->layer_epsg[$i]["maxx"];
						}
						if($this->objLayer[$j]->layer_epsg[$i]["maxy"]>$this->objLayer[0]->layer_epsg[$i]["maxy"]){
							$this->objLayer[0]->layer_epsg[$i]["maxy"] = $this->objLayer[$j]->layer_epsg[$i]["maxy"];
						}
					}
				}
			}
		}
		
		
		for($i=0;$i<count($this->objLayer);$i++){
			if(count($this->objLayer[$i]->layer_epsg) == 0 && count($this->objLayer[0]->layer_epsg) > 0){
				$this->objLayer[$i]->layer_epsg = $this->objLayer[0]->layer_epsg; 
			}
			if(!is_int($this->objLayer[$i]->layer_parent)){
				$this->objLayer[$i]->layer_abstract = $this->wms_abstract;
				for ($r = 0; $r < count($this->wms_keyword); $r++) {
					array_push($this->objLayer[$i]->layer_keyword, trim($this->wms_keyword[$r]));
				}
			}
			if($this->objLayer[$i]->layer_name == ""){
				$this->objLayer[$i]->layer_name = $this->objLayer[$i]->layer_title;
			}
			if($this->objLayer[$i]->layer_minscale == ""){
				$this->objLayer[$i]->layer_minscale = 0;
			}
			if($this->objLayer[$i]->layer_maxscale == ""){
				$this->objLayer[$i]->layer_maxscale = 0;
			}
			if($this->objLayer[$i]->layer_queryable == ""){
				$this->objLayer[$i]->layer_queryable = 0;
			}
			$this->objLayer[$i]->gui_layer_minscale = $this->objLayer[$i]->layer_minscale;
			$this->objLayer[$i]->gui_layer_maxscale = $this->objLayer[$i]->layer_maxscale;
			$this->objLayer[$i]->layer_searchable = 1;
		}
		
		
		for($i=0;$i<count($this->data_format);$i++){
			if(mb_strtolower($this->data_type[$i]) == 'map' && mb_strtoupper($this->data_format[$i]) == mb_strtoupper($map_default)){
				$this->gui_wms_mapformat = mb_strtolower($map_default);
				$map_default_ok = true;
			}
			if(mb_strtolower($this->data_type[$i]) == 'featureinfo' && mb_strtoupper($this->data_format[$i]) == mb_strtoupper($featureinfo_default)){
				$this->gui_wms_featureinfoformat = mb_strtolower($featureinfo_default);
				$featureinfo_default_ok = true;
			}		
			if(mb_strtolower($this->data_type[$i]) == 'exception' && mb_strtolower($this->data_format[$i]) == mb_strtolower($exception_default)){
				$this->gui_wms_exceptionformat = mb_strtolower($exception_default);
				$exception_default_ok = true;
			}		
		}
		if($map_default_ok == false){
			for($i=0;$i<count($this->data_format);$i++){
				if(mb_strtolower($this->data_type[$i]) == "map" ){$this->gui_wms_mapformat = $this->data_format[$i]; break;}
			}
		}
		if($featureinfo_default_ok == false){
			for($i=0;$i<count($this->data_format);$i++){
				if(mb_strtolower($this->data_type[$i]) == "featureinfo" ){$this->gui_wms_featureinfoformat = $this->data_format[$i]; break;}
			}
		}
		if($exception_default_ok == false){
			for($i=0;$i<count($this->data_format);$i++){
				if(mb_strtolower($this->data_type[$i]) == "exception" ){$this->gui_wms_exceptionformat = $this->data_format[$i]; break;}
			}
		}
		
	
		/*the queryable layers*/
		for($i=0; $i<count($this->objLayer); $i++){
			if($this->objLayer[$i]->layer_queryable == 1){
				$this->objLayer[$i]->gui_layer_queryable = 1;
			}
			else{
				$this->objLayer[$i]->gui_layer_queryable = 0;
			}
		}
		for($i=0; $i<count($this->objLayer); $i++){
				$this->objLayer[$i]->layer_pos=$i;
		}
		
		// check if gui_layer_title isset
		for($i=0; $i<count($this->objLayer); $i++){
			$this->objLayer[$i]->gui_layer_title = $this->objLayer[$i]->gui_layer_title != "" ?
				$this->objLayer[$i]->gui_layer_title :
				$this->objLayer[$i]->layer_title;
		}
		
		/* fill sld variables when empty */
		if($this->wms_supportsld == ""){
				$this->wms_supportsld = 0;
		}
		if($this->wms_userlayer == ""){
				$this->wms_userlayer = 0;
		}
		if($this->wms_userstyle == ""){
				$this->wms_userstyle = 0;
		}
		if($this->wms_remotewfs == ""){
				$this->wms_remotewfs = 0;
		}
	  }
	


	/**
	 * Loads content from the given URL.
	 */
	public function load($url, $connectionType) {
		
		
		if(strpos($url,"?") === false)
			$url .= "?";
		if(! MapUtils::singleton()->endsWith($url,"?"))
			$url .= "&";
		$url .= "service=WMS&request=GetCapabilities";
		switch ($connectionType) {
			case "curl":
				if (func_num_args() == 2) {
					$auth = func_get_arg(1);
					if (isset($auth)) {
						$this->file = $this->getCURL($url,$auth);
					}
				}else {
					$this->file = $this->getCURL($url);
				}
				break;

			case "http":
				$this->file = $this->getHTTP($url);
				break;

			case "socket":
				$this->file = $this->getSOCKET($url);
				break;
		}
		if(!$this->file){
			return false;
		}
		return $this->file;
	}


	private function getCURL($url){
		$url=Str_replace(" ","+",$url); //to have no problems with image/png; mode=24bit!
		$url=str_replace(";","%3B",$url);
		if (func_num_args() == 2) {
			$auth = func_get_arg(1);
		} //auth should be an array of ['username', 'realm', 'password', 'auth_type'] - or false - problem would be, that these are stored without hashing them!
		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); //for images
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//dont store and load cookies from previous sessions
		curl_setopt($ch, CURLOPT_COOKIESESSION, true);
		//allow https connections and handle certificates quite simply ;-)
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeOut);
			
		$arURL = parse_url($url);
		$host = $arURL["host"];
		$port = $arURL["port"];
			
		if($port == ''){
			$port = 80;
		}
		$path = $arURL["path"];
			
			
		$NOT_PROXY_HOSTS_array = explode(",", NOT_PROXY_HOSTS);
		 
		//check if http_proxy is set as env, if yes, unset it for the curl action here, it will be reset somewhere below - normally not needed, cause it will be only available when at execution time of the script http://php.net/manual/en/function.putenv.php
		if (getenv('http_proxy')) {
			$tmpHttpProxy = getenv('http_proxy');
			putenv("http_proxy"); //this should unset the variable???
		} else {
			$tmpHttpProxy = getenv('http_proxy');
		}

			
		if(isset($auth) && $auth != false) {
			curl_setopt($ch, CURLOPT_USERPWD, $auth['username'].':'.$auth['password']);
			if ($auth['auth_type'] == 'digest') {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
			}
			if ($auth['auth_type'] == 'basic') {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			}
		}
			
			
			
		//if httpType is POST, set CURLOPT_POST and CURLOPT_POSTFIELDS
		//and set a usefull http header
		if(strtoupper($this->httpType) == 'POST'){
			$headers = array(
						"POST ".$path." HTTP/1.1",
	            			 	"Content-type: ".$this->httpContentType."; charset=".CHARSET,
	           				"Cache-Control: no-cache",
		           		 	"Pragma: no-cache",
		           		 	"Content-length: ".strlen($this->httpPostData)
			);
			$e = new mb_notice("connector: CURL POST: ".$this->httpPostData);
			$e = new mb_notice("connector: CURL POST length: ".strlen($this->httpPostData));

			if ($this->curlSendCustomHeaders) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			}
			if ($this->httpPostFieldsNumber != 1){
				curl_setopt($ch,CURLOPT_POST,$this->httpPostFieldsNumber);
			} else {
				curl_setopt($ch, CURLOPT_POST, 1);
			}
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->httpPostData);
		}
		$useragent=CONNECTION_USERAGENT;
		//Build own headers for GET Requests - maybe needful?
		if(strtoupper($this->httpType) == 'GET'){
			$headers = array(
						"GET ".$path." HTTP/1.1",
						"User-Agent: ".$_SERVER['HTTP_USER_AGENT'],
	           				"Host: ".$host,
		           		 	"Accept: */*",
						"Proxy-Connection: Keep-Alive"
						);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		curl_setopt($ch,CURLOPT_DNS_USE_GLOBAL_CACHE, false);
		curl_setopt($ch,CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, false);

		$file = curl_exec ($ch);
		$info = curl_getinfo($ch);
			
		if ($info['http_code'] == '401') {
			curl_close ($ch);
			return $info['http_code'];
		}
		if ($info['http_code'] == '502') {
			curl_close ($ch);
			//$e = new mb_exception("class_connector.php: Problem with connecting Gateway - maybe problem with the configuration of the security proxy (mod_proxy?).");
			return $info['http_code'];
			/*fwrite($handle,"HEADER: \n");
				fwrite($handle,$error_log);
				fwrite($handle,"502: ".$file."\n");*/
		}
		curl_close ($ch);
			
		return $file;
	}

	private function getHTTP($url){
		if ($this->httpType == "get") {
			return @file_get_contents($url);
		}
		else {
			$errno = 0;
			$errstr = "";
			$urlComponentArray = parse_url($url);
			$scheme = $urlComponentArray["scheme"];
			$host = $urlComponentArray["host"];
			$port = $urlComponentArray["port"];
			if ($port == "") {
				if ($scheme == "https") {
					$port = 443;
				}
				else {
					$port = 80;
				}
			}
			$path = $urlComponentArray["path"];
			$query = $urlComponentArray["query"];
			$buf = '';
			if ($scheme == "https") {
				$fp = fsockopen("ssl://". $host, $port, $errno, $errstr);
			}
			else {
				$fp = fsockopen($host, $port);
			}
			$postStr = "";
			$postPath = "POST " . $path . "?" . $query . " HTTP/".$this->httpVersion . "\r\n";
			$postStr .= $postPath;
			fputs($fp, $postPath);

			$postHost = "Host: " . $host . "\r\n";
			$postStr .= $postHost;
			fputs($fp, $postHost);

			if ($this->isValidHttpContentType($this->httpContentType)) {
				$postContentType = "Content-type: " . $this->httpContentType . "\r\n";
				$postStr .= $postContentType;
				fputs($fp, $postContentType);
			}
			$postContentLength = "Content-length: " . strlen($this->httpPostData) . "\r\n";
			$postStr .= $postContentLength;
			fputs($fp, $postContentLength);

			$postClose = "Connection: close\r\n\r\n";
			$postStr .= $postClose;
			fputs($fp, $postClose);

			$postStr .= $this->httpPostData;
			fputs($fp, $this->httpPostData);

			new mb_notice("connector.http.postData: ".$this->httpPostData);

			$xmlstr = false;
			//@TODO remove possibly infinite loop
			while (!feof($fp)) {
				$content = fgets($fp,4096);
				//		    	if( strpos($content, '<?xml') === 0){
				if( strpos($content, '<') === 0){
					$xmlstr = true;
				}
				if($xmlstr == true){
					$buf .= $content;
				}
			}
			fclose($fp);
			//		    new mb_notice("connector.http.response: ".$buf);
			return $buf;
		}
	}

	private function getSOCKET($url){
		$r = "";
		$fp = fsockopen (CONNECTION_PROXY, CONNECTION_PORT, $errno, $errstr, 30);
		if (!$fp) {
			echo "$errstr ($errno)<br />\n";
		}
		else {
			fputs ($fp, "GET ".$url." HTTP/1.0\r\n\r\n");
			while (!feof($fp)) {
				$r .= fgets($fp,4096);
			}
			fclose($fp);
			return $r;
		}
	}

	function stripEndlineAndCarriageReturn($string) {
		return preg_replace("/\n/", "", preg_replace("/\r/", " ", $string));
	}

	private function formatExists ($type, $format) {
		for ($i = 0; $i < count($this->data_type); $i++) {
			if ($type == $this->data_type[$i] && $format == $this->data_format[$i]) {
				return true;
			}
		}
		return false;
	}
}


class layer extends WMS {	
	var $layer_id;
	var $layer_parent;
	var $layer_name;
	var $layer_title;
	var $layer_abstract;
	var $layer_pos;
	var $layer_queryable;
	var $layer_minscale;
	var $layer_maxscale;
	var $layer_dataurl;
	
    var $layer_dataurl_href;
    var $layer_metadataurl;
	var $layer_searchable;
    var $layer_keyword = array();
	var $layer_epsg = array();
	var $layer_style = array();
	var $layer_md_topic_category_id = array();
	var $layer_inspire_category_id = array();
	var $layer_custom_category_id = array();
	
	var $gui_layer_wms_id;
	var $gui_layer_status = 1;
	var $gui_layer_selectable = 1;
	var $gui_layer_visible = 0;
	var $gui_layer_queryable = 0;
	var $gui_layer_querylayer = 0;
	var $gui_layer_style = NULL;	
	
	
	function layer($id,$parent){
		$this->layer_id = $id;
		$this->layer_parent = $parent;	
		//var_dump($this);	
	}

	public function equals ($layer) {
		if (is_numeric($this->layer_uid) && $this->layer_uid === $layer->layer_uid) {
			return true;
		}
		return false;
	}

	public function __toString () {
//		$e = new mb_exception("TITLE: " . $this->layer_title);
		return $this->layer_title;
	}
}