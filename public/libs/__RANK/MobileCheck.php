<?php


$obj = new MobileCheck();
$env = $obj->CheckUA($_SERVER['HTTP_USER_AGENT']);
$ua = $_SERVER['HTTP_USER_AGENT'];
if($env !== 'pc' && $env !== 'other'){
  $obj->GetZone($env);
  $result = $obj->CheckIP($obj->zone);
  if($result === FALSE){
    // 偽装の時はpcへ振り分け
    $env = 'pc';
  }else{
    list($ser,$icc,$srn,$ezn) = $obj->GetSub($env);
  }
}



class MobileCheck{
  var $zone;

  function GetSub($env){
    global $ua;
    if($env === 'docomo'){
      if(preg_match("/ser([a-zA-Z0-9]+)/",$ua, $dprg)){
        if(strlen($dprg[1]) === 11){
          $ser = $dprg[1];
        }elseif(strlen($dprg[1]) === 15){
          $ser = $dprg[1];
          if(preg_match("/icc([a-zA-Z0-9]+)/",$ua, $dpeg)){
            if(strlen($dpeg[1]) === 20){
              $icc = $dpeg[1];
            }
          }
        }
      }
    }elseif($env === 'softbank'){
      if(preg_match("/\/SN([a-zA-Z0-9]+)\//",$ua,$vprg)){
        $srn = $vprg[1];
      }
    }elseif($env === 'au'){
      $ezn = $_SERVER['HTTP_X_UP_SUBNO'];
    }

    return array($ser,$icc,$srn,$ezn);
  
  }//func-GetSub

