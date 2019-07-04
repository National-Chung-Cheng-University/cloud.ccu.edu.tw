<?php
/* **************************************

/tools/api_tools.php

*** 取檔案 rec
mode=file
act=GetContent
file_path= 	// url_path
 

*** 設定檔案 rec
mode=file
act=SetRec
file_path= 	// url_path
title=			// 標題
description=	// 描述
class=			// 類別
tag=			// 標籤


*** 取檔案 回應
mode=file
act=GetComment
file_path=	// url_path
p=		// 第幾頁
ps=		// 一頁筆數



*** 新增檔案回應
mode=file
act=AddComment
site=
file_path=
name=		// 名稱
content=	// 內容

*** 刪除檔案回應
mode=file
act=DelComment
site=
file_path=
id=

*** 修改檔案回應
mode=file
act=UpdComment
site=
file_path=
id=
content=


*** 設定標籤
mode=file
act=SetTag
file_path=	// url_path
tag=		// 標籤 [xxx,xxx,...]
[name=file_name,...]	// 多筆用; 有 name 時 file_path 是目錄位置.


*** 取 TagList
mode=file
act=GetTagList
type=[article(html and dir)/html/dir/video/photo/document/file/(empty or othter => all)]
sort=[atime/cnt]	// 排序

return: json_encode
{	"cnt":1,
	"recs":[{"name":"xxx","cnt":"xxx","type":"xxx",
				"ctime":"20120711194634","atime":"20120711195842"}}

				
				
*** 取 Tag Group
/tools/api_tools.php
?mode=file"
&act=GetTagGroup"
&file_path=
&type=
&class=
&subclass=
&group=[*tag/xxx]
				


*** 取 ClassList
mode=file
act=GetClassList
type=[article(html and dir)/html/dir/video/photo/document/file/youtube/(empty or othter => all)]
site_name=	// 子網站 acn
group=[class/subclass/tag]	// 群組欄位名稱

return: json_encode
{	"cnt":1,
	"recs":{"name1":count, "name2":count, ...}
}

*** 取 ClassList2 => TagData
mode=file
act=GetClassList2
type=[article(html and dir)/html/dir/video/photo/document/file/youtube/(empty or othter => all)]
file_path=	// 子網站路徑

return: json_encode
{	"cnt":1,
	"recs":[{
		type:video,							// 類別型態
		name:華人音樂,						// 類別名稱
		sub:,台灣流行,台灣合唱,台灣民謠,... // 子類別
		tag:,美容,烹飪,DIY,...				// 標籤
	}]
}


*** 設定 喜歡 與 不喜歡
mode=file
act=ClickLike
file_path=	// url_path
like=[y/n]	//  y喜歡; n不喜歡

return: json_encode
cnt_like:	// 喜歡數量
cnt_unlike:	// 不喜歡數量


*** 瀏覽次數加一; 一小時只會加一次;
mode=file
act=CntView_Increase
file_path=	// url_path

return: "ok"



*** 取轉出的影片列表
mode=file
act=GetVideoList
file_path=	// file_path

return jason_encode:
	flv_1080_url : xxx
	flv_1080_fs : xxx
	mp4_1080_url : xxx
	mp4_1080_fs : xxx
	bInternalNetwork : [true(內網)/false(外網)]

		
*** 搜尋
mode=search
file_path=			// 一定要設定，沒有設 "dir" 會搜尋子目錄底下的檔案
dir=				// 指定單層目錄，不包含子目錄底下的檔案
fe=[.副檔名]		// 
type=[Photo(fe:.jpg,.png)/Image/Album/Article/Document/File/Web/(Field type)]	//
class=				// 
tag=				// 
ftag=				// 多重標籤, and
sort=[field id]		// 排序
order=[inc/*dec]	// 排序方向
p=[*1]				// 第幾頁
ps=[*10]			// 一頁筆數
q=					// Query
getRecInfo=[y/n]	// 取原始的記錄檔



*** 標籤
tools/api_tools.php
file_path = 
mode=tag
act=[upd(變更) / del(刪除) / get_list(取得全部列表)]
n=	名稱
fc= 前景
bc=	背景



*** send_mail
mode=send_mail
file_path=	// 必須要有瀏覽權限才能寄信.
from=
to= xx<xx@xx.xx>, xx@xx.xx
subject=
text=	// 二選一
html=	// 二選一
files=urlencode(filepath:filename) , ...	// 附件檔



*** 取得隱藏權限 key code
mode=get_random_path
file_path=	// 必須要有管理者權限


************************************** */



// 
 
 

if( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' )	// Windows
{
	define("SYS_OS_WIN",true);
}
else
{
	define("SYS_OS_WIN",false);
}
define("DIR_TOOLS", "");
define("DIR_TAG",	DIR_TOOLS."tag/");

require_once("wbase2.php");
require_once("wrec.php");
require_once("rs_tools_base.php");
require_once("rs_odb_lib.php");
require_once("rs_cache_lib.php");

require_once("public_lib.php");


define("HOSTIP_ROOT", "ookon.nuweb.cc");
// Debug
define("bDebug", isset($_REQUEST["debug"]) ? $_REQUEST["debug"] == "y" : false);

$sMode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
switch($sMode) {
	// case "test":
		// print B_Array2String2($_REQUEST, false, 1);
		// break;
		
	case "file":
		m_file();
		break;
		
	case "search":
		$sub_mode	= isset($_REQUEST["sub_mode"])	? $_REQUEST["sub_mode"]	: "";
		$type		= isset($_REQUEST["type"])		? $_REQUEST["type"]		: "";
		// NUDrive [資料類別] - [所有xxx]
		if ($sub_mode == "drive_obj_dir") 
			m_search_DriveObjDir();
		// 首頁的動態訊息
		else if ($type == "page_all")
			m_search_PageAll();
		else
			m_search();
		break;
		
	case "ssn_get2":
		m_ssn_get2();
		break;
	case "set_cookie":
		m_set_cookie();
		break;
	case "set_cookie_extend":
		m_set_cookie_extend();
		break;
		
	case "get_fileinfo":
		m_get_fileinfo();
		break;
		
	case "get_host_ip":
		m_get_host_ip();
		break;
		
	case "get_main_top_bar_info":
		m_get_main_top_bar_info();
		break;
		
	case "get_forum_path":
		m_get_forum_path();
		break;
		
	case "tag":
		m_tag();
		break;
		
	case "send_mail":
		m_send_mail();
		break;
		
	case "get_random_path":
		m_get_random_path();
		break;
		
	case "get_contacts":
		m_get_contacts();
		break;
		
	case "set_contacts":
		m_set_contacts();
		break;
		
	case "get_user_info":
		m_get_user_info();
		break;
		
	case "set_user_info":
		m_set_user_info();
		break;
		
	case "get_often_list_share":
		m_get_often_list_share();
		break;
		
	case "set_often_list_share":
		m_set_often_list_share();
		break;
		
	case "get_user_list":
		m_get_user_list();
		break;
	case "get_user_list2":
		m_get_user_list2();
		break;
		
	case "edit_api":
		m_edit_api();
		break;
		
	case "numail_is_install":
		require_once("/data/HTTPD/htdocs/Admin/init.php");
		print chk_numail();
		break;
		
	case "chk_online":
		require_once("/data/HTTPD/htdocs/Site/init.php");
		$reg_conf = read_conf(REGISTER_CONFIG);
		if (empty($reg_conf)) die_json("Error: Get confing error.");
		$url = rs_wns_getUrl().API_WNS_chk_online."?ssn=".$reg_conf['ssn']."&sca=".$reg_conf['sca'];
		$data = B_file_get_contents($url);
		print $data;
		break;
		
	// case "get_info": // debug
		// require_once("/data/HTTPD/htdocs/Site/init.php");
        // print B_Array2String2(read_conf(REGISTER_CONFIG));
		// break;
		
	case "get_server_info":
		require_once("/data/HTTPD/htdocs/Site/init.php");
        $rec = read_conf(REGISTER_CONFIG);
		$out = array();
		$out['acn'] 		= $rec['acn'];
		$out['sun'] 		= $rec['sun'];
		$out['description'] = $rec['description'];
		$out['alias'] 		= $rec['alias'];
		
		// locac_wns=Y 代表要啟用 Local wns, 若設為 N 代表不啟用 local wns
		// close_server=Y 代表設定為封閉式 Server,若設為 N 代表是開放式 Server (封閉式 Server 一定要啟用 local wns)
        $rec = read_conf(SETUP_CONFIG);
		$out['locac_wns']		= isset($rec['locac_wns']) && $rec['locac_wns'] == "Y" ? "y" : "n";
		$out['close_server']	= isset($rec['close_server']) && $rec['close_server'] == "Y" ? "y" : "n";
		$out['server_url'] 		= preg_replace("#\/*$#","",get_server_url()); // 去除後面的斜
		
		print json_encode($out);
		break;
		
	case "wns_acn2info":
		m_wns_acn2info();
		break;
		
	case "user_search":
		m_user_search();
		break;
		
	case "short_code":
		m_short_code();
		break;
		
	case "odb_test":
		m_odb_test();
		break;
		
	case "gs_get_site_list":
		ignore_user_abort(1);
		set_time_limit(0);
		m_gs_get_site_list();
		break;
		
	default:
		die("Error: mode does not exist.($sMode)");
		break;
}
exit;
//////////////////////////
//

function m_ssn_get2()
{
	global $wns_ser, $wns_port;
	
// echo "SERVER_ACN=".SERVER_ACN," <br>";
	$acn		= isset($_REQUEST["acn"])		? $_REQUEST["acn"]		: "";
	$pwd		= isset($_REQUEST["pwd"])		? $_REQUEST["pwd"]		: "";
	
// echo "acn=$acn, pwd=$pwd, <br>";
	$InHeader = array();
	if (SERVER_ACN == "tw00a") // 中正 - SSO
		$InHeader['url'] = "http://localhost/Site_Prog/login_sso.php?cookie_only=1&acn=$acn&pwd=$pwd";
	else
		$InHeader['url'] = "http://localhost/Site_Prog/login.php?cookie_only=1&acn=$acn&pwd=$pwd";
		
	$res = B_curl_get($InHeader, $OutHeader);
	if (B_chkErr_SendResult($res))
		die($res);
	$nu_code = B_HttpHeader_GetSetCookie(explode("\n",$OutHeader['header_all']),"nu_code");
	if (empty($nu_code))
		die("Error: empty nu_code.");

	$OutHeader = array();
	$InHeader['url'] = rs_wns_getUrl()."/wns/ssn_get2.php?code=".$nu_code;
	$res = B_curl_get($InHeader, $OutHeader);
if (bDebug) echo "res:<br>".B_Array2String2($res)." <br>";
	if (!B_chkErr_SendResult($res)) {
		$res .= "<nu_code>".$nu_code."</nu_code>\r\n"
				."<wns_ip>".$wns_ser."</wns_ip>\r\n"
				."<wns_port>".$wns_port."</wns_port>\r\n";
	}
	
	print $res;
}

function m_gs_get_site_list()
{

	$upd = isset($_REQUEST["update"]) ? $_REQUEST["update"] == "y" : false;
	
	$res = gs_get_site_list($upd);
	if ($res === false) die_json("Error: false");
	
if (bDebug) echo "res:<br>".B_Array2String2($res)." <br>";
	$out = array();
	$out['recs'] = $res;
	print json_encode($out);
}

function m_odb_test()
{
	$sSite 		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$sFilePath = rs_filter_Site($sFilePath);
	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();
	//echo "USER_ACN=".USER_ACN.", Manager=".POWER_Manager.", Admin=".POWER_Admin.", Show=".POWER_Show.", Upload=".POWER_Upload." <br>";
	//if (!POWER_Manager) B_Error(403);
	
	odb_test();
}

// set_short_code($page_url) : 設定短網址代碼 (回傳一組 code 或 false)
// get_short_code($page_url) : 取得短網址代碼 (回傳一組 code 或 false)
// get_url_by_short_code($short_code) : 由短網址代碼取得 URL (回傳 URL 或 false)
// update_short_code($short_code) : 更新 short_code (回傳一組 code 或 false)
// del_short_code($short_code) : 刪除 short_code (回傳 true 或 false)
function m_short_code()
{
	$act		= isset($_REQUEST["act"])		? $_REQUEST["act"]		: "";
	$page_url	= isset($_REQUEST["page_url"])	? $_REQUEST["page_url"]	: "";
	$short_code	= isset($_REQUEST["short_code"])? $_REQUEST["short_code"]	: "";

	switch($act) {
		case "set":
if (bDebug) echo "set: page_url=$page_url <br>";
			if (empty($page_url)) die_json("Error: empty page_url");
			$data = set_short_code($page_url);
if (bDebug) echo "res: data=$data <br>";
			print $data;
			break;
			
		case "get":
if (bDebug) echo "get: page_url=$page_url <br>";
			if (empty($page_url)) die_json("Error: empty page_url");
			$data = get_short_code($page_url);
			if (empty($data))
				$data = set_short_code($page_url);
if (bDebug) echo "res: data=$data <br>";
			print $data;
			break;
			
		case "url2code":
			if (empty($short_code)) die_json("Error: empty short_code");
			print get_url_by_short_code($short_code);
			break;
			
		// case "upd_code":
			// if (empty($short_code)) die_json("Error: empty short_code");
			// print update_short_code($short_code);
			// break;
			
		case "del_code":
			if (empty($short_code)) die_json("Error: empty short_code");
			print del_short_code($short_code);
			break;
			
		default:
			die("Error: act does not exist.($act)");
			break;
	}
}

function m_wns_acn2info()
{
	global $wns_ser, $wns_port;
	require_once("/data/Admin/wns_init.php");
	
	$keyword= isset($_REQUEST["keyword"])	? $_REQUEST["keyword"]	: "";
	if (empty($keyword)) die_json("Error: empty keyword");
	
	$InHeader['Cookie'] = rs_sys_getCookis_Power();
	
	$setup_conf = sys_get_setup_conf();
	// 有啟動 Local WNS
	if ($setup_conf != false && $setup_conf['local_wns'] == "Y") {
		$url = "http://".$_SERVER["SERVER_ADDR"]."/wns/user_info_get.php?keyword=".$keyword;
		$info = rs_cache_get_url($url, 180, $InHeader, true);
		print $info['data'];
		return;
	}
	
	$url = "http://".$wns_ser.":".$wns_port."/wns/user_info_get.php?keyword=".$keyword;
	$OutHeader = array();
	$info = rs_cache_get_url($url, 180, $InHeader, true);
	print $info['data'];
	
}
// sub_mode[ /user/site]
function m_user_search()
{
	$sub_mode	= isset($_REQUEST["sub_mode"])	? $_REQUEST["sub_mode"]	: "";
	$q			= isset($_REQUEST["q"])			? $_REQUEST["q"]		: "";
	$p			= isset($_REQUEST["p"])			? $_REQUEST["p"]		: "";
	$ps			= isset($_REQUEST["ps"])		? $_REQUEST["ps"]		: "";
	
	$aArg = array();
	$aArg['mode'] = $sub_mode;
	$aArg['keyword'] = $q;
	$aArg['page'] = $p;
	$aArg['ps'] = $ps;
	$aArg['is_json'] = 1;
	
	$setup_conf = sys_get_setup_conf();
//if (bDebug) echo "setup_conf:<br>".B_Array2String2($setup_conf)." <br>";
	// 有啟動 Local WNS
	if ($setup_conf != false && $setup_conf['local_wns'] == "Y") {
	// 封閉式 Server
	//if ($setup_conf != false && $setup_conf['close_server'] == "Y") {
		$url = "http://".$_SERVER["SERVER_ADDR"]."/wns/user_search.php";
if (bDebug) echo "url=$url <br>";
		$data = B_file_get_contents_post($url, $aArg, $OutHeader, $InHeader);
if (bDebug) echo "data=$data <br>";
		print $data;
		return;
	}
	
	$url = rs_wns_getUrl()."/wns/user_search.php?".http_build_query($aArg);
if (bDebug) echo "url=$url <br>";
	$OutHeader = array();
	$info = rs_cache_get_url($url, 600, $InHeader, true);
if (bDebug) echo "info:<br>".B_Array2String2($info)." <br><br>";
	print $info['data'];
}
//
function m_edit_api()
{
	$arg = array();
	$arg['site']	= isset($_REQUEST["site"])		? $_REQUEST["site"]		: "Site";
	$arg['lang_ver']= isset($_REQUEST["lang_ver"])	? $_REQUEST["lang_ver"]	: "cht";
	$arg['mode']	= isset($_REQUEST["sun_mode"])	? $_REQUEST["sun_mode"]	: "";
	$arg['code']	= isset($_REQUEST["code"])		? $_REQUEST["code"]		: "";
	$arg['path']	= isset($_REQUEST["path"])		? $_REQUEST["path"]		: "";
	$arg['name']	= isset($_REQUEST["name"])		? $_REQUEST["name"]		: "";
	$arg['content']	= isset($_REQUEST["content"])	? $_REQUEST["content"]	: "";
	
	B_Log_f("m_edit_api.log", "path=$arg[path], name=$arg[name]");
	B_Log_f("m_edit_api.log", "content=$arg[content]");

	m_edit_api_match($arg, "#<(img)[^>]* src=\"([^\"]+)\"#", 2, 1); 
	
}
function m_edit_api_match($arg, $pat, $num, $key)
{
	$C = $arg['content'];
	$x = 0;
	while (preg_match($pat, $C, $m, PREG_OFFSET_CAPTURE, $x)) {
		$ms = $m[$num][0];
		$mpos = $m[$num][1];
		$mls = strlen($ms);
		B_Log_f("m_edit_api.log", "ms=$ms, mpos=$mpos");
		// 
		if (substr($ms, 0, 1) == '/') {
			
		}
		//
		$d_url = $ms;
		//
		$x = $mpos + strlen($d_url);
	}
}
//
function m_user_search2()
{
	$q		= isset($_REQUEST["q"])		? $_REQUEST["q"]	: "";
	$type	= isset($_REQUEST["type"])	? $_REQUEST["type"]	: "";	// [/user/site]
	$p		= isset($_REQUEST["p"]) 	? $_REQUEST["p"] 	: "1";
	$ps		= isset($_REQUEST["ps"]) 	? $_REQUEST["ps"] 	: "20";
	if (empty($q)) die_json("Error: empty q");
	
	$url = API_WNS_user_search."?mode={$type}&page={$p}&keyword=".rawurlencode($q);
	$data = B_file_get_contents($url);
	$recs = wns_xml_con_txt2array($data);
	print json_encode(array('cnt' => $cnt, 'recs' => $recs));
}
// 暫時
function m_get_user_list()
{
	$out = array();
	$f = "/data/Admin/group_user.list";
	$data = B_LoadFile($f);
	$rows = explode("\n", $data);
	foreach($rows as $row) {
		$row = trim($row);
		if (empty($row)) continue;
		list($ssn, $acn, $mail) = explode("\t", $row);
		if (empty($ssn) || empty($acn)) continue;
		$out[] = array("ssn" => $ssn, "acn" => $acn, "mail" => $mail);
	}
	cmp_o_od($out, "acn", "inc");
	print json_encode($out);
}
function m_get_user_list2()
{
	$server_acn = rs_sys_get_server_acn();
	$recs = rs_getSiteList();
	$out = array();
	foreach($recs as $rec) {
		$out[] = trim($rec['owner']);
		$manager = trim($rec['manager']);
		if (!empty($manager)) {
			$a = explode(",", $manager);
			foreach($a as $n) {
				$n = trim($n);
				if (!empty($n))
					$out[] = $n;
			}
		}
		// 社群網站
		if ($rec['type'] == "1") {
			$out[] = trim($rec['acn']).".".$server_acn;
		}
	}
	$out = array_unique($out);
	print json_encode($out);
}


function m_get_random_path()
{
    Global $fe_type;
	
	$sSite		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	
	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();
	
	if (!POWER_Admin) B_Error(403);
	print set_share_code(PATH_FilePath, 1);
}
function m_get_contacts()
{
	$sSite		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$o			= isset($_REQUEST["o"]) 		? $_REQUEST["o"] 			: "name";
	$od 		= isset($_REQUEST["od"]) 		? $_REQUEST["od"] 			: "inc";
	
	$sFilePath = rs_filter_Site($sFilePath);
	if ( empty($sFilePath) ) die("Error: empty file_path.");

	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();

	if (!POWER_Admin) B_Error(403);
	
	$fInfoDB = rs_con_FilePath2PathContactsInfoDB($sFilePath);
	$recInfo = B_Rec_File2Rec($fInfoDB);
	$fDB = rs_con_FilePath2PathContactsDB($sFilePath);
	$recs = B_Rec_File2Recs($fDB);
	$cnt = count($recs);
	if ($cnt > 0) {
		if (!isset($recs[0][$o])) $o = "name";
		cmp_o_od($recs, $o, $od);
	}
	$recInfo['cnt'] = $cnt;
	$recInfo['recs'] = $recs;
	print json_encode($recInfo);
}
function m_set_contacts()
{
	$sSite		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$new_s_recs	= isset($_REQUEST["recs"]) 		? $_REQUEST["recs"] 		: ""; // [name, mail]
	$src		= isset($_REQUEST["src"]) 		? $_REQUEST["src"] 			: "";
	
	$sFilePath = rs_filter_Site($sFilePath);
	if ( empty($sFilePath) ) die("Error: empty file_path.");

	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();

	if (!POWER_Admin) B_Error(403);
	
	$fDB = rs_con_FilePath2PathContactsDB($sFilePath);
	$recs = B_Rec_File2Recs($fDB, "email");
	$new_recs = B_Rec_Data2Recs($new_s_recs);
	$bAdd = 0;
	foreach($new_recs as $rec) {
		if (empty($rec['email']) 
			|| strpos($rec['email'],"@") === false
			|| isset($recs[$rec['email']])
			) continue;
		
		if (empty($rec['name']))
			list($rec['name']) = explode('@', $rec['email'], 2);
		$recs[$rec['email']] = $rec;
		$bAdd++;
	}
	if ($bAdd > 0) B_Rec_Recs2File($fDB, $recs);
	//
	if (!empty($src)) {
		$src = strtolower(trim($src));
		$fInfoDB = rs_con_FilePath2PathContactsInfoDB($sFilePath);
		$recInfo = B_Rec_File2Rec($fInfoDB);
		if (!B_tag_is_exists($recInfo['src'], $src)) {
			$recInfo['src'] = B_tag_add($recInfo['src'], $src);
			B_Rec_Rec2File($fInfoDB, $recInfo);
		}
	}
	print json_encode(array('cnt_add' => $bAdd, 'cnt' => count($recs)));
}
function m_get_user_info()
{
	$sSite		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	
	$sFilePath = rs_filter_Site($sFilePath);
	if ( empty($sFilePath) ) die_json("Error: empty file_path.");

	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();
	
	$f_db = rs_con_FilePath2UserDB();
	if ($f_db === false)
		die_json("Error: db.");
	$rec = B_Rec_File2Rec($f_db);
	print json_encode($rec);
}
function m_set_user_info()
{
	$sSite		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$recData	= isset($_REQUEST["rec"]) 		? $_REQUEST["rec"] 			: "";
	
	$sFilePath = rs_filter_Site($sFilePath);
	if ( empty($sFilePath) ) die_json("Error: empty file_path.");

	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();

	$f_db = rs_con_FilePath2UserDB();
	if ($f_db === false)
		die_json("Error: db.");
	$rec = B_Rec_File2Rec($f_db);
	$recNew = B_Rec_Data2Rec($recData);
	$rec = array_merge($rec, $recNew);
	B_Rec_Rec2File($f_db, $rec, false);
	// Debug
	$rec['f_db'] = $f_db;
	print json_encode($rec);
}

function m_get_often_list_share()
{
	$sSite		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$o			= isset($_REQUEST["o"]) 		? $_REQUEST["o"] 			: "cnt";
	$od 		= isset($_REQUEST["od"]) 		? $_REQUEST["od"] 			: "dec";
	
	$sFilePath = rs_filter_Site($sFilePath);
	if ( empty($sFilePath) ) die("Error: empty file_path.");

	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();

	if (!POWER_Admin) B_Error(403);
	
	$fDB = rs_con_FilePath2Path_OftenListShareDB($sFilePath);
	$recs = B_Rec_File2Recs($fDB);
	$cnt = count($recs);
	if ($cnt > 0) {
		cmp_o_od($recs, $o, $od);
	}
	$recInfo['cnt'] = $cnt;
	$recInfo['recs'] = $recs;
	print json_encode($recInfo);
}
function m_set_often_list_share()
{
	$sSite		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$acn		= isset($_REQUEST["acn"]) 		? $_REQUEST["acn"] 			: "";
	$sun		= isset($_REQUEST["sun"]) 		? $_REQUEST["sun"] 			: "";
	$mail		= isset($_REQUEST["mail"]) 		? $_REQUEST["mail"] 		: "";
	
	$sFilePath = rs_filter_Site($sFilePath);
	if ( empty($sFilePath) ) die("Error: empty file_path.");
	$acn = strtolower(trim($acn));
	if ( empty($acn) ) die("Error: empty acn.");

	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();

	if (!POWER_Admin) B_Error(403);
	
	$fDB = rs_con_FilePath2Path_OftenListShareDB($sFilePath);
	$recs = B_Rec_File2Recs($fDB, "acn");
	if (isset($recs[$acn]))
		$recs[$acn]['cnt']++; 
	else {
		$recs[$acn]['acn'] = $acn;
		$recs[$acn]['sun'] = $sun;
		$recs[$acn]['mail'] = $mail;
		$recs[$acn]['cnt'] = 1;
	}
	B_Rec_Recs2File($fDB, $recs);
	print return_json("ok");
}

function m_send_mail()
{
	ignore_user_abort(1);
	set_time_limit(0);

	require_once("wmime.php");
	require_once("content-type.php");
	
	$bOut_str	= isset($_REQUEST["out"])		? $_REQUEST["out"]== "str"	: "";
	
	$sSite		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	
	$name 		= isset($_REQUEST["name"]) 		? $_REQUEST["name"] 		: "";
	$from 		= isset($_REQUEST["from"]) 		? $_REQUEST["from"] 		: "";
	$to 		= isset($_REQUEST["to"]) 		? $_REQUEST["to"] 			: "";
	$subject	= isset($_REQUEST["subject"]) 	? $_REQUEST["subject"] 		: "";
	$text		= isset($_REQUEST["text"]) 		? $_REQUEST["text"] 		: "";	// 二選一
	$html		= isset($_REQUEST["html"]) 		? $_REQUEST["html"] 		: "";	// 二選一
	$files		= isset($_REQUEST["files"]) 	? $_REQUEST["files"] 		: "";

	$sFilePath = rs_filter_Site($sFilePath);
	if ( empty($sFilePath) ) die("Error: empty file_path.");

	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();

	if (!POWER_Edit && !POWER_Upload) B_Error(403);
	
	if (empty($from)) {
		if (empty($name))
			$from = "Ookon <nuweb@webasp.monkia.com>";
		else 
			$from = "$name <nuweb@webasp.monkia.com>";
	}
	
	$sMime = "";
	Mime_Add_Head($sMime, $from, $to, $subject);
	$arg = Array();
	$arg['PathHead'] = PATH_Web;
	if (!empty($html)) {
		Mime_Load_Html2($sMime, $html, "", $arg);
	}
	else
		Mime_Add_Html($sMime, htmlspecialchars($text));
	
	if (!empty($files)) {
		$aF = explode(",", $files);
		foreach($aF as $f) {
			list($fp, $fn) = explode(":", rawurldecode($f));
			Mime_Add_File($sMime, PATH_Web.$fp, $fn);
		}
	}
	Mime_Add_Ending($sMime);

if ($bOut_str) {
	echo "html:<br>$html<br>";
	echo "arg:<br>".B_Array2String2($arg)." <br>";
	echo "<br>sMime:<br>".preg_replace("#\r*\n+#", "<br>", htmlspecialchars($sMime))." <br>";
}
	rs_SendMails(Mime_con_String2Mails($to), $sMime);
	print "OK";
}


/////////////////////
// tag
function m_tag()
{
	$sSite		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$sAct 		= isset($_REQUEST["act"]) 		? $_REQUEST["act"] 			: "";
	
	$n 			= isset($_REQUEST["n"]) 		? $_REQUEST["n"] 		: "";
	$fc 		= isset($_REQUEST["fc"]) 		? $_REQUEST["fc"] 		: "";
	$bc 		= isset($_REQUEST["bc"]) 		? $_REQUEST["bc"] 		: "";
	
	$sFilePath = rs_filter_Site($sFilePath);
	if ( empty($sFilePath) ) die("Error: empty file_path.");
	
	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();

	require_once(DIR_TAG."tag_lib.php");
	tag_Init(SYS_SiteName);
	
	switch($sAct) {
		case "upd":
			if (!POWER_Edit) B_Error(403);
			if ( empty($n) ) die("empty n.");
			if ( empty($fc) ) die("empty fc.");
			if ( empty($bc) ) die("empty bc.");
			$aRec = array();
			$aRec['n'] = $n;
			$aRec['fc'] = $fc;
			$aRec['bc'] = $bc;
			$res = tagrec_Upd($aRec);
			print (strpos($res, "Error:") === false ? array("succeed" => $res) : die_json($res));
			break;
			
		case "del":
			if (!POWER_Edit) B_Error(403);
			$res = tagrec_Del($n);
			print (strpos($res, "Error:") === false ? array("succeed" => $res) : die_json($res));
			break;
			
		case "get_list":
			$aRecs = tagrec_load();
			print json_encode($aRecs);
			break;
			
		default:
			die_json("act does not exist.");
	}
}

// Window 7, IE And NUBraim Cookie 不相容, 自動設定 IE Cookie.
function m_set_cookie()
{
	$url	 	= isset($_REQUEST["url"]) 		? $_REQUEST["url"] 			: "";
	$ssn_acn	= isset($_REQUEST["ssn_acn"]) 	? $_REQUEST["ssn_acn"]		: "";
	$local_port = isset($_REQUEST["local_port"])? $_REQUEST["local_port"] 	: "";
	if (empty($ssn_acn)) die("Error: empty ssn_acn.");
	
	// video p2p 用
	if (!empty($local_port)) setcookie("local_port", $local_port, time()+(3600*24), "/");
	
	setcookie("ssn_acn", $ssn_acn, time()+(3600*3), "/");	// 3小時
	if (!empty($url)) {
		header("Location: {$url}");
	}
	else {
		print "<script>setTimeout(function(){window.close();}, 100);</script>";
	}
}
function m_set_cookie_extend()
{
	$expire = time()+(86400*90); // 90天
	$ssn_acn = $_COOKIE["ssn_acn"];
    if (!empty($ssn_acn)) setcookie("ssn_acn", $ssn_acn, $expire, "/");
	$nu_code = $_COOKIE["nu_code"];
    if (!empty($nu_code)) setcookie("nu_code", $nu_code, $expire, "/");
}
function m_get_forum_path()
{
	$UrlPath = pc_find_main_forum();
	if ($UrlPath === false)
		echo "Error: not found forum.";
	else
		echo $UrlPath;
}
// 取得檔案記錄檔內容
function m_get_fileinfo()
{
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$sSite 		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	if ( empty($sFilePath) ) die("Error: empty file_path.");
	if ( empty($sSite) ) 	 die("Error: empty site.");

	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();
	//echo "USER_ACN=".USER_ACN.", Admin=".POWER_Admin.", Show=".POWER_Show.", Upload=".POWER_Upload." <br>";

	$url_fp = "/".URL_WebPages."/".$sFilePath;
	$path_info = rs_con_FilePath2PathInfo( $url_fp );
	if (!empty($path_info['flv'])) {
		$path_info['flv_fs'] = filesize(PATH_Web.$path_info['flv']);
	}
	if (!empty($path_info['mp4']) && file_exists(PATH_Web.$path_info['mp4'])) {
		$path_info['mp4_fs'] = filesize(PATH_Web.$path_info['mp4']);
	}
	$f_rec = PATH_Web.$path_info["rec"];
	$rec = B_Rec_Data2Rec(B_LoadFile($f_rec));
	$rec = array_merge($path_info, $rec);
	
	print B_Rec_Rec2Data($rec);
}
// 向 wns server 取得 NUServer 內外部 IP,Port
function m_get_host_ip()
{
	$acn = isset($_REQUEST["acn"]) ? $_REQUEST["acn"] : "";
	if (empty($acn)) die_json("Error: empty acn");
	
	$info = get_ip($acn);
	if (empty($info))
		die_json("Error: acn does not exist.");
	
	unset($info['ssn']);
	print json_encode($info);
}

function m_get_main_top_bar_info()
{
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$sSite 		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "";
	$sLang 		= isset($_REQUEST["lang_ver"]) 	? $_REQUEST["lang_ver"] 		: "cht";
	if ( empty($sFilePath) ) die("Error: empty file_path.");
	if ( empty($sSite) ) 	 die("Error: empty site.");

	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();
	//echo "USER_ACN=".USER_ACN.", Admin=".POWER_Admin.", Show=".POWER_Show.", Upload=".POWER_Upload." <br>";

	$arg = array();
	$arg['site']		= $sSite;
	$arg['file_path']	= $sFilePath;
	$arg['bAdmin']		= POWER_Admin;
	$arg['bUpload'] 	= POWER_Upload;
	$arg['bManager'] 	= POWER_Manager;
	$arg['site_name']	= SYS_SiteName;
	$arg['file_name'] 	= rs_con_FilePath2FileName($file_path);
	$arg['edit_code'] 	= rs_sys_getEditCode();
	$arg['subscribe_code']= rs_sys_getCode_subscribe($sSite, $sFilePath);
	$arg['url_site_prog']= URL_Site_Prog;
	print json_encode($arg);
}


/////////////////////
// File
function m_file()
{
$fLog = DIR_LOGS."_file_ooki.log";
$qtt = B_GetCurrentTime_usec();
	
    Global $fe_type;
	
	$sSite 		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$sAct 		= isset($_REQUEST["act"]) 		? $_REQUEST["act"] 			: "";
	
	$sFilePath = rs_filter_Site($sFilePath);
	
	// 不須要 file_path
	switch($sAct)
	{
		case "GetClassList":
			file_GetClassList();
			return;
			
		case "GetTagGroup":
			file_GetTagGroup();
			return;
			
		case "GetTpl":
			file_GetTpl();
			return;
			
	}
	
	if ( empty($sFilePath) ) die("Error: empty file_path.");

	// RS Base
	rs_init($sSite, $sFilePath);
	// 取得檢查權限
	rs_power_init();
if (bDebug) echo "POWER_Manager=".POWER_Manager.", USER_ACN=".USER_ACN.", Admin=".POWER_Admin.", Show=".POWER_Show.", Download=".POWER_Download.", Upload=".POWER_Upload.", Edit=".POWER_Edit.", Del=".POWER_Del." <br>";


	// 分散式 Server - 網站不在這台 Server
	if (gs_get_api_data()) {
$tt = B_GetCurrentTime_usec()-$qtt;
B_Log_f($fLog, "m_file:: sAct=$sAct, End 網站不在這台 t=".sprintf("%.3f",$tt).($tt > 4 ? " ***" : ""));
		return;
	}
	
	switch($sAct) {
		case "test_pwd":
			echo "USER_ACN=".USER_ACN.", Admin=".POWER_Admin.", Show=".POWER_Show.", Upload=".POWER_Upload." <br>";
			echo "Cookie:<br>".B_Array2String($_COOKIE)."<br>";
			break;
			
		case "GetPower":
			file_GetPower();
			break;
			
		case "GetPageInfo":
			file_GetPageInfo();
			break;
			
		case "GetPdf":
			if (!POWER_Show) B_Error(403);
			file_GetPdf();
			break;
			
		case "GetPlayList":
			file_GetPlayList();
			break;
			
		case "GetSiteInfo":
			if (!POWER_Admin) B_Error(403);
			file_GetSiteInfo();
			break;
			
		case "GetFileList":
			file_GetFileList();
			break;
		case "GetFileListSort":
			file_GetFileListSort();
			break;
		case "GetMainMenuList":
			file_GetMainMenuList();
			break;
		case "GetFileListRec":
			file_GetFileListRec();
			break;
			
		case "GetDirContent":
			file_GetDirContent();
			break;
			
		case "GetEditInfo":
			file_GetEditInfo();
			break;
			
		case "events":
			file_Events();
			break;
			
		case "article":
			if (!POWER_Edit) B_Error(403);
			file_Article();
			break;
			
		case "SetRec":
		case "SetEditInfo":
			if (!POWER_Edit) B_Error(403);
			file_SetRec();
			break;
			
		case "SetRecs":
			file_SetRecs();
			break;
			
		case "GetContent":
			file_GetContent();
			break;
			
		case "GetComment":
			file_GetComment();
			break;
			
		case "GetCommentRec":
			file_GetCommentRec();
			break;
			
		case "AddComment":
			file_AddComment();
			break;
			
		case "DelComment":
			file_DelComment();
			break;
			
		case "UpdComment":
			file_UpdComment();
			break;
			
		case "GetTagList":
			file_GetTagList();
			break;
			
		case "GetClassList2":
			file_GetClassList2();
$tt = B_GetCurrentTime_usec()-$qtt;
B_Log_f($fLog, "m_file:: sAct=$sAct, End t=".sprintf("%.3f",$tt).($tt > 4 ? " ***" : ""));
			return;
			
		case "SetTag":
			if (!POWER_Edit) B_Error(403);
			file_SetTag();
			break;
			
		case "ClickLike":
			file_ClickLike();
			break;
			
		case "CntView_Increase":
			file_CntView_Increase();
			break;
			
		case "view":
			file_view();
			break;
			
		// 取影片轉出的影片列表
		case "GetVideoList":
			file_GetVideoList();
			break;
			
		// 分享設定: 公開功能
		case "set_public":
			file_set_public();
			break;
		// 分享設定: 分享功能
		case "set_share_code":
			file_set_share_code();
			break;
		// 分享設定: 共用功能
		case "set_use_acn":
			file_set_use_acn();
			break;
		
		// 圖片旋轉
		case "img_rotate":
			file_img_rotate();
			break;
			
		case "video_inport":
			if (!POWER_Edit) B_Error(403);
			file_video_inport();
			break;
			
		default:
			die("Error: not exists act.");
	}
$tt = B_GetCurrentTime_usec()-$qtt;
B_Log_f($fLog, "m_file:: sAct=$sAct, End t=".sprintf("%.3f",$tt).($tt > 4 ? " ***" : ""));
}
// 圖片旋轉
function file_img_rotate()
{
	require_once("rs_image_lib.php");

	$degrees = isset($_REQUEST["degrees"])	? (int)$_REQUEST["degrees"]	: 0;
	if ($degrees == 0 || $degrees >= 360)
		die("Error: Degrees is invalid.");
	if (!file_exists(PATH_FilePath))
		die("Error: File does not exist.");
	
	
	// ODB
	$bODB = ODB_START && !filesize(PATH_FilePath);
	if ($bODB) {
		$tm = filemtime(PATH_FilePath);
		if (odb_api_get_content_to_file(PATH_FilePath, PATH_FilePath) === false)
			die("Error: ODB failed.");
		touch(PATH_FilePath, $tm);
	}
	//
	$tm = filemtime(PATH_FilePath)+1;
	if (img_rotate(PATH_FilePath, PATH_FilePath, $degrees) === false)
		die("Error: Conversion failed.");
	touch(PATH_FilePath, $tm);
	
	$f = PATH_FilePath.".thumbs.jpg";
	if (file_exists($f)) unlink($f);
	sys_extract_tn(PATH_FilePath, $f, 300);
	
	$f = PATH_FilePath.".640.thumbs.jpg";
	if (file_exists($f)) unlink($f);
	sys_extract_tn(PATH_FilePath, $f, 640);
	
	$f = PATH_FilePath.".1920.thumbs.jpg";
	if (file_exists($f)) unlink($f);
	sys_extract_tn(PATH_FilePath, $f, 1920);
	
	
	// 更新修改時間
	$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
	$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
	$rec['md5'] = md5_file(PATH_FilePath);
	$rec['mtime'] = date("YmdHis", $tm);
	$rec['size'] = filesize(PATH_FilePath);
	if (!B_Rec_Rec2File($sfRec, $rec, false))
		die("Error: fail write file.");
	// ODB
	if ($bODB)
		odb_api_upload_file(PATH_FilePath);
	// index 通知 index 更新 rec
	rs_ndx_UpdateRec(FP_FilePath);
	
	print "ok";
}
/* 分享功能:
    set_share_code($file_path, $mode) : 設定|移除|重設 分享功能
        $file_path : 為檔案或目錄完整位置
        $mode : 0 或 1 或 2, 3 (0代表移除,1代表設定,2代表重設,3 GET, 預設為1,若要設定分享可不必傳入 $mode)
        若是設定狀態,會檢查是否已設定過,若已設定過會直接抓取原設定值.
        若是重設狀態,會重新設定,原設定值將會失效.
        (PS. 為考量程式修改方便,原 hidden_url_set 函數仍保留,參數與 set_share_code 相同,程式不用修改應該就可使用分享功能)
		define OFF | ON | RESET 為 0 | 1 | 2
*/
function file_set_share_code()
{
	if (!POWER_Admin) B_Error(403);

	$mode2 = isset($_REQUEST["mode2"])	? (int)$_REQUEST["mode2"]	: ON;
	print set_share_code(PATH_FilePath, $mode2);
}
/*  公開功能: public_lib.php
    set_public($file_path, $public) : 設定或移除公開功能
        $file_path : 為檔案或目錄完整位置
        $public : 0 或 1 (0代表移除,1代表設定,預設為1,若要設定公開可不必傳入 $public)
*/
function file_set_public()
{
	if (!POWER_Admin) B_Error(403);

	$public = isset($_REQUEST["public"])	? (int)$_REQUEST["public"]	: 0;
	print set_public(PATH_FilePath, $public);
}
/* 共用功能:
    set_use_acn($file_path, $acn_list) : 設定或移除共用功能
        $file_path : 為檔案或目錄完整位置
        $acn_list : 擁有共用權限的 user 帳號,此參數是 array (例: $acn_list[0]="jason"; $acn_list[1]="whee";)
        若 acn_list 是空的,代表要移除共用功能(預設為空的)
*/
function file_set_use_acn()
{
	if (!POWER_Admin) B_Error(403);

	$acn_list = isset($_REQUEST["acn_list"])	? $_REQUEST["acn_list"]	: "";
	$aList = explode(",",$acn_list);
	print set_use_acn(PATH_FilePath, $aList);
}
function file_GetPlayList()
{
	$play_list = rs_con_FilePath2PathInfo_VideoTV(FP_FilePath);
	if ($play_list === false)
		$play_list = array();
	
	$play_list['bHtml5'] = rs_is_HTML5();
	$play_list['bInternalNetwork'] = rs_is_LocalLan();
if (bDebug) echo "play_list:<br>".B_Array2String2($play_list)." <br>";
	print json_encode($play_list);
}
function file_GetPdf()
{
	$random_path = isset($_REQUEST["random_path"])	? $_REQUEST["random_path"]	: "";
	$ver 		 = isset($_REQUEST["ver"])			? $_REQUEST["ver"]			: "";

	$f = PATH_WebPages."/".FP_FilePath;
	$ufp = "/Site/".FP_FilePath;
	if (!file_exists($f))
		B_Error(404);
	// 是 pdf 檔案直接顯示
	if (preg_match("#\.pdf$#i", $f)) {
		header("Location:$ufp");
		return;
	}
	
	//$fpdf = get_file_ver_path($f, $ver);
	
	$f_pdf = rs_con_FilePath2FilePDF_ver($f, $ver);
	$u_pdf = rs_con_FilePath2FilePDF_ver($ufp, $ver);
	if (!file_exists($f_pdf)) $res = doc2pdf($f);
if (bDebug) {
	echo "res=$res <br>";	
	echo "f=$f <br>";	
	echo "f_pdf=$f_pdf <br>";	
	echo "u_pdf=$u_pdf <br>";	
	echo "file_exists=".file_exists($f_pdf)." <br>";	
	return;
}
	if (file_exists($f_pdf))
	{
		header("Location:$u_pdf");
	}
	else
	{
		$url = preg_replace("#\/*$#","",get_server_url()).$ufp."?nu_code=".$_COOKIE['nu_code'];
		if (!empty($random_path)) $url .= "&random_path=".$random_path;
		$u_gle = "http://docs.google.com/viewer?url=".rawurlencode($url)."&embedded=true";
		header("Location:$u_gle");
	}
	
}
function file_GetPower()
{
	global $reg_conf;
	// 成員目錄中的自己目錄
	$bMemDirME = rs_is_memberDirToMe(FP_FilePath);
	
	$out = array();
	$out['SYS_OS'] 		= SYS_OS;
	$out['lang'] 		= SYS_Lang;
	
	$out['bManager'] 	= POWER_Manager;
	$out['bAdmin'] 		= POWER_Admin;
	$out['bShow'] 		= POWER_Show;
	$out['bDownload'] 	= POWER_Download;
	$out['bUpload'] 	= POWER_Upload;
	$out['bEdit'] 		= POWER_Edit;
	$out['bDel'] 		= POWER_Del;
	$out['bPWD'] 		= rs_dir_is_PWD(FP_FilePath);
	$out['bNeedPwd'] 	= POWER_NeedPwd;
	$out['bNeedSPwd'] 	= POWER_NeedSPwd;
	$out['bMemDirME'] 	= $bMemDirME;
	
	$out['bExists'] 	= file_exists(DIR_SITE.FP_FilePath);
	$out['bDir'] 		= PATH_IS_DIR;
	$out['bSiteWeb']	= rs_is_SiteWeb();
	$out['bOdbStart']	= ODB_START;
	$out['user'] 		= USER_ACN;
	$out['user_ssn'] 	= USER_SSN;
	$out['user_sun'] 	= USER_SUN;
	$out['user_mail'] 	= USER_MAIL;
	$out['server_acn'] 	= SERVER_ACN;
	$out['server_sun']	= $reg_conf['sun'];
	$out['server_url']	= preg_replace("#\/*$#","",get_server_url()); // 去除後面的斜
	$out['site_name'] 	= SYS_SiteName;
	
	if (isset($_REQUEST["callback"]))
		print $_REQUEST["callback"]."(".json_encode($out).")";
	else
		print json_encode($out);
}
// 取得網頁的訊息
function file_GetPageInfo()
{
	$arg = array();
	$arg['random_path']	= isset($_REQUEST["random_path"])	? $_REQUEST["random_path"]			: "";
	$arg['get_content'] = isset($_REQUEST["get_content"])	? $_REQUEST["get_content"] 	== "y"	: false;
	$arg['get_cnt_cmn'] = isset($_REQUEST["get_cnt_cmn"])	? $_REQUEST["get_cnt_cmn"] 	== "y"	: false;
	$arg['server_ip'] 	= isset($_REQUEST["server_ip"])		? $_REQUEST["server_ip"] 	== "y"	: false;
	
	$out = rs_getPageInfo($arg);
if (bDebug) echo B_Array2String2($out)." <br><br>";
	
	if (isset($_REQUEST["callback"]))
		print $_REQUEST["callback"]."(".json_encode($out).")";
	else
		print json_encode($out);
	
}
function file_GetSiteInfo()
{
	$info = rs_getSiteInfo(SYS_SiteName);
	print json_encode($info);
}
function file_GetFileList()
{
	require_once("rs_dbv_lib.php");
	
	$type	= isset($_REQUEST["type"])	? $_REQUEST["type"]		: "";
	$o		= isset($_REQUEST["o"])		? $_REQUEST["o"]		: "";
	$od		= isset($_REQUEST["od"])	? $_REQUEST["od"]		: "";
	
	if (POWER_Show) {
		// dbv_getFileListInfo_Index($sFilePath, $o="", $od="", $type="", $level=0, $bAdmin=true)
		$out = dbv_getFileListInfo_Index(FP_FileDir, $o, $od, $type, 0, POWER_Admin);
	}
	else {
		$out = array(
			'dir' => array()
			,'file' => array()
		);
	}
	
	$arg['bManager'] 	= POWER_Manager;
	$arg['bAdmin'] 		= POWER_Admin;
	$arg['bShow'] 		= POWER_Show;
	$arg['bDownload'] 	= POWER_Download;
	$arg['bUpload'] 	= POWER_Upload;
	$arg['bEdit'] 		= POWER_Edit;
	$arg['bDel'] 		= POWER_Del;
	$arg['bPWD'] 		= rs_dir_is_PWD(FP_FilePath);

if (bDebug) {
	echo "FP_FileDir=".FP_FileDir."<br>";
	echo "out:<br>".B_Array2String2($out)."<br><br><br>";
}
	
	print json_encode($out);
}
// 取得有排序過的 File List
// define("DIR_TYPE", "R");
// define("VIDEO_TYPE", "V");
// define("AUDIO_TYPE", "A");
// define("IMAGE_TYPE", "I");
// define("DOC_TYPE", "D");
// define("TEXT_TYPE", "T");
// define("HTML_TYPE", "H");
// define("LINK_TYPE", "L");
// define("OTHER_TYPE", "O");
// get_sub_list($page_url, $type)
function file_GetFileListSort()
{
	$type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : "";
	
	$recs = array();
	$list = get_sub_list("/Site/".FP_FileDir, $type); // public_lib.php
	$cnt = count($list);
	for($x=0; $x<$cnt; $x++) {
if (bDebug) echo "list:<br>".B_Array2String2($list[$x])." <br><br>";
		$path = $list[$x]['path'];
		$fp = rs_con_UrlPath2FilePath(rs_con_FileReal2Url($path));
		
		$f_rec = PATH_WebPages."/".rs_con_FilePath2FileRec($fp);
		
		$rec = B_Rec_Data2Rec(B_LoadFile($f_rec));
		if (empty($rec['url']))			$rec['url'] = "/Site/".$fp;
		
		$recs[] = $rec;
	}
if (bDebug) echo "type=$type <br>";
if (bDebug) echo "FP_FileDir=".FP_FileDir." <br><br>";
if (bDebug) echo "recs:<br>".B_Array2String2($recs)." <br><br>";
	
	$out = array();
	$out['cnt'] = count($recs);
	$out['recs'] = $recs;
	print json_encode($out);
}
function file_GetMainMenuList()
{
	// 過濾掉 
	$aDenySelect	= array();
	$aDenyTag 		= array();	// [$k=>$v, ]
	$aDenySelect['filename'] = '.files';
	$aDenyTag['page_name'] = 'Driver';
	
	$arg = array(
		'dir' 		=> FP_FileDir,
		'type' 		=> "Directory",
		'o' 		=> "mtime",
		'od' 		=> "inc",
	);
	// 拒絕條件
	$arg["aDenySelect"]		= $aDenySelect; 
	$arg["aDenyTag"]		= $aDenyTag;
	//
	$res = file_GetFileListRec_dbman($arg);
// if (bDebug) echo "res:<br>".B_Array2String2($res)." <br><br>";
	$recs = $res['recs'];
	
	$f_list = DIR_SITE.SITE_NAME."/.nuweb_menu";
if (bDebug) echo "f_list=$f_list <br>";
	if (file_exists($f_list)) {
		$list = file($f_list);
if (bDebug) echo "list:<br>".B_Array2String2($list)." <br><br>";
	}
	
	if (empty($list)) {
		foreach($recs as $n => $rec) {
			if ($rec['filename'] == "Friend") continue;
			
			$recs[$n]['bShow'] = true;
		}
	}
	else {
 		$recsSort = array();
		foreach($list as $fn) {
			$fn = trim($fn);
			if ($fn == "Friend") continue;
			
			$n = B_obj_indexOf($recs, "page_name", $fn);
			if ($n !== false) {
				$rec = $recs[$n];
				$rec['bShow'] = true;
				
				$recsSort[] = $rec;
				unset($recs[$n]);
			}
		}
		foreach($recs as $n => $rec) {
			if ($rec['filename'] == "Friend") continue;
			
			$rec['bShow'] = false;
			$recsSort[] = $rec;
		}
		$recs = $recsSort;
	}
	//
	$sDir = FP_FileDir;
	rs_con_Recs2OutRecs($recs, false, $sDir);

if (bDebug) echo "recs:<br>".B_Array2String2($recs)." <br><br>";
	$out = array();
	$out['cnt'] = count($recs);
	$out['recs'] = $recs;
	print json_encode($out);
}
function file_GetFileListRec()
{
if (bDebug) echo "*** file_GetFileListRec *** <br>";


	require_once("rs_dbv_lib.php");

	$type	= isset($_REQUEST["type"])	? $_REQUEST["type"]		: "";
	$mtime	= isset($_REQUEST["mtime"])	? $_REQUEST["mtime"]	: "";
	$class	= isset($_REQUEST["class"])	? $_REQUEST["class"]	: "";
	$subclass=isset($_REQUEST["subclass"])?$_REQUEST["subclass"]: "";
	$tag	= isset($_REQUEST["tag"])	? $_REQUEST["tag"]		: "";
	$ftag	= isset($_REQUEST["ftag"])	? $_REQUEST["ftag"]		: "";
	$o		= isset($_REQUEST["o"])		? $_REQUEST["o"]		: "";
	$od		= isset($_REQUEST["od"])	? $_REQUEST["od"]		: "";
	$p		= isset($_REQUEST["p"])		? $_REQUEST["p"]		: 1;
	$ps		= isset($_REQUEST["ps"])	? $_REQUEST["ps"]		: 999999;

	$bRecInfo	= isset($_REQUEST["getRecInfo"])? $_REQUEST["getRecInfo"] == 'y'	: false; // 取原始的記錄檔
	$bview_path	= isset($_REQUEST["view_path"])	? $_REQUEST["view_path"] == 'y'		: false; // 顯示路徑名稱
	$bMIME		= isset($_REQUEST["mime"])		? $_REQUEST["mime"] == 'y'			: false; // 取 MIME
	$bUpload	= isset($_REQUEST["pw_upload"])	? $_REQUEST["pw_upload"] == 'y'		: false; // 取 目錄的上傳權限
	
	if ($bMIME) require_once("content-type.php");
	if ($bUpload) $page_dir = PATH_WebPages."/";
	
	if (POWER_Show) {
	
		$arg = array(
			'dir' 		=> FP_FileDir,
			'type' 		=> $type,
			'mtime' 	=> $mtime,
			'class' 	=> $class,
			'subclass' 	=> $subclass,
			'tag' 		=> $tag,
			'ftag' 		=> $ftag,
			'o' 		=> $o,
			'od' 		=> $od,
			'p' 		=> $p,
			'ps' 		=> $ps
		);
		$res = file_GetFileListRec_dbman($arg);
	
		//$rows = dbv_getFileListRec(FP_FileDir, $o, $od, $type);
		rs_con_Recs2OutRecs($res['recs'], $bview_path, FP_FileDir);
		// 取原始的記錄檔
		if ($bRecInfo) $res['recs'] = search_con_GetFileRecInfo($res['recs']);
		
		// 過濾掉沒有欄位 _i 的資料
		$recs = array();
		foreach($res['recs'] as $rec) {
			if ($bMIME && isset($rec['filename'])) {
				$type = $rec['type'];
				if ($type != "Directory" && $type != "Html" && $type != "BBS" && $type != "Bookmark") {
					$ext = strtolower(B_GetExtension($rec['filename']));
					$rec['MIME'] = con_Extension2ContentType($ext);
				}
			}
			// 取 目錄的上傳權限
			if ($bUpload && $rec['type'] == "Directory") {
				$fp = rs_con_UrlPath2FilePath($rec['url']);
				$rec['pw_upload'] = chk_upload_right($page_dir, $fp) == PASS;
			}
			$recs[] = $rec;
		}
		
	}
	
	$out = array();
	$out["cnt"] = $res['cnt'];
	$out["recs"] = $recs;
if (bDebug) {
	echo "POWER_Manager=".POWER_Manager.", USER_ACN=".USER_ACN.", Admin=".POWER_Admin.", Show=".POWER_Show.", Upload=".POWER_Upload." <br>";
	echo 	"FP_FileDir=".FP_FileDir." <br>";
	echo 	"type=$type, o=$o, od=$od<br>";
	echo 	"out:<br>".B_Array2String2($out)
			."<br><br>";
}
	print json_encode($out);
}

function file_GetFileListRec2()
{
	require_once("rs_dbv_lib.php");
	
define("bDebug", isset($_REQUEST["debug"]) ? true : false);

	$type	= isset($_REQUEST["type"])	? $_REQUEST["type"]		: "";
	$tag	= isset($_REQUEST["tag"])	? $_REQUEST["tag"]		: "";
	$ftag	= isset($_REQUEST["ftag"])	? $_REQUEST["ftag"]		: "";
	$o		= isset($_REQUEST["o"])		? $_REQUEST["o"]		: "";
	$od		= isset($_REQUEST["od"])	? $_REQUEST["od"]		: "";

	$bRecInfo	= isset($_REQUEST["getRecInfo"])? $_REQUEST["getRecInfo"] == 'y'	: false; // 取原始的記錄檔
	$bview_path	= isset($_REQUEST["view_path"])	? $_REQUEST["view_path"] == 'y'		: false; // 顯示路徑名稱
	$bMIME		= isset($_REQUEST["mime"])		? $_REQUEST["mime"] == 'y'			: false; // 取 MIME
	$bUpload	= isset($_REQUEST["pw_upload"])	? $_REQUEST["pw_upload"] == 'y'		: false; // 取 目錄的上傳權限
	
	if ($bMIME) require_once("content-type.php");
	if ($bUpload) $page_dir = PATH_WebPages."/";
	
	if (POWER_Show) {
		$rows = dbv_getFileListRec(FP_FileDir, $o, $od, $type);
		rs_con_Recs2OutRecs($rows, $bview_path, FP_FileDir);
		// 取原始的記錄檔
		if ($bRecInfo) $rows = search_con_GetFileRecInfo($rows);
		
		// 過濾掉沒有欄位 _i 的資料
		$recs = array();
		foreach($rows as $rec) {
			if ($bMIME && isset($rec['filename'])) {
				$type = $rec['type'];
				if ($type != "Directory" && $type != "Html" && $type != "BBS" && $type != "Bookmark") {
					$ext = strtolower(B_GetExtension($rec['filename']));
					$rec['MIME'] = con_Extension2ContentType($ext);
				}
			}
			// 取 目錄的上傳權限
			if ($bUpload && $rec['type'] == "Directory") {
				$fp = rs_con_UrlPath2FilePath($rec['url']);
				$rec['pw_upload'] = chk_upload_right($page_dir, $fp) == PASS;
			}
			$recs[] = $rec;
		}
		
	}
	
	$out = array();
	$out["cnt"] = count($recs);
	$out["recs"] = $recs;
if (bDebug) {
	echo "POWER_Manager=".POWER_Manager.", USER_ACN=".USER_ACN.", Admin=".POWER_Admin.", Show=".POWER_Show.", Upload=".POWER_Upload." <br>";
	echo 	"FP_FileDir=".FP_FileDir." <br>";
	echo 	"type=$type, o=$o, od=$od<br>";
	echo 	"out:<br>".B_Array2String2($out)
			."<br><br>";
}
	print json_encode($out);
}

function file_GetDirContent()
{
	$dir_type	= isset($_REQUEST["dir_type"])	? $_REQUEST["dir_type"]	: "";
	if ($dir_type == "forum")
	{
		$f = PATH_WebPages."/".FP_FileDir."/ArticleAll";
		$rows = B_Rec_File2Recs($f);
		$recs = array();
		foreach($rows as $rec) {
			$recs[] = array(
				'id' => $rec['fp'].'-1'	//@id:GROUP_ALL/2013041-1
				,'time' => $rec['rt']
				,'page_name' => 'forum_view.php?mode=far&path='.$aRec['d']
								.'&f='.$aRec['f'].'&i=1'
				,'author' => $rec['a']
				,'title' => $rec['T']
				,'type' => 'BBS'
			);
		}
		cmp_o_od($recs, "time", "dec");
	}
	else if ($dir_type == "bookmark")
	{
		$f = PATH_WebPages."/".FP_FileDir."/db/data_rec";
		$rows = B_Rec_File2Recs($f);
		$recs = array();
		foreach($rows as $rec) {
			$recs[] = array(
				'id' => $rec['id']
				,'time' => $rec['tm']
				,'page_name' => 'index.php?mode=view&id='.$rec['id']
				,'url_push' => $rec['url']
				,'title' => $rec['title']
				,'type' => 'Bookmark'.(rs_is_Youtube_Url($rec['url']) ? ',Youtube' : '')
			);
		}
		cmp_o_od($recs, "time", "dec");
	}
	else
	{
		die_json("Error: dir_type.");
	}
	
	$out = array();
	$out["cnt"] = count($recs);
	$out["recs"] = $recs;
	print json_encode($out);
}
// 取得網頁的樣版
function file_GetTpl()
{
	global $def_lang;

	$type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : "";
	
	$out = array();
	if ($type == "blog")
	{
		$f = DIR_TOOLS."page/template/blog.tpl.".$def_lang;
		$tpl = B_LoadFile($f);
		$out['item'] = B_GetSection($tpl, "<!-- START BLOCK : HTML_ITEM -->", 	"<!-- END BLOCK : HTML_ITEM -->", true, false);
		$out['bn_del'] = B_GetSection($out['item'], "<!-- START BLOCK : MANAGE_ITEM -->", 	"<!-- END BLOCK : MANAGE_ITEM -->", true, true);
	}
	print json_encode($out);
}
// 取影片轉出的影片列表
function file_GetVideoList()
{
	$VideoInfo = rs_con_FilePath2PathInfo_Video(FP_FilePath);
	$VideoInfo['bInternalNetwork'] = rs_is_LocalLan();
	print json_encode($VideoInfo);
}
function file_view()
{
	$type		= isset($_REQUEST["type"])		? $_REQUEST["type"]		: "";
	$range		= isset($_REQUEST["range"])		? $_REQUEST["range"]	: "";
	$subsite	= isset($_REQUEST["subsite"])	? $_REQUEST["subsite"]	: "";
	$back_url	= isset($_REQUEST["back_url"])	? $_REQUEST["back_url"]	: "";

	if ($type == "Image")
	{
		$recType = rs_getDirTypeRec(FP_FilePath);
		$DirType = $recType['DIR_TYPE'];
		if ($DirType == "OokonAlbum") {
			$url = "/tools/page/show_page.php"
						."?page_url=/".URL_Path
						."&file=/".URL_FilePath;
		} else {
			$url = "/tools/pv/pv_album.php"
						."?site=".URL_Site
						."&file_path=".FP_FilePath
						."&range=".$range
						."&subsite=".$subsite
						."&back_url=".rawurlencode($back_url);
		}
	}
	else if ($type == "Html")
	{
		$recType = rs_getDirTypeRec(FP_FilePath);
		$DirType = $recType['DIR_TYPE'];
		if ($DirType == "OokonBlog") {
			$url = "/tools/page/show_page.php"
						."?page_url=/".URL_Path
						."&file=/".URL_FilePath;
		} else {
			$url = "/".URL_FilePath;
		}
	}
	else if ($type == "Video")
	{
		$url = "/tools/pv/pv_video.php"
					."?site=".URL_Site
					."&file_path=".FP_FilePath
					."&range=".$range
					."&subsite=".$subsite
					."&back_url=".rawurlencode($back_url);
	}
	else
	{
		$url = "/".URL_FilePath;
	}
//echo $url;
	header("Location: {$url}");
}
function file_GetEditInfo()
{
	$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
	$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
	
	$out = array();
	$out['class'] = (isset($rec['class']) ? $rec['class'] : "");
	$out['tag'] = (isset($rec['tag']) ? $rec['tag'] : "");
	print json_encode($out);
}
function file_Events()
{
	$title 		= isset($_REQUEST["title"])		? stripslashes($_REQUEST["title"])	: "";
	$desc 		= isset($_REQUEST["desc"])		? stripslashes($_REQUEST["desc"])	: "";
	$t_start 	= isset($_REQUEST["t_start"])	? stripslashes($_REQUEST["t_start"]): "";
	$t_end	 	= isset($_REQUEST["t_end"])		? stripslashes($_REQUEST["t_end"])	: "";
	$place	 	= isset($_REQUEST["place"])		? stripslashes($_REQUEST["place"])	: "";
	
	// 動作 [add / upd]
	$mm 		= isset($_REQUEST["mm"])		? $_REQUEST["mm"]			: "";

	if (empty($mm)) die("Error: empty mm");
	
	$page_dir = PATH_WebPages."/";
	if ($mm == "add")
	{
		if (!POWER_Admin) B_Error(403);
		
		if (empty($title)) die("Error: empty title");
		
		//$name = mb_strimwidth($title, 0, 64, "", "UTF-8");
		$name = B_File_FilterName($title);
		$res = file_GetFileRec_fn(FP_FilePath, $name);
		if ($res !== false)
			die("Error: The title already exists");
		
		// 建立活動目錄
		$path = FP_FilePath;
		$dir_type = "events";
		$real_dir_name = "";
		$dir = new_dir($page_dir, $path, $name, $dir_type, $real_dir_name, false, false);
		if (empty($dir))
			die("Error: creation failed");
		
		
			$path = $dir;
			// 建立 隱藏留言版
			$name = ".nuweb_forum";
			$dir_type = "forum";
			$real_dir_name = ".nuweb_forum";
			new_dir($page_dir, $path, $name, $dir_type, $real_dir_name, false, false);
			// 建立 相簿
			$name = "相簿";
			$dir_type = "directory";
			$real_dir_name = "";
			new_dir($page_dir, $path, $name, $dir_type, $real_dir_name, false, false);
			// 建立 行事曆
			$name = "行事曆";
			$dir_type = "calendar";
			$real_dir_name = "Calendar";
			new_dir($page_dir, $path, $name, $dir_type, $real_dir_name, false, false);
			
		
		
		$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec($dir));
		$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
if (bDebug) echo "dir=$dir <br>";
if (bDebug) echo "sfRec=$sfRec <br>";
if (bDebug) echo "rec:<br>".B_Array2String2($rec)." <br>";
		
		$rec['title'] = $title;
		$rec['description'] = $desc;
		$rec['t_start'] = $t_start;
		$rec['t_end'] = $t_end;
		$rec['place'] = $place;
		
		$rec['tag'] = B_tag_add($rec['tag'], "SYS_DIR_EVENTS");
if (bDebug) echo "<br><br> rec:<br>".B_Array2String2($rec)." <br>";
		
		if (!B_Rec_Rec2File($sfRec, $rec, false))
			die("Error: fail write file.");
		// 通知 index 更新 rec
		rs_ndx_UpdateRec($dir, "update");
		
		// 送出 動態訊息
		upload_dymanic_share_rec($page_dir, $dir, "new");

		print return_json($dir);
	}
	else if ($mm == "upd")
	{
		if (!POWER_Admin) B_Error(403);
		
		$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
		$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
if (bDebug) echo "sfRec=$sfRec <br>";
if (bDebug) echo "rec:<br>".B_Array2String2($rec)." <br>";
		
		$rec['title'] = $title;
		$rec['description'] = $desc;
		$rec['t_start'] = $t_start;
		$rec['t_end'] = $t_end;
		$rec['place'] = $place;
		
		$rec['tag'] = B_tag_add($rec['tag'], "SYS_DIR_EVENTS");
if (bDebug) echo "<br><br> rec:<br>".B_Array2String2($rec)." <br>";
		
		if (!B_Rec_Rec2File($sfRec, $rec, false))
			die("Error: fail write file.");
		// 通知 index 更新 rec
		rs_ndx_UpdateRec(FP_FilePath, "update");

		
		$out = rs_getPageInfo();
		print json_encode($out);
	}
	else if ($mm == "join")
	{
		$user = USER_ACN;
		$val = isset($_REQUEST["val"]) ? $_REQUEST["val"] : "";
		
		if (empty($user)) B_Error(403);
		if (empty($val)) die("Error: empty val");
		
		
		$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
		$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
if (bDebug) echo "sfRec=$sfRec <br>";
if (bDebug) echo "rec:<br>".B_Array2String2($rec)." <br>";
		if ($val == "y") {
			$rec['evn_join_y'] 	= B_tag_add($rec['evn_join_y'], $user);
			$rec['evn_join_p'] 	= B_tag_del($rec['evn_join_p'], $user);
			$rec['evn_join_n'] 	= B_tag_del($rec['evn_join_n'], $user);
			$rec['evn_invite'] 	= B_tag_del($rec['evn_invite'], $user);
			$rec['evn_view'] 	= B_tag_del($rec['evn_view'], 	$user);
		}
		else if ($val == "p") {
			$rec['evn_join_y'] 	= B_tag_del($rec['evn_join_y'], $user);
			$rec['evn_join_p'] 	= B_tag_add($rec['evn_join_p'], $user);
			$rec['evn_join_n'] 	= B_tag_del($rec['evn_join_n'], $user);
			$rec['evn_invite']	= B_tag_del($rec['evn_invite'], $user);
			$rec['evn_view'] 	= B_tag_del($rec['evn_view'], 	$user);
		}
		else if ($val == "n") {
			$rec['evn_join_y'] 	= B_tag_del($rec['evn_join_y'], $user);
			$rec['evn_join_p'] 	= B_tag_del($rec['evn_join_p'], $user);
			$rec['evn_join_n'] 	= B_tag_add($rec['evn_join_n'], $user);
			$rec['evn_invite'] 	= B_tag_del($rec['evn_invite'], $user);
			$rec['evn_view'] 	= B_tag_del($rec['evn_view'], 	$user);
		}
		// 已瀏覽名單
		else if ($val == "v") {
			if (B_tag_is_exists($rec['evn_invite'], $user))
				$rec['evn_view'] = B_tag_add($rec['evn_view'], $user);
		}
		
if (bDebug) echo "<br><br> rec:<br>".B_Array2String2($rec)." <br>";
		
		if (!B_Rec_Rec2File($sfRec, $rec, false))
			die("Error: fail write file.");
		// 通知 index 更新 rec
		rs_ndx_UpdateRec(FP_FilePath, "update", true);

		print json_encode($rec);
	}
	// 邀請
	else if ($mm == "invite")
	{
		$user = USER_ACN;
		$val = isset($_REQUEST["val"]) ? $_REQUEST["val"] : "";
		
		if (!POWER_Admin) B_Error(403);
		if (empty($val)) die("Error: empty val");
		
		
		$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
		$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
if (bDebug) echo "sfRec=$sfRec <br>";
if (bDebug) echo "rec:<br>".B_Array2String2($rec)." <br>";

		$list = explode(",", $val);
		foreach($list as $u) {
			$u = trim($u);
			if (empty($u)) continue;
			if (B_tag_is_exists($rec['evn_join_y'], $u)
				|| B_tag_is_exists($rec['evn_join_p'], $u)
				|| B_tag_is_exists($rec['evn_join_n'], $u)
				) continue;
				
			$rec['evn_invite'] = B_tag_add($rec['evn_invite'], $u);
		}
		
if (bDebug) echo "<br><br> rec:<br>".B_Array2String2($rec)." <br>";
		
		if (!B_Rec_Rec2File($sfRec, $rec, false))
			die("Error: fail write file.");
		// 通知 index 更新 rec
		rs_ndx_UpdateRec(FP_FilePath, "update", true);

		print json_encode($rec);
	}
	// 邀請
	else if ($mm == "del_join")
	{
		$user	= USER_ACN;
		$join	= isset($_REQUEST["join"])	? $_REQUEST["join"]	: "";
		$val	= isset($_REQUEST["val"])	? $_REQUEST["val"]	: "";
		
		if (!POWER_Admin) B_Error(403);
		if (empty($join)) die("Error: empty join");
		if (empty($val)) die("Error: empty val");
		
		$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
		$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
if (bDebug) echo "sfRec=$sfRec <br>";
if (bDebug) echo "rec:<br>".B_Array2String2($rec)." <br>";

		if ($join == "i")
			$key = 'evn_invite';
		else
			$key = 'evn_join_'.$join;
		if (isset($rec[$key])) {
			$rec[$key] 	= B_tag_del($rec[$key], $val);
			
			if (!B_Rec_Rec2File($sfRec, $rec, false))
				die("Error: fail write file.");
			// 通知 index 更新 rec
			rs_ndx_UpdateRec(FP_FilePath, "update");
		}

		print json_encode($rec);
	}
	else
	{
		die("Error: invalid mm");
	}
	
}
function file_Article()
{
	$title 		= isset($_REQUEST["title"])		? stripslashes($_REQUEST["title"])	: "";
	$content 	= isset($_REQUEST["content"])	? stripslashes($_REQUEST["content"]): "";
	$desc 		= isset($_REQUEST["desc"])		? stripslashes($_REQUEST["desc"])	: "";
	
	// 動作 [add / upd]
	$mm 		= isset($_REQUEST["mm"])		? $_REQUEST["mm"]			: "";
	// 內文是純文字
	$c_type 	= isset($_REQUEST["c_type"])	? $_REQUEST["c_type"]		: "";
	// 有重複 [y(改寫) / rename(自動加序號) / (錯誤)]
	$rewrite	= isset($_REQUEST["rewrite"])	? $_REQUEST["rewrite"]		: "";

//echo "file_Article FP_FilePath=".FP_FilePath." <br>";

	if (empty($mm)) die("Error: empty mm");
	$bText = $c_type == "text";
	
	if ($mm == "add" || $mm == "upd")
	{
		if ($mm == "add") {
			if ($bText) {
				$content = trim($content);
				if (empty($content)) die("Error: empty content");
				if (empty($title)){
					$aC = explode("\n", $content);
					foreach($aC as $s){
						$s = trim($s);
						if (empty($s)) continue;
						$title = $s;
						break;
					}
					if (empty($title)) die("Error: invalid content");
				}
				$title = mb_strimwidth($title, 0, 64, "", "UTF-8");
				// Text 2 Html
				$content = preg_replace("#\r*\n#", "<br>", htmlspecialchars($content));
			}
			// Html
			else {
				if (empty($title)) die("Error: empty title");
				if (empty($content)) die("Error: empty content");
			}
			$fn = B_File_FilterName($title).".html";
			$mode = "new_article";
			
			// 已經存在了
			$rec = file_GetFileRec_fn(FP_FilePath, $fn);
			if ($rec !== false) {
				// 改寫
				if ($rewrite == "y") {
					$mode = "update_article";
					$fn = $rec['page_name'];
				}
				// 自動加序號
				else if ($rewrite == "rename") {
					$id = 0;
					$name = B_GetFileName($fn);
					$ext = B_GetExtension($fn);
					if (!empty($ext)) $ext = ".".$ext;
					do {
						++$id;
						$fn = $name.sprintf("_%02d",$id).$ext;
						$rec = file_GetFileRec_fn(FP_FilePath, $fn);
					} while($rec !== false);
				}
				else {
					die("Error: 檔名已經存在了.");
				}
			}
		}
		else {
			$fn = "";
			$mode = "update_article";
		}
		
		$content = file_con_content_inport($content, $title, $desc);
		
		$InHeader = array();
		$InHeader['url'] = "http://localhost".API_edit_api;
		$InHeader['Cookie'] = rs_sys_getCookis_Power();
		$arg = array();
		$arg['site'] = "Site";
		$arg['mode'] = $mode;
		$arg['lang_ver'] = SYS_Lang;
		$arg['code'] = "nuweb_editpass";
		$arg['path'] = FP_FilePath;
		$arg['name'] = $fn;
		$arg['content'] = $content;
		$res = B_curl($InHeader, $arg);
		print $res;
	}
	else
	{
		die("Error: invalid mm");
	}
}
function file_SetRec()
{
	$bUpdate = false;
	$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
	$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
	
	$bMsgUploadTime = false;
	// Title
	if (isset($_REQUEST["title"])) {
		$sTitle = stripslashes($_REQUEST["title"]);
		if ($rec['title'] != $sTitle) {
			$rec['title'] = $sTitle;
			$bUpdate = true;
			$bMsgUploadTime = true;
		}
	}
	// Description
	if (isset($_REQUEST["description"])) {
		$sDesc = stripslashes($_REQUEST["description"]);
		if ($rec['description'] != $sDesc) {
			$rec['description'] = $sDesc;
			$bUpdate = true;
			$bMsgUploadTime = true;
		}
	}
	// Memo
	if (isset($_REQUEST["memo"])) {
		$memo = stripslashes($_REQUEST["memo"]);
		if ($rec['memo'] != $memo) {
			$rec['memo'] = $memo;
			$bUpdate = true;
			$bMsgUploadTime = true;
		}
	}
	// Class
	if (isset($_REQUEST["class"])) {
		$sClass = stripslashes($_REQUEST["class"]);
		if ($rec['class'] != $sClass) {
			$rec['class'] = $sClass;
			$bUpdate = true;
		}
	}
	// Tag
	$bUpdTag = false;
	if (isset($_REQUEST["tag"])) {
		$sTag = file_tag_ClearSpace(stripslashes($_REQUEST["tag"]));
		if ($rec['tag'] != $sTag) {
			// Tag_Object
			require_once(DIR_TAG."tag_lib.php");
			tag_Init(SYS_SiteName);
			$tag_new = $sTag;
			$tag_old = $rec['tag'];
			$type = tag_conRec2Type($rec);
			tag_append($type, $tag_new, $tag_old, FP_FilePath);
			
			$rec['tag'] = $sTag;
			$bUpdate = true;
			$bUpdTag = true;
		}
	}
	// Image
	if (isset($_REQUEST["img"])) {
		$sImg = stripslashes($_REQUEST["img"]);
		if ($rec['img'] != $sImg) {
			$rec['img'] = $sImg;
			$bUpdate = true;
		}
	}
	// Image Pos Top
	if (isset($_REQUEST["img_pos_top"])) {
		$sImgPosTop = stripslashes($_REQUEST["img_pos_top"]);
		if ($rec['img_pos_top'] != $sImgPosTop) {
			$rec['img_pos_top'] = $sImgPosTop;
			$bUpdate = true;
		}
	}
	// Link Url
	if (isset($_REQUEST["link_url"])) {
		$sLink = stripslashes($_REQUEST["link_url"]);
		if ($rec['link_url'] != $sLink) {
			$rec['link_url'] = $sLink;
			$bUpdate = true;
		}
	}
	// fb Facebook
	if (isset($_REQUEST["fb"])) {
		$sFB = stripslashes($_REQUEST["fb"]);
		if ($rec['fb'] != $sFB) {
			$rec['fb'] = $sFB;
			$bUpdate = true;
		}
	}
	// job 職業
	if (isset($_REQUEST["job"])) {
		$sJob = stripslashes($_REQUEST["job"]);
		if ($rec['job'] != $sJob) {
			$rec['job'] = $sJob;
			$bUpdate = true;
		}
	}
	// 行程 ID
	if (isset($_REQUEST["calendar_id"])) {
		$sID = stripslashes($_REQUEST["calendar_id"]);
		if ($rec['calendar_id'] != $sID) {
			$rec['calendar_id'] = $sID;
			$bUpdate = true;
		}
	}
	// VideoInport
	if (isset($_REQUEST["vi"])) {
		$vi = $_REQUEST["vi"] == "y";
		if ($rec['vi'] != $vi) {
			$rec['vi'] = $vi;
			$bUpdate = true;
		}
	}
	// 目錄設定值
	if (isset($_REQUEST["dir_set"])) {
		$sDirSet = stripslashes($_REQUEST["dir_set"]);
		if ($rec['dir_set'] != $sDirSet) {
			$rec['dir_set'] = $sDirSet;
			$bUpdate = true;
		}
	}
	// 目錄 - 區塊顯示
	if (isset($_REQUEST["dir_zv"])) {
		$sDirSet = stripslashes($_REQUEST["dir_zv"]);
		if ($rec['dir_zv'] != $sDirSet) {
			$rec['dir_zv'] = $sDirSet;
			$bUpdate = true;
		}
	}
	
	
	
	if ($bUpdate) {
		if (!B_Rec_Rec2File($sfRec, $rec, false))
			die("Error: fail write file.");
		// index 通知 index 更新 rec
B_Log_f("logs/_api_tools_2.log", "file_SetRec FP_FilePath=".FP_FilePath.", update, bMsgUploadTime=".$bMsgUploadTime);
		rs_ndx_UpdateRec(FP_FilePath, "update", $bMsgUploadTime);
		// 處理隱藏標籤
		if ($bUpdTag) {
			update_rec_by_hidden(PATH_FilePath);
		}
	}
if (bDebug) echo "sfRec=$sfRec <br>";
if (bDebug) echo "bUpdate=$bUpdate <br>";
if (bDebug) echo "rec:<br>".B_Array2String2($rec)." <br>";
	print "ok";
}
function file_SetRecs()
{
$qtt = B_GetCurrentTime_usec();
	$sRecs = isset($_REQUEST["recs"]) ? stripslashes($_REQUEST["recs"]) : "";
	if (empty($sRecs)) die_json("Error: empty recs");
	
	$out = array();
if (bDebug) echo "sRecs=$sRecs <br>";
	$recs = json_decode($sRecs,true);
if (bDebug) echo "recs:<br>".B_Array2String2($recs)." <br>";
	if (!is_array($recs))
		die_json("Error: 無效的 recs");
	
	foreach($recs as $aRec)
	{
		$bUpdate = false;
		$ufp = $aRec['ufp'];
		$fp = rs_con_UrlPath2FilePath($ufp);
		$f = PATH_Web.$ufp;
if (bDebug) echo "---------------------------- <br>";
if (bDebug) echo "ufp=$ufp <br>";
if (bDebug) echo "fp=$fp <br>";
if (bDebug) echo "f=$f <br>";
		// 不是管理者須檢查權限
		if (!POWER_Admin) {
			$aPW = chk_user_right($f);
// if (bDebug) echo "aPW:<br>".B_Array2String2($aPW)." <br>";
			// 沒有編輯權限
			if ($aPW['edit'] != PASS) {
				$out[] = array("ufp" => $ufp, "status" => "403");
				continue;
			}
		}
		
		$fRec = rs_con_FilePath2FileRec($f);
		$rec = B_Rec_Data2Rec(B_LoadFile($fRec));
if (bDebug) echo "fRec=$fRec <br>";
// if (bDebug) echo "rec:<br>".B_Array2String2($rec)." <br>";
		
		$bMsgUploadTime = false;
		// Title
		if (isset($aRec["title"])) {
			$sTitle = $aRec["title"];
			if ($rec['title'] != $sTitle) {
				$rec['title'] = $sTitle;
				$bUpdate = true;
				$bMsgUploadTime = true;
			}
		}
		// Description
		if (isset($aRec["description"])) {
			$sDesc = $aRec["description"];
			if ($rec['description'] != $sDesc) {
				$rec['description'] = $sDesc;
				$bUpdate = true;
				$bMsgUploadTime = true;
			}
		}
		// Memo
		if (isset($aRec["memo"])) {
			$memo = $aRec["memo"];
			if ($rec['memo'] != $memo) {
				$rec['memo'] = $memo;
				$bUpdate = true;
				$bMsgUploadTime = true;
			}
		}
		// Class
		if (isset($aRec["class"])) {
			$sClass = $aRec["class"];
			if ($rec['class'] != $sClass) {
				$rec['class'] = $sClass;
				$bUpdate = true;
			}
		}
		// Tag
		$bUpdTag = false;
		if (isset($aRec["tag"])) {
			$sTag = file_tag_ClearSpace($aRec["tag"]);
			if ($rec['tag'] != $sTag) {
				// Tag_Object
				if (!function_exists("tag_Init")) {
					require_once(DIR_TAG."tag_lib.php");
					tag_Init(SYS_SiteName);
				}
				$tag_new = $sTag;
				$tag_old = $rec['tag'];
				$type = tag_conRec2Type($rec);
				tag_append($type, $tag_new, $tag_old, $fp);
				
				$rec['tag'] = $sTag;
				$bUpdate = true;
				$bUpdTag = true;
			}
		}
		// VideoInport
		if (isset($aRec["vi"])) {
			$vi = $aRec["vi"] == "y";
			if ($rec['vi'] != $vi) {
				$rec['vi'] = $vi;
				$bUpdate = true;
			}
		}
		
		if ($bUpdate) {
			if (!B_Rec_Rec2File($fRec, $rec, false))
				die("Error: fail write file.");
			
$tt = B_GetCurrentTime_usec();
				// index 通知 index 更新 rec
				rs_ndx_UpdateRec($fp, "update", $bMsgUploadTime);
if (bDebug) echo "rs_ndx_UpdateRec t=".sprintf("%.3f",B_GetCurrentTime_usec()-$tt)." <br>";

			// 處理隱藏標籤
			if ($bUpdTag) {
$tt = B_GetCurrentTime_usec();
				update_rec_by_hidden($f);
if (bDebug) echo "update_rec_by_hidden t=".sprintf("%.3f",B_GetCurrentTime_usec()-$tt)." <br>";
			}
		}
		
		$out[] = array("ufp" => $ufp, "status" => "ok");
	}
if (bDebug) echo "End t=".sprintf("%.3f",B_GetCurrentTime_usec()-$qtt)." <br>";
	print json_encode($out);
}
function file_video_inport()
{
$f_log = "logs/_file_video_inport.log";
	global $LangStr;
	
	ignore_user_abort(1);
	set_time_limit(0);

	require_once("rs_html_parse_lib.php");
	require_once("/data/HTTPD/htdocs/API/inport_file_lib.php");
	
	$bUpdate = false;
	$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
	$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
	$url = $rec['link_url'];
	$title = $rec['title'];
if (bDebug) echo "sfRec=$sfRec <br>";
if (bDebug) echo "rec:<br>".B_Array2String2($rec)." <br><br>";
	
	// VideoInport
	$rec['vi'] = "download";
	if (!B_Rec_Rec2File($sfRec, $rec, false))
		die_json("Error: 寫入失敗.");
	// index 通知 index 更新 rec
	rs_ndx_UpdateRec(FP_FilePath);
	
	$sErr = "";
	$v_ufp = "";
	do {
		// check or Create Video Dir
		$page_dir = WEB_PAGE_DIR;
		$file_path = FP_FileDir;
		
if (bDebug) echo "page_dir=$page_dir <br>";
if (bDebug) echo "file_path=$file_path <br>";
if (bDebug) echo "FP_FileDir=".FP_FileDir." <br>";
		
/*		$file_path = SYS_SiteName."/Driver/Video";
		$dir_video = WEB_PAGE_DIR.$file_path;
if (bDebug) echo "dir_video=$dir_video <br>";
		if (!file_exists($dir_video)) {
			$path = SYS_SiteName."/Driver/";
			//$name = $LangStr['S_DirName_Video'];	// 影音
			$name = "影音";
			$dir_type = "OokonStorage";
			$real_dir_name = "Video";
			$dir = new_dir($page_dir, $path, $name, $dir_type, $real_dir_name);
if (bDebug) echo "dir=$dir <br>";
			if (empty($dir))
				$dir = new_dir($page_dir, $path, $name."1", $dir_type, $real_dir_name);
			if (empty($dir))
				$dir = new_dir($page_dir, $path, $name."2", $dir_type, $real_dir_name);
		}*/
		
		
		
		// Download Youtube Video File
if (bDebug) echo "m_video_inport 1 url=$url <br>";
		$url = B_Url_SetArg($url, "list", false);
		$url = B_Url_SetArg($url, "index", false);
if (bDebug) echo "m_video_inport 2 url=$url <br>";
		$vid = B_Url_GetArg($url, "v");
if (bDebug) echo "m_video_inport 3 vid=$vid <br>";
B_Log_f($f_log, "vid=".$vid);
		// 取得主要圖片
		$InHeader['url'] = "http://www.youtube.com/watch?v=".$vid;
		$img_main = Html_getImgMain(B_curl($InHeader), $url);
B_Log_f($f_log, "img_main=".$img_main);
		//
		$f_mp4 = B_file_get_temp("v").".mp4";
		$cmd = API_YOUTUBE_DL." \"$url\" -f mp4/37/22/84/85/18/82/83 -o $f_mp4";
B_Log_f($f_log, "cmd=".$cmd);
if (bDebug) echo "m_video_inport cmd=$cmd <br>";
		$y_data = shell_exec($cmd);
		$bExists = file_exists($f_mp4);
if (bDebug) echo "m_video_inport file_exists=$bExists, y_data=$y_data <br>";
B_Log_f($f_log, "bExists=$bExists, y_data=$y_data");
		if (!$bExists) {
			$sErr = "Error: Download failed";
			break;
		}

		// 取得唯一檔名
		$fn = B_File_FilterName($title);
		$name = $fn.".mp4"; $n = 0;
		// 先檢查檔案是否已存在
		while(filename_exists($page_dir, $file_path, $name) != false) {
			$n++;
			$name = $fn."-".$n.".mp4";
		}
		// 將影片檔匯入 "影音目錄"
		$read_file = realpath($f_mp4);
		$type = "new";
		$page_url = SITE_URL;
		$fmtime = null;
		$source = "youtube";
		$v_ufp = inport_file($read_file, $type, $page_dir, $page_url, $file_path, $fmtime, $name, $source, $img_main);
if (bDebug) echo "v_ufp=$v_ufp <br>";
		if (B_chkErr_SendResult($v_ufp)) {
			$sErr = $v_ufp;
			break;
		}
		//
		$v_fp = rs_con_UrlPath2FilePath($v_ufp);
		$v_fRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec($v_fp));
		$v_rec = B_Rec_Data2Rec(B_LoadFile($v_fRec));
if (bDebug) echo "v_fRec=$v_fRec <br>";
if (bDebug) echo "v_rec:<br>".B_Array2String2($v_rec)." <br><br>";
		// 有標籤或描述要傳過去
		if (!empty($rec['tag']) || !empty($rec['description'])) {
			$v_rec['tag'] 			= $rec['tag'];
			$v_rec['description'] 	= $rec['description'];
			if (B_Rec_Rec2File($v_fRec, $v_rec, false)) {
				// index 通知 index 更新 rec
				rs_ndx_UpdateRec($v_fp);
			}
		}
		
	} while(0);
	
	// VideoInport
	$rec['vi'] = empty($sErr) ? "y" : $sErr;
	$rec['vi_ufp'] = $v_ufp;
	if (!B_Rec_Rec2File($sfRec, $rec, false))
		die_json("Error: 寫入失敗.");
	if (!empty($sErr)) {
B_Log_f($f_log, "sErr=$sErr");
		die_json($sErr);
	}
	// index 通知 index 更新 rec
	rs_ndx_UpdateRec(FP_FilePath);
	
	return_json($v_ufp);
}

