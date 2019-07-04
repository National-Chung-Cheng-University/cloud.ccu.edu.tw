<?php
/*

***	Old Code ***
	真對 Video  第一次轉檔做處理
	*** "/data/NUWeb_Site/bin/video2flv.php" ***
		#160
			require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
			odb_api_viseo_video2flv($file_full_path);
	
	
	真對 Video  第二次轉檔做處理
	*** "/data/HTTPD/htdocs/API/video_lib.php" ***
		#852
			require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
		#833, #896
			odb_api_video_video_lib($video_file, $target_file);
******		
		
	
	檔案上傳
	*** "/data/HTTPD/htdocs/Site_Prog/API/upload_file.php" ***
	*** "/data/HTTPD/htdocs/API/edit_api_lib_p.php.quota" ***
		#227
			$read_file = $page_dir.$file;
			if (!odb_api_upload_file_chk_exists($read_file))
			{
				...
			}
		#262
			odb_api_upload_file($read_file);
	
	轉換完成
	*** "/data/HTTPD/htdocs/API/video_lib.php"
		// whee ODB 轉換完成 call odb check => convert_video_src(...)
		if (!function_exists("odb_api_video_notice_ok"))
			require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
		odb_api_video_notice_img_ok($video_file);
			add_convert_list("odb", $video_file); - 29
			add_convert_list($video_file, "odb"); - 28
		
		
		// whee ODB 通知已完成 => exec_first_convert_list(...)
		- 29
			case "odb" : 
				if (!function_exists("odb_api_video_notice_ok"))
					require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
				odb_api_video_notice_ok($video_file, $target_file);
				break;
		- 28
			if ($type == "odb") {
				if (!function_exists("odb_api_video_notice_ok"))
					require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
				odb_api_video_notice_ok($video_file);
			}
		
		
	檔案顯示
	*** "/data/HTTPD/htdocs/tools/page/show_page.php" ***
	#246
		$bDownload = $mode == "download";
		if (odb_api_print_show_page($page_path, $bDownload)){
			exit;
		}
	
	*** /data/HTTPD/htdocs/tools/public_lib.php ***
	#2603
		if (!function_exists("odb_api_upload_file_image"))
			require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
		odb_api_upload_file_image($img_file);
	#7234
		if (!function_exists("odb_api_del_trash"))
			require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
		odb_api_del_trash($trash_id_path);
	#7266
		if (!function_exists("odb_api_del_trash"))
			require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
		odb_api_del_trash($trash_path);
		
		
		
	// 影片待轉的檔案
	/data/HTTPD/htdocs/Site/video_waiting.list
		wheechen/Driver/dir_yo5sNj/dir_tqfn4V/file_38jd7d.mp4
		wheechen/Driver/dir_yo5sNj/dir_tqfn4V/file_Tb6AUC.mp4
		wheechen/Driver/dir_yo5sNj/dir_tqfn4V/file_zbeZDo.mp4
		// Append File
			$fp = fopen($page_dir.VIDEO_WAITING_LIST, "a");
			fputs($fp, "$file_path\n");
			fclose($fp);
		// 第兩次轉檔
		/data/NUWeb_Site/tmp/video_convert.list 
		flv	/data/HTTPD/htdocs/Site/wheechen/Driver/dir_yo5sNj/dir_tqfn4V/file_qIyNLM.mp4	/data/HTTPD/htdocs/Site/wheechen/Driver/dir_yo5sNj/dir_tqfn4V/.nuweb_media/file_qIyNLM.mp4.480.flv	480	1500	29
		mp4	/data/HTTPD/htdocs/Site/wheechen/Driver/dir_yo5sNj/dir_tqfn4V/file_qIyNLM.mp4	/data/HTTPD/htdocs/Site/wheechen/Driver/dir_yo5sNj/dir_tqfn4V/.nuweb_media/file_qIyNLM.mp4.480.mp4	480	1500	29
		
        $fp = fopen(CONVERT_LIST, "a");
        fputs($fp, trim("$type\t$video_file\t$target_file\t$height\t$v_bitrate\t$v_tbr")."\n");
        fclose($fp);
		
		
*/

if (!function_exists("die_json")) {
    require_once("/data/HTTPD/htdocs/tools/wbase2.php");
    require_once("/data/HTTPD/htdocs/tools/wrec.php");
}
if (!function_exists("get_server_domain")) {
    require_once("/data/HTTPD/htdocs/tools/public_lib.php");
}

define("ODB_HOST_PATH",		"/home/odb/bin/odbap");


if (!defined("NUWEB_REC_PATH")) define("NUWEB_REC_PATH",	".nuweb_rec/");
define("ODB_LOG_PATH",		"/data/HTTPD/htdocs/tools/logs/");

Global $fe_type;

odb_init_arg();

// 設定一些參數
function odb_init_arg()
{
	Global $lang, $set_conf, $reg_conf, $login_user, $uid, $uacn, $is_manager;
	if (isset($set_conf['odb_start']))
	{
		define("ODB_START",	$set_conf['odb_start'] == "y" && file_exists(ODB_HOST_PATH));
		if (isset($set_conf['odb_host_url']))
			define("ODB_HOST_URL", $set_conf['odb_host_url']);
		else
			define("ODB_HOST_URL", "http://localhost:3927/");
		if (isset($set_conf['odb_host_num']))
			define("ODB_HOST_NUM", " -C ".$set_conf['odb_host_num']);
		else
			define("ODB_HOST_NUM", " -C 2");
	}
	else
	{
		define("ODB_START",	false);
		define("ODB_HOST_URL", "http://localhost:3927/");
		define("ODB_HOST_NUM", " -C 2");
	
	
		// define("ODB_START",	file_exists(ODB_HOST_PATH));
		// $acn = $reg_conf['acn'];
		// if ($acn == "tw202"
			// || $acn == "tw203") {
			// define("ODB_HOST_URL", "http://163.27.70.91:3700/");
			// define("ODB_HOST_NUM", " -C 3");
		// } else {
			// define("ODB_HOST_URL", "http://localhost:3927/");
			// define("ODB_HOST_NUM", " -C 2");
		// }
	}

/*
	單一主機
		單多硬碟
			小硬碟
				Image
					.thumbs		o
					.640		o
					.1920		x
				video
					.thumbs		o
					.flv,.mp4	o
					.480 xx		x
				other
								o
			大硬碟
				Image
					.thumbs		o
					.640		o
					.1920		o
				video
					.thumbs		o
					.flv,.mp4	o
					.480 xx		o
				other
								o
*/
	// 是否單一主機
	//define("ODB_HOST_SINGLE",		true);
	define("ODB_HOST_SINGLE",		false);
	// 是否單一硬碟
	$disk_info = B_disk_free();
	define("ODB_HDD_SINGLE",		count($disk_info) <= 1);
	// 主硬碟是否為大硬碟 - 大於 600GB
	define("ODB_HDD_MAIN_LARGE",	disk_total_space("/data/HTTPD/htdocs") > 600000000*1024);
	
	
	define("ODB_HOLD_IMG_THUMBS",		true);
	define("ODB_HOLD_IMG_THUMBS_640",	true);
	define("ODB_HOLD_IMG_THUMBS_1920",	false);
	define("ODB_HOLD_VIDEO_THUMBS",		true);
	define("ODB_HOLD_VIDEO_THUMBS_SRC",	false);
	//define("ODB_HOLD_VIDEO_THUMBS_1920",	true);
	
	// 轉給 ODB 輸出檔案
	define("ODB_FILE_OUT",		true);

}
// 刪除檔案 - 垃圾桶
function odb_api_del_trash($trash_dir)
{
define("ODB_LOG_FILE",	ODB_LOG_PATH."odb_api_del_trash.log");
	odb_log("***");
	odb_log("*** odb_api_del_trash\t trash_dir=$trash_dir");
	
	if (!ODB_START) return false;
	
	if (!preg_match("#\\/\\.nuweb_trash(\\/|$)#", $trash_dir)) {
		odb_log("*** odb_api_del_trash\t 路徑錯誤");
		return false;
	}
	
	odb_api_del_trash_dir($trash_dir);
	
	odb_log("*** odb_api_del_trash\t End");
}
function odb_api_del_trash_dir($dir)
{
	odb_log("odb_api_del_trash_dir\t dir=$dir");
	if (substr($dir, -1) != "/") $dir .= "/";
	$f_type = $dir.".nuweb_type";
	$data_type = B_LoadFile($f_type);
	$aType = B_con_String2ArrayKey($data_type, "=", "\n");
	$dir_type = isset($aType['DIR_TYPE']) ? $aType['DIR_TYPE'] : "";
	odb_log("odb_api_del_trash_dir\t dir_type=$dir_type");
	// 功能目錄 - 不處理
	if (!empty($dir_type) && odb_dir_is_fun($dir_type)) {
		odb_log("odb_api_del_trash_dir\t 功能目錄 - 不處理");
		return false;
	}

	$handle=opendir($dir); 
	while ($fn = readdir($handle)) {
		if ($fn=='.' || $fn=='..') continue;
		if ($fn == "index.html") continue;
		if (odb_is_fn_sysfile($fn)) continue;
		if (odb_is_fn_thumbs($fn)) continue;
		
		$f = $dir.$fn;
		if (is_dir($f)) {
			odb_api_del_trash_dir($f);
		}
		else {
			odb_api_del_trash_file($f);
		}
	}
	closedir($handle);
}
function odb_api_del_trash_file($file)
{
	odb_log("odb_api_del_trash_file\t file=$file");

	$odb_info = odb_get_info($file);
	// 沒有 record 檔
	if ($odb_info === false) {
		odb_log("odb_api_del_trash_file\t 沒有 record 檔");
		return false;
	}

	$key = $odb_info['md5'];
	odb_log("odb_api_del_trash_file\t key=$key");
	$res = odb_delobject_cmd($key);
	odb_log("odb_api_del_trash_file\t res=$res");
	return $res;
}