  function GetZone($env){
    /* IP帯域の設定 */
    if($env === 'docomo'){
      //i-mode(NTT DoCoMo)のIPアドレス帯域を設定
      //更新日時:2007-03-22
      //http://www.nttdocomo.co.jp/service/imode/make/content/ip/index.html
      $this->zone[0] = '210.153.84.0/24';
      $this->zone[1] = '210.136.161.0/24';
      $this->zone[2] = '210.153.86.0/24';
      // 開発用IPをマッピング
      $this->zone[3] = '210.138.117.0/24';
    }elseif($env === 'au'){
      //EZWeb(au)のIPアドレス帯域を設定
      //更新日時:2007-03-23
      //http://www.au.kddi.com/ezfactory/tec/spec/ezsava_ip.html
      $this->zone[0] = '210.169.40.0/24';
      $this->zone[1] = '210.196.3.192/26';
      $this->zone[2] = '210.196.5.192/26';
      $this->zone[3] = '210.230.128.0/24';
      $this->zone[4] = '210.230.141.192/26';
      $this->zone[5] = '210.234.105.32/29';
      $this->zone[6] = '210.234.108.64/26';
      $this->zone[7] = '210.251.1.192/26';
      $this->zone[8] = '210.251.2.0/27';
      $this->zone[9] = '211.5.1.0/24';
      $this->zone[10] = '211.5.2.128/25';
      $this->zone[11] = '211.5.7.0/24';
      $this->zone[12] = '218.222.1.0/24';
      $this->zone[13] = '61.117.0.0/24';
      $this->zone[14] = '61.117.1.0/24';
      $this->zone[15] = '61.117.2.0/26';
      $this->zone[16] = '61.202.3.0/24';
      $this->zone[17] = '219.108.158.0/26';
      $this->zone[18] = '219.125.148.0/24';
      $this->zone[19] = '222.5.63.0/24';
      $this->zone[20] = '222.7.56.0/24';
      $this->zone[21] = '222.5.62.128/25';
      $this->zone[22] = '222.7.57.0/24';
      $this->zone[23] = '59.135.38.128/25';
      $this->zone[24] = '219.108.157.0/25';
      $this->zone[25] = '219.125.151.128/25';
      // 開発用IPをマッピング
      $this->zone[26] = '210.138.117.0/24';
    }elseif($env === 'softbank'){
      //Yahoo!ケータイ(SoftBank)のIPアドレス帯域を設定
      //更新日時:2007-03-23
      //http://developers.softbankmobile.co.jp/dp/tech_svc/web/ip.php
      $this->zone[0] = '202.179.204.0/24';
      $this->zone[1] = '202.253.96.248/29';
      $this->zone[2] = '210.146.7.192/26';
      $this->zone[3] = '210.146.60.192/26';
      $this->zone[4] = '210.151.9.128/26';
      $this->zone[5] = '210.169.130.112/29';
      $this->zone[6] = '210.169.130.120/29';
      $this->zone[7] = '210.169.176.0/24';
      $this->zone[8] = '210.175.1.128/25';
      $this->zone[9] = '210.228.189.0/24';
      $this->zone[10] = '211.8.159.128/25';
    }elseif($env === 'willcom'){
      //AIR-EDGE PHONE(WILLCOM)のIPアドレス帯域を設定
      //更新日時:2007-03-22
      //http://www.willcom-inc.com/ja/service/contents_service/club_air_edge/for_phone/ip/
      $this->zone[0] = '61.198.142.0/24';
      $this->zone[1] = '219.108.14.0/24';
      $this->zone[2] = '61.198.161.0/24';
      $this->zone[3] = '219.108.0.0/24';
      $this->zone[4] = '61.198.249.0/24';
      $this->zone[5] = '219.108.1.0/24';
      $this->zone[6] = '61.198.250.0/24';
      $this->zone[7] = '219.108.2.0/24';
      $this->zone[8] = '61.198.253.0/24';
      $this->zone[9] = '219.108.3.0/24';
      $this->zone[10] = '61.198.254.0/24';
      $this->zone[11] = '219.108.4.0/24';
      $this->zone[12] = '61.198.255.0/24';
      $this->zone[13] = '219.108.5.0/24';
      $this->zone[14] = '61.204.3.0/25';
      $this->zone[15] = '219.108.6.0/24';
      $this->zone[16] = '61.204.4.0/24';
      $this->zone[17] = '221.119.0.0/24';
      $this->zone[18] = '61.204.6.0/25';
      $this->zone[19] = '221.119.1.0/24';
      $this->zone[20] = '125.28.4.0/24';
      $this->zone[21] = '221.119.2.0/24';
      $this->zone[22] = '125.28.5.0/24';
      $this->zone[23] = '221.119.3.0/24';
      $this->zone[24] = '125.28.6.0/24';
      $this->zone[25] = '221.119.4.0/24';
      $this->zone[26] = '125.28.7.0/24';
      $this->zone[27] = '221.119.5.0/24';
      $this->zone[28] = '125.28.8.0/24';
      $this->zone[29] = '221.119.6.0/24';
      $this->zone[30] = '211.18.235.0/24';
      $this->zone[31] = '221.119.7.0/24';
      $this->zone[32] = '211.18.238.0/24';
      $this->zone[33] = '221.119.8.0/24';
      $this->zone[34] = '211.18.239.0/24';
      $this->zone[35] = '221.119.9.0/24';
      $this->zone[36] = '125.28.11.0/24';
      $this->zone[37] = '125.28.13.0/24';
      $this->zone[38] = '125.28.12.0/24';
      $this->zone[39] = '125.28.14.0/24';
      $this->zone[40] = '125.28.2.0/24';
      $this->zone[41] = '125.28.3.0/24';
      $this->zone[42] = '211.18.232.0/24';
      $this->zone[43] = '211.18.233.0/24';
      $this->zone[44] = '211.18.236.0/24';
      $this->zone[45] = '211.18.237.0/24';
      $this->zone[46] = '125.28.0.0/24';
      $this->zone[47] = '125.28.1.0/24';
      $this->zone[48] = '61.204.0.0/24';
      $this->zone[49] = '210.168.246.0/24';
      $this->zone[50] = '210.168.247.0/24';
      $this->zone[51] = '219.108.7.0/24';
      $this->zone[52] = '61.204.2.0/24';
      $this->zone[53] = '61.204.5.0/24';
      $this->zone[54] = '61.198.129.0/24';
      $this->zone[55] = '61.198.140.0/24';
      $this->zone[56] = '61.198.141.0/24';
      $this->zone[57] = '125.28.15.0/24';
      $this->zone[58] = '61.198.165.0/24';
      $this->zone[59] = '61.198.166.0/24';
      $this->zone[60] = '61.198.168.0/24';
      $this->zone[61] = '61.198.169.0/24';
      $this->zone[62] = '61.198.170.0/24';
      $this->zone[63] = '61.198.248.0/24';
      $this->zone[64] = '125.28.16.0/24';
      $this->zone[65] = '125.28.17.0/24';
      $this->zone[66] = '211.18.234.0/24';
      $this->zone[67] = '219.108.8.0/24';
      $this->zone[68] = '219.108.9.0/24';
      $this->zone[69] = '219.108.10.0/24';
    }
  }//func-GetZone

