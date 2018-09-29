<?	/*### EDITED on 20070722 by ZAxisIT@gmail.com ###*/	?>
<?	/*### FUNCTION IN FOLDER ###*/
	//	include 'function/scandir.php';
	//	foreach(scandir((file_exists("../_zaxes2")?"../_zaxes2/":"../../_zaxes2/").'function') as $key => $value)
	//		if(end(explode(".",$value))=="php" && reset(explode(".",$value)) != "function")
	//			if(file_exists($value))
	//				include $value;
?>
<?	/*### DEFINED FUNCTION ###*/
		if (!function_exists('arrayv_unset')) {
			function arrayv_unset($array, $targets)
			{	foreach($targets as $target)
					unset($array[array_search($target, $array)]);
				return $array;
			}
		}
		if (!function_exists('arrayk_unset')) {
			function arrayk_unset($array, $targets)
			{	foreach($targets as $target)
					unset($array[$target]);
				return $array;
			}
		}
		if (!function_exists('array_concat')) {
			function array_concat($prefix="", $array, $suffix="")
			{	array_walk($array, create_function('&$value,$key', '$value = "'.$prefix.'".$value."'.$suffix.'";'));
				return $array;
			}
		}
		if (!function_exists('arraykv_concat')) {
			function arraykv_concat($left="", $middle="", $array, $right="")
			{	if(is_array($array))
				{	array_walk($array, create_function('&$value,$key', '$value = "'.$left.'".$key."'.$middle.'".$value."'.$right.'";'));
					return $array;
				}else
					echo $array;echo "<br><b>arraykv_concat function : this argument must be array</b><pre>";print_r($array);echo("</pre>");
				return array();
			}
		}
		if (!function_exists('isarray')) {
			function isarray($array)
			{	if(is_array($array))
					return $array;
				return array();
			}
		}
		if (!function_exists('in_array_multi')) {
			function in_array_multi($needle, $haystack)
			{	if (!is_array($haystack))
					return false;
				while (list($key, $value) = each($haystack))
					if (is_array($value) && in_array_multi($needle, $value) || $value === $needle)
						return true;
				return false;
			}
		}
		if (!function_exists('dir_full')) {
			function dir_full()
			{	return "http://" . rtrim($_SERVER[HTTP_HOST], '/\\') . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/";
			}
		}
		if (!function_exists('header_full')) {
			function header_full($target="",$parameter="")
			{	if (strpos($_SERVER['HTTP_HOST'],'.')!==false&&substr($_SERVER['HTTP_HOST'],0,3) != 'www'&&!preg_match('/(?:\d{1,3}\.){3}\d{1,3}/', $_SERVER['HTTP_HOST']))
					$www = "www.";
				if(count(explode("www.",$_SERVER['HTTP_HOST']))<=1)
					$www = "";
				if($target)
					header("location: http://" . $www . rtrim($_SERVER[HTTP_HOST], '/\\') . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $target . $parameter);
				else
					header("location: http://" . $www . rtrim($_SERVER[HTTP_HOST], '/\\') . $_SERVER[PHP_SELF] . $parameter);
				exit;
			}
		}
		if (!function_exists('genQueryString')) {
			function genQueryString($queryString, $target="")
			{	if($target=="")
					$target = $_SERVER[PHP_SELF];
				foreach($queryString as $key => $value)
					$queryString[$key] = urlencode($value);
				$queryString = arraykv_concat("","-",$queryString,"");
				return basename($target, '.'.end(explode('.', $target))).'-'.implode("-", $queryString).'.'.end(explode('.', $target));
				return $target."?".implode("&", $queryString);
			}
		}
		if (!function_exists('dump')) {
			function dump($echo, $array=array(), $name=null )
			{	if($echo===1)
					echo '<pre style="text-align: left; font-size: 11px;"><b>'.$name.'</b> '.print_r($array,true).'</pre>';
				else if($echo!==0)
					echo '<pre style="text-align: left; font-size: 11px;">'.print_r($echo,true).'</pre>';
			}
		}
		if (!function_exists('resize')) {
			function resize($tmp_name, $file_name, $size)
			{	$dim = GetImageSize($tmp_name);
				$w=$dim[0];
				$h=$dim[1];
				if ($w >= $size || $h >= $size)
				{	if ($w > $h)
					{	$w_new = $size;
						$h_new = ($h*$w_new)/$w;
					}elseif($w < $h)
					{	$h_new = $size;
						$w_new = ($w*$h_new)/$h;
					}else
					{	$w_new=$size;
						$h_new=$size;
					}
				}else
				{	$w_new=$w;
					$h_new=$h;
				}
				$w_new = number_format($w_new,0,'.','');
				$h_new = number_format($h_new,0,'.','');
				$tmp_name = ImageCreateFromJpeg($tmp_name);
				$thumb = ImageCreateTrueColor($w_new,$h_new);
				$wm = $w/$w_new;
				$hm = $h/$h_new;
				$h_height = $h_new/2;
				$w_height = $w_new/2;
				if($w > $h)
				{	$adjusted_width = $w / $hm;
					$half_width = $adjusted_width / 2;
					$int_width = $half_width - $w_height;
					ImageCopyResampled($thumb,$tmp_name,-$int_width,0,0,0,$adjusted_width,$h_new,$w,$h);
				}elseif(($w < $h) || ($w == $h))
				{	$adjusted_height = $h / $wm;
					$half_height = $adjusted_height / 2;
					$int_height = $half_height - $h_height;
					ImageCopyResampled($thumb,$tmp_name,0,-$int_height,0,0,$w_new,$adjusted_height,$w,$h);
				}else
				{	ImageCopyResampled($thumb,$tmp_name,0,0,0,0,$w_new,$h_new,$w,$h); 	
				}
				ImageJPEG($thumb,$file_name,95);
				imagedestroy($tmp_name);
			}
		}
		if (!function_exists('url2html')) {
			function url2html($file)
			{	$fp = @fopen($file,"r");
				while(!feof($fp))
					$cont.= fread($fp,1024);
				fclose($fp);
				return $cont;
			}
		}
		if (!function_exists('genSQLOrder ')) {
			function genSQLOrder ($sql, $column)
			{	$sql_array = parseSQLOrder($sql);
				if(isset($sql_array[$column = trim($column)]))
					if($sql_array[$column]=="" || $sql_array[$column]=="asc")
						$sql_array[$column] = "desc";
					else
						unset($sql_array[$column]);
				else
					$sql_array = array_merge($sql_array,array(trim($column)=>""));
				if(count($sql_array))
					return "ORDER BY ".implode(',',arraykv_concat(""," ",$sql_array,""));
				else
					return "";
			}
		}
		if (!function_exists('parseSQLOrder')) {
			function parseSQLOrder($sql)
			{	$sql = trim(str_replace("order by", "", strtolower(str_replace(array(", ", " ,", "	,",",	"), ",", str_replace(array("	", "	 ", "		"), " ", $sql)))));
				if($sql)
				{	foreach($sql = explode(",", $sql = trim($sql)) as $key => $value)
						$sql_array[trim(reset(explode(" ",trim($value))))] = trim(end(explode(" ",trim($value))));
					foreach($sql_array as $key => $value)
						if($key==$value||$value=="")
							$sql_array[$key] = "asc";
					return $sql_array;
				}else
					return array();
			}
		}
		if (!function_exists('scan_dir')) {
			function scan_dir($dirname=".", $ext = "", $ext_excl=false)
			{	$allfiles = scandir($dirname);
				if(!is_array($ext))
					$ext = array($ext);
				foreach(isarray($allfiles) as $key => $value)
				{	if($value!='.'&&$value!='..')
					{	$pathinfo = pathinfo($value);
						if($ext_excl)
						{	if(!in_array($pathinfo['extension'], $ext))
								$files[] = $value;
						}else
						{	if(isset($pathinfo['extension']))
								if(in_array($pathinfo['extension'], $ext))
									$files[] = $value;
						}
					}
				}
				return($files);
			}
		}
		if (!function_exists('thainum ')) {
			function thainum ($num)
			{	$numarray = array('๐','๑','๒','๓','๔','๕','๖','๗','๘','๙');
				foreach($num_temp = str_split($num) as $key => $value)
					if(is_numeric($value))
						$num_temp[$key] = $numarray[$value];
					else
						$num_temp[$key] = $value;
				return implode('',$num_temp);
			}
		}
		if (!function_exists('num2thaiword ')) {
			function num2thaiword ($num)
			{	dump(0,$wordarray = array("ศูนย์","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ด","แปด","เก้า"));
				dump(0,$wordspecialarray = array("","","ยี่"));
				dump(0,$unitarray = array_reverse(array("","สิบ","ร้อย","พัน","หมื่น","แสน"),true));
				if($num<10)
					return $wordarray[$num];
				else
				{	dump(0,$num = array_chunk(array_reverse(str_split(number_format($num,0,'',''))),6));
					foreach($num as $key => $value)
					{	foreach ($value as $key2 => $value2)
						{	if($key2==1&&$value2<3)
								$num[$key][$key2] = $wordspecialarray[$value2].$unitarray[$key2];
							else
								$num[$key][$key2] = $wordarray[$value2].$unitarray[$key2];
							if($key2==0&&$value2==1)
								$num[$key][$key2] = 'เอ็ด';
							if($value2==0)
								$num[$key][$key2] = '';
						}
						$num[$key] = implode('',array_reverse($num[$key]));
					}
					return implode('ล้าน',array_reverse($num));
				}
			}
		}
		if (!function_exists('xml2array')) {
			function xml2array($content)
			{	$xml_parser = xml_parser_create();
				xml_parser_set_option($xml_parser,XML_OPTION_SKIP_WHITE,1);
				$values = $result = array();
				xml_parse_into_struct($xml_parser, $content, $values);
				xml_parser_free($xml_parser);
				foreach($values as $key => $value)
				{	if($value[type]=='open')
						$temp = array();
					else if($value[type]=='complete')
						$temp[$value[tag]] = $value[value];
					else if($value[type]=='close')
						$result[] = array_change_key_case($temp);
				}
				return $result;
			}
		}
		if (!function_exists('xml21array')) {
			function xml21array($content)
			{	$xml_parser = xml_parser_create();
				xml_parser_set_option($xml_parser,XML_OPTION_SKIP_WHITE,1);
				$values = $result = array();
				xml_parse_into_struct($xml_parser, $content, $values);
				xml_parser_free($xml_parser);
				foreach($values as $key => $value)
				{	if($value[type]=='complete')
						$temp[$value[tag].$count[$value[tag]]] = $value[value];
					else if($value[type]=='open')
						$temp[$value[tag].$count[$value[tag]]] = $value[type];
					else if($value[type]=='close')
						$temp[$value[tag].$count[$value[tag]]."_".$value[type]] = $value[type];
					if(in_array($value[tag],array_keys($temp)))
						$count[$value[tag]] = max(2,++$count[$value[tag]]);
				}
				return array_change_key_case($temp);
			}
		}
		if (!function_exists('file_get_content')) {
			function file_get_content($url)
			{	$ch = curl_init();
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
				$str = curl_exec($ch);
				curl_close($ch);
				return $str;
			}
		}
		if (!function_exists('url_exist')) {
			function url_exist($url) 
			{	$a_url = parse_url($url);
				if (!isset($a_url['port'])) 
					$a_url['port'] = 80;
				$errno = 0;
				$errstr = '';
				$timeout = 30;
				if(isset($a_url['host']) && $a_url['host']!=gethostbyname($a_url['host']))
				{	$fid = fsockopen($a_url['host'], $a_url['port'], $errno, $errstr, $timeout);
					if (!$fid) 
						return false;
					$page = isset($a_url['path'])	?$a_url['path']:'';
					$page .= isset($a_url['query'])?'?'.$a_url['query']:'';
					fputs($fid, 'HEAD '.$page.' HTTP/1.0'."\r\n".'Host: '.$a_url['host']."\r\n\r\n");
					$head = fread($fid, 4096);
					$head = substr($head,0,strpos($head, 'Connection: close'));
					fclose($fid);
					if (preg_match('#^HTTP/.*\s+[200|302]+\s#i', $head)) 
					{	$pos = strpos($head, 'Content-Type');
						return $pos !== false;
					}
				} else {
					return false;
				}
			}
		}
		if (!function_exists('queryString')) {
			function queryString($enable=1,$queryString)
			{	if(!$enable)
					return $queryString;
				$target = reset(explode("?",$queryString));
				parse_str(end(explode("?",$queryString)),$queryString);
				return genQueryString($queryString, $target);
			}
		}
		if (!function_exists('full_url')) {
			function full_url()
			{	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
				$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
				$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
				return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
			}
		}
		if (!function_exists('tis2utf8')) {
			function tis2utf8($tis) 
			{	for( $i=0 ; $i< strlen($tis) ; $i++ )
				{	$s = substr($tis, $i, 1);
					$val = ord($s);
					if( $val < 0x80 ){
						 $utf8 .= $s;
					} elseif ( ( 0xA1 <= $val and $val <= 0xDA ) or ( 0xDF <= $val and $val <= 0xFB ) ){
						 $unicode = 0x0E00 + $val - 0xA0;
						 $utf8 .= chr( 0xE0 | ($unicode >> 12) );
						 $utf8 .= chr( 0x80 | (($unicode >> 6) & 0x3F) );
						 $utf8 .= chr( 0x80 | ($unicode & 0x3F) );
					}
				}
				return $utf8;
			}
		}
?>