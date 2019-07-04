<?php
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
define("API_DBMAN",	"/data/NUWeb_Site/Search/bin/dbman");
define("API_GET_EVENT",			"/API/get_event.php");
define("API_GET_DYMANIC",		"/API/get_dymanic_db.php");
define("API_WNS_GET_SHARE",		"/Share/get_share.php");


require_once("wbase2.php");
require_once("wrec.php");
require_once("rs_tools_base.php");
require_once("rs_cache_lib.php");

require_once("public_lib.php");
require_once(DIR_SITE."/config.php");


if (!file_exists(DIR_LOGS))
	mkdir(DIR_LOGS);
if (!file_exists(DIR_USERDB))
	mkdir(DIR_USERDB);


define("bDebug", isset($_REQUEST["debug"]) ? $_REQUEST["debug"] == "y" : false);
//echo "debug=".$_REQUEST["debug"]." <br>";
// if ($_REQUEST["debug"] == "123") {
	// phpinfo();
	// exit;
// }

$sMode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
//
if ($sMode == "is_login")
{
    print check_login_cookie() !== false ? "y" : "n";
	return;
}
// 同步 API
if (substr($sMode, 0, 5) == "sync_") {
	m_sync($sMode);
	return;
}


rs_user_init();
define("FP_USER_INFO",		DIR_USERDB.USER_ACN."_info.rec");
define("FP_CONTACTS_INFO",	DIR_USERDB.USER_ACN."_contacts_info.rec");
define("FP_CONTACTS_DB",	DIR_USERDB.USER_ACN."_contacts.rec");
define("FP_OFTEN_LIST_DB",	DIR_USERDB.USER_ACN."_often_list.rec");
//define("FP_TAG_LOG_DB",		DIR_USERDB.USER_ACN."_tag_log.rec");
define("FP_TAG_LOG_DB",		DIR_USERDB."wheechen_tag_log.rec");

define("FP_MEMBER_SITELIST",	DIR_USERDB.USER_ACN."_member_sitelist.rec");

define("USER_FP_DYNAMIC_INFO",	DIR_USERDB.USER_ACN."_dynamic_info.rec");
define("USER_FP_DYNAMIC",		DIR_USERDB.USER_ACN."_dynamic.rec");
define("USER_FP_DYNAMIC_INFO2",	DIR_USERDB.USER_ACN."_dynamic_info2.rec");
define("USER_FP_DYNAMIC2",		DIR_USERDB.USER_ACN."_dynamic2.rec");


if ($sMode == "redirect")
{
	m_redirect();
	return;
}
else if ($sMode == "get_site_list")
{
	m_get_site_list();
	return;
}
else if ($sMode == "get_site_info")
{
	m_get_site_info();
	return;
}
else if ($sMode == "get_member_sitelist")
{
	ignore_user_abort(1);
	set_time_limit(0);
	m_get_member_sitelist();
	return;
}
else if ($sMode == "get_web_dynamic")
{
	m_get_web_dynamic();
	return;
}
else if ($sMode == "friend_add_html")
{
	m_friend_add_html();
	return;
}


$user = USER_ACN;
if (empty($user))
	B_Error(403);

//	is_manager (系統管理者), admin_manager (後端管理者)
global $is_manager, $admin_manager;
define("POWER_Manager", $admin_manager);	// 系統管理者

// Ooki API
if (substr($sMode, 0, 5) == "ooki_") {
	m_ooki($sMode);
	return;
}


switch($sMode) {
	case "to_driver":
		m_to_driver();
		break;
		
	case "pc_get_upd_msg":
		m_pc_get_upd_msg();
		break;
		
	case "user_info_get":
		m_user_info_get();
		break;
		
	case "user_info_set":
		m_user_info_set();
		break;
		
	case "get_global_dynamic2":
		m_get_global_dynamic2();
		break;
		
	case "get_web_dynamic":
		m_get_web_dynamic();
		break;
		
	case "get_global_share":
		m_get_global_share();
		break;
		
	case "get_contacts":
		m_get_contacts();
		break;
		
	case "set_contacts":
		m_set_contacts();
		break;
		
	case "get_often_list_share":
		m_get_often_list_share();
		break;
		
	case "set_often_list_share":
		m_set_often_list_share();
		break;
		
	case "get_tag":
		m_get_tag();
		break;
		
	case "search_site":
		m_search_site();
		break;
		
	case "log":
		m_log();
		break;
		
	default:
		die_json("Error: Mode does not exist.($sMode)");
		break;
}

function m_pc_get_upd_msg()
{
	$out = array();
	
	$time = isset($_REQUEST["time"]) ? $_REQUEST["time"] : 0;
	
	// 動態訊息
	$InHeader = array();
	$InHeader['url'] = "http://localhost/tools/api_user_info.php?mode=get_global_dynamic2&get_new=y&expire=".$time;
	$InHeader['Cookie'] = rs_sys_getCookis_Power();
	$res = B_curl($InHeader);
	if (!B_chkErr_SendResult($res)) {
		$aRes = json_decode($res, true);
		if (isset($aRes['recs'])) {
			$aRec = array();
			foreach($aRes['recs'] as $rec) {
				$files = $rec['files'];
				foreach($files as $file) {
					$aRec[] = array(
						'url' => $file['url']
						,'title' => (empty($file['title']) ? $file['filename'] : $file['title'])
						,'description' => $file['description']
						,'type' => $file['type']
						,'owner' => $file['owner']
						,'size' => $file['size']
						,'time' => $file['time']
						,'tag' => $file['tag']
					);
				}
			}
			$aRes['recs'] = $aRec;
			$out['msgs'] = $aRes;
		}
		if (isset($aRes['time']))
			$out['time'] = $aRes['time'];
	}
	// NUMail
	$InHeader['url'] = "http://localhost/NUMail/api_mobile_mail_list.php?notag=r&page=1&itemsPerPage=100";
	$res = B_curl($InHeader);
	if (!B_chkErr_SendResult($res)) {
		$aRes = json_decode($res, true);
//if (bDebug) echo "NUMail aRes:<br>".B_Array2String($aRes)."<br><br><br>";
		$aRec = array();
		foreach($aRes['data'] as $rec) {
			$t = strtotime(str_replace(" ", "", $rec['d']));
			if ($t <= $time) continue;
			
			$aRec[] = array(
				'url' => "http://".SERVER_ACN.".nuweb.cc/NUMail/?view=mail_display&rid=".$rec['_i'][0]
				,'title' => $rec['T']
				,'description' => $rec['B']
				,'type' => "Mail"
				,'owner' => $rec['f']
				,'size' => $rec['s']
				,'time' => str_replace(" ", "", $rec['d'])
				,'tag' => ""
			);
		}
if (bDebug) echo "NUMail aRec:<br>".B_Array2String($aRec)."<br><br><br>";
		$out['numail'] = array(
			'total' => $aRes['total']
			,'recs' => $aRec
		);
	}
	
if (bDebug) echo "out:<br>".B_Array2String($out)."<br>";
	print json_encode($out);
}

function m_friend_add_html()
{
// http://10.0.0.28/tools/api_user_info.php?mode=friend_add_html&friend=wheechen
	global $reg_conf;

	$friend		= isset($_REQUEST["friend"])	? $_REQUEST["friend"]	: "";
	$val		= isset($_REQUEST["val"])		? $_REQUEST["val"]		: "";

	$arg = array();
	if (defined("USER_ACN")) {
		$arg['user'] 		= USER_ACN;
		$arg['user_ssn'] 	= USER_SSN;
		$arg['user_sun'] 	= USER_SUN;
		$arg['user_mail'] 	= USER_MAIL;
	}
	$arg['server_acn'] 	= SERVER_ACN;
	$arg['server_sun']	= $reg_conf['sun'];
	
	$arg['friend']		= $friend;
	$arg['val']			= $val;
	
	$earg = json_encode($arg);
	
print <<<_EOT_
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/tools/rs_tools_lib.js"></script>
<script type="text/javascript" src="/tools/wrs_lib.js"></script>
<script>
var sys_is_iframe = window != window.parent;
var gArg = $earg;

$(document).ready(function(){
	// 沒有登入
	if (gArg.user == null || gArg.user == "") {
		rs_Login(false);
		return;
	}
	//
	friend_getList(function(){
		
		friend_add(gArg.friend, gArg.val, function(){
			
			OnCancel();
		});
	});
	
});
function friend_getList(funcOK){
	WRS_API_WNS_friend_api({
		mode:	"get"
		,funcOK:function(data){
			
// console.log('~~~ WRS_API_WNS_friend_api get data=', data);
			if (data == null) {
				OnCancel();
				return;
			}
			
			//$(".se_msg").append(data.friend+"<br>");
			gArg.list = data.friend;
			funcOK();
		}
	});
}
function friend_add(acn, val, funcOK)
{
	WRS_API_WNS_friend_api({
		mode: 	(val == "y" ? "pass" : "deny")
		,acn:	acn
		,funcOK:function(data, err){
			
			if (data) {
				if (val == "y") {
					$(".se_msg").html("已加為好友");
				} else {
					$(".se_msg").html("已經刪除邀請");
				}
			}
			else {
				if (val == "y" && rec_tag_is_exists(gArg.list, acn))
					$(".se_msg").html("已加為好友");
				else if (err && err.error && err.error.indexOf("friend waiting list") > -1)
					$(".se_msg").html("已經取消或過時了<br>");
				else
					$(".se_msg").html(err);
				
			}
			funcOK();
		}
	});
}
function OnCancel(){
	setTimeout(function(){
		if (sys_is_iframe) {
			B_win_close_ParReload();
		}
		else {
			location.href = "/";
		}
	},1000);
}

</script>
</head>
<body>
<h1 class="se_msg"></h1>
</body>
</html>
_EOT_;
	require_once("/data/Admin/wns_init.php");
	
	
	$url = "http://".$wns_ser.":".$wns_port."/UserProfile/friend_api.php";
	$arg = array();
	$arg['code'] = rs_sys_getCookis_Power();
	$arg['mode'] = "get";
}

function m_user_info_get()
{
	$info = B_Rec_File2Rec(FP_USER_INFO);
	
	print json_encode(array(
				'info' => $info
			));
}
function m_user_info_set()
{
	$atc	= isset($_REQUEST["atc"])	? $_REQUEST["atc"]: "";
	$srec	= isset($_REQUEST["rec"])	? $_REQUEST["rec"]: "";
	$rec = B_Rec_Data2Rec($srec);
	
	$info = B_Rec_File2Rec(FP_USER_INFO);
	$bMsgUps = false;
	if ($atc == "msg_deny_source_add")
	{
		$bMsgUps = true;
		$info['msg_deny_source'] = B_tag_add($info['msg_deny_source'], $rec['msg_deny_source']);
	}
	else if ($atc == "msg_deny_source_del")
	{
		$bMsgUps = true;
		$info['msg_deny_source'] = B_tag_del($info['msg_deny_source'], $rec['msg_deny_source']);
	}
	else if ($atc == "msg_deny_owner_add")
	{
		$bMsgUps = true;
		$info['msg_deny_owner'] = B_tag_add($info['msg_deny_owner'], $rec['msg_deny_owner']);
	}
	else if ($atc == "msg_deny_owner_del")
	{
		$bMsgUps = true;
		$info['msg_deny_owner'] = B_tag_del($info['msg_deny_owner'], $rec['msg_deny_owner']);
	}
	else if ($atc == "msg_post_pw_set")
	{
		if (!empty($rec['msg_post_pw']) )
			$info['msg_post_pw'] = $rec['msg_post_pw'];
	}
	else
	{
	}
	// 
	B_Rec_Rec2File(FP_USER_INFO, $info, false);
	// 清潔 - 動態訊息的更新日期
	if ($bMsgUps) {
		$msg_info = B_Rec_Data2Rec(B_LoadFile(USER_FP_DYNAMIC_INFO2));
		$msg_info['dynamic_all_update'] = 0;
		$msg_info['dynamic_utime'] = 0;
		B_Rec_Rec2File(USER_FP_DYNAMIC_INFO2, $msg_info, false);
	}

	
	print json_encode(array(
				'info' => $info
			));
}