function file_GetContent()
{
	$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
	$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
	// unset($rec['GAIS_Rec']);
	// unset($rec['allow']);
	// unset($rec['deny']);
if (bDebug) echo "rec:<br>".B_Array2String2($rec)." <br><br>";
	print json_encode($rec);
}
function file_GetComment()
{
	$p		 	= isset($_REQUEST["p"])			? (int)$_REQUEST["p"] 			: 1;
	$ps 		= isset($_REQUEST["ps"])		? (int)$_REQUEST["ps"] 			: 10;
	$o 			= isset($_REQUEST["o"])			? $_REQUEST["o"] 				: "tm";
	$os 		= isset($_REQUEST["os"])		? $_REQUEST["os"] 				: "dec";
	$bCntLink	= isset($_REQUEST["cnt_like"])	? $_REQUEST["cnt_like"] == "y"	: false;
	
	$sfCMN = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2CmnFileRec(FP_FilePath));
	$recsCMN = B_Rec_Data2Recs(B_LoadFile($sfCMN));
	cmp_o_od($recsCMN, $o, $os);
	//usort($recsCMN, "file_cmp_tm_dec");
	$cnt_content = count($recsCMN);
	$pc = (int)(($cnt_content-1)/$ps)+1;
	$recsCMN = file_cmn_rec_GetSub($recsCMN, $p, $ps);
	
	$out = array();
	$out['bManager'] 	= POWER_Manager;
	$out['bAdmin'] 		= POWER_Admin;
	$out['bShow'] 		= POWER_Show;
	$out['bUpload'] 	= POWER_Upload;
	
	$out['cnt'] = $cnt_content;
	$out['recs'] = $recsCMN;
	
	if ($bCntLink) {
		$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
		$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
		$out["cnt_like"] 	= $rec["cnt_like"];
		$out["cnt_unlike"] 	= $rec["cnt_unlike"];
		$out["bMyLike"] 	= B_tag_is_exists($rec['us_like'],USER_ACN);
		$out["us_like"] 	= $rec['us_like'];
	}
	
if (bDebug) echo "out:<br>".B_Array2String2($out)." <br><br>";
	print json_encode($out);
}
function file_AddComment()
{
	$sName 	= isset($_REQUEST["name"])		? stripslashes($_REQUEST["name"]) 		: USER_ACN;
	$sMail 	= isset($_REQUEST["mail"])		? stripslashes($_REQUEST["mail"]) 		: "";
	$sC 	= isset($_REQUEST["content"])	? stripslashes($_REQUEST["content"]) 	: "";
	$bRec 	= isset($_REQUEST["out"])		? $_REQUEST["out"] == "rec"		 		: false;
	$sC = trim($sC);
	if ( empty($sC) ) die_json("empty content.");
	
	$sfCMN = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2CmnFileRec(FP_FilePath));
	$recsCMN = B_Rec_Data2Recs(B_LoadFile($sfCMN));
	$cnt = count($recsCMN)+1;
	
	$recLast = end($recsCMN);
	$id = ($recLast === false ? 1 : $recLast['i'] +1);
	if ($id<1) $id = 1;
	
	$rec = array();
	$rec["i"] = sprintf("%04d", $id);
	$rec["user"] = USER_ACN;
	$rec["name"] = $sName;
	$rec["mail"] = $sMail;
	$rec["tm"] = B_GetCurrentTime();
	$rec["content"] = $sC;
	
	$recsCMN[] = $rec;
	if (!B_Rec_Recs2File($sfCMN, $recsCMN))
		die_json("fail write file.");
		
	// 寫入 File Record 
	file_Comment_WriteDB($recsCMN);
	// index 通知 index 更新 rec