function odb_api_is_exists($read_file)
{
define("ODB_LOG_FILE",	ODB_LOG_PATH."odb_api_is_exists.log");

	if (is_file($read_file))
		return true;
	
	if (!ODB_START) return false;
		
	$info = odb_file_path_parse($read_file);
	$fp = $info['fp'];

	// 檢查 Record 是否存在
	$fp_rec = odb_con_file2recfile($fp);
	$b = file_exists($fp_rec);
	return $b;
	
	// 檢查 ODB 是否存在
	// $odb_info = odb_get_info($fp);
	// if ($odb_info === false)
		// return false; // 沒有 record 檔
	
	// $key = $odb_info['md5'].$info['ext'];
	// return odb_is_exists($key);
}
function odb_api_video_notice_img_ok($video_file, $target_file)
{
define("ODB_LOG_FILE",	ODB_LOG_PATH."odb_api_video_notice_img_ok.log");
	if (!ODB_START) return false;
	odb_log("*** odb_api_video_notice_img_ok\t video_file=$video_file");

	// Check List 完成
	$bOK = odb_video_chk_list_ok($video_file);
	odb_log("*** odb_api_video_notice_img_ok\t ***** bOK=$bOK *****");
}
function odb_api_video_notice_ok($video_file, $target_file)
{
define("ODB_LOG_FILE",	ODB_LOG_PATH."odb_api_video_notice_ok.log");
	if (!ODB_START) return false;
	odb_log("*** odb_api_video_notice_ok\t video_file=$video_file");

	// Check List 完成
	$bOK = odb_video_chk_list_ok($video_file);
	// 刪除原檔案
	odb_del_original_file($video_file);
	
	odb_log("*** odb_api_video_notice_ok\t ***** bOK=$bOK *****");
}
function odb_api_video_chk_list_ok($video_file)
{
	if (!ODB_START) return false;
	return odb_video_chk_list_ok($video_file);
}
// 檢查是否存在
function odb_api_upload_file_chk_exists($read_file, $arg="")
{
define("ODB_LOG_FILE",	ODB_LOG_PATH."odb_api_upload_file_chk_exists.log");
	Global $fe_type;
	
	if (!ODB_START) return false;
	odb_log("*** odb_api_upload_file_chk_exists\t arg=$arg, read_file=$read_file");
	
	//odb_init_arg();
	// 修正舊的 code => $f_md5, $read_file
	if (!empty($arg) && substr($arg,0,1) == "/") $read_file = $arg;
	// 單一主機
	$out = false;
	$fe = ".".strtolower(B_GetExtension($read_file));
	$f_type = $fe_type[$fe];
	odb_log("*** odb_api_upload_file_chk_exists\t fe=$fe, f_type=$f_type, fp_rec=$fp_rec");
	if (ODB_HOST_SINGLE)
	{
		if ($f_type == IMAGE_TYPE)
		{
			$key = md5_file($read_file);
			$out = odb_img_chk_list_ok($read_file, $key, true);
		}
		if ($f_type == VIDEO_TYPE)
		{
			$out = true;
		}
	}
	// 多主機
	else
	{
		// 必須先產生縮圖檔才會被記錄到 Record 檔中
		if ($f_type == IMAGE_TYPE)
		{
            // 先轉小縮圖
            extract_tn($read_file);
		
			//$key = md5_file($read_file);
			//odb_img_chk_list_ok($read_file, $key, true);
		}
		if ($f_type == VIDEO_TYPE)
		{
		}
		
		// 改由 ODB 轉縮圖及影片
		$out = true;
	}
	odb_log("*** odb_api_upload_file_chk_exists\t out=$out ~~~~~~~~~~~~~~");
	return $out;
}
// 檔案上傳 call
function odb_api_upload_file($read_file, $act="new")
{
define("ODB_LOG_FILE",	ODB_LOG_PATH."odb_api_upload_file.log");
	if (!ODB_START) return false;
	
odb_log("***");
odb_log("***** odb_api_upload_file ***** read_file=".$read_file);
	$dir = B_Url_MakePath($read_file, false, true);
	$FileName = B_GetFileNameExtension($read_file);
	$f_rec = $dir.NUWEB_REC_PATH.$FileName.".rec";
	odb_log("odb_api_upload_file\t f_rec=$f_rec, file_exists=".file_exists($f_rec));
	if (!file_exists($f_rec))
		return false; // 沒有 Record 檔
		
	$rec = B_Rec_File2Rec($f_rec);
	$key = $rec['md5'];
	$type = $rec['type'];
	odb_log("odb_api_upload_file\t key=$key, type=$type");
	
	//odb_init_arg();
	$bNewFile = $act != "update"; // 新增檔案
	// 單一主機
	$bOK = false;
	if (ODB_HOST_SINGLE)
	{
		if ($type == "Image")
		{
			// 上傳原檔
			odb_upload_file($key, $read_file, $type);
			// 檢查縮圖是否有轉換完成
			$bOK = odb_img_chk_list_ok($read_file, $key, false);
		}
		else if ($type == "Video")
		{
			// 上傳原檔
			odb_upload_file($key, $read_file, $type);
			// 檢查 影片 List 在 ODB 上是否完整
			$bOK = odb_video_chk_list_ok($read_file);
			if ($bOK) {
				// 已完成，刪除原檔
				odb_del_original_file($read_file);
			}
			else {
				// 未完成加入轉檔 List
				$file_path = odb_con_read_file2file_path($read_file);
				$f_list = WEB_PAGE_DIR.VIDEO_WAITING_LIST;
				$hFile = fopen($f_list, "a");
				if ($hFile) {
					fputs($hFile, "$file_path\n");
					fclose($hFile);
odb_log("***** odb_api_upload_file 加入轉檔 OK file_path=$file_path");
				} else {
odb_log("***** odb_api_upload_file 加入轉檔 失敗 file_path=$file_path");
				}
			}
		}
		else
		{
			// 上傳原檔
			if (odb_upload_file($key, $read_file, $type)) {
				// 上傳成功刪除原檔
				odb_del_original_file($read_file);
				$bOK = true;
			}
		}
	}
	// 多主機
	else
	{
		if ($type == "Image")
		{
			// 檢查縮圖是否有轉換完成
			$bOK = odb_img_chk_list_ok($read_file, $key, false);
			// 上傳原檔
			if (odb_upload_file($key, $read_file, $type, !$bOK)) {
				// 上傳成功刪除原檔
				odb_del_original_file($read_file);
				
				if (!$bOK) 
					$bOK = odb_img_chk_list_ok($read_file, $key, false);
			}
		}
		else if ($type == "Video")
		{
			// 檢查 影片 List 在 ODB 上是否完整
			$bOK = odb_video_chk_list_ok($read_file);
			// 上傳原檔
			if (odb_upload_file($key, $read_file, $type, !$bOK)) {
				// 上傳成功刪除原檔
				odb_del_original_file($read_file);
			}
		}
		else
		{
			// 上傳原檔
			if (odb_upload_file($key, $read_file)) {
				// 上傳成功刪除原檔
				odb_del_original_file($read_file);
				$bOK = true;
			}
		}
	}
odb_log("***** odb_api_upload_file ***** bOK=$bOK *****");
}
// 第二次轉縮圖OK call
function odb_api_upload_file_image($read_file)
{
define("ODB_LOG_FILE",	ODB_LOG_PATH."odb_api_upload_file_image.log");
odb_log("odb_api_upload_file_image read_file=$read_file");
	if (!ODB_START) return false;

	$odb_info = odb_get_info($read_file);
	// 沒有 record 檔
	if ($odb_info === false)
		return false;
		
	$key = $odb_info['md5'];
	$bOK = odb_img_chk_list_ok($read_file, $key, false);
	// 轉檔結束, 刪除原檔
	odb_del_original_file($read_file);
	
odb_log("odb_api_upload_file_image ***** bOK=$bOK *****");
	return $bOK;
}
function odb_api_img_chk_list_ok($read_file, $key)
{
	if (!ODB_START) return false;
	return odb_img_chk_list_ok($read_file, $key, false);
}
function odb_del_original_file($read_file)
{
	$tm = filemtime($read_file);
	B_SaveFile($read_file, "");
	touch($read_file, $tm);
	
	odb_log("odb_del_original_file 清空原檔 read_file=$read_file");
}