function m_log()
{
	ignore_user_abort(1);
	set_time_limit(0);
	
	$name	= isset($_REQUEST["name"]) 		? $_REQUEST["name"] 	: "";
	$msg 	= isset($_REQUEST["msg"]) 		? $_REQUEST["msg"] 		: "";
	if (empty($name)) $name = "def";
	
	$f = DIR_LOGS.USER_ACN."_".$name.".log";
if (bDebug) echo "f=$f <br>";
	B_Log_f($f, $msg);
	print "OK";
}

function m_redirect()
{
	$url	= isset($_REQUEST["url"])	? $_REQUEST["url"]	: "";
	if (empty($url)) $url = "/";
	
	echo "POWER_Manager=".POWER_Manager.", USER_ACN=".USER_ACN.", USER_SSN=".USER_SSN.", USER_SUN=".USER_SUN.", USER_MAIL=".USER_MAIL." <br>";
	echo "url=$url <br>";
//	header("Location: {$url}");
	
	exit;
}
function m_get_tag()
{
	$type	= isset($_REQUEST["type"])	? $_REQUEST["type"]	: "";
	
	$nselect_arg = "";
	if ($type == "hot") {
		$day = 90;
		$aNSelect = array();
			$start_time = date("YmdHis", time() - ($day*24*60*60));
			$end_time = date("YmdHis");
			$aNSelect[] = "+@t:$start_time-$end_time";
		$nselect_arg = "-nselect \"".implode(";", $aNSelect)."\"";
	}
	
	$cmd = "\"".API_DBMAN."\" -recbeg \"@\\n\" $nselect_arg -groupby \"@name:\" -orderby cnt \"".FP_TAG_LOG_DB."\"";
if (bDebug) echo "cmd=$cmd <br>";
	$result = shell_exec($cmd);
if (bDebug) echo "result=$result <br>";

	$cnt = B_Rec_GetTotal($result);
	$recs = B_Rec_Data2Recs($result);
if (bDebug) echo "recs:<br>".B_Array2String2($recs)." <br><br>";
	
	$out = array();
	$out['cnt'] = count($recs);
	$out['recs'] = $recs;
	print json_encode($out);
}

function m_get_often_list_share()
{
	$o			= isset($_REQUEST["o"]) 		? $_REQUEST["o"] 			: "cnt";
	$od 		= isset($_REQUEST["od"]) 		? $_REQUEST["od"] 			: "dec";

	$recs = B_Rec_File2Recs(FP_OFTEN_LIST_DB);
	$cnt = count($recs);
	if ($cnt > 0) {
		cmp_o_od($recs, $o, $od);
	}
	$recInfo['cnt'] = $cnt;
	$recInfo['recs'] = $recs;
	print json_encode($recInfo);
}
// list => acn \t sun \t mail \n acn \t ...
function m_set_often_list_share()
{
	$list = isset($_REQUEST["list"]) ? $_REQUEST["list"] : "";
	if (empty($list)) die_json("Error: empty list.");
	
	$recs = B_Rec_File2Recs(FP_OFTEN_LIST_DB, "acn");
	
	$rows = explode("\n", $list);
	foreach($rows as $row) {
		$cols = explode("\t", $row);
		if (count($cols) != 3) continue;
		$acn = strtolower(trim($cols[0]));
		if (empty($acn)) continue;
		
		if (isset($recs[$acn])) {
			$recs[$acn]['cnt']++;
			if (!empty($cols[1]) && $recs[$acn]['sun'])	 $recs[$acn]['sun'] = $cols[1];
			if (!empty($cols[2]) && $recs[$acn]['mail']) $recs[$acn]['mail'] = $cols[2];
		}			
		else {
			$recs[$acn]['acn'] = $acn;
			$recs[$acn]['sun'] = $cols[1];
			$recs[$acn]['mail'] = $cols[2];
			$recs[$acn]['cnt'] = 1;
		}
	}
	
	B_Rec_Recs2File(FP_OFTEN_LIST_DB, $recs);
	
	print return_json("ok");
}

function m_get_site_info()
{
	$site_name	= isset($_REQUEST["site_name"])	? $_REQUEST["site_name"] : "";
	$bPW		= isset($_REQUEST["pw"])		? $_REQUEST["pw"] == "y" : "";
	
	$info = rs_getSiteInfo($site_name);
	define("POWER_Admin", chk_manager_right($site_name)==PASS);
	if (POWER_Admin) {
		if ($bPW) {
			$info['bAdmin'] = true;
			$info['bShow'] = true;
			$info['bUpload'] = true;
		}
	}
	else {
		$bShow = chk_browse_right(DIR_SITE, $site_name) == PASS;
		if (!$bShow) B_Error(403);
		unset($info['status']);
		unset($info['owner_info']);
		unset($info['a_owner_info']);
		
		if ($bPW) {
			$bUpload = chk_upload_right(DIR_SITE, $site_name) == PASS;
			$info['bAdmin'] = false;
			$info['bShow'] = true;
			$info['bUpload'] = $bUpload;
		}
	}
if (bDebug) echo "info:<br>".B_Array2String2($info)." <br>";
	print json_encode($info);
}

function m_to_driver()
{
	$hash	= isset($_REQUEST["hash"])	? $_REQUEST["hash"]	: "";
	
	$InHeader['Cookie'] = rs_sys_getCookis_Power();
	$url = "http://localhost/tools/api_user_info.php?mode=get_site_list";
	$info = rs_cache_get_url($url, 30, $InHeader, true);
	$res = json_decode($info['data'], true);
// if (bDebug) echo "url=$url <br>";
// if (bDebug) echo "InHeader:<br>".B_Array2String2($InHeader)." <br>";
	
	$aSite = array();
	foreach($res['recs'] as $rec) {
		if ($rec['type'] == "0" && $rec['owner'] == USER_ACN) {
			$aSite[] = $rec;
		}
	}
	if (count($aSite) == 0)
		B_Error(404);
	
	$url = "/tools/page/show_page.php?page_url=/Site/".$aSite[0]['acn']."/Driver/";
	if (!empty($hash)) $url .= "#".$hash;
	if (bDebug) echo "url=$url <br>";
	header("Location: ".$url);
	
// if (bDebug) echo "aSite:<br>".B_Array2String2($aSite)." <br>";
// if (bDebug) echo "res:<br>".B_Array2String2($res)." <br>";
	
}

function m_get_site_list()
{
$f_log = "logs/".USER_ACN."_aui_get_site_list.log";

	global $lang, $set_conf, $reg_conf, $login_user, $group_mode;
if (bDebug) echo "group_mode=$group_mode <br>";

	$type		= isset($_REQUEST["type"])		? $_REQUEST["type"]			: "";		// [owner / manager / member]
	$site_acn	= isset($_REQUEST["site_acn"])	? $_REQUEST["site_acn"]		: "";
	$b_site		= isset($_REQUEST["site"])		? $_REQUEST["site"]== "y"	: false;	// 只取網站
	$b_group	= isset($_REQUEST["group"])		? $_REQUEST["group"]== "y"	: false;	// 只取社群
	$b_global	= isset($_REQUEST["global"])	? $_REQUEST["global"]== "y"	: false;	// 取全部社群
	$ext_acns	= isset($_REQUEST["ext_acns"])	? $_REQUEST["ext_acns"]		: "";		// 外加 網站
	$bUpdate	= isset($_REQUEST["update"]) 	? $_REQUEST["update"] == "y": false;	// 更新 cache
	
	$user_acn = USER_ACN;
	$bLogon = !empty($user_acn);
	if ($user_acn == "wheechen" && isset($_REQUEST["acn"]))
		$user_acn = $_REQUEST["acn"];
if (bDebug) echo "USER_ACN=".USER_ACN.", type=$type, b_group=$b_group, POWER_Manager=".POWER_Manager." <br>";
if (bDebug) echo "user_acn=$user_acn <br>";
if (bDebug) echo "USER_SSN=".USER_SSN." <br>";
if (bDebug) echo "rs_wns_getHostIP=".rs_wns_getHostIP()." <br>";
	
	$out_recs = array();
	$out_recs_w = array();
	$out_recs_m = array();
	
	// 分散式 Server
	$b_group_server = $group_mode == GROUP_SERVER || $group_mode == GROUP_CLIENT;
	// 這台是 Client
	if ($group_mode == GROUP_CLIENT)
	{
		$InHeader['Cookie'] = rs_sys_getCookis_Power();
		$gcs_ip = rs_getHostIPByACN($set_conf["group_server"]);
		$url = "http://".$gcs_ip."/tools/api_user_info.php";
		$arg = array();
		$arg['mode'] 	= "get_site_list";
		$arg['type'] 	= $type;
		$arg['site_acn']= $site_acn;
		$arg['site'] 	= $b_site ? "y" : "n";
		$arg['group'] 	= $b_group ? "y" : "n";
		$arg['ext_acns']= $ext_acns;
		$url .= "?".http_build_query($arg);
		$info = rs_cache_get_url($url, ($bUpdate ? 1 : 30), $InHeader, true);
		$res = json_decode($info['data'], true);
		
		if (is_array($res)) {
			if (is_array($res['recs'])) 	$out_recs 	= $res['recs'];
			if (is_array($res['recs_w'])) 	$out_recs_w = $res['recs_w'];
			if (is_array($res['recs_m'])) 	$out_recs_m = $res['recs_m'];
			$group_main_url = $res['group_main_url'];
		}
	}
	else
	{
		if ($group_mode == GROUP_SERVER)
			$DBPath = PATH_GROUP_SERVER_SITELIST;	// 分散式 Server
		else
			$DBPath = PATH_SERVER_SITELIST;			// 單機 Server
		
		$aTag = array();
		if (empty($type)) {
			$aTag[] = "@owner:".$user_acn;
			$aTag[] = "@manager:".$user_acn;
			$aTag[] = "@member:".$user_acn;
		}
		else {
			if (preg_match("#(^|,)\s*owner\s*($|,)#i", $type))	$aTag[] = "@owner:".$user_acn;
			if (preg_match("#(^|,)\s*manager\s*($|,)#i", $type))$aTag[] = "@manager:".$user_acn;
			if (preg_match("#(^|,)\s*member\s*($|,)#i", $type))	$aTag[] = "@member:".$user_acn;
		}
		if (POWER_Manager && $b_manager && !$b_site) $aTag[] = "@site_acn:web";
		if ($b_site) $aTag[] = "+@type:0";
		if ($b_group) $aTag[] = "+@type:1";
		$tag_arg = count($aTag) > 0 ? "-tag \"".implode(";", $aTag)."\"" : ""; // -tagcaseins 不區分大小寫
		$sort_arg = "-sort -key \"@name:\"";
		
		$cmd = "\"".API_DBMAN."\" -recbeg \"@\\n@GAIS_Rec:\" -avesize 2000 -flag \"@_f:Normal\" $tag_arg $sort_arg \"".$DBPath."\"";
if (bDebug) echo "cmd=$cmd <br>";
		$result = shell_exec($cmd);
if (bDebug) echo "result=$result <br>";

		$cnt = rec_GetTotal($result);
		$recs = B_Rec_Data2Recs($result);
		
		foreach($recs as $rec) {
		
			unset($rec['_f']);
			unset($rec['_c']);
			unset($rec['_m']);
			unset($rec['_y']);
			unset($rec['_S']);
			unset($rec['_s']);
			unset($rec['_A']);
			
			$rec['site_acn']= strtolower($rec['site_acn']);
			$rec['acn'] 	= $rec['site_acn'];
			$rec['sun'] 	= $rec['name'];
			$rec['ctime'] 	= $rec['crt_time'];
			
			$owner_info = array();
			list($owner_info['ssn'],$owner_info['acn'],$owner_info['mail'],$owner_info['sun']) = explode(":", $rec['owner_info']);
			$rec['owner_info'] = $owner_info;
			if (!$bLogon) {
				unset($rec['owner_info']['ssn']);
				unset($rec['owner_info']['mail']);
			}
			
			if ($rec['owner'] == $user_acn || B_tag_is_exists($rec['manager'], $user_acn)) {
				if ($rec['status'] == "A")
					$out_recs[] = $rec;
				else if ($rec['status'] == "W")
					$out_recs_w[] = $rec;
			}
			else 
				$out_recs_m[] = $rec;
		}
		//
		$group_main_url = get_server_url();
	}
	
	// 網站設定值
	$site_conf = empty($site_acn) ? array() : read_conf(DIR_SITE.$site_acn."/".NUWEB_CONF);
	
	$out = array();
	$out['lang'] 			= !empty($lang) ? $lang : "cht";
	$out['show_home_icon']	= $set_conf['show_home_icon'] != "N" && $site_conf['show_home_icon'] != "N";
	$out['use_drive'] 		= $set_conf['use_drive'] != "N"; // 啟用 NUDrive 功能
	$out['use_numail'] 		= $set_conf['use_numail'] != "N"; // 啟用 NUMail 功能
	$out['sys_subsite_mode']= isset($set_conf['subsite_mode']) ? $set_conf['subsite_mode'] : "";
	$out['sys_show_reg'] 	= isset($set_conf['show_reg']) ? $set_conf['show_reg'] != "N" : true;
	
	$out['bManager']	= POWER_Manager;
	$out['user'] 		= USER_ACN;
	$out['user_ssn'] 	= USER_SSN;
	$out['user_sun'] 	= USER_SUN;
	$out['user_mail'] 	= USER_MAIL;
	$out['server_acn'] 	= SERVER_ACN;
	$out['server_sun']	= rs_sys_get_server_sun();
	$out['numail_is_install'] = rs_is_NUMail_Install();
	//
	$out['logo_url'] 	= (!empty($set_conf["logo_url"]) ? $set_conf["logo_url"] 
							: (defined(DEF_SERVER_LOGO_URL) ? DEF_SERVER_LOGO_URL
							: ""));
	
	$out['cnt'] 		= count($out_recs);
	$out['recs'] 		= $out_recs;
	$out['recs_w'] 		= $out_recs_w;
	$out['recs_m'] 		= $out_recs_m; // 加入的成員
	// 分散式 Server 
	$out['group_mode'] 	= $group_mode;
	if ($b_group_server) {
		$out['group_server']= $set_conf["group_server"];
		$out['group_main_url'] = $group_main_url;
	}
	// 取全部社群
	if ($b_global)
	{
		$user_info = B_Rec_File2Rec(FP_USER_INFO);
		$aDenySrc = explode(",", $user_info['msg_deny_source']);
		
		$aSite = array();
		$out_gm = array();
		foreach($out_recs as $rec) {
			$acn = strtolower($rec['acn']);
			$cs = empty($rec['cs']) ? SERVER_ACN : $rec['cs'];
			$site = $acn.".".$cs;
			if (isset($aSite[$site])) continue;
			if (!(!empty($ext_acns) && $acn == $ext_acns) // 外加 網站 不能過濾掉
				&& $rec['type'] != 1) continue;
				
			$aSite[$site] = 1;
			$bHide = in_array($site, $aDenySrc);
			$out_gm[] = array(
				'site' => $site,
				'acn' => $acn,
				'sun' => $rec['sun'],
				'cs' => $cs,
				'cs_ip' => "localhost",
				'type' => "admin",
				'deny' => $bHide,
				'tag' => $rec['tag']
			);
		}
		//
		$recsMem = user_get_member_sitelist($bUpdate);
		if (!empty($recsMem)) {
			foreach($recsMem as $rec) {
				$site = strtolower($rec['site']);
				if (isset($aSite[$site])) continue;
				$aSite[$site] = 1;
				$bHide = in_array($site, $aDenySrc);
				
				$out_gm[] = array(
					'site' => $site,
					'acn' => strtolower($rec['acn']),
					'sun' => $rec['sun'],
					'cs' => $rec['cs'],
					'cs_ip' => $rec['cs_ip'],
					'type' => (isset($rec['bAdmin']) && $rec['bAdmin'] ? "admin" : "mem"),
					'deny' => $bHide,
					'tag' => $rec['tag']
				);
			}
		}
		//
		usort($out_gm, "m_get_site_list_gm_sort_cmp");
		
		$out['recs_gm'] = $out_gm;
		$out['msg_deny_source'] = $user_info['msg_deny_source'];
		$out['msg_deny_owner'] = $user_info['msg_deny_owner'];
	}
	
if (bDebug) echo "out:<br>".B_Array2String2($out)." <br>";
	print json_encode($out);
}
function m_get_site_list_gm_sort_cmp($a, $b)
{
	if ($a['type'] == $b['type']) {
		if ($a['sun'] == $b['sun'])
			return 0;
		return $a['sun'] > $b['sun'] ? 1 : -1;
	}
	return $a['type'] == "admin" ? -1 : 1;
}


