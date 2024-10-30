<?php
/*
Plugin Name: Blogorola
Plugin URI: http://ma.tija.cc/blogorola-wordpress-plugin/
Description: Goodies for <a href="http://www.blogorola.com">Blogorola</a> - ultimate blogroll.
Author: ma.tija
Version: 1.0.1
Author URI: http://ma.tija.cc
*/

if (!defined('BLOGOROLA_IN_WORDPRESS')) define('BLOGOROLA_IN_WORDPRESS', true);	

if ( !class_exists('blogorola') ) {

	class blogorola {
	
		var $url;
		var $path;
		var $url_img;
		var $logo;
		var $symbol_opt;
		var $symbol;
		var $service_url;
		var $service_wsdl;
		var $service_js;
		var $service_ajax;
		
		var $apikey;			
		var $comments_pingback;
		var $update_service;
		var $hotornot;
		var $hotornot_position;
		var $hotornot_position_opt;
		var $hotornot_location;
		var $hotornot_location_opt;
		
		var $hotornot_dashboard;
		var $hotornot_manageposts;
		
		var $hon_border;
		var $hon_bg;
		var $hon_over;
		var $hon_font;
		var $hon_title_hot;
		var $hon_title_not;
		
		var $version;
		var $php_version;
		var $extensions;
		var $soap_ext;
		var $soap_lib;
		var $comment_type;
		var $soap_server;
		
		var $cache_time;
		var $cache_refresh;
		
		var $symbol_html;
		
		function blogorola() {
			
			$this->url					= 'http://www.blogorola.com';
			$this->path 				= '/wp-content/plugins/blogorola';
			$this->url_img				= get_option('siteurl') . $this->path . '/img' ;
			$this->logo  				= $this->url_img . '/blogorola_logo_v2.png' ;
			$this->symbol_opt			= array (
											'blogorola-symbol-v2_1.png', 'blogorola-symbol-v2_2.png', 'blogorola-symbol-v2_3.png', 'blogorola-symbol-v2_4.png', 'blogorola-symbol-v2_5.png', 'blogorola-symbol-v2_6.png') ; 
			$this->service_url 			= get_option('siteurl') . $this->path . '/service.php' ;
			$this->service_wsdl			= get_option('siteurl') . $this->path . '/service.php?wsdl' ;
			$this->service_js			= get_option('siteurl') . $this->path . '/service.php?js' ;
			$this->service_ajax			= get_option('siteurl') . $this->path . '/service.php?ajax' ;
			
			$this->apikey				= get_option('blogorola_apikey');
			$this->comments_pingback 	= get_option('blogorola_comments_pingback');
			$this->update_service 		= get_option('blogorola_update_service');
			$this->hotornot				= get_option('blogorola_hotornot');
			$this->hotornot_position	= get_option('blogorola_hotornot_position');
			$this->hotornot_position_opt = array (
											'top-left', 'top-right', 'bottom-left', 'bottom-right') ;

			$this->hon_border			= get_option('blogorola_hotornot_border');
			$this->hon_bg				= get_option('blogorola_hotornot_background');
			$this->hon_over				= get_option('blogorola_hotornot_over');
			$this->hon_font				= get_option('blogorola_hotornot_font');
			$this->hon_title_hot		= get_option('blogorola_hotornot_title_hot');
			$this->hon_title_not		= get_option('blogorola_hotornot_title_not');
											
			$this->hotornot_location	= get_option("blogorola_hotornot_location");
			$this->hotornot_location_opt = array(
											'home'=>__('homepage','blogorola'), 'single'=>__('post pages (aka single pages)','blogorola'), 'category'=>__('category pages','blogorola'), 'date'=>__('archive pages','blogorola'), 'search'=>__('search results','blogorola') );
											
			$this->hotornot_dashboard	= get_option("blogorola_hotornot_dashboard");											
			$this->hotornot_manageposts	= get_option("blogorola_hotornot_manageposts");											
											
			$this->symbol				= get_option('blogorola_symbol');
			
			$this->version				= '010001';
			$this->php_version			= phpversion();
			
			$this->extensions			= get_loaded_extensions();
			$this->soap_ext 			= false;
			$this->soap_lib				= false;
			$this->comment_type			= 'blogorola';
			$this->soap_server			= 'http://api.blogorola.com/wsdl';
			
			$this->cache_time			= get_option("blogorola_cache");
			
			if (!preg_match("/^[a-zA-Z0-9]{32}$/", $this->apikey)) { $this->apikey = null; }
			if ($this->comments_pingback != 1) $this->comments_pingback = null;
			if ($this->update_service != 1) $this->update_service = null;
			if ($this->hotornot != 1) $this->hotornot = null;
			if (!in_array($this->symbol, $this->symbol_opt)) { $this->symbol = array_shift($this->symbol_opt); array_unshift($this->symbol_opt, $this->symbol); }
			
			if (!in_array($this->hotornot_position, $this->hotornot_position_opt)) $this->hotornot_position = "bottom-right";
			
			if ($this->hotornot_dashboard != 1) $this->hotornot_dashboard = null;
			if ($this->hotornot_manageposts != 1) $this->hotornot_manageposts = null;
			
			if (in_array("soap", $this->extensions)) $this->soap_ext = true;
			
			if (!isset($this->hon_border) || !preg_match("/^[a-fA-F0-9]{6}$/", $this->hon_border)) $this->hon_border = "cccbc2";
			if (!isset($this->hon_bg) || !preg_match("/^[a-fA-F0-9]{6}$/", $this->hon_bg)) $this->hon_bg = "e6e5dd";
			if (!isset($this->hon_over) || !preg_match("/^[a-fA-F0-9]{6}$/", $this->hon_over)) $this->hon_over = "f8f2f2";
			if (!isset($this->hon_font) || !preg_match("/^[a-fA-F0-9]{6}$/", $this->hon_font)) $this->hon_font = "aeada4";
			
			$this->symbol_html			= '<img src="'.get_option('siteurl').$this->path.'/img/'.$this->symbol.'" width="14" height="12" alt="" title="" />';
		
			if ((int) $blogorola->php_version >= 5 && $this->soap_ext) {
				
			} else if (!$this->soap_ext) {
				@require_once(dirname(__FILE__). '/lib/soap.php');
				if (!class_exists('soap_client') || !class_exists('soap_server')) {
					@require_once('lib/soap.php');
				}
				if (class_exists('soap_client') && class_exists('soap_server')) {
					$this->soap_lib		= true;
				}
			}
			
			$this->cache_refresh = 3600; /* seconds */
			if (!isset($this->cache_time)) {
				$this->cache_time = mktime();
				$this->cache_refresh = -1;
				update_option('blogorola_cache', $this->cache_time);				
			}
			
		}
		
		function internal_array_sort() {
		    $args = func_get_args();
		    $marray = array_shift($args);
		    $msortline = "return(array_multisort(";
			$i = 0;
		    foreach ($args as $arg) {
		        $i++;
		        if (is_string($arg)) {
		            foreach ($marray as $row) {
		                $sortarr[$i][] = $row[$arg];
		            }
		        } else {
		            $sortarr[$i] = $arg;
		        }
		        $msortline .= "\$sortarr[".$i."],";
		    }
		    $msortline .= "\$marray));";
		    eval($msortline);
		    return $marray;
		}
		
		function plugin_basename($file) {
			$file = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', $file);
			return $file;
		}
		
		function get_url_vars($params) {
			$array_vars = array();
			$new_string = '';
		
			$query_string = $_SERVER['QUERY_STRING'];
			$document = $_SERVER['PHP_SELF'];
		
			$vars = explode("&",$query_string);
			$query_vars = array();
	
			foreach ($vars as $key => $value) {
				$e = explode("=",$value);
				if (!empty($value)) {
					$query_vars[$e[0]] = $e[1];
				}
			}
		
			foreach ($params as $parameter_var => $parameter_value) {
					$query_vars[$parameter_var] = $parameter_value;
			}
		
			foreach ($query_vars as $var => $value) {
				if ($value == '') {
					unset($query_vars[$var]);
				}
			}
		
			foreach($query_vars as $var => $value) {
				$new_string .= "&$var=$value";
			}
			$new_string = substr($new_string,1);
			if (strlen($new_string) > 0) {
				$new_string = "?" . $new_string;
				return $new_string;
			}
		
		}
		
		function plugin_insert_api() {
			global $blogorola;
			
			if (preg_match("/^[a-zA-Z0-9]{32}$/", $_POST['apikey'])) {
				
				if ( $blogorola->soap_ext || $blogorola->soap_lib ) {
				
					if ($blogorola->soap_ext) {
						
						$client = new SoapClient($blogorola->soap_server, array('encoding' => 'UTF-8'));
						$res = $client->blogorola_blog_getBlogByUrl($_POST['apikey'], "SI", get_option('siteurl'));
						
					} elseif ($blogorola->soap_lib) {
						
						$client = new soap_client($blogorola->soap_server, true);
						$param = array('apikey' => $_POST['apikey'], 'language' => "SI", 'blog_url' => get_option('siteurl'));
						$res = $client->call('blogorola_blog_getBlogByUrl', $param);			
					}
			
					if ($res['code'] == 200) {
						
						if (isset($res['result']['url']) && !empty($res['result']['url']) && is_string($res['result']['url'])) {
							if (rtrim($res['result']['url'], ' /') == rtrim(get_option('siteurl'), ' /') ) {				
								update_option('blogorola_apikey', $_POST['apikey']);
								$blogorola->apikey = $_POST['apikey'];
							}
						} 
						
					} else {
						$output = $res['error'];
						echo '<div id="insert_api" class="error fade"><p>'.$output.'.</p></div>';
					} 
					
				} else {
					
					echo '<div id="insert_api" class="error fade"><p>'.__('SOAP addon not found!','blogorola').'</p></div>';
				
				}
				
				
			} else 
				echo '<div id="insert_api" class="error fade"><p>'.__('API Key is empty or not valid.','blogorola').'</p></div>';
			
			return true;
		}
		
		function plugin_update() {
			global $blogorola;
		
			if ($blogorola->comments_pingback != $_POST['comments_pingback']) {
				
				if ( $blogorola->soap_ext || $blogorola->soap_lib ) {
				
	    			update_option('blogorola_comments_pingback', (isset($_POST['comments_pingback']) && $_POST['comments_pingback'] == 1 ? 1 : null));
					$blogorola->comments_pingback = (isset($_POST['comments_pingback']) && $_POST['comments_pingback'] == 1 ? 1 : null);
						
					if ($blogorola->soap_ext) {
							
						$client = new SoapClient($blogorola->soap_server, array('encoding' => 'UTF-8'));
						if (isset($_POST['comments_pingback']) && $_POST['comments_pingback'] == 1) {
							$res = $client->blogorola_comment_enablePingback($blogorola->apikey, $blogorola->service_wsdl);
						} else {
							$res = $client->blogorola_comment_disablePingback($blogorola->apikey, $blogorola->service_wsdl);				
						}
						
					} elseif ($blogorola->soap_lib) {
						
						$client = new soap_client($blogorola->soap_server, true);
						$param = array('apikey' => $blogorola->apikey, 'soap_url' => $blogorola->service_wsdl);
						if (isset($_POST['comments_pingback']) && $_POST['comments_pingback'] == 1) {
							$res = $client->call('blogorola_comment_enablePingback', $param);
						} else {
							$res = $client->call('blogorola_comment_disablePingback', $param);			
						}
						
					}
					
					if ($res['code'] != 200) {
						echo '<div id="insert_api" class="error fade"><p>'.$res['error'].'.</p></div>';
					} 
				
				} else {
					
					echo '<div id="insert_api" class="error fade"><p>'.__('SOAP addon not found!','blogorola').'</p></div>';
					
				}
	    	}
	    	
	    	if ($blogorola->update_service != $_POST['update_service']) {
	    		update_option('blogorola_update_service', (isset($_POST['update_service']) && $_POST['update_service'] == 1 ? 1 : null));
				$blogorola->update_service = (isset($_POST['update_service']) && $_POST['update_service'] == 1 ? 1 : null);				
	    	}
	    	
	    	if ($blogorola->symbol != $_POST['symbol'] && in_array($_POST['symbol'], $blogorola->symbol_opt)) {
	    		update_option('blogorola_symbol', $_POST['symbol']);
	    		$blogorola->symbol = $_POST['symbol'];
	    	}
	    	
	    	if ($blogorola->hotornot != $_POST['hotornot']) {
	    		update_option('blogorola_hotornot', $_POST['hotornot']);
	    		$blogorola->hotornot = $_POST['hotornot'];
	    	}
	    	
	    	if ($blogorola->hotornot_dashboard != $_POST['hotornot_dashboard']) {
	    		update_option('blogorola_hotornot_dashboard', $_POST['hotornot_dashboard']);
	    		$blogorola->hotornot_dashboard = $_POST['hotornot_dashboard'];
	    	}
	    	
	    	if ($blogorola->hotornot_manageposts != $_POST['hotornot_manageposts']) {
	    		update_option('blogorola_hotornot_manageposts', $_POST['hotornot_manageposts']);
	    		$blogorola->hotornot_manageposts = $_POST['hotornot_manageposts'];
	    	}
	    	
	    	if ($blogorola->hon_border != $_POST['hon_border'] && preg_match("/^[a-fA-F0-9]{6}$/", $_POST['hon_border'])) {
	    		update_option('blogorola_hotornot_border', $_POST['hon_border']);
	    		$blogorola->hon_border = $_POST['hon_border'];
	    	}
	    	
	    	if ($blogorola->hon_bg != $_POST['hon_bg'] && preg_match("/^[a-fA-F0-9]{6}$/", $_POST['hon_bg'])) {
	    		update_option('blogorola_hotornot_background', $_POST['hon_bg']);
	    		$blogorola->hon_bg = $_POST['hon_bg'];
	    	}
	    	
	    	if ($blogorola->hon_over != $_POST['hon_over'] && preg_match("/^[a-fA-F0-9]{6}$/", $_POST['hon_over'])) {
	    		update_option('blogorola_hotornot_over', $_POST['hon_over']);
	    		$blogorola->hon_over = $_POST['hon_over'];
	    	}
	    	
	    	if ($blogorola->hon_font != $_POST['hon_font'] && preg_match("/^[a-fA-F0-9]{6}$/", $_POST['hon_font'])) {
	    		update_option('blogorola_hotornot_font', $_POST['hon_font']);
	    		$blogorola->hon_font = $_POST['hon_font'];
	    	}
	    	
	    	if ($blogorola->hon_title_hot != $_POST['hon_title_hot']) {
	    		update_option('blogorola_hotornot_title_hot', trim(strip_tags($_POST['hon_title_hot'])));
	    		$blogorola->hon_title_hot = $_POST['hon_title_hot'];
	    	}
	    	
	    	if ($blogorola->hon_title_not != $_POST['hon_title_not']) {
	    		update_option('blogorola_hotornot_title_not', trim(strip_tags($_POST['hon_title_not'])));
	    		$blogorola->hon_title_not = $_POST['hon_title_not'];
	    	}
	    	
	    	if ($blogorola->hotornot_position != $_POST['hotornot_position'] && in_array($_POST['hotornot_position'], $blogorola->hotornot_position_opt)) {
	    		update_option('blogorola_hotornot_position', $_POST['hotornot_position']);
	    		$blogorola->hotornot_position = $_POST['hotornot_position'];
	    	}
	
	    	if (isset($_POST['location']) && is_array($_POST['location'])) {
	    		foreach ($blogorola->hotornot_location_opt as $k => $l) {
	    			if (isset($_POST['location'][$k]) &&  $_POST['location'][$k] == 1 && array_key_exists($k, $_POST['location'])) {
	    				$blogorola->hotornot_location[$k] = 1;
	    			} else $blogorola->hotornot_location[$k] = 0;
	    		}
	    		update_option('blogorola_hotornot_location', $blogorola->hotornot_location);
	    	}
	    	
	    	echo '<div id="insert_api" class="updated fade"><p>'.__('Your settings have been saved!','blogorola').'</p></div>';
	    	
	    	return true;			
		}
		
		function blog_ping($post_ID) {
			global $wpdb, $blogorola;
		
			if ($blogorola->soap_ext) {
				
				$client = new SoapClient($blogorola->soap_server, array('encoding' => 'UTF-8'));
				$res = $client->blogorola_blog_updatePing($blogorola->apikey, get_option('siteurl'));
				
			} elseif ($blogorola->soap_lib) {
				
				$client = new soap_client($blogorola->soap_server, true);
				$param = array('apikey' => $blogorola->apikey, 'blog_url' => get_option('siteurl'));
				$res = $client->call('blogorola_blog_updatePing', $param);
				
			}
			
			return $post_ID;
		}
		
		function blog_ping_activate() {
			global $wpdb, $blogorola;
		
			if ($blogorola->soap_ext) {
				
				$client = new SoapClient($blogorola->soap_server, array('encoding' => 'UTF-8'));
				$res = $client->blogorola_blog_updatePing('', get_option('siteurl'));
				
			} elseif ($blogorola->soap_lib) {
				
				$client = new soap_client($blogorola->soap_server, true);
				$param = array('apikey' => '', 'blog_url' => get_option('siteurl'));
				$res = $client->call('blogorola_blog_updatePing', $param);
				
			}
		}
		
		function comment_pingback($comment_ID) {
			global $wpdb, $blogorola;
			
			$comment = get_comment($comment_ID);
			$post = get_post($comment->comment_post_ID);
					
			if ( $comment->comment_approved == '1' ) {	
				
				if ($blogorola->soap_ext) {
					
					$client = new SoapClient($blogorola->soap_server, array('encoding' => 'UTF-8'));
					$res = $client->blogorola_comment_insertComment($blogorola->apikey, $post->guid, $comment->comment_author, $comment->comment_author_email, $comment->comment_author_url, $comment->comment_content);
					
				} elseif ($blogorola->soap_lib) {
					
					$client = new soap_client($blogorola->soap_server, true);
					$param = array('apikey' => $blogorola->apikey, 'post_url' => $post->guid, 'author' => $comment->comment_author, 'author_email' => $comment->comment_author_email, 'author_url' => $comment->comment_author_url, 'comment' => $comment->comment_content);
					$res = $client->call('blogorola_comment_insertComment', $param);
				}
			}
			
			return $comment_ID;
		}
		
		function comment_insert($guid, $comment_author, $comment_author_email, $comment_author_url, $comment_content) {
			global $blogorola, $wpdb;
			
			$post = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE guid = '$guid'");
			$comment_post_ID = (int) $post->ID;
			if (!isset($comment_post_ID) || !is_numeric($comment_post_ID) || !$comment_post_ID > 0) {
				return __('Error 404 - Not Found','blogorola');
			}
			
			$status = $wpdb->get_row("SELECT post_status, comment_status FROM $wpdb->posts WHERE ID = '$comment_post_ID'");
			if ( empty($status->comment_status) ) {
				return __('Error 404 - Not Found','blogorola');
			} elseif ( 'closed' ==  $status->comment_status ) {
				return __('Sorry, comments are closed for this item','blogorola');
			} elseif ( 'draft' == $status->post_status ) {
				return __('Spammer!','blogorola');
			}
			
			$comment_author       = trim($comment_author);
			$comment_author_email = trim($comment_author_email);
			$comment_author_url   = trim($comment_author_url);
			$comment_content      = trim($comment_content);

			if ( get_option('comment_registration') )
				return __('Sorry, you must be logged in to post a comment','blogorola');
			
			$comment_type 		  = $blogorola->comment_type;
										
			if ( get_option('require_name_email') ) {
				if ( 6 > strlen($comment_author_email) || '' == $comment_author )
					return __('Error: please fill the required fields (name, email)','blogorola');
				elseif ( !is_email($comment_author_email))
					return __('Error: please enter a valid email address','blogorola');
			}
			
			if ( '' == $comment_content )
				return __('Error: please type a comment','blogorola');
				
			$user_ID = 0;
				
			$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'user_ID');

			$comment_id = wp_new_comment( $commentdata );
			
			return __('Ok','blogorola');
		}
			
		function comment_symbol($html) {
			global $blogorola, $comment;
			
			if (isset($comment->comment_type) && $comment->comment_type == $blogorola->comment_type) {
				return $blogorola->symbol_html . $html;
			} 
			return $html;
		}
		
		function post_vote($guid, $vote) {
			global $blogorola;

			if ( $blogorola->soap_ext || $blogorola->soap_lib ) {
												
				if ($blogorola->soap_ext) {
				
					$client = new SoapClient($blogorola->soap_server, array('encoding' => 'UTF-8'));
					$res = $client->blogorola_post_setVote($blogorola->apikey, $guid, $vote, $_SERVER['REMOTE_ADDR']);
					
				} elseif ($blogorola->soap_lib) {
					
					$client = new soap_client($blogorola->soap_server, true);
					$param = array('apikey' => $blogorola->apikey, 'post_url' => $guid, 'vote' => $vote, 'remote_ip' => $_SERVER['REMOTE_ADDR']);
					$res = $client->call('blogorola_post_setVote', $param);
					
				}
			
				if ($res['code'] == 200) {
					return $res['result'];
				}
				
			}
			
			 
			return false;					
		}
		
		function post_hotornot_display($hot=null, $not=null) {
			global $blogorola, $post;
			
			if($blogorola->hotornot_position == "top-left" || $blogorola->hotornot_position == "bottom-left") { 
				$float = 'float: left;';
			} else if($blogorola->hotornot_position == "top-right" || $blogorola->hotornot_position == "bottom-right") {
				$float = 'float: right;';
    		}
    		
    		switch ($blogorola->hotornot_position) {
    			case "top-left" : $margin = "5px 10px 10px 0"; break;
    			case "top-right" : $margin = "5px 0 10px 10px"; break;
    			case "bottom-left" : $margin = "10px 10px 5px 0"; break;
    			case "bottom-right" : $margin = "10px 0 5px 10px"; break;
    		}
			
			$code  = "\n".'<!-- blogorola hotornot -->'."\n";
			$code .= '<div class="blogorola_hotornot" style="'.$float.'border:1px solid #'.$blogorola->hon_border.'; width:116px; height:20px; padding:1px; margin:'.$margin.'; white-space:nowrap;background:#'.$blogorola->hon_bg.'">'."\n";
			
			$code .= '<div class="blogorola_hotornot_logo" style="float:left;border-right:1px solid #'.$blogorola->hon_border.';width:20px;height:18px;padding:1px;margin:0;white-space:nowrap;cursor:pointer;background:#'.$blogorola->hon_bg.'" onmouseover="this.style.background = \'#'.$blogorola->hon_over.'\';" onmouseout="this.style.background = \'#'.$blogorola->hon_bg.'\';" onclick="window.open(\''.$blogorola->url.'\');" >';
				$code .= '<span style="width:19px;padding:0;margin:0;float:left;text-align:center;"><img style="padding:0;margin:0;border:none;" src="'.$blogorola->url_img.'/hon_logo_v2.png" alt="Blogorola" title="Blogorola" border="0" width="19" height="18" /></span>';
			$code .= '</div>'."\n";
			
			$code .= '<div class="blogorola_hotornot_hot" id="blogorola_hon_hot_div_'.$post->ID.'" title="'.stripslashes($blogorola->hon_title_hot).'" style="float:left;border-right:1px solid #'.$blogorola->hon_border.';width:44px;height:18px;padding:1px;margin:0;white-space:nowrap;cursor:pointer;background:#'.$blogorola->hon_bg.'" onmouseover="this.style.background = \'#'.$blogorola->hon_over.'\';" onmouseout="this.style.background = \'#'.$blogorola->hon_bg.'\';" onclick="if(typeof _blogorola_make_request == \'function\') _blogorola_make_request(\'id='.$post->ID.'&amp;vote=1\', \'blogorola_hon_hot_cnt_'.$post->ID.'\', \''.$post->ID.'\');" >';
				$code .= '<span id="blogorola_hon_hot_cnt_'.$post->ID.'" style="width:26px;padding:4px 2px 0 0;margin:0;float:left;text-align:right;font: 9px Arial; color:#'.$blogorola->hon_font.';">'.($hot!=null?$hot:"0").'%</span>';
				$code .= '<span style="width:16px;padding:0;margin:0;float:left;text-align:right;"><img style="padding:0;margin:0;border:none;" src="'.$blogorola->url_img.'/hon_up.png" alt="'.stripslashes($blogorola->hon_title_hot).'" title="'.stripslashes($blogorola->hon_title_hot).'" border="0" width="16" height="18" /></span>';
			$code .= '</div>'."\n";
			
			$code .= '<div class="blogorola_hotornot_not" id="blogorola_hon_not_div_'.$post->ID.'" title="'.stripslashes($blogorola->hon_title_not).'" style="float:left;width:44px;height:18px;padding:1px;margin:0;white-space:nowrap;cursor:pointer;background:#'.$blogorola->hon_bg.'" onmouseover="this.style.background = \'#'.$blogorola->hon_over.'\';" onmouseout="this.style.background = \'#'.$blogorola->hon_bg.'\';" onclick="if(typeof _blogorola_make_request == \'function\') _blogorola_make_request(\'id='.$post->ID.'&amp;vote=0\', \'blogorola_hon_not_cnt_'.$post->ID.'\', \''.$post->ID.'\');" >';
				$code .= '<span style="width:16px;padding:0;margin:0;float:left;text-align:right;"><img style="padding:0;margin:0;border:none;" src="'.$blogorola->url_img.'/hon_down.png" alt="'.stripslashes($blogorola->hon_title_not).'" title="'.stripslashes($blogorola->hon_title_not).'" border="0" width="16" height="18" /></span>';
				$code .= '<span id="blogorola_hon_not_cnt_'.$post->ID.'" style="width:26px;padding:4px 0 0 2px;margin:0;float:left;text-align:left;font: 9px Arial; color:#'.$blogorola->hon_font.';">'.($not!=null?$not:"0").'%</span>';				
			$code .= '</div>';
						
			$code .= '</div>'."\n";
			
			if($blogorola->hotornot_position == "bottom-left" || $blogorola->hotornot_position == "bottom-right") {
				$code .= '<div style="clear:both;margin:0;padding:0;"></div>'."\n";				
			}
			
			$code .= '<!-- blogorola hotornot -->'."\n";
			
			return $code;
		
		}
		
		function post_hotornot($content) {
			global $blogorola, $post;

			$hot = get_post_meta($post->ID, 'blogorola_hot', $single = true);
			$not = get_post_meta($post->ID, 'blogorola_not', $single = true);
			
			if (!preg_match('/^[0-9]{1,3}$/',$hot)) {
				$hot = 0; add_post_meta($post->ID, 'blogorola_hot', 0); }
			if (!preg_match('/^[0-9]{1,3}$/',$not)) {
				$not = 0; add_post_meta($post->ID, 'blogorola_not', 0); }
					
			if((is_home() && $blogorola->hotornot_location['home'] == 1) ||
    			(is_single() && $blogorola->hotornot_location['single'] == 1) ||
				(is_category() && $blogorola->hotornot_location['category'] == 1) ||
				(is_date() && $blogorola->hotornot_location['date'] == 1) ||
				(is_search() && $blogorola->hotornot_location['search'] == 1)
   				) {
   				if($blogorola->hotornot_position == "top-left" || $blogorola->hotornot_position == "top-right") {
    	    		$content = $blogorola->post_hotornot_display($hot,$not).$content;
     			} else if($blogorola->hotornot_position == "bottom-left" || $blogorola->hotornot_position == "bottom-right") {
	    			$content .= $blogorola->post_hotornot_display($hot,$not);
				}
    		}
			
			return $content;			
		}
		
		function post_hotornot_head() {
			global $blogorola;

			?><script src="<?php echo $blogorola->service_js ?>" type="text/javascript"></script><?php
		}
		
		function feed_normalize_links() {
			
			remove_filter('template_redirect', 'ol_feed_redirect');
			remove_filter('init','ol_check_url');
			
		}
		
		function admin_manage_posts_columns($posts_columns) {
			$result = array();
			foreach ($posts_columns as $key => $value) {
				if ($key == 'title') {
					$result[$key] = $value;
					$result['hotornot'] = "Hot'or'Not";
				} else $result[$key] = $value;
			}
			return $result;
		}

		function admin_manage_posts_custom_column($column_name) {
			if ($column_name == 'hotornot') {
				global $post, $blogorola;
				$hot = get_post_meta($post->ID, 'blogorola_hot', $single = true);
				if (!preg_match('/^[0-9]{1,3}$/',$hot)) $hot = 0;
				$not = get_post_meta($post->ID, 'blogorola_not', $single = true);
				if (!preg_match('/^[0-9]{1,3}$/',$not)) $not = 0;
				echo $hot . "%&#183;" . $not . "%";
			}
		}
		
		function admin_activity_box() {
			global $blogorola, $wpdb;
			
			$posts_meta = $wpdb->get_results("SELECT m.post_id, m.meta_key, m.meta_value, p.post_title, p.guid FROM $wpdb->postmeta m, $wpdb->posts p WHERE (m.meta_key = 'blogorola_hot' OR m.meta_key = 'blogorola_not') AND m.post_id = p.ID AND p.post_type = 'post'");
			if ($posts_meta) {
				$hotornot_list = array();
				foreach ($posts_meta as $item) {
					if (!preg_match('/^[0-9]{1,3}$/', $item->meta_value)) $item->meta_value = 0;
					$hotornot_list[$item->post_id][$item->meta_key] = $item->meta_value;
					$hotornot_list[$item->post_id]['title'] = $item->post_title;
					$hotornot_list[$item->post_id]['guid'] = $item->guid;
				}
			}
			if ($hotornot_list && is_array($hotornot_list)) {
				$hot_tmp = $hotornot_list; $hotornot_list = $blogorola->internal_array_sort($hot_tmp, 'blogorola_hot', SORT_DESC); unset($hot_tmp);
				if (count($hotornot_list) > 5)
					$hotornot_list = array_slice($hotornot_list, 0, 5);
				echo '<div><h3>'.__('Hot\'or\'Not','blogorola').'</h3>';
				echo '<ul>';
				foreach ($hotornot_list as $item) {				
					echo '<li><a href="'. $item['guid'] .'">'. $item['title'] .'</a> ('. $item['blogorola_hot'] .'%&#183;'. $item['blogorola_not'] .'%)</small></li>';
				}
				echo '</ul>';
				echo '</div>';					
			}
		}
		
		function admin_menu() {		
			add_action('admin_menu', array('blogorola', 'admin_options_page'));
		}
		
		function admin_options_page() {
		    if (function_exists('add_options_page')) {
				add_options_page('Blogorola', 'Blogorola', 10, basename(__FILE__), array('blogorola', 'admin_options_subpanel'));
		    }
		}
		
		function admin_options_subpanel() {
			
			global $blogorola;

			if (isset($_POST['post_insert_api'])) {
				$blogorola->plugin_insert_api();
		    }
		    
		    if (isset($_POST['post_update_plugin'])) {	
				$blogorola->plugin_update();
		    }
		
			?>
			
			<?php
			$_show_stats = false;
			if (isset($_GET['show']) && $_GET['show'] == 'stats') {
				$_show_stats = true;				
			}
							
		?>
	
		<script src="http://api.blogorola.com/wordpress?a=upgrade&amp;v=<?php echo $blogorola->version;?>&amp;r=<?php bloginfo('siteurl');?>" type="text/javascript"></script>
		
		<div class="wrap">
		<form method="post">
		<h2>Blogorola<?php echo ( $_show_stats ? ' '.__('statistics','blogorola') : '');?></h2>
		
			<fieldset class="options" style="padding-bottom:0;">
			<p class="submit" style="margin-bottom:0;">
				<?php if ($blogorola->apikey == null) { ?>
				<a style="border-bottom: 0;" href="http://www.blogorola.com"><img src="<?php echo $blogorola->logo; ?>" alt="Blogorola" border="0"></a></p>
				<?php } else { 
					if ($_show_stats) { ?>
						<input type="button" name="options" value="&laquo <?php _e('Options','blogorola') ?> " onclick="window.location='<?php echo $_SERVER['PHP_SELF'] . $blogorola->get_url_vars( array('page'=> basename(__FILE__), 'show' => '') ); ?>';"/>	
					<?php } else { ?>
						<input type="button" name="statistics" value="<?php _e('Statistics','blogorola') ?> &raquo;" onclick="window.location='<?php echo $_SERVER['PHP_SELF'] . $blogorola->get_url_vars( array('page'=> basename(__FILE__), 'show' => 'stats') ); ?>';"/>							
					<?php } ?>
				<?php } ?>
			</fieldset>
		
			<?php if ($blogorola->apikey == null) { 
				/**
				 * API KEY
				 */
				?>
			
				<?php
					$config_found = false;
					if (file_exists(dirname(__FILE__). '/../../../wp-config.php'))
						$config_found = true;
					if (file_exists('../../../wp-config.php'))
						$config_found = true;
				?>
			
				<fieldset class="options">
				<p><?php echo sprintf(__('<a href="%s">Blogorola</a> is a blog aggregate and community portal, aimed at connecting bloggers from the former Yugoslavia. This plugin was put together to help you implement all the useful features of Blogorola into your blog as easily as possible.','blogorola'), 'http://www.blogorola.com'); ?>
					<br /><br /><?php _e('We hope you enjoy it. Thanks for participating in the Blogorola community.','blogorola'); ?><br /><br />
				</p>
				</fieldset>
			
				<fieldset class="options"><!--<legend>&nbsp;</legend>-->
				<table class="optiontable">
					<tr valign="top">
						<th scope="row"><?php _e('API Key','blogorola'); ?>:</th>
						<td>
							<input type="text" name="apikey" value="" maxlength="32" size="32">
							<br /><br />
							<?php echo __('The Blogorola plugin utilizes Blogorola\'s API service. To be able to use it, you need to obtain a key. We use this to verify and track API usage.','blogorola'); ?>
						</td>
					</tr>
				</table>
				
				<p><?php echo __('Where can I get API Key?','blogorola'); ?>
					<ul>
					<li><?php echo sprintf(__('Create a user profile in <a href="%s">registration</a> page','blogorola'),'http://www.blogorola.com/si/registracija');?></li>
					<li><?php echo sprintf(__('Claim your blog, follow this <a href="%s">guide</a>','blogorola'),'http://www.blogorola.com/si/prevzem-mojega-bloga');?></li>
					<li><?php echo sprintf(__('While logged in, check your blog\'s detail page (<a href="%s">example</a>)','blogorola'),'http://www.blogorola.com/si/blog/1904');?></li>
					<li><?php echo __('Your unique API Key will appear below','blogorola');?></li>
					</ul>
				</p>
				
				<?php if ( ($blogorola->soap_ext || $blogorola->soap_lib) && $config_found ) { ?>
				<p class="submit">
				    <input type="submit" name="post_insert_api" value="<?php _e('Save API Key','blogorola') ?> &raquo;" />
				</p>
				<?php } ?>
				</fieldset>
			
			<?php } else { ?> 
			
				<?php if (!$_show_stats) { 
				/**
				 * OPTIONS
				 */	
				?>
			
				<fieldset class="options"><!--<legend>Options</legend>-->
				<table class="optiontable">
					<tr valign="top">
						<th scope="row"><?php _e('Comment Synchronization','blogorola'); ?>:</th>
						<td style="padding-top:10px;">
							<input type="checkbox" name="comments_pingback" value="1" <?php echo ($blogorola->comments_pingback==1)?"checked ":""; ?>> <strong><?php _e('Activate','blogorola'); ?></strong>
							<br /><br />
							<?php _e('All comments are synchronized between Blogorola and your blog. So any comments posted on your blog will be displayed on Blogorola and vice-versa. This enables much easier discussions between readers (members of the Blogorola community).','blogorola'); ?>
							<br />
							<?php _e('Comments received from Blogorola will be marked with a small icon before author\'s name.','blogorola'); ?>
							<br /><br /><strong><?php _e('Available icons','blogorola'); ?>:</strong><br />
							<?php foreach ($blogorola->symbol_opt as $s) { ?>
							<input type="radio" name="symbol" value="<?php echo $s;?>" <?php if($s == $blogorola->symbol) echo "checked"; ?>><img src="<?php echo get_option('siteurl').$blogorola->path.'/img/'.$s?>" width="14" height="12" alt="" title="" />&nbsp;&nbsp;&nbsp;
							<?php } ?>
							<br /><br />
							<?php _e('If the icon doesn\'t appear (i.e. if you have a non-conform WordPress theme installed), paste the code below in the "comments.php" file in <em>Theme Editor</em>. Place the code just before the comment author\'s name.','blogorola'); ?>
							<br /><br />
							<code>&lt;?php if(function_exists('is_blogorola_comment')) is_blogorola_comment(); ?&gt;</code> 
							
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">Hot'or'Not:</th>
						<td style="padding-top:10px;">
							<input type="checkbox" name="hotornot" value="1" <?php echo ($blogorola->hotornot==1)?"checked ":""; ?>> <strong><?php _e('Activate','blogorola'); ?></strong>
							<br /><br />
							<?php _e('This will display a Hot\'or\'Not voting toolbar in your post detail. Hot\'or\'Not is Blogorola\'s rating system, which readers can use to rate your posts. Posts with good ratings are read more often and are featured on Blogorola\'s website.','blogorola'); ?>
							<br /><br />
							<table>
								<tr>
									<td valign="top" style="width:200px;">
										<strong><?php _e('Colors (hex)','blogorola'); ?>: </strong><br />
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td style="margin:0;padding:0;" align="right"><?php _e('Border','blogorola'); ?>&nbsp;</td>
												<td style="margin:0;padding:0;"><input type="text" name="hon_border" value="<?php echo $blogorola->hon_border;?>" maxlength="6" size="6"></td>
											</td>
											<tr>
												<td style="margin:0;padding:0;" align="right"><?php _e('Background','blogorola'); ?>&nbsp;</td>
												<td style="margin:0;padding:0;"><input type="text" name="hon_bg" value="<?php echo $blogorola->hon_bg;?>" maxlength="6" size="6"></td>
											</tr>
											<tr>
												<td style="margin:0;padding:0;" align="right"><?php _e('On mouse over','blogorola'); ?>&nbsp;</td>
												<td style="margin:0;padding:0;"><input type="text" name="hon_over" value="<?php echo $blogorola->hon_over;?>" maxlength="6" size="6"></td>
											</tr>
											<tr>
												<td style="margin:0;padding:0;" align="right"><?php _e('Font','blogorola'); ?>&nbsp;</td>
												<td style="margin:0;padding:0;"><input type="text" name="hon_font" value="<?php echo $blogorola->hon_font;?>" maxlength="6" size="6"></td>
											</tr>
										</table>
									</td>
									<td valign="top">
										<strong><?php _e('Preview','blogorola'); ?>: </strong>
										<table>
											<tr>
												<td style="margin:0;padding:0;">
													<div style="border:1px solid #<?php echo $blogorola->hon_border;?>;width:44px;height:18px;padding:1px;margin:0;white-space:nowrap;cursor:pointer;background:#<?php echo $blogorola->hon_bg; ?>" onmouseover="this.style.background = '#<?php echo $blogorola->hon_over; ?>';" onmouseout="this.style.background = '#<?php echo $blogorola->hon_bg; ?>';" >
														<span style="width:26px;padding:4px 2px 0 0;margin:0;float:left;color:#<?php echo $blogorola->hon_font;?>;font: 9px Arial; text-align:right;">100%</span>
														<span style="width:16px;padding:0;margin:0;float:left;text-align:right;"><img style="padding:0;margin:0;border:none;" src="<?php echo $blogorola->url_img.'/hon_up.png'; ?>" alt="" title="" border="0" width="16" height="18" /></span>
													</div>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<strong><?php _e('Titles (on mouse over)','blogorola'); ?>: </strong><br />
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td style="margin:0;padding:0;" align="right">Hot&nbsp;</td>
												<td style="margin:0;padding:0;"><input type="text" name="hon_title_hot" value="<?php echo stripslashes($blogorola->hon_title_hot);?>" ></td>
											</td>
											<tr>
												<td style="margin:0;padding:0;" align="right">Not&nbsp;</td>
												<td style="margin:0;padding:0;"><input type="text" name="hon_title_not" value="<?php echo stripslashes($blogorola->hon_title_not);?>" ></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<br />
							<strong><?php _e('Position','blogorola'); ?>: </strong> <select name="hotornot_position">
								<?php foreach ($blogorola->hotornot_position_opt as $p) { ?>
									<option value="<?php echo $p; ?>" <?php if ($p == $blogorola->hotornot_position) echo "selected"; ?> ><?php echo $p; ?></option>
								<?php } ?>
							</select> <?php _e('(in regard to the post body)','blogorola'); ?><br /><br />
							<?php _e('Select the pages where you want to have the Hot\'or\'Not toolbar displayed','blogorola'); ?>:<br />
							<?php foreach ($blogorola->hotornot_location_opt as $k => $l) { ?>
							<input type="checkbox" name="location[<?php echo $k; ?>]" value="1" <?php if (isset($blogorola->hotornot_location[$k]) && $blogorola->hotornot_location[$k] == 1) echo "checked"; ?> > <?php echo $l; ?><br />
							<?php } ?>
							<br />
							<?php _e('Display Hot\'or\'Not percentage in','blogorola'); ?>:<br />
							<input type="checkbox" name="hotornot_dashboard" value="1" <?php echo ($blogorola->hotornot_dashboard==1)?"checked ":""; ?>> <?php _e('dashboard','blogorola'); ?><br />
							<input type="checkbox" name="hotornot_manageposts" value="1" <?php echo ($blogorola->hotornot_manageposts==1)?"checked ":""; ?>> <?php _e('manage posts','blogorola'); ?><br />
							<br />
							<?php _e('You can also uncheck all of the options above and position the toolbar where you think it\'s best for you. Paste this code anywhere you like.','blogorola'); ?>
							<br /><br />
							<code>&lt;?php if(function_exists('show_blogorola_hotornot')) show_blogorola_hotornot(); ?&gt;</code>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Update Services','blogorola'); ?>:</th>
						<td style="padding-top:10px;">
							<input type="checkbox" name="update_service" value="1" <?php echo ($blogorola->update_service==1)?"checked ":""; ?>> <strong><?php _e('Activate','blogorola'); ?></strong>
							<br /><br />
							<?php _e('Our update service enables your WordPress blog to automatically notify Blogorola of a new post as soon as you publish it. This way it will appear on Blogorola almost immediately instead of waiting for the spider to crawl your blog for new posts.','blogorola'); ?>
							<br /><br />
							<?php _e('You can also add our server to your <em>Options &raquo; Writing &raquo; Update Services</em> list. Simply add line below.','blogorola'); ?>
							<br /><br />
							<code>http://api.blogorola.com/ping</code>
						</td>
					</tr>
					
				</table>
				<p class="submit">
				    <input type="submit" name="post_update_plugin" value="<?php _e('Update options','blogorola') ?> &raquo;" />
				</p>
				</fieldset>
			
				<fieldset class="options">
					<?php echo sprintf(__('If you don\'t have the standard Kubrick theme or a Kubrick-based theme installed, you might run into some problems - or not. For any questions, bug reports and feature requests (or donations, for that matter) visit <a href="%s">author\'s</a> page.','blogorola'), 'http://ma.tija.cc/blogorola-wordpress-plugin/'); ?>
				</fieldset>
				
			<?php } else { 
				/**
				 * STATISTICS
				 */

				// blog
				if ( ( $blogorola->cache_time + $blogorola->cache_refresh ) < mktime() ) {
					
					if ($blogorola->soap_ext) {
							
						$client = new SoapClient($blogorola->soap_server, array('encoding' => 'UTF-8'));
						$res_blog = $client->blogorola_blog_getBlogByUrl($blogorola->apikey, "SI", get_option('siteurl'));
						$res_post = $client->blogorola_blog_getPosts($blogorola->apikey, "SI", get_option('siteurl'));
						
					} elseif ($blogorola->soap_lib) {
						
						$client = new soap_client($blogorola->soap_server, true);
						$param = array('apikey' => $blogorola->apikey, 'language' => "SI", 'blog_url' => get_option('siteurl'));
						$res_blog = $client->call('blogorola_blog_getBlogByUrl', $param);			
						$res_post = $client->call('blogorola_blog_getPosts', $param);			
					}
					
					update_option('blogorola_cache_stat_blog', $res_blog);
					update_option('blogorola_cache_stat_post', $res_post);
					$blogorola->cache_time = mktime();
					update_option('blogorola_cache', $blogorola->cache_time);
					
				} else {
					
					$res_blog = get_option('blogorola_cache_stat_blog');
					$res_post = get_option('blogorola_cache_stat_post');
					
				}
				?>																	
				<table class="widefat">
				<tr>
					<th colspan="6"><h3><?php _e('Blog','blogorola'); ?></h3></th>
				</tr>
				<?php 
				if ($res_blog['code'] == 200) {
					?>
					<tr class="thead">
						<th><?php _e('URL','blogorola'); ?></th><th><?php _e('Clicks','blogorola'); ?></th><th><?php _e('Rate','blogorola'); ?></th><th><?php _e('Votes','blogorola'); ?></th><th><?php _e('Language','blogorola'); ?></th><th><?php _e('Registered','blogorola'); ?></th>				
					</tr>
					<tr class="alternate">
						<td><a href="<?php echo $res_blog['result']['blogorola_url']; ?>" target="_blank"><?php echo $res_blog['result']['title']; ?></a></td>
						<td><?php echo $res_blog['result']['clicks']; ?></td>
						<td><?php echo $res_blog['result']['rate']; ?></td>
						<td><?php echo $res_blog['result']['votes']; ?></td>
						<td><?php echo $res_blog['result']['language']; ?></td>
						<td><?php echo date(get_option('date_format'), strtotime($res_blog['result']['registered'])); ?></td>
					</tr>
				<?php
					
				} else { ?>
					<tr>
						<td colspan="6"><?php echo $res_blog['error']; ?></td>
					</tr>
					<?php
				}
				?> 			
				</table>
				
				<table class="widefat">
				<tr>
					<th colspan="6"><h3><?php _e('Posts','blogorola'); ?></h3></th>
				</tr>
				<?php 
				if ($res_post['code'] == 200) {
					?>
					<tr class="thead">
						<th><?php _e('Title','blogorola'); ?></th><th><?php _e('Author','blogorola'); ?></th><th><?php _e('Votes','blogorola'); ?></th><th>Hot'or'Not</th><th><?php _e('Comments','blogorola'); ?></th><th><?php _e('Date','blogorola'); ?></th>			
					</tr>
					<?php 
					if (!empty($res_post['result'])) {
						$alter = true;
						foreach ( $res_post['result'] as $value) {
						?>					
						<tr<?php if ($alter) echo ' class="alternate"';?>>	
							<td><a href="<?php echo $value['blogorola_url']; ?>" target="_blank"><?php echo $value['title']; ?></a></td>
							<td><?php echo $value['author']; ?></td>
							<td><?php echo $value['votes']; ?></td>
							<td><?php echo $value['hot'] . '%&#183;' . $value['not'] . '%'; ?></td>
							<td><?php echo $value['comments']; ?></td>
							<td><?php echo date(get_option('date_format'), strtotime($value['date'])) . ' at ' . date(get_option('time_format'), strtotime($value['date'])); ?></td>
						</tr>
					<?php
						$alter = !$alter;
						} 
					} else { ?>
					<tr>
						<td colspan="6"><?php _e('No posts found.','blogorola'); ?></td>
					</tr>
					<?php } ?>
				<?php
					
				} else { ?>
					<tr>
						<td colspan="6"><?php echo $res_post['error']; ?></td>
					</tr>
					<?php
				}
				?> 			
				</table>
		
				<fieldset class="options" style="padding-bottom:0;">
				<p align="center" style="margin-bottom:0;"><small><?php _e('Next refresh','blogorola'); ?>: <?php echo date(get_option('date_format'), $blogorola->cache_time + $blogorola->cache_refresh) . ' at '. date(get_option('time_format'), $blogorola->cache_time + $blogorola->cache_refresh); ?></small></p>
				</fieldset>
				
				<?php 
				// end stats
				} ?>
				
			<?php } ?>
			
			<?php if ($blogorola->apikey == null) { ?>
			
			<p align="center" style="padding:0; margin:0;"><small style="color:#ddd;"><?php echo __('Debugging info','blogorola'); ?>: 
				<?php _e('PHP version','blogorola'); ?> <?php echo $blogorola->php_version; ?>,
				<?php _e('SOAP extension','blogorola'); ?> <?php if ($blogorola->soap_ext) echo __('available','blogorola'); else echo __('not found','blogorola');  ?>,
				<?php _e('SOAP library','blogorola'); ?> <?php if ($blogorola->soap_lib) echo __('included','blogorola'); else echo __('not required','blogorola');  ?>,
				<?php _e('WP functions','blogorola'); ?> <?php if ($config_found) echo __('readable','blogorola'); else echo __('not found','blogorola');  ?></small>
			</p>
			
			<?php } ?>
			
			<fieldset class="options" style="padding-bottom:0;">
			<p align="center" style="margin-bottom:0;">
			<img style="padding:0;margin:0;border:none;" src="<?php echo $blogorola->url_img.'/icon_v2_footer.png'; ?>" alt="" title="" border="0"/><br />
			<small><?php echo (int) substr($blogorola->version,0,2).'.'. (int) substr($blogorola->version,2,2).'.'. (int) substr($blogorola->version,4,2);  ?></small>
			</p>
			
			</fieldset>
			
		</form>
		</div>
	
		<?php
		}

	}
	
}