function odb_api_print_show_page($read_file, $bDownload)
{
define("ODB_LOG_FILE",	ODB_LOG_PATH."odb_api_print_show_page.log");

	if (!ODB_START) return false;

	odb_log("odb_getobject_show_page 0 --------------------");
	odb_log("odb_getobject_show_page 1 bDownload=$bDownload, read_file=$read_file");
if (bDebug) echo "odb_getobject_show_page 1 bDownload=$bDownload, read_file=$read_file <br>";
	
	// Video .nuweb_media
	if (preg_match("#\/\.nuweb_media\/#i", $read_file))
	{
		if (!preg_match("#(\.720\.flv|\.720\.mp4|\.480\.flv|\.480\.mp4|\.flv|\.mp4)$#", $read_file, $m))
			return false;
			
		odb_log("odb_getobject_show_page preg_match=".B_array2String2($m,false,1));
if (bDebug) echo "odb_getobject_show_page preg_match=".B_array2String2($m,false,1)." <br>";
		$dir = B_Url_MakePath(B_Url_MakePath($read_file,false,false),false,false);
		$ext = $m[0];
		$fn = substr(B_GetFileNameExtension($read_file), 0, -strlen($ext));
		$fp = $dir."/".$fn;
		odb_log("odb_getobject_show_page fp=$fp");
		
		
		$odb_info = odb_get_info($fp);
		if ($odb_info === false)
			return false; // 沒有 record 檔
			
		$odb_info['key'] = $odb_info['md5'].$ext;
		odb_log("odb_getobject_show_page key=".$odb_info['key']);
		$stat = odb_get_stat($odb_info['key']);
if (bDebug) echo "odb_getobject_show_page Video stat=".B_array2String2($stat,false,1)." <br>";
		if ($stat === false)
			return false; // ODB 檔案不存在
		
		$odb_info = array_merge($odb_info, $stat);
		odb_getobject_print_file($odb_info, $ext, $bDownload);
		return true;
	}
	// Image .nuweb_media
	else if (preg_match("#(\.1920|\.640|\.480|\.src)?\.thumbs\.jpg$#i", $read_file, $m))
	{
	
		odb_log("odb_getobject_show_page preg_match=".B_array2String2($m,false,1));
if (bDebug) echo "odb_getobject_show_page Video preg_match=".B_array2String2($m,false,1)." <br>";
		$ext = $m[0];
		$fp = substr($read_file, 0, -strlen($ext));
		odb_log("odb_getobject_show_page fp=$fp");
		
		
		$odb_info = odb_get_info($fp);
		if ($odb_info === false)
			return false; // 沒有 record 檔
			
		$odb_info['key'] = $odb_info['md5'].$ext;
if (bDebug) echo "odb_getobject_show_page key=".$odb_info['key']." <br>";
		odb_log("odb_getobject_show_page key=".$odb_info['key']);
		$stat = odb_get_stat($odb_info['key']);
if (bDebug) echo "odb_getobject_show_page stat=".B_array2String2($stat,false,1)." <br>";
		if ($stat === false)
			return false; // ODB 檔案不存在
			
		$odb_info = array_merge($odb_info, $stat);
		odb_getobject_print_file($odb_info, $ext, $bDownload);

		return true;
	}
	// 一般檔案
	else
	{
		$odb_info = odb_get_info($read_file);
		if ($odb_info === false)
			return false; // 沒有 record 檔
			
		$odb_info['key'] = $odb_info['md5'];
		odb_log("odb_getobject_show_page key=".$odb_info['key']);
		$stat = odb_get_stat($odb_info['key']);
		if ($stat === false)
			return false; // ODB 檔案不存在
			
		$odb_info = array_merge($odb_info, $stat);
		odb_getobject_print_file($odb_info, "", $bDownload);
		return true;
	}

	return false;
}
// 取得檔案內容
function odb_api_get_content($read_file)
{
define("ODB_LOG_FILE",	ODB_LOG_PATH."odb_api_get_content.log");

	if (!ODB_START) return false;
	odb_log("odb_api_get_content\t *** read_file: $read_file");
		
	$info = odb_file_path_parse($read_file);
	if ($info === false)
		return false;
	
	odb_log("odb_api_get_content\t *** fp: ".$info['fp']);
	$odb_info = odb_get_info($info['fp']);
	if ($odb_info === false)
		return false; // 沒有 record 檔
	
	$key = $odb_info['md5'].$info['ext'];
	odb_log("odb_api_get_content\t *** key: $key");
	$data = odb_getobject($key);
	odb_log("odb_api_get_content\t *** fs: ".strlen($data).", data: ".substr($data,0,64));
	if (strlen($data) == 0)
		return false;
		
	return $data;
}
// 取得檔案內容
function odb_api_get_content_to_file($read_file, $file)
{
define("ODB_LOG_FILE",	ODB_LOG_PATH."odb_api_get_content_to_file.log");

	if (!ODB_START) return false;
	odb_log("odb_api_get_content_to_file\t *** read_file: $read_file");
		
	$info = odb_file_path_parse($read_file);
	if ($info === false)
		return false;
	
	odb_log("odb_api_get_content_to_file\t *** fp: ".$info['fp']);
	$odb_info = odb_get_info($info['fp']);
	if ($odb_info === false)
		return false; // 沒有 record 檔
	
	$key = $odb_info['md5'].$info['ext'];
	odb_log("odb_api_get_content_to_file\t *** key: $key");
	$fs = odb_getobject_to_file($file, $key);
	odb_log("odb_api_get_content_to_file\t *** fs: $fs");
	return $fs;
}


