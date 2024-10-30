<?php

define('BLOGOROLA_IN_WORDPRESS', false);

$_blogorola_soap_ext = false;
if (in_array("soap", get_loaded_extensions())) $_blogorola_soap_ext = true;

$_blogorola_service_url = (isset($_SERVER['SCRIPT_NAME'])) ? $_SERVER['SCRIPT_NAME'] : false;
if (!$_blogorola_service_url) $_blogorola_service_url = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : false;
if (!strstr($_blogorola_service_url, '://')) $_blogorola_service_url = 'http://' . $_SERVER['HTTP_HOST'] . $_blogorola_service_url;

if (isset($_GET['ajax'])) {
	
	if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
		
		if (isset($_GET['vote']) && ($_GET['vote'] == 0 || $_GET['vote'] == 1)) {	
			
			if (!function_exists('get_option')) @require_once(dirname(__FILE__). '/../../../wp-config.php');
			if (!function_exists('get_option')) @require_once('../../../wp-config.php');
				
			if(!function_exists('get_option')) {
				echo "0%|0%"; /* error */ exit();
			}
			
			if (!class_exists("blogorola")) {
				echo "0%|0%"; /* error */
				if (class_exists("wpdb") && isset($wpdb->dbh)) @mysql_close($wpdb->dbh); exit();
			}
			
			$postdata = get_post($_GET['id']);
			if (isset($postdata->ID) && $postdata->ID > 0) {
				
				$result = $blogorola->post_vote($postdata->guid, $_GET['vote']);
				if ($result == false) {
					
					$hot = get_post_meta($_GET['id'], 'blogorola_hot', $single = true);
					$not = get_post_meta($_GET['id'], 'blogorola_not', $single = true);
					echo $hot."%" . "|" . $not."%";
					
				} else { 
					
					update_post_meta($_GET['id'], 'blogorola_hot', $result['hot']);
					update_post_meta($_GET['id'], 'blogorola_not', $result['not']);
					echo $result['hot']."%" . "|" . $result['not']."%";
				}
				
			} else {
				echo "0%|0%"; /* error */	
			}
		}
	}
	if (class_exists("wpdb") && isset($wpdb->dbh)) @mysql_close($wpdb->dbh);
	exit();
		
	
} elseif (isset($_GET['js'])) {
	
	$arh = array();
	$rx_http = '/\AHTTP_/';
	foreach($_SERVER as $key => $val) {
		if ( preg_match($rx_http, $key) ) {
			$arh_key = preg_replace($rx_http, '', $key);
			$rx_matches = array();
			$rx_matches = explode('_', $arh_key);
			if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
				foreach ($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst(strtolower($ak_val));
				$arh_key = implode('-', $rx_matches);
			}
			$arh[$arh_key] = $val;
		}
	}	
	$filename = basename($_SERVER['PHP_SELF']);
	if (isset($arh['If-Modified-Since']) && (strtotime($arh['If-Modified-Since']) == filemtime($filename))) {
		header('Content-type: text/javascript; charset: UTF-8');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT', true, 304);
		exit();
	}
	header('Content-type: text/javascript; charset: UTF-8');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s',filemtime($filename)).' GMT', true, 200);
	
	echo 'var _blogorola_tmp_cnt_bf 		= \'\';
var _blogorola_tmp_elm_bf 		= \'\';
var _blogorola_tmp_pid 		= \'\';

function _blogorola_make_request(gets, elm, pid) {
	var url = \''.$_blogorola_service_url.'?ajax'.'\' + \'&\' + gets;
	_blogorola_tmp_elm_bf = elm;
	_blogorola_tmp_cnt_bf = _blogorola_get_el(elm).innerHTML;
	_blogorola_tmp_pid = pid;
	_blogorola_get_el(elm).innerHTML = \'\';
	
	_blogorola_get_el(\'blogorola_hon_hot_div_\'+pid).removeAttribute(\'onclick\'); _blogorola_get_el(\'blogorola_hon_not_div_\'+pid).removeAttribute(\'onclick\');
	_blogorola_get_el(\'blogorola_hon_hot_div_\'+pid).style.cursor = \'default\'; _blogorola_get_el(\'blogorola_hon_not_div_\'+pid).style.cursor = \'default\';
	
	var _blogorola_callback = _blogorola_process_response;
	_blogorola_execute_xhr(_blogorola_callback, url);	
}

function _blogorola_execute_xhr(_blogorola_callback, url) {
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
		req.onreadystatechange = _blogorola_callback;
		req.open("GET", url, true);
		req.send(null);
	} 
	else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
		if (req) {
			req.onreadystatechange = _blogorola_callback;
			req.open("GET", url, true);
			req.send(null);
		}
	}
}

function _blogorola_process_response() {
	if (req.readyState == 4) {
		if (req.status == 200) {
			var _blogorola_response = req.responseText.split(\'|\');
			_blogorola_get_el(\'blogorola_hon_hot_cnt_\'+_blogorola_tmp_pid).innerHTML = _blogorola_response[0];
			_blogorola_get_el(\'blogorola_hon_not_cnt_\'+_blogorola_tmp_pid).innerHTML = _blogorola_response[1];
		} else {
			_blogorola_get_el(\'_blogorola_tmp_elm_bf\').innerHTML = _blogorola_tmp_cnt_bf;
		}
	}
}

function _blogorola_get_el(id) {
	return(document.getElementById(id));
}';
			
	
} elseif (isset($_GET['wsdl']) && $_blogorola_soap_ext) {

	header("Content-Type: text/xml; charset=UTF-8");

	?><definitions name="Blogorola" 
targetNamespace="urn:Blogorola" 
xmlns:typens="urn:Blogorola" 
xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" 
xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" 
xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" 
xmlns="http://schemas.xmlsoap.org/wsdl/">

<message name="blogorola_comment_insertCommentRequest">
	<part name="guid" type="xsd:string"/>
	<part name="comment_author" type="xsd:string"/>
	<part name="comment_author_email" type="xsd:string"/>
	<part name="comment_author_url" type="xsd:string"/>
	<part name="comment_content" type="xsd:string"/>
</message>
<message name="blogorola_comment_insertCommentResponse">
	<part name="responce" type="xsd:string"/>
</message> 

<portType name="BlogorolaPortType">
	<operation name="blogorola_comment_insertComment">
		<input message="typens:blogorola_comment_insertCommentRequest"/>
		<output message="typens:blogorola_comment_insertCommentResponse"/>
	</operation>
</portType>

<binding name="BlogorolaBinding" type="typens:BlogorolaPortType">
	<soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
	<operation name="blogorola_comment_insertComment">
		<soap:operation soapAction="urn:BlogorolaAction"/>
		<input>
			<soap:body namespace="urn:Blogorola" use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
		</input>
		<output>
			<soap:body namespace="urn:Blogorola" use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
		</output>
	</operation>
	
</binding>

<service name="BlogorolaService">
	<port name="BlogorolaPort" binding="typens:BlogorolaBinding">
		<soap:address location="<?php echo $_blogorola_service_url; ?>"/>
	</port>
</service>
</definitions><?php	
	
} else {
	
	if(!function_exists('get_option'))
		@require_once(dirname(__FILE__). '/../../../wp-config.php');
	
	if (!function_exists('get_option'))
		@require_once('../../../wp-config.php');
		
	if(!function_exists('get_option')) 
		exit("Blogorola include error");
		
	if (!class_exists("blogorola")) {
		exit("Blogorola plugin error");
		if (class_exists("wpdb") && isset($wpdb->dbh)) @mysql_close($wpdb->dbh);	
	}
	
	if ( $blogorola->soap_ext || $blogorola->soap_lib ) {
	
		ini_set("soap.wsdl_cache_enabled", "0");
		$data = $HTTP_RAW_POST_DATA;
		if (!$data && $blogorola->soap_ext)
			$data = file_get_contents('php://input');
		
		if (!$data && $blogorola->soap_ext) {
			if (class_exists("wpdb") && isset($wpdb->dbh)) @mysql_close($wpdb->dbh);
			exit();	
		}
			
		function blogorola_comment_insertComment($guid, $comment_author, $comment_author_email, $comment_author_url, $comment_content) {
			global $blogorola;
			
			remove_action('comment_post', array('blogorola', 'comment_pingback'));
			$res = $blogorola->comment_insert($guid, $comment_author, $comment_author_email, $comment_author_url, $comment_content);
			return $res;
		}	
			
		if ($blogorola->soap_ext) {	
			
			$server = new SoapServer($blogorola->service_wsdl, array(
			    'encoding' => 'UTF-8'
			    ));
			$server->addFunction('blogorola_comment_insertComment');
			$server->handle($data);
			
		} elseif ($blogorola->soap_lib) {
			
			$server = new soap_server();
			$server->configureWSDL('Blogorola', 'urn:Blogorola');
			$server->register('blogorola_comment_insertComment',
				array(
					'guid' => 'xsd:string',
					'comment_author' => 'xsd:string',
					'comment_author_email' => 'xsd:string',
					'comment_author_url' => 'xsd:string',
					'comment_content' => 'xsd:string'
				),
				array('responce' => 'xsd:string'),	
				'urn:Blogorola',					
				'urn:Blogorola#blogorola_comment_insertComment',				
				'rpc',
				'encoded',
				''
			);
		
			$server->service($data);
		}
		
	}
}

if (class_exists("wpdb") && isset($wpdb->dbh)) @mysql_close($wpdb->dbh);
exit();
?>