function m_get_member_sitelist()
{
	$user = USER_ACN;
	if (empty($user)) B_Error(403);
	
	$bUpdate	= isset($_REQUEST["update"]) ? $_REQUEST["update"] == "y"	: false;
	
	$recs = user_get_member_sitelist($bUpdate);
	
	$out = array();
	$out['cnt'] = count($recs);
	$out['recs'] = $recs;
if (bDebug) echo "out:<br>".B_Array2String2($out)." <br><br>";
	print json_encode($out);
}
function m_get_global_share()
{
	$url_get_share = rs_wns_getUrl().API_WNS_GET_SHARE;
	$setup_conf = sys_get_setup_conf();
if (bDebug) echo "setup_conf:<br>".B_Array2String2($setup_conf)." <br>";
	if ($setup_conf != false) {
		// 封閉式 Server
		if ($setup_conf['close_server'] == "Y") {
			$url_get_share = "http://".$_SERVER["SERVER_ADDR"]."/API/get_share.php";
		}
	}
	
	$fun= isset($_REQUEST["fun"])	? $_REQUEST["fun"]	: "";
	
	$aArg = array();
	$aArg['fun'] = $fun;
	$InHeader['Cookie'] = rs_sys_getCookis_Power();
	$data = B_file_get_contents_post($url_get_share, $aArg, $OutHeader, $InHeader);
if (bDebug) echo "Cookie=".rs_sys_getCookis_Power()." <br>";
if (bDebug) echo "url_get_dymanic=$url_get_share <br>";
if (bDebug) echo "aArg:<br>".B_Array2String2($aArg)." <br><br>";
if (bDebug) echo "InHeader:<br>".B_Array2String2($InHeader)." <br><br>";
if (bDebug) echo "OutHeader:<br>".B_Array2String2($OutHeader)." <br><br>";
if (bDebug) echo "data:<br>".B_Array2String2(json_decode($data,true))." <br><br>";
	print $data;
}
function m_get_global_dynamic2()
{
if (bDebug) echo "m_get_global_dynamic2 ********** <br><br>";

	ignore_user_abort(1);
	set_time_limit(0);
	
define("FP_LOG2", "logs/_get_global_dynamic2.log");
$qtt = B_GetCurrentTime_usec();

	define("FP_LOG", "logs/".USER_ACN."_aui_get_global_dynamic.log");
	$dynamic_ver = "1.070";
	$user = strtolower(USER_ACN);
if (bDebug) echo "FP_LOG=".FP_LOG." <br>";
if (bDebug) echo "dynamic_ver=$dynamic_ver, user=$user <br>";
	

/***
	/API/get_dymanic_db.php
	參數:
	mode: [ / group]	// group => site_acn and owner 作 Group
	gt : 查時間之後
	lt : 查時間之前
	as : 查網站
	ds : 過濾網站
	ao : 查 owner
	do : 過濾 owner
***/
	$url_get_dymanic = "http://localhost".API_GET_DYMANIC;
	// 封閉式 Server
	/*$setup_conf = sys_get_setup_conf();
if (bDebug) echo "setup_conf:<br>".B_Array2String2($setup_conf)." <br>";
	if ($setup_conf != false) {
		if ($setup_conf['close_server'] == "Y") {
			$url_get_dymanic = "http://".$_SERVER["SERVER_ADDR"]."/API/get_dymanic.php";
		}
	}*/
	
	
	$pt		= isset($_REQUEST["pt"])	? (int)$_REQUEST["pt"]	: 0;
	$ps		= isset($_REQUEST["ps"])	? (int)$_REQUEST["ps"]	: 5;
	$expire	= isset($_REQUEST["expire"])? $_REQUEST["expire"]	: 0;	// 過期時間 手機用
	
	$id		= isset($_REQUEST["id"])	? $_REQUEST["id"]		: "";	// 
	$as		= isset($_REQUEST["as"])	? $_REQUEST["as"]		: "";	// 查網站
	$ao		= isset($_REQUEST["ao"])	? $_REQUEST["ao"]		: "";	// 查 owner
	$au		= isset($_REQUEST["au"])	? $_REQUEST["au"]		: "";	// page_url 查某個目錄底下的所有訊息
//	$du		= isset($_REQUEST["du"])	? $_REQUEST["du"]		: "";	// page_url 過濾掉某個目錄底下的所有訊息
	$ts		= isset($_REQUEST["ts"])	? (int)$_REQUEST["ts"]	: "";	// Start Time
	$kw		= isset($_REQUEST["kw"])	? $_REQUEST["kw"]		: "";	// Keyword
	// act => mode = [group / id]
	$act	= isset($_REQUEST["act"])	? $_REQUEST["act"]		: "";
	$gt		= isset($_REQUEST["gt"])	? (int)$_REQUEST["gt"]	: 0;
	$lt		= isset($_REQUEST["lt"])	? (int)$_REQUEST["lt"]	: 0;
	// 取得新的訊息
	$bGetNew= isset($_REQUEST["get_new"])? $_REQUEST["get_new"] == "y"	: false;
	$new_time=isset($_REQUEST["new_time"])? $_REQUEST["new_time"]		: 0;
	// 更新 cache
	$bUpdate= isset($_REQUEST["update"])? $_REQUEST["update"] == "y"	: false;	
	
	if ($as == "all") $as = "";
	if ($ao == "all") $ao = "";
	
	$user_info = B_Rec_File2Rec(FP_USER_INFO);
if (bDebug) echo "user_info:<br>".B_Array2String2($user_info)." <br><br>";
	if (is_array($user_info)) {
		if (!empty($user_info['msg_deny_source'])) $ds = $user_info['msg_deny_source']; // 過濾網站
		if (!empty($user_info['msg_deny_owner']))  $do = $user_info['msg_deny_owner'];	// 過濾 owner
	}
	
	
	$bUserView = $act == "user_view"; // 會清潔最新訊息數
	if ($act == "user_view") $act = "";
	$bDefView = empty($act) && empty($as) && empty($ao) && empty($au) && empty($ts) && empty($kw);
if (bDebug) echo "*** act=$act, bUserView=$bUserView, bDefView=$bDefView <br>";
	
	$new_cnt = 0;
	$out_recs = array();
	$recs = array();
	// mode => [group]
	if (!empty($act))
	{
		$aArg = array();
		$aArg['mode'] = $act;
		//
$tt = B_GetCurrentTime_usec();
		$InHeader = array();
		if ($act == "group")
		{
			if ($lt > $pt) $pt = $ts;
			if (!empty($au)) $aArg['au'] = $au;
			if ($pt > 0) 	$aArg['lt'] = $pt;
			if ($gt > 0)	$aArg['gt'] = $gt;
			
			$aArg['user'] = $user;
			if (!empty($as)) $aArg['as'] = $as;
			$InHeader['Cookie'] = rs_sys_getCookis_Power();
			$url = $url_get_dymanic."?".http_build_query($aArg);
			$info = rs_cache_get_url($url, 180, $InHeader, true);
			$OutHeader = $info['head'];
			$res = json_decode($info['data'], true);
		}
		else
		{
			if (!empty($id)) {
				if (B_isUrl($id))
					$aArg['mode'] = "url";
				$aArg['id'] = $id;
			}
			
			$InHeader['url'] = $url_get_dymanic;
			$InHeader['Cookie'] = rs_sys_getCookis_Power();
			$res = json_decode(B_curl($InHeader, $aArg, $OutHeader), true);
		}
if (bDebug) echo "*** B_curl tt=".(B_GetCurrentTime_usec()-$tt)." <br>";

if (bDebug) echo "url_get_dymanic={$url_get_dymanic}?".htmlspecialchars(http_build_query($aArg))." <br>";
if (bDebug) echo "aArg:<br>".B_Array2String2($aArg)." <br><br>";
if (bDebug && is_array($info)) echo "info:<br>".B_Array2String2($info)." <br><br>";
if (bDebug && !is_array($info)) echo "OutHeader:<br>".B_Array2String2($OutHeader)." <br><br>";
if (bDebug) echo "res:<br>".B_Array2String2($res)." <br><br>";
		
		if ($act == "group")
		{
			if ($res) {
				$site = $res['siteids'];
				$owner = $res['owners'];
				cmp_o_od($site, "total", "dec");
				cmp_o_od($owner, "total", "dec");
			}
			if (!is_array($site)) $site = array();
			if (!is_array($owner)) $owner = array();
			$out = array();
			$out['site'] = $site;
			$out['owner'] = $owner;
if (bDebug) echo "out:<br>".B_Array2String2($out)." <br><br>";
			print json_encode($out);
		}
		else
		{
			$out = array();
			$out['data'] = $res;
if (bDebug) echo "out:<br>".B_Array2String2($out)." <br><br>";
			print json_encode($out);
		}
		
		exit;
/*** EXIT ***/

	}
	// 有過濾條件 - 直接向 DB 要
	else if (!$bDefView)
	{
if (bDebug) echo "*** 有過濾條件 *** <br>";

		if ($ts > $pt) $pt = $ts;
		$aArg = array();
		$aArg['ps'] = $ps;
		if ($pt > 0) $aArg['lt'] = $pt;
		if (!empty($as) && !empty($ao)) {
			$aArg['as'] = $as;
			$aArg['ao'] = $ao;
		}
		else if (!empty($as)) {
			$aArg['as'] = $as;
			if (!empty($do)) $aArg['do'] = $do;
		}
		else if (!empty($ao)) {
			$aArg['ao'] = $ao;
			if (!empty($ds)) $aArg['ds'] = $ds;
		} 
		else {
			if (!empty($do)) $aArg['do'] = $do;
			if (!empty($ds)) $aArg['ds'] = $ds;
		}
		if (!empty($au)) $aArg['au'] = $au;
		if (!empty($kw)) $aArg['kw'] = $kw;
		//
		$InHeader['url'] = $url_get_dymanic;
		$InHeader['Cookie'] = rs_sys_getCookis_Power();
$tt = B_GetCurrentTime_usec();
		$res = json_decode(B_curl($InHeader, $aArg, $OutHeader), true);
if (bDebug) echo "*** B_curl tt=".sprintf("%.3f",B_GetCurrentTime_usec()-$tt)." <br>";

if (bDebug) echo "url_get_dymanic={$url_get_dymanic}?".htmlspecialchars(http_build_query($aArg))." <br>";
if (bDebug) echo "aArg:<br>".B_Array2String2($aArg)." <br><br>";
if (bDebug) echo "OutHeader:<br>".B_Array2String2($OutHeader)." <br><br>";

		if ($res) {
B_Log_f(FP_LOG, "2. res.recs=".count($res['recs']));
if (bDebug) echo "2. res.recs=".count($res['recs'])." <br>";
if (bDebug) echo "res:<br>".B_Array2String2($res)." <br><br>";
			
			$out_recs = $res['recs'];
		}
	}
	// 一般訊息 有 Cache 功能
	else
	{
if (bDebug && $_REQUEST["mm"] == "getRecs" && USER_ACN == "wheechen") {
	echo "<br><br> ***** Get Recs [".$_REQUEST["acn"]."] ***** <br><br>";
	$f = DIR_USERDB.$_REQUEST["acn"]."_dynamic2.rec";
	$recs = json_decode(B_LoadFile($f), true);
	echo "recs:<br>".B_Array2String2($recs)." <br>";
	return;
}
		
		$recs = json_decode(B_LoadFile(USER_FP_DYNAMIC2), true);
		if ($pt == 0)
		{
			// 檢查訊息讀取是否正確
			if (file_exists(USER_FP_DYNAMIC_INFO2)) {
				$fs = filesize(USER_FP_DYNAMIC_INFO2);
				$data = B_LoadFile(USER_FP_DYNAMIC_INFO2);
				$bErrInfo = strlen($data) != $fs;
				$info = B_Rec_Data2Rec($data);
if (bDebug) echo "bErrInfo=$bErrInfo, fs=$fs, data_fs=".strlen($data)." <br>";
if (bDebug) echo "info:<br>".B_Array2String2($info)." <br>";
B_Log_f(FP_LOG, "0. bErrInfo=$bErrInfo, fs=$fs, data_length=".strlen($data));
			}
			else {
				$bErrInfo = false;
				$info = array();
				$info['dynamic_all_update'] = 0;
			}
			$t_all_update = $info['dynamic_all_update'];
			$tCur = time();
			$tDiff = $tCur - $t_all_update;
			$allUpdate = $tDiff > 180; // Cache Time
if (bDebug) echo "tCur=$tCur, tDiff=$tDiff/180, allUpdate=$allUpdate <br><br>";
			// 版本不同清空
			if ($info['dynamic_ver'] != $dynamic_ver) {
				$info = array();
				$info['dynamic_ver'] = $dynamic_ver;
				$info['dynamic_last_time'] = 0;
				$info['dynamic_utime'] = 0;
				$info['dynamic_new_cnt'] = 0;
				//
				$recs = array();
				$bUserView = true;
				$allUpdate = true;
			}
			else if ($bUpdate || $allUpdate) {
				$info['dynamic_last_time'] = 0;
				//
				$recs = array();
				$allUpdate = true;
			}
			$new_cnt= (int)$info['dynamic_new_cnt'];
			$last_time = isset($info['dynamic_last_time']) && $info['dynamic_last_time'] > 1 ? (int)$info['dynamic_last_time']+1 : 0;
			$utime = isset($info['dynamic_utime']) ? (int)$info['dynamic_utime'] : 0;
B_Log_f(FP_LOG, "1. bErrInfo=$bErrInfo, new_cnt=$new_cnt, last_time=$last_time, utime=$utime");
if (bDebug) echo "1. bErrInfo=$bErrInfo, new_cnt=$new_cnt, last_time=$last_time, utime=$utime <br><br>";

// *****************************************
			
			$aArg = array();
			$aArg['ps'] = 500;
			if ($last_time > 0) $aArg['gt'] = $last_time;
			if (!empty($ds)) 	$aArg['ds'] = $ds;
			if (!empty($do)) 	$aArg['do'] = $do;
			//
			$InHeader['url'] = $url_get_dymanic; //."?".http_build_query($aArg);
			$InHeader['Cookie'] = rs_sys_getCookis_Power();
$tt = B_GetCurrentTime_usec();
			$res = json_decode(B_curl($InHeader, $aArg, $OutHeader), true);
$stt = sprintf("%.3f",B_GetCurrentTime_usec()-$tt);
if (bDebug) echo "*** B_curl tt=$stt <br><br>";
if (bDebug) echo "url_get_dymanic={$url_get_dymanic}?".htmlspecialchars(http_build_query($aArg))." <br><br>";
if (bDebug) echo "aArg:<br>".B_Array2String2($aArg)." <br>";
if (bDebug) echo "OutHeader:<br>".B_Array2String2($OutHeader)." <br>";
B_Log_f(FP_LOG, "DB Out => recs.length=".count($res['recs']).", stt=$stt");
if (bDebug) echo "DB Out => recs.length=".count($res['recs'])." <br><br>";
if (bDebug) echo "res:<br>".B_Array2String2($res)." <br><br>";
			if ($res) {
				
if (bDebug) echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br>";
				//
				if (count($res['recs']) > 0)
				{
// if (bDebug) echo "recs_0:<br>".B_Array2String2($res['recs'][0])." <br>";
					// 資料有變動, 重新計算
					$new_cnt = 0;
					// 將新的資料加進來
					if (count($recs) == 0)
					{
						$recs = $res['recs'];
					}
					else
					{
						$x = count($res['recs'])-1;
						for(; $x>=0; $x--)
						{
							$recCur = $res['recs'][$x];
							$OK = false;
							foreach($recs as $k => $rec) {
								$diff = $recCur['t_first'] - $rec['t_first'];
// if (bDebug) echo "diff=$diff, u_fp=".$recCur['u_fp']." <br>";
								if ($diff > 1800) { // 已經超過 30分鐘
									break;
								}
								// 同目錄及時間內加入
								if ($recCur['u_fp'] == $rec['u_fp']) {
									$recCur['files'] = array_merge($recCur['files'], $rec['files']);
									$recCur['t_last'] = $rec['t_last'];
									$recCur['cnt'] += $rec['cnt'];
									//
									unset($recs[$k]);
									break;
								}
							}
// if (bDebug) echo "OK=$OK <br>";
							if (!$OK) {
								array_unshift($recs, $recCur);
							}
						}
					}
					// 過濾及刪除
					$cnt = 0;
					$cntDir = 0;
					$list_file = array();
					$newRecs = array();
					foreach($recs as $nDir => $recDir) {
						$cntDir++;
						$recFs = $recDir['files'];
						$cntFs = count($recFs);
						$new_a = 0; $new_f = 0;
						$newRecsFs = array();
						foreach($recFs as $rec) {
							$cnt++;
							$type = $rec['type'];
							$bA = $type == "BBS" || $type == "Html";
							$mode = $rec['mode'];
							$url = $rec['url'];
							//
							if (preg_match("#\\.files|temp\\.html\$#", $rec['view_path'])) {
// if (bDebug) echo "~過濾掉的檔案 url=$url, vfp=".$rec['view_path']." <br>";
								continue;
							}
							// 過濾重複
							if (isset($list_file[$url])) {
if (bDebug) echo "~已經存在了 mode=$mode, url=$url, vfp=".$rec['view_path']." <br>";
								continue;
							}
							$list_file[$url] = "$cntDir/$cnt";
							
							// 計算新增了幾筆
							if ($rec['upload_time'] > $utime && $rec['owner'] != $user) {
								if ($bA)
									$new_a++;
								else
									$new_f++;
if (bDebug) echo "$cntDir/$cnt, 新增資料 type=$type, mode=$mode, $rec[upload_time] / $utime, view_path=".$rec['view_path']." <br>";
							}
							// BBS OR Html 把它切出來
							if ($bA && $cntFs > 1) {
								$recDirA = array(
									't_first' => strtotime($rec['upload_time'])
									,'t_last' => strtotime($rec['upload_time'])
									,'u_fp' => $recDir['u_fp']
									,'v_fp' => $recDir['v_fp']
									,'cnt' => 1
									,'files' => array($rec)
								);
								$newRecs[] = $recDirA;
							}
							else {
								$newRecsFs[] = $rec;
							}
							
						}
						if ($new_a > 0 || $new_f > 0) {
							if ($new_a > 0) $new_cnt += $new_a;
							if ($new_f > 0) $new_cnt += 1; // 同一個目錄算一筆
if (bDebug) echo "*** 新增資料 cntFs=$cntFs, new_cnt=$new_cnt,,, new_a=$new_a, new_f=$new_f <br>";
						}
						//
						if (count($newRecsFs) > 0) {
							$cntFs = count($newRecsFs);
							$recDir['t_first'] = strtotime($newRecsFs[0]['upload_time']);
							$recDir['t_last'] = strtotime($newRecsFs[$cntFs-1]['upload_time']);
							$recDir['cnt'] = $cntFs;
							$recDir['files'] = $newRecsFs;
							$newRecs[] = $recDir;
						}
					}
					$recs = $newRecs;
					unset($newRecs);
if (bDebug) echo "=== 新增資料 recs.length=".count($recs).", new_cnt=$new_cnt ================<br>";
					
					// 只保留 1000 筆
					if (count($recs) > 1000)
						$recs = array_slice($recs, 0, 1000);
					B_SaveFile(USER_FP_DYNAMIC2, json_encode($recs));
				}
				$rec0 = count($recs) > 0 ? $recs[0] : null;
B_Log_f(FP_LOG, "new_cnt=$new_cnt ");
if (bDebug) echo "new_cnt=$new_cnt <br>";
				// 記錄 offset
				if (!$bErrInfo 
					&& ($bUserView || (!empty($rec0) && $rec0['t_first'] != $info['dynamic_last_time']))
					|| $allUpdate)
				{
if (bDebug) echo "記錄 offset ~~~~~~~~~~~~~~~~~~~~~~~~~ <br>";
					if ($allUpdate) $info['dynamic_all_update'] = $tCur;
					$info['dynamic_last_time'] = $rec0['t_first'];
					$info['dynamic_new_cnt'] = $new_cnt;
					
					// 使用者觀看
					if ($bUserView || $utime == 0) {
						$info['dynamic_new_cnt'] = 0;
						// 修正 dynamic_utime 會變成 0
						if (count($recs) > 0) {
							$t = $rec0['files'][0]['upload_time'];
							if ($t > $utime) $info['dynamic_utime'] = $t;
						}
						if (empty($info['dynamic_utime'])) $info['dynamic_utime'] = $utime;
					}
					
B_Log_f(FP_LOG, "3. info=".B_Array2String2($info)." \n");
if (bDebug) echo "3 bUserView=$bUserView <br>info:<br>".B_Array2String2($info)." <br><br>";
					B_Rec_Rec2File(USER_FP_DYNAMIC_INFO2, $info, false);
				}
			}
			
			// 過期時間 手機用
			if ($expire > 0) {
				$recs2 = array();
				foreach($recs as $rec) {
					if ($rec['t_first'] > $expire)
						$recs2[] = $rec;
				}
				$recs = $recs2;
			}
			
			//
			if (is_array($recs)) {
				// 只取新的訊息
				if ($bGetNew) {
					//$new_cnt = 50; // Debug
					if ($new_cnt > 0)
						$out_recs = array_slice($recs, 0, min($new_cnt, 100));
					else
						$out_recs = array();
				}
				else {
					$out_recs = array_slice($recs, 0, $ps);
				}
			}
		}
		// 第二頁以後
		else
		{
			if (is_array($recs)) {
				$cnt = 0;
if (bDebug) echo "第二頁以後 recs.length=".count($recs)." pt=$pt <br>";
				foreach($recs as $rec) {
					if ($rec['t_first'] < $pt) {
						$cnt++;
if (bDebug) echo "第二頁以後 cnt=$cnt, t_first=".$rec['t_first'].", url=".$rec['u_fp']." <br>";
						$out_recs[] = $rec;
						if ($cnt >= $ps)
							break;
					}
				}
			}
		}
		
	}
	
if (bDebug) echo "*** End tt=".(B_GetCurrentTime_usec()-$tt)." <br><br><br><br><br><br><br>";
	//
	$out = array();
	if (isset($res) && isset($res['time'])) $out['time'] = $res['time'];
	$out['user'] 		= USER_ACN;
	$out['user_sun'] 	= USER_SUN;
	$out['user_mail'] 	= USER_MAIL;
	
	if ($bDefView) $out['cnt_new'] 	= $new_cnt;
	if (isset($t_all_update)) $out['t_update'] 	= $t_all_update;
	
	$out['recs'] 		= $out_recs;
if (bDebug) echo "out:<br>".B_Array2String2($out)." <br><br>";
	if (isset($_REQUEST["callback"]))
		print $_REQUEST["callback"]."(".json_encode($out).")";
	else
		print json_encode($out);
	
B_Log_f(FP_LOG2, "USER_ACN=".USER_ACN.", qtt=".sprintf("%.3f",B_GetCurrentTime_usec()-$qtt));
B_Log_f(FP_LOG2, "URI=".$_SERVER["REQUEST_URI"]);
}