// out => {fp, ext}
function odb_file_path_parse($read_file)
{
	// Video .nuweb_media
	if (preg_match("#\/\.nuweb_media\/#i", $read_file))
	{
		$dir = B_Url_MakePath(B_Url_MakePath($read_file,false,false),false,false);
		if (!preg_match("#(\.720\.flv|\.720\.mp4|\.480\.flv|\.480\.mp4|\.flv|\.mp4)$#i", $read_file, $m))
			return false;
			
		$ext = $m[0];
		$fn = substr(B_GetFileNameExtension($read_file), 0, -strlen($ext));
		$fp = $dir."/".$fn;
	}
	// 一般檔案
	else
	{
		// Image
		if (preg_match("#(\.1920|\.640|\.src)*\.thumbs\.jpg$#i", $read_file, $m)) {
			$ext = $m[0];
			$fp = substr($read_file, 0, -strlen($ext));
		}
		else {
			$fp = $read_file;
			$ext = "";
		}
	}
	return array('fp' => $fp, 'ext' => $ext);
}
// 上傳原始檔
function odb_upload_file($key, $read_file, $type="", $bConvert=false)
{
	odb_log("odb_upload_file\t type=$type, bConvert=$bConvert, key=$key, read_file=$read_file");
	
	$s_cnt = " -Link";
	$s_convert = "";
	for ($nErr=0; $nErr<3; $nErr++)
	{
		if ($type == "Image")
		{
			if ($bConvert)
				$s_convert = ' -convert "img:.'.B_GetExtension($read_file).'"';
			$cmd = ODB_HOST_PATH.$s_cnt.$s_convert.ODB_HOST_NUM." -H '".ODB_HOST_URL."' -N '$key' -f '$read_file'";
		}
		else if ($type == "Video")
		{
			if ($bConvert)
				$s_convert = ' -convert "'.odb_video_get_list_def_po($read_file).'"';
			$cmd = ODB_HOST_PATH.$s_cnt.$s_convert.ODB_HOST_NUM." -H '".ODB_HOST_URL."' -N '$key' -f '$read_file'";
		}
		else
		{
			$cmd = ODB_HOST_PATH.$s_cnt.ODB_HOST_NUM." -H '".ODB_HOST_URL."' -N '$key' -f '$read_file'";
		}
		odb_log("odb_upload_file\t cmd: $cmd");
		$t = B_GetCurrentTime_usec();
		$result = shell_exec($cmd);
		odb_log("odb_upload_file\t t=".sprintf("%.3f",B_GetCurrentTime_usec()-$t)." res: ".$result);
		
		if (odb_is_exists($key)) {
			return true; // 已經存在了
		}
	}
	odb_log("odb_upload_file\t 失敗, 已經錯誤 3次");
	return false;
}
// 上傳一般檔案或是附屬檔
function odb_putobject_cmd($key, $read_file)
{
	odb_log("odb_putobject_cmd\t key=$key, read_file=$read_file, type=$type");
	for ($nErr=0; $nErr<3; $nErr++)
	{
		//if ($nErr > 0 && odb_is_exists($key)) { // 重新上傳
		if (odb_is_exists($key)) {
			return true; // 已經存在了
		}
		
		$cmd = ODB_HOST_PATH.ODB_HOST_NUM." -H '".ODB_HOST_URL."' -N '$key' -f '$read_file'";
		odb_log("odb_putobject_cmd\t nErr=$nErr, cmd: $cmd");
		$t = B_GetCurrentTime_usec();
		$result = shell_exec($cmd);
		odb_log("odb_putobject_cmd\t t=".sprintf("%.3f",B_GetCurrentTime_usec()-$t)." res: ".$result);
	}
	odb_log("odb_putobject_cmd\t 失敗, 已經錯誤 3次");
	return false;
}
function odb_getobject($key)
{
	$cmd = ODB_HOST_PATH." -H '".ODB_HOST_URL."' -R '$key'";
	odb_log("odb_getobject cmd: $cmd");
	$result = shell_exec($cmd);
	odb_log("odb_getobject res: ".substr($result,0,128));
	return $result;
}
// $info => add {key, st_xxx(odb_get_stat)}
function odb_getobject_print_file($odb_info, $ext, $bDownload)
{
    require_once("/data/HTTPD/htdocs/tools/content-type.php");
	
	$key = $odb_info['key'];
	$fn = $odb_info['filename'].(!empty($ext) ? $ext : "");
	$fe = B_GetExtension($fn);
	$CT = con_Extension2ContentType($fe);
	$tm = $odb_info['st_mtime'];
	$fsize = $odb_info['st_size'];
    $start = 0;
odb_log("odb_getobject_print_file: key=$key, fn=$fn, CT=$CT, odb_tm=$tm, rec_tm".strtotime($odb_info['mtime'])." fsize=$fsize, start=$start");
	
// *** 轉給 ODB 輸出 ***
	if (ODB_FILE_OUT && isset($odb_info['URL']))
	{
		$u = $odb_info['URL']
			."content-type:".$CT.";"
			.($bDownload ? "download:" : "outname:").rawurlencode($odb_info['filename']).";";
		header('Location: '.$u);
		exit;			
	}
	
	// flv 傳來的參數 
    if (isset($_REQUEST["start"]))
        $start = $_REQUEST["start"];
if (bDebug) echo "odb_getobject_print_file: fe=$fe, odb_tm=$tm, rec_tm".strtotime($odb_info['mtime'])." fsize=$fsize <br>";
if (bDebug) echo "odb_getobject_print_file: key=$key, fn=$fn, CT=$CT, start=$start <br>";
	
	/* 檢查瀏覽器是否為 IE, 是就將 fn 進行 rawurlencode */
	$buf = explode(";", $_SERVER["HTTP_USER_AGENT"]);
	list($brow, $brow_ver) = explode(" ", trim($buf[1]));
	$bIE11 = strpos($_SERVER["HTTP_USER_AGENT"], "rv:11.0") !== false;
	if ($brow == "MSIE" || $bIE11)
		$show_file_name = rawurlencode($fn);
	else
		$show_file_name = $fn;
	
	
	// if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $tm)
	// {
		// header('HTTP/1.0 304 Not Modified');
		// exit;
	// }
	
if (bDebug) {
	$data = odb_getobject($key);
	echo "odb_getobject_print_file: fs=".strlen($data).", buf=".substr($data, 0, 64)." <br>";
	exit;
}

	$bPartial = !empty($_SERVER["HTTP_RANGE"]);
	if ($bPartial) {
		header("HTTP/1.1 206 Partial Content");
	}
	else {
		header("Pragma: cache");
		header("Cache-Control: public");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s T", $tm));
	}
	
	header("Content-type: ".$CT);
	if ($bDownload)
	{
		header("Content-Disposition: attachment; filename=\"".$show_file_name."\"");
		header("Content-Transfer-Encoding: binary");
	}
	else
	{
		header("Accept-Ranges: bytes");
		header("Content-Disposition: inline; filename=\"".$show_file_name."\"");
	}

	if ($bPartial)
	{
		list($unit, $v) = explode("=", $_SERVER["HTTP_RANGE"], 2);
		list($Pos, $End) = explode("-", $v);
		odb_log("odb_getobject_print_file: HTTP_RANGE=$Pos, $End");
		if (empty($End))
			$End = $fsize - 1;
		$size = $End - $Pos + 1;
		header("Content-Length: $size");
		header("Content-Range: bytes {$Pos}-{$End}/{$fsize}");
		odb_getobject_print($key, $Pos, $size);
	}
	else
	{
		$size = $fsize - $start;
		header("Content-Length: $size");
		/* 若 start > 0 且是 FLV 檔案,要輸出 FLV file format header */
		if (($start > 0) && ($fe == "flv"))
			echo "FLV".pack("CCNN", 1, 1, 9, 9); // "FLV\x01\x01\x00\x00\x00\x09\x00\x00\x00\x09"
		if ($start == 0)
			odb_getobject_print($key);
		else
			odb_getobject_print($key, $start, $size);
	}
}
function odb_getobject_print($key, $pos=null, $size=0)
{
	if (isset($pos))
		$cmd = ODB_HOST_PATH." -H '".ODB_HOST_URL."' -offset $pos -size $size -R '$key'";
	else
		$cmd = ODB_HOST_PATH." -H '".ODB_HOST_URL."' -R '$key'";
	odb_log("odb_getobject_print\t cmd: $cmd");
	$t = B_GetCurrentTime_usec();
	$handle = popen($cmd, "rb");
	odb_log("odb_getobject_print\t handle:$handle");
	$fs = 0;
	while (!feof($handle)) {
		$buf = fread($handle, 4096);
		if (empty($buf))
			break;
			
		print $buf;
		//
		if ($fs == 0)
			odb_log("odb_getobject_print\t pos=$handle,$fs, res: ".substr($buf,0,64));
		$fs += strlen($buf);
	}
	pclose($handle);
	odb_log("odb_getobject_print\t t=".sprintf("%.3f",B_GetCurrentTime_usec()-$t)." fs:$fs");
	return $fs;
}
function odb_getobject_to_file($file, $key, $pos=null, $size=0)
{
	$hFile = fopen($file, "wb");
	if (!$hFile) return false;

	if (isset($pos))
		$cmd = ODB_HOST_PATH." -H '".ODB_HOST_URL."' -offset $pos -size $size -R '$key'";
	else
		$cmd = ODB_HOST_PATH." -H '".ODB_HOST_URL."' -R '$key'";
	odb_log("odb_getobject_to_file\t cmd: $cmd");
	$t = B_GetCurrentTime_usec();
	$handle = popen($cmd, "rb");
	odb_log("odb_getobject_to_file\t handle:$handle");
	$fs = 0;
	while (!feof($handle)) {
		$buf = fread($handle, 4096);
		if (empty($buf))
			break;
			
		fwrite($hFile, $buf);
		//
		if ($fs == 0)
			odb_log("odb_getobject_to_file\t res: ".substr($buf,0,64));
		$fs += strlen($buf);
	}
	pclose($handle);
	fclose($hFile);
	odb_log("odb_getobject_to_file\t t=".sprintf("%.3f",B_GetCurrentTime_usec()-$t)." fs:$fs");
	return filesize($file);
}
// -d => 刪除 或是 Count 減淤一
// -forcedel => 強制刪除, 不管 Count 是多少
function odb_delobject_cmd($key, $forced=false)
{
if (bDebug) echo "odb_delobject_cmd forced=$forced, key=$file <br>";
	odb_log("odb_delobject_cmd\t forced=$forced, key=$file");
	
	if ($forced)
		$cmd = ODB_HOST_PATH." -forcedel '$key' ";
	else
		$cmd = ODB_HOST_PATH." -d '$key' ";
if (bDebug) echo "odb_delobject_cmd cmd=$cmd <br>";
	odb_log("odb_delobject_cmd\t cmd=$cmd");
	$t = B_GetCurrentTime_usec();
	$result = shell_exec($cmd);
if (bDebug) echo "odb_delobject_cmd t=".sprintf("%.3f",B_GetCurrentTime_usec()-$t)." res: ".$result." <br>";
	odb_log("odb_delobject_cmd\t t=".sprintf("%.3f",B_GetCurrentTime_usec()-$t)." res: ".$result);
}
// odbap -H http://localhost:3927/ -convert 'img:.jpg' -N 7b135f955f97c8ef4d97bfee9583652a -reconv
function odb_reconv_cmd($key, $read_file, $img)
{
	if ($img)
		$cmd = ODB_HOST_PATH.' -H "'.ODB_HOST_URL.'" -convert "img:.jpg" -N '.$key.' -reconv';
	else
		$cmd = ODB_HOST_PATH.' -H "'.ODB_HOST_URL.'" -convert "'.odb_video_get_list_def_po($read_file).'" -N '.$key.' -reconv';
	odb_log("odb_reconv_cmd\t cmd=$cmd");
// echo "odb_reconv_cmd cmd=$cmd <br>\n";
	$t = B_GetCurrentTime_usec();
	$result = shell_exec($cmd);
	odb_log("odb_reconv_cmd\t t=".sprintf("%.3f",B_GetCurrentTime_usec()-$t)." res: ".$result);
// echo "odb_reconv_cmd t=".sprintf("%.3f",B_GetCurrentTime_usec()-$t)." res: ".$result." <br>\n";
}



