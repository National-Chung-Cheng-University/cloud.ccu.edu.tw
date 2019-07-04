<?php
    $session_id = session_id();
    if(empty($session_id)){
        session_cache_expire(30);
        session_start();
        $_SESSION["commit_id"] = session_id();
        session_write_close();
    }
    require_once("/data/HTTPD/htdocs/tools/public_lib.php");
    require_once("/data/HTTPD/htdocs/API/TemplateLib.php");
    //require_once("/data/HTTPD/htdocs/tools/page/define_msg.php");
    require_once("/data/HTTPD/htdocs/tools/ooki/ooki_rs_lib.php");
	// whee ODB
	require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
    /* 2014/12/17 新增,因要檢查是否為 Mobile 裝置,所以要 require wbase2.php */
    require_once("/data/HTTPD/htdocs/tools/wbase2.php");

    Global $lang, $def_lang, $login_user, $m_right, $u_right, $b_right, $dir_def, $fe_type, $isset_random_path, $php_self;

    $msg_file = WEB_LANG_MSG_DIR."page_msg_$lang.php";
    if (!file_exists($msg_file))
        $msg_file = WEB_LANG_MSG_DIR."page_msg_$def_lang.php";
    require_once($msg_file);

    define("PATH_GAP", " / ");
    define("TEMPLATE_DIR", "/data/HTTPD/htdocs/tools/page/template/");
    define("DEF_TEMPLATE", "directory.tpl");
    define("DEF_LANG", "cht");
    define("SUBDIR_CONTENT_LEN", 32);
    define("FUN_CONTENT", "# NUWEB_FUNCTION_CONTENT #");
    define("SHOW_PAGE_URL", "/tools/page/show_page.php");
    define("SHOW_TN_URL", "/tools/api_get_thumbs.php");
    define("GOOGLE_ANALYTICS_CONF", "/data/Admin/google_analytics.conf");

    define("DEF_PAGE_STYLE", "style01");
    define("TYPE_SUPER_SIMPLE", "super_simple");
    define("TYPE_SIMPLE", "simple");
    define("TYPE_COMPLETE", "complete");
    define("TYPE_ONLY_DIR", "only_dir");
    define("MAX_OUTPUT_SUB_ITEM", 10);
    define("FILE_TITLE_LEN", 8);

    $php_self = $_SERVER["SCRIPT_NAME"];

    /* 若有傳入 random_path 參數時,才會設定 isset_random_path (為了檢查 URL 是否須加上 random_path 參數) */
    $isset_random_path = false;
    if (isset($_REQUEST["random_path"]))
        $isset_random_path = true;

    //$ssn_acn = $_COOKIE["ssn_acn"];
    //if (!empty($ssn_acn))
    //    list($uid, $uacn) = explode(":", strtolower($ssn_acn));
    /* 取得 login user 資料 */
    //$login_user = get_login_user();

    $err_mode = $_REQUEST["err_mode"];

    /************/
    /* 函 數 區 */
    /************/

    function err_page($nErr)
    {
        Global $err_mode;

        switch($nErr)
        {
            case 400:
                if ($err_mode == "code")
                    header("HTTP/1.1 400 Bad Request");
                else
                    header("Location: /tools/NUError/err400.html\r\n");
                break;
            case 401:
                if ($err_mode == "code")
                    header("HTTP/1.1 401 Unauthorized");
                else
                    header("Location: /tools/NUError/err401.html\r\n");
                break;
            case 403:
                if ($err_mode == "code")
                    header("HTTP/1.1 403 Forbidden");
                else
                    header("Location: /tools/NUError/err403.html\r\n");
                break;
            case 404:
                if ($err_mode == "code")
                    header("HTTP/1.1 404 Not Found");
                else
                    header("Location: /tools/NUError/err404.html\r\n");
                break;
        }
        exit;
    }

    /* 檢查 page_url */
    function chk_page_url($page_url)
    {
        Global $page_path, $site_path, $site_acn, $file_path, $page_dir;

        /* 檢查 page_url 是否存在 */
        $page_path = WEB_ROOT_PATH.$page_url;
        if (!file_exists($page_path))
        {
            /* 2015/2/25 修改,檢查 page_url 是否為縮圖檔,若是且不存在就直接建立縮圖檔 */
            $is_tn = false;
            $l1 = strlen(TN_FE_NAME);
            $l2 = strlen(MED_TN_FE_NAME);
            $l22 = strlen(MED2_TN_FE_NAME);
            $l3 = strlen(BIG_TN_FE_NAME);
            if (substr($page_path, -$l1) == TN_FE_NAME)
            {
                $is_tn = true;
                if (substr($page_path, -$l3) == BIG_TN_FE_NAME)
                    extract_tn(substr($page_path, 0, -$l3), BIG_TN_SIZE, BIG_TN_FE_NAME);
                else if (substr($page_path, -$l2) == MED_TN_FE_NAME)
                    extract_tn(substr($page_path, 0, -$l2), MED_TN_SIZE, MED_TN_FE_NAME);
                else if (substr($page_path, -$l22) == MED2_TN_FE_NAME)
                    extract_tn(substr($page_path, 0, -$l22), MED2_TN_SIZE, MED2_TN_FE_NAME);
                else
                    extract_tn(substr($page_path, 0, -$l1));
            }

            /* 若不是縮圖檔或建立縮圖失敗就回傳錯誤訊息 */
            if (($is_tn == false) || (!file_exists($page_path)))
                return ERR_PAGE_URL;
        }

        /* 必須先去 require_once 網站的 init.php */
        $path = explode("/", $page_url);
        $site_path = $path[1];
        $site_acn = $path[2];
        $init_prog = WEB_ROOT_PATH."/$site_path/init.php";
        if (!file_exists($init_prog))
            return ERR_PAGE_URL;
        require_once($init_prog);
        $page_dir = WEB_PAGE_DIR;

        /* 檢查 page_path 位置是否合法 */
        if (strstr($page_path, $page_dir) !== $page_path)
            return ERR_PAGE_URL;
        $file_path = substr($page_path, strlen($page_dir));

        return true;
    }

    /* 取得要輸出的 page 資料 */
    function get_page_info($page_url, $type=TYPE_SIMPLE, $sort_by="", $sort_mode="A", $offset = -1, $req_count = 0)
    {
        Global $login_user, $page_path, $site_path, $site_acn, $file_path, $page_dir, $fe_type, $is_manager, $admin_manager, $php_self, $m_right;

        /* 檢查 page_url */
        if (($status = chk_page_url($page_url)) !== true)
            err_page(404);

        if (($type != TYPE_COMPLETE) && ($type != TYPE_SUPER_SIMPLE) && ($type != TYPE_ONLY_DIR))
            $type = TYPE_SIMPLE;

        /* 2013/8/22 預設 sort_by=time, sort_mode=D */
        if (empty($sort_by))
        {
            /* 2014/4/7 修改,若是目錄且目錄內有 .nuweb_sub_list,就改成預設 sort_by=sub_list */
            $nuweb_sub_list = str_replace("//", "/", $page_path."/".NUWEB_SUB_LIST);
            if ((is_dir($page_path)) && (file_exists($nuweb_sub_list)))
                $sort_by = "sub_list";
            else
                $sort_by = "time";
            $sort_mode = "D";
        }

        /* 若有需 sort 必須設定 sort_mode (A:ASC or D:DESC) */
        if ((!empty($sort_by)) && ($sort_mode != "A"))
            $sort_mode = "D";

        /* 將 file_path 分離出 path_name & file_name */
        if (is_dir($page_path))
        {
            $dir_mode = true;
            $file_name = DEF_HTML_PAGE;
            if (substr($file_path, -1) != '/')
            {
                $path_name = $file_path;
                $page_path .= "/".$file_name;
            }
            else
            {
                $path_name = substr($file_path, 0, -1);
                $page_path .= $file_name;
            }
        }
        else
        {
            $dir_mode = false;
            $n = strrpos($file_path, "/");
            $path_name = substr($file_path, 0, $n);
            $file_name = substr($file_path, $n+1);
            if (!is_dir($page_dir.$path_name))
                return ERR_NOT_DIR;
        }
        $page_url_chk = $page_dir.$path_name."/";

        /* 取出副檔名 */
        $fe = strtolower(substr($file_name, strrpos($file_name, ".")));

        /* 過濾掉 *.php 與 .nuweb_* 與 Site 下的 *.list 的檔案 */
        if (($fe == ".php") || (substr($file_name, 0, 7) == NUWEB_SYS_FILE) || (($fe == ".list") && (empty($path_name))))
            return ERR_FORBIDDEN;

        /* 若是子網站,必須檢查網站狀態 */
        if ($site_path == "Site")
        {
            $status = get_site_status($path_name);
            if ($status != SITE_STATUS_ALLOW)
                return ERR_FORBIDDEN;
        }

        /* 檢查權限 */
        $m_right = chk_manager_right($path_name);
        $u_right = chk_upload_right($page_dir, $path_name);
        $b_right = chk_browse_right($page_dir, $path_name);
        /* 2015/2/10 新增,依據新版權限設定資料檢查 user 權限 */
        $user_right = chk_user_right($page_dir.$path_name);

        /* 取得 CS 帳號 */
        $reg_conf = read_conf(REGISTER_CONFIG);
        $cs_acn = $reg_conf["acn"];

        /* 取得登入的 user 帳號 */
        //if (isset($_COOKIE["ssn_acn"]))
        //    list($uid, $uacn) = explode(":", strtolower($_COOKIE["ssn_acn"]));

        /* 設定部分輸出基本資料 */
        $output["page_url"]  = $page_url;
        $output["site_path"] = $site_path;
        $output["page_dir"]  = $page_dir;
        $output["file_path"] = $file_path;
        $output["path_name"] = $path_name;
        $output["file_name"] = $file_name;
        $output["dir_path"]  = str_replace(WEB_ROOT_PATH."/", "", $page_dir).$path_name;
        $output["show_path"] = block_con_filepath2showpath($page_dir, $file_path);
        session_start();
        if ((isset($_SESSION["random_path"])) && ($_SESSION["random_path"] === undefined))
            unset($_SESSION["random_path"]);
        if ($m_right == PASS)
        {
            $result_url = hidden_url_set($page_url_chk);
            if (isset($result_url["random_path"]))
                $_SESSION["random_path"] = $result_url["random_path"];
            else if (isset($_SESSION["random_path"]))
                unset($_SESSION["random_path"]);
        }
        else if (isset($_REQUEST["random_path"]))
            $_SESSION["random_path"] = $_REQUEST["random_path"];
        if (isset($_SESSION["random_path"]))
            $output["random_path"] = $_SESSION["random_path"];
        else
            $output["random_path"] = "";
        session_write_close();
        $output["fe"] = $fe;
        $output["sys_manager"] = $is_manager;
        $output["admin_manager"] = $admin_manager;
        $output["m_right"] = $m_right;
        $output["u_right"] = $u_right;
        $output["b_right"] = $b_right;
        $output["user_right"] = $user_right;
        $output["site_url"] = SITE_URL;
        $output["site_page_url"] = PAGE_URL;
        $output["site"] = SITE;
        $output["cs_acn"] = $cs_acn;
        $output["user_acn"] = $login_user["acn"];
        $output["user_sun"] = $login_user["sun"];
        $output["owner_acn_ssn"] = get_site_conf($path_name);
        $output["owner_acn_ssn"] = explode(",", $output["owner_acn_ssn"]["owner"]);
        foreach($output["owner_acn_ssn"] as $key => $value)
        {
            if ($value == $login_user["acn"])
                $output["owner_acn_ssn"][$key] = array("ssn"=>$login_user["ssn"], "acn"=>$value);
            else
                $output["owner_acn_ssn"][$key] = array("ssn"=>"", "acn"=>$value);
        }

        /* 2013/5/23 修改,若不在網路硬碟目錄且若無瀏覽權限,就只輸出基本資料 */
        /* 2013/5/23 修改,若不在網路硬碟目錄且若無瀏覽權限與上傳權限時,就只輸出基本資料 */
        //if (($b_right != PASS) && ((is_Driver($page_url) !== true) || ($php_self !== SHOW_PAGE_URL)))
        if (($b_right != PASS) && ($u_right != PASS) && ((is_Driver($page_url) !== true) || ($php_self !== SHOW_PAGE_URL)))
            return $output;

        /* 若有上傳權限就先產生一個 edit_code (提供 edit_api 使用) */
        if ($u_right == PASS)
            $output["edit_code"] = get_edit_code();

        /* 檢查是否有設密碼 */
        //$dir_def = read_nuweb_def($page_dir, $path_name);
        //if (!empty($dir_def["pwd"]))
        //    $output["set_pwd"] = true;
        //else
        //    $output["set_pwd"] = false;
        /* 2015/2/10 修改,不再使用 .nuweb_def,改用新版權限資料,檢查權限時就有檢查是否設定密碼 */
        $output["set_pwd"] = $user_right["set_pwd"];

        /* 若是網頁或目錄 (HTML 檔案),則取得 page 的內容 */
        /* 2015/2/11 修改,必須有瀏覽權限才取得 page 內容 */
        //if ($fe_type[$fe] == HTML_TYPE)
        if (($fe_type[$fe] == HTML_TYPE) && ($b_right == PASS))
        {
            $page_content = implode("", @file($page_path));
            if ($output["page_url"] == "/".SUB_SITE_NAME."/".$site_acn."/")
            {
                $site_conf_file = $page_dir.$site_acn."/".NUWEB_CONF;
                $conf = read_conf($site_conf_file);
                $output["index_mode"] = DEF_INDEX_MODE;
                if (isset($conf["index_mode"]))
                    $output["index_mode"] = $conf["index_mode"];
                /* 2015/8/12 修改,因 DEF_INDEX_MODE 修改成 link,所以這裡檢查不可使用 DEF_INDEX_MODE 要改成 streaming */
                //if ($output["index_mode"] == DEF_INDEX_MODE)
                if ($output["index_mode"] == "streaming")
                    $page_content = implode("", @file(TEMPLATE_DIR."OokonHome_stream.tpl.cht"));
                else if ($output["index_mode"] == "block")
                    $page_content = implode("", @file(TEMPLATE_DIR."OokonHome_block.tpl.cht"));
             }
             $output["page_content"] = ooki_link_video2flv($page_content);
        }

        /* 2014/10/30 新增,檢查若是 TEXT 檔,則取出檔案內容 */
        /* 2015/2/11 修改,必須有瀏覽權限才取得檔案內容 */
        if (($fe_type[$fe] == TEXT_TYPE) && ($b_right == PASS))
            $output["page_content"] = "<pre style=\"white-space: pre-wrap;\">".get_rec_field($page_path, "content")."</pre>";

        /* 取得與網站相關的資料 */
        get_site_info($site_path, $page_dir, $path_name, $output);

        /* 取得 .nuweb_type 設定 */
        $output["dir_type"] = get_nuweb_type($page_dir.$path_name."/");
        /* 2015/4/21 修改,強制將 multimedia 與 page 轉成預設 directory (因為要強制使用 directory 的版型) */
        if (($output["dir_type"] == MULTIMEDIA_DIR_TYPE) || (($output["dir_type"] == PAGE_DIR_TYPE) && (is_site_dir($page_dir.$path_name) == false)))
            $output["dir_type"] = GENERAL_DIR_TYPE;

        /* 依檔案或目錄取出相對應的資料 */
        /* 2015/2/11 修改,必須有瀏覽權限才取出相對應的資料 */
        if ($b_right == PASS)
        {
            if ($dir_mode == true)
            {
                $output["record"] = get_path_rec_field($page_dir, $path_name);
                get_dir_info($page_dir, $path_name, $file_name, $output, $sort_by, $sort_mode, $type, $offset, $req_count);
            }
            else
                $output["record"] = get_path_rec_field($page_dir, $path_name."/".$file_name);
            if (empty($output["record"]["description"]))
                $output["record"]["description"] = get_rec_description($output["record"]);
        }
        /* 取得 header 背景與 Logo 的 URL */
        $bg_img_file = $page_dir.$path_name."/".NUWEB_BG_IMG;
        if (file_exists($bg_img_file))
            $bg_img_url = str_replace(WEB_ROOT_PATH, "", $bg_img_file);
        else
            $bg_img_url = STYLE_URL.$output["style"]."/".DEF_BG_IMG;
        if(is_file($page_url_chk.NUWEB_HEADER))
            $bg_img_url = "/".$output["dir_path"]."/".NUWEB_HEADER;
        $output["bg_img_url"] = $bg_img_url;
        $logo_file = $page_dir.$site_acn."/".NUWEB_LOGO;
        if (file_exists($logo_file))
            $logo_url = str_replace(WEB_ROOT_PATH, "", $logo_file);
        else
            $logo_url = DEF_LOGO_URL;
        $output["logo_url"] = $logo_url;
        return $output;
    }

    /* 取得與網站相關的資料 */
    function get_site_info($site_path, $page_dir, $path_name, &$output)
    {
        /* 檢查是否為子網站 */
        if ($site_path == "Site")
            get_subsite_info($page_dir, $path_name, $output);
        else
            get_website_info($page_dir, $path_name, $output);
    }

    /* 取得子網站相關的資料 */
    function get_subsite_info($page_dir, $path_name, &$output)
    {
        Global $lang, $login_user;

        /* 先取出 site_acn */
        $path = explode("/", $path_name);
        $site_acn = $path[0];

        /* 取得與設定 lang 與 site_name */
        $site_conf_file = $page_dir.$site_acn."/".NUWEB_CONF;
        if (file_exists($site_conf_file))
        {
            $conf = read_conf($site_conf_file);
            if ((isset($conf["lang"])) && (!empty($conf["lang"])))
                $lang = $conf["lang"];
            $site_name = $conf["site_name"];
            $logo_place = $conf["logo_place"];

            /* 2015/10/6 新增,檢查是否為網站的 owner */
            $owner = strtolower($conf["owner"]);
            if ((($login_user["acn"] == $owner) || ($login_user["mail"] == $owner)) && (!empty($login_user["ssn"])) && (!empty($login_user["mail"])) && (!empty($login_user["sun"])))
            {
                /* 整理 login_user 的資料,並檢查是否與網站設定的 owner_info 相同,若不相同就必須更新網站 owner_info 資料 (因 user 可能變更 sun 但網站不知道已變更,所以增加檢查修正功能) */
                $owner_info = $login_user["ssn"].":".$login_user["acn"].":".$login_user["mail"].":".$login_user["sun"];
                if ((!isset($conf["owner_info"])) || ($conf["owner_info"] !== $owner_info))
                {
                    $conf["owner_info"] = $owner_info;
                    update_site_conf($path[0], $conf, "update");
                }
            }
        }
        if (empty($lang))
            $lang = "cht";
        /* 2014/4/23 新增,若 user 有設定語系,就將 lang 改成 user 的語系 */
        //if ((isset($login_user["lang"])) && (!empty($login_user["lang"])))
        //    $lang = $login_user["lang"];
        /* 預設 logo_place 為 DEF_LOGO_PLACE (M: logo 在 header 中間, T: logo 在 header 上面) */
        if (empty($logo_place))
            $logo_place = DEF_LOGO_PLACE;
        $output["lang"] = $lang;
        $output["site_name"] = $site_name;
        $output["logo_place"] = $logo_place;

        /* 檢查是否有 .nuweb_dir_set 若有就取出設定資料 */
        $output["dir_set"] = get_dir_set($page_dir, $path_name);

        /* 取得子網站的 menu 資料 */
        $output["site_acn"] = $site_acn;
        $output["site_prog_url"] = SITE_PROG_URL;
        $output["menu"] = get_subsite_menu($page_dir, $site_acn);
        if (empty($output["dir_set"]["style"]))
            $output["dir_set"]["style"] = DEF_PAGE_STYLE;
        $output["style"] = $output["dir_set"]["style"];
    }

    /* 取得內外部網站相關的資料 */
    function get_website_info($page_dir, $path_name, &$output)
    {
        Global $lang, $company;

        /* 取得與設定 lang 與 site_name */
        $output["lang"] = $lang;
        $output["site_name"] = $company;

        /* 檢查是否有 .nuweb_dir_set 若有就取出設定資料 */
        $output["dir_set"] = get_dir_set($page_dir, $path_name);

        $output["site_prog_url"] = SITE_URL;

        /* 取得內外部網站的 menu 資料 */
        $output["menu"] = get_website_menu();

        /* 取得 style 設定 */
        $style_config = ADMIN_DIR.$output["site"]."/".STYLE_CONFIG;
        /* 取出 style.conf 的設定內容 (包括: class, style) */
        if (file_exists($style_config))
            list($output["style_class"], $output["style"]) = explode("\t", trim(implode("", @file($style_config))));
        if (empty($output["style"]))
            $output["style"] = DEF_PAGE_STYLE;
    }

    /* 取得子網站 menu */
    function get_subsite_menu($page_dir, $site_acn)
    {
        Global $m_right, $fun_set_conf;

        /* 尋找第一層目錄內是否有 .nuweb_menu 若有就取出來當 menu */
        $n = 0;
        $site_dir = $page_dir.$site_acn;
        $menu_file = $site_dir."/".NUWEB_MENU;
        if (file_exists($menu_file))
        {
            $menu_list = @file($menu_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $menu_cnt = count($menu_list);
            for ($i = 0; $i < $menu_cnt; $i++)
            {
                $menu_list[$i] = trim($menu_list[$i]);
                /* 檢查 menu list 中是否有重複的目錄 */
                $duplicate = false;
                for ($j = 0; $j < $i-1; $j++)
                {
                    $menu_list[$j] = trim($menu_list[$j]);
                    if ($menu_list[$i] == $menu_list[$j])
                    {
                        $duplicate = true;
                        break;
                    }
                }

                /* 如果有重複的目錄或是 Driver 目錄就直接跳過 */
                /* 2015/3/19 修改,若是系統目錄也跳過 */
                /* 2015/7/28 修改,Friend 目錄也跳過 */
                if (($duplicate == true) || ($menu_list[$i] == DRIVER_DIR_NAME) || (strstr($menu_list[$i], NUWEB_SYS_FILE) !== false) || ($menu_list[$i] == FRIEND_DIR_NAME))
                    continue;

                /* 若目錄不存在也直接跳過 */
                $menu_path = $site_acn."/".$menu_list[$i];
                if (!is_dir($page_dir.$menu_path))
                    continue;

                /* 2014/10/24 新增,若沒有管理權限且設定隱藏也直接跳過 */
                if (($m_right != PASS) && (chk_hidden($page_dir.$menu_path) == true))
                    continue;

                /* 2015/12/16 新增,若是活動目錄 (Events) 且功能設定項目 (fun_set_conf) 設定關閉 event 就代表不使用活動功能目錄,就直接跳過 */
                if (($menu_list[$i] == EVENT_DIR_NAME) && (isset($fun_set_conf["event"])) && ($fun_set_conf["event"] == NO))
                    continue;

                /* 2015/12/16 新增,若是行事曆目錄 (Calendar) 且功能設定項目 (fun_set_conf) 設定關閉 calendar 就代表不使用行事曆功能目錄,就直接跳過 */
                if (($menu_list[$i] == CALENDAR_DIR_NAME) && (isset($fun_set_conf["calendar"])) && ($fun_set_conf["calendar"] == NO))
                    continue;

                /* 2015/12/16 新增,若是成員目錄 (Members) 且功能設定項目 (fun_set_conf) 設定關閉 member_dir 就代表不使用成員目錄,就直接跳過 */
                if (($menu_list[$i] == MEMBER_DIR_NAME) && (isset($fun_set_conf["member_dir"])) && ($fun_set_conf["member_dir"] == NO))
                    continue;

                $menu[$n]["name"] = get_file_name($page_dir, $menu_path);
                $url = SITE_URL.$menu_path."/";
                $menu[$n]["url"] = $url;
                /* 2014/10/27 新增,設定 tag 資料 */
                $menu[$n]["tag"] = get_rec_field($page_dir.$menu_path, "tag");
                $n++;
            }
        }
        else
        {
            /* 取得目錄內第一層子目錄當成 menu */
            $handle = opendir($site_dir);
            $n = 0;
            while ($sub_dir = readdir($handle))
            {
                /* 只取出子目錄,並過濾掉 . & .. & .nuweb_* & symlink */
                $sub_path = $site_dir."/".$sub_dir;
                if ((!is_dir($sub_path)) || ($sub_dir == ".") || ($sub_dir == "..") || (substr($sub_dir, 0, 7) == NUWEB_SYS_FILE) || (is_link($sdir_path)))
                    continue;

                /* 2013/6/26 新增,若是 Driver 目錄也跳過不顯示 */
                /* 2015/7/28 修改,Friend 目錄也跳過 */
                if (($sub_dir == DRIVER_DIR_NAME) || ($sub_dir == FRIEND_DIR_NAME))
                    continue;

                /* 2014/10/24 新增,若沒有管理權限且設定隱藏也直接跳過 */
                if (($m_right != PASS) && (chk_hidden($sub_path) == true))
                    continue;

                /* 2015/12/16 新增,若是活動目錄 (Events) 且功能設定項目 (fun_set_conf) 設定關閉 event 就代表不使用活動功能目錄,就直接跳過 */
                if (($sub_dir == EVENT_DIR_NAME) && (isset($fun_set_conf["event"])) && ($fun_set_conf["event"] == NO))
                    continue;

                /* 2015/12/16 新增,若是行事曆目錄 (Calendar) 且功能設定項目 (fun_set_conf) 設定關閉 calendar 就代表不使用行事曆功能目錄,就直接跳過 */
                if (($sub_dir == CALENDAR_DIR_NAME) && (isset($fun_set_conf["calendar"])) && ($fun_set_conf["calendar"] == NO))
                    continue;

                /* 2015/12/16 新增,若是成員目錄 (Members) 且功能設定項目 (fun_set_conf) 設定關閉 member_dir 就代表不使用成員目錄,就直接跳過 */
                if (($sub_dir == MEMBER_DIR_NAME) && (isset($fun_set_conf["member_dir"])) && ($fun_set_conf["member_dir"] == NO))
                    continue;

                /* 取出目錄名稱,並過濾掉 *.files 目錄 */
                $dir_name = get_file_name($page_dir, $site_acn."/".$sub_dir);
                if (substr($dir_name, -6) == ".files")
                    continue;
                $menu[$n]["name"] = $dir_name;
                $menu[$n]["url"] = SITE_URL.$site_acn."/".$sub_dir;
                $menu[$n]["time"] = filemtime($sub_path);
                /* 2014/10/27 新增,設定 tag 資料 */
                $menu[$n]["tag"] = get_rec_field($sub_path, "tag");
                $n++;
            }
            sort_array($menu, "time");
        }

        return $menu;
    }

    /* 取得內外部網站 menu */
    function get_website_menu()
    {
        Global $setup_conf;

        /* 取得 menu 資料 */
        $n = 0;
        if (file_exists(SITE_DATA_DIR.SITE_MENU_FILE))
        {
            $fp = fopen(SITE_DATA_DIR.SITE_MENU_FILE, "r");
            while(($buf = fgets($fp, MAX_BUFFER_LEN)) != false)
            {
                list($name, $url, $select, $target) = explode("\t", trim($buf));
                if (empty($name) || empty($url))
                    continue;
                if ($select == "T")
                {
                    if (strstr($url, PAGE_URL) !== $url)
                    {
                        if (($url[0] != '/') && (strstr($url, "http://") != $url))
                        {
                            if ((is_dir(SITE_WEB_DIR.$url)) || (is_file(SITE_WEB_DIR.$url)))
                                $url = SITE_URL.$url;
                            else
                                $url = PAGE_URL.$url;
                        }
                    }
                    $menu[$n]["name"] = $name;
                    $menu[$n]["url"] = $url;
                    $menu[$n]["link_target"] = $target;
                    $n++;
                }
            }
            fclose($fp);
        }

        return $menu;
    }

    /* 取得目錄內所有檔案的 record */
    function get_dir_sublist_rec($dir_url)
    {
        Global $m_right;

        if (substr($dir_url, -1) !== "/");
            $dir_url .= "/";
        /* 取得此目錄 index 中所有資料 */
        $rBegin = str_replace("\n", "\\n", REC_START.REC_BEGIN_PATTERN);
        $index_file = WEB_ROOT_PATH.$dir_url.NUWEB_REC_PATH.DIR_INDEX."/current";
        if ($m_right == PASS)
            $cmd = SEARCH_BIN_DIR."dbman -recbeg \"$rBegin\" -flag \"@_f:Normal\" -sort $index_file";
        else
            $cmd = SEARCH_BIN_DIR."dbman -recbeg \"$rBegin\" -tag \"-@tag:".HIDDEN_TAG."\" -flag \"@_f:Normal\" -sort $index_file";
        $fp = popen($cmd, "r");
        $rec_content = "";
        while($buf = fgets($fp, MAX_BUFFER_LEN))
            $rec_content .= $buf;
        pclose($fp);
        $rec_content = str_replace("@\n@\n", "@\n", $rec_content);
        $rec_content = str_replace("\n@@", "\n@", $rec_content);
        $match_cnt = get_match_cnt($rec_content);
        if (($match_cnt == 0) || (empty($match_cnt)))
            return false;
        $recs = recbuf2array(explode("\n", $rec_content));
        return $recs;
    }

    /* 取得目錄相關資料 */
    function get_dir_info($page_dir, $path_name, $file_name, &$output, $sort_by, $sort_mode, $type=TYPE_SIMPLE, $offset=-1, $req_count=0)
    {
        Global $fe_type;

        if ($type == TYPE_SUPER_SIMPLE)
            return;

        /* offset:從第幾筆開始輸出(-1代表取出全部), req_cont:要輸出幾筆 */
        $offset = intval($offset);
        $req_count = intval($req_count);
        $output["req_count"] = $req_count;
        if ($offset > -1)
            $req_count += $offset;

        $dir_url = str_replace("//", "/", $output["page_url"]."/");

        /* 取出 submenu */
        $submenu_list = get_submenu_list($page_dir, $path_name);
        if ($submenu_list !== false)
            $output["submenu"] = $submenu_list;

        /* 檢查是否為功能目錄 (有 index.php),若是就不必再往下處理 */ 
        if (chk_function_dir($page_dir, $path_name))
        {
            $output["fun_dir"] = true;
            return;
        }

        /* 檢查是否需要 sort */
        if (!empty($sort_by))
        {
            $output["sort_by"] = $sort_by;
            $output["sort_mode"] = $sort_mode;
        }

        if ($type == TYPE_ONLY_DIR)
            return;

        /* 取得此目錄 index 中所有資料 */
        //$rBegin = str_replace("\n", "\\n", REC_START.REC_BEGIN_PATTERN);
        //$index_file = WEB_ROOT_PATH.$dir_url.NUWEB_REC_PATH.DIR_INDEX."/current";
        //$cmd = SEARCH_BIN_DIR."dbman -recbeg \"$rBegin\" -flag \"@_f:Normal\" -sort $index_file";
        //$fp = popen($cmd, "r");
        //$rec_content = "";
        //while($buf = fgets($fp, MAX_BUFFER_LEN))
        //    $rec_content .= $buf;
        //pclose($fp);
        //$rec_content = str_replace("@\n@\n", "@\n", $rec_content);
        //$rec_content = str_replace("\n@@", "\n@", $rec_content);
        //$match_cnt = get_match_cnt($rec_content);
        //if (($match_cnt == 0) || (empty($match_cnt)))
        //    return;
        //$recs = recbuf2array(explode("\n", $rec_content));
        $recs = get_dir_sublist_rec($dir_url);
        if ($recs == false)
            $match_cnt = 0;
        else
            $match_cnt = count($recs);

        /* 2013/8/22 新增將取得的資料依 sort_by 與 sort_mode 設定進行 sort */
        /* 2014/4/7 修改,若 sort_by=sub_list ,代表要依據 .nuweb_sub_list 順序顯示,就 call sort_sub_list 處理 (sort_by=sub_list 時不需 sort_mode 參數) */
        if ($sort_by == "sub_list")
            sort_sub_list($recs, $dir_url);
        else
            sort_array($recs, $sort_by, $sort_mode);

        /* 整理要輸出的相關檔案與子目錄資料 */
        $dir_cnt = 0;
        $file_cnt = 0;
        $ignoreo = 0;
        for ($i = 0; $i < $match_cnt; $i++)
        {
            if (($offset >= 0) && ($ignoreo >= $req_count))
                break;
            if ($ignoreo < $offset)
            {
                $ignoreo++;
                continue;
            }
            $ignoreo++;

            clean_rec($recs[$i]);
            if (empty($recs[$i]))
                continue;
            $page_path = $path_name."/".$recs[$i]["page_name"];
            $sub_file = $recs[$i]["page_name"];
            if ($recs[$i]["type"] == "Directory")
            {
                /* 過濾掉 *.files 與 .sync 目錄 */
                if ((substr($recs[$i]["filename"], -6) == ".files") || ($recs[$i]["filename"] == SYNC_DIR))
                    continue;

                /* 整理子目錄的相關資料 */
                $output["sub_dir"][$dir_cnt]["path"] = $sub_file;
                $output["sub_dir"][$dir_cnt]["url"] = $dir_url.$recs[$i]["page_name"];
                $output["sub_dir"][$dir_cnt]["time"] = $recs[$i]["mtime"];
                $output["sub_dir"][$dir_cnt]["name"] = $recs[$i]["filename"];
                $output["sub_dir"][$dir_cnt]["tag"] = $recs[$i]["tag"];
                if (isset($recs[$i]["first_tn"]))
                    $output["sub_dir"][$dir_cnt]["first_tn_url"] = $dir_url.$recs[$i]["first_tn"];

                /* 整理 record 資料,如果 type = complete 就直接抓取 record 檔內容,否則直接用搜尋結果的 record */
                if ($type == TYPE_COMPLETE)
                    $output["sub_dir"][$dir_cnt]["record"] = get_path_rec_field($page_dir, $page_path);
                else
                    $output["sub_dir"][$dir_cnt]["record"] = $recs[$i];

                /* 取得子目錄的 .nuweb_dir_set 設定資料 */
                $subdir_set = get_dir_set($page_dir, $page_path);
                $output["sub_dir"][$dir_cnt]["dir_type"] = $subdir_set["type"];
                $dir_cnt++;
            }
            else
            {
                /* 2013/10/28 新增,若是圖檔就檢查縮圖是否存在,若不存在就建立 */
                if ($fe_type[$recs[$i]["fe"]] === IMAGE_TYPE)
                {
                    $file_path = $page_dir.$page_path;
                    /* 若小縮圖不存在就建立小縮圖,並更新 record file */
                    if (!file_exists($file_path.TN_FE_NAME))
                    {
                        extract_tn($file_path);
                        if (file_exists($file_path.TN_FE_NAME))
                        {
                            $tmp_rec["thumbs"] = $recs[$i]["page_name"].TN_FE_NAME;
                            $recs[$i]["thumbs"] = $tmp_rec["thumbs"];
                            $rec_file = get_file_rec_path($file_path);
                            if ($rec_file !== false)
                                update_rec_file($rec_file, $tmp_rec);
                        }
                    }
                    /* 若中縮圖不存在就建立中縮圖 */
                    if (!file_exists($file_path.MED_TN_FE_NAME))
                        extract_tn($file_path, MED_TN_SIZE, MED_TN_FE_NAME);
                    /* 若中縮圖2不存在就建立中縮圖2 */
                    if (!file_exists($file_path.MED2_TN_FE_NAME))
                        extract_tn($file_path, MED2_TN_SIZE, MED2_TN_FE_NAME);
                    /* 若大縮圖不存在就建立大縮圖 */
                    if (!file_exists($file_path.BIG_TN_FE_NAME))
                        extract_tn($file_path, BIG_TN_SIZE, BIG_TN_FE_NAME);
                }

                /* 整理檔案的相關資料 */
                $output["sub_file"][$file_cnt]["path"] = $sub_file;
                $output["sub_file"][$file_cnt]["url"] = $dir_url.$recs[$i]["page_name"];
                $output["sub_file"][$file_cnt]["size"] = $recs[$i]["size"];
                $output["sub_file"][$file_cnt]["time"] = $recs[$i]["mtime"];
                $output["sub_file"][$file_cnt]["name"] = $recs[$i]["filename"];
                $output["sub_file"][$file_cnt]["title"] = $recs[$i]["title"];
                $output["sub_file"][$file_cnt]["tag"] = $recs[$i]["tag"];
                $output["sub_file"][$file_cnt]["describe"] = $recs[$i]["description"];
                $output["sub_file"][$file_cnt]["fe"] = $recs[$i]["fe"];
                if ((isset($recs[$i]["images"])) && (!empty($recs[$i]["images"])))
                    $output["sub_file"][$file_cnt]["images"] = $recs[$i]["images"];
                /* 2014/3/31 修改,縮圖 url 改由 /tools/api_get_thumbs.php 顯示 */
                $f_url = $output["sub_file"][$file_cnt]["url"];
                $f_type = $recs[$i]["type"];
                if ((isset($recs[$i]["thumbs"])) && (!empty($recs[$i]["thumbs"])))
                    $output["sub_file"][$file_cnt]["tn_img_url"] = SHOW_TN_URL."?page_url=$f_url&type=$f_type";
                //    $output["sub_file"][$file_cnt]["tn_img_url"] = $dir_url.$recs[$i]["thumbs"];
                if ((isset($recs[$i]["med_tn_img"])) && (!empty($recs[$i]["med_tn_img"])))
                    $output["sub_file"][$file_cnt]["tn_img_url"] = SHOW_TN_URL."?page_url=$f_url&type=$f_type&size=".MED_TN_SIZE;
                //    $output["sub_file"][$file_cnt]["med_tn_img_url"] = $dir_url.$recs[$i]["med_tn_img"];
                if ((isset($recs[$i]["big_tn_img"])) && (!empty($recs[$i]["big_tn_img"])))
                    $output["sub_file"][$file_cnt]["tn_img_url"] = SHOW_TN_URL."?page_url=$f_url&type=$f_type&site=".BIG_TN_SIZE;
                //    $output["sub_file"][$file_cnt]["big_tn_img_url"] = $dir_url.$recs[$i]["big_tn_img"];

                /* 整理 record 資料,如果 type = complete 就直接抓取 record 檔內容,否則直接用搜尋結果的 record */
                if ($type == TYPE_COMPLETE)
                    $output["sub_file"][$file_cnt]["record"] = get_path_rec_field($page_dir, $page_path);
                else
                    $output["sub_file"][$file_cnt]["record"] = $recs[$i];

                $file_cnt++;
            }
        }
        $output["offset"] = $ignoreo;
    }

    function assign_global(&$tpl, $output)
    {
        /* 2015/5/27 新增,若有設定 google_analytics.conf,就取回檔案內容並 assign 成 global 變數 */
        if (file_exists(GOOGLE_ANALYTICS_CONF))
        {
            $content = trim(implode("", @file(GOOGLE_ANALYTICS_CONF)));
            $tpl->assignGlobal("google_analytics", $content);
        }

        /* 整理所有變數 assign 成樣版中 global 變數 */
        foreach($output as $key => $value)
        {
            /* 屬於陣列型態的資料,就不能 assign 成 global 變數 */
            if (is_array($value))
                continue;

            /* 將其他變數都 assign 成 global 變數 */
            if ($key == "site_name")
                $tpl->assignGlobal($key, htmlspecialchars($value));
            else
                $tpl->assignGlobal($key, $value);
        }

        /* 整理 record 資料,assign 成樣版中 global 變數 */
        foreach($output["record"] as $key => $value)
        {
            if (($key == "description") || ($key == "title"))
                $tpl->assignGlobal("rec_$key", htmlspecialchars($value));
            else
                $tpl->assignGlobal("rec_$key", $value);
        }

        /* 整理 dir_set 資料,assign 成樣版中 global 變數 */
        foreach($output["dir_set"] as $key => $value)
            $tpl->assignGlobal("dir_$key", $value);

        /* 2015/2/10 新增,將 user 的權限狀態 assign 成樣版中 global 變數 */
        foreach($output["user_right"] as $key => $value)
            $tpl->assignGlobal("right_$key", $value);

        /* 檢查及設定其他需使用到的 global 變數 */
        if ($output["m_right"] == PASS)
        {
            $tpl->assignGlobal("is_admin", "y");
            $tpl->assignGlobal("b_is_admin", "true");
        }
        else
        {
            $tpl->assignGlobal("is_admin", "n");
            $tpl->assignGlobal("b_is_admin", "false");
        }

        if ($output["u_right"] == PASS)
        {
            $tpl->assignGlobal("is_upload", "y");
            $tpl->assignGlobal("b_is_upload", "true");
        }
        else
        {
            $tpl->assignGlobal("is_upload", "n");
            $tpl->assignGlobal("b_is_upload", "false");
        }

        if ($output["set_pwd"] == true)
        {
            $tpl->assignGlobal("is_pwd", "y");
            $tpl->assignGlobal("b_is_pwd", "true");
        }
        else
        {
            $tpl->assignGlobal("is_pwd", "n");
            $tpl->assignGlobal("b_is_pwd", "false");
        }

        /* 2015/3/13 新增,輸出設定權限資料 */
        if ($output["user_right"]["set"] == PASS)
        {
            $tpl->assignGlobal("is_set", "y");
            $tpl->assignGlobal("b_is_set", "true");
        }
        else
        {
            $tpl->assignGlobal("is_set", "n");
            $tpl->assignGlobal("b_is_set", "false");
        }

        $tpl->assignGlobal("cnt_sub_dir", count($output["sub_dir"]));
        $tpl->assignGlobal("cnt_file_dir", count($output["sub_file"]));
    }

    function block_index_mode(&$tpl, $output){
        if ($output["m_right"] == PASS && isset($output["index_mode"]))
            $tpl->newBlock("INDEX_MODE");
        if (isset($output["index_mode"]))
            $tpl->assignGlobal('cur_idx_mode', $output["index_mode"]);
    }

    function block_header(&$tpl, $output, $block_name="HEADER")
    {
        $tpl->newBlock($block_name);
        $tpl->newBlock("LOGO_".$output["logo_place"]);
    }

    function block_footer(&$tpl, $output, $block_name="FOOTER")
    {
        /* 取出 footer 檔內容 */
        if ($output["site"] == "Site")
            $footer_file = WEB_PAGE_DIR.$output["site_acn"]."/".NUWEB_FOOTER;
        else
            $footer_file = WEB_PAGE_DIR.NUWEB_FOOTER;

        if (file_exists($footer_file))
        {
            $buf = @file($footer_file);
            $tmp_buf = trim($buf[0]);
            /* 取出 footer title */
            $footer_title = get_title($tmp_buf);
            if ($footer_title != false)
            {
                /* 將 footer 中的 title 資料內容清空,以避免被放到 footer_content 中 */
                $l = strlen(TITLE_POSTFIX);
                $n = strpos($buf[0], TITLE_POSTFIX);
                $buf[0] = substr($buf[0], $n+$l);
            }
            $footer = implode("", $buf);
        }
        else
            touch($footer_file);

        if (!empty($footer))
        {
            $tpl->newBlock($block_name);
            $tpl->assign("footer", $footer);
        }
    }

    function block_menu(&$tpl, $output, $block_name="MENU_L")
    {
        Global $isset_random_path;

        $tpl->newBlock($block_name);
        /* 整理 menu 資料 */
        $cnt = count($output["menu"]);
        for ($i = 0; $i < $cnt; $i++)
        {
            $tpl->newBlock($block_name."_ITEM");
            $url = $output["menu"][$i]["url"];
            $tpl->assign("filepath", str_replace($output["site_page_url"], "", $url));
            if ($isset_random_path == true)
                $url .= "&random_path=".$output["random_path"];
            $tpl->assign("url", $url);
            $tpl->assign("name", $output["menu"][$i]["name"]);
            $tpl->assign("link_target", $output["menu"][$i]["link_target"]);
            $tpl->assign("tag", $output["menu"][$i]["tag"]);
            /* 2014/10/27 新增,若是管理者且有設定隱藏 tag 就要顯式隱藏 icon */
            if (($output["m_right"] == PASS) && (strstr($output["menu"][$i]["tag"], HIDDEN_TAG) !== false))
                $tpl->newBlock($block_name."_HIDDEN_BLOCK");
        }

        /* 整理系統自動建立的 menu */
        /* 1. 子網站首頁 */
        // if ($output["m_right"] == PASS && $output["site_path"] == "Site")
            // $tpl->newBlock($block_name."_ITEM_SITE_HOME");

        /* 2. 子網站列表頁, 20130911 修改,只有 web 網站才顯示 */
        //if ((file_exists(SUB_SITE_LINK_NAME)) && (chk_show_subsite_link()))
        if ((file_exists(SUB_SITE_LINK_NAME)) && (chk_show_subsite_link()) && ($output["site_acn"] == "web"))
        {
            $buf = @file(SUB_SITE_LINK_NAME);
            $cnt = count($buf);
            for ($i = 0; $i < $cnt; $i++)
            {
                list($link_lang, $subsite_link_name) = explode("\t", trim($buf[$i]));
                if (($link_lang == $output["lang"]) && (!empty($subsite_link_name)))
                {
                    $tpl->newBlock($block_name."_ITEM_SITE_LIST");
                    $tpl->assign("subsite_link_name", $subsite_link_name);
                    break;
                }
            }
        }

        /* 3. 後端管理系統 */
        /* 2015/3/19 修改,系統管理者與後端管理者才會顯示 */
        if (($output["sys_manager"] == true) || ($output["admin_manager"] == true))
            $tpl->newBlock($block_name."_ITEM_ADMIN");
    }

    function block_function_menu(&$tpl, $output, $block_name="FUNCTION_MENU")
    {
        Global $login_user;

        $tpl->newBlock($block_name);

        if (!empty($login_user["sun"]))
        {
            $tpl->newBlock("LOGIN_USER");
            $tpl->assign("user_name", htmlspecialchars($login_user["sun"]));
        }
        else
            $tpl->newBlock("LOGIN");

        /* 有上傳權限的人才能編輯文章 */
        if ($output["u_right"] == PASS)
        {
            $tpl->newBlock("EDIT_MENU_BLOCK");
            $tpl->newBlock("EDIT_BLOCK");
        }

        /* 有管理權限的人才顯示管理區塊 */
        if ($output["m_right"] == PASS)
        {
            $tpl->newBlock("MANAGER_MENU_BLOCK");
            $tpl->assign("footer_file", NUWEB_FOOTER);
            /* 2013/7/25 新增將 Login 的 nu_code Cookie 資料,assign 成樣板變數 */
            $tpl->assignGlobal("code", $_COOKIE["nu_code"]);
            $tpl->newBlock("MANAGER_BLOCK");
            /* 2015/11/16 新增,檢查若是一般目錄型態才顯示變更目錄型態項目 */
            if (chk_general_dir_type($output["dir_type"]) == true)
                $tpl->newBlock("SET_DIR_TYPE_BLOCK");
        }

        /* 有個人網站才會顯示網站 Link 區塊 */
        $acn_site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".$login_user["acn"];
        if ((!empty($login_user["acn"])) && (is_dir($acn_site_path)))
            $tpl->newBlock("MY_SITE_LINK");
    }

    function block_submenu(&$tpl, $output, $block_name="SUBMENU")
    {
        Global $isset_random_path;

        if (!isset($output["submenu"]))
            return false;

        $tpl->newBlock($block_name);

        /* 整理 submenu 資料 */
        $submenu_list = $output["submenu"];
        if (!isset($submenu_list["id"]))
            $submenu_id = DEF_SUBMENU_ID;
        else
            $submenu_id = $submenu_list["id"];

        if (($n = strrpos($submenu_id, "-")) != false)
            $submenu_css_id = substr($submenu_id, 0 , $n);
        else
            $submenu_css_id = $submenu_id;

        if (!isset($submenu_list["width"]))
            $submenu_width = DEF_SUBMENU_WIDTH;
        else
            $submenu_width = $submenu_list["width"];

        if (!isset($submenu_list["top_bottom"]))
            $submenu_top_bottom = DEF_SUBMENU_TOP_BOTTOM;
        else
            $submenu_top_bottom = $submenu_list["top_bottom"];

        if (!isset($submenu_list["left_right"]))
            $submenu_left_right = DEF_SUBMENU_LEFT_RIGHT;
        else
            $submenu_left_right = $submenu_list["left_right"];

        $tpl->assign("submenu_css_id", $submenu_css_id);
        $tpl->assign("submenu_width", $submenu_width);
        $tpl->assign("submenu_top_bottom", $submenu_top_bottom);
        $tpl->assign("submenu_left_right", $submenu_left_right);

        $sunmenu_cnt = count($submenu_list["item"]);
        for ($i = 0; $i < $sunmenu_cnt; $i++)
        {
            $tpl->newBlock("SUBMENU_ITEM");
            if ($submenu_list["item"][$i]["path"] != $output["path_name"])
                $tpl->assign("submenu_id", $submenu_id);
            else
                $tpl->assign("submenu_id", $submenu_css_id);
            if ($isset_random_path == true)
                $submenu_url = SITE_URL.$submenu_list["item"][$i]["path"]."&random_path=".$output["random_path"];
            else
                $submenu_url = SITE_URL.$submenu_list["item"][$i]["path"];
            $tpl->assign("submenu_url", $submenu_url);
            $tpl->assign("submenu_filepath", $submenu_list["item"][$i]["path"]);
            $tpl->assign("submenu_name", $submenu_list["item"][$i]["name"]);
        }
    }

    function block_share(&$tpl, $output, $block_name="SHARE")
    {
        if ($output["dir_set"]["share"] == true)
            $tpl->newBlock($block_name);
    }

    function block_path(&$tpl, $output, $block_name="PATH")
    {
        /* 若是目錄 file_path 最後一律加上 / */
        $page_path = $output["page_dir"].$output["file_path"];
        $file_path = $output["file_path"];
        if ((is_dir($page_path)) && (substr($file_path, -1) != '/'))
            $file_path .= "/";
        $bDir = substr($file_path, -1) == '/';
        $path = explode("/", $file_path);
        $path_cnt = count($path);
        $last_no = $path_cnt - 1;
        for ($i = 0; $i < $path_cnt; $i++)
        {
            /* 若 file_path 最後有 / 最後一個 path 會是空的不必處理 */
            if (($i == $last_no) && $bDir)
                return;

            $tpl->newBlock($block_name);
            if ($i == 0)
                $now_path = $path[$i];
            else
                $now_path .= "/".$path[$i];
            if ($i < $last_no)
                $tpl->assign("gap", PATH_GAP);
            $tpl->assign("path", $now_path.(($i == $last_no) && !$bDir ? "" : "/"));
            /* 2014/2/26 修改,最後一個顯示名稱不要顯示副檔名 */
            $path_link_name = get_file_name($output["page_dir"], $now_path);
            if (($i == $last_no) && (($n = strrpos($path_link_name,  ".")) !== false))
                $path_link_name = substr($path_link_name, 0, $n);
            $tpl->assign("path_link_name", htmlspecialchars($path_link_name));
        }
    }

    function block_user_comment(&$tpl, $output, $block_name="USER_COMMENT")
    {
        Global $login_user;

        if (!isset($output["dir_set"]["user_comment"]) || $output["dir_set"]["user_comment"] == true)
        {
            if ($output["dir_set"]["user_comment"] == 2 && array_search($login_user["acn"], get_group_user()) === FALSE)
                return;
            $tpl->newBlock($block_name);
        }
    }

    function block_dir_content(&$tpl, $output, $block_name="DIR_CONTENT")
    {
        /* dir_type 為 page (網頁模式)時,不需要輸出 dir_content */
        if ($output["dir_type"] == "page")
            return;

        $lang = $output["lang"];
        if (empty($lang))
            $lang = DEF_LANG;

        /* 依據 dir_set 中的 tpl_mode 選擇 template file */
        if (empty($output["dir_set"]["tpl_mode"]))
        {
            if (empty($output["dir_type"]))
                $dir_template = TEMPLATE_DIR.DEF_TEMPLATE.".$lang";
            else
                $dir_template = TEMPLATE_DIR.$output["dir_type"].".tpl.$lang";
        }
        else
            $dir_template = TEMPLATE_DIR.$output["dir_set"]["tpl_mode"].".tpl.$lang";
        /* 若 template file 不存在就不顯示 dir_content */
        /* 2015/11/16 修改,若 template file 不存在就改用預設樣版檔 */
        if (!file_exists($dir_template))
            $dir_template = TEMPLATE_DIR.DEF_TEMPLATE.".$lang";
            //return;

        /* 先設定 Global 參數 */
        $dir_tpl = new TemplateLib($dir_template);
        assign_global($dir_tpl, $output);

        /* 依據 dir_type 決定要輸出那些 block 內容 */
        $bDirSpace = (count($output["sub_dir"]) == 0 && count($output["sub_file"]) == 0 && empty($output["page_content"]));
        switch($output["dir_type"])
        {
            case "blog":
                if ($bDirSpace)
                {
                    if ($output["u_right"] == PASS) 
                        $tpl->newBlock("DIR_SPACE_BLOG");
                }
                else
                    block_subfile($dir_tpl, $output);
                break;

            case "album":
            case "media":
            case "multimedia":
                if ($bDirSpace)
                {
                    if ($output["u_right"] == PASS) 
                        $tpl->newBlock("DIR_SPACE_MULTIMEDIA");
                }
                else
                {
                    block_subdir($dir_tpl, $output);
                    block_album($dir_tpl, $output);
                    block_subfile($dir_tpl, $output);
                }
                break;

            case "directory":
            default:
                if ($bDirSpace)
                {
                    if ($output["u_right"] == PASS) 
                        $tpl->newBlock("DIR_SPACE_DATA");
                }
                else
                {
                    //if (!empty($output["sub_file"]))
                    //    usort($output["sub_file"], 'cmp_fe');
                    block_subdir($dir_tpl, $output);
                    block_subfile($dir_tpl, $output);
                }
                break;

        }

        $dir_content = $dir_tpl->getOutputContent();
        $tpl->newBlock($block_name);
        $tpl->assign("dir_content", $dir_content);
    }

    function cmp_fe($a, $b)
    {
        if (empty($a["fe"]) || empty($b["fe"]))
        {
            if (empty($a["fe"]) && empty($b["fe"]))
                return 0;
            else if(empty($a["fe"]))
                return -1;
            else
                return 1;
        }
        else
            return strcmp($a["fe"], $b["fe"]);
    }

    function block_subdir(&$tpl, $output, $block_name="SUB_DIR")
    {
        Global $isset_random_path, $login_user, $is_manager, $m_right, $site_acn;

        /* 2015/7/28 新增,檢查 record 的 dir_zv 欄位,若沒設定 dir_zv 欄位或有設定 R (DIR_TYPE),若有才顯示子目錄 */
        if ((isset($output["record"]["dir_zv"])) && (!empty($output["record"]["dir_zv"])) && (strstr($output["record"]["dir_zv"], DIR_TYPE) == false))
            return false;

		$bFilePW = false;
		$patFilePW = "";
		/* 2015/3/19 修改,系統管理者已不使用,新增檢查網站管理者 */
		//if (!$is_manager && $login_user !== false) {
		if (($m_right !== PASS) && !$is_manager && $login_user !== false) {
			$bFilePW = true;
			$aPat[] = preg_quote($login_user['acn']);
			$aPat[] = preg_quote($login_user['mail']);
			if ($login_user['site_owner']) 	$aPat[] = "site_owner";			// 主網站成員
			if ($login_user['site_manager'])$aPat[] = preg_quote($site_acn.".".$output['cs_acn']);	// 本網站成員
			if (!empty($user)) $aPat[] = "W";	// 是會員
			// 取得所有加入的社群網站
			if (!function_exists("user_get_member_sitelist"))
				require_once("/data/HTTPD/htdocs/tools/rs_tools_base.php");
			$recsGS = user_get_member_sitelist();
			if (is_array($recsGS)) {
				foreach($recsGS as $rec)
					$aPat[] = preg_quote($rec['site']);
			}
			//
			$patFilePW = "#(^|,)(".implode("|",$aPat).")(,|$)#i";
		}
		
        $n = count($output["sub_dir"]);
        $cnt = 0;
        for ($i = 0; $i < $n; $i++)
        {
            $page_path = $output["path_name"]."/".$output["sub_dir"][$i]["path"];

            /* 除了 dir_type == directory 外,其他目錄型態都只顯示該目錄型態的子網站 */
            if (($output["dir_type"] != "directory") && ($output["sub_dir"][$i]["dir_type"] != $output["dir_type"]))
                continue;

            /* 確認有相關的子目錄,才設定顯示這個區塊 */
            if ($cnt == 0)
                $tpl->newBlock($block_name);

            /* 至少 2 筆且是管理者才顯示調整順序項目 */
            if (($cnt == 1) && ($output["m_right"] == PASS))
                $tpl->newBlock($block_name."_FUN");

            /* 檢查是否已取得子目錄的 record 資料,若無則自動讀取 */
            if (!isset($output["sub_dir"][$i]["record"]))
                $rec = get_path_rec_field($output["page_dir"], $page_path);
            else
                $rec = $output["sub_dir"][$i]["record"];
            $tpl->newBlock("SUB_DIR_ITEM");

            /* 2014/5/16 新增,依子目錄型態設定顯示的 icon */
            $dir_type = strtolower($output["sub_dir"][$i]["dir_type"]);
            switch($dir_type)
            {
                case "blog":
                case "bookmark":
                case "forum":
                case "table":
                case "vote":
                    $tpl->newBlock("OTHER_DIR");
                    $tpl->assign("sub_dir_type", $dir_type);
                    break;

                default:
                    $tpl->newBlock("DIRECTORY");
                    break;
            }
            /* 2014/10/27 新增,若是管理者且有設定隱藏 tag 就要顯式隱藏 icon */
            if (($output["m_right"] == PASS) && (strstr($output["sub_dir"][$i]["tag"], HIDDEN_TAG) !== false))
                $tpl->newBlock("SUB_DIR_HIDDEN_BLOCK");
            $tpl->gotoBlock("SUB_DIR_ITEM");

            $date = substr($rec["time"], 0, 8);
            $year = substr($date, 0, 4);
            $month = substr($date, 4, 2);
            $day = substr($date, 6);
            $tpl->assign("year", $year);
            $tpl->assign("month", $month);
            $tpl->assign("day", $day);
            $tpl->assign("time", $rec["time"]);
            $tpl->assign("mtime", $rec["mtime"]);
            $tpl->assign("title", $rec["title"]);
            $tpl->assign("tag", $rec["tag"]);
            $tpl->assign("page_path", $page_path);
            $tpl->assign("page_file_name", $rec["filename"]);
            $show_file_name = mb_substr($rec["filename"], 0, FILE_TITLE_LEN, 'UTF-8');
            $l = strlen($rec["filename"]);
            if (strlen($show_file_name) < $l)
                $show_file_name .= "...";
            $tpl->assign("show_file_name", $show_file_name);
            $subdir_url = str_replace("//", "/", $output["page_url"]."/".$output["sub_dir"][$i]["path"]);
            $real_subdir_url = $subdir_url;
            $tpl->assign("subdir_filepath", str_replace($output["site_page_url"], "", $subdir_url));
            if ($isset_random_path == true)
                $subdir_url .= "&random_path=".$output["random_path"];
            $tpl->assign("subdir_url", $subdir_url);

            /* 取得 description */
            $description = htmlspecialchars(get_rec_description($rec));
            $tpl->assign("description", $description);

            /* 如果是 multimedia 目錄就必須取得子目錄得第一張縮圖 */
            if ($output["dir_type"] == "multimedia")
            {
                $tn_img_file = get_first_tn_pict($output["page_dir"], $page_path);
                if ($tn_img_file == false)
                    $tpl->newBlock("SUB_DIR_NOIMG");
                else
                {
                    $tpl->newBlock("SUB_DIR_IMG");
                    if ($tn_img_file == LOCK_DIR_PICT)
                        $tn_img_url = SITE_URL."images/".LOCK_DIR_PICT;
                    else
                    {
                        if ($isset_random_path == true)
                            $tn_img_url = PAGE_URL.$tn_img_file."&random_path=".$output["random_path"];
                        else
                            $tn_img_url = PAGE_URL.$tn_img_file;
                    }
                    $tpl->assign("tn_img_url", $tn_img_url);
                }
                $tpl->assign("description", $description);
            }
            else
            {
                /* 如果不是 multimedia 目錄就必須取得子目錄內有那些子目錄與檔案,整理出部分內容顯示 */
                //$subdir_content = get_subdir_content($output["page_dir"], $page_path);
                //$tpl->assign("subdir_content", $subdir_content);
                /* 若有設定 description 就顯示 description */
                if (!empty($description))
                {
                    $tpl->newBlock("DESCRIPTION_DIR_ITEM");
                    $tpl->assign("description", $description);
                }
                else
                {
                    /* 取得目錄內要輸出的檔案資料 */
                    $data = get_subdir_data($output["page_dir"], $page_path);
                    if ($data == false)
                        $d_cnt = 0;
                    else
                        $d_cnt = count($data);
                    if ($d_cnt > MAX_OUTPUT_SUB_ITEM)
                        $d_cnt = MAX_OUTPUT_SUB_ITEM;
                    for ($j = 0; $j < $d_cnt; $j++)
                    {
                        /* 過濾掉 *.files 與 .sync 目錄 */
                        if ((substr($data[$j]["filename"], -6) == ".files") || ($data[$j]["filename"] == SYNC_DIR))
                            continue;

                        if ($data[$j]["type"] == IMAGE_TYPE_NAME)
                            $tpl->newBlock("IMAGE_DIR_ITEM");
                        else if ($data[$j]["type"] == VIDEO_TYPE_NAME)
                            $tpl->newBlock("VIDEO_DIR_ITEM");
                        else if ($data[$j]["type"] == DIR_TYPE_NAME)
                            $tpl->newBlock("DIR_DIR_ITEM");
                        else
                            $tpl->newBlock("FILE_DIR_ITEM");
                        /* 2015/3/25 修改,將 subdir_url 改成 read_subdir_url,因為若有 random_path 時,會造成 file_url 錯誤,有 random_path 時,需將 random_path 加到 file_url 後面 */
                        $file_url = $real_subdir_url."/".$data[$j]["page_name"];
                        if ($isset_random_path == true)
                            $file_url .= "&random_path=".$output["random_path"];
                        $tpl->assign("file_url", $file_url);
                        $file_name = $data[$j]["filename"];
                        $tpl->assign("file_name", $file_name);
                        $tpl->assign("type", $data[$j]["type"]);
                        $p = strrpos($file_name, ".");
                        if ($p !== false)
                            $file_title = substr($file_name, 0, strrpos($file_name, "."));
                        else
                            $file_title = $file_name;
                        $l = strlen($file_title);
                        $file_title = mb_substr($file_title, 0, FILE_TITLE_LEN, 'UTF-8');
                        if (strlen($file_title) < $l)
                            $file_title .= "...";
                        $tpl->assign("file_title", $file_title);
                        if ($j < ($d_cnt - 1))
                            $tpl->assign("comma", ",");
                        /* 整理 size */
                        if ($data[$j]["size"] < 1024)
                            $size = $data[$j]["size"]."Bytes";
                        else if ($data[$j]["size"] < 1024 * 1024)
                            $size = (round(($data[$j]["size"] / 1024) * 100) / 100)."KB";
                        else if ($data[$j]["size"] < 1024 * 1024 * 1024)
                            $size = (round(($data[$j]["size"] / 1024 / 1024) * 100) / 100)."MB";
                        else
                            $size = (round(($data[$j]["size"] / 1024 / 1024 / 1024) * 100) / 100)."GB";
                        $tpl->assign("size", $size);
                        /* 整理 date */
                        $f_time = $data[$j]["time"];
                        $tpl->assign("date", substr($f_time, 0, 4)."/".substr($f_time, 4, 2)."/".substr($f_time, 6, 2)." ".substr($f_time, 8, 2).":".substr($f_time, 10, 2).":".substr($f_time, 12, 2));
                        /* 整理圖片&影片長與寬與影片長度 */
                        if (isset($data[$j]["width"]))
                            $tpl->assign("width", $data[$j]["width"]);
                        if (isset($data[$j]["height"]))
                            $tpl->assign("height", $data[$j]["height"]);
                        if (isset($data[$j]["duration"]))
                            $tpl->assign("duration", $data[$j]["duration"]);
                    }
                }
            }
            $cnt++;
			
			// 2015/02/11 whee 檔案權限
			$pw = "N";
			if ($bFilePW && $rec["r_flag"] == "Y" && $rec['owner'] != $login_user["acn"]) {
				$aPW = array();
				if (preg_match($patFilePW, $rec['r_browse']) == 1) $aPW[] = "B";
				if (preg_match($patFilePW, $rec['r_download']) == 1) $aPW[] = "L";
				if (preg_match($patFilePW, $rec['r_upload']) == 1) $aPW[] = "U";
				if (preg_match($patFilePW, $rec['r_edit']) == 1) $aPW[] = "E";
				if (preg_match($patFilePW, $rec['r_del']) == 1) $aPW[] = "D";
				$pw = implode(",", $aPW);
			}
            $tpl->assign("owner", $rec['owner']);
            $tpl->assign("pw", $pw);

        }
    }

    function block_album(&$tpl, $output, $block_name="ALBUM")
    {
        Global $isset_random_path;

        $n = count($output["sub_file"]);
        $cnt = 0;
        for ($i = 0; $i < $n; $i++)
        {
            /* Album 只處理相片檔案 (.jpg .jpeg .png) */
            $fe = $output["sub_file"][$i]["fe"];
            if (($fe != ".jpg") && ($fe != ".jpeg") && ($fe != ".png"))
                continue;

            $page_path = $output["path_name"]."/".$output["sub_file"][$i]["path"];
            if ($cnt == 0)
            {
                /* 確認有相片檔,才設定顯示這個區塊 */
                $tpl->newBlock($block_name);

                /* 檢查是否已取得第一張圖片的 record 資料,若無則自動讀取 */
                if (!isset($output["sub_file"][$i]["record"]))
                    $rec = get_path_rec_field($output["page_dir"], $page_path);
                else
                    $rec = $output["sub_file"][$i]["record"];
                $tpl->newBlock("FIRST_".$block_name);
                $date = substr($rec["time"], 0, 8);
                $year = substr($date, 0, 4);
                $month = substr($date, 4, 2);
                $day = substr($date, 6);
                $tpl->assign("year", $year);
                $tpl->assign("month", $month);
                $tpl->assign("day", $day);
                $tpl->assign("title", $rec["title"]);
                $tpl->assign("tag", $rec["tag"]);
                $tpl->assign("description", htmlspecialchars(get_rec_description($rec)));
            }
            else
            {
                /* 至少 2 筆且是管理者才顯示調整順序項目 */
                if (($cnt == 1) && ($output["m_right"] == PASS))
                    $tpl->newBlock($block_name."_FUN");

                $tpl->newBlock("OTHER_".$block_name);
            }

            $subfile_url = $output["sub_file"][$i]["url"];
            if ($isset_random_path == true)
            {
                $tpl->assign("subfile_url", $subfile_url."&random_path=".$output["random_path"]);
                $tpl->assign("subfile_filepath", str_replace($output["site_page_url"], "", $subfile_url));
                $tpl->assign("img_url", $subfile_url."&random_path=".$output["random_path"]);
                //$tpl->assign("big_tn_img_url", $output["sub_file"][$i]["big_tn_img_url"]."&random_path=".$output["random_path"]);
                //$tpl->assign("tn_img_url", $output["sub_file"][$i]["tn_img_url"]."&random_path=".$output["random_path"]);
                $tpl->assign("big_tn_img_url", SHOW_TN_URL."?page_url=$subfile_url&type=Image&size=".BIG_TN_SIZE."&random_path=".$output["random_path"]);
                $tpl->assign("tn_img_url", SHOW_TN_URL."?page_url=$subfile_url&type=Image&random_path=".$output["random_path"]);
            }
            else
            {
                $tpl->assign("subfile_url", $subfile_url);
                $tpl->assign("subfile_filepath", str_replace($output["site_page_url"], "", $subfile_url));
                $tpl->assign("img_url", $subfile_url);
                //$tpl->assign("big_tn_img_url", $output["sub_file"][$i]["big_tn_img_url"]);
                //$tpl->assign("tn_img_url", $output["sub_file"][$i]["tn_img_url"]);
                $tpl->assign("big_tn_img_url", SHOW_TN_URL."?page_url=$subfile_url&type=Image&size=".BIG_TN_SIZE);
                $tpl->assign("tn_img_url", SHOW_TN_URL."?page_url=$subfile_url&type=Image");
            }
            $tpl->assign("page_path", $page_path);
            $tpl->assign("page_file_name", $output["sub_file"][$i]["name"]);
            /* 2014/10/27 新增,若是管理者且有設定隱藏 tag 就要顯式隱藏 icon */
            if (($output["m_right"] == PASS) && (strstr($output["sub_file"][$i]["tag"], HIDDEN_TAG) !== false))
                $tpl->newBlock($block_name."_HIDDEN_BLOCK");
            else
                $tpl->newBlock($block_name."_NO_HIDDEN_BLOCK");
            $cnt++;
        }
    }

    function block_subfile(&$tpl, $output)
    {
        Global $fe_type, $isset_random_path, $login_user, $is_manager, $m_right, $site_acn;
		
        /* 2015/7/28 新增,取得 record 的 dir_zv 欄位資料設定到 show 參數中,若沒設定 dir_zv 欄位就設定 show 參數為 true,代表全部都顯示 */
        if ((isset($output["record"]["dir_zv"])) && (!empty($output["record"]["dir_zv"])))
            $show = $output["record"]["dir_zv"];
        else
            $show = true;

		$bFilePW = false;
		$patFilePW = "";
		/* 2015/3/19 修改,系統管理者已不使用,新增檢查網站管理者 */
		//if (!$is_manager && $login_user !== false) {
		if (($m_right !== PASS) && !$is_manager && $login_user !== false) {
			$bFilePW = true;
			$aPat[] = preg_quote($login_user['acn']);
			$aPat[] = preg_quote($login_user['mail']);
			if ($login_user['site_owner']) 	$aPat[] = "site_owner";			// 主網站成員
			if ($login_user['site_manager'])$aPat[] = preg_quote($site_acn.".".$output['cs_acn']);	// 本網站成員
			if (!empty($user)) $aPat[] = "W";	// 是會員
			// 取得所有加入的社群網站
			if (!function_exists("user_get_member_sitelist"))
				require_once("/data/HTTPD/htdocs/tools/rs_tools_base.php");
			$recsGS = user_get_member_sitelist();
			if (is_array($recsGS)) {
				foreach($recsGS as $rec)
					$aPat[] = preg_quote($rec['site']);
			}
			//
			$patFilePW = "#(^|,)(".implode("|",$aPat).")(,|$)#i";
		}

        $i_cnt = 0;
        $v_cnt = 0;
        $a_cnt = 0;
        $h_cnt = 0;
        $d_cnt = 0;
        $l_cnt = 0;
        $o_cnt = 0;
        $chk_cnt = 0;
        $n = count($output["sub_file"]);
        for ($i = 0; $i < $n; $i++)
        {
            /* 確認有該型態的檔案,才設定顯示這個型態的區塊 */
            $fe = $output["sub_file"][$i]["fe"];
            /* 2015/7/28 新增,若 show 不是 true 且內容不包含目前檔案型態,代表不顯示此型態的資料,就直接跳過此檔案 */
            if (!isset($fe_type[$fe]))
                $fe_type[$fe] = OTHER_TYPE;
            if (($show !== true) && (strstr($show, $fe_type[$fe]) == false))
                continue;
            switch($fe_type[$fe])
            {
                case IMAGE_TYPE:
                    $type = "IMAGE";
                    $chk_cnt = $i_cnt;
                    $i_cnt++;
                    break;
                case VIDEO_TYPE:
                    $type = "VIDEO";
                    $chk_cnt = $v_cnt;
                    $v_cnt++;
                    break;
                case AUDIO_TYPE:
                    $type = "AUDIO";
                    $chk_cnt = $a_cnt;
                    $a_cnt++;
                    break;
                case HTML_TYPE:
                    $type = "HTML";
                    $chk_cnt = $h_cnt;
                    $h_cnt++;
                    break;
                case TEXT_TYPE:
                case DOC_TYPE:
                    $type = "DOC";
                    $chk_cnt = $d_cnt;
                    $d_cnt++;
                    break;
                case LINK_TYPE:
                    $type = "LINK";
                    $chk_cnt = $l_cnt;
                    $l_cnt++;
                    break;
                default:
                    $type = "OTHER";
                    $chk_cnt = $o_cnt;
                    $o_cnt++;
                    break;
            }

            if ($chk_cnt == 0)
                $tpl->newBlock($type);

            /* 至少 2 筆且是管理者才顯示調整順序項目 */
            if (($chk_cnt == 1) && ($output["m_right"] == PASS))
                $tpl->newBlock($type."_FUN");

            $page_path = $output["path_name"]."/".$output["sub_file"][$i]["path"];

            /* 檢查是否已取得檔案的 record 資料,若無則自動讀取 */
            if (!isset($output["sub_file"][$i]["record"]))
                $rec = get_path_rec_field($output["page_dir"], $page_path);
            else
                $rec = $output["sub_file"][$i]["record"];
            $tpl->newBlock($type."_ITEM");
            $date = substr($rec["time"], 0, 8);
            $year = substr($date, 0, 4);
            $month = substr($date, 4, 2);
            $day = substr($date, 6);
            $tpl->assign("year", $year);
            $tpl->assign("month", $month);
            $tpl->assign("day", $day);
            $tpl->assign("time", $rec["time"]);
            $tpl->assign("mtime", $rec["mtime"]);
            $tpl->assign("fe", $fe);
            $tpl->assign("type", $type);
            $fsize = $rec["size"];
            if ($fsize > 1048576)
                $size = round($fsize/104857.6)/10 . " MB";
            else if ($fsize > 1024)
                $size = round($fsize/102.4)/10 . " KB";
            else
                $size = $fsize . " B";
            $tpl->assign("size", $size);
            $tpl->assign("real_size", $fsize);
            $tpl->assign("title", $rec["title"]);
            $tpl->assign("tag", $rec["tag"]);
            if ($output["dir_type"] == "blog")
                $tpl->assign("description", get_rec_description($rec));
            else
                $tpl->assign("description", htmlspecialchars(get_rec_description($rec)));
            $tpl->assign("page_path", $page_path);
            $tpl->assign("page_file_name", $rec["filename"]);
            if (!empty($output["random_path"]))
                $subfile_url = $output["sub_file"][$i]["url"]."&random_path=".$output["random_path"];
            else
                $subfile_url = $output["sub_file"][$i]["url"];
            $tpl->assign("subfile_url", $subfile_url);
            $tpl->assign("real_url", $output["sub_file"][$i]["url"]);
            $tpl->assign("subfile_filepath", str_replace($output["site_page_url"], "", $output["sub_file"][$i]["url"]));
            if (($fe_type[$fe] == IMAGE_TYPE) || ($fe_type[$fe] == VIDEO_TYPE))
            {
                //if (empty($rec["thumbs"]))
                //    $tn_img_url = EMPTY_ICON_URL;
                //else
                //{
                //    if (!empty($output["random_path"]))
                //        $tn_img_url = $output["sub_file"][$i]["tn_img_url"]."&random_path=".$output["random_path"];
                //    else
                //        $tn_img_url = $output["sub_file"][$i]["tn_img_url"];
                //}
                /* 2014/4/2 修改,改由 /tools/api_get_thumbs.php 顯示 */
                $f_type = ($fe_type[$fe] == IMAGE_TYPE) ? IMAGE_TYPE_NAME : VIDEO_TYPE_NAME;
                $tn_img_url = SHOW_TN_URL."?page_url=".$output["sub_file"][$i]["url"]."&type=$f_type";
                if ($isset_random_path == true)
                    $tn_img_url .= "&random_path=".$output["random_path"];
                $tpl->assign("tn_img_url", $tn_img_url);
            }

            /* 設定 Document 與 Other 的 fe_icon 參數 */
            if (($type == "DOC") || ($type == "OTHER"))
            {
                if (($fe == ".text") || ($fe == ".txt"))
                    $fe_icon = "txt";
                else if (($fe == ".doc") || ($fe == ".docx") || ($fe == ".odt"))
                    $fe_icon = "doc";
                else if (($fe == ".pps") || ($fe == ".ppt") || ($fe == ".pptx") || ($fe == ".odp"))
                    $fe_icon = "ppt";
                else if (($fe == ".xls") || ($fe == ".xlsx") || ($fe == ".ods"))
                    $fe_icon = "xlsx";
                else if ($fe == ".pdf")
                    $fe_icon = "pdf";
                else if ($fe == ".exe")
                    $fe_icon = "exe";
                else if (($fe == ".zip") || ($fe == ".rar") || ($fe == ".tar") || ($fe == ".tgz") || ($fe == ".gz") || ($fe == ".bz2") || ($fe == ".7zip") || ($fe == ".7z") || ($fe == ".arj") || ($fe == ".cab") || ($fe == ".ace"))
                    $fe_icon = "zip";
                else $fe_icon = "other_file";
                $tpl->assign("fe_icon", $fe_icon);
            }

            /* 若是 Audio 檔就檢查是否可線上播放 (.mp3 .wav .wma),可線上播放才顯示播放功能 */
            if (($type == "AUDIO") && (($fe == ".mp3") || ($fe == ".wav") || ($fe == ".wma")))
            {
                $tpl->newBlock("AUDIO_PLAY");
                $tpl->assign("subfile_url", $subfile_url);
            }

            /* 若是 Link 檔就設定顯示縮圖 */
            if ($type == "LINK")
            {
                //if (empty($rec["thumbs"]))
                //    $tn_img_url = EMPTY_ICON_URL;
                //else
                //{
                //    $tn_img_url = $output["page_url"].$rec["thumbs"];
                //    if (!empty($output["random_path"]))
                //        $tn_img_url .= "&random_path=".$output["random_path"];
                //}
                //$tpl->assign("tn_img_url", $tn_img_url);
                $tn_img_url = SHOW_TN_URL."?page_url=".$output["sub_file"][$i]["url"]."&type=".LINK_TYPE_NAME;
                if ($isset_random_path == true)
                    $tn_img_url .= "&random_path=".$output["random_path"];
                $tpl->assign("tn_img_url", $tn_img_url);
            }

            /* 2014/10/27 新增,若是管理者且有設定隱藏 tag 就要顯式隱藏 icon */
            if (($output["m_right"] == PASS) && (strstr($output["sub_file"][$i]["tag"], HIDDEN_TAG) !== false))
                $tpl->newBlock($type."_HIDDEN_BLOCK");
            else
                $tpl->newBlock($type."_NO_HIDDEN_BLOCK");
			
			
			// 2015/02/11 whee 檔案權限
			$pw = "N";
			if ($bFilePW && $rec["r_flag"] == "Y" && $rec['owner'] != $login_user["acn"]) {
				$aPW = array();
				if (preg_match($patFilePW, $rec['r_browse']) == 1) $aPW[] = "B";
				if (preg_match($patFilePW, $rec['r_download']) == 1) $aPW[] = "L";
				if (preg_match($patFilePW, $rec['r_upload']) == 1) $aPW[] = "U";
				if (preg_match($patFilePW, $rec['r_edit']) == 1) $aPW[] = "E";
				if (preg_match($patFilePW, $rec['r_del']) == 1) $aPW[] = "D";
				$pw = implode(",", $aPW);
			}
            $tpl->assign("owner", $rec['owner']);
            $tpl->assign("pw", $pw);

        }
    }

    // FilePath to ShowPath
    function block_con_filepath2showpath($page_dir, $file_path)
    {
        $aPath = explode("/", $file_path);
        $path_cnt = count($aPath);
        $show_path = ""; $path = "";
        for ($i = 0; $i < $path_cnt; $i++)
        {
            if ($i > 0)
                $path .= "/";
            if (!empty($show_path))
                $show_path .= " / ";
            $path .= $aPath[$i];
            $show_path .= get_file_name($page_dir, $path);
        }
        return $show_path;
    }

    function get_subdir_content($page_dir, $path)
    {

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        $full_path = $page_dir.$path;
        if (!is_dir($full_path))
            return false;

        /* 讀取目錄內的子目錄與檔案 */
        $l = strlen(NUWEB_SYS_FILE);
        $handle = opendir($full_path);
        $item_cnt = 0;
        while ($dir_file = readdir($handle))
        {
            /* 跳過子目錄名稱為 . & .. 不必處理 */
            if (($dir_file == ".") || ($dir_file == ".."))
                continue;

            /* 過濾掉 *.php & .nuweb_* & symlink & 縮圖 (.thumbs.jpg) & .core 檔 */
            $fe = strtolower(substr($dir_file, strrpos($dir_file, ".")));
            $f_path = str_replace("//", "/", $full_path."/".$dir_file);
            if (($fe == ".php") || (substr($dir_file, 0, $l) == NUWEB_SYS_FILE) || (is_link($f_path)) || (substr($dir_file, -11) == ".thumbs.jpg") || (substr($dir_file, -5) == ".core"))
                continue;

            $sub_name = get_file_name($page_dir, str_replace("//", "/", $path."/".$dir_file));

            if ($item_cnt == 0)
                $buf = $sub_name;
            else
                $buf .= ", $sub_name";
            $item_cnt++;
        }
        $subdir_content = mb_substr($buf, 0, SUBDIR_CONTENT_LEN, 'UTF-8');
        if ($subdir_content != $buf)
            $subdir_content .= " ...";
        return $subdir_content;
    }

    /* 取得目錄內的檔案與子目錄資料 */
    function get_subdir_data($page_dir, $path)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        $full_path = $page_dir.$path;
        if (!is_dir($full_path))
            return false;

        /* 取得目錄內所有檔案的 record */
        $dir_url = str_replace(WEB_ROOT_PATH, "", $full_path);
        $d_recs = get_dir_sublist_rec($dir_url);
        if ($d_recs == false)
            return false;
        /* 依 sub_list 順序排序輸出,若無 sub_list 就依時間順序輸出 */
        if (sort_sub_list($d_recs, $dir_url) == false)
            sort_array($d_recs, "time", "D");
        $d_cnt = count($d_recs);
        $f_num = 0;
        $i_num = 0;
        $v_num = 0;
        $i_data = array();
        $v_data = array();
        $f_data = array();
        for ($i = 0; $i < $d_cnt; $i++)
        {
            switch($d_recs[$i]["type"])
            {
                case IMAGE_TYPE_NAME:
                    $i_data[$i_num++] = $d_recs[$i];
                    break;
                case VIDEO_TYPE_NAME:
                    $v_data[$v_num++] = $d_recs[$i];
                    break;
                default:
                    $f_data[$f_num++] = $d_recs[$i];
                    break;
            }
        }

        /* 2014/4/25 修改,將影片併入圖片中一起輸出 */
        if (!empty($v_data))
        {
            for ($i = 0; $i < $v_num; $i++)
                $i_data[$i_num++] = $v_data[$i];
        }
        /* 依圖片 -> 檔案 的順序,檢查並輸出其中一種資料 */
        if (!empty($i_data))
            return $i_data;
        //else if (!empty($v_data))
        //    return $v_data;
        else
            return $f_data;
    }

    /* 取得功能目錄的樣板框架 */
    function get_frame_fun($page_url, $page_content="")
    {
        Global $lang;

        /* 檢查參數 */
        if (empty($page_url))
            return false;

        /* page_url 開頭必須是 / 且不可以有 ./ */
        if ((substr($page_url, 0, 1) != "/") || (strstr($page_url, "./") != false))
            return false;

        /* 檢查 page_url 是否存在 */
        $page_path = WEB_ROOT_PATH.$page_url;
        if (!file_exists($page_path))
            return false;

        /* 取得 page 的相關資料 */
        $output = get_page_info($page_url);
        if (!is_array($output))
            return false;

        /* 檢查是否有瀏覽權限 */
        if ($output["b_right"] != PASS)
            return false;

        $lang = $output["lang"];
        if (empty($lang))
            $lang = DEF_LANG;

        /* 如果沒傳入 page_content,就預設為 FUN_CONTENT */
        if (empty($page_content))
            $page_content = FUN_CONTENT;
        $output["page_content"] = $page_content;

        /* 讀取樣版檔 */
        $template_file = TEMPLATE_DIR."frame_fun.tpl.$lang";
        $tpl = new TemplateLib($template_file);
        /* 2014/12/17 新增,若是手機或平板就顯示 MOBILE 區塊,否則就顯示 PC 區塊 */
        /* 2015/1/29 修改,只檢查 Android */
        //if (b_is_mobile() == true)
        if (b_is_android() == true)
            $tpl->newBlock("MOBILE");
        else
            $tpl->newBlock("PC");

        assign_global($tpl, $output);
        block_header($tpl, $output);
        block_menu($tpl, $output, "MENU_".$output["dir_set"]["menu_place"]);
        block_function_menu($tpl, $output);
        block_path($tpl, $output);
        block_submenu($tpl, $output);
        block_footer($tpl, $output);

        $content = $tpl->getOutputContent();
        return $content;
    }

    /* 取得搜尋筆數 */
    function get_match_cnt($rec_content)
    {
        if (preg_match("#^total:\s*(\d+)#mi", $rec_content, $m))
            return (int)$m[1];
        return 0;
    }

    /* 整理乾淨的 record 將系統自動產生的欄位過濾掉 (@_ 開頭的欄位) */
    function clean_rec(&$rec)
    {
        foreach ($rec as $key => $value)
        {
            if (substr($key, 0, 1) == "_")
                unset($rec[$key]);
        }
    }
?>