  function CheckUA($agent){
    /* UserAgentからキャリアを返す */
    if(strpos($agent,"DoCoMo") !== FALSE){
      return('docomo');
    }elseif(strpos($agent,"SoftBank") !== FALSE || strpos($agent,"Vodafone") !== FALSE || strpos($agent,"J-PHONE") !== FALSE || strpos($agent,"MOT-") !== FALSE){
      return('softbank');
    }elseif(strpos($agent,"KDDI-") !== FALSE || strpos($agent,"UP.Browser/") !== FALSE){
      return('au');
    }elseif(strpos($agent,"WILLCOM") !== FALSE || strpos($agent,"DDIPOCKET") !== FALSE){
      return('willcom');
    }elseif(strpos($agent,"L-MODE") !== FALSE || strpos($agent,"Nintendo Wii;") !== FALSE || strpos($agent,"PlayStation Portable") !== FALSE || strpos($agent,"EGBROWSER") !== FALSE || strpos($agent,"AveFront") !== FALSE || strpos($agent,"PLAYSTATION 3;") !== FALSE || strpos($agent,"ASTEL") !== FALSE || strpos($agent,"PDXGW") !== FALSE){
      return('other');
    }else{
      return('pc');
    }
  }//func-CheckUA

  function CheckIP($area){
    /* IPアドレス帯域($zone)に含まれているか検査 */
    $addr = $_SERVER['REMOTE_ADDR'];

    $i = 0;
    $count = count($area);
    $flag = FALSE;

    while($i < $count){
      /* ネットワークアドレスの算出 */
      //範囲の特定
      list($ip,$sub) = explode('/',$area[$i]);
      list($mask,$plus) = $this->switchtomask($sub);
      if($mask === FALSE && $plus === FALSE) die('範囲がおかしいです(0-32まで)');

      //IP,サブネットマスクの論理積を求める
      $ip = explode('.',$ip);
      $mask = explode('.',$mask);

      //それぞれの論理積を求める
      $network[0] = bindec(decbin($ip[0]) & decbin($mask[0]));
      $network[1] = bindec(decbin($ip[1]) & decbin($mask[1]));
      $network[2] = bindec(decbin($ip[2]) & decbin($mask[2]));
      $network[3] = bindec(decbin($ip[3]) & decbin($mask[3]));

      //ロングIPアドレスへ
      $naddr = sprintf("%u", ip2long(implode('.',$network)));
      $baddr = $naddr + $plus -1;

      /* $addrが範囲内にあるか */
      //$addrをロングIPアドレス化する
      $addr = sprintf("%u", ip2long($addr));

      if($naddr < $addr && $addr < $baddr){
        $flag = TRUE;
        break;
      }

      $i++;
    }

    return $flag;

  }//func-CheckIP

