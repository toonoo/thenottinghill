<?	if (!function_exists('array2xml')) 
		{	function array2xml($buffer) 
			{	$xml  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
				$xml .= "<service version=\"1.0\">\n";
				foreach($buffer as $val)
				{	$xml .= "    <document>\n";
					foreach ($val as $key => $value)
						$xml .= "        <{$key}>".utf8_encode(htmlspecialchars($value))."</{$key}>\n";
					$xml .= "    </document>\n";
				}
				$xml .= "</service>\n";
				return $xml;
			}
		}
?>