if (BLOGOROLA_IN_WORDPRESS) {
	
	if (!function_exists('is_blogorola_comment')) {
		function is_blogorola_comment() {
			global $blogorola, $comment;
			
			if (isset($comment->comment_type) && $comment->comment_type == $blogorola->comment_type) {
				echo $blogorola->symbol_html;
				return true;
			}
			echo ""; return false;
		}
	}
	
	if (!function_exists('show_blogorola_hotornot')) {
		function show_blogorola_hotornot() {
			global $blogorola, $post;
			
			add_filter('wp_head', array('blogorola', 'post_hotornot_head'));
			
			if (isset($post->ID) && is_numeric($post->ID)) {
				$hot = get_post_meta($post->ID, 'blogorola_hot', $single = true);
				$not = get_post_meta($post->ID, 'blogorola_not', $single = true);
				echo $blogorola->post_hotornot_display($hot, $not);	
			}
			return true;
		}
	}
	
}

$blogorola = new blogorola();

load_plugin_textdomain('blogorola', $blogorola->path. '/lang');

if (BLOGOROLA_IN_WORDPRESS && $blogorola->apikey != null) { 
		
	if ($blogorola->comments_pingback == 1) {
		add_action('comment_post', array('blogorola', 'comment_pingback'));
		add_action('wp_set_comment_status ', array('blogorola', 'comment_pingback'));
	}
	
	if ($blogorola->update_service == 1) add_action('publish_post', array('blogorola', 'blog_ping'));
	
	if ($blogorola->hotornot == 1) { 
		add_filter('the_content', array('blogorola', 'post_hotornot')); 
		add_filter('the_excerpt', array('blogorola', 'post_hotornot'));
		
		if ($blogorola->hotornot_manageposts == 1) {
			add_filter('manage_posts_columns', array('blogorola', 'admin_manage_posts_columns'));
			add_action('manage_posts_custom_column', array('blogorola', 'admin_manage_posts_custom_column'));
		}
		
		if ($blogorola->hotornot_dashboard == 1) {
			add_action('activity_box_end', array('blogorola', 'admin_activity_box'));
		}
	}
	
	if (preg_match("/itsybitsy/i", $_SERVER['HTTP_USER_AGENT']) || $_SERVER['REMOTE_ADDR'] == gethostbyname('www.blogorola.com')) {
		add_action('init', array('blogorola', 'feed_normalize_links'), 1);
	}
	
	add_filter('get_comment_author_link  ', array('blogorola', 'comment_symbol'));
	add_filter('wp_head', array('blogorola', 'post_hotornot_head'));

}

if (BLOGOROLA_IN_WORDPRESS && is_admin()) { 
	$blogorola->admin_menu();
	if (function_exists('register_activation_hook'))
		register_activation_hook( $blogorola->plugin_basename(__FILE__), array('blogorola', 'blog_ping_activate') );
}