function m_get_web_dynamic()
{
	ignore_user_abort(1);
	set_time_limit(0);

	define("USER_FP_WEB_DYNAMIC_INFO",	DIR_USERDB.USER_ACN."_web_dynamic_info.rec");
	define("USER_FP_WEB_DYNAMIC",		DIR_USERDB.USER_ACN."_web_dynamic.rec");
	define("FP_LOG",					"logs/".USER_ACN."_aui_get_web_dynamic.log");
	$dynamic_ver = "1.002";
if (bDebug) echo "USER_FP_WEB_DYNAMIC_INFO=".USER_FP_WEB_DYNAMIC_INFO." <br>";
if (bDebug) echo "USER_FP_WEB_DYNAMIC=".USER_FP_WEB_DYNAMIC." <br>";
	
	$act		= isset($_REQUEST["act"])		? $_REQUEST["act"]	: "";
	$p			= isset($_REQUEST["p"])			? $_REQUEST["p"]	: 1;
	$ps			= isset($_REQUEST["ps"])		? $_REQUEST["ps"]	: 5;
	$site_acn	= isset($_REQUEST["site_acn"])	? $_REQUEST["site_acn"]	: "";
	$test_acn 	= isset($_REQUEST["test_acn"]) 	? $_REQUEST["test_acn"] : "";
	
	$bUserView = $act == "user_view";
	$new_cnt = 0;
	if (!empty($test_acn) && USER_ACN == "wheechen")
	{
		$recs = json_decode(B_LoadFile(DIR_USERDB.$test_acn."_web_dynamic.rec"), true);
		//
		$info = B_Rec_File2Rec(USER_FP_WEB_DYNAMIC_INFO);
		$new_cnt = (int)$info['dynamic_new_cnt'];
	}
	else
	{
		$recs = json_decode(B_LoadFile(USER_FP_WEB_DYNAMIC), true);
		if ($p == 1)
		{
			$InHeader['Cookie'] = rs_sys_getCookis_Power();
			// 檢查訊息讀取是否正確
			if (file_exists(USER_FP_WEB_DYNAMIC_INFO)) {
				$fs = filesize(USER_FP_WEB_DYNAMIC_INFO);
				$data = B_LoadFile(USER_FP_WEB_DYNAMIC_INFO);
				$bErrInfo = strlen($data) != $fs;
				$info = B_Rec_Data2Rec($data);
if (bDebug) echo "bErrInfo=$bErrInfo, fs=$fs, data_fs=".strlen($data)." <br>";
if (bDebug) echo "info:<br>".B_Array2String2($info)." <br><br>";
B_Log_f(FP_LOG, "0. bErrInfo=$bErrInfo, fs=$fs, data_length=".strlen($data));
			}
			else {
				$bErrInfo = false;
				$info = array();
			}
			// 版本不同清空
			if (1 || $info['dynamic_ver'] != $dynamic_ver) {
				$info = array();
				$info['dynamic_ver'] = $dynamic_ver;
				$info['dynamic_offset'] = 0;
				$info['dynamic_utime'] = 0;
				$info['dynamic_new_cnt'] = 0;
				//
				$bUserView = true;
				$recs = array();
			}
			$new_cnt= (int)$info['dynamic_new_cnt'];
			$offset	= isset($info['dynamic_offset']) ? (int)$info['dynamic_offset'] : 0;
			$utime	= isset($info['dynamic_utime'])	 ? (int)$info['dynamic_utime']	: 0;
			
B_Log_f(FP_LOG, "1. bErrInfo=$bErrInfo, new_cnt=$new_cnt, offset=$offset, utime=$utime");
if (bDebug) echo "1. bErrInfo=$bErrInfo, new_cnt=$new_cnt, offset=$offset, utime=$utime <br>";
			
			$aArg = array();
			$aArg['mode'] 	 = "get"; // [get(朋友分享給我的 )/ put(我分享給朋友的)]
			$aArg['site_acn']= $site_acn;
			$aArg['p'] 		 = 1;
			$aArg['ps'] 	 = 300;
			if ($offset > 0) $aArg['of'] = $offset;
			$url = "http://localhost".API_GET_EVENT;
$tt = B_GetCurrentTime_usec();
			$res = json_decode(B_file_get_contents_post($url, $aArg, $OutHeader, $InHeader), true);
$stt = sprintf("%.3f",B_GetCurrentTime_usec()-$tt);
if (bDebug) echo "tt=$stt, url=$url?".htmlspecialchars(http_build_query($aArg))." <br>";
if (bDebug) echo "Cookie=".rs_sys_getCookis_Power()." <br>";
if (bDebug) echo "aArg:<br>".B_Array2String2($aArg)." <br><br>";
if (bDebug) echo "res:<br>".B_Array2String2($res)." <br><br>";
			if ($res) {
B_Log_f(FP_LOG, "2. res.recs=".count($res['recs']));
if (bDebug) echo "2. res.recs=".count($res['recs'])." <br>";
				//
				if (count($res['recs']) > 0)
				{
if (bDebug) echo "recs_0:<br>".B_Array2String2($res['recs'][0])." <br>";
					// 資料有變動, 重新計算
					$new_cnt = 0;
					// 將新的資料加進來
					if (count($recs) == 0)
					{
						$recs = $res['recs'];
					}
					else
					{
						$x = count($res['recs'])-1;
						for(; $x>=0; $x--)
						{
							$recCur = $res['recs'][$x];
							$OK = false;
							foreach($recs as $k => $rec) {
if (bDebug) echo "diff=".($recCur['t_first']-$rec['t_first']).", u_fp=".$recCur['u_fp']." <br>";
								if ($recCur['t_first'] - $rec['t_first'] > 1800) { // 已經超過 30分鐘
									break;
								}
								// 同目錄及時間內加入
								if ($recCur['u_fp'] == $rec['u_fp']) {
									$recs[$k]['files'] = array_merge($recCur['files'], $recs[$k]['files']);
									$recs[$k]['t_first'] = $recCur['t_first'];
									$recs[$k]['cnt'] += $recCur['cnt'];
									$OK = true;
									break;
								}
							}
if (bDebug) echo "OK=$OK <br>";
							if (!$OK) {
								array_unshift($recs, $recCur);
							}
						}
					}
					// 過濾及刪除
					$cnt = 0;
					$cntDir = 0;
					$list_del_dir = array();
					$list_del_file = array();
					$list_file = array();
					foreach($recs as $nDir => $recDir) {
						$cntDir++;
						$bUpd = false;
						$recFs = $recDir['files'];
						$recCnt = $recDir['cnt'];
						// 刪除 .files 目錄
						if (preg_match("#\\.files\$#", $recDir['v_fp'])) {
if (bDebug) echo "刪除 .files 目錄 v_fp=$recDir[v_fp] <br>";
							unset($recs[$nDir]);
							continue;
						}
						foreach($recFs as $n => $rec) {
							$cnt++;
if (bDebug) echo "$cntDir/$cnt, mode=".$rec['mode'].", type=".$rec['type'].", view_path=".$rec['view_path']." <br>";
							$bDir = $rec['type'] == "Directory";
							$mode = $rec['mode'];
							$url = $rec['url'];
							$fn = $rec['filename'];
							//
							if (preg_match("#\\.files\$|^temp\\.html\$#", $fn)
								|| $rec['allow'] == "ALLOW_NONE") {
								$bUpd = true;
								$recCnt--;
								unset($recFs[$n]);
								continue;
							}
							// 刪除 或 關閉 Record
							if ($mode == "del" || $mode == "unset") {
								if ($bDir) {
									if (substr($url,-1) != "/") $url .= "/";
									if (!isset($list_del_dir[$url])) {
										if (!preg_match("#\/Driver\/?$#", $url))	// 過濾掉 網路硬碟目錄 "xxx/Driver"
											$list_del_dir[$url] = "$cntDir/$cnt";
									}
								}
								else {
									if (!isset($list_del_file[$url]))
										$list_del_file[$url] = "$cntDir/$cnt";
								}
								//
								$bUpd = true;
								$recCnt--;
								unset($recFs[$n]);
								continue;
							}
							// 過濾在刪名單中的 Record
							if ($bDir) {
								if (substr($url,-1) != "/") $url .= "/";
							}
							else {
								// 在刪除檔案名單中
								if (isset($list_del_file[$url])) {
									$bUpd = true;
									$recCnt--;
									unset($recFs[$n]);
if (bDebug) echo "~在刪除檔案名單中 url=$url <br>";
									continue;
								}
							}
							// 在刪除目錄名單中
							foreach($list_del_dir as $del_dir => $v) {
								if (substr($url, 0, strlen($del_dir)) == $del_dir) {
									$bUpd = true;
									$recCnt--;
									unset($recFs[$n]);
if (bDebug) echo "~在刪除目錄名單中 url=$url <br>";
									$url = "";
									break;
								}
							}
							if (empty($url))
								continue; // 已經被刪除了
							
							// 過濾重複
							if (isset($list_file[$url])) {
									$bUpd = true;
									$recCnt--;
									unset($recFs[$n]);
if (bDebug) echo "已經存在了 url=$url <br>";
									continue;
							}
							else {
								$list_file[$url] = "$cntDir/$cnt";
							}
							// 計算新增了幾
							if ($rec['upload_time'] > $utime) {
								$new_cnt++;
B_Log_f(FP_LOG, "新資料 new_cnt=$new_cnt, ".$rec[$upload_time]."/$utime,  mode=".$rec['mode'].", type=".$rec['type'].", view_path=".$rec['view_path']);
if (bDebug) echo "$cntDir/$cnt, 新增資料 new_cnt=$new_cnt, $rec[upload_time]/$utime,  mode=".$rec['mode'].", type=".$rec['type'].", view_path=".$rec['view_path']." <br>";
							}
							
						}
						// 
						if ($bUpd) {
if (bDebug) echo "更新 Files, files=".count($recFs).", cnt=$recCnt <br>";
							$recsNew = array();
							foreach($recFs as $rec)
								$recsNew[] = $rec;
							$recs[$nDir]['files'] = $recsNew;
							$recs[$nDir]['cnt'] = $recCnt;
							//
							if (count($recsNew) == 0) {
if (bDebug) echo "*** 刪除目錄 v_fp=".$recs[$nDir]['v_fp'].", u_fp=".$recs[$nDir]['u_fp']." <br>";
								unset($recs[$nDir]);
								continue;
							}
						}
					}
					
if (bDebug) echo "list_del_dir:<br>".B_Array2String2($list_del_dir)." <br>";
if (bDebug) echo "recs_0:<br>".B_Array2String2($recs[0])." <br>";
					
					// 只保留 300 筆
					if (count($recs) > 300)
						$recs = array_slice($recs, 0, 300);
					B_SaveFile(USER_FP_WEB_DYNAMIC, json_encode($recs));
				}
				// 記錄 offset
				if (!$bErrInfo 
					&& ($bUserView || (isset($res['end_offset']) && $res['end_offset'] != $info['dynamic_offset'])) )
				{
					$info['dynamic_offset'] = $res['end_offset'];
					$info['dynamic_new_cnt'] = $new_cnt;
					
					// 使用者觀看
					if ($bUserView) {
						$new_cnt = 0;
						$info['dynamic_new_cnt'] = 0;
						// 修正 dynamic_utime 會變成 0
						if (count($recs) > 0)
							$info['dynamic_utime'] = $recs[0]['files'][0]['upload_time'];
						if (empty($info['dynamic_utime'])) $info['dynamic_utime'] = $utime;
					}
					
B_Log_f(FP_LOG, "3. info=".B_Array2String2($info)." \n");
if (bDebug) echo "3 bUserView=$bUserView <br>info:<br>".B_Array2String2($info)." <br><br>";
					B_Rec_Rec2File(USER_FP_WEB_DYNAMIC_INFO, $info, false);
				}
			}
		}
	}
	
	if (is_array($recs)) {
		$cnt = count($recs);
		if ($p < 1) $p = 1;
		$xS = ($p-1)*$ps;
		$p_recs = array_slice($recs, $xS, $ps);
	}
	//$recs = array();
	//
	$out = array();
	$out['cnt_new'] 	= $new_cnt;
	$out['cnt'] 		= count($recs);
	$out['recs'] 		= $p_recs;
if (bDebug) echo "out:<br>".B_Array2String2($out)." <br><br>";
	print json_encode($out);
}