// Key 是否存在
function odb_is_exists($key)
{
	$st = odb_get_stat($key);
	$bOK = $st !== false;
	odb_log("odb_is_exists\t ***** bOK=$bOK *****");
	return $st !== false;
	
	// $cmd = ODB_HOST_PATH." -H '".ODB_HOST_URL."' -T '$key'";
	// odb_log("odb_is_exists\t cmd: $cmd");
	// $t = B_GetCurrentTime_usec();
	// $result = shell_exec($cmd);
	// odb_log("odb_is_exists\t t=".sprintf("%.3f",B_GetCurrentTime_usec()-$t)." fs:".strlen($result)." res: ".$result);
	// return strpos($result, "ok") !== false;
}
// 是否是縮圖
function odb_is_fn_thumbs($sFileName)
{
	$sFileName = strtolower($sFileName);
	return substr($sFileName, -11) == ".thumbs.jpg";
}
// 是否是系統檔名
function odb_is_fn_sysfile($sFileName)
{
	$sFileName = strtolower($sFileName);
	return substr($sFileName, 0, 7) == ".nuweb_"
			|| $sFileName == "style"
			|| substr($sFileName, -4) == ".php"
			|| substr($sFileName, -5) == ".core";
}
function odb_dir_is_fun($sDirType)
{
	return	$sDirType == "forum" 
			|| $sDirType == "vote" 
			|| $sDirType == "table" 
			|| $sDirType == "shop" 
			|| $sDirType == "bookmark"
			;
}

// st_mode	33188
// st_atime	1389346583
// st_mtime	1389346470
// st_ctime	1389346470
// st_size	928247
function odb_get_stat($key)
{
	$cmd = ODB_HOST_PATH." -H '".ODB_HOST_URL."' -stat '$key'";
	odb_log("odb_get_stat\t cmd: $cmd");
	$t = B_GetCurrentTime_usec();
	$result = shell_exec($cmd);
	odb_log("odb_get_stat\t t=".sprintf("%.3f",B_GetCurrentTime_usec()-$t)." fs:".strlen($result)." res: ".$result);
	if (strpos($result, "st_mode") === false)
		return false;
	return B_con_String2ArrayKey($result, "\t", "\n");
}

function odb_con_feType2fType($feType)
{
	switch($feType) {
		case VIDEO_TYPE:
			$type = VIDEO_TYPE_NAME;
			break;
		case AUDIO_TYPE:
			$type = AUDIO_TYPE_NAME;
			break;
		case IMAGE_TYPE:
			$type = IMAGE_TYPE_NAME;
			break;
		case DOC_TYPE:
			$type = DOC_TYPE_NAME;
			break;
		case TEXT_TYPE:
			$type = TEXT_TYPE_NAME;
			break;
		case HTML_TYPE:
			$type = HTML_TYPE_NAME;
			break;
		case LINK_TYPE:
			$type = LINK_TYPE_NAME;
			break;
		default:
			$type = OTHER_TYPE_NAME;
			break;
	}
	return $type;
}
function odb_con_file2recfile($read_file)
{
	$dir = B_Url_MakePath($read_file, false, true);
	$FileName = B_GetFileNameExtension($read_file);
	return $dir.NUWEB_REC_PATH.$FileName.".rec";
}
function odb_con_read_file2file_path($read_file)
{
	$x = strpos($read_file, "/Site/");
	if ($x !== false)
		return substr($read_file,$x+6);
	return false;
}
function odb_get_info($read_file)
{
	$dir = B_Url_MakePath($read_file, false, true);
	$FileName = B_GetFileNameExtension($read_file);
	$f_rec = $dir.NUWEB_REC_PATH.$FileName.".rec";
// if (bDebug) echo "odb_get_info f_rec=$f_rec <br>";
	// odb_log("odb_get_info\t f_rec=$f_rec, file_exists=".file_exists($f_rec));
	if (!file_exists($f_rec))
		return false;
	
	$rec = B_Rec_File2Rec($f_rec);
	//odb_log("odb_get_info\t md5=".$rec['md5']);
	//odb_log("odb_get_info\t rec=".B_Array2String2($rec,false,1));
	//
	$rec['read_dir'] = $dir;
	$rec['read_file'] = $read_file;
	$rec['read_fn'] = $FileName;
	return $rec;
}


function odb_img_get_list_def($read_file, $key)
{
	$list = array();
	$list[] = array('fp' =>	 $read_file.TN_FE_NAME		// 300
					,'key' => $key.TN_FE_NAME
					,'size' => 300);
	$list[] = array('fp' =>	 $read_file.MED_TN_FE_NAME	// 640
					,'key' => $key.MED_TN_FE_NAME
					,'size' => 640);
	$list[] = array('fp' =>	 $read_file.BIG_TN_FE_NAME	// 1920
					,'key' => $key.BIG_TN_FE_NAME
					,'size' => 1920);
	return $list;
}
// 檢查縮圖是否完整
function odb_img_chk_list_ok($read_file, $key, $OnlyChk=false)
{
	odb_log("odb_img_chk_list_ok read_file=$read_file, key=$key, OnlyChk=$OnlyChk");
		
	if (empty($key))
		return false;
	
	$bListOK = true;
	$list = odb_img_get_list_def($read_file, $key);
	foreach($list as $row) {
		$fp = $row['fp'];
		$key2 = $row['key'];
		$size = $row['size'];
		
		// 是否保留在硬碟
		$bHold = false;
		if ($size == 300)  $bHold = ODB_HOLD_IMG_THUMBS;
		if ($size == 640)  $bHold = ODB_HOLD_IMG_THUMBS_640;
		if ($size == 1920) $bHold = ODB_HOLD_IMG_THUMBS_1920;
		odb_log("odb_img_chk_list_ok bHold=$bHold, size=$size, key2=$key2, fp=$fp, file_exists=".file_exists($fp));
		//
		if (file_exists($fp) && filesize($fp) > 0) {
			if (odb_putobject_cmd($key2, $fp)) {
				if (!$bHold) {
					odb_log("odb_img_chk_list_ok 不保留, 清空檔案 $fp");
					B_SaveFile($fp, "");
				}
			} else {
				$bListOK = false; // 上傳失敗
			}
		}
		else {
			// ODB 存在
			if (odb_is_exists($key2)) {
				if ($bHold) {
					odb_log("odb_img_chk_list_ok 下載檔檔 $fp");
					if (!odb_download_file($key2, $fp))
						$bListOK = false;
				}
				else {
					odb_log("odb_img_chk_list_ok 不保留, 設為空檔 $fp");
					B_SaveFile($fp, "");
				}
			}
			else {
				$bListOK = false;
			}
		}
	}
	// 上傳 List 成功
	if ($bListOK) {
		
		if (!$OnlyChk) {
			// 刪除原檔
			if (B_filesize($read_file) > 0)
				odb_del_original_file($read_file);
		
			// 記錄完成
			// $dir = B_Url_MakePath($read_file, false, true);
			// $FileName = B_GetFileNameExtension($read_file);
			// $f_rec = $dir.NUWEB_REC_PATH.$FileName.".rec";
			// if (!file_exists($f_rec))
				// return false;
			// $rec = B_Rec_File2Rec($f_rec);
			// $rec['image_list_ok'] = "y";
			// B_Rec_Rec2File($f_rec, $rec, false);
			// odb_log("odb_img_chk_list_ok 記錄完成 f_rec=$f_rec");
		}
	}
	odb_log("odb_img_chk_list_ok ***** bListOK=$bListOK *****");
	return $bListOK;
}


