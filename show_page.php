<?php

// error_reporting(E_ALL); 
// ini_set("display_errors", 1); 

    require_once("/data/HTTPD/htdocs/tools/page/page_lib.php");
    require_once("/data/HTTPD/htdocs/API/content_type.php");
    /* 20130903 新增,因要檢查是否為 Mobile 裝置,所以要 require rs_tools_base.php */
    //require_once("/data/HTTPD/htdocs/tools/rs_tools_base.php");
    define("AUDIO_PLAT_TEMPLATE", TEMPLATE_DIR."audio_play.tpl");

    /* 如果 PHP 的 magic_quotes_gpc 為 OFF,就將所有參數特殊字元轉換 */
    //if (!get_magic_quotes_gpc())
    //    $_REQUEST = array_map("addslashes", $_REQUEST);
    /* 2015/3/4 修改,不用 array_map 處理,改 call addslashes_request 函數 (在 public_lib.php 中) */
    addslashes_request();

    $PHP_SELF = $_SERVER["SCRIPT_NAME"];
    $page_dir = WEB_PAGE_DIR;
    $page_url = $_REQUEST["page_url"];
    $mode = $_REQUEST["mode"];
    $start = 0;
    if (isset($_REQUEST["start"]))
        $start = $_REQUEST["start"];
    $sort_by = NULL;
    $sort_mode = NULL;
    if (isset($_REQUEST["sb"]))
        $sort_by = $_REQUEST["sb"];
    if (isset($_REQUEST["sm"]))
        $sort_mode = $_REQUEST["sm"];

    /* 2014/10/15 新增,若是 Group Client 就要檢查 login 狀態 (若傳入 group_login=-1 就代表不檢查,NUAPP 使用) */
    /* 2014/10/21 修改,若是 Group 內的 CS 就要檢查 login 狀態 (因 Group Server 需要處理 domain 的問題,所以還是要檢查) */
    if (($group_mode != GROUP_NONE) && ($_REQUEST["group_login"] != -1))
        group_chk_login();

    /* 2013/9/27 新增,若有傳入 nu_code 參數,就用 nu_code 取得登入 user 資料 */
    if (isset($_REQUEST["nu_code"]))
    {
        $login_user = get_login_user($_REQUEST["nu_code"]);
        $uid = $login_user["ssn"];
        $uacn = $login_user["acn"];
    }

    if (empty($page_url))
    {
        $now_dir = getcwd()."/";
        if (strstr($now_dir, WEB_ROOT_PATH) !== $now_dir)
            err_page(404);
        $page_url = str_replace(WEB_ROOT_PATH, "", $now_dir);
    }
    if (empty($page_url))
        die(EMPTY_PAGE_URL);

    /* page_url 開頭必須是 / 且不可以有 ./ 及其他非檔名允許的特殊符號 */
    if ((substr($page_url, 0, 1) != "/") || (strstr($page_url, "./") != false) || (preg_match(FORMAT_FAIL_PATH_NAME, $page_url)))
        err_page(404);
    /* 檢查 page_url 是否存在 */
    /* 2014/9/3 修改,增加檢查是否為 Group 內的 CS,若是就再檢查是否需要跳轉到其他 CS (若 Group Client 要連到 web 網站就跳轉到 Group Server 的 web 網站) */
    $path = explode("/", substr($page_url, 1));
    $group_web_site = false;
    if (($group_mode == GROUP_CLIENT) && ($path[0] == SUB_SITE_NAME) &&  ($path[1] == "web"))
        $group_web_site = true;
    if (($group_web_site == true) || (chk_page_url($page_url) !== true))
    {
        /* 若不是 Group 的 CS 就直接顯示錯誤頁 */
        if ($group_mode == GROUP_NONE)
            err_page(404);

        /* 先取得 page_url 的 site_acn */
        if ($path[0] != SUB_SITE_NAME)
            err_page(404);
        $site_acn = $path[1];

        /* 取回 group 的 site list,檢查 site_acn 是否在 site list 內,若不在就直接顯示錯誤頁 */
        /* 2015/5/26 修改,若 site_acn 是在目前的 CS 上,代表該目錄或檔案真的不存在,也要直接顯示錯誤頁,否則會造成無窮迴圈 */
        $site_list = group_get_site_list();
        if ((!isset($site_list[$site_acn])) || (empty($site_list[$site_acn])) || ($site_list[$site_acn] == strtolower($reg_conf["acn"])))
            err_page(404);

        /* 整理參數 (目前僅提供 GET 方式的參數傳遞方式) */
        $arg = "";
        foreach($_REQUEST as $key => $value)
        {
            if (!empty($arg))
                $arg .= "&";
            $arg .= "$key=$value";
        }
        $url = "http://".$site_list[$site_acn].".nuweb.cc".SHOW_PAGE_URL."?$arg";
        header("Location: $url");
        exit;
    }

    /* 檢查 page_url 是否為網站根目錄,若是先檢查是否有設定網站首頁位置 */
    if (($path[0] == SUB_SITE_NAME) && (empty($path[2])))
    {
        /* 先讀取網站設定檔 */
        $site_acn = $path[1];
        $site_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".$site_acn."/";
        $conf_file = $site_dir.NUWEB_CONF;
        $site_conf = read_conf($conf_file);
        /* 2014/9/3 新增,若網站還在等待審核中,就直接跳到預設網站 (/Site/web) */
        if ($site_conf["status"] != SITE_STATUS_ALLOW)
        {
            header("Location: /Site/web/");
            exit;
        }
        /* 2015/8/17 新增,若有設定 random_path 參數,也必須傳送 random_path 參數 */
        $arg = "";
        if (isset($_REQUEST["random_path"]))
            $arg = "&random_path=".$_REQUEST["random_path"];
        /* 若有設定 link_path 就跳到 link_path 指定位置 */
        /* 2015/8/12 修改,沒設定 index_mode 時,將 index_mode 設為預設值,若 index_mode 為 link 才檢查 link_path */
        if ((!isset($site_conf["index_mode"])) || (empty($site_conf["index_mode"])))
            $site_conf["index_mode"] = DEF_INDEX_MODE;
        if ($site_conf["index_mode"] == "link")
        {
            $l_path = NULL;
            if (!empty($site_conf["link_path"]))
                $l_path = $site_conf["link_path"];
            /* 檢查 link_path 最前面不可以是 / 內容也不可以有 .. 也必須符合路徑格式,也必須是存在的目錄,若有發現不合法內容就不導向到 link_path 位置 */
            if ((!empty($l_path)) && (substr($l_path, 0, 1) !== '/') && (strstr($l_path, "..") == false) && (!preg_match(FORMAT_FAIL_PATH_NAME, $l_path)) && (is_dir($site_dir.$l_path)))
            {
                /* 導向到 link_path 位置 */
                header("Location: ".SITE_URL.$path[1]."/$l_path".$arg);
                exit;
            }
            /* 若沒設定 link_path 或 link_path 不存在或不符合路徑格式就抓網站 menu 的第一個 url 當成預設首頁目錄,直接導向 */
            $menu = get_subsite_menu($page_dir, $site_acn);
            header("Location: ".$menu[0]["url"].$arg);
            exit;
        }
    }

    $page_path = WEB_ROOT_PATH.$page_url;
    if (isset($_REQUEST["random_path"]))
    {
        if (is_dir($page_path))
            $page_url_chk = str_replace("//", "/", $page_path."/");
        else
            $page_url_chk = substr($page_path, 0, strrpos($page_path, "/")+1);

        session_start();
        $_SESSION["random_path"] = $_REQUEST["random_path"];
        session_write_close();
    }

    /* 檢查是否為目錄或 HTML 檔或 .url 檔,若不是就顯示(下載)檔案 */
    /* 2014/10/30 修改,增加檢查 TEXT 檔 */
    /* 2015/7/1 修改,增加檢查 mode 是否為 download,若是也改用 show_file */
    $fe = strtolower(substr($page_path, strrpos($page_path, ".")));
    if ((!is_dir($page_path)) && ((($fe_type[$fe] != HTML_TYPE) && ($fe != ".url") && ($fe_type[$fe] != TEXT_TYPE)) || ($mode == "get_force") || ($mode == "download")))
    {
        $status = show_file($page_url, $mode, $start, $fe);
        if ($status != true)
            die($status);
        exit;
    }

    /* 若是 .url 檔就 call show_url() */
    if ($fe == ".url")
    {
        /* 2013/8/29 修改,若 mode=download 就改 call show_file */
        if ($mode == "download")
            $status = show_file($page_url, $mode, $start, $fe);
        else
            $status = show_url($page_url);
        if ($status !== true)
            die($status);
        exit;
    }

    /* 先取出 .nuweb_dir_set 設定資料 */
    $dir_set = get_dir_set($page_dir, str_replace($page_dir, "", $page_path));

    /* 預設用 simple 模式取得 page_info */
    $type = TYPE_SIMPLE;
    /* 若 dir_set["def_frame"] 是 NO,就用 super_simple 模式取得 page_info */
    if ($dir_set["def_frame"] === NO)
        $type = TYPE_SUPER_SIMPLE;
    /* 若 dir_set["type"] 是 OokonStorage (網路硬碟),就用 only_dir 模式取得 page_info */
    if ($dir_set["type"] === "OokonStorage")
        $type = TYPE_ONLY_DIR;

    if(is_dir($page_path) && substr($page_path, strlen($page_path) - 1) != "/")
    {
        $page_path .= "/";
        $page_url .= "/";
    }
    /* 取得 page 的相關資料 */
    $output = get_page_info($page_url, $type, $sort_by, $sort_mode);
    if (!is_array($output))
        err_page(404);

    /* 檢查是否為功能目錄,若是就直接由功能目錄顯示 */
    if ($output["fun_dir"] == true)
    {
        $fun_url = str_replace("//", "/", "$page_url/index.php");
        header("Location: $fun_url");
        exit;
    }
    if (!isset($output['random_path']))
        $output['random_path'] = "";

    /* 2013/5/23 修改,若不在網路硬碟目錄,就檢查是否有瀏覽權限,若在網路硬碟目錄中,就由網路硬碟自行管控權限 */
    if (is_Driver($page_url) !== true)
    {
        switch($output["b_right"])
        {
            case PASS:
                break;
            case DENY_PWD:
                $template_file = TEMPLATE_DIR."show_login.tpl.$lang";
                $tpl = new TemplateLib($template_file);
                $tpl->newBlock("PWD_BLOCK");
                $tpl->assign("path_url", $page_url);
                $tpl->assign("lang", $lang);
                $tpl->printToScreen();
                exit;
            case DENY_FORBIDDEN:
                /* 2015/2/11 修改,若無瀏覽權限但有上傳權限,不可顯示 403 錯誤,仍要輸出畫面(但沒有顯示內容) */
                if ($output["u_right"] == PASS)
                    break;
                err_page(403);
                exit;
            case DENY_COOKIE:
                $template_file = TEMPLATE_DIR."show_login.tpl.$lang";
                $tpl = new TemplateLib($template_file);
                $tpl->newBlock("LOGIN_BLOCK");
                $tpl->assign("login_url", SITE_PROG_URL."login.php");
                $tpl->printToScreen();
                exit;
        }
    }

    $lang = $output["lang"];
    if (empty($lang))
        $lang = DEF_LANG;
    /* 記錄 Click log */
    write_click_log($page_url);

    /* 20130903 新增,檢查若是 Mobile 裝置,且 tpl_mode 為 OokonStorage 且 OokonStorageMobile.tpl.cht 已存在,tpl_mode 就改設為 OokonStorageMobile */
    //$driver_mobile_tpl = TEMPLATE_DIR.DRIVER_DIR_TYPE."Mobile.tpl.$lang";
    //if ((rs_is_mobile() == true) && ($output["dir_set"]["tpl_mode"] == DRIVER_DIR_TYPE) && (file_exists($driver_mobile_tpl)))
    //    $output["dir_set"]["tpl_mode"] = DRIVER_DIR_TYPE."Mobile";

	// 使用外掛 Lib function 
	if ($mode != "def" && isset($dir_set["lib"])) {
		$f_lib = $dir_set["lib"];
		if (file_exists($f_lib)) {
			require_once($f_lib);
			if (function_exists("ext_show_page")) {
				ext_show_page($output);
				exit;
			}
		}
	}

    /* 若 dir_set["def_frame"] 是 NO 代表不使用預設框架,就直接將取得的 page_info 資料傳入樣版即可 */
    if ($mode != "def" && $dir_set["def_frame"] === NO)
    {
        $template_file = TEMPLATE_DIR.$output["dir_set"]["tpl_mode"].".tpl.$lang";
        $tpl = new TemplateLib($template_file);
        /* 2014/12/17 新增,若是手機或平板就顯示 MOBILE 區塊,否則就顯示 PC 區塊 */
        /* 2015/1/29 修改,只檢查 Android */
        //if (b_is_android() == true)
        if (b_is_mobile() == true)
            $tpl->newBlock("MOBILE");
        else
            $tpl->newBlock("PC");
        $tpl->assignGlobal("page_info", json_encode($output));
        assign_global($tpl, $output);
        $tpl->printToScreen();
        exit;
    }

    /* 讀取樣版檔 */
    $template_file = TEMPLATE_DIR."frame.tpl.$lang";
    $tpl = new TemplateLib($template_file);
    $tpl->assignGlobal("show_page_prog", $PHP_SELF);
    /* 2014/12/17 新增,若是手機或平板就顯示 MOBILE 區塊,否則就顯示 PC 區塊 */
    /* 2015/1/29 修改,只檢查 Android */
    //if (b_is_mobile() == true)
    if (b_is_android() == true)
        $tpl->newBlock("MOBILE");
    else
        $tpl->newBlock("PC");

    /* 設定並顯示內容 */
    assign_global($tpl, $output);
    block_header($tpl, $output);
    block_menu($tpl, $output, "MENU_".$output["dir_set"]["menu_place"]);
    block_function_menu($tpl, $output);
    block_path($tpl, $output);
    block_submenu($tpl, $output);
    block_share($tpl, $output);
    block_index_mode($tpl, $output);
    block_dir_content($tpl, $output);
    block_user_comment($tpl, $output);
    block_footer($tpl, $output);
    $tpl->printToScreen();

    /************/
    /* 函 數 區 */
    /************/

    /* 顯示連結檔,redirect 到所設定的 url 位置 */
    function show_url($page_url)
    {
        Global $page_path, $site_path, $site_acn, $file_path, $page_dir;

        /* 將 file_path 分離出 path_name & file_name */
        $n = strrpos($file_path, "/");
        $path_name = substr($file_path, 0, $n);
        $file_name = substr($file_path, $n+1);

        /* 檢查是否有瀏覽權限 */
        if (chk_browse_right(WEB_PAGE_DIR, $file_path) != PASS)
            return ERR_NO_PERMISSION;

        /* 記錄 Click log*/
        write_click_log($page_url);

        /* 取得 url 檔內容,並 redirect 到所設定的 url 位置 */
        /* 2015/8/14 修改,檢查 url 若開頭不是 / 或 http:// 或 https:// 就自動在前面加上 http:// */
        $conf = read_conf($page_path);
        $url = $conf["URL"];
        if (($url[0] !== "/") && (substr($url, 0, 7) !== "http://") && (substr($url, 0, 8) !== "https://"))
            $url = "http://$url";
        header("Location: ".$url);
        exit;
    }

    /* 顯示(下載)檔案 */
    function show_file($page_url, $mode, $start, $fe="")
    {
        Global $PHP_SELF, $page_path, $site_path, $site_acn, $file_path, $page_dir, $content_type, $fe_type;

        /* 將 file_path 分離出 path_name & file_name */
        $n = strrpos($file_path, "/");
        $path_name = substr($file_path, 0, $n);
        $file_name = substr($file_path, $n+1);

        /* 檢查是否有瀏覽權限 */
        if (chk_browse_right(WEB_PAGE_DIR, $file_path) != PASS)
            return ERR_NO_PERMISSION;

        /* 記錄 Click log (要過濾掉縮圖與系統檔) */
        if ((strstr($page_url, TN_FE_NAME) == false) && (strstr($page_url, NUWEB_SYS_FILE) == false))
            write_click_log($page_url);

        /* 取出真實檔名 */
        $real_file_name = get_file_name(WEB_PAGE_DIR, $file_path);
        //$real_file_md5 = get_file_md5(WEB_PAGE_DIR, $file_path);
        /* 2015/1/8 新增,檢查 real_file_name 的副檔名是否與 file_name 的副檔名相同,若不同代表是附屬檔,需再將附屬檔副檔名加到 real_file_name 後面 */
        /* 2015/7/1 修改,若 file_name 的副檔名是 .txt 就不是附屬檔,而是強制轉換副檔名 (如 .php .pl .sh 等檔案),因此不必另外加上副檔名 */
        $r_fe = strtolower(substr($real_file_name, strrpos($real_file_name, ".")));
        if (($r_fe !== $fe) && ($fe !== ".txt"))
            $real_file_name .= $fe;

        if ($mode == "audio_play")
        {
            /* 檢查是否為可線上播放的 audio 檔案 (.mp3 .wav .wma) */
            if (($fe != ".mp3") && ($fe != ".wav") && ($fe != ".wma"))
                return ERR_NO_PLAY;
            $play_url = $page_url;
            $content = implode("", @file(AUDIO_PLAT_TEMPLATE));
            $content = str_replace("{play_url}", $play_url, $content);
            $content = str_replace("{file_name}", $real_file_name, $content);
            echo $content;
            exit;
        }
		
		
		
		// whee 由 ODB 輸出檔案
		$bDownload = $mode == "download";
		if (odb_api_print_show_page($page_path, $bDownload)){
			exit;
		}
		
		

        /* 檢查瀏覽器是否為 IE, 是就將 real_file_name 進行 rawurlencode */
        $buf = explode(";", $_SERVER["HTTP_USER_AGENT"]);
        list($brow, $brow_ver) = explode(" ", trim($buf[1]));
		$bIE11 = strpos($_SERVER["HTTP_USER_AGENT"], "rv:11.0");
        if ($brow == "MSIE" || $bIE11 !== false)
           $show_file_name = rawurlencode($real_file_name);
        else
           $show_file_name = $real_file_name;

        $bPartial = !empty($_SERVER["HTTP_RANGE"]);
        if ($bPartial)
            header("HTTP/1.1 206 Partial Content");

        /* 如果 mode=download 或無法由副檔名找到相對應的 Content_type,就變強制下載 */
        if (($mode == "download") || (empty($content_type[$fe])))
        {
            if (empty($content_type[$fe]))
                header("Content-type: application/force-download");
            else
                header("Content-type: ".$content_type[$fe]);
            header('Content-Disposition: attachment; filename="'.$show_file_name.'"');
            header("Content-Transfer-Encoding: binary");
            header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($page_path))." GMT");
        }
        else
        {
            if($fe_type[$fe] == VIDEO_TYPE)
                header("Accept-Ranges: bytes");
            header("Content-type: ".$content_type[$fe]);
            header('Content-Disposition: inline; filename="'.$show_file_name.'"');
        }

        $fsize = real_filesize($page_path);
        if ($bPartial)
        {
            list($unit, $v) = explode("=", $_SERVER["HTTP_RANGE"], 2);
            list($Pos, $End) = explode("-", $v);
            if (empty($End))
                $End = $fsize - 1;
            $size = $End - $Pos + 1;
            header("Content-Length: $size");
            header("Content-Range: bytes {$Pos}-{$End}/{$fsize}");
            readfile_chunked($page_path, true, $Pos, $End);
        }
        else
        {
            $size = $fsize - $start;
            header("Content-Length: $size");
            /* 若 start > 0 且是 FLV 檔案,要輸出 FLV file format header */
            if (($start > 0) && ($fe == ".flv"))
                echo "FLV".pack("CCNN", 1, 1, 9, 9);
            readfile_chunked($page_path, true, $start);
        }
    }
?>