function m_get_contacts()
{
	$ver = "002";
	$o			= isset($_REQUEST["o"]) 		? $_REQUEST["o"] 			: "name";
	$od 		= isset($_REQUEST["od"]) 		? $_REQUEST["od"] 			: "inc";
	
	$recInfo = B_Rec_File2Rec(FP_CONTACTS_INFO);
	$recs = B_Rec_File2Recs(FP_CONTACTS_DB);
	
	// Data Update
	if ($recInfo['ver'] != $ver)
	{
		// 過濾有問題的資料
		$recInfo['ver'] = $ver;
		foreach($recs as $key => $rec) {
			if ($rec['name'] == "undefined" || strpos($rec['email'],",") !== false)
				unset($recs[$key]);
			if (strpos($rec['name'],"@") !== false)
				list($recs[$key]['name']) = explode('@', $rec['name'], 2);
		}
		// 
		$src = "";
		if (B_tag_is_exists($recInfo['src'], "gmail")) $src = B_tag_add($src, "gmail");
		if (B_tag_is_exists($recInfo['src'], "yahoo")) $src = B_tag_add($src, "yahoo");
		$recInfo['src'] = $src;
		//
		B_Rec_Rec2File(FP_CONTACTS_INFO, $recInfo);
		B_Rec_Recs2File(FP_CONTACTS_DB, $recs);
	}
	
	$cnt = count($recs);
	if ($cnt > 0) {
		if (!isset($recs[0][$o])) $o = "name";
		cmp_o_od($recs, $o, $od);
	}
	$recInfo['is_numail_install'] = rs_is_NUMail_Install();
	$recInfo['cnt'] = $cnt;
	$recInfo['recs'] = $recs;
	print json_encode($recInfo);
}
function m_set_contacts()
{
	$new_s_recs	= isset($_REQUEST["recs"]) 		? $_REQUEST["recs"] 		: ""; // [name, mail]
	$src		= isset($_REQUEST["src"]) 		? $_REQUEST["src"] 			: "";
	
	$recs = B_Rec_File2Recs(FP_CONTACTS_DB, "email");
	$new_recs = B_Rec_Data2Recs($new_s_recs);
	$bAdd = 0;
	foreach($new_recs as $rec) {
		$mail = $rec['email'];
		if (empty($mail) 
			|| strpos($mail,"@") === false
			|| strpos($mail,",") !== false
			|| isset($recs[$mail])
			) continue;
		
		if (empty($rec['name']) || $rec['name'] == "undefined")
			list($rec['name']) = explode('@', $rec['email'], 2);
		$recs[$rec['email']] = $rec;
		$bAdd++;
	}
	if ($bAdd > 0) B_Rec_Recs2File(FP_CONTACTS_DB, $recs);
	//
	if (!empty($src)) {
		$src = strtolower(trim($src));
		$recInfo = B_Rec_File2Rec(FP_CONTACTS_INFO);
		if (!B_tag_is_exists($recInfo['src'], $src)) {
			$recInfo['src'] = B_tag_add($recInfo['src'], $src);
			B_Rec_Rec2File(FP_CONTACTS_INFO, $recInfo);
		}
	}
	print json_encode(array('cnt_add' => $bAdd, 'cnt' => count($recs)));
}