// 取得影片應該有那些影片
function odb_video_get_list_def_po($read_file)
{
	// return {"mp4":["480","720"]} 
	$data = get_video_convert_info($read_file);
	$ext = B_GetExtension($read_file);
odb_log("***** odb_video_get_list_def_po 影片 ext=$ext, data=$data");
	$recs = array();
	if ($data !== false) {
		$res = json_decode($data, true);
		// mp4
		if (isset($res['mp4']) && count($res['mp4']) > 0) {
			$list = $res['mp4'];
			foreach($list as $size)
				$recs[] = "mp4:".$size; 
		} else 
			$recs[] = "mp4:0"; 
		// flv
		if (isset($res['flv'])) {
			$list = $res['flv'];
			foreach($list as $size)
				$recs[] = "flv:".$size; 
		}
	}
	$list = count($recs) > 0 ? implode(",", $recs) : "mp4:0";
	return "video:.$ext,$list";
}
function odb_video_get_list_def($read_file, $key, $height)
{
	$dir = B_Url_MakePath($read_file, false, true);
	$fn = B_GetFileNameExtension($read_file);
	$list_flv = array();
	$list_mp4 = array();
	$list_flv_se = array();
	$list_mp4_se = array();
	
	// return {"mp4":["480","720"]} 
	$data = get_video_convert_info($read_file);
	$ext = B_GetExtension($read_file);
odb_log("***** odb_video_get_list_def 影片 data=$data");
	if ($data !== false) {
		$res = json_decode($data, true);
		// mp4
		if (isset($res['mp4']) && count($res['mp4']) > 0) {
			$list = $res['mp4'];
			foreach($list as $size) {
				$list_mp4[] = array('fp' =>	$dir.NUWEB_MEDIA_PATH."$fn.$size.mp4"
									,'key' => $key.".$size.mp4"
									,'size' => $size
									,'s_ext' => $size.":.$size.mp4");
				$list_mp4_se[] = $size.":.$size.mp4";
			}
		}
		else {
			$size = $height;
			$list_mp4[] = array('fp' =>	$dir.NUWEB_MEDIA_PATH."$fn.mp4"
								,'key' => $key.".mp4"
								,'size' => $size
								,'s_ext' => $size.":mp4");
			$list_mp4_se[] = $size.":.$size.mp4";
		}
		//flv
		if (isset($res['flv'])) {
			$list = $res['flv'];
			foreach($list as $size) {
				$list_flv[] = array('fp' =>	$dir.NUWEB_MEDIA_PATH."$fn.$size.flv"
									,'key' => $key.".$size.flv"
									,'size' => $size
									,'s_ext' => $size.":.$size.flv");
				$list_flv_se[] = $size.":.$size.flv";
			}
		}
	}
	else {
		$size = $height;
		$list_mp4[] = array('fp' =>	$dir.NUWEB_MEDIA_PATH."$fn.mp4"
							,'key' => $key.".mp4"
							,'size' => $size
							,'s_ext' => $size.":mp4");
		$list_mp4_se[] = $size.":.$size.mp4";
	}
	return array('list_flv' => $list_flv, 'list_mp4' => $list_mp4,
				'list_flv_se' => implode(",", $list_flv_se), 'list_mp4_se' => implode(",", $list_mp4_se));
}

// 34. video_path(2):{
    // 1. flv_list(2):{
        // 1. 0(2):{
            // 1. size:480,
            // 2. oid:"/Site/wheechen/Driver/dir_52d49bd2cee92/dir_52d49bed4c85f/.nuweb_media/file_52d4de45a8f22.avi.480.flv"
        // },
        // 2. 1(2):{
            // 1. size:720,
            // 2. oid:"/Site/wheechen/Driver/dir_52d49bd2cee92/dir_52d49bed4c85f/.nuweb_media/file_52d4de45a8f22.avi.flv"
        // }
    // },
    // 2. mp4_list(2):{
        // 1. 0(2):{
            // 1. size:720,
            // 2. oid:"/Site/wheechen/Driver/dir_52d49bd2cee92/dir_52d49bed4c85f/.nuweb_media/file_52d4de45a8f22.avi.mp4"
        // },
        // 2. 1(2):{
            // 1. size:480,
            // 2. oid:"/Site/wheechen/Driver/dir_52d49bd2cee92/dir_52d49bed4c85f/.nuweb_media/file_52d4de45a8f22.avi.480.mp4"
        // }
    // }
// }, 
// @flv_list:720:.flv,480:.480.flv
// @mp4_list:720:.mp4,480:.480.mp4
function odb_video_get_list_play($read_file)
{
define("ODB_LOG_FILE",	ODB_LOG_PATH."odb_video_get_list_play.log");

	odb_log("***** odb_video_get_list_play ***** read_file=$read_file");
	$odb_info = odb_get_info($read_file);
	if ($odb_info === false)
		return false; // 沒有 record 檔
		
	if (!function_exists("con_Extension2ContentType"))
		require_once("/data/HTTPD/htdocs/tools/content-type.php");
	
	$key = $odb_info['md5'];
	$height = $odb_info['height'];
	$dir = B_Url_MakePath($read_file, false, true);
	$fn = B_GetFileNameExtension($read_file);
	odb_log("***** odb_video_get_list_play @@@ height=$height, key=$key");
	
	$dir_media = $dir.NUWEB_MEDIA_PATH;
	if (!file_exists($dir_media))
		mkdir($dir_media);
	
	// check 縮圖
	if (ODB_START) odb_video_chk_list_ok_img($read_file, $odb_info);
	
	// $flv_list = array();
	$mp4_list = array();
	// $a_flv = array(1080, 720, 480, 320);
	$a_mp4 = array(1080, 720, 480, 320);
	// $ext = "flv"; $bSort = false;
	// foreach($a_flv as $size) {
		// if ($size > $height) continue;
		// $fp = $dir.NUWEB_MEDIA_PATH.$fn.".$size.flv";
		// if ($key !== false) $key2 = $key.".$size.flv";
		// $b = odb_video_get_list_play_chk($fp, $key2, $st);
		// if ($b) odb_video_get_list_play_add_list($flv_list, $st, $size, $fp, $fn, $ext, $bSort);
	// }
	$ext = "mp4"; $bSort = false;
	foreach($a_mp4 as $size) {
		if ($size > $height) continue;
		$fp = $dir.NUWEB_MEDIA_PATH.$fn.".$size.mp4";
		if ($key !== false) $key2 = $key.".$size.mp4";
		$b = odb_video_get_list_play_chk($fp, $key2, $st);
		if ($b) odb_video_get_list_play_add_list($mp4_list, $st, $size, $fp, $fn, $ext, $bSort);
	}
	// 舊格式
	if ($odb_info !== false) {
		// flv
		// $fp = $dir.NUWEB_MEDIA_PATH.$fn.".flv";
		// $key2 = $key.".flv";
		// $size = $height < 720 ? $height : 720;
		// if (B_obj_indexOf($flv_list, "size", $size) === false) {
			// $b = odb_video_get_list_play_chk($fp, $key2, $st);
			// if ($b) odb_video_get_list_play_add_list($flv_list, $st, $size, $fp, $fn, "flv", true);
		// }
		// mp4
		$fp = $dir.NUWEB_MEDIA_PATH.$fn.".mp4";
		$key2 = $key.".mp4";
		$size = $height < 720 ? $height : 720;
		if (B_obj_indexOf($mp4_list, "size", $size) === false) {
			$b = odb_video_get_list_play_chk($fp, $key2, $st);
			if ($b) odb_video_get_list_play_add_list($mp4_list, $st, $size, $fp, $fn, "mp4", true);
		}
		// 檔案本身就是 MP4
		$ext = strtolower(B_GetExtension($fn));
		odb_log("***** odb_video_get_list_play 檔案本身是 ext=$ext");
		if ($ext == "mp4" && $height < 1281) {
			$fp = $read_file;
			$key2 = $key;
			$size = $height;
			if (B_obj_indexOf($mp4_list, "size", $size) === false) {
				$b = odb_video_get_list_play_chk($fp, $key2, $st);
				if ($b) odb_video_get_list_play_add_list($mp4_list, $st, $size, $fp, $fn, "mp4", true);
			}
		}
	}
	//
	$out = array();
	//if (count($flv_list) > 0) $out['flv_list'] = $flv_list;
	if (count($mp4_list) > 0) $out['mp4_list'] = $mp4_list;
	return $out;
}
function odb_video_get_list_play_add_list(&$list, $st, $size, $fp, $fn, $ext, $bSort)
{
	if (ODB_FILE_OUT && $st !== false && !empty($st['URL'])) {
		odb_log("***** odb_video_get_list_play_add_list *** st->url=".$st['URL']);
		$ct = con_Extension2ContentType($ext);
		odb_log("***** odb_video_get_list_play_add_list fn=$fn, ct=$ct");
		$oid = $st['URL']
			."content-type:".$ct.";"
			."outname:".rawurlencode($fn).";";
	}
	else
		$oid = "/Site/".odb_con_read_file2file_path($fp);
	odb_log("***** odb_video_get_list_play_add_list *** size=$size, oid=$oid");
	$list[] = array('size' => $size, 'oid' => $oid);
	//
	if ($bSort && count($list) > 1) usort($list, "odb_video_get_list_play_cmp_size");
}
function odb_video_get_list_play_cmp_size($a, $b)
{
	if ($a['size'] == $b['size']) return 0;
	return $a['size'] > $b['size'] ? -1 : 1;
}