B_Log_f("logs/_api_tools_2.log", "file_AddComment FP_FilePath=".FP_FilePath);
		// 更新留言數
		$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
		$rec2 = B_Rec_Data2Rec(B_LoadFile($sfRec));
		$rec2['cnt_cmn'] = $cnt;
		B_Rec_Rec2File($sfRec, $rec2, false);
	$bMsgUploadTime = true;
	rs_ndx_UpdateRec(FP_FilePath, "update", $bMsgUploadTime);
	
	if ($bRec) {
		print json_encode($rec);
	}
	else {
		print "ok";
	}
}
function file_GetCommentRec()
{
	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
	if (empty($id)) die_json("empty id.");
	
	$sfCMN = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2CmnFileRec(FP_FilePath));
	$data = B_LoadFile($sfCMN);
	$rec = B_Rec_Data_Find($data, "i", $id, $Pos, $L);
	if ($rec === false)
		B_Error(404);
	
	print json_encode($rec);
}
function file_DelComment()
{
	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
	if (empty($id)) die_json("empty id.");
	
	$sfCMN = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2CmnFileRec(FP_FilePath));
	$data = B_LoadFile($sfCMN);
	$rec = B_Rec_Data_Find($data, "i", $id, $Pos, $L);
	if ($rec === false)
		B_Error(404);
	// 不是管理者, 也不是作者, 不能刪除.
	if (!POWER_Admin && !empty($rec["user"]) && strcasecmp($rec["user"], USER_ACN))
		B_Error(403);
	
	// save
	$data = B_Replace_Pos($data, "", $Pos, $L);
	if (B_SaveFile($sfCMN, $data) === false)
		die_json("fail write.");
	
	// 寫入 File Record 
	file_Comment_WriteDB(B_Rec_Data2Recs($data));
	
	print json_encode(array("succeed" => "ok"));
}
function file_UpdComment()
{
	$sC		= isset($_REQUEST["content"])	? stripslashes($_REQUEST["content"]): "";
	$preview= isset($_REQUEST["preview"]) 	? $_REQUEST["preview"]				: "y";
	$id 	= isset($_REQUEST["id"]) 		? $_REQUEST["id"] 					: "";
	$bRec 	= isset($_REQUEST["out"])		? $_REQUEST["out"] == "rec"		 	: false;
	if (empty($id)) die_json("empty id.");
	
	$sfCMN = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2CmnFileRec(FP_FilePath));
	$data = B_LoadFile($sfCMN);
	$rec = B_Rec_Data_Find($data, "i", $id, $Pos, $L);
	if ($rec === false)
		B_Error(404);
	// 不是管理者, 也不是作者, 不能刪除.
	if (!POWER_Admin && !empty($rec["user"]) && strcasecmp($rec["user"], USER_ACN))
		B_Error(403);
	
	if (isset($_REQUEST["content"]))
		$rec["content"] = $_REQUEST["content"];
	if (isset($_REQUEST["preview"]))
		$rec["preview"] = $preview != "n" ? "y" : "n";
	// save
	$data = B_Replace_Pos($data, B_Rec_Rec2Data($rec), $Pos, $L);
	if (B_SaveFile($sfCMN, $data) === false)
		die_json("fail write.");
	
	// 寫入 File Record 
	file_Comment_WriteDB(B_Rec_Data2Recs($data));
	
	if ($bRec) {
		print json_encode($rec);
	}
	else {
		print json_encode(array("succeed" => "ok"));
	}
}
// 寫入 File Record
function file_Comment_WriteDB($recs)
{
$f_log = "logs/file_Comment_WriteDB.log";
	
	$ss = "";
	foreach($recs as $rec) {
		$ss .= preg_replace("#[\r\n\\s]+#", " ", $rec['content'])."\n";
	}
	
B_Log_f($f_log, "ss=".$ss);
	
	$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
B_Log_f($f_log, "sfRec=".$sfRec);
	$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
	$rec['comments'] = $ss;
	if (!B_Rec_Rec2File($sfRec, $rec, false))
		die("Error: fail write file.");
	// index 通知 index 更新 rec
	rs_ndx_UpdateRec(FP_FilePath);
}