function m_search_site()
{
	global $lang, $set_conf, $reg_conf, $login_user, $group_mode;
if (bDebug) echo "group_mode=$group_mode <br>";

	$q		= isset($_REQUEST["q"])		? $_REQUEST["q"]	: "";
	$type	= isset($_REQUEST["type"])	? $_REQUEST["type"]	: null;
	$p		= isset($_REQUEST["p"])		? $_REQUEST["p"]	: 1;
	$ps		= isset($_REQUEST["ps"])	? $_REQUEST["ps"]	: 10;
	
	// 分散式 Server
	$b_group_server = $group_mode == GROUP_SERVER || $group_mode == GROUP_CLIENT;
	// 分散式 Server - 這台是 Client
	if ($group_mode == GROUP_CLIENT)
	{
		$gcs_ip = rs_getHostIPByACN($set_conf["group_server"]);
		$url = "http://".$gcs_ip.$_SERVER["REQUEST_URI"];
		$InHeader['url'] = $url;
		$InHeader['Cookie'] = rs_sys_getCookis_Power();
		//$data = B_curl($InHeader, null, $OutHeader);
		$info = rs_cache_get_url($url, 30, $InHeader);
		$data = $info['data'];
		
if (bDebug) echo "InHeader:<br>".B_Array2String2($InHeader)." <br><br>";
if (bDebug) echo "OutHeader:<br>".B_Array2String2($OutHeader)." <br><br>";
if (bDebug) echo "res:<br>".B_Array2String2(json_decode($data, true))." <br><br>";
		print $data;
		exit;
	}
	
	$arg = array();
	$arg['q'] = $q;
	$arg['type'] = $type;
	$arg['user'] = USER_ACN;
	$out_recs = rs_search_site($arg);
	$cnt = count($out_recs);
	if ($p < 1) $p = 1;
	$StartID = ($p-1)*$ps;
	$out_recs = array_slice($out_recs, $StartID, $ps);
	
	$out = array();
	$out['cnt'] = $cnt;
	$out['recs']= $out_recs;
if (bDebug) echo "out:<br>".B_Array2String2($out)." <br>";
	print json_encode($out);
}


///////////////////////////////////////////////////////////////////////////////
function m_sync($sMode)
{
	$site_name	= isset($_REQUEST["site_name"])	? $_REQUEST["site_name"]: "";
	if (empty($site_name))	die_json("Error: empty site_name.");

	$site = "Site";
	rs_init($site, $site_name);
	rs_power_init();
	
if (bDebug)
	echo "USER_ACN=".USER_ACN.", USER_SSN=".USER_SSN.", USER_SUN=".USER_SUN.", USER_MAIL=".USER_MAIL.", <br>";

	// 必須是 網站管理者
	if (!POWER_Admin)
		B_Error(403);

	sync_init($site_name);
	
	switch($sMode) {
		case "sync_get_list_update":
			m_sync_get_list_update();
			break;
		case "sync_get_info":
			m_sync_get_info();
			break;
		case "sync_get_list":
			m_sync_get_list();
			break;
		case "sync_get_list_rec":
			m_sync_get_list_rec();
			break;
		case "sync_set_list":
			m_sync_set_list();
			break;
		case "sync_set_list_local":
			require_once(API_PATH_init);
			m_sync_set_list_local();
			break;
		case "sync_back_upload_filelist":
			m_sync_back_upload_filelist();
			break;
		case "sync_back_get_filelist":
			m_sync_back_get_filelist();
			break;
			
		default:
			die_json("Error: Mode does not exist.($sMode)");
			break;
	}
}

function m_sync_get_info()
{
	$info = B_Rec_File2Rec(SYNC_FP_INFO);
	// 必須在 24 小時內有做過同步，才有同步功能
	$info['bStart'] = !empty($info['tNUSync']) && $info['tNUSync'] > time()-3600*24;
	//
	if ($info['bStart']) {
		$recs = B_Rec_File2Recs(SYNC_FP_LIST, 'fp');
		
// *** Init 
		$t = strtotime("2013/07/31 00:00:01");
		if (1 || $t != $info['tDataUpdate'])
		{
			$upd = false;
			foreach($recs as $k => $rec) {
				if (!isset($rec['SP'])) {
					unset($recs['SP']);
					$upd = true;
				}
			}
			if ($upd) {
				B_Rec_Recs2File(SYNC_FP_LIST, $recs, false);
			}
			
			
			$info['tDataUpdate'] = $t;
			B_Rec_Rec2File(SYNC_FP_INFO, $info, false);
		}
// *** End		
		
	} else {
		$recs = array();
	}
	
	print json_encode(array(
				'info' => $info
				,'list' => $recs
			));
}
function m_sync_get_list()
{
	$nusync		= isset($_REQUEST["nusync"])	? $_REQUEST["nusync"] == "y" 	: "";
	
	//
	$recs = B_Rec_File2Recs(SYNC_FP_LIST, 'fp');
	// NUBraim 做 sync 
	if ($nusync)
	{
		$info = B_Rec_File2Rec(SYNC_FP_INFO);
		$info['tNUSync'] = time();
		B_Rec_Rec2File(SYNC_FP_INFO, $info, false);
		//
		$recs_del = B_Rec_File2Recs(SYNC_FP_LIST_DEL, 'fp');
		// 
		$cnt_upd = 0;
		foreach($recs as $k => $rec) {
			// 檢查是否有 key
			if (!isset($rec['key'])) {
				$cnt_upd++;
				$recs[$k]['key'] = sync_list_get_only_key($recs, $rec);
			}
			// 備份目錄：檢查是否有 上傳 今日的 FileList Log
			if ($rec['SM'] == "up") {
				$cnt_upd++;
				$fp_log = sync_con_key2back_log_fp($rec['key']);
				$recs[$k]['b_back_log_exists'] = file_exists($fp_log) ? "y" : "n";
			}
			// 目錄不存在了
			$bExists = file_exists(PATH_WebPages."/".$k);
if (bDebug) echo "bExists=$bExists, fp=$k, fn=".$rec['fn']." <br>";
			if (!$bExists) {
				continue;
			}
			// 檔名有變動
			$name = get_file_name(PATH_WebPages, $rec['fp']);
			if ($name != $rec['LF']) {
if (bDebug) echo "檔名有變動 name=$name <br>";
				$cnt_upd++;
				$oldName = $rec['LF'];
				$recs[$k]['fn'] = $name;
				$recs[$k]['LF'] = $name;
				$recs[$k]['LP'] = str_replace($oldName, $name, $rec['LP']);
				$recs[$k]['SF'] = get_view_path(PATH_WebPages."/".$k);
			}
		}
		if ($cnt_upd > 0) {
			B_Rec_Recs2File(SYNC_FP_LIST, $recs, false);
		}
		
		$out = array(
			'cnt' => count($recs)
			,'recs' => $recs
			,'recs_del' => $recs_del
		);
	}
	else
	{
		$out = array(
			'cnt' => count($recs)
			,'recs' => $recs
		);
	}
	
	if (bDebug) print B_Array2String($out)."<br><br>";
	
	print json_encode($out);
}
function m_sync_get_list_rec()
{
/*
	$f_list = aui_f_sync_list($site_name);
	$recs = B_Rec_File2Recs($f_list);
	
	if (count($recs) > 0) {
		$aUrl = array();
		foreach($recs as $rec) {
			$aUrl[] = "/Site/".$rec['fp'];
		}
		$select_arg = (count($aUrl) > 0 ? "-tag \"@url:".implode(",", $aUrl)."\"" : "");
		//
		$DBPath = WEB_ROOT_PATH."/Site/".SITE_NAME."/.nuweb_index/current";
		$cmd = "\"".API_DBMAN."\" -recbeg \"@\\n@GAIS_Rec:\" -avesize 2000 -flag \"@_f:Normal\" $select_arg -sort -key \"@time:\" ".$DBPath;
		$data = shell_exec($cmd);

		$data = str_replace("@\n@\n", "@\n", $data);
		$cnt = rec_GetTotal($data);
		$recs = B_Rec_Data2Recs($data);
	}
	else {
		$cnt = 0;
		$recs = array();
	}
	cmp_o_od(&$recs, "filename", "inc");
	
	$out = array();
	$out["cnt"] = $cnt;
	$out["recs"]= $recs;
	print json_encode($out);
*/
	print json_encode(array());
}
function m_sync_get_list_update()
{
	$tCur = B_GetCurrentTime_usec();

	$p			= isset($_REQUEST["p"])			? $_REQUEST["p"]		: 1;
	$ps			= isset($_REQUEST["ps"])		? $_REQUEST["ps"]		: 10;
	
	$info = B_Rec_File2Rec(SYNC_FP_INFO);
	$recs = B_Rec_File2Recs(SYNC_FP_LIST);
	if (count($recs) > 0) {
		$aUrl = array();
		foreach($recs as $rec) {
			$aUrl[] = "@url:/Site/".$rec['fp'];
		}
		$select_arg = (count($aUrl) > 0 ? "-select \"".implode(";", $aUrl)."\"" : "");
		//
		if ($p < 1) $p = 1;
		if ($ps < 3) $ps = 3;
		$start = ($p-1)*$ps+1;
		$end = $start+$ps-1;
		$l_arg = "-L $start-$end";
		//
		$DBPath = WEB_ROOT_PATH."/Site/".SITE_NAME."/.nuweb_index/current";
		$cmd = "\"".API_DBMAN."\" -recbeg \"@\\n@GAIS_Rec:\" -avesize 2000 $select_arg -sort -key \"@mtime:\" -reverse $l_arg ".$DBPath;
		$data = shell_exec($cmd);

		$data = str_replace("@\n@\n", "@\n", $data);
		$cnt = rec_GetTotal($data);
		$recs = B_Rec_Data2Recs($data);
		
		// foreach($recs as $rec) {
			// echo $rec['mtime']." ".$rec['url']." <br>";
		// }
		
	}
	else {
		$cnt = 0;
		$recs = array();
	}
if (bDebug) {
	echo "cmd$cmd <br>";
	echo "t=".sprintf("%.3f", B_GetCurrentTime_usec()-$tCur)." <br>"
		."recs:<br>".B_Array2String2($recs)." <br><br>";
}
	
	$out = array();
	$out["tList"]	= $info['tList'];
	$out["cnt"] 	= $cnt;
	$out["recs"]	= $recs;
	print json_encode($out);
}
function m_sync_set_list()
{
	$act		= isset($_REQUEST["act"])		? $_REQUEST["act"]		: "";
	$fp 		= isset($_REQUEST["fp"])		? $_REQUEST["fp"]		: "";
	$fn 		= isset($_REQUEST["fn"])		? $_REQUEST["fn"]		: "";
	$old_fp 	= isset($_REQUEST["old_fp"])	? $_REQUEST["old_fp"]	: "";
	if (empty($act))		die_json("Error: empty act.");
	if (empty($fp))			die_json("Error: empty fp.");
	
	$info = B_Rec_File2Rec(SYNC_FP_INFO);
	$recs = B_Rec_File2Recs(SYNC_FP_LIST, 'fp');
	$bUpdate = false;
	if ($act == "add")
	{
		if (empty($fn))			die_json("Error: empty fn.");
		if (isset($recs[$fp]))	die_json("FilePath already exists.");
		
		foreach($recs as $rec) {
			if ($fn == $rec['fn'] && substr($rec['fn'],0,7) == "NUSync/") {
				die_json("Error: fn already exists.");
			}
		}
		$t = B_GetCurrentTime();
		$recs[] = array('fp' => $fp
						,'fn' => $fn
						,'LP' => "NUSync/".$fn
						,'LF' => $fn
						,'SP' => SERVER_ACN."/Site/".$fp
						,'SF' => get_view_path(PATH_WebPages."/".$fp)
						,'tc' => $t
						,'tm' => $t
						);
		// 刪除記錄檔
		sync_list_del2del($fp);
		
		$bUpdate = true;
	}
	//
	else if ($act == "upd")
	{
		if (!isset($recs[$old_fp])) die_json("Error: Old FilePath does not exist.");
		
		// Rename
		if ($old_fp == $fp)
		{
			if (empty($fn))				die_json("Error: empty fn.");
			$recs[$old_fp]['fn'] = $fn;
			$recs[$old_fp]['SF'] = get_view_path(PATH_WebPages."/".$fp);
		}
		// move
		else
		{
			$recs[$old_fp]['fp'] = $fp;
			$recs[$old_fp]['SP'] = SERVER_ACN."/Site/".$fp;
			$recs[$old_fp]['SF'] = get_view_path(PATH_WebPages."/".$fp);
		}
		$recs[$old_fp]['tm'] = B_GetCurrentTime();
		$bUpdate = true;
	}
	else if ($act == "del")
	{
		if (!isset($recs[$fp])) die_json("Error: FilePath does not exist.");
		
		$bUpdate = true;
		$rec = $recs[$fp];
		unset($recs[$fp]);
		// add 刪除記錄檔
		sync_list_del2add($rec);
	}
	// Save
	if ($bUpdate) {
		//
		B_Rec_Recs2File(SYNC_FP_LIST, $recs, false);
		//
		$info['tList'] = time();
		B_Rec_Rec2File(SYNC_FP_INFO, $info, false);
	}
	return_json("ok");
}
// @fp:file_path
// @fn:file_path
// @LP:NUSync/xxx
// @LF:xxx
// @SM:	[up(上傳)/down(下載)/to(上傳+Del)/from(下載+Del)/union(雙向)/with(雙向+Del)]
// @SP:ookon_test001/Site/wheechen
// @SF:WheeChen - Test001
// @tm:1375232106
function m_sync_set_list_local()
{
	$act		= isset($_REQUEST["act"])		? $_REQUEST["act"]				: "";
	$LP 		= isset($_REQUEST["LP"])		? stripslashes($_REQUEST["LP"])	: "";
	$LF 		= isset($_REQUEST["LF"])		? stripslashes($_REQUEST["LF"])	: "";
	$SM 		= isset($_REQUEST["SM"])		? stripslashes($_REQUEST["SM"])	: "";
	$oldLP 		= isset($_REQUEST["oldLP"])		? stripslashes($_REQUEST["oldLP"]):"";	// upd
	$rewrite 	= isset($_REQUEST["rewrite"])	? $_REQUEST["rewrite"]=="y"		: false;// add
	
	$info = B_Rec_File2Rec(SYNC_FP_INFO);
	$recs = B_Rec_File2Recs(SYNC_FP_LIST, 'LP');
	
	$fn = $LF;
	$path = SITE_NAME."/Driver/";
	// return => [fp => fn,...]
	$filelist = get_dir_file_list(PATH_WebPages."/".$path); // publib_lib
	$bUpdate = false;
	if ($act == "add")
	{
		if (empty($LP))			die_json("Error: empty LP.");
		if (isset($recs[$LP]))	die_json("Error: LocalPath already exists.");
			
		$new_fp = "";
		foreach($filelist as $k => $v) {
			if ($fn == $v) {
				$new_fp = $k;
				break;
			}
		}
		if (!empty($new_fp)) {
			if (!$rewrite)
				die_json("Error: Name already exists.");
			
			$dir_type = rs_dir_getType($new_fp);
			if (!rs_dir_is_share($dir_type))
				die_json("Error: Directory already exists, but not the general catalog.");
				
		} else {
			// 建立目錄
			$page_dir = PATH_WebPages."/";
			//$path = SYS_SiteName."/Driver/";
			$name = $fn;
			$dir_type = "";
			$real_dir_name = "";
			$new_fp = new_dir($page_dir, $path, $name, $dir_type, $real_dir_name);
		}
		//
		$bUpdate = true;
		$t = B_GetCurrentTime();
		$recs[$LP] = array('fp' => $new_fp
						,'fn' => $fn
						,'LP' => $LP
						,'LF' => $LF
						,'SM' => $SM
						,'SP' => SERVER_ACN."/Site/".$new_fp
						,'SF' => get_view_path(PATH_WebPages."/".$new_fp)
						,'tc' => $t
						,'tm' => $t
						);
		// 刪除記錄檔
		sync_list_del2del($new_fp);
	}
	else if ($act == "del")
	{
		if (empty($LP))			die_json("Error: empty LP.");
		if (!isset($recs[$LP]))	die_json("LocalPath not found.");
		
		$bUpdate = true;
		$rec = $recs[$LP];
		unset($recs[$LP]);
		// 刪除記錄檔
		sync_list_del2add($rec);
	}
	else if ($act == "upd")
	{
		if (empty($LP))		die_json("Error: empty LP.");
		if (empty($oldLP))	die_json("Error: empty oldLP.");
		
		if ($LP != $oldLP) {
			if (isset($recs[$LP]))
				die_json("Error: LocalPath already exists.");
			if (!isset($recs[$oldLP]))
				die_json("Error: Old LocalPath not found.");
			foreach($recs as $rec) {
				if ($LF == $rec['LF']) {
					die_json("Error: Name already exists.");
				}
			}
			// 
			$rec = $recs[$oldLP];
			if ($LF != $rec['fn']) {
				// Rename
				$page_dir = PATH_WebPages."/";
				$file_path = $rec['fp'];
				$name = $LF;
				rename_page($page_dir, $file_path, $name);
				
				
				$rec['fn'] = $LF;
				$rec['SF'] = get_view_path(PATH_WebPages."/".$file_path);
			}
			//
			$rec['LP'] = $LP;
			$rec['LF'] = $LF;
			$rec['tm'] = B_GetCurrentTime();
			$recs[$oldLP] = $rec;
			$bUpdate = true;
		}
	}
	
	if ($bUpdate) {
		//
		B_Rec_Recs2File(SYNC_FP_LIST, $recs, false);
		//
		$info['tList'] = time();
		B_Rec_Rec2File(SYNC_FP_INFO, $info, false);
	}
	return_json("ok");
}




function m_ooki($sMode)
{
	switch($sMode) {
		case "ooki_temp_save":
			m_ooki_temp_save();
			break;
		case "ooki_temp_get":
			m_ooki_temp_get();
			break;
		case "ooki_temp_del":
			m_ooki_temp_del();
			break;
		case "ooki_temp_2last":
			m_ooki_temp_2last();
			break;
			
		case "ooki_last_save":
			m_ooki_last_save();
			break;
		case "ooki_last_get":
			m_ooki_last_get();
			break;
		case "ooki_last_list":
			m_ooki_last_list();
			break;
			
		default:
			die_json("Error: Mode does not exist.($sMode)");
			break;
	}
}
// rec => [md5, t,  k, T, D, C]
function m_ooki_temp_save()
{
	ignore_user_abort(1);
	set_time_limit(0);

	$type 	= isset($_REQUEST["type"]) 	? $_REQUEST["type"] 			: "";
	$k 		= isset($_REQUEST["k"]) 	? trim($_REQUEST["k"])			: "";
	$T 		= isset($_REQUEST["T"]) 	? stripslashes($_REQUEST["T"]) 	: "";
	$D 		= isset($_REQUEST["D"]) 	? stripslashes($_REQUEST["D"]) 	: "";
	$C 		= isset($_REQUEST["C"]) 	? stripslashes($_REQUEST["C"]) 	: "";
	$Y 		= isset($_REQUEST["Y"]) 	? $_REQUEST["Y"] 				: "";	// type => [ tog(編輯器切換) ]
	if (empty($k)) die_json("Error: empty k.");
	if (empty($C)) die_json("Error: empty C.");
	
	$md5 = md5($k);
	$f = aui_f_ooki_temp_db();
	$recs = B_Rec_File2Recs($f, "md5");
	if (isset($recs[$md5])) {
		$recs[$md5]['t'] = B_GetCurrentTime();
		$recs[$md5]['type'] = $type;
		$recs[$md5]['T'] = $T;
		$recs[$md5]['D'] = $D;
		$recs[$md5]['C'] = $C;
		$recs[$md5]['Y'] = $Y;
	} else {
		$recs[] = array(
			'md5' 	=> $md5,
			't' 	=> B_GetCurrentTime(),
			'type' 	=> $type,
			'k' 	=> $k,
			'T'		=> $T,
			'D'		=> $D,
			'C'		=> $C,
			'Y'		=> $Y
		);
	}
	// 只保留 80 筆
	if (count($recs) > 120) {
		cmp_o_od($recs, 't', 'dec');
		$recs = array_slice($recs, 0, 100);
	}
	//
	B_Rec_Recs2File($f, $recs, false);
	return_json("ok");
}
function m_ooki_temp_get()
{
	$k = isset($_REQUEST["k"]) ? trim($_REQUEST["k"]) : "";
	if (empty($k)) die_json("Error: empty k.");
	
	$md5 = md5($k);
	$f = aui_f_ooki_temp_db();
	$recs = B_Rec_File2Recs($f, "md5");
	if (isset($recs[$md5]))
		return_json($recs[$md5]);
	else
		return_json(array());
}
function m_ooki_temp_del()
{
	ignore_user_abort(1);
	set_time_limit(0);

	$k = isset($_REQUEST["k"]) ? trim($_REQUEST["k"]) : "";
	if (empty($k)) die_json("Error: empty k.");
	
	$md5 = md5($k);
	$f = aui_f_ooki_temp_db();
	$recs = B_Rec_File2Recs($f, "md5");
	if (isset($recs[$md5])) {
		unset($recs[$md5]);
		B_Rec_Recs2File($f, $recs, false);
	}
	return_json("ok");
}
// 臨時文章 轉到 文章區塊
function m_ooki_temp_2last()
{
	ignore_user_abort(1);
	set_time_limit(0);

	$k 		= isset($_REQUEST["k"]) 	? trim($_REQUEST["k"])		: "";
	$type	= isset($_REQUEST["type"])	? trim($_REQUEST["type"])	: "";
	if (empty($k)) die_json("Error: empty k.");
	
	$rec = false;
	// del temp
	$md5 = md5($k);
	$f = aui_f_ooki_temp_db();
	$recs = B_Rec_File2Recs($f, "md5");
	if (isset($recs[$md5])) {
		$rec = $recs[$md5];
		unset($recs[$md5]);
		B_Rec_Recs2File($f, $recs, false);
	}
	// add last
	if ($rec !== false) {
		$f = aui_f_ooki_last_db();
		$recs = B_Rec_File2Recs($f);
		$recs[] = array(
			't' 	=> B_GetCurrentTime(),
			'type' 	=> $type,
			'T' 	=> $rec['T'],
			'D'		=> $rec['D'],
			'C'		=> $rec['C']
		);
		// 只保留 200 筆
		if (count($recs) > 250) {
			cmp_o_od($recs, 't', 'dec');
			$recs = array_slice($recs, 0, 200);
		}
		//
		B_Rec_Recs2File($f, $recs, false);
	}
	
	return_json("ok");
}

// rec => {t[id], T, D, C}
function m_ooki_last_save()
{
	ignore_user_abort(1);
	set_time_limit(0);

	$type 	= isset($_REQUEST["type"]) 	? trim($_REQUEST["type"]) 				: "";
	$T 		= isset($_REQUEST["T"]) 	? trim(stripslashes($_REQUEST["T"])) 	: "";
	$D 		= isset($_REQUEST["D"]) 	? trim(stripslashes($_REQUEST["D"])) 	: "";
	$C		= isset($_REQUEST["C"]) 	? trim(stripslashes($_REQUEST["C"])) 	: "";
	if (empty($C)) die_json("Error: empty C.");
	
	$f = aui_f_ooki_last_db();
	$recs = B_Rec_File2Recs($f);
	$recs[] = array(
		't' 	=> B_GetCurrentTime(),
		'type' 	=> $type,
		'T' 	=> $T,
		'D'		=> $D,
		'C'		=> $C
	);
	// 只保留 200 筆
	if (count($recs) > 250) {
		cmp_o_od($recs, 't', 'dec');
		$recs = array_slice($recs, 0, 200);
	}
	//
	B_Rec_Recs2File($f, $recs, false);
	
	return_json("ok");
}
function m_ooki_last_get()
{
	$t = isset($_REQUEST["t"]) ? trim($_REQUEST["t"]) : "";
	if (empty($t)) die_json("Error: empty t.");
	
	$f = aui_f_ooki_last_db();
	$recs = B_Rec_File2Recs($f, 't');
	if (isset($recs[$t]))
		print json_encode($recs[$t]);
	else
		print json_encode(array("error" => "t => ".$t));
}
function m_ooki_last_list()
{
	$type 	= isset($_REQUEST["type"]) 	? trim($_REQUEST["type"]) 	: "";
	
	$f = aui_f_ooki_last_db();
	if (empty($type)) {
		$recs = B_Rec_File2Recs($f);
	} else {
		$rows = B_Rec_File2Recs($f);
		$recs = array();
		foreach($rows as $rec) {
			if ($rec['type'] == $type)
				$recs[] = $rec;
		}
	}
	cmp_o_od($recs, 't', 'dec');
	return_json($recs);
}



function aui_f_ooki_temp_db()
{
	return DIR_USERDB.USER_ACN."_ooki_temp_db.rec";
}
function aui_f_ooki_last_db()
{
	return DIR_USERDB.USER_ACN."_ooki_last_db.rec";
}



function sync_init($site_name)
{
	define("SITE_NAME", $site_name);
	define("SYNC_FP_INFO", 		DIR_SITE.$site_name."/.nuweb_db/sync_info.rec");
	define("SYNC_FP_LIST", 		DIR_SITE.$site_name."/.nuweb_db/sync_list.rec");
	define("SYNC_FP_LIST_DEL", 	DIR_SITE.$site_name."/.nuweb_db/sync_list_del.rec");
	
	// 建立 user file list log 目錄
	$dir = DIR_SITE.SITE_NAME."/.nuweb_db/sync_back_".USER_ACN;
	if (!file_exists($dir))
		mkdir($dir);
	$dir .= "/";
	define("SYNC_BACK_DIR", 	$dir);	
}
// 刪除記錄檔
function sync_list_del2add($rec)
{
	if (substr($rec['LP'],0,7) == "NUSync/") {
		$fp = $rec['fp'];
		$rec['tm'] = B_GetCurrentTime();
		
		$recs = B_Rec_File2Recs(SYNC_FP_LIST_DEL, 'fp');
		$recs[$fp] = $rec;
		B_Rec_Recs2File(SYNC_FP_LIST_DEL, $recs, false);
	}
}
function sync_list_del2del($fp)
{
	$recs = B_Rec_File2Recs(SYNC_FP_LIST_DEL, 'fp');
	if (isset($recs[$fp])) {
		unset($recs[$fp]);
		B_Rec_Recs2File(SYNC_FP_LIST_DEL, $recs, false);
	}
}
function sync_list_get_only_key($recs, $rec)
{
	$key = $rec['tc'];
	if (B_obj_indexOf($recs, 'key', $key) === false) {
		return $key;
	}
	else {
		$i = 1;
		while (B_obj_indexOf($recs, 'key', $key."_".$i) !== false)
			$i++;
		return $key."_".$i;
	}
}
function sync_con_key2back_log_fp($key)
{
	return SYNC_BACK_DIR.$key.date("_Ymd").".filelist";
}

function m_sync_back_upload_filelist()
{
	$key		= isset($_REQUEST["key"]) 		? stripslashes($_REQUEST["key"])		: "";
	$filelist	= isset($_REQUEST["filelist"])	? stripslashes($_REQUEST["filelist"])	: "";
	
	if (empty($key))		json_die_err("Empty key");
	if (empty($filelist))	json_die_err("Empty filelist");
	
	$f = sync_con_key2back_log_fp($key);
	if (file_exists($f))
		json_die_err("File already exists");
	
	if (!B_SaveFile($f, $filelist))
		json_die_err("Write failure");
	
	return_json("ok");
}
function m_sync_back_get_filelist()
{
	$key	= isset($_REQUEST["key"]) ? stripslashes($_REQUEST["key"]) : "";
	$act	= isset($_REQUEST["act"]) ? stripslashes($_REQUEST["act"]) : "";
	if (empty($key)) json_die_err("Empty key");

	$list = array();
	$key .= "_";
	$lkey = strlen($key);
	$handle=opendir(SYNC_BACK_DIR); 
	while ($fn = readdir($handle)) {
		if ($fn == "." ) continue;
		if ($fn == ".." ) continue;
		if (substr($fn,0,$lkey) == $key)
			$list[] = $fn;
	}
	closedir($handle);
	
	if (count($list) > 0) {
		rsort($list);
		$last_file = $list[0];
	}
	
	
	$out = array();
	$out['last_file'] = $last_file;
	if ($act == "getData" && !empty($last_file))
		$out['last_filedata'] = B_LoadFile(SYNC_BACK_DIR.$last_file);
	print json_encode($out);
}



?>