<?	function genpwd($PwdLength=8, $PwdType='standard')
		{	$Ranges='';
			if('test'==$PwdType)				 return 'test';
			elseif('standard'==$PwdType) $Ranges='65-78,80-90,97-107,109-122,50-57';
			elseif('alphanum'==$PwdType) $Ranges='65-90,97-122,48-57';
			elseif('any'==$PwdType)			$Ranges='40-59,61-91,93-126';
			if($Ranges<>'')
			{	$Range=explode(',',$Ranges);
				$NumRanges=count($Range);
				mt_srand(time()); //not required after PHP v4.2.0
				$p='';
				for ($i = 1; $i <= $PwdLength; $i++)
						{
						$r=mt_rand(0,$NumRanges-1);
						list($min,$max)=explode('-',$Range[$r]);
						$p.=chr(mt_rand($min,$max));
						}
				return $p;
			}
		}
?>