  /* xxx.xxx.xxx.xxx/YYのYY→yyy.yyy.yyy.yyyへ */
  function switchtomask($sub){
    switch($sub){
      case 32 :
        $mask = '255.255.255.255';
        $plus = 1;
        break;
      case 31 :
        $mask = '255.255.255.254';
        $plus = 2;
        break;
      case 30 :
        $mask = '255.255.255.252';
        $plus = 4;
        break;
      case 29 :
        $mask = '255.255.255.248';
        $plus = 8;
        break;
      case 28 :
        $mask = '255.255.255.240';
        $plus = 16;
        break;
      case 27 :
        $mask = '255.255.255.224';
        $plus = 32;
        break;
      case 26 :
        $mask = '255.255.255.192';
        $plus = 64;
        break;
      case 25 :
        $mask = '255.255.255.128';
        $plus = 128;
        break;
      case 24 :
        $mask = '255.255.255.0';
        $plus = 256;
        break;
      case 23 :
        $mask = '255.255.254.0';
        $plus = 512;
        break;
      case 22 :
        $mask = '255.255.252.0';
        $plus = 1024;
        break;
      case 21 :
        $mask = '255.255.248.0';
        $plus = 2048;
        break;
      case 20 :
        $mask = '255.255.240.0';
        $plus = 4096;
        break;
      case 19 :
        $mask = '255.255.224.0';
        $plus = 8192;
        break;
      case 18 :
        $mask = '255.255.192.0';
        $plus = 16384;
        break;
      case 17 :
        $mask = '255.255.128.0';
        $plus = 32768;
        break;
      case 16 :
        $mask = '255.255.0.0';
        $plus = 65536;
        break;
      case 15 :
        $mask = '255.254.0.0';
        $plus = 131072;
        break;
      case 14 :
        $mask = '255.252.0.0';
        $plus = 262144;
        break;
      case 13 :
        $mask = '255.248.0.0';
        $plus = 524288;
        break;
      case 12 :
        $mask = '255.240.0.0';
        $plus = 1048576;
        break;
      case 11 :
        $mask = '255.224.0.0';
        $plus = 2097152;
        break;
      case 10 :
        $mask = '255.192.0.0';
        $plus = 4194304;
        break;
      case 9 :
        $mask = '255.128.0.0';
        $plus = 8388608;
        break;
      case 8 :
        $mask = '255.0.0.0';
        $plus = 16777216;
        break;
      case 7 :
        $mask = '254.0.0.0';
        $plus = 33554432;
        break;
      case 6 :
        $mask = '252.0.0.0';
        $plus = 67108864;
        break;
      case 5 :
        $mask = '248.0.0.0';
        $plus = 134217728;
        break;
      case 4 :
        $mask = '240.0.0.0';
        $plus = 268435456;
        break;
      case 3 :
        $mask = '224.0.0.0';
        $plus = 536870912;
        break;
      case 2 :
        $mask = '192.0.0.0';
        $plus = 1073741824;
        break;
      case 1 :
        $mask = '128.0.0.0';
        $plus = 2147483648;
        break;
      case 0 :
        $mask = '0.0.0.0';
        $plus = 4294967296;
        break;
      default :
        $mask = FALSE;
        $plus = FALSE;
        break;
    }
    return array($mask,$plus);
  }//func-switchtomask
}//class-MobileCheck




/* 機種名取得 */
function getmobilename($uagent){
 //au deviceID -> 機種名
 $auarray = array("CA34" => "G'z One W42CA","HI37" => "W42H","TS37" => "W44T",
				  "TS36" => "W43T","SN36" => "W42S","KC36" => "W42K","TS35" => "neon","TS34" => "W41T",
				  "SA36" => "W41SA","KC35" => "W41K","HI36" => "W41H","SN34" => "W41S",
				  "CA33" => "W41CA","HI34" => "PENCK","SA35" => "W33SA","TS33" => "W32T",
				  "SA34" => "W32SA","KC34" => "W32K","HI35" => "W32H","SN35" => "W32S(Suica対応)",
				  "SN33" => "W32S","CA32" => "W31CA","TS32" => "W31T","SN32" => "W31S",
				  "KC33" => "W31K","SA33" => "W31SA","SA32" => "W22SA","HI33" => "W22H","CA31" => "W21CA/��",
				  "SA31" => "W21SA","TS31" => "W21T","SN31" => "W21S","KC32" => "W21K",
				  "HI32" => "W21H","KC31" => "W11K","HI31" => "W11H","ST29" => "Sweets pure",
				  "CA28" => "G'z ONE TYPE-R","ST26" => "Sweets","ST25" => "talby","KC28" => "A5521K",
				  "ST2A" => "A5520SA","ST28" => "A5518SA","ST22" => "INFOBAR","TS2C" => "A5517T",
				  "TS2B" => "A5516T","KC27" => "A5515K","ST27" => "A5514SA","CA27" => "A5512CA",
				  "TS2A" => "A5511T","TS29" => "A5509T","ST24" => "A5507SA","TS28" => "A5506T",
				  "SA27" => "A5505SA","TS27" => "A5504T","SA26" => "A5503SA","KC24" => "A5502K",
				  "KC25" => "A5502K","TS26" => "A5501T","CA26" => "A5407CA","CA25" => "A5406CA",
				  "ST23" => "A5405SA","SN25" => "A5404S","CA24" => "A5403CA","SN24" => "A5402S",
				  "CA23" => "A5401CA","ST21" => "A5306ST","KC22" => "A5305K","TS24" => "A5304T",
				  "HI24" => "A5303H II","HI23" => "A5303H","CA22" => "A5302CA","TS23" => "A5301T","TS21" => "C5001T","PT21" => "A1405PT"
     ,"SN29" => "A1404S","KC26" => "A1403K","SN27" => "A1402S��","SN28" => "A1402S��ｶﾒﾗ無し"
     ,"SN26" => "A1402S","KC23" => "A1401K","SA28" => "A1305SA","TS25" => "A1304T"
     ,"SA25" => "A1303SA","SA24" => "A1302SA","SN23" => "A1301S","SN22" => "A1101S"
     ,"SA22" => "A3015SA","SN21" => "A3014S","TS22" => "A3013T","CA21" => "A3012CA"
     ,"SA21" => "A3011SA","MA21" => "C3003P","KC21" => "C3002K","HI21" => "C3001H"
     ,"ST14" => "A1014ST","KC15" => "A1013K","KC14" => "A1012K","ST13" => "A1011ST"
     ,"SN17" => "C1002S","SY15" => "C1001SA","CA14" => "C452CA","HI14" => "C451H"
     ,"TS14" => "C415T","KC13" => "C414K","SN15" => "C413S","SN16" => "C413S"
     ,"SY14" => "C412SA","ST12" => "C411ST","TS13" => "C410T","CA13" => "C409CA"
     ,"MA13" => "C408P","HI13" => "C407H","SN13" => "C406S","SY13" => "C405SA"
     ,"SN12" => "C404S","SN14" => "C404S","ST11" => "C403ST","DN11" => "C402DE"
     ,"SY12" => "C401SA","KC12" => "C313K","CA12" => "C311CA","TS12" => "C310T"
     ,"HI12" => "C309H","MA11" => "C308P","MA12" => "C308P","KC11" => "C307K"
     ,"SN11" => "C305S","SY11" => "C304SA","CA11" => "C303CA","HI11" => "C302H"
     ,"TS11" => "C301T","DN01" => "C202DE","HI01" => "C201H","HI02" => "C201H"
     ,"KCTE" => "TK51","TST9" => "TT51","KCU1" => "TK41","SYT5" => "TS41","KCTD" => "TK40"
     ,"TST8" => "TT32","TST7" => "TT31","KCTC" => "TK31","SYT4" => "TS31"
     ,"KCTB" => "TK23","KCTA" => "TK22","TST6" => "TT22","KCT9" => "TK21"
     ,"TST5" => "TT21","TST4" => "TT11","KCT8" => "TK12","SYT3" => "TS11"
     ,"KCT7" => "TK11","MIT1" => "TD11","MAT3" => "TP11","KCT6" => "TK05"
     ,"TST3" => "TT03","KCT5" => "TK04","KCT4" => "TK03","SYT2" => "TS02"
     ,"MAT1" => "TP01","MAT2" => "TP01","TST2" => "TT02","KCT3" => "TK0K"
     ,"KCT2" => "TK02","KCT1" => "TK01","TST1" => "TT01","SYT1" => "TS01");

 if(preg_match("/\.ezweb\.ne\.jp$/",@gethostbyaddr($_SERVER['REMOTE_ADDR']))):
  // auXHTML機種判別
  if(preg_match("/[KDDI-]([0-9A-Z]{4})/",$uagent,$match)):
   $device = $match[1];
   $kishu = $auarray[$device];
   if($kishu == ""):
    $kishu = "KDDI-".$device;
   endif;
  // auHDML,Tu-Ka機種判別
  elseif(preg_match("/^UP\.Browser.*-([0-9A-Z]){4}/",$uagent,$match)):
   $device = $match[1];
   $kishu = $auarray[$device];
   if($kishu == ""):
    $kishu = $device;
   endif;
  endif;
 elseif(preg_match("/\.docomo\.ne\.jp$/",@gethostbyaddr($_SERVER['REMOTE_ADDR']))):
  // Mova機種判別
  list(,,$kishu,) = explode("/", $uagent);
  // FOMA機種は特別
  if($kishu == ""):
   if(preg_match("/^DoCoMo\/2\.0\s([0-9a-zA-Z]+)/",$uagent,$match)):
    $kishu = $match[1];
    if($kishu == 'MST'):
     $kishu = 'SH2101V';
    endif;
    // ↑SH2101Vの処理
   else:
    // それでも取得できないとき
    $kishu = 'DoCoMo';
   endif;
  endif;
 elseif(preg_match("/\.jp-[kcqt]\.ne\.jp$/",@gethostbyaddr($_SERVER['REMOTE_ADDR']))):
  // voda機種判別
  $kishu = $_SERVER['HTTP_X_JPHONE_MSNAME'];
 else:
  $kishu = 'PC';
 endif;
 return $kishu;
}




?>