function file_GetTagList()
{
	// Tag_Object
	require_once(DIR_TAG."tag_lib.php");
	tag_Init(SYS_SiteName);
	
	$bOut_str		= isset($_REQUEST["out"])		? $_REQUEST["out"]== "str"		: "";
	$arg = array();
	$arg['type']	= isset($_REQUEST["type"])		? $_REQUEST["type"]				: "";
	$arg['sort']	= isset($_REQUEST["sort"])		? $_REQUEST["sort"]				: "";
	$bArray			= isset($_REQUEST["out"])		? $_REQUEST["out"] == "array" 	: "";
	$arg['fp'] = 	tag_con_FilePath2FP(FP_FilePath);

	$recs = tag_load_arg($arg);
if (bDebug) {
	echo "<br>SYS_SiteName=".SYS_SiteName;
	echo "<br>FP_FilePath=".FP_FilePath;
	echo "<br>FN_DB_TAG=".FN_DB_TAG;
	echo "<br>DIR_SITE=".DIR_SITE;
	echo "<br>arg:<br>".B_Array2String2($arg);
	echo "<br><br>recs:<br>".B_Array2String2($recs);
}
	if ($bArray) {
		$rows = $recs;
		$recs = array();
		foreach($rows as $rec)
			$recs[] = $rec;
	}
	
	$out = array();
	$out["cnt"] = count($recs);
	$out["recs"]= $recs;
	if ($bOut_str) {
		print 	"out:<br>".B_Array2String2($out)
				."<br><br>";
	}
	print json_encode($out);
}
function file_GetTagGroup()
{
	define("ALL_INDEX",	"/data/NUWeb_Site/Search/Index/All/current");
	
	$bOut_array	= isset($_REQUEST["out"])		? $_REQUEST["out"] == "array": false;
	
	$site 		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$file_path 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";	// 某個目錄, 包含子目錄底檔案
	$dir 		= isset($_REQUEST["dir"]) 		? $_REQUEST["dir"] 			: "";	// 某個目錄, 不包含子目錄底檔案
	$file_path = rs_filter_Site($file_path);
if (bDebug) echo "site=$site, file_path=$file_path, <br><br>";
	rs_init($site, $file_path);
if (bDebug) echo "site=$site, file_path=$file_path, <br><br>";

	$mtime		= isset($_REQUEST["mtime"])		? $_REQUEST["mtime"]	: "";
	$fe			= isset($_REQUEST["fe"])		? $_REQUEST["fe"]		: "";
	$type		= isset($_REQUEST["type"])		? $_REQUEST["type"]		: "";
	$class		= isset($_REQUEST["class"])		? $_REQUEST["class"]	: "";
	$subclass	= isset($_REQUEST["subclass"])	? $_REQUEST["subclass"]	: "";
	$tag		= isset($_REQUEST["tag"])		? $_REQUEST["tag"]		: "";
	$ftag		= isset($_REQUEST["ftag"])		? $_REQUEST["ftag"]		: "";	// 多重標籤, and
	$group		= isset($_REQUEST["group"])		? $_REQUEST["group"]	: "tag";
	
	// 過濾掉 "網路硬碟"
	$bNotDriver	= isset($_REQUEST["not_driver"])? $_REQUEST["not_driver"] == "y" : false;
	
	$sRange 	= isset($_REQUEST["range"])		? $_REQUEST["range"]	: "";
	if ($sRange == "group")
	{
		$query = array();
		$query['mode'] 		= "file";
		$query['act'] 		= "GetTagGroup";
		$query['site'] 		= $site;
		$query['file_path']	= $file_path;
		$query['dir'] 		= $dir;
		$query['fe'] 		= $fe;
		$query['type'] 		= $type;
		$query['class'] 	= $class;
		$query['subclass'] 	= $subclass;
		$query['group'] 	= $group;
		$query['range'] 	= "all";
		$query['out'] 		= $_REQUEST["out"];
		$res = group_api_tools($query);
		print json_encode($res);
		return;
	}
	if ($sRange == "all") {
		$site = "";
		$file_path = "";
	}
	
	$DBPath = ALL_INDEX;
	if (!empty($file_path)) {
		$aPath = explode("/", $file_path);
		$DBPath = WEB_ROOT_PATH."/Site/".$aPath[0]."/.nuweb_index/current";
	}
	
	$aDenySelect = array();
	$aDenyNSelect = array();
	$aDenyTag = array();
	// 過濾掉 "網路硬碟"
	if ($bNotDriver) $aDenySelect['url'] = '/Driver/'; // 過濾掉 "網路硬碟"
	// All, Video, Image, Article, Document, File, Web
	switch($type) {
		case "All":
			$type = "";
			break;
		case "Photo":
			$type = "";
			$fe = ".jpg,.png";
			break;
		case "Album":
			$type = "";
			$dir_type = "OokonAlbum";
			break;
		case "Article":
			$type = "Html,Directory";
			break;
		case "Document":
			break;
		case "File":
			$type = "Other";
			break;
		case "Web":
			$type = "Directory";
			break;
	}
	
	$aSelect = array();
	if (!empty($site) && empty($dir))
	{
		if ($site == "Site")
			$url_path = str_replace("//", "/", "/$site/$file_path/");
		else
			$url_path = str_replace("//", "/", "/$site/Pages/$file_path/");
		$aSelect[] = "+@url:$url_path";
	}
	else
		$aSelect[] = "+@site_mode:".EXTERNAL_MODE;
	if (!empty($path) && empty($dir))
		$aSelect[] = "+@url:$path";
		// Deny
	if (count($aDenySelect) > 0) {
		foreach($aDenySelect as $k => $v)
			$aSelect[] = "-@$k:$v";
	}
	$select_arg = (count($aSelect) > 0 ? "-select \"".implode(";", $aSelect)."\"" : "");
		
	/* fe, type, tag 只能選擇其中一項 (fe > type > tag) */
	$aTag = array();
	if (!empty($fe))
		$aTag[] = "+@fe:".dbman_con_fe2fes($fe);
	if (!empty($type))
		$aTag[] = "+@type:$type";
	if(!empty($dir_type))
		$aTag[] = "+@dir_type:".rawurlencode($dir_type);
	if (!empty($class))
		$aTag[] = "+@class:".rawurlencode($class);
	if (!empty($subclass))
		$aTag[] = "+@subclass:".rawurlencode($subclass);
	if (!empty($tag))
		$aTag[] = "+@tag:".rawurlencode($tag);
	// 多重標籤, and
	if (!empty($ftag)) {
		$a = explode(",", $ftag);
		foreach($a as $v)
			$aTag[] = "+@tag:".rawurlencode($v);
	}
	$tag_arg = (count($aTag) > 0 ? "-tag \"".implode(";", $aTag)."\"" : "");
	
	// 年月過濾
	$cmd_mtime = "";
	if (!empty($mtime)) {
		$cmd_mtime = API_DBMAN." -recbeg \"@\\n@GAIS_Rec:\" -avesize 2000 -flag \"@_f:Normal\" -select \"@mtime:^{$mtime}\" -matchmode \"regular\" -select2query -sort -key \"@mtime:\" ";
	}
	
	// 某個目錄底下的檔案, 不包含子目錄
	if (!empty($dir)) {
		// 改用目錄的 index db
		$DBPath = WEB_ROOT_PATH."/Site/".$dir."/.nuweb_rec/dir_index/current";
		$DBPath = str_replace("//", "/", $DBPath);
	}
	if (empty($cmd_mtime))
		$cmd = "\"".API_DBMAN."\" -recbeg \"@\\n@GAIS_Rec:\" -avesize 2000 -flag \"@_f:Normal\" $tag_arg $select_arg -groupby \"@$group:\" -orderby cnt \"".$DBPath."\"";
	else {
		$cmd = $cmd_mtime.$DBPath." | ".API_DBMAN." -recbeg \"@\\n@GAIS_Rec:\" -avesize 2000 -flag \"@_f:Normal\" $tag_arg $select_arg -sort -key \"@fe:\" -groupby \"@$group:\" -orderby cnt ";
	}
	$result = shell_exec($cmd);

	$cnt = BAT_rec_GetTotal($result);
	$recs = B_Rec_Data2Recs($result);
if (bDebug) {
	echo "cmd=$cmd <br><br>"
		."result:<br>".substr($result,0,256)."<br><br>"
		//."result:<br>".$result."<br><br>"
		.B_Array2String2($recs)."<br><br>";
}	
	$aRecs = array();
	foreach($recs as $rec) {
		$key = trim($rec['key']);
		$a = explode(",",$key);
		foreach($a as $k) {
			$k = trim($k);
			if (empty($k) || strlen($k) > 26) continue;
			$aRecs[$k] += $rec['cnt'];
		}
	}
	// 合並 docx => doc
	if (isset($aRecs['.docx'])) {
		if (isset($aRecs['.doc']))
			$aRecs['.doc'] += $aRecs['.docx'];
		else
			$aRecs['.doc'] = $aRecs['.docx'];
		unset($aRecs['.docx']);
	}
	if (isset($aRecs['.xlsx'])) {
		if (isset($aRecs['.xls']))
			$aRecs['.xls'] += $aRecs['.xlsx'];
		else
			$aRecs['.xls'] = $aRecs['.xlsx'];
		unset($aRecs['.xlsx']);
	}
	if (isset($aRecs['.pptx'])) {
		if (isset($aRecs['.ppt']))
			$aRecs['.ppt'] += $aRecs['.pptx'];
		else
			$aRecs['.ppt'] = $aRecs['.pptx'];
		unset($aRecs['.pptx']);
	}
	
	if ($bOut_array) {
		$recs = $aRecs;
		$aRecs = array();
		foreach($recs as $k => $v)
			$aRecs[] = array('key' => $k, 'cnt' => $v);
		usort($aRecs, "file_GetTagGroup_cnt");
	}
	else {
		arsort($aRecs, SORT_NUMERIC);
	}
	
	$out = array();
	$out["cnt"] = count($aRecs);
	$out["recs"]= $aRecs;
	//$out["recs_db"]= $recs;

	// if (bDebug)
	// {
		// echo "out:<br>".B_Array2String2($out)."<br><br>";
		
		// if (empty($cmd_mtime))
			// $cmd = API_DBMAN." -recbeg \"@\\n@GAIS_Rec:\" -avesize 2000 -flag \"@_f:Normal\" $tag_arg $select_arg -L 1-10 -sort -key \"@$group:\" -reverse ".$DBPath;
		// else 
			// $cmd = $cmd = $cmd_mtime.$DBPath." | ".API_DBMAN." -recbeg \"@\\n@GAIS_Rec:\" -avesize 2000 -flag \"@_f:Normal\" $tag_arg $select_arg -L 1-10 -sort -key \"@$group:\" -reverse ";
		// echo "*** Debug *** cmd=$cmd <br>";
		// $result = shell_exec($cmd);
		// echo str_replace("\n", "<br>", $result)."<br><br>";
	// }
	print json_encode($out);
}
function file_GetTagGroup_cnt($a, $b)
{
	if ($a['cnt'] == $b['cnt']) return 0;
	return $a['cnt'] > $b['cnt'] ? -1 : 1;
}



function file_GetClassList2()
{
	// Tag_Object
	require_once(DIR_TAG."tag_lib.php");
	tag_Init(SYS_SiteName);

	$bOut_str	= isset($_REQUEST["out"])		? stripslashes($_REQUEST["out"])== "str": "";
	$site		= isset($_REQUEST["site"])		? stripslashes($_REQUEST["site"])		: "";
	$file_path	= isset($_REQUEST["file_path"])	? stripslashes($_REQUEST["file_path"])	: "";
	$type		= isset($_REQUEST["type"])		? stripslashes($_REQUEST["type"])		: "";
	if (empty($type)) die_json("empty type.");
	
	$sRange 	= isset($_REQUEST["range"])		? $_REQUEST["range"]	: "";
	if ($sRange == "root")
	{
		$sArg = "mode=file"
				."&act=GetClassList2"
				."&site=" 		.rawurlencode($site)
				."&file_path=" 	.rawurlencode($file_path)
				."&type=" 		.rawurlencode($type)
				."&out="		.$bOut_str;
		$root_url = "http://".HOSTIP_ROOT."/tools/api_tools.php?".$sArg;
		$root_data = B_file_get_contents($root_url);
		print $root_data;
		return;
	}
	
	$recs = cla_getList($type);
	$out = array();
	$out["cnt"] = count($recs);
	$out["recs"]= $recs;
	if ($bOut_str) {
		print 	"out:<br>".B_Array2String2($out)
				."<br><br>";
	}
	print json_encode($out);
}
function file_GetClassList()
{
	define("ALL_INDEX",	"/data/NUWeb_Site/Search/Index/All/current");
	
	$bOut_str	= isset($_REQUEST["out"])		? stripslashes($_REQUEST["out"])== "str": "";
	$type		= isset($_REQUEST["type"])		? stripslashes($_REQUEST["type"])		: "";
	$site_name	= isset($_REQUEST["site_name"])	? stripslashes($_REQUEST["site_name"])	: "";
	$group		= isset($_REQUEST["group"])		? stripslashes($_REQUEST["group"])		: "class";
	
	// type=[article(html and dir)/html/dir/video/photo/document/file/youtube/(empty or othter => all)]
	$tag_arg = "";
	switch($type) {
		case "article":
            $tag_arg = "-tag \"@type:Directory; @fe:.html\"";
			break;
		case "dir":
            $tag_arg = "-tag \"@type:Directory\"";
			break;
		case "html":
            $tag_arg = "-tag \"@fe:.html\"";
			break;
		case "video":
            $tag_arg = "-tag \"@type:Video\"";
			break;
		case "photo":
            $tag_arg = "-tag \"@fe:.jpg\"";
			break;
		case "document":
            $tag_arg = "-tag \"@type:Document\"";
			break;
		case "file":
            $tag_arg = "-tag \"@type:Other\"";
			break;
		case "youtube":
            $tag_arg = "-tag \"@type:Youtube\"";
			break;
	}
	
	$select_arg = "";
	if (!empty($site_name))
		$select_arg = "-select \"@url:/Site/$site_name/\"";

	$cmd = "\"".API_DBMAN."\" -recbeg \"@\\n@GAIS_Rec:\" -avesize 2000 -flag \"@_f:Normal\" $tag_arg $select_arg -groupby \"@$group:\" -orderby cnt \"".ALL_INDEX."\"";
	$fp = popen($cmd, "r");
	$data = "";
	while($buf = fgets($fp))
		$data .= $buf;
	pclose($fp);
	
	$cnt = BAT_rec_GetTotal($data);
	$recs = B_Rec_Data2Recs($data);
	$aRec = array();
	foreach($recs as $rec) {
		$key = trim($rec['key']);
		if (empty($key)) continue;
		$a = explode(",",$key);
		foreach($a as $k) {
			if (empty($k)) continue;
			$aRec[$k] += $rec['cnt'];
		}
	}
	arsort($aRec, SORT_NUMERIC);
	
	if ($group == "class") {
		$cla_def = array("台灣音樂","日韓音樂","歐美音樂","跨界音樂","古典音樂","台灣電視","歐美電視","風景旅遊","電影動畫","知識學習","動物植物","趣味短片","運動","其他");
		$aRec = array();
		foreach($cla_def as $cla) {
			$aRec[$cla] = 0;
		}
	}
		
	$out = array();
	$out["cnt"] = count($aRec);
	$out["recs"]= $aRec;
	$out["recs_db"]= $recs;
// Debug 
//	$out["debug"].= B_Array2String2($out)."<br><br><br>";
// echo "cmd=$cmd <br>";
// echo B_Array2String2($out)."<br><br><br>";


	if ($bOut_str)
	{
		print $cmd."<br>".B_Array2String2($aRec)."<br><br>";
		
		$cmd = "\"".API_DBMAN."\" -recbeg \"@\\n@GAIS_Rec:\" -avesize 2000 -flag \"@_f:Normal\" $tag_arg $select_arg -sort -key \"@class:\" -reverse \"".ALL_INDEX."\"";
		echo "cmd=$cmd <br>";
		$fp = popen($cmd, "r");
		$data = "";
		while($buf = fgets($fp))
			$data .= $buf;
		pclose($fp);
		echo str_replace("\n", "<br>", $data)."<br><br>";
	}
	print json_encode($out);
}
function file_SetTag()
{
	// Tag_Object
	require_once(DIR_TAG."tag_lib.php");
	tag_Init(SYS_SiteName);
		
	$sTag	= isset($_REQUEST["tag"])	? stripslashes($_REQUEST["tag"])	: "";
	$sName	= isset($_REQUEST["name"])	? stripslashes($_REQUEST["name"])	: "";
	// tag color
	$fc		= isset($_REQUEST["fc"])	? stripslashes($_REQUEST["fc"])	: "";
	$bc		= isset($_REQUEST["bc"])	? stripslashes($_REQUEST["bc"])	: "";
	// tag append
	$bAppend= isset($_REQUEST["append"])? $_REQUEST["append"] == "y"	: false;
	if (empty($sName))
	{
		$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
		$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));

		$tag_new = tag_ClearSpace($bAppend ? $rec['tag'].",".$sTag : $sTag);
		$tag_old = $rec['tag'];
		$type = tag_conRec2Type($rec);
		tag_append($type, $tag_new, $tag_old, FP_FilePath);
		
		// index 通知 index 更新 rec
		$rec['tag'] = preg_replace('#^,|,S#', '', $tag_new);
		update_rec_file($sfRec, $rec);	// public_lib.php
	}
	else
	{
		$bColor = (!empty($fc) && !empty($bc));
		$aName = explode(",", $sName);
		foreach($aName as $name) {
			$name = trim(rawurldecode($name));
			if (empty($name)) continue;
			$file_path = B_Url_AddNames(FP_FileDir, $name);
			$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec($file_path));
			$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
			// tag db
			$tag_new = tag_ClearSpace(($bAppend ? $rec['tag'].",".$sTag : $sTag));
			$tag_old = $rec['tag'];
			$type = tag_conRec2Type($rec);
			tag_append($type, $tag_new, $tag_old, $file_path);
			// index 通知 index 更新 rec
			$rec['tag'] = preg_replace('#^,|,S#', '', $tag_new);
			update_rec_file($sfRec, $rec);	// public_lib.php
		}
	}
	// tag color
	if (!empty($fc) && !empty($bc)) {
		$aTag = explode(",", $sTag);
		foreach($aTag as $tag) {
			$tag = trim($tag);
			if (empty($tag)) continue;
			
			$aRec = array();
			$aRec['n'] = $tag;
			$aRec['fc'] = $fc;
			$aRec['bc'] = $bc;
			$res = tagrec_Upd($aRec);
		}
	}
	
	print "ok";
}
function file_ClickLike()
{
	$sLike	= isset($_REQUEST["like"])	? $_REQUEST["like"]	: "";
	$sUser	= strtolower(USER_ACN);
	if ( empty($sUser) ) 	die_json("not login.");
	if ( empty($sLike) ) 	die_json("empty like.");
	if ( $sLike != "y" && $sLike != "n" ) die_json("Invalidated like.");
	$bLike = ($sLike == "y");
	
	$sfRec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec(FP_FilePath));
	$rec = B_Rec_Data2Rec(B_LoadFile($sfRec));
if (bDebug) echo "rec:<br>".B_Array2String2($rec)."<br><br>";
	
	$bUpd = false;
	$k = ",".$sUser.",";
	if ($bLike)
	{
		if (empty($rec["us_like"])) {
			$rec["us_like"] = $k;
			$bUpd = true;
		}
		else if (strpos($rec["us_like"], $k) === false) {
			$rec["us_like"] .= $sUser.",";
			$bUpd = true;
		}
		
		if (!empty($rec["us_unlike"])) {
			$x = strpos($rec["us_unlike"], $k);
			if ($x !== false) {
				$rec["us_unlike"] = B_Replace_Pos($rec["us_unlike"], "", $x, strlen($k)-1);
				$bUpd = true;
			}
		}
	}
	else
	{
		if (empty($rec["us_unlike"])) {
			$rec["us_unlike"] = $k;
			$bUpd = true;
		}
		else if (strpos($rec["us_unlike"], $k) === false) {
			$rec["us_unlike"] .= $sUser.",";
			$bUpd = true;
		}
		
		if (!empty($rec["us_like"])) {
			$x = strpos($rec["us_like"], $k);
			if ($x !== false) {
				$rec["us_like"] = B_Replace_Pos($rec["us_like"], "", $x, strlen($k)-1);
				$bUpd = true;
			}
		}
	}
	
	if ( $bUpd ) {
		$rec["cnt_like"] = empty($rec["us_like"]) ? 0 : substr_count($rec["us_like"],",")-1;
		$rec["cnt_unlike"] = empty($rec["us_unlike"]) ? 0 : substr_count($rec["us_unlike"],",")-1;
		
		if (!B_Rec_Rec2File($sfRec, $rec, false))
			die("Error: fail write file.");
			
		// index 通知 index 更新 rec
		rs_ndx_UpdateRec(FP_FilePath, "update", false);
	}
	$out = array();
	$out["cnt_like"] 	= $rec["cnt_like"];
	$out["cnt_unlike"] 	= $rec["cnt_unlike"];
	$out["bMyLike"] 	= B_tag_is_exists($rec['us_like'],$sUser);
	$out["us_like"] 	= $rec['us_like'];
	print json_encode($out);
}
// 增加瀏覽次數
function file_CntView_Increase()
{
	$sUrlPath = "/".B_Url_AddNames(URL_WebPages, FP_FilePath);
	write_click_log($sUrlPath);
	print "ok";
}


function file_cmn_rec_GetSub(&$recsAll, $p=1, $ps=10)
{
	// 全部資料
	if ($ps == -1)
		return $recsAll;
	if ($p<1) $p = 1;
	$StartID = ($p-1) * $ps;
	return array_slice($recsAll, $StartID, $ps);
}
function file_cmp_tm_dec($a, $b)
{
    if ($a["tm"] == $b["tm"]) {
        return 0;
    }
    return ($a["tm"] < $b["tm"]) ? 1 : -1;
}
// 清除空白 及 過濾唯一
function file_tag_ClearSpace($sTags)
{
	$aTag = explode(',', preg_replace('#\s*,\s*#', ',', trim($sTags)));
	return implode(',', array_unique($aTag));
}
function file_rec_GetTotal($data)
{
	if (preg_match("#^total:\s*(\d+)#mi", $data, $m))
		return (int)$m[1];
	return 0;
}





function m_search()
{
	global $login_user;

	$sRange = isset($_REQUEST["range"]) ? $_REQUEST["range"] : "";
	if ($sRange == "Global") {
		$fDB = "/data/NUWeb_Site/Search/IndexGlobal/All/current";
		if (!file_exists($fDB))
			die_json("db does not exist");
		define("ALL_INDEX", $fDB);
	}
	require_once(DIR_TOOLS."search_lib.php");
	
	
	$bOut_str	= isset($_REQUEST["out"])		? stripslashes($_REQUEST["out"])== "str": "";
	$sSite 		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$sFilePath 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$sFilePath = rs_filter_Site($sFilePath);
	rs_init($sSite, $sFilePath);
	rs_power_init(true);
	$user = USER_ACN;
if (bDebug) echo "POWER_Manager=".POWER_Manager.", USER_ACN=".USER_ACN.", Admin=".POWER_Admin.", Show=".POWER_Show.", Download=".POWER_Download.", Upload=".POWER_Upload.", Edit=".POWER_Edit.", Del=".POWER_Del." <br>";

	// 分散式 Server - 網站不在這台 Server
	if (gs_get_api_data())
		return;

	$sDir		= isset($_REQUEST["dir"])		? rs_filter_Site($_REQUEST["dir"]) : "";
	$sFE		= isset($_REQUEST["fe"])		? $_REQUEST["fe"]		: "";
	$sType		= isset($_REQUEST["type"])		? $_REQUEST["type"]		: "";
	$sClass		= isset($_REQUEST["class"])		? $_REQUEST["class"]	: "";
	$sSubClass	= isset($_REQUEST["subclass"])	? $_REQUEST["subclass"]	: "";
	$sTag		= isset($_REQUEST["tag"])		? $_REQUEST["tag"]		: "";
	$sFTag		= isset($_REQUEST["ftag"])		? $_REQUEST["ftag"]		: "";
	$sDay		= isset($_REQUEST["day"])		? $_REQUEST["day"]		: "";	// 有效天數
	$time_range	= isset($_REQUEST["time_range"])? $_REQUEST["time_range"]:"";	// 日期範圍 start_time - tend_time
	$sMtime		= isset($_REQUEST["mtime"])		? $_REQUEST["mtime"]	: "";
	
	$sSort		= isset($_REQUEST["sort"])		? $_REQUEST["sort"]		: "time";
	$sOrder		= isset($_REQUEST["order"])		? $_REQUEST["order"]	: "";	// [inc / *dec]
	$p			= isset($_REQUEST["p"])			? (int)$_REQUEST["p"] 	: 1;
	$ps 		= isset($_REQUEST["ps"])		? (int)$_REQUEST["ps"] 	: 10;
	$type_ps 	= isset($_REQUEST["type_ps"])	? (int)$_REQUEST["type_ps"] : 0;
	$sQ			= isset($_REQUEST["q"])			? stripslashes($_REQUEST["q"]) 			: "";
					// exactterm, exactphrase, bestterm, regular, fuzzy
    $q_mode		= isset($_REQUEST["q_mode"]) 	? $_REQUEST["q_mode"]	: "exactterm";
	$q_fields 	= isset($_REQUEST["q_fields"])	? stripslashes($_REQUEST["q_fields"])	: "all";
	$o_fields 	= isset($_REQUEST["o_fields"])	? stripslashes($_REQUEST["o_fields"])	: "";
	
	$bContent 	= isset($_REQUEST["getContent"])? $_REQUEST["getContent"] == "y"	: false; // 取原始內容
	$bRecInfo	= isset($_REQUEST["getRecInfo"])? $_REQUEST["getRecInfo"] == 'y'	: false; // 取原始的記錄檔
	$bHP	 	= isset($_REQUEST["HP"])		? $_REQUEST["HP"] == 'y'			: false; // 輸出 頁數
	$bview_path	= isset($_REQUEST["view_path"])	? $_REQUEST["view_path"]  == 'y'	: true;  // 顯示路徑名稱
	$bnot_driver= isset($_REQUEST["not_driver"])? $_REQUEST["not_driver"] == 'y'	: false; // 過濾掉網路碟
	$bMIME		= isset($_REQUEST["mime"])		? $_REQUEST["mime"] == 'y'			: false; // 取 MIME
	$bUpload	= isset($_REQUEST["pw_upload"])	? $_REQUEST["pw_upload"]  == 'y'	: false; // 取 目錄的上傳權限
	$c_sort		= isset($_REQUEST["c_sort"])	? $_REQUEST["c_sort"] 	  == 'y'	: false; // 自定排序
	
	if ($bMIME) require_once("content-type.php");
	if ($bUpload) $page_dir = PATH_WebPages."/";
	// User 自訂排序
	$bSortCustom = $sSort == "custom";
	if ($bSortCustom) $sSort = "time";
	
	
	if ($sRange == "group")
	{
		$arg = array();
		$arg['mode'] 	 = "search";
		$arg['range'] 	 = "Global";
		
		$arg['site'] 	 = $sSite;
		$arg['file_path']= $sFilePath;
		
		$arg['fe'] 		= $sFE;
		$arg['type'] 	= $sType;
		$arg['class'] 	= $sClass;
		$arg['subclass']= $sSubClass;
		$arg['tag'] 	= $sTag;
		$arg['day'] 	= $sDay;
		
		$arg['sort'] 	= $sSort;
		$arg['order'] 	= $sOrder;
		$arg['p'] 		= $p;
		$arg['ps'] 		= $ps;
		$arg['q'] 		= $sQ;
		$arg['q_fields']= $q_fields;
		$arg['o_fields']= $o_fields;
		
		$arg['getRecInfo']= $bRecInfo;
		$arg['HP']		  = $bHP;
		$arg['view_path'] = $bview_path;
		
		$res = group_api_tools($arg, 60);
		foreach($res['recs'] as $k => $rec) {
			$url_head = "http://".$rec['server_acn'].".nuweb.cc";
			// 沒有 url 刪除
			if (empty($rec['url'])) {
				unset($res['recs'][$k]['url']);
				continue;
			}
			
			//if (!empty($rec['url']))	$res['recs'][$k]['url']		= B_Url_InsertPath2($rec['url'],	$url_head);
			if (!empty($rec['url']))	$res['recs'][$k]['url']		= $url_head.$rec['url'];
			if (!empty($rec['thumbs']))	$res['recs'][$k]['thumbs']	= $url_head.$rec['thumbs'];
			if (!empty($rec['flv']))	$res['recs'][$k]['flv']		= $url_head.$rec['flv'];
			if (!empty($rec['link']))	$res['recs'][$k]['link']	= $url_head.$rec['link'];
		}
// if (bDebug) {
	// echo 	"url=".$res['url']."<br>"
			// ."cnt=".$res['cnt'].", recs_cnt=".count($res['recs'])."<br>"
			// ."recs:<br>".B_Array2String2($res['recs'])
			// ."<br><br>";
// }
		print json_encode($res);
	}
	else
	{
		// 拒絕條件
		$aDenySelect	= array();
		$aDenyNSelect	= array();
		$aDenyTag 		= array();	// [$k=>$v, ]
		// 自定條件 [+@$k:$v]
		$aFilterSelect	= array();
		$aFilterTag		= array();	// ["+@$k:$v", ]
		//
		if (!POWER_Admin) {
			$aDenyTag["tag"] = "SYS_HIDE";
			$aDenyTag["r_strong_deny"] = "Y";	// 權限設定, 關閉分享
		}
		if (!POWER_Show) {
			//if (!empty($user)) 
			$aFilterTag[] = "+@owner:".rawurlencode($user);
		}
		
		if ($sFilePath == $sDir)
		{
if (bDebug) echo "目錄 Search *** sDir=$sDir <br>";
if (bDebug) echo "login_user<br>".B_Array2String2($login_user)." <br>";

			$bDirSearch = true;
			// 過濾掉 
			if (empty($sMtime))
				$aDenySelect['filename'] = '.files';
			
			$arg = array(
				'dir' 		=> $sDir,
				'type' 		=> $sType,
				'mtime' 	=> $sMtime,
				'class' 	=> $sClass,
				'subclass' 	=> $sSubClass,
				'tag' 		=> $sTag,
				'ftag' 		=> $sFTag,
				'o' 		=> $sSort,
				'od' 		=> $sOrder,
				'p' 		=> $p,
				'ps' 		=> $ps
			);
			// 拒絕條件
			$arg["aDenySelect"]		= $aDenySelect; 
			$arg["aDenyNSelect"]	= $aDenyNSelect;
			$arg["aDenyTag"]		= $aDenyTag;
			// 自定條件 [+@$k:$v]
			$arg["aFilterSelect"]	= $aFilterSelect;
			$arg["aFilterTag"]		= $aFilterTag;
			//
			$res = file_GetFileListRec_dbman($arg);
			$cnt = $res['cnt'];
			$rows = $res['recs'];
			unset($res);
		}
		else
		{
if (bDebug) echo "sFilePath=$sFilePath <br>";
		
			// All, Video, Image, Article, Document, File, Web
			switch($sType) {
				case "All":
					$sType = "";
					break;
				case "Photo":
					$sType = "";
					$sFE = ".jpg,.png";
					break;
				case "Album":
					$sType = "Directory";
					$sPath = "Album";
					//$sDirType = "OokonAlbum";
					break;
				case "Article":
					$sType = "Html,Directory";
					$size = '10-'.(0xFFFFFFFFF);
					break;
				case "Document":
					break;
				case "File":
					$sType = "Other";
					break;
				case "Web":
					$sType = "Directory";
					break;
				// case "Directory":
					// break;
			}
			// 取單層目錄檔案
			if (!empty($sDir)) {	// file_path and dir 二選一
				$sSite = "";
				$sFilePath = "";
			}
			if (empty($sFilePath))
				$sSite = "";
			// 過濾掉 
			$aDenySelect['view_path'] = '.files';
			// 過濾掉 "網路硬碟"
			if ($bnot_driver) {
				$aDenySelect['url'] = '/Driver/';
			}
			// 多重標籤, and
			if (!empty($sFTag)) {
				$a = explode(";", $sFTag);
				foreach($a as $v)
					$aFilterTag[] = "+@tag:".rawurlencode($v);
			}
			
			$query = array();
			$query["site"] 		= $sSite;		// empety => site and ext
			$query["file_path"] = $sFilePath;
			$query["dir"] 		= $sDir;
			$query["path"] 		= $sPath;
			$query["query"] 	= $sQ;		// 二選一 預先
			$query["key"] 		= $sQ;		// 二選一
			$query["query_mode"]= $q_mode;
			$query["fe"] 		= dbman_con_fe2fes($sFE);
			$query["type"] 		= $sType;
			$query["class"] 	= $sClass;	
			$query["subclass"] 	= $sSubClass;
			$query["tag"] 		= $sTag;	
			$query["dir_type"] 	= $sDirType;
			$query["day"]		= $sDay == "all" ? "" : $sDay;// 有效天數
			$query["time_range"]= $time_range;	// 日期範圍 start_time - tend_time
			$query["mtime"] 	= $sMtime;
			$query["size"] 		= $size;
			$query["sort"] 		= $sSort;
			$query["sort_order"]= $sOrder;
			$query["q_fields"] 	= $q_fields;
			// 拒絕條件
			$query["deny_slect"]	= $aDenySelect; 
			$query["deny_nslect"]	= $aDenyNSelect;
			$query["deny_tag"]		= $aDenyTag;
			// 自定條件 [+@$k:$v]
			$query["filter_select"]	= $aFilterSelect;
			$query["filter_tag"]	= $aFilterTag;
			//
			$data = all_search($query, $p, $ps);

	// if (bDebug) echo $data."<br><br><br>";
		// echo B_Array2String2($query)."<br>";
		// echo $data."<br>";
		// exit;
			$data = str_replace("@\n@\n", "@\n", $data);
			$data = preg_replace_callback("#^@.*\\[\\[\\[.*:#m", "search_filter_highlight", $data);
			$cnt = file_rec_GetTotal($data);
			$rows = B_Rec_Data2Recs($data);
			if (empty($cnt)) $cnt = 0;
		}
		
		// 自定排序
if (bDebug) echo "自定排序 ~~~ c_sort=$c_sort, bDirSearch=$bDirSearch, bSortCustom=$bSortCustom, sSort=$sSort  <br>";
		if ($c_sort == true && $bDirSearch == true && $bSortCustom == true) {
if (bDebug) echo "~~~ 自定排序 ~~~~~~~~~~~~~~~~ <br>";
			$list = array();
			$sort_dir = array();
			// Main Menu
			if (preg_match("/^[^\/]+\/?$/", $sFilePath)){
				$f_sort = B_Url_AddNames(PATH_WebPages, $sFilePath, ".nuweb_menu");
				if (file_exists($f_sort)){
					$data = B_LoadFile($f_sort);
					$aRow = explode("\n", $data);
					foreach ($aRow as $v) {
						$v = trim($v);
						if (empty($v)) continue;
						$list[] = $v;
					}
					$sort_dir[$k] = "R";
				}
			}
			// sub list
			$f_sort = B_Url_AddNames(PATH_WebPages, $sFilePath, ".nuweb_sub_list");
			if (file_exists($f_sort)){
				$data = B_LoadFile($f_sort);
				$aRow = explode("\n", $data);
				foreach ($aRow as $row) {
					list($k, $v) = explode("\t", $row, 2);
					$k = trim($k);
					$v = trim($v);
					if (empty($v)) continue;
					$sort_dir[$k] = $k;
					$list[] = $v;
				}
			}
			// Sort
			if (count($list)) {
				$recsSort = array();
				$recsNew = array();
				foreach($list as $fn) {
					$n = B_obj_indexOf($rows, "page_name", $fn);
					if ($n !== false) {
						$rec = $rows[$n];
						$rec['bSort'] = true;
						
						$recsSort[] = $rec;
						unset($rows[$n]);
					}
				}
				foreach($rows as $n => $rec) {
					$recsNew[] = $rec;
				}
				$rows = array_merge($recsNew,$recsSort);
			}
		}
		// 過濾檔案權限
		rs_pw_init();
if (bDebug) echo "過濾檔案權限 rows=".count($rows)."<br>";
		// 過濾掉沒有欄位 _i 的資料
		$cnt_dir=0; $cnt_img=0; $cnt_video=0; $cnt_audio=0; $cnt_html=0; $cnt_link=0; $cnt_doc=0; $cnt_other=0;
		$recs = array();
		foreach($rows as $rec) {
			if (empty($rec['_i'])) continue;
			if (!isset($rec['filename']) && !isset($rec['title'])) continue;
			
			$type = $rec['type'];
			$bDir = $type == "Directory" || $type == "Site";
			// 過濾檔案權限 - 自己的檔案不用判斷
			if (!rs_pw_chk_show($rec)) continue;
			$rec['pw'] = rs_pw_get_pw($rec);
			
			// 限制類別數量
			if ($type_ps > 0) {
				if ($bDir) {
					$cnt_dir++;
					if ($cnt_dir > $type_ps)
						continue;
				}
				else if ($type == "Image") {
					$cnt_img++;
					if ($cnt_img > $type_ps)
						continue;
				}
				else if ($type == "Video") {
					$cnt_video++;
					if ($cnt_video > $type_ps)
						continue;
				}
				else if ($type == "Audio") {
					$cnt_audio++;
					if ($cnt_audio > $type_ps)
						continue;
				}
				else if ($type == "Html") {
					$cnt_html++;
					if ($cnt_html > $type_ps)
						continue;
				}
				else if ($type == "Link") {
					$cnt_link++;
					if ($cnt_link > $type_ps)
						continue;
				}
				else if ($type == "Document" || $type == "Text") {
					$cnt_doc++;
					if ($cnt_doc > $type_ps)
						continue;
				}
				else {
					$cnt_other++;
					if ($cnt_other > $type_ps)
						continue;
				}
			}
			
			if ($bMIME && isset($rec['filename'])) {
				if ($type != "Directory" && $type != "Html" && $type != "BBS" && $type != "Bookmark") {
					$ext = strtolower(B_GetExtension($rec['filename']));
					$rec['MIME'] = con_Extension2ContentType($ext);
				}
			}
			// 取 目錄的上傳權限
			if ($bUpload && $type == "Directory") {
				$fp = rs_con_UrlPath2FilePath($rec['url']);
				$rec['pw_upload'] = chk_upload_right($page_dir, $fp) == PASS;
			}
			// 取原始內容
			if ($bContent && ($type == "Html" || $type == "Directory" || $type == "Site" || $type == "Text")) {
				if (empty($sDir))
					$f = PATH_Web.$rec['url'];
				else
					$f = DIR_SITE.$sDir."/".$rec['page_name'];
				if ($type == "Directory" || $type == "Site")
					$f = B_Url_AddNames($f, "index.html");
if (bDebug) echo "exists=".file_exists($f).", f=$f <br>";
				if (file_exists($f))
					$rec['content'] = B_LoadFile_utf8($f);
			}
			// 限制 content 的長度.
			else if (strlen($rec['content']) > 4096)
				$rec['content'] = B_Str_LimitLength($rec['content'],4096);
				
			$recs[] = $rec;
		}
		
		rs_con_Recs2OutRecs($recs, $bview_path, $sDir, $o_fields);
		// 取原始的記錄檔
		if ($bRecInfo) $recs = search_con_GetFileRecInfo($recs);
if (bDebug) {

		echo "type_ps=$type_ps, recs=".count($recs)."<br>";
		if ($type_ps > 0)
			echo "cnt_dir=$cnt_dir, cnt_img=$cnt_img, cnt_video=$cnt_video, cnt_audio=$cnt_audio, cnt_html=$cnt_html, cnt_doc=$cnt_doc, cnt_other=$cnt_other <br>";
	
		$dRecs = array();
		foreach($recs as $rec) {
			$dRecs[] = array(
				'rid' => $rec['rid'],
				'url' => $rec['url'],
				'mtime' => $rec['mtime'],
				//'tag' => $rec['tag'],
				'size' => $rec['size'],
			);
		}

		$x = strpos($data, "\n");
		echo "<br><br>cmd:<br>".substr($data, 0, $x)
			."<br><br>data:<br>".substr($data, $x, 1024)
			."<br><br>query:<br>".B_Array2String2($query)
			//."<br><br>recs:<br>".B_Array2String2($recs)
			//."<br><br>recs:<br>".B_Array2String2($dRecs)
			."<br><br>";

		unset($dRecs);
}
		
		unset($data);
		unset($rows);
if (bDebug) echo "memory_get_usage=".memory_get_usage()." <br>";
		$out = array();
		$out["sort_dir"] = empty($sort_dir) ? "" : implode("",array_keys($sort_dir));
		$out["cnt"] = $cnt;
		$out["recs"] = $recs;
		if ($type_ps > 0) {
			$out["type_cnt"] = array('Directory' => $cnt_dir, 'Image' => $cnt_img, 'Video' => $cnt_video,
									'Audio' => $cnt_audio, 'Html' => $cnt_html, 'Link' => $cnt_link,
									'Document' => $cnt_doc, 'Other' => $cnt_other);
		}
		if ($bHP) {
			$pc = (int)(($cnt-1)/$ps)+1;
			$out["HP"] = search_getPageHtml($p, $pc);
		}
		// if ($bOut_str) {
			// print 	"query:<br>".B_Array2String2($query)
					// ."recs:<br>".B_Array2String2($recs)
					// ."<br><br>";
		// }
if (bDebug) echo "out=".B_Array2String2($out)." <br>";
		print json_encode($out);
	}
}
function search_filter_highlight($str)
{
	$str = str_replace("[[[font color=red]]]", "",
			str_replace("[[[/font]]]", "",
			$str[0]));
	return $str;
}
// 首頁的動態訊息
function m_search_PageAll()
{
	require_once(DIR_TOOLS."search_lib.php");

	$SE_KEY = "SearchPageAll";
	
define("bDebug", isset($_REQUEST["debug"]) ? true : false);

	$site 		= isset($_REQUEST["site"]) 		 ? $_REQUEST["site"] 		: "Site";
	$file_path 	= isset($_REQUEST["file_path"])  ? $_REQUEST["file_path"] 	: "";
	$filter_site= isset($_REQUEST["filter_site"])? $_REQUEST["filter_site"] : "";
	$p			= isset($_REQUEST["p"])			 ? (int)$_REQUEST["p"] 		: 1;
	$ps 		= isset($_REQUEST["ps"])		 ? (int)$_REQUEST["ps"] 	: 10;
	
	$file_path	= rs_filter_Site($file_path);
	if (substr($file_path, -1) == '/') $file_path = substr($file_path, 0, -1);
	rs_init($site, $file_path);
	if (empty($file_path)) $site = "";

if (bDebug) { print_r($_COOKIE); echo "<br>"; }
$f_log = "logs/search_PageAll.log";
B_Log_f($f_log, "p=$p, ps=$ps, file_path=$file_path");

	// 取原始的記錄檔
	$bRecInfo	= isset($_REQUEST["getRecInfo"])? $_REQUEST["getRecInfo"] == 'y'	: false;
	// 顯示路徑名稱
	$bview_path	= true;
	// 過濾掉網路碟
	$bnot_driver= strpos($file_path, "/Driver") === false;
	
	$key = $SE_KEY.substr(md5($file_path), -8);
	$f_db = "";
	if (isset($_COOKIE[$key])) {
		$f_db = $_COOKIE[$key];
		if (!file_exists($f_db))
			$f_db = "";
		else if ($p == 1) {
			if (1 || filemtime($f_db) < time()-30) { // 30 秒 內不更新
				if (bDebug) echo "資料已經超過 3分鐘 - ".(time()-filemtime($f_db))." <br>";
				$f_db = "";
			} else {
				if (bDebug) echo "資料在 3分鐘內 - ".(time()-filemtime($f_db))." <br>";
			}
		}
	}
	if (!empty($f_db)) {
		$data = B_LoadFile($f_db);
		$arg = json_decode($data, TRUE);
		unset($data);
	} else {
		$f_db = tempnam(sys_get_temp_dir(), 'SPA');
		setcookie($key, $f_db);
		
		$arg = array();
		$arg['site'] = $site;
		$arg['file_path'] = $file_path;
		$arg['filter_site'] = $filter_site;
		$arg['p'] = 0;
		$arg['ps'] = 500;
		$arg['o'] = "time";
		$arg['od'] = "dec";
		$arg['cnt'] = 0;
		$arg['recs'] = array();
		$arg['bend'] = false;
		$arg['bRecInfo'] = $bRecInfo;
		$arg['bview_path'] = $bview_path;
		$arg['bnot_driver'] = $bnot_driver;
	}
if (bDebug) {
	echo "key=$key <br>";
	echo "f_db=$f_db <br>";
	print_r($_COOKIE); echo "<br>";	
}		
	
	
	if ($p < 1) $p = 1;
	$StartID = ($p-1)*$ps;
	// init
	// if ($p == 1) {
		// $arg['p'] = 0;
		// $arg['cnt'] = 0;
		// $arg['recs'] = array();
	// }
	$bUpdate = false;
	$xEnd = $StartID+$ps+1;
	while ($xEnd > $arg['cnt']) {
		$bUpdate = true;
		$arg['p']++;
		m_search_PageAll_getData($arg);
			
		if ($arg['bend'])
			break;
	}
	$recs = array_slice($arg['recs'], $StartID, $ps);

	$aRecs = array();
	foreach($recs as $rec) {
		// 1個檔案
		if ($rec['cnt'] == 1) {
			$aRecs[] = $rec['files'][0];
		}
		// 1個以上
		else {
			$recsDir = array();
			$fp = rs_con_UrlPath2FilePath($rec['u_fp']);
			$f_rec = B_Url_AddNames(PATH_WebPages, rs_con_FilePath2FileRec($fp));
			$f_data = B_LoadFile($f_rec);
			$rec0 = $rec['files'][0];
			$view_path = B_Url_MakePath($rec0['view_path'], false, false);
			
			// 有些功能目錄沒有 rec 檔
			if (empty($f_data)) {
				$url = $rec['u_fp'];
				$fp = rs_con_UrlPath2FilePath($url);
				$site_name = B_GetField($fp, "/", 0);
				$site_info = rs_sys_getSiteInfo($site_name);
				
				$recsDir[0]['url'] 		= $url;
				$recsDir[0]['owner'] 	= ($site_info !== false ? $site_info['owner'] : "");
				$recsDir[0]['filename'] = B_GetFileNameExtension($view_path);
				$recsDir[0]['title'] 	= $recsDir[0]['filename'];
				$recsDir[0]['type'] 	= "Directory";
				$recsDir[0]['dir_type'] = rs_dir_getType($fp);
			} else {
				$recsDir[] = B_Rec_Data2Rec($f_data);
				
				// 暫時, 預設目錄 owner 是主網站, 改成子網站的 owner
				$site_name = B_GetField($fp, "/", 0);
				$site_info = rs_sys_getSiteInfo($site_name);
				$recsDir[0]['url'] 		= $rec['u_fp'];
				$recsDir[0]['owner'] 	= ($site_info !== false ? $site_info['owner'] : "");
			}
			// 顯示路徑名稱
			rs_con_Recs2OutRecs($recsDir, $bview_path, "");
			// 取原始的記錄檔
			if ($bRecInfo) $recsDir = search_con_GetFileRecInfo($recsDir);
			
			$recDir = $recsDir[0];
			$recDir['view_path'] = $view_path;
			$recDir['t_first'] = date("YmdHis", $rec['t_first']); // B_Rec_Str2RDateTime
			$recDir['t_last']  = date("YmdHis", $rec['t_last']);
			$recDir['files']   = $rec['files'];
			
			$recDir['time'] = $rec0['time'];
			$recDir['owner'] = !empty($rec0['owner']) ? $rec0['owner'] : $rec0['acn'];
			$recDir['last_acn'] = $rec0['last_acn'];
			
			$aRecs[] = $recDir;
		}
		
		$aRecs[count($aRecs)-1]['group_id'] = ++$StartID;
	}
	// Save Data
	if ($bUpdate) {
		$data = json_encode($arg);
		B_SaveFile($f_db, $data);
	}

if (bDebug) {
// $test_info .= "m_search_PageAll_getData:<br>".$_SESSION[$SE_KEY]['debug']."<br>";
echo "NUWebCS recs:<br>".B_Array2String2($recs)."<br><br>";
echo "NUWebCS recs:<br>".B_Array2String2($aRecs)."<br><br>";
}
	$out = array();
// debug
$out["test_info"] = $test_info;
	$out["recs"] = $aRecs;
	print json_encode($out);
		
}
// site, file_path, p, ps, o, od, bview_path, bRecInfo, recs
function m_search_PageAll_getData(&$arg)
{
if (bDebug) echo "*** Test *** <br>";
	
	$bGroupView = empty($arg['file_path']); // 社群訊息
	$p = $arg['p'];
	$ps = $arg['ps'];
	//
	$aDenySelect = array("filename" => ".files");
	$aFilterSelect = array();
	if (!empty($arg['filter_site'])) {
		$a = explode(",", $arg['filter_site']);
		foreach($a as $site)
			$aFilterSelect[] = "-@url:/Site/{$site}/";
	}
	//
	$query = array();
	$query["site"] 		= $arg['site'];	// empety => site and ext
	$query["file_path"] = $arg['file_path'];
	$query["sort"] 		= $arg['o'];
	$query["sort_order"]= $arg['od'];
	// 拒絕條件
	$query["deny_slect"]	= $aDenySelect; 
	// $query["deny_nslect"]	= $aDenyNSelect;
	// $query["deny_tag"]		= $aDenyTag;
	// 自定條件 [+@$k:$v]
	$query["filter_select"]	= $aFilterSelect;
	//$query["filter_tag"]	= $aFilterTag;
	
	$data = str_replace("@\n@\n", "@\n", all_search($query, $p, $ps));
	$Total = file_rec_GetTotal($data);
	if (empty($Total)) $Total = 0;
	$recs = B_Rec_Data2Recs($data);
if (bDebug) echo B_Array2String2($query)."<br> Total=$Total <br>data=".substr($data,0,512)." <br>";
	
	// 顯示路徑名稱
	rs_con_Recs2OutRecs($recs, $arg['bview_path'], "");
	// 取原始的記錄檔
	if ($arg['bRecInfo']) $recs = search_con_GetFileRecInfo($recs);
	
	$aUGK = array(); // 過濾 User 一天 3測訊息
	$tCur = time();
	$dir_rec = null;
	$dir_tu = 0;
	foreach($recs as $rec) {
		$url = $rec['url'];
		$fp = rs_con_UrlPath2FilePath($url);
		$u_fp = B_Url_MakePath($url,false,false);
if (bDebug) echo "<br>fp=$fp <br>";
		if ($rec['type'] == "Directory") {
			
			// 過濾掉已經存在的目錄
			if (B_obj_indexOf($arg['recs'], "u_fp", $url) !== false)
				continue;
			// 過濾掉沒有寫描述的目錄
			if (empty($rec['content']))
				continue;
		}
		
		// 過濾掉網路硬碟
		if ($arg['bnot_driver'] && strpos($fp, '/Driver/') !== false) continue;
		// 過濾 .files 檔案
		if (preg_match("#.files\/#i", $rec['view_path'])
			|| preg_match("#temp\.html#i", $rec['filename'])) {
if (bDebug) echo ".files ~~~~~~~~~~~~ view_path=".$rec['view_path'].", filename=".$rec['filename']." <br>";
			continue;
		}
		
		$time = $rec['time'];
		$tu = strtotime($time);
		// 過濾掉未來時間
		if ($tu > $tCur) {
			continue;
		}

		$cnt = count($arg['recs']);
		$x = $cnt-1;
		for(; $x>=0; $x--)
		{
			$dir_rec = $arg['recs'][$x];
			$dir_tu = $dir_rec['t_last'];
if (bDebug) echo ($dir_tu - $tu).", tu=$tu, dir_tu=$dir_tu <br>";
			if ($tu < $dir_tu-1800) { // 小於 30分鐘
			//if ($dir_tu - $tu > 1800) { // 小於 30分鐘
				$x = -1;
				break;
			}
			// 同目錄
			if ($dir_rec['u_fp'] == $u_fp) {
				break;
			}
		}
		if ($x > -1) {
			$arg['recs'][$x]['t_last']  = $tu;
			$arg['recs'][$x]['cnt']	 += 1;
			if ($arg['recs'][$x]['cnt'] <= 10)
				$arg['recs'][$x]['files'][] = $rec;
		}
		else {
			// 過濾 User 一天 3測訊息
			if ($bGroupView) {
				$ugk = $rec['owner'].substr($time,0,8);
				if (isset($aUGK[$ugk])) {
					if ($aUGK[$ugk] >= 3) {
if (bDebug) echo "ugk=$ugk / ".$aUGK[$ugk].", 超過了 fp=$fp, time=$time <br>";
						continue;
					}
					else {
						$aUGK[$ugk]++;
					}
				}
				else {
					$aUGK[$ugk] = 1;
				}
if (bDebug) echo "ugk=$ugk / ".$aUGK[$ugk].", fp=$fp, time=$time <br>";
			}
			//
			$recDir = array();
			$recDir['t_first'] = $tu;
			$recDir['t_last']  = $tu;
			$recDir['u_fp']	   = $u_fp;
			$recDir['cnt']	   = 1;
			$recDir['files'][] = $rec;
			$arg['recs'][] = $recDir;
		}
	}
	$arg['p'] = $p;
	$arg['cnt'] = count($arg['recs']);
	$arg['bend'] = ($Total == 0 || ($p*$ps) >= $Total);
if (bDebug) echo "Total=$Total, p=$p, ps=$ps, bend=".($Total == 0 || ($p*$ps) >= $Total)." <br>";
}
// NUDrive [資料類別] - [所有xxx]
function m_search_DriveObjDir()
{
	require_once(DIR_TOOLS."search_lib.php");

	$site 		= isset($_REQUEST["site"]) 		? $_REQUEST["site"] 		: "Site";
	$file_path 	= isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] 	: "";
	$type 		= isset($_REQUEST["type"]) 		? $_REQUEST["type"] 		: "";
	$p			= isset($_REQUEST["p"])			? (int)$_REQUEST["p"] 		: 1;
	$ps 		= isset($_REQUEST["ps"])		? (int)$_REQUEST["ps"] 		: 999999;
	$o 			= isset($_REQUEST["sort"]) 		? $_REQUEST["sort"] 		: "time";
	$od 		= isset($_REQUEST["order"]) 	? $_REQUEST["order"] 		: "dec";
	$fc 		= isset($_REQUEST["fc"]) 		? $_REQUEST["fc"] 			: 10;	// Files Count
	$o_fields 	= isset($_REQUEST["o_fields"])	? stripslashes($_REQUEST["o_fields"])	: "";
	$file_path = rs_filter_Site($file_path);
	rs_init($site, $file_path);
	
	if ($fc < 1) $fc = 10;
	switch($type) {
		case "Article":
			$type = "Html,Directory";
			$size = '10-'.(0xFFFFFFFFF);
			break;
	}
	
$test_t = B_GetCurrentTime_usec();
	$query = array();
	$query["site"] 		= $site;	// empety => site and ext
	$query["file_path"] = $file_path;
	$query["type"] 		= $type;
	$query["size"] 		= $size;
	$query["sort"] 		= "mtime";
	$query["sort_order"]= "dec";
if (bDebug) echo "query:<br>".B_Array2String2($query)."<br>";
	$data = all_search($query, 1, 999999);
$test_t_search = B_GetCurrentTime_usec();
if (bDebug) echo "data_length:".strlen($data).", data=".substr($data, 0, 1024)."<br>";
	
	//$data = str_replace("@\n@\n", "@\n", $data);
	$Total = file_rec_GetTotal($data);
	if (empty($Total)) $Total = 0;
	$recs = B_Rec_Data2Recs($data);
	
	$dir_tags = array();
	$dir_year = array();
	$dir_recs = array();
	foreach($recs as $rec) {
		// 過濾掉不要的
		if ($rec['type'] == "Directory") continue;
		if (empty($rec['size'])) continue;
		//if (!rs_dir_is_share($rec['dir_type'])) continue;
		
		$rec['content'] = B_Str_LimitLength($rec['content'], 256);
		
		$fp = rs_con_UrlPath2FilePath($rec['url']);
		$dir = B_Url_MakePath($fp,false,false);
		if (!isset($dir_recs[$dir])) {
			$f_rec = PATH_WebPages."/".$dir."/.nuweb_rec/dir.rec";
			if (file_exists($f_rec)) {
				$dir_recs[$dir] = B_Rec_Data2Rec(B_LoadFile($f_rec));
				if (!empty($dir_recs[$dir]['tag'])) {
					$a_tag = explode(",", $dir_recs[$dir]['tag']);
					foreach($a_tag as $tag) {
						if (isset($dir_tags[$tag]))
							$dir_tags[$tag]++;
						else
							$dir_tags[$tag] = 1;
					}
				}
			} else {
if (bDebug) echo "not record dir=$dir <br>";
				$dir_recs[$dir] = array();
				//$dir_recs[$dir]['filename'] = B_GetFileNameExtension($dir);
				$dir_recs[$dir]['filename'] = B_GetFileNameExtension(B_Url_MakePath($rec['view_path'], false, false));
				$dir_recs[$dir]['dir_type'] = "directory";
				$dir_recs[$dir]['type'] 	= "Directory";
			}
			
			$dir_recs[$dir]['url'] = "/Site/".$dir;
			$dir_recs[$dir]['view_path'] = B_Url_MakePath($rec['view_path'], false, false);
			$dir_recs[$dir]['files_cnt'] = 0;
			// 以第一筆的 MTime 做為目錄 MTime
			$dir_recs[$dir]['mtime'] = $rec['mtime'];
				// MTime Group
			if (!empty($dir_recs[$dir]['mtime'])) {
				$year = substr($dir_recs[$dir]['mtime'],0,4);
				if (isset($dir_year[$year]))
					$dir_year[$year]++;
				else
					$dir_year[$year] = 1;
			}
		}
		//
		$dir_recs[$dir]['files_cnt']++;
		if ($dir_recs[$dir]['files_cnt'] <= $fc) {
			$dir_recs[$dir]['files'][] = $rec;
		}
	}
	//
	cmp_o_od($dir_recs, $o, $od);
	arsort($dir_tags);
	krsort($dir_year);
	// 過濾掉功能目錄
	$b_o_fields = !empty($o_fields);
	$a_o_fields = explode(",", $o_fields);
	$recs = array();
	$files_cnt = 0;
	foreach($dir_recs as $dir => $rec) {
		$dir = rs_con_UrlPath2FilePath($rec['url']);
if (bDebug) echo "dir=$dir, rs_dir_is_share=".rs_dir_is_share($rec['dir_type'])."<br>";

		if (rs_dir_is_share($rec['dir_type']))
		{
			// 指定欄位輸出
			if ($b_o_fields) {
				foreach($rec as $k => $v) {
					if (!in_array($k, $a_o_fields))
						unset($rec[$k]);
				}
			}
		
			$recs[$dir] = $rec;
			$files_cnt += $rec['files_cnt'];
		}
	}
	//
	$cnt = count($recs);
	if ($p < 1) $p = 1;
	$StartID = ($p-1)*$ps;
	$recs = array_slice($recs, $StartID, $ps);

$test_t_ok = B_GetCurrentTime_usec();
if (bDebug) {
	// echo "POWER_Manager=".POWER_Manager.", USER_ACN=".USER_ACN.", Admin=".POWER_Admin.", Show=".POWER_Show.", Upload=".POWER_Upload." <br>";
	
	echo "test_t=$test_t, test_t_search=$test_t_search, test_t_ok=$test_t_ok <br><br>";
	echo "dbman=".($test_t_search-$test_t).", PHP=".($test_t_ok-$test_t_search).", total=".($test_t_ok-$test_t)." <br><br>";
	
	// echo B_Array2String2($query)."<br> Total=$Total <br>"
		// ."data=$data<br><br>";
	
	echo "files_cnt:$files_cnt<br>"
		."recs:<br>".B_Array2String2($recs)
		."<br><br>";
}
	$out = array();
	$out["cnt"] = $cnt;
	$out["cnt_dir"] = $cnt;
	$out["cnt_file"] = $files_cnt;
	$out["dir_tags"] = $dir_tags;
	$out["dir_year"] = $dir_year;
	$out["recs"] = $recs;
	print json_encode($out);
}
// 取原始的記錄檔
function search_con_GetFileRecInfo($recs)
{
	$out = array();
	foreach ($recs as $rec) {
		if ($rec['type'] == "Directory" || $rec['type'] == "Html") {
			$sFilePath = rs_con_UrlPath2FilePath($rec['url']);
			if ($rec['type'] == "Directory") $sFilePath = B_Url_AddNames($sFilePath,"index.html");
			$sfHtml = B_Url_AddNames(PATH_WebPages, $sFilePath);
			$data = B_LoadFile($sfHtml);
			$D = B_GetSection($data, "<!-- ##TEMP_DESCRIBE_BEGIN##", "##TEMP_DESCRIBE_END## -->", false, true);
			$rec['description'] = $D;
			$rec['content'] = $data;
		}
		$out[] = $rec;
	}
	return $out;
}
// $p:頁數, $pc:總頁數, $bs:按鈕數
function search_getPageHtml($p, $pc, $bs=10)
{
	global $LangStr;
	if ($pc < 2) return "";
	
	$bc = (int)($bs / 2); // 中間值
	if ($p < 1) $p = 1;
	
	$pages = "";
	if ($p > 1) $pages .= '<a href="#" class="btn btn_s" rel="'.($p-1).'">上一頁</a>'."\r\n"; // 上一頁 '.$LangStr['BN_UpPage'].'
	
	$x = ($p > $bc ? $p -$bc : 1);
	for ($l=$bs; $l>0 && $x<=$pc; $l--, $x++) 
	{
		if ($x == $p)
			$pages .= '<span class="btn_sel" rel="'.$x.'">'.$x.'</span>'."\r\n";
		else
			$pages .= '<a href="#" class="btn btn_n" rel="'.$x.'">'.$x.'</a>'."\r\n";
	}
	
	if ($p < $pc)
		$pages .= '<a href="#" class="btn btn_s" rel="'.($p+1).'">下一頁</a>'."\r\n"; // 下一頁 .$LangStr['BN_DownPage'].
		
	return $pages;
}


function BAT_rec_GetTotal($data)
{
	if (preg_match("#^total:\s*(\d+)#mi", $data, $m))
		return (int)$m[1];
	return 0;
}

function BAT_GetTagList($sType)
{
	require_once(DIR_TOOLS."search_lib.php");
	
	$h = "";
	$cnt = 0;
	if ($sType == "Video")
	{
		$recs = get_tag_list("video", $sSite);
	}
	else
	{
		$query = array();
		$query['site'] = "Site";
		switch($sType) {
			case "All":
				break;
			case "Album":
				$query["fe"] = ".jpg";
				break;
			case "Article":
				$query["type"] = "Html";
				break;
			case "File":
				$query["type"] = "Other";
				break;
			case "Web":
				$query["type"] = "Directory";
				break;
//			case "Document":
//			case "Site":
//			case "Bookmark":
			default:
				$query["type"] = $sType;
		}
		$data = get_tag_group($query);
		$cnt = file_rec_GetTotal($data);
		$aRecs = B_Rec_Data2Recs($data);
		// 將 tags 切開成單一
		$oRecs = array();
		$cnt=0;
		foreach($aRecs as $aRec) {
			$cnt++;
			$a = explode(",", $aRec['key']);
			$n = (int)$aRec['cnt'];
			foreach($a as $k) {
				$k = trim($k);
				if (empty($k)) continue;
				if (isset($oRecs[$k])) {
					$oRecs[$k]['ks'] .= $cnt.",";
					$oRecs[$k]['cnt'] += $n;
				}
				else {
					$oRecs[$k]['ks'] = $cnt.",";
					$oRecs[$k]['cnt'] = $n;
				}
			}
		}
		// 轉成 $k => $v 
		$recs = array();
		foreach($oRecs as $k => $aRec) {
			$recs[$k] = $aRec['cnt'];
		}
		arsort($recs);
	}
	if (is_array($recs)) {
		arsort($recs, SORT_NUMERIC);
	}
	return $recs;
}

?>