function odb_video_get_list_play_chk($fp, $key, &$st=null)
{
	odb_log("odb_video_get_list_play_chk\t key=$key, fp=$fp");
	$st = false;
	$bOK = false;
	$bExists = file_exists($fp);
	if ($bExists && filesize($fp) > 0) {
		$bOK = true;
	}
	else {
		if (ODB_START) {
			// ODB 存在
			if ($key !== false && ($st=odb_get_stat($key)) !== false) {
				// 檔案不存在，產生空檔
				if (!$bExists) B_SaveFile($fp, "");
				$bOK = true;
			}
		}
	}
	odb_log("odb_video_get_list_play_chk\t ***** bOK=$bOK, st=$st *****");
	return $bOK;
}
// 檢查 影片 List 在 ODB 上是否完整
// $OnlyChk => 只檢查 Record 的記錄是否完整
function odb_video_chk_list_ok($read_file)
{
	odb_log("***** odb_video_chk_list_ok ***** read_file=".$read_file);
	$dir = B_Url_MakePath($read_file, false, true);
	$FileName = B_GetFileNameExtension($read_file);
	$f_rec = $dir.NUWEB_REC_PATH.$FileName.".rec";
	$bExists = file_exists($f_rec);
	odb_log("odb_video_chk_list_ok\t f_rec=$f_rec, bExists=$bExists");
	if (!$bExists)
		return false; // 沒有 Record 檔
		
	$rec = B_Rec_File2Rec($f_rec);
	$key = $rec['md5'];
	$height = $rec['height'];
	$def = odb_video_get_list_def($read_file, $key, $height);
	odb_log("odb_video_chk_list_ok\t rec key=$key, *** height=".$rec['height']);
	odb_log("odb_video_chk_list_ok\t def=".B_Array2String2($def,false,1));
	//
	$dir_media = $dir.NUWEB_MEDIA_PATH;
	if (!file_exists($dir_media)) {
		mkdir($dir_media);
		odb_log("odb_video_chk_list_ok\t 建立目錄 $dir_media");
	}
	//
	$bListOK = true;
	// check 縮圖
	if (!odb_video_chk_list_ok_img($read_file, $rec))
		$bListOK = false;
	// flv
	foreach($def['list_flv'] as $row) {
		$fp = $row['fp'];
		$key2 = $row['key'];
		if (!odb_video_chk_list_ok_do($fp, $key2, false))
			$bListOK = false;
	}
	// mp4 
	foreach($def['list_mp4'] as $row) {
		$fp = $row['fp'];
		$key2 = $row['key'];
		if (!odb_video_chk_list_ok_do($fp, $key2, false))
			$bListOK = false;
	}
	if ($bListOK) {
	}
	odb_log("odb_video_chk_list_ok\t *** bListOK=$bListOK ***");
	return $bListOK;
}
function odb_video_chk_list_ok_img($video_file, $odb_info)
{
	$bOK = true;
	$key = $odb_info['md5'];
	// 小縮圖 300px
	$fp = $video_file.TN_FE_NAME;
	$key2 = $key.TN_FE_NAME;
	if (odb_video_chk_list_ok_do($fp, $key2, ODB_HOLD_VIDEO_THUMBS)) {
		// 沒有記錄縮圖
		if (empty($odb_info['thumbs'])) {
			$dir = B_Url_MakePath($video_file, false, true);
			$FileName = B_GetFileNameExtension($video_file);
			$f_rec = $dir.NUWEB_REC_PATH.$FileName.".rec";
			$rec_add = array();
			$rec_add["thumbs"] = B_GetFileNameExtension($fp);
			update_rec_file($f_rec, $rec_add);
			odb_log("odb_video_chk_list_ok_img\t 沒有記錄縮圖 thumbs=".$rec_add["thumbs"]);
		}
	}
	else 
		$bOK = false;
	odb_log("odb_video_chk_list_ok_img\t 縮圖300 bOK=$bOK, key2=$key2, fp=$fp");
	// 大縮圖 - 影片 Size
	$fp = $video_file.SRC_TN_FE_NAME;
	$key2 = $key.SRC_TN_FE_NAME;
	if (!odb_video_chk_list_ok_do($fp, $key2, ODB_HOLD_VIDEO_THUMBS_SRC))
		$bOK = false;
	odb_log("odb_video_chk_list_ok_img\t 縮圖SRC bOK=$bOK, key2=$key2, fp=$fp");
	return $bOK;
}

function odb_video_chk_list_ok_do($fp, $key, $bHold)
{
	odb_log("odb_video_chk_list_ok_do\t bHold=$bHold, key=$key, fp=$fp");
	$bOK = true;
	$bExists = file_exists($fp);
	if ($bExists && filesize($fp) > 0) {
		if (odb_putobject_cmd($key, $fp)) {
			if (!$bHold) { // ODB_HOLD_VIDEO_THUMBS_SRC
				odb_log("odb_video_chk_list_ok_do 不保留, 清空檔案 $fp");
				B_SaveFile($fp, "");
			}
		} else
			$bOK = false; // 上傳失敗
	}
	else {
		// ODB 存在
		if (odb_is_exists($key)) {
			if ($bHold) {
				odb_log("odb_video_chk_list_ok_do 下載檔檔 $fp");
				if (!odb_download_file($key, $fp))
					$bOK = false;
			}
			else {
				if (!$bExists) {
					odb_log("odb_video_chk_list_ok_do 不保留, 設為空檔 $fp");
					B_SaveFile($fp, "");
				}
			}
			
		}
		else {
			$bOK = false;
		}
	
	}
	odb_log("odb_video_chk_list_ok_do\t ***** bOK=$bOK *****");
	return $bOK;
}
/* 記錄預設的基本 Record */
function odb_video_set_record($read_file, $sFileName)
{
	Global $uacn, $fe_type;
	
	$dir = B_Url_MakePath($read_file, false, true);
	$dir_rec = $dir.NUWEB_REC_PATH;
	if (!file_exists($dir_rec))
		mkdir($dir_rec);
	
	$FileName = B_GetFileNameExtension($read_file);
	$f_rec = $dir.NUWEB_REC_PATH.$FileName.".rec";
	// if (file_exists($f_rec))
		// return false;

	if (file_exists($rec_file))
		$rec = B_Rec_File2Rec($f_rec);

	$fe_n = strrpos($read_file, ".");
	if ($fe_n == false)
		$fe = "";
	else
		$fe = strtolower(substr($read_file, $fe_n));
	$f_md5 = md5_file($read_file);
	$f_size = real_filesize($read_file);
	
	$fe = ".".strtolower(B_GetExtension($read_file));
	$feType = $fe_type[$fe];
	
	$type_field = get_video_field($read_file, $fe);

	$rec["md5"] 	= $f_md5;
	$rec["filename"]= $sFileName;
	$rec["fe"] 		= $fe;
	$rec["size"] 	= $f_size;
	$rec["type"] 	= odb_con_feType2fType($feType);
	if (!empty($type_field)) {
		foreach($type_field as $key => $value)
			$rec[$key] = $value;
	}
	
	B_Rec_Rec2File($f_rec, $rec);
}
// 
function odb_cmd_api_scan_site_dir($site_path)
{
	if (substr($site_path, -1) != "/") $site_path .= "/";
	$f_type = $site_path.".nuweb_type";
	$data_type = B_LoadFile($f_type);
	$aType = B_con_String2ArrayKey($data_type, "=", "\n");
	$dir_type = isset($aType['DIR_TYPE']) ? $aType['DIR_TYPE'] : "";
odb_log("odb_cmd_api_scan_site_dir\t dir_type=$dir_type, site_path=$site_path");
	// 功能目錄 - 不處理
	if (!empty($dir_type) && odb_dir_is_fun($dir_type)) {
odb_log("odb_cmd_api_scan_site_dir\t 功能目錄 - 不處理");
		return false;
	}

	$handle=opendir($site_path); 
	while ($fn = readdir($handle)) {
		if ($fn=='.' || $fn=='..') continue;
		if ($fn == "index.html") continue;
		if (odb_is_fn_sysfile($fn)) continue;
		if (odb_is_fn_thumbs($fn)) continue;
		
		$f = $site_path.$fn;
		if (is_dir($f)) {
			odb_cmd_api_scan_site_dir($f);
		}
		else {
			if (preg_match("#\.html?$#i", $fn)) {
odb_log("odb_cmd_api_scan_site_dir\t 過濾掉文章, f=$f");
				continue;
			}
			
			odb_cmd_api_scan_site_file($f);
		}
	}
	closedir($handle);
}
function odb_cmd_api_scan_site_file($file)
{
	$fs = B_filesize($file);
odb_log("odb_cmd_api_scan_site_file\t fs=$fs, file=$file");
// echo "odb_cmd_api_scan_site_file\t fs=$fs, file=$file \n";
	// 還沒有上傳到 ODB
	if ($fs > 0)
	{
		odb_api_upload_file($file);
	}
	// 檢查是否有轉檔完成
	else
	{
		$dir = B_Url_MakePath($file, false, true);
		$FileName = B_GetFileNameExtension($file);
		$f_rec = $dir.NUWEB_REC_PATH.$FileName.".rec";
		odb_log("odb_api_upload_file\t f_rec=$f_rec, file_exists=".file_exists($f_rec));
		if (!file_exists($f_rec))
			return false; // 沒有 Record 檔
		
		$rec = B_Rec_File2Rec($f_rec);
		$key = $rec['md5'];
		$type = $rec['type'];
		if ($type == "Image")
		{
			$bOK = odb_img_chk_list_ok($file, $key, true);
			if (!$bOK) {
odb_log("odb_cmd_api_scan_site_file\t Image bOK=$bOK, file=$file");
// echo "odb_cmd_api_scan_site_file\t Image bOK=$bOK, file=$file \n";
				odb_reconv_cmd($key, $file, true);
			}
		}
		else if ($type == "Video")
		{
			$bOK = odb_video_chk_list_ok($file);
			if (!$bOK) {
odb_log("odb_cmd_api_scan_site_file\t Video bOK=$bOK, file=$file");
// echo "odb_cmd_api_scan_site_file\t Video bOK=$bOK, file=$file \n";
				odb_reconv_cmd($key, $file, false);
			}
		}
	}
}


function odb_download_file($key, $fp)
{
	odb_log("odb_download_file\t key=$key, fp=$fp");
	$st = odb_get_stat($key);
	if ($st == false) return false;
	$data = odb_getobject($key);
	B_SaveFile($fp, $data);
	odb_log("odb_download_file\t data size=".strlen($data));
	return true;
}

function odb_log($s)
{
	//$f = "/data/HTTPD/htdocs/tools/rs_odb_lib_get.php.log";
	if (defined('ODB_LOG_FILE'))
		$f = ODB_LOG_FILE;
	else
		$f = ODB_LOG_PATH."odb_lib.log";
	
	// 清除內容
	if ($s == "clear data") {
		$hFile = fopen($f, "wb");
		fclose($hFile);
		return;
	}
	// 
	$s = date("Ymd His\t").str_replace("\r", "\\r", str_replace("\n","\\n",$s))."\n";
	if (file_exists($f) && filesize($f) > 3000000) // 3M
		$hFile = fopen($f, "wb");
	else
		$hFile = fopen($f, "ab");
	if (!$hFile) return false;
	fwrite($hFile, $s);
	fclose($hFile);
}

function odb_test()
{
	Global $lang, $set_conf, $reg_conf, $login_user, $uid, $uacn, $is_manager;
	
	define("PATH_Web",			"/data/HTTPD/htdocs");
	define("URL_WebPages",		"Site");
	
    require_once("/data/HTTPD/htdocs/tools/rs_tools_base.php");
    require_once("/data/HTTPD/htdocs/tools/public_lib.php");
    require_once("/data/HTTPD/htdocs/tools/content-type.php");
	
	echo "acn=".$reg_conf['acn']." <br>";
	echo "ODB_START=".ODB_START." <br>";
	echo "ODB_HOST_NUM=".ODB_HOST_NUM." <br>";
	echo "ODB_HOST_PATH=".ODB_HOST_PATH." <br>";
	echo "ODB_HOST_URL=".ODB_HOST_URL." <br>";
	echo "ODB_HOST_SINGLE=".ODB_HOST_SINGLE." <br>";
	echo "ODB_HDD_SINGLE=".ODB_HDD_SINGLE." <br>";
	echo "ODB_HDD_MAIN_LARGE=".ODB_HDD_MAIN_LARGE." <br>";
	//echo "<br>login_user:<br>".B_Array2String2($login_user)." <br>";
	echo "<br>set_conf:<br>".B_Array2String2($set_conf)." <br>";
	echo "<br><br>";
	
	// ODB 沒有啟用
	// if (!ODB_START)
		// return;
		
	$file_path = isset($_REQUEST["file_path"]) ? $_REQUEST["file_path"] : "";
	if (empty($file_path)) die("Error: empty file_path");
	
print <<<_EOT_
<style>
body {font-family:Verdana, Geneva, sans-serif;}
h3 {margin:0;}
span.fn {width:300px; display:inline-block; overflow:hidden;}
span.err {color:#f00; font-weight:bold;}
</style>

_EOT_;
	
	$file_path = rs_filter_Site($file_path);
	if (!empty($file_path) && substr($file_path,-1) != '/') $file_path .= '/';
	odb_test_dir($file_path, "");
	
	echo "<br><br><br><br><br><br>";
}
function odb_test_dir($fp, $v_fp)
{
	// 功能目錄
	$dir_type = rs_dir_getType($fp);
	if (rs_dir_is_fun($dir_type)) {
		//echo "<h3 style=\"margin:0;\">功能目錄 $fp - $dir_type</h3>";
		return;
	}
	
	// 檔案
	$n = get_file_name(DIR_SITE, $fp);  // public_lib
	if (preg_match("#\.files$#i", $n)) {
		return;
	}
	$v_fp .= $n;
	echo "<h3>$v_fp &nbsp; (".preg_replace("#\/$#","",$fp).") </h3>";
	$o_fp = DIR_SITE.$fp;
	$handle=opendir($o_fp); 
	while ($fn = readdir($handle)) {
		if ($fn == '.' 
			|| $fn == '..'
			|| rs_is_fn_sysfile($fn)
			|| rs_is_fn_thumbs($fn)
			|| $fn == 'index.html'
			) continue;
		
		$sub_fp = $fp.$fn;
		$sub_o_fp = $o_fp.$fn;
		if (is_dir($sub_o_fp)) {
		} else {
			echo odb_test_file($sub_fp);
		}
	}
	closedir($handle);
	// 目錄
	$handle=opendir($o_fp); 
	while ($fn = readdir($handle)) {
		if ($fn == '.' 
			|| $fn == '..'
			|| rs_is_fn_sysfile($fn)
			|| rs_is_fn_thumbs($fn)
			|| $fn == 'index.html'
			) continue;
		
		$sub_fp = $fp.$fn;
		$sub_o_fp = $o_fp.$fn;
		if (is_dir($sub_o_fp)) {
			odb_test_dir($sub_fp."/", $v_fp."/");
		} else {
		}
	}
	closedir($handle);
}
function odb_test_file($fp)
{
	$fn = get_file_name(DIR_SITE, $fp);  // public_lib
	$read_file = DIR_SITE.$fp;
	$out = "<span class=\"fn\">$fn</span> - ";
	do {
		$dir = B_Url_MakePath($read_file, false, true);
		$FileName = B_GetFileNameExtension($read_file);
		$f_rec = $dir.NUWEB_REC_PATH.$FileName.".rec";
		if (!file_exists($f_rec)) {
			$out .= "沒有 Record 檔";
			break;
		}
		
		$rec = B_Rec_File2Rec($f_rec);
		$key = $rec['md5'];
		$type = $rec['type'];
		$out .= odb_test_chk($read_file, $key, "原檔", $rec);
		
		if ($type == "Image")
		{
			$out .= "size(".$rec['width']."x".$rec['height'].") ";
			$list = odb_img_get_list_def($read_file, $key);
			foreach($list as $row) {
				$fp = $row['fp'];
				$key2 = $row['key'];
				$size = $row['size'];
				$out .= odb_test_chk($fp, $key2, $size, $rec);
			}
		}
		else if ($type == "Video")
		{
			$out .= "height(".$rec['height'].") ";
			// 300
			$fp = $read_file.TN_FE_NAME;
			$key2 = $key.TN_FE_NAME;
			$out .= odb_test_chk($fp, $key2, "300", $rec);
			// src
			$fp = $read_file.SRC_TN_FE_NAME;
			$key2 = $key.SRC_TN_FE_NAME;
			$out .= odb_test_chk($fp, $key2, "src", $rec);
			//
			$def = odb_video_get_list_def($read_file, $key, $height);
			// flv
			foreach($def['list_flv'] as $row) {
				$fp = $row['fp'];
				$key2 = $row['key'];
				$size = $row['size'];
				$out .= odb_test_chk($fp, $key2, $size.".flv", $rec);
			}
			// mp4 
			foreach($def['list_mp4'] as $row) {
				$fp = $row['fp'];
				$key2 = $row['key'];
				$size = $row['size'];
				$out .= odb_test_chk($fp, $key2, $size.".mp4", $rec);
			}
		}
		else
		{
		}
	} while(0);
	return $out." <br>";
}
function odb_test_chk($fp, $key, $T, $rec)
{
	if (file_exists($fp)) {
		$ho = ",".tpl_con_Size2Abbr(filesize($fp));
	} else {
		$ho = ",n";
	}
// echo "odb_test_chk ho=$ho, fp=$fp <br>";

	$st = odb_get_stat($key);
	$bExists = $st !== false;
	if ($bExists) {
		$CT = con_Extension2ContentType(B_GetExtension($rec['filename']));
		$u = $st['URL']
			."content-type:".$CT.";"
			."outname:".rawurlencode($rec['filename']).";";
		return '<a href="'.$u.'" target="_blank">'.$T."</a>(y{$ho}) ";
	}
	else
		return '<span class="err">'.$T."</span>(n{$ho}) ";
}

?>