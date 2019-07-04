<?php
    $session_id = session_id();
    if(empty($session_id)){
        session_cache_expire(30);
        session_start();
        $_SESSION["commit_id"] = session_id();
        session_write_close();
    }
    header("Content-Type: text/html; charset=utf-8");
    require_once("/data/HTTPD/htdocs/tools/public_lib.php");
    require_once("/data/HTTPD/htdocs/API/video_lib.php");

    Global $PHP_SELF, $wns_ser, $wns_port, $lang, $set_conf, $pwd_cookie, $auth_pwd, $ssn_acn, $login_user, $uid, $uacn, $is_manager, $admin_manager, $type_list, $type_cnt, $dir_set;

    define("COOKIE_DOMAIN", "");
    define("SITE", "Site");
    define("SITE_URL", "/Site/");
    define("SITE_PROG_URL", "/Site_Prog/");
    define("PAGE_URL", SITE_URL);
    define("TINY_MCE_URL", SITE_PROG_URL."tiny_mce/");
    define("MIN_SITE_ACN_LEN", 3);
    define("MAX_SITE_ACN_LEN", 32);
    define("MIN_SITE_NAME_LEN", 3);
    define("MAX_SITE_NAME_LEN", 64);

    if (file_exists(NUWEB_CONF))
    {
        $conf = read_conf(NUWEB_CONF);
        if ((isset($conf["lang"])) && (!empty($conf["lang"])) && (file_exists(LANG_LIST_FILE.".".$conf["lang"])))
            $lang = $conf["lang"];
        $company = $conf["show_name"];
    }

    define("DEF_DIR_TYPE", ADMIN_DIR."def_site_dir_type.$lang");
    define("DEF_LOGO_FILE", ADMIN_DIR."def_logo.gif");
    define("DEF_BG_IMG_FILE", "style/images/header.jpg");
    define("STYLE_DIR", NUWEB_DIR."Style/");
    define("STYLE_CNT", 195);
    define("DEF_STYLE", STYLE_DIR."style01/");
    define("STYLE_SYMLINK", "style");
    define("STYLE_URL", "style/");
    #define("SITE_LOGO_FILE", ".nuweb_logo");
    define("SITE_LOGO_FILE", NUWEB_LOGO);
    define("SITE_BG_IMG_FILE", ".nuweb_bg_img");
    define("SITE_FORUM_PATH", ".nuweb_forum");
    define("SITE_PUBLIC_PATH", ".nuweb_public");
    define("PROG_DIR", NUWEB_DIR."Prog/");
    define("CONTENT_TYPE_FILE", "/data/HTTPD/htdocs/API/content_type.php");
    define("TEMPLATE_LIB", "/data/HTTPD/htdocs/API/TemplateLib.php");
    define("WEB_PAGE_DIR", "/data/HTTPD/htdocs/Site/");
    define("WEB_PROG_DIR", "/data/HTTPD/htdocs/Site_Prog/");
    define("WEB_TEMPLATE_DIR", WEB_PROG_DIR."Template/");
    define("INDEX_PROG", "index.php");
    define("SHOW_IMG_PROG", "show_img.php");
    define("SHOW_PAGE_PROG", INDEX_PROG."?file_path=");
    define("SHOW_MULTIPLE_URL", "/tools/ObjectView/ObjectView.php");
    define("PV_ALBUM_URL", "/tools/pv/pv_album.php?file_path=");
    define("PV_VIDEO_URL", "/tools/pv/pv_video.php?file_path=");
    define("DEF_INDEX_PROG", WEB_PROG_DIR."show_index.php");
    define("DEF_SHOW_IMG_PROG", WEB_PROG_DIR.SHOW_IMG_PROG);
    define("DIR_CONFIG", "dir_config.php");
    define("ROOT_PATH", "/data/HTTPD/htdocs");
    define("DEF_DIR_PERSONAL", WEB_PROG_DIR."def_dir_personal.$lang");
    define("DEF_DIR_GROUP", WEB_PROG_DIR."def_dir_group.$lang");
    define("DEF_GROUP_TYPE", "/data/HTTPD/htdocs/tools/Language/htdocs/def_group_type.$lang");
    define("SITE_TPL_LIST", WEB_PROG_DIR."site_tpl.list");
    define("FILE_NAME_CHK", "/[\\/:*?\"<>|]/");
    define("PATH_GAP", " / ");
    define("AUTO_SITE_ACN", "AUTO_SITE_ACN");

    define("TABLE_LIST", "table.list");
    define("DEF_HTML_PAGE", "index.html");
    define("PREFIX_DIR", "dir_");
    define("PREFIX_FILE", "file_");
    define("PREFIX_ARTICLE", "article_");
    define("MAX_BUF_LEN", 1024);
    define("MAX_LIST_CNT", 16);
    define("MAX_PAGELINK_CNT", 10);
    define("PICT_LINE_CNT", 5);

    /* 定義基本的目錄 type (在 DEF_DIR_TYPE 也有設定),因部份程式需使用,所以在此定義 */
    define("TYPE_DATA_DIR", "directory");
    define("TYPE_PAGE_DIR", "page");
    define("TYPE_BLOG_DIR", "blog");
    define("TYPE_ALBUM_DIR", "album");
    define("TYPE_MEDIA_DIR", "media");
    define("TYPE_MULTIMEDIA_DIR", "multimedia");
    define("TYPE_MULTIPLE_DIR", "multiple");
    define("TYPE_FORUM_DIR", "forum");
    define("TYPE_VOTE_DIR", "vote");
    define("TYPE_TABLE_DIR", "table");
    define("TYPE_SHOP_DIR", "shop");
    define("TYPE_MARK_DIR", "mart");
    define("TYPE_BOOKMARK", "bookmark");
    define("TYPE_MESSAGE_CENTER_DIR", "message_center");
    define("TYPE_FRIEND_LIST_DIR", "friend_list");
    define("TYPE_LINK_DIR", "link");
    define("TYPE_USER_PROFILE_DIR", "user_profile");
    /* 定義那些 type 屬於功能目錄 (用空白格開) */
    define("FUNCTION_DIR_TYPE", TYPE_FORUM_DIR." ".TYPE_VOTE_DIR." ".TYPE_TABLE_DIR." ".TYPE_SHOP_DIR." ".TYPE_MARK_DIR." ".TYPE_BOOKMARK." ".TYPE_MESSAGE_CENTER_DIR." ".TYPE_FRIEND_LIST_DIR." ".TYPE_LINK_DIR." ".TYPE_USER_PROFILE_DIR);
    define("FUNCTION_DIR_SIZE", -1);
    /* 2015/11/16 新增,定義一般目錄型態 (一般目錄型態才可變更目錄型態),因在 public_lib.php 已定義過 GENERAL_DIR_TYPE 為一般資料目錄,因此這邊改用 GENERAL_DIR_TYPE_LIST */
    define("GENERAL_DIR_TYPE_LIST", TYPE_DATA_DIR." ".TYPE_PAGE_DIR." ".TYPE_BLOG_DIR." ".TYPE_ALBUM_DIR." ".TYPE_MEDIA_DIR." ".TYPE_MULTIMEDIA_DIR." ".TYPE_MULTIPLE_DIR);

    /* 定義網站類型 */
    define("SITE_TYPE_PERSONAL", 0);
    define("SITE_TYPE_GROUP", 1);
    define("SITE_TYPE_GROUP_ALIAS", 2); /* Group Alias 為群組,並不是真實網站 */

    /* 定義網站申請模式 */
    define("MODE_NOT_OPEN", 0);
    define("MODE_FREE", 1);
    define("MODE_CHECK", 2);
    define("MODE_MANAGER", 3);
    define("MODE_CODE", 4);

    /* 定義成員網站建立方式 */
    define("MEMBER_SITE_DISABLE", 0);
    define("MEMBER_SITE_ENABLE", 1);

    /* 認證狀態 */
    define("STATUS_ALLOW", "A");
    define("STATUS_DENY", "D");
    define("STATUS_WAITING", "W");

    /* 載入訊息檔 */
    require_once(WEB_PROG_DIR."msg_$lang.php");

    /* 如果 PHP 的 magic_quotes_gpc 為 OFF,就將所有參數特殊字元轉換 */
    //if (!get_magic_quotes_gpc())
    //    $_REQUEST = array_map("addslashes", $_REQUEST);
    /* 2015/3/4 修改,不用 array_map 處理,改 call addslashes_request 函數 (在 public_lib.php 中) */
    addslashes_request();

    $PHP_SELF = $_SERVER["SCRIPT_NAME"];

    /* 讀取設定檔 */
    $set_conf = read_conf(SETUP_CONFIG);
    /* 2015/3/9 修改,若尚未註冊且來源位置不是註冊程式,就一律跳到註冊程式中 */
    /* 2015/5/22 修改,增加檢查來源程式要跳過網路設定程式 */
    //if($reg_conf === false){
    if (($reg_conf === false) && ($PHP_SELF !== "/Admin/register.php") && ($PHP_SELF !== "/Admin/register_set.php") && ($PHP_SELF !== "/Admin/network_set.php") && ($PHP_SELF !== "/Admin/network_save.php"))
    {
        header("Location: /Admin/register.php");
        exit;
    }

    /* 若沒設定 sunsite_mode 而且 NUWEB_CS_TYPE 為 HOME (代表是家庭伺服器),將 subsite_mode 設為自由申請 */
    if ((empty($set_conf["subsite_mode"])) && (NUWEB_CS_TYPE == "HOME"))
        $set_conf["subsite_mode"] = MODE_FREE;

    /* 檢查是否有開放網站申請 (如果沒開放,就回傳沒有使用權限) */
    /* 2015/9/17 取消檢查 subsite_mode (即使沒開放還是可瀏覽) */
    //if ((empty($set_conf["subsite_mode"])) || ($set_conf["subsite_mode"] == MODE_NOT_OPEN))
    //{
    //    header("HTTP/1.0 403 Forbidden");
    //    //header("Location: http://localhost/Admin/setup.php");
    //    exit;
    //}

    /* 2015/2/10 修改,將密碼編碼方式改用 auth_encode */
    if (isset($_COOKIE["auth_pwd"]))
        $pwd_cookie = $_COOKIE["auth_pwd"];
    else
        $pwd_cookie = NULL;
    if (isset($_SERVER["PHP_AUTH_PW"]))
        $auth_pwd = auth_encode($_SERVER["PHP_AUTH_PW"]);
    else
        $auth_pwd = NULL;

    /* 取得 dir type list */
    $type_list = get_dir_type_list();
    $type_cnt = count($type_list);

    require_once(TEMPLATE_LIB);

    /************/
    /*  函數區  */
    /************/

    /* 檢查網站是否存在 */
    function site_exists($page_dir, $site_acn)
    {
        $acn = strtolower($site_acn);

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 檢查目錄是否已存在 */
        $site_dir = $page_dir.$acn;
        if (is_dir($site_dir))
            return true;
        else
            return false;
    }

    /* 取得網站的設定資料 */
    function get_site_conf($path_name)
    {
        Global $login_user;

        /* 讀取網站設定 */
        $path = explode("/", $path_name);
        $conf_file = WEB_PAGE_DIR.$path[0]."/".NUWEB_CONF;
        $site_conf = read_conf($conf_file);

        return $site_conf;
    }

    /* 2015/8/4 新增,執行更新網站設定資料時所須處理的工作 */
    function update_site_conf($site_acn, $conf, $mode="new")
    {
        if ((empty($site_acn)) || (empty($conf)))
            return false;
        $site_acn = strtolower($site_acn);
        $site_dir = WEB_PAGE_DIR.$site_acn."/";
        $conf_file = $site_dir.NUWEB_CONF;

        /* 2015/10/6 新增檢查網站設定資料是否正確 (目前檢查 site_acn / name / owner / crt_time / status 等欄位必須有資料,type 欄位必須有設定) */
        if ((empty($conf["site_acn"])) || (empty($conf["name"])) || (empty($conf["owner"])) || (empty($conf["crt_time"])) || (empty($conf["status"])) || (!isset($conf["type"])))
            return false;

        /* 2015/10/28 新增,檢查是否有空欄位,若有必須清除 */
        if (isset($conf[""]))
            unset($conf[""]);

        /* 更新網站設定資料 & Site index & modify.list */
        write_conf($conf_file, $conf);
        /* 將網站資訊更新到 DB 中 */
        site2db($site_acn);
        /* 將網站設定資料上傳到 Group Server */
        group_upload_site_conf($conf);
        /* 更新 Site_Index 資料 */
        update_site_index($site_acn);
        /* 將網站設定檔上傳到 wns server 建立 Global Site Index */
        update_cs_site_conf($site_acn);
        /* 設定 modify.list */
        write_modify_list($mode, $conf_file, "conf");
    }

    /* 取得網站的 member list */
    function get_site_member($path_name)
    {
        /* 讀取網站的 member list */
        $path = explode("/", strtolower($path_name));
        $site_acn = $path[0];
        if ((empty($site_acn)) || (site_exists(WEB_PAGE_DIR, $site_acn) == false))
            return false;

        /* 取得網站的設定資料,找出 member 資料 */
        /* 2014/10/9 修改,owner 與 manager 都是網站 member */
        $site_conf = get_site_conf($site_acn);
        /* 2015/5/5 修改,若沒有設定 member 資料,就不必使用 $site_conf["member"] 資料 */
        //$member_list = $site_conf["owner"].",".$site_conf["manager"].",".$site_conf["member"];
        $member_list = $site_conf["owner"].",".$site_conf["manager"];
        if ((isset($site_conf["member"])) && (!empty($site_conf["member"])))
            $member_list .= ",".$site_conf["member"];
        $m_list = explode(",", $member_list);
        /* 整理並過濾重覆資料 */
        $cnt = count($m_list);
        $member = NULL;
        $n = 0;
        for ($i = 0; $i < $cnt; $i++)
        {
            $acn = trim($m_list[$i]);
            /* 2015/2/6 修改,檢查 acn 中是否有 '.' 若有就先跳過 (要避免 member list 中有 {site_acn}.{cs} 的資料,有可能造成無窮迴圈)*/
            /* 2015/10/5 修改,acn 有 '.' 時還要檢查是否有 '@' 若有就必須保留 (因為是成員的 mail,並不是 site_id 格式) */
            if ((empty($acn)) || ((strstr($acn, ".") !== false) && (strstr($acn, "@") == false)))
                continue;
            $exists = false;
            for ($j = 0; $j < $n; $j++)
            {
                if ($acn == $member[$j])
                {
                    $exists = true;
                    break;
                }
            }
            if ($exists == true)
                continue;
            $member[$n++] = $acn;
        }
        return $member;
    }

    /* 檢查網站狀態 */
    function get_site_status($path_name)
    {
        /* 讀取網站設定 */
        $site_conf = get_site_conf($path_name);

        /* 回傳網站的 status */
        return $site_conf["status"];
    }

    /* 建立目錄 */
    function new_dir($page_dir, $path, $name, $dir_type="", $real_dir_name="", $hidden=false, $upload_dymanic=false, $site_conf=NULL)
    {
        Global $type_list, $type_cnt;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 一定要傳 path (第一層目錄一律是子網站使用,不能建立一般目錄與檔案) */
        if (empty($path))
            return false;
        $path = str_replace("//", "/", $path."/");
        $path = str_replace("//", "/", $path);
        /* 檢查目錄是否已存在 */
        if (filename_exists($page_dir, $path, $name) != false)
            return false;

        /* 檢查目前網站使用空間是否超出 quota,若已超出就不可建立新目錄 */
        list($site_acn, $other) = explode("/", $path, 2);
        $status = chk_site_quota($site_acn);
        if (($status === QUOTA_OVER) || ($status === SYSTEM_QUOTA_OVER))
            return false;

        /* 整理要建立的真實目錄名稱 */
        if (!empty($real_dir_name))
        {
            /* 如果有傳入 real_dir_name,就用該名稱來建立目錄,但必須先檢查該目錄是否已存在 */
            $dir = $path.$real_dir_name;
            $real_dir =  $page_dir.$dir."/";
            if (is_dir($real_dir))
                return false;
        }
        else
        {
            /* 2014/2/25 修改,改用 tempnam 建立目錄名稱 (最後須先 unlink 系統才能建立目錄) */
            $tmp_file = tempnam($page_dir.$path, PREFIX_DIR);
            if (empty($tmp_file))
                return false;
            /* 2014/5/29 修改,不可直接用 tmp_file 來整理 real_dir 與 dir,若 /data/ 是 Symlink 抓到的 tmp_file 就不是 page_dir 開頭的位置,整理後會錯誤*/
            $tmp_name = substr($tmp_file, strrpos($tmp_file, "/")+1);
            $dir = $path.$tmp_name;
            $real_dir = $page_dir.$dir."/";
            unlink($tmp_file);
        }

        if (mkdir($real_dir) == false)
            return false;
        touch($real_dir.DEF_HTML_PAGE);

        /* 將 dir_type 繼承到新建目錄,如果沒有設定 dir_type 就用系統預設的目錄模式 */
        $type_file = $page_dir.$path.NUWEB_TYPE;
        if (file_exists($type_file))
        {
            $type = read_conf($type_file);
            $def_type_mode = $type["DIR_TYPE"];
        }

        $type_flag = false;
        for ($i = 0; $i < $type_cnt; $i++)
        {
            /* 如果沒有繼承原目錄的 dir_type,就用第一個 type 當成預設的 type */
            if ((empty($def_type_mode)) && ($i == 0))
                $def_type_mode = $type_list[$i]['mode'];
            /* 取得 def_type_mode 對應的 def_type_prog & is_prog */
            if ($def_type_mode == $type_list[$i]['mode'])
            {
                $def_type_prog = $type_list[$i]['prog'];
                $def_is_prog = $type_list[$i]['is_prog'];
            }

            /* 檢查傳送進來的 dir_type 是否正確 */
            if ($dir_type == $type_list[$i]['mode'])
            {
                $type_flag = true;
                $type_prog = $type_list[$i]['prog'];
                $is_prog =  $type_list[$i]['is_prog'];
                break;
            }
        }

        /* 如果 dir_type 不正確就用預設的 type */
        if ($type_flag == false)
        {
            $dir_type = $def_type_mode;
            $type_prog = $def_type_prog;
            $is_prog =  $def_is_prog;
        }

        /* 將目錄模式記錄到目錄模式設定檔中 */
        $type["DIR_TYPE"] = $dir_type;
        $type_file = $real_dir.NUWEB_TYPE;
        write_conf($type_file, $type);

        /* 如果目錄模式是 table mode 或 shop mode 就記錄到 table list 中 */
        if (($dir_type == TYPE_TABLE_DIR) || ($dir_type == TYPE_SHOP_DIR))
        {
            $fp = fopen($page_dir.TABLE_LIST, "a");
            fputs($fp, $dir."\n");
            fclose($fp);
        }

        /* 2014/7/1 新增,若目錄型態是 blog 且目錄內已存在 .nuweb_submenu 就自動將新建立目錄加入 .nuweb_submenu 中 */
        /* 2015/3/9 修改,若是系統目錄 (.nuweb_*) 就不加入 .nuweb_submenu 中 */
        $submenu_file = $page_dir.$path.NUWEB_SUBMENU;
        if (($dir_type == TYPE_BLOG_DIR) && (file_exists($submenu_file)) && (strstr($dir, NUWEB_SYS_FILE) === false))
        {
            $fp = fopen($submenu_file, "a");
            fputs($fp, $tmp_name);
            fclose($fp);
        }

        /* 將目錄設定檔繼承到新建目錄,但須調整目錄型態與版型設定 */
        $update_dir_set = false;
        $dir_set_file = $page_dir.$path.NUWEB_DIR_SET;
        if (file_exists($dir_set_file) && substr_count($path, "/") > 1)
        {
            /* 先取得上層目錄的 .nuweb_dir_set 設定內容 */
            $dir_set = read_conf($dir_set_file);
            /* 若是功能目錄 (有相關程式),就不需要設定 type 與 tpl_mode 項目 */
            if (!empty($type_prog))
            {
                if (isset($dir_set["type"]))
                    unset($dir_set["type"]);
                if (isset($dir_set["tpl_mode"]))
                    unset($dir_set["tpl_mode"]);
            }
            else
            {
                /* 若不是功能目錄,type 與 tpl_mode 都設為 dir_type (tpl_mode 預設為與 dir_type 相同名稱) */
                $dir_set["type"] = $dir_type;
                $dir_set["tpl_mode"] = $dir_type;
            }
            /* 2015/4/21 修改,暫時不儲存,先設定 update_dir_set 為 true,代表要更新 dir_set 資料,後面再儲存 (因為後面還要處理 tpl_mode) */
            $update_dir_set = true;
        }

        /* 2015/4/21 新增,若 update_dir_set 為 true,代表要更新 dir_set 資料,將 dir_set 資料儲存到目錄設定檔 */
        if ($update_dir_set == true)
        {
            $dir_set_file = $real_dir.NUWEB_DIR_SET;
            write_conf($dir_set_file, $dir_set);
        }

        /* 如果有相關程式 (Prog),就在目錄中寫入相關設定檔,並將程式 copy 進目錄中 */
        if (!empty($type_prog))
        {
            /* 若 is_prog 為 TRUE 才需要建立 dir_config.php */
            if ($is_prog == TRUE)
                write_dir_config($page_dir, $dir);
            copy_prog($real_dir, $type_prog);
        }

        /* 如果有設定 .nuweb_menu 且新增目錄不是 .files 目錄,就自動將新增的目錄加入 */
        /* 2015/3/9 修改,若是系統目錄 (.nuweb_*) 就不加入 .nuweb_submenu 中 */
        $menu_file = $page_dir.$path.NUWEB_MENU;
        if ((file_exists($menu_file)) && ((substr($name, -6) != ".files")) && (strstr($dir, NUWEB_SYS_FILE) === false))
        {
            $n = strrpos($dir, "/");
            if ($n !== false)
                $dir_name = substr($dir, $n+1);
            else
                $dir_name = $dir;
            $fp = fopen($menu_file, "a");
            flock($fp, LOCK_EX);
            fputs($fp, $dir_name."\n");
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        /* 建立目錄的 record 檔案 */
        write_def_record($page_dir, $dir, $name, false, NULL, false, $hidden);

        /* 檢查並上傳動態 share record */
        /* 2014/12/29 修改,若 upload_dymanic 為 false 時,就不送出動態訊息 */
        if ($upload_dymanic !== false)
            upload_dymanic_share_rec($page_dir, $dir, "new");

        return $dir;
    }

    /* 建立網站目錄 */
    function new_site_dir($page_dir, $site_acn, $name, $owner, $type=SITE_TYPE_PERSONAL, $auto=false, $site_mode="", $public=YES, $def_right_info=NULL, $tpl_mode=NULL, $member=NULL)
    {
        Global $login_user, $uacn, $is_manager, $admin_manager, $lang, $set_conf;

        if ($site_acn !== AUTO_SITE_ACN)
        {
            $site_acn = strtolower($site_acn);
            $acn = $site_acn;
        }
        $crt_time = date("Y-m-d H:i", time());
        $log_year = date("Y");
        $log_date = date("Ymd");

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        if ($type != SITE_TYPE_GROUP)
            $type = SITE_TYPE_PERSONAL;
        if ($public != NO)
            $public = YES;

        /* 2015/11/19 新增,site_acn 第二字元不可為 '_' (為了保留其他功能使用,例:群組開頭會是 'g_') */
        if (substr($site_acn, 1, 1) == "_")
            return false;
        /* 2014/4/9 新增,若是要建立社群網站,必須檢查是否為合法的網站管理者 */
        if (($type == SITE_TYPE_GROUP) && ($login_user["site_manager"] !== true))
            return false;

        /* 如果有傳入 auto 為 true,代表是要自動建立成員網站 */
        if ($auto == true)
        {
            /* 檢查是否為系統成員 */
            $g_user = get_group_user();
            $cnt = count($g_user);
            $is_group_user = false;
            for ($i = 0; $i < $cnt; $i++)
            {
                /* 2015/6/24 修改,檢查 site_acn 或 owner 在系統成員名單中 */
                if (($g_user[$i] == $site_acn) || ($g_user[$i] == $owner))
                {
                    $is_group_user = true;
                    break;
                }
            }
            if ($is_group_user == false)
                return false;
        }
        else
        {
            /* 如果沒有登入或是系統沒開放網站申請,就直接回傳 false */
            if ((empty($uacn)) || ($set_conf["subsite_mode"] == MODE_NOT_OPEN))
                return false;
            /* 如果網站申請是由系統管理者建立,不是管理者且要申請個人網站就直接回傳 false */
            /* 2015/3/19 修改,除系統管理者(目前已不使用)外,後端管理者也可建立個人網站 */
            if (($set_conf["subsite_mode"] == MODE_MANAGER) && ($is_manager != true) && ($admin_manager != true) && ($type == SITE_TYPE_PERSONAL))
                return false;
        }
        /* 讀取 CS 的資料 */
        $reg_conf = read_conf(REGISTER_CONFIG);
        $cs = strtolower($reg_conf["acn"]);

        /* 檢查目錄是否已存在,如果不存在就建立 */
        /* 2015/5/29 修改,若 site_acn 是 AUTO_SITE_ACN 代表要自動建立網站 id (site_acn),就用亂數建立網站目錄 */
        if ($site_acn == AUTO_SITE_ACN)
        {
            /* 用亂數(要轉成小寫)建立網站目錄 (前面加上 owner_ 以方便尋找) */
            $tmp_dir = tempdir($page_dir, $owner."_", "lower");
            if ($tmp_dir == false)
                return false;
            /* 成功建立網站目錄後,就將網站目錄的名稱設定成 site_acn 與 acn */
            $site_acn = substr($tmp_dir, strrpos($tmp_dir, "/")+1);
            $acn = $site_acn;
            $site_id = "$acn.$cs";
            $site_dir = $page_dir.$acn."/";
        }
        else
        {
            $site_id = "$acn.$cs";
            $site_dir = $page_dir.$acn."/";
            if (is_dir($site_dir))
                return $site_id;
            if (mkdir($site_dir) == false)
                return false;
        }

        /* 設定網站狀態 */
        /* 2015/3/19 修改,也要檢查是否為後端管理者 */
        if (($set_conf["subsite_mode"] == MODE_CHECK) && ($auto == false) && ($is_manager != true) && ($admin_manager != true))
            $status = STATUS_WAITING;
        else
            $status = STATUS_ALLOW;
        /* 2014/4/9 修改,若是建立 web 或是社群網站,一律設為 Allow */
        if (($site_acn == "web") || ($type == SITE_TYPE_GROUP))
            $status = STATUS_ALLOW;

        /* 向 wns 註冊新網站 */
        if (new_cs_site($site_acn, $name, $owner, $type, $status, $crt_time) !== true)
        {
            rmdir($site_dir);
            return false;
        }

        touch($site_dir.DEF_HTML_PAGE);

        /* 將網站目錄記錄到 site list 中 */
        $fp = fopen($page_dir.SITE_LIST, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $acn."\t".$name."\t".$owner."\t".$crt_time."\t".$status."\n");
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 2015/3/26 新增,若有傳入預設權限資料,需先將 + 轉換成 site_id,並依權限狀態變更是否公開 */
        if (!empty($def_right_info))
        {
            /* 將所有權限名單中,若有 + (SITE_MEMBER) 都改成 site_id */
            if (isset($def_right_info["browse"]))
                $def_right_info["browse"] = str_replace(SITE_MEMBER, $site_id, $def_right_info["browse"]);
            if (isset($def_right_info["download"]))
                $def_right_info["download"] = str_replace(SITE_MEMBER, $site_id, $def_right_info["download"]);
            if (isset($def_right_info["upload"]))
                $def_right_info["upload"] = str_replace(SITE_MEMBER, $site_id, $def_right_info["upload"]);
            if (isset($def_right_info["edit"]))
                $def_right_info["edit"] = str_replace(SITE_MEMBER, $site_id, $def_right_info["edit"]);
            if (isset($def_right_info["del"]))
                $def_right_info["del"] = str_replace(SITE_MEMBER, $site_id, $def_right_info["del"]);
            /* 若瀏覽或下載名單中有 * (ALL_USER) 或 W (WNS_MEMBER) 或 site_owner 就設為公開,否則就設為不公開 */
            $chk_list = $def_right_info["browse"].",".$def_right_info["download"];
            if ((strstr($chk_list, ALL_USER) !== false) || (strstr($chk_list, WNS_MEMBER) !== false) || (strstr($chk_list, SITE_OWNER) !== false))
                $public = YES;
            else
                $public = NO;
        }

        /* 設定目錄模式 */
        $dir_type["DIR_TYPE"] = TYPE_PAGE_DIR; /* 網站目錄第一層一律先設為 page (網頁模式) */
        $dir_type["DIR_LANG"] = $lang;         /* 使用預設語系 */
        $type_file = $site_dir.NUWEB_TYPE;
        write_conf($type_file, $dir_type);

        /* 2015/4/17 新增,若有傳入 tpl_mode 參數,就要設定到 .nuweb_dir_set 中 */
        if (!empty($tpl_mode))
        {
            $dir_set_file = $site_dir.NUWEB_DIR_SET;
            $dir_set["type"] = TYPE_PAGE_DIR;
            $dir_set["tpl_mode"] = $tpl_mode;
            $dir_set["def_frame"] = NO; /* 有傳入 tpl_mode 代表要使用特殊樣版,一律將 def_frame 設為 N */
            write_conf($dir_set_file, $dir_set);
        }

        /* 向 wns 取得 owner 的資料 */
        $user = get_user_data($owner);

        /* 儲存網站設定檔 */
        $conf["site_acn"] = $site_acn; /* 網站帳號 */
        $conf["name"] = $name;         /* 網站名稱 */
        $conf["owner"] = $owner;       /* 網站所有人 (申請者) */
        if ($user !== false)           /* 網站所有人相關註冊資料 */
            $conf["owner_info"] = $user["ssn"].":".$user["acn"].":".$user["mail"].":".$user["sun"];
        $conf["manager"] = $owner;     /* 預設 owner 為網站管理者 */
        $conf["crt_time"] = $crt_time; /* 網站建立時間 */
        $conf["status"] = $status;     /* 目前審核狀態 */
        $conf["type"] = $type;         /* 網站類型 */
        $conf["public"] = $public;     /* 2014/8/26 新增,網站是否公開 */
        /* 2015/6/16 新增,網站成員名單欄位 */
        if (!empty($member))
            $conf["member"] = $member;
        /* 若有設定子網站預設 Quota,就要使用預設 Quota */
        if ((!empty($set_conf["subsite_def_quota"])) && ($set_conf["subsite_def_quota"] > 0))
            $conf["quota"] = $set_conf["subsite_def_quota"];
        /* 2014/5/10 新增,若有傳入 site_mode 也記錄下來 */
        if (!empty($site_mode))
            $conf["site_mode"] = $site_mode;
        /* 2015/8/4 修改,改 call update_site_conf 函數來處理更新網站設定資料的相關工作 */
        update_site_conf($site_acn, $conf, "new");

        /* 2015/6/9 新增,記錄 register log (若目錄不存在就建立) */
        if (!is_dir(REGISTER_LOG_DIR))
            mkdir(REGISTER_LOG_DIR);
        $log_dir = REGISTER_LOG_DIR.$log_year;
        if (!is_dir($log_dir))
            mkdir($log_dir);
        $log_file = $log_dir."/".$log_date;
        write_server_log($log_file, "$site_acn\t$name\t$owner\t$type");

        /* 2014/5/6 新增,將管理者資料記錄到 site_manager.list 中 */
        $fp = fopen(SITE_MANAGER_LIST, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$site_acn\t$owner,$owner\n");
        flock($fp, LOCK_UN);
        fclose($fp);
        /* 2014/7/8 新增,將 site_manager.list 上傳至 Group Server */
        group_upload_site_manager();

        /* 2014/1/25 新增,若是個人網站且有設定會員預設 Quota,就要使用預設 Quota */
        if (($type == SITE_TYPE_PERSONAL) && (!empty($set_conf["member_def_quota"])) && ($set_conf["member_def_quota"] > 0))
            set_member_quota($owner, $set_conf["member_def_quota"]);

        /* 2013/11/20 新增,先建立網站第一層的 record 與 dir index 目錄,以避免目錄建立失敗會造成顯示上的問題 */
        mkdir($site_dir.NUWEB_REC_PATH);
        rdb_gen($site_dir.NUWEB_REC_PATH.DIR_INDEX);

        /* 建立網站目錄的 record 檔案 (2013/7/18 調整,必須在建立預設目錄之前先建立 record 檔,要不然無法建立網站第一層的 dir index) */
        write_def_record($page_dir, $acn, $name);

        /* 2015/3/26 修改,若有傳入預設權限設定資料,就將權限資料寫入 record 中 */
        if (!empty($def_right_info))
            set_rec_right_info($site_dir, $def_right_info);

        /* 建立預設的目錄 */
        if ($type == SITE_TYPE_GROUP)
            $def_dir = @file(DEF_DIR_GROUP);
        else
            $def_dir = @file(DEF_DIR_PERSONAL);
        $dir_cnt = count($def_dir);
        for ($i = 0; $i < $dir_cnt; $i++)
        {
            /* 2014/5/10 修改,多增加一個 s_mode 參數,若有設定此欄位,代表是特定 site_mode 才會建立的目錄 */
            /* 2015/1/24 修改,再多加一個 s_public 參數,若此欄位為 NO 代表預設不開放,僅提供成員瀏覽 */
            $s_mode = "";
            list($dir_name, $dir_type, $real_dir_name, $s_mode, $s_public) = explode("\t", trim($def_dir[$i]));
            if (($s_mode != "") && ($site_mode != $s_mode))
                continue;
            /* 若 dir_name 或 dir_type 為空的就不處理 */
            if ((empty($dir_name)) || (empty($dir_type)))
                continue;

            /* 2014/12/29 修改,檢查 real_dir_name 若有 / 代表不是第一層目錄,需調整 new_dir 的參數內容 */
            $path = $acn;
            if (($n = strrpos($real_dir_name, "/")) != false)
            {
                $path .= "/".substr($real_dir_name, 0, $n);
                $real_dir_name = substr($real_dir_name, $n+1);
            }
            /* 2014/12/29 修改,建立網站的預設目錄時不送出動態訊息 */
            $real_dir_path = new_dir($page_dir, $path, $dir_name, $dir_type, $real_dir_name, false, false, $conf);
            /* 2015/1/16 新增,若目錄建立失敗就跳過 */
            if ($real_dir_path == false)
                continue;
            /* 2015/1/24 新增,若 s_public 為 NO 代表目錄預設不開放,僅提供成員瀏覽 */
            $dir_path = $page_dir.$real_dir_path;
            if ($s_pblic == NO)
            {
                /* 先取得目錄預設權限資料,並調整瀏覽與下載權限後再寫入 record 中 */
                $right_info = get_rec_right_info($dir_path);
                /* 將瀏覽與下載權限都改成網站成員 (直接設定 site_id) */
                $right_info["browse"] = $site_id;
                $right_info["download"] = $site_id;
                set_rec_right_info($dir_path, $right_info);
            }
            /* 若是網站的 Driver 目錄需建立預設權限 (所有人不可瀏覽與下載),因 get_rec_right_info 會自動檢查 Driver 目錄,所以直接將回傳資料寫入 record 即可 */
            if ($real_dir_name == "Driver")
            {
                $right_info = get_rec_right_info($dir_path);
                set_rec_right_info($dir_path, $right_info);
            }
        }

        /* 2015/3/13 新增,預設建立 .nuweb_forum 目錄 (權限: 所有人可瀏覽下載,wns 會員可上傳編輯) */
        /* 2015/5/19 修改,權限調整 wns 會員有刪除權限,網站成員有所有權限 */
        /* 2015/6/11 修改,若是社群網站,所有權限一律改成 site_id,若是個人網站就檢查是否公開,若公開就採用原設定權限,若不公開就不設定權限 */
        $real_dir_path = new_dir($page_dir, $acn, SITE_FORUM_PATH, TYPE_FORUM_DIR, SITE_FORUM_PATH, false, false, $conf);
        $dir_path = $page_dir.$real_dir_path;
        if ($type == SITE_TYPE_GROUP)
        {
            $r_info["browse"] = $site_id;
            $r_info["download"] = $site_id;
            $r_info["upload"] = $site_id;
            $r_info["edit"] = $site_id;
            $r_info["del"] = $site_id;
        }
        if ($type == SITE_TYPE_PERSONAL)
        {
            if ($public == NO)
                return $site_id;
            $r_info["browse"] = ALL_USER.",".$site_id;
            $r_info["download"] = ALL_USER.",".$site_id;
            $r_info["upload"] = WNS_MEMBER.",".$site_id;
            $r_info["edit"] = WNS_MEMBER.",".$site_id;
            $r_info["del"] = WNS_MEMBER.",".$site_id;
        }
        set_rec_right_info($dir_path, $r_info);

        /* 2015/8/7 新增,預設建立 .nuweb_public 目錄 (用來儲存公開資料,如網站圖檔...等),設定權限瀏覽與下載全部開放 */
        $real_dir_path = new_dir($page_dir, $acn, SITE_PUBLIC_PATH, TYPE_DATA_DIR, SITE_PUBLIC_PATH, false, false);
        $dir_path = $page_dir.$real_dir_path;
        $r_info["browse"] = ALL_USER;
        $r_info["download"] = ALL_USER;
        $r_info["upload"] = $site_id;
        $r_info["edit"] = $site_id;
        $r_info["del"] = $site_id;
        set_rec_right_info($dir_path, $r_info);

        return $site_id;
    }

    /* 建立成員目錄 (設定權限僅該成員可瀏覽與上傳) */
    function create_member_dir($page_dir, $path)
    {
        Global $lang;

        /* 檢查參數,path 必須是目錄才可使用 */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        $dir_path = $page_dir.$path;
        if (substr($dir_path, -1) != "/")
            $dir_path .= "/";
        if (!is_dir($dir_path))
            return false;

        /* 檢查目前網站使用空間是否超出 quota,若已超出就不可建立新目錄 */
        list($site_acn, $other) = explode("/", $path, 2);
        $status = chk_site_quota($site_acn);
        if (($status === QUOTA_OVER) || ($status === SYSTEM_QUOTA_OVER))
            return false;

        /* 檢查是否有網站管理權限,有權限才可執行本功能 */
        if (chk_manager_right($site_acn) !== PASS)
            return false;

        /* 2015/3/13 新增,檢查是否在 Members 目錄內 */
        $in_site_member_dir = false;
        if ($dir_path == $page_dir.$site_acn."/".MEMBER_PATH."/")
            $in_site_member_dir = true;

        /* 取得成員名單,並檢查是否有重覆或已建立 */
        $m_list = get_site_member($site_acn);
        $cnt = count($m_list);
        $n = 0;
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 檢查成員目錄是否已建立,若已建立就跳過 */
            $member_dir = $dir_path.$m_list[$i];
            if (is_dir($member_dir))
                continue;

            /* 取得成員資料 */
            $member_data = get_user_data($m_list[$i]);
            if ($member_data == false)
                continue;
            /* 整理相關資料 */
            $member_acn = strtolower($member_data["acn"]);
            $member_sun = $member_data["sun"];
            $member_name = $member_sun;
            for ($j = 0; $j < $n; $j++)
            {
                /* 檢查成員是否重覆,若重覆就跳過不處理 */
                if ($member[$j]["acn"] == $member_acn)
                    break;
                /* 檢查 sun 是否重覆,若重覆就改用 sun(acn) 當目錄名稱,以避免目錄名稱相同 */
                if ($member[$j]["sun"] == $member_sun)
                {
                    $member[$j]["name"] = $member[$j]["sun"]."(".$member[$j]["acn"].")";
                    $member_name = $member_sun."(".$member_acn.")";
                }
            }

            /* 檢查目錄名稱是否已存在,若已存在也改用 sun(acn) 當目錄名稱,以避免目錄名稱相同 */
            if (filename_exists($page_dir, $path, $member_name) != false)
                 $member_name = $member_sun."(".$member_acn.")";
            $member[$n]["dir"] = $member_dir;
            $member[$n]["acn"] = $member_acn;
            $member[$n]["sun"] = $member_sun;
            $member[$n]["name"] = $member_name;
            $n++;
        }
        /* 實際建立成員目錄 */
        for ($i = 0; $i < $n; $i++)
        {
            /* 2015/3/12 修改,建立成員目錄時先不送動態訊息 */
            $member_path = new_dir($page_dir, $path, $member[$i]["name"], "", $member[$i]["acn"], false, false);
            /* 若成員目錄建立失敗就先跳過 */
            if ($member_path == false)
                continue;

            /* 2015/2/10 修改,先取得目錄預設權限資料,並調整瀏覽與下載權限後再寫入 record 中 */
            $member_dir = $page_dir.$member_path;
            $right_info = get_rec_right_info($member_dir);
            /* 將瀏覽/下載/上傳/編輯權限都改成成員本人 */
            $right_info["browse"] = $member[$i]["acn"];
            $right_info["download"] = $member[$i]["acn"];
            $right_info["upload"] = $member[$i]["acn"];
            $right_info["edit"] = $member[$i]["acn"];
            /* 2015/3/13 新增,檢查若是 Members 目錄內成員目錄,就設定刪除與設定權限 */
            if ($in_site_member_dir == true)
            {
                $right_info["del"] = $member[$i]["acn"];
                $right_info["set"] = $member[$i]["acn"];
            }
            set_rec_right_info($member_dir, $right_info);

            /* 2015/3/12 新增,送出動態訊息 */
            upload_dymanic_share_rec($page_dir, $member_path, "new");

            /* 紀錄到 modify.list 中 */
            write_modify_list("new", $page_dir.$member_path, "dir");
        }
        return true;
    }

    /* 將程式解壓縮放進目錄內 */
    function copy_prog($real_dir, $file)
    {
        Global $type_list;
        $prog_file = PROG_DIR.$file;

        chdir($real_dir);
        /* 先刪除預設的 index.html */
        //if ($type_list["is_prog"] == TRUE)
        //    unlink(DEF_HTML_PAGE);

        /* 將相關程式 copy 進來 */
        $cmd = SYS_TAR." --no-same-owner -xf $prog_file";
        $fp = popen($cmd, "r");
        pclose($fp);
    }

    /* 在目錄中寫入相關設定檔 */
    function write_dir_config($page_dir, $dir)
    {
        Global $lang;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        if (substr($dir, -1) != "/")
            $dir .= "/";

        $folder_dir = $page_dir.$dir;
        $folder_url = str_replace(ROOT_PATH, "", $folder_dir);
        $web_site_dir = $page_dir;
        $fp = fopen($folder_dir.DIR_CONFIG, "w");
        fwrite($fp, "<?php\n");
        fwrite($fp, "    \$folder_dir = \"$folder_dir\";\n");
        fwrite($fp, "    \$folder_url = \"$folder_url\";\n");
        fwrite($fp, "    \$web_site_dir = \"$web_site_dir\";\n");
        fwrite($fp, "    \$lang = \"$lang\";\n");
        fwrite($fp, "?>\n");
        fclose($fp);
    }

    /* 取得 dir type list */
    function get_dir_type_list()
    {
        $type_buf = @file(DEF_DIR_TYPE);
        $type_cnt = count($type_buf);
        for ($i = 0; $i < $type_cnt; $i++)
        {
            //list($type_list[$i]['name'], $type_list[$i]['mode'], $type_list[$i]['prog'], $type_list[$i]['show']) = explode("\t", trim($type_buf[$i]));
            $t_list = explode("\t", trim($type_buf[$i]));
            if ((empty($t_list[0])) || (empty($t_list[1])))
                continue;
            $type_list[$i]['name'] = $t_list[0];
            $type_list[$i]['mode'] = $t_list[1];
            $type_list[$i]['prog'] = NULL;
            $type_list[$i]['show'] = NULL;
            if (isset($t_list[2]))
            {
                if (substr($t_list[2], 0, 1) == "!")
                {
                    $type_list[$i]['prog'] = substr($t_list[2], 1);
                    $type_list[$i]['is_prog'] = FALSE;
                }
                else
                {
                    $type_list[$i]['prog'] = $t_list[2];
                    $type_list[$i]['is_prog'] = TRUE;
                }
            }
            else
                $type_list[$i]['is_prog'] = FALSE;
            if (isset($t_list[3]))
                $type_list[$i]['show'] = $t_list[3];
        }
        return $type_list;
    }

    /* 建立新的 Article */
    function new_article($page_dir, $path, $name, $content="", $fmtime="", $real_file_name="")
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 一定要傳 path (第一層目錄一律是子網站使用,不能建立一般目錄與檔案) */
        if (empty($path))
            return false;
        $path = str_replace("//", "/", $path."/");
        /* 檢查檔案是否已存在 */
        if (filename_exists($page_dir, $path, $name) != false)
            return false;

        /* 檢查目前網站使用空間是否超出 quota,若已超出就不可新增 Article */
        list($site_acn, $other) = explode("/", $path, 2);
        $status = chk_site_quota($site_acn);
        if (($status === QUOTA_OVER) || ($status === SYSTEM_QUOTA_OVER))
            return false;

        $subname = ".html";
        /* 2015/5/8 修改,若有傳入 real_file_name 就改用 real_file_name 來建立檔案 */
        if (!empty($real_file_name))
        {
            /* 先檢查 real_file_name 副檔名是否為 .html 或 .htm,若不是就自動加上 .html */
            $fe = substr($real_file_name, strrpos($real_file_name, '.'));
            if (($fe !== ".html") && ($fe !== ".htm"))
                $real_file_name .= $subname;
            /* 檢查 real_file_name 是否已存在,若存在就回傳 false */
            $real_file = $page_dir.$path.$real_file_name;
            if (file_exists($real_file))
                return false;
            touch($real_file);
        }
        else
        {
            /* 2014/2/15 修改,改用 tempnam 建立檔案 */
            $tmp_file = tempnam($page_dir.$path, PREFIX_ARTICLE);
            if (empty($tmp_file))
                return false;
            /* 2014/5/29 修改,不可直接用 tmp_file 來整理 real_file,若 /data/ 是 Symlink 抓到的 tmp_file 就不是 page_dir 開頭的位置,整理後會錯誤*/
            $tmp_name = substr($tmp_file, strrpos($tmp_file, "/")+1);
            $real_file = $page_dir.$path.$tmp_name.$subname;
            rename($tmp_file, $real_file);
        }
        $l = strlen($page_dir);
        $file = substr($real_file, $l);

        /* 2015/5/8 修改,有傳入 content 才需要寫入檔案中 */
        if (!empty($content))
        {
            $fp = fopen($real_file, "w");
            if ($fp == false)
                return false;
            fputs($fp, $content);
            fclose($fp);
        }

        /* 如果有傳入 fmtime 就要設定檔案的最後修改時間為 fmtime */
        if (!empty($fmtime))
            touch($real_file, $fmtime);

        /* 建立 Aritcle 檔案的 record 檔案 */
        write_def_record($page_dir, $file, $name);

        /* 更新使用的空間 (有傳入內容才需處理) */
        if (!empty($content))
            update_use_space($page_dir, $file, MODE_ADD);

        return $file;
    }

    /* 建立新的檔案 */
    function new_file($page_dir, $path, $name, $real_file_name="")
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 一定要傳 path (第一層目錄一律是子網站使用,不能建立一般目錄與檔案) */
        if (empty($path))
            return false;
        $path = str_replace("//", "/", $path."/");
        /* 檢查檔案是否已存在 */
        if (filename_exists($page_dir, $path, $name) != false)
            return false;

        /* 取得檔案的副檔名 */
        $pos = strrpos($name, '.');
        if ($pos == false)
            $fe = "";
        else
            $fe = strtolower(substr($name, $pos));

        /* 檢查副檔名是否為 .php .pl .sh,如果是就強制轉為 .txt */
        /* 2015/1/20 新增,副檔名為 .htm .html .js 也強制轉為 .txt */
        if (($fe == ".php") || ($fe == ".pl") || ($fe == ".sh") || ($fe == ".htm") || ($fe == ".html") || ($fe == ".js"))
            $fe = ".txt";

        /* 2015/5/8 修改,若有傳入 real_file_name 就改用 real_file_name 來建立檔案 */
        if (!empty($real_file_name))
        {
            /* 先檢查 real_file_name 副檔名是否與檔案副檔名相同,若不同就自動加上檔案副檔名 */
            $r_fe = substr($real_file_name, strrpos($real_file_name, '.'));
            if ($fe !== $r_fe)
                $real_file_name .= $fe;
            /* 檢查 real_file_name 是否已存在,若存在就回傳 false */
            $real_file = $page_dir.$path.$real_file_name;
            if (file_exists($real_file))
                return false;
            touch($real_file);
        }
        else
        {
            /* 2014/2/25 修改,改用 tempnam 建立檔案 */
            $tmp_file = tempnam($page_dir.$path, PREFIX_FILE);
            if (empty($tmp_file))
                return false;
            /* 2014/5/29 修改,不可直接用 tmp_file 來整理 real_file,若 /data/ 是 Symlink 抓到的 tmp_file 就不是 page_dir 開頭的位置,整理後會錯誤*/
            $tmp_name = substr($tmp_file, strrpos($tmp_file, "/")+1);
            $real_file = $page_dir.$path.$tmp_name.$fe;
            rename($tmp_file, $real_file);
        }
        $l = strlen($page_dir);
        $file = substr($real_file, $l);

        return $file;
    }

    /* 檢查是否為功能目錄 */
    function chk_function_dir($page_dir, $path)
    {
        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* page path 必須是目錄 */
        $page_path = $page_dir.$path;
        if (!is_dir($page_path))
            return false;

        /* 取出目錄的 dir_type */
        $type_file = $page_path."/".NUWEB_TYPE;
        if (!file_exists($type_file))
            return false;
        $dir_type = read_conf($type_file);

        /* 找出那些目錄 type 是屬於功能目錄 */
        $fun_dir_type = explode(" ", FUNCTION_DIR_TYPE);
        $fun_type_cnt = count($fun_dir_type);

        for ($i = 0; $i < $fun_type_cnt; $i++)
        {
            /* 檢查是否為功能目錄 */
            if ($dir_type['DIR_TYPE'] == $fun_dir_type[$i])
                return true;
        }
        return false;
    }

    /* 2015/11/16 新增,檢查是否為一般目錄 (一般目錄才可變更目錄型態) */
    function chk_general_dir($page_dir, $path)
    {
        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* page path 必須是目錄 */
        $page_path = $page_dir.$path;
        if (!is_dir($page_path))
            return false;

        /* 取出目錄的 dir_type */
        $type_file = $page_path."/".NUWEB_TYPE;
        if (!file_exists($type_file))
            return false;
        $dir_type = read_conf($type_file);

        /* 檢查是否為一般目錄型態 */
        return chk_general_dir_type($dir_type['DIR_TYPE']);
    }

    /* 2015/11/16 新增,檢查是否為一般目錄型態 */
    function chk_general_dir_type($type)
    {
        /* 找出一般目錄型態的 List */
        $gen_dir_type = explode(" ", GENERAL_DIR_TYPE_LIST);
        $gen_type_cnt = count($gen_dir_type);

        for ($i = 0; $i < $gen_type_cnt; $i++)
        {
            /* 檢查是否為一般目錄型態 */
            if ($type == $gen_dir_type[$i])
                return true;
        }
        return false;
    }

    /* Copy or Move 功能 */
    function copy_move_path($mode, $page_dir, $src_path, $target_path, $rewrite=false, $name="", $fmtime="", $sync=false)
    {
        /* 如果 mode 是 copy_sync,把 mode 改成 copy,另外設定 sync 為 true */
        /* 2015/5/8 修改,將 sync 參數改成 copy_sync 以避免與新增的 sync 參數衝突 */
        /* (copy_sync 使用來同步 Client 端的功能目錄用,sync 是 copy 功能參數,主要設定檔案時間與來源檔相同) */
        if ($mode == "copy_sync")
        {
            $mode = "copy";
            $copy_sync = true;
        }
        else
            $copy_sync = false;

        /* mode 必須是 copy or move */
        if (($mode != "copy") && ($mode != "move"))
            return false;

        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 一定要傳入 src_path 與 target_path */
        if (empty($src_path) || empty($target_path))
            return false;

        /* path 最後不可有 '/' */
        if (substr($src_path, -1) == "/")
            $src_path = substr($src_path, 0, -1);
        if (substr($target_path, -1) == "/")
            $target_path = substr($target_path, 0, -1);

        /* 檢查 src_path 是否存在 */
        $src_file = $page_dir.$src_path;
        if (!file_exists($src_file))
            return false;

        /* target_path 必須是目錄 */
        $target_dir = $page_dir.$target_path;
        if (!is_dir($target_dir))
            return false;

        /* 檢查 src_path 所在的目錄是否與 target_dir 相同,若相同就不處理 (因為是自己 copy or move 自己) */
        $pos = strrpos($src_file, "/");
        if ($pos !== false)
        {
            $src_dir = substr($src_file, 0, $pos);
            if ($src_dir == $target_dir)
                return false;
        }

        /* 2013/12/4 修改成檢查上傳權限,有上傳權限才可進行 copy or move */
        /* 2015/9/18 修改,來源端只需有瀏覽權限就可 copy */
        //$u_right = chk_upload_right($page_dir, $src_path);
        //if ($u_right != PASS)
        //    err_header($u_right);
        $b_right = chk_browse_right($page_dir, $src_path);
        if ($b_right != PASS)
            err_header($b_right);
        $u_right = chk_upload_right($page_dir, $target_path);
        if ($u_right != PASS)
            err_header($u_right);

        /* 若 mode 為 copy 時,需檢查目前網站使用空間是否超出 quota,若已超出就不可進行 copy 功能 */
        if ($mode == copy)
        {
            list($site_acn, $other) = explode("/", $target_path, 2);
            $status = chk_site_quota($site_acn);
            if (($status === QUOTA_OVER) || ($status === SYSTEM_QUOTA_OVER))
                return false;
        }

        /* 如果沒傳入 name,就取得原始的名稱 */
        if (empty($name))
        {
            $name = get_file_name($page_dir, $src_path);
            if ($name === false)
                return false;
        }

        /* 檢查原始名稱是否已存在 target_path 中 */
        $target_file_path = filename_exists($page_dir, $target_path, $name);
        /* 如果不覆寫且原始的名稱已存在 target_path 中就不處理 */
        if (($rewrite !== true) && ($target_file_path !== false))
        {
            /* 2015/9/4 修改,增加檢查若 rewrite=save 代表檔名重覆就將檔名增加序號 */
            if ($rewrite !== "save")
                return false;
            $name = get_seq_name($page_dir, $target_path, $name);
            $target_file_path = false;
        }

        /* 進行原始檔(目錄)的 copy or move */
        if (is_dir($src_file))
        {
            $t_path = copy_move_path_dir($mode, $page_dir, $src_path, $target_path, $name, $copy_sync, $fmtime, $target_file_path, $sync);

            /* 2013/7/26 新增檢查是否從 web-->Driver 或 Driver-->web,若是必須繼承上一層的 .nuweb_dir_set 與 .nuweb_def,要不然顯示或權限可能會有問題 */
            $src_in_Driver = chk_inDriver(SITE_URL.$src_path);
            $target_in_Driver = chk_inDriver(SITE_URL.$t_path);
            if ((($src_in_Driver == true) && ($target_in_Driver !== true)) || (($src_in_Driver !== true) && ($target_in_Driver == true)))
                update_copy_move_sys_file($page_dir.$t_path);
        }
        else
            $t_path = copy_move_path_file($mode, $page_dir, $src_path, $target_path, $name, $copy_sync, $fmtime, $target_file_path, $sync);

        /* 若 mode 為 copy 時,需更新使用的空間 */
        if ($mode == copy)
            update_use_space($page_dir, $t_path, MODE_ADD);

        return $t_path;
    }

    /* Copy or Move 目錄 */
    function copy_move_path_dir($mode, $page_dir, $src_path, $target_path, $name, $copy_sync=false, $fmtime="", $target_file_path=false, $sync=false)
    {
        /* mode 必須是 copy or move */
        if (($mode != "copy") && ($mode != "move"))
            return false;

        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 一定要傳入 src_path 與 target_path */
        if (empty($src_path) || empty($target_path))
            return false;

        /* 檢查 src_path 是否存在 */
        $src_dir = $page_dir.$src_path;
        if ((!file_exists($src_dir)) || (!is_dir($src_dir)))
            return false;

        /* target_path 不可在 src_path 目錄之下 */
        $src_path_len = strlen($src_path);
        $target_path_len = strlen($target_path);
        if (($target_path_len > $src_path_len) && (substr($target_path, 0, $src_path_len) == $src_path))
            return false;

        /* 如果沒傳入 target_file_path,就先建立空的目的目錄 */
        if ($target_file_path == false)
            $target_file_path = new_dir($page_dir, $target_path, $name);

        /* 如果 src_path 是功能目錄 */
        if (chk_function_dir($page_dir, $src_path) == true)
            copy_move_fun_dir($mode, $page_dir, $src_path, $target_file_path, $sync);
        else
            copy_move_data_dir($mode, $page_dir, $src_path, $target_file_path, $copy_sync, $fmtime, $sync);

        /* 如果是 move,就刪除到原始目錄資料 */
        if ($mode == "move")
            del_dir($page_dir, $src_path);

        return $target_file_path;
    }

    /* Copy or Move 功能目錄 */
    function copy_move_fun_dir($mode, $page_dir, $src_path, $target_path, $sync=false)
    {
        /* mode 必須是 copy or move */
        if (($mode != "copy") && ($mode != "move"))
            return false;

        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 一定要傳入 src_path 與 target_path */
        if (empty($src_path) || empty($target_path))
            return false;

        /* 先 copy or move 整個目錄到 target_path 內 */
        copy_move_dir($mode, $page_dir.$src_path, $page_dir.$target_path, $sync);

        /* 更新目錄設定檔 */
        write_dir_config($page_dir, $target_path);

        /* 更新預設的 Record */
        write_def_record($page_dir, $target_path, "", true, "", false, false, $sync, $sync);

        return true;
    }

    /* Copy or Move 一般資料目錄 */
    function copy_move_data_dir($mode, $page_dir, $src_path, $target_path, $copy_sync=false, $fmtime="", $sync=false)
    {
        /* mode 必須是 copy or move */
        if (($mode != "copy") && ($mode != "move"))
            return false;

        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 一定要傳入 src_path 與 target_path */
        if (empty($src_path) || empty($target_path))
            return false;

        if (substr($src_path, -1) != "/")
            $src_path .= "/";

        if (substr($target_path, -1) != "/")
            $target_path .= "/";

        /* 先處理目錄描述檔 (index.html),並更新描述中的 link (因目錄描述的附屬檔直接在目錄內,所以 files 目錄就是目錄本身) */
        $src_index_path = $src_path.DEF_HTML_PAGE;
        $target_index_path = $target_path.DEF_HTML_PAGE;
        $src_name = get_file_name($page_dir, $src_path);
        copy_move_path_file($mode, $page_dir, $src_index_path, $target_path, $src_name, $copy_sync, $fmtime, $target_index_path, $sync);
        update_files_link($page_dir, $target_index_path, $src_path, $target_path);
        /* 2015/5/8 新增,若 sync 為 true 就要將目的檔的時間調整成與來源檔相同 */
        if ($sync == true)
        {
            $f_mtime = filemtime($page_dir.$src_index_path);
            touch($page_dir.$target_index_path, $f_mtime);
        }

        /* 找出所有 src_path 目錄中的 file list (只取一層) */
        //$src_path_len = strlen($src_path);
        $subdir_cnt = 0;
        $dir_list = get_dir_file_list($page_dir.$src_path);
        //foreach($page_file_name as $path => $name)
        foreach($dir_list as $path => $name)
        {
            //if ((substr($path, 0, $src_path_len) == $src_path) && (strpos(substr($path, $src_path_len), "/") == false))
            //{
                $src_file_path = $page_dir.$path;
                /* 如果是檔案就先處理 */
                if (is_file($src_file_path))
                {
                    /* 檢查原始名稱是否已存在 target_path 中,若已存在就覆寫 */
                    $target_file_path = filename_exists($page_dir, $target_path, $name);
                    /* 2015/5/8 修改,若 target_file_path 為 false 就取出 src_file_path 的檔案名稱,來產生 target_file_path (因目錄描述內的 link 會因為建立的檔案名稱不同而造成錯誤,所以目錄內的檔案一律改用原檔案名稱) */
                    if ($target_file_path == false)
                    {
                        $src_file = substr($src_file_path, strrpos($src_file_path, '/')+1);
                        if (!file_exists($page_dir.$target_path.$src_file))
                            $target_file_path = $target_path.$src_file;
                    }
                    copy_move_path_file($mode, $page_dir, $path, $target_path, $name, $copy_sync, $fmtime, $target_file_path, $sync);
                }
                else
                    $src_sub_dir[$subdir_cnt++] = $path;
            //}
        }

        /* 處理完檔案,再處理子目錄 */
        for ($i = 0; $i < $subdir_cnt; $i++)
        {
            /* 檢查原始名稱是否已存在 target_path 中 */
            //$name = $page_file_name[$src_sub_dir[$i]];
            $name = $dir_list[$src_sub_dir[$i]];
            $target_file_path = filename_exists($page_dir, $target_path, $name);
            /* 進行原始檔(目錄)的 copy or move */
            copy_move_path_dir($mode, $page_dir, $src_sub_dir[$i], $target_path, $name, $copy_sync, $fmtime, $target_file_path, $sync);
        }

        /* 檢查是否有目錄相關的系統設定檔,也必須 copy or move 過去 */
        $src_dir = $page_dir.$src_path;
        $target_dir = $page_dir.$target_path;
        $src_sys_file = $src_dir.NUWEB_DEF;
        $target_sys_file = $target_dir.NUWEB_DEF;
        if (file_exists($src_sys_file))
            copy_move_file($mode, $src_sys_file, $target_sys_file, $sync);
        $src_sys_file = $src_dir.NUWEB_TYPE;
        $target_sys_file = $target_dir.NUWEB_TYPE;
        if (file_exists($src_sys_file))
            copy_move_file($mode, $src_sys_file, $target_sys_file, $sync);
        $src_sys_file = $src_dir.NUWEB_CONF;
        $target_sys_file = $target_dir.NUWEB_CONF;
        if (file_exists($src_sys_file))
            copy_move_file($mode, $src_sys_file, $target_sys_file, $sync);
        $src_sys_file = $src_dir.NUWEB_SUBMENU;
        $target_sys_file = $target_dir.NUWEB_SUBMENU;
        if (file_exists($src_sys_file))
            copy_move_file($mode, $src_sys_file, $target_sys_file, $sync);
        $src_sys_file = $src_dir.NUWEB_SUBMENU_SET;
        $target_sys_file = $target_dir.NUWEB_SUBMENU_SET;
        if (file_exists($src_sys_file))
            copy_move_file($mode, $src_sys_file, $target_sys_file, $sync);
        $src_sys_file = $src_dir.NUWEB_MENU;
        $target_sys_file = $target_dir.NUWEB_MENU;
        if (file_exists($src_sys_file))
            copy_move_file($mode, $src_sys_file, $target_sys_file, $sync);
        $src_sys_file = $src_dir.NUWEB_DIR_SET;
        $target_sys_file = $target_dir.NUWEB_DIR_SET;
        if (file_exists($src_sys_file))
            copy_move_file($mode, $src_sys_file, $target_sys_file, $sync);
        $src_sys_file = $src_dir.NUWEB_FOOTER;
        $target_sys_file = $target_dir.NUWEB_FOOTER;
        if (file_exists($src_sys_file))
            copy_move_file($mode, $src_sys_file, $target_sys_file, $sync);
        $src_sys_file = $src_dir.NUWEB_MEMBER;
        $target_sys_file = $target_dir.NUWEB_MEMBER;
        if (file_exists($src_sys_file))
            copy_move_file($mode, $src_sys_file, $target_sys_file, $sync);
        $src_sys_file = $src_dir.NUWEB_REC_PATH.DIR_COMMENT_RECORD;
        $target_sys_file = $target_dir.NUWEB_REC_PATH.DIR_COMMENT_RECORD;
        if (file_exists($src_sys_file))
            copy_move_file($mode, $src_sys_file, $target_sys_file, $sync);

        /* 更新預設的 Record */
        write_def_record($page_dir, $target_path, "", true, "", false, false, $sync, $sync);

        return true;
    }

    /* Copy or Move 檔案 */
    function copy_move_path_file($mode, $page_dir, $src_path, $target_path, $name, $copy_sync=false, $fmtime="", $target_file_path=false, $sync=false)
    {
        Global $fe_type;

        /* mode 必須是 copy or move */
        if (($mode != "copy") && ($mode != "move"))
            return false;

        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 一定要傳入 src_path 與 target_path */
        if (empty($src_path) || empty($target_path))
            return false;

        /* 檢查 src_path 是否存在 */
        $src_file = $page_dir.$src_path;
        if (!file_exists($src_file))
            return false;

        /* 取得原始檔案的副檔名 */
        $pos = strrpos($src_path, '.');
        if ($pos == false)
            $fe = "";
        else
            $fe = strtolower(substr($src_path, $pos));

        /* 如果沒傳入 target_file_path,就依據原始檔案的副檔名決定是 Article 或一般檔案,先建立空的目的檔案 */
        if ($target_file_path == false)
        {
            if ($fe == ".html")
                $target_file_path = new_article($page_dir, $target_path, $name);
            else
                $target_file_path = new_file($page_dir, $target_path, $name);
            if ($target_file_path === false)
                return false;
        }

        /* 將原始檔案 copy or move 到目的檔案 */
        $target_file = $page_dir.$target_file_path;
        if (copy_move_file($mode, $src_file, $target_file, $sync) === false)
            return false;

        /* 如果有傳入 fmtime 就要設定檔案的最後修改時間為 fmtime */
        if (!empty($fmtime))
            touch($target_file, $fmtime);

        /* 如果有(圖片 or 影片)縮圖檔,必須也 copy or move 過去 */
        $src_tn_file = $src_file.TN_FE_NAME;
        $target_tn_file = $target_file.TN_FE_NAME;
        if (file_exists($src_tn_file))
            copy_move_file($mode, $src_tn_file, $target_tn_file, $sync);

        /* 如果有中縮圖檔,必須也 copy or move 過去 */
        $src_med_tn_file = $src_file.MED_TN_FE_NAME;
        $target_med_tn_file = $target_file.MED_TN_FE_NAME;
        if (file_exists($src_med_tn_file))
            copy_move_file($mode, $src_med_tn_file, $target_med_tn_file, $sync);

        /* 如果有中縮圖檔-2,必須也 copy or move 過去 */
        $src_med_tn_file = $src_file.MED2_TN_FE_NAME;
        $target_med_tn_file = $target_file.MED2_TN_FE_NAME;
        if (file_exists($src_med_tn_file))
            copy_move_file($mode, $src_med_tn_file, $target_med_tn_file, $sync);

        /* 如果有大縮圖檔,必須也 copy or move 過去 */
        $src_big_tn_file = $src_file.BIG_TN_FE_NAME;
        $target_big_tn_file = $target_file.BIG_TN_FE_NAME;
        if (file_exists($src_big_tn_file))
            copy_move_file($mode, $src_big_tn_file, $target_big_tn_file, $sync);

        /* 如果有影片原始縮圖檔,必須也 copy or move 過去 */
        $src_video_img_file = $src_file.SRC_TN_FE_NAME;
        $target_video_img_file = $target_file.SRC_TN_FE_NAME;
        if (file_exists($src_video_img_file))
            copy_move_file($mode, $src_video_img_file, $target_video_img_file, $sync);

        /* 整理出 src file 與 target file 所在的目錄與檔名 */
        $n = strrpos($src_file, "/");
        $src_dir = substr($src_file, 0, $n+1);
        $src_file_name = substr($src_file, $n+1);
        $n = strrpos($target_file, "/");
        $target_dir = substr($target_file, 0, $n+1);
        $target_file_name = substr($target_file, $n+1);

        /* 如果是目錄描述檔,就可直接 return */
        if ($src_file_name == DEF_HTML_PAGE)
        {
            /* 2015/4/28 修改,要將原 record 的 tag 資料 copy 過來,但是要刪除隱藏 tag */
            $src_rec_file = get_file_rec_path($src_dir);
            if ($src_rec_file !== false)
                $src_rec = rec2array($src_rec_file);
            $tag = $src_rec[0]["tag"];
            if ((!empty($tag)) && ($tag !== HIDDEN_TAG))
            {
                /* 將原 tag 過濾掉隱藏 tag */
                $rec["tag"] = "";
                if (strstr($tag, HIDDEN_TAG) == false)
                    $rec["tag"] = $tag;
                else
                {
                    $tag_item = explode(",", $tag);
                    $cnt = count($tag_item);
                    for ($i = 0; $i < $cnt; $i++)
                    {
                        $tag_item[$i] = trim($tag_item[$i]);
                        if ($tag_item[$i] == HIDDEN_TAG)
                            continue;
                        if (!empty($rec["tag"]))
                            $rec["tag"] .= ",";
                        $rec["tag"] .= $tag_item[$i];
                    }
                }
                /* 將 tag 資料更新到目錄 record 中 */
                if (!empty($rec["tag"]))
                    $update_rec = true;
            }

            /* 2015/5/8 新增,若 sync 為 true 就將原 record 的 time & mtime copy 過來 */
            if ($sync == true)
            {
                $rec["time"] = $src_rec[0]["time"];
                $rec["mtime"] = $src_rec[0]["mtime"];
                $update_rec = true;
            }

            /* 檢查是否要更新 record */
            if ($update_rec == true)
                update_rec_file($target_dir.NUWEB_REC_PATH.DIR_RECORD, $rec);

            return $target_file_path;
        }

        /* 如果有 rec 檔,必須也 copy or move 過去 */
        $src_rec_dir = $src_dir.NUWEB_REC_PATH;
        $src_rec_file = $src_rec_dir.$src_file_name.".rec";
        $src_comment_rec = $src_rec_dir.$src_file_name.".comment.rec";
        if (file_exists($src_rec_file))
        {
            $target_rec_dir = $target_dir.NUWEB_REC_PATH;
            $target_rec_file = $target_rec_dir.$target_file_name.".rec";
            $target_comment_rec = $target_rec_dir.$target_file_name.".comment.rec";

            if (!is_dir($target_rec_dir))
                mkdir($target_rec_dir);
            /* record file 一律用 copy 的方式,因為必須保留原 record 才能進行刪除 index 內資料 */
            copy_move_file("copy", $src_rec_file, $target_rec_file, $sync);

            /* 如果是 move 方式,就必須進行 del_rec_file 處理 */
            if ($mode == "move")
                del_rec_file($src_rec_file);

            /* 如果是 copy 方式,必須把目的檔 record file 中所有相關的 count 都清為 0 */
            if ($mode == "copy")
                clean_rec_cnt($target_rec_file);

            /* 檢查是否有回應的 record 檔,若有也要 copy or move 過去 */
            if (file_exists($src_comment_rec))
                copy_move_file($mode, $src_comment_rec, $target_comment_rec, $sync);
        }

        /* 如果有 flv or mp4 檔,必須也 copy or move 過去 */
        $src_media_dir = $src_dir.NUWEB_MEDIA_PATH;
        $src_flv_file = $src_media_dir.$src_file_name.".flv";
        if (file_exists($src_flv_file))
        {
            $target_media_dir = $target_dir.NUWEB_MEDIA_PATH;
            $target_flv_file = $target_media_dir.$target_file_name.".flv";
            if (!is_dir($target_media_dir))
                mkdir($target_media_dir);
            copy_move_file($mode, $src_flv_file, $target_flv_file, $sync);
        }
        $src_mp4_file = $src_media_dir.$src_file_name.".mp4";
        if (file_exists($src_mp4_file))
        {
            $target_media_dir = $target_dir.NUWEB_MEDIA_PATH;
            $target_mp4_file = $target_media_dir.$target_file_name.".mp4";
            if (!is_dir($target_media_dir))
                mkdir($target_media_dir);
            copy_move_file($mode, $src_mp4_file, $target_mp4_file, $sync);
        }

        /* 2014/6/13 新增,如果有 pdf 檔,必須也 copy or move 過去 */
        $src_pdf_dir = $src_dir.NUWEB_PDF_PATH;
        $src_pdf_file = $src_pdf_dir.$src_file_name.".pdf";
        if (file_exists($src_pdf_file))
        {
            $target_pdf_dir = $target_dir.NUWEB_PDF_PATH;
            $target_pdf_file = $target_pdf_dir.$target_file_name.".pdf";
            if (!is_dir($target_pdf_dir))
                mkdir($target_pdf_dir);
            copy_move_file($mode, $src_pdf_file, $target_pdf_file, $sync);
        }

        /* 2015/10/2 新增,如果有 mp3 檔,必須也 copy or move 過去 */
        $src_media_dir = $src_dir.NUWEB_MEDIA_PATH;
        $src_mp3_file = $src_media_dir.$src_file_name.".mp3";
        if (file_exists($src_mp3_file))
        {
            $target_media_dir = $target_dir.NUWEB_MEDIA_PATH;
            $target_mp3_file = $target_media_dir.$target_file_name.".mp3";
            if (!is_dir($target_media_dir))
                mkdir($target_media_dir);
            copy_move_file($mode, $src_mp3_file, $target_mp3_file, $sync);
        }

        /* 如果是影片檔,必須更新 video list */
        if ($fe_type[$fe] == VIDEO_TYPE)
        {
            /* 如果是 move 必須先把原始影片檔從 video list 中刪除 */
            if ($mode == "move")
                del_video_list($page_dir, $src_path);

            /* 找出目的檔的 flv 檔位置 */
            if ($fe == ".flv")
                $flv_path = $target_file_path;
            else if (file_exists($target_flv_file))
                $flv_path = str_replace($page_dir, "", $target_flv_file);

            /* 找出目的檔的影片縮圖檔位置 */
            if (file_exists($target_tn_file))
                $img_path = str_replace($page_dir, "", $target_tn_file);

            /* 將目的檔相關資料更新到 video list 中 */
            update_video_list($page_dir, $target_file_path, $flv_path, $img_path);
        }

        /* 如果是網頁檔案且不是 copy_sync 工作,就檢查及 copy or move 附件目錄 (.files),並調整網頁中的 link */
        if (($fe_type[$fe] == HTML_TYPE) && ($copy_sync == false))
            copy_move_html_files_dir($mode, $page_dir, $src_path, $target_path, $target_file_path, $name, $sync);

        /* 更新預設的 Record */
        write_def_record($page_dir, $target_file_path, $name, true, "", false, false, $sync, $sync);

        return $target_file_path;
    }

    /* Copy or Move 網頁的附件目錄 (.files),並調整網頁中的 link */
    function copy_move_html_files_dir($mode, $page_dir, $src_path, $target_path, $target_file_path, $name, $sync=false)
    {
        /* mode 必須是 copy or move */
        if (($mode != "copy") && ($mode != "move"))
            return false;

        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 一定要傳入 src_path 與 target_path */
        if (empty($src_path) || empty($target_path))
            return false;

        /* 若沒有找到來源檔的附件目錄,就不處理 */
        $src_files_path = get_files_dir($page_dir, $src_path, $name);
        if ($src_files_path === false)
            return false;

        /* 取得附件目錄的名稱 */
        $files_dir_name = get_file_name($page_dir, $src_files_path);

        /* 若有發現目的檔的附件目錄,就刪除掉重新進行 copy or move */
        $target_files_path = filename_exists($page_dir, $target_path, $files_dir_name);
        if ($target_files_path !== false)
            del_dir($page_dir, $target_files_path);

        /* 建立新的目的檔的附件目錄 */
        $target_files_path = new_dir($page_dir, $target_path, $files_dir_name);
        if ($target_files_path == false)
            return false;

        /* copy or move files 目錄 */
        copy_move_dir($mode, $page_dir.$src_files_path, $page_dir.$target_files_path, $sync);

        /* 更新目的檔的 link */
        update_files_link($page_dir, $target_file_path, $src_files_path, $target_files_path);

        /* 更新 copy or move 後的 record file */
        /* 2015/5/8 修改,增加傳入 sync 參數 (第一個是 clean_cnt 參數,是要清除 cnt 用,因為 sync 時才要 clean_cnt,所以直接傳入 sync 參數即可) */
        update_copy_move_rec($page_dir, $target_files_path, $sync, $sync);
    }

    /* 變更名稱 (檔案 or 目錄) */
    function rename_page($page_dir, $file_path, $name)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        $page_path = $page_dir.$file_path;

        /* 先取出原始的 file name */
        $file_name = get_file_name($page_dir, $file_path);

        /* 檢查新的檔案是否已經存在 */
        $pos = strrpos($file_path, "/");
        if ($pos != false)
        {
            $path = substr($file_path, 0, $pos+1);
            if (filename_exists($page_dir, $path, $name) != false)
                return false;
        }

        /* 2015/4/17 新增,若是目錄就先取得原目錄的 view_path */
        if (is_dir($page_path))
            $old_view_path = get_view_path($page_path);

        /* 另外更新 record file 中的 filename (title) */
        update_rec_by_filename($page_dir, $file_path, $name);

        /* 取得原始檔案的正副檔名 */
        $pos = strrpos($file_name, '.');
        if ($pos != false)
        {
            $fe = strtolower(substr($file_name, $pos));
            $fn = substr($file_name, 0, $pos);
        }
        /* 檢查副檔名是否為 .html or .htm */
        if (($fe == ".html") || ($fe == ".htm"))
        {
            /* 檢查網頁的 .files 是否存在 */
            $files_path = filename_exists($page_dir, $path, $fn.".files");

            /* 如果網頁的 .files 存在,也要一併更名 */
            if ($files_path != false)
            {
                $files_name = substr($name, 0, strrpos($name, '.')).".files";
                update_rec_by_filename($page_dir, $files_path, $files_name);
            }
        }

        /* 2015/4/17 新增,若是目錄就必須更新 Site Index 與 All Index 的資料,將此目錄底下的所有 view_path 都必須修改成新的 view_path */
        if (is_dir($page_path))
        {
            /* 取得目錄的新 view_path */
            $new_view_path = get_view_path($page_path);

            /* 將 All index 中符合 old_view_path 的資料替換成 new_view_path */
            rep_index($old_view_path, $new_view_path, ALL_INDEX_DIR);

            /* 將 Site index 中符合 old_view_path 的資料替換成 new_view_path */
            $site_index_dir = get_site_index_dir(str_replace(WEB_ROOT_PATH, "", $page_path));
            rep_index($old_view_path, $new_view_path, $site_index_dir);
        }

        return true;
    }

    /* 刪除檔案 */
    function del_file($page_dir, $path, $name)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        $path = str_replace("//", "/", $path."/");
        $aName = explode(",", $name);
        $cntName = count($aName);
        foreach($aName as $name)
        {
            $name = trim(rawurldecode($name));
            if (empty($name))
                continue;

            $file_path = $path.$name;
            $file = $page_dir.$file_path;

            /* 2013/4/16 新增,若檔案不存在仍要嘗試刪除 index */
            if (!file_exists($file))
            {
                $file_url = str_replace(ROOT_PATH, "", $file);
                rec_delete($file_url);
            }

            /* 刪除的必須是檔案,而且不可以刪除目錄首頁 */
            if ((!file_exists($file)) || ($name == DEF_HTML_PAGE))
            {
                if ($cntName == 1)
                    return false;
                else
                    continue;
            }

            /* 先取出原本目錄的使用空間 */
            //$src_size = get_use_space($page_dir.$path);
            $src_size = real_filesize($page_dir.$path);

            // sub dir
            if (is_dir($file)) {
                del_dir($page_dir, $file_path);
                continue;
            }

            // del tag
            $url = "http://localhost/tools/tag/tag.php?mode=del&file_path=".$file_path;
            @file_get_contents($url);

            /* 先取出 file name */
            $file_name = get_file_name($page_dir, $file_path);

            /* 將檔案刪除 */
            unlink($file);

            /* 如果有縮圖檔也一併刪除 */
            $tn_file = $file.TN_FE_NAME;
            if (file_exists($tn_file))
                unlink($tn_file);

            /* 如果有中縮圖檔也一併刪除 */
            $med_tn_file = $file.MED_TN_FE_NAME;
            if (file_exists($med_tn_file))
                unlink($med_tn_file);

            /* 如果有中縮圖檔-2也一併刪除 */
            $med_tn_file = $file.MED2_TN_FE_NAME;
            if (file_exists($med_tn_file))
                unlink($med_tn_file);

            /* 如果有大縮圖檔也一併刪除 */
            $big_tn_file = $file.BIG_TN_FE_NAME;
            if (file_exists($big_tn_file))
                unlink($big_tn_file);

            /* 如果有影片縮圖檔也一併刪除 */
            $video_img_file = $file.SRC_TN_FE_NAME;
            if (file_exists($video_img_file))
                unlink($video_img_file);

            /* 如果有 rec 檔,也要一併刪除 */
            $rec_dir = $page_dir.$path.NUWEB_REC_PATH;
            $rec_file = $rec_dir.$name.".rec";
            if (is_dir($rec_dir))
            {
                if (file_exists($rec_file))
                    del_rec_file($rec_file);

                /* 檢查是否有回應的 record 檔,若有也要刪除 */
                $comment_rec = $rec_dir.$name.".comment.rec";
                if (file_exists($comment_rec))
                    unlink($comment_rec);
            }

            /* 如果有 flv or mp4 檔,也要一併刪除 */
            $media_dir = $page_dir.$path.NUWEB_MEDIA_PATH;
            $flv_file = $media_dir.$name.".flv";
            if ((is_dir($media_dir)) && (file_exists($flv_file)))
                unlink($flv_file);
            $mp4_file = $media_dir.$name.".mp4";
            if ((is_dir($media_dir)) && (file_exists($mp4_file)))
                unlink($mp4_file);

            /* 2014/6/13 新增,如果有 pdf 檔,也要一併刪除 */
            $pdf_dir = $page_dir.$path.NUWEB_PDF_PATH;
            $pdf_file = $pdf_dir.$name.".pdf";
            if ((is_dir($pdf_dir)) && (file_exists($pdf_file)))
                unlink($pdf_file);

            /* 檢查 video.list,刪除的檔案如果在 video.list 中也要刪除 */
            del_video_list($page_dir, $file_path);

            /* 取得要刪除檔案的正副檔名 */
            $pos = strrpos($file_name, '.');
            if ($pos != false)
            {
                $fe = strtolower(substr($file_name, $pos));
                $fn = substr($file_name, 0, $pos);
            }
            /* 檢查副檔名是否為 .html or .htm */
            if (($fe == ".html") || ($fe == ".htm"))
            {
                /* 檢查網頁的 .files 是否存在 */
                $files_path = filename_exists($page_dir, $path, $fn.".files");
                /* 如果網頁的 .files 存在,也要一併刪除 */
                if ($files_path != false)
                    del_dir($page_dir, $files_path, false);
            }

            /* 更新使用的空間 */
            update_use_space($page_dir, $path, MODE_DEL, $src_size);
        }
        return true;
    }

    /* 刪除網站目錄 */
    function del_site_dir($page_dir, $site_acn)
    {
        Global $is_manager, $admin_manager, $lang;
        $acn = strtolower($site_acn);

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 2015/3/19 新增,若是後端管理者,就定義 DEL_SITE_FLAG 為 true,後續進行刪除時需要檢查權限用(因後端管理者可能沒有刪除網站目錄權限) */
        if ($admin_manager === true)
            define("DEL_SITE_FLAG", true);

        /* 檢查是否有管理權限,有管理權限才能刪除網站目錄 */
        /* 2015/3/19 修改,後端管理者也可以刪除網站 (系統管理者已不使用) */
        if (($is_manager != true) && ($admin_manager != true) && (chk_manager_right($acn) != PASS))
            return false;

        /* 讀取 CS 的資料 */
        $reg_conf = read_conf(REGISTER_CONFIG);
        $cs = strtolower($reg_conf["acn"]);

        /* 讀取原網站資訊 */
        $conf_file = $page_dir.$acn."/".NUWEB_CONF;
        $site_conf = read_conf($conf_file);

        /* 先通知 WNS 刪除網站 */
        if (del_cs_site($site_conf["site_acn"]) !== true)
            return false;

        /* 檢查目錄是否已存在,如果存在就刪除 */
        $site_dir = $page_dir.$acn."/";
        if (is_dir($site_dir))
        {
            /* 2015/9/21 修改,刪除網站時不直接刪除網站資料,先丟到網站垃圾桶中 */
            //del_dir($page_dir, $acn, false);
            set_site_trash($acn);

            /* 將網站目錄記錄由 site list 中刪除 */
            $slist = $page_dir.SITE_LIST;
            if (file_exists($slist))
            {
                $site_list = @file($slist);
                $cnt = count($site_list);
                $fp = fopen($slist, "w");
                flock($fp, LOCK_EX);
                for ($i = 0; $i < $cnt; $i++)
                {
                    list($s_acn, $s_name, $s_owner, $s_time, $s_status) = explode("\t", trim($site_list[$i]));
                    if ($acn == strtolower($s_acn))
                        continue;
                    fputs($fp, $site_list[$i]);
                }
                flock($fp, LOCK_UN);
                fclose($fp);
            }
        }

        /* 2014/12/12 新增,將網站資訊從 DB 中刪除 */
        site2db_del($acn);
        /* 2014/8/25 新增,通知 Group Server 刪除網站設定資料 */
        group_del_site_conf($acn);
        /* 2015/1/21 新增,刪除 Site_Index 資料 */
        del_site_index($acn);

        return true;
    }

    /* 刪除目錄的主 function */
    function del_dir($page_dir, $path, $chk_def_dir=true)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 如果 chk_def_dir != false 代表要檢查是否為預設目錄,若是就不刪除 */
        if ($chk_def_dir != false)
        {
            $site_conf = get_site_conf($path);
            $type = $site_conf['type'];
            $site_acn = strtolower($site_conf['site_acn']);
            /* 2013/10/28 新增,檢查是否為網站目錄(網站目錄也屬於預設目錄),若是就離開不進行刪除 */
            if (($path == $site_acn) || ($path == $site_acn."/"))
                return false;
            /* 取得預設的目錄資料 */
            if ($type == SITE_TYPE_GROUP)
                $def_dir = @file(DEF_DIR_GROUP);
            else
                $def_dir = @file(DEF_DIR_PERSONAL);
            $dir_cnt = count($def_dir);
            for ($i = 0; $i < $dir_cnt; $i++)
            {
                list($dir_name, $dir_type, $real_dir_name) = explode("\t", trim($def_dir[$i]));
                $real_path = $site_acn."/".$real_dir_name;
                /* 檢查要刪除的目錄是否為預設目錄,若是就離開不進行刪除 */
                if (($path == $real_path) || ($path == $real_path."/"))
                    return false;
            }
        }

        /* 讀取 video list */
        $video_list = get_video_list($page_dir);

        /* 檢查並上傳動態 share record (設為 del,因會檢查是否存在,所以必須在刪除前執行) */
        upload_dymanic_share_rec($page_dir, $path, "del");

        /* 進行目錄的刪除 */
        //$del_flag = rm_dir($page_dir, $path, $path_name, $video_list);
        $del_flag = rm_dir($page_dir, $path, $video_list);

        /* 儲存新的 video list */
        rewrite_video_list($page_dir, $video_list);

        /* 如果目錄刪除成功,檢查上層目錄是否有 .nuweb_menu ,若有也要檢查是否有被刪除的目錄,也要一併移除 */
        if ($del_flag !== false)
        {
            /* 取出上層目錄 */
            $path_item = explode("/", $path);
            $item_cnt = count($path_item);
            $menu_dir = $page_dir;
            for ($i = 0; $i < $item_cnt; $i++)
            {
                if (trim($path_item[$i+1]) != "")
                    $menu_dir .= $path_item[$i]."/";
                else
                {
                    $last_path = $path_item[$i];
                    break;
                }
            }

            /* 檢查 .nuweb_menu 是否存在,若存在就整理 .nuweb_menu 內容 */
            $menu_file = $menu_dir.NUWEB_MENU;
            if (file_exists($menu_file))
            {
                $menu = @file($menu_file);
                $cnt = count($menu);
                $menu_content = "";
                $change = false;
                for ($i = 0; $i < $cnt; $i++)
                {
                    if (trim($menu[$i]) != $last_path)
                        $menu_content .= $menu[$i];
                    else
                        $change = true;
                }
                if ($change == true)
                {
                    $fp = fopen($menu_file, "w");
                    fputs($fp, $menu_content);
                    fclose($fp);
                }
            }
        }

        /* 更新使用的空間 */
        update_use_space($page_dir, $path, MODE_UPDATE);

        return $del_flag;
    }

    /* 刪除目錄 */
    //function rm_dir($page_dir, $path, &$path_name, &$video_list)
    function rm_dir($page_dir, $path, &$video_list)
    {
        Global $admin_manager;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 有上傳權限才可刪除目錄 */
        if (chk_upload_right($page_dir, $path) != PASS)
            return false;

        /* 讀取目錄內的檔案與子目錄 */
        $del_flag = true;
        $dir = $page_dir.$path;
        $handle = opendir($dir);
        while ($sub_name = readdir($handle))
        {
            /* 2014/9/5 修改,增加檢查網站設定檔須先跳過,否則有可能造成檢查管理權限錯誤 */
            /* 先跳過 . & .. & index.html 與權限檔與目錄型態檔與網站設定檔與 record 目錄 */
            if (($sub_name == ".") || ($sub_name == "..") || ($sub_name == DEF_HTML_PAGE) || ($sub_name == NUWEB_DEF) || ($sub_name == NUWEB_TYPE) || ($sub_name == NUWEB_CONF) || ($sub_name == str_replace("/", "", NUWEB_REC_PATH)))
                continue;
            /* 檢查是子目錄 or 檔案 */
            $sub_obj = $dir."/".$sub_name;
            if ((is_file($sub_obj)) || (is_link($sub_obj)))
            {
                /* 如果是檔案 or symlink,就直接刪除 */
                unlink($sub_obj);
                //del_file_list_item($path_name, $path."/".$sub_name);
                $rec_file = $dir."/".NUWEB_REC_PATH.$sub_name.".rec";
                if (file_exists($rec_file))
                    del_rec_file($rec_file);
                $comment_rec = $dir."/".NUWEB_REC_PATH.$sub_name.".comment.rec";
                if (file_exists($comment_rec))
                    unlink($comment_rec);
                if (!empty($video_list))
                    del_video_list_item($video_list, $path."/".$sub_name);
            }
            else if (is_dir($sub_obj))
            {
                /* 如果是子目錄,就繼續往下刪除 */
                //if (rm_dir($page_dir, $path."/".$sub_name, $path_name, $video_list) == false)
                if (rm_dir($page_dir, $path."/".$sub_name, $video_list) == false)
                    $del_flag = false;
            }
        }
        closedir($handle);

        /* 確認子目錄與檔案都刪除後,再刪除 index.html 與權限檔與目錄型態檔與 record 目錄 */
        if ($del_flag == true)
        {
            if (file_exists($dir."/".DEF_HTML_PAGE))
                unlink($dir."/".DEF_HTML_PAGE);
            if (file_exists($dir."/".NUWEB_DEF))
                unlink($dir."/".NUWEB_DEF);
            /* 刪除 dir record file 與 fun.rec 與 record 目錄內的權限檔與 record 目錄 */
            $rec_dir = $dir."/".NUWEB_REC_PATH;
            $dir_rec = $rec_dir.DIR_RECORD;
            $dir_comment_rec = $rec_dir.DIR_COMMENT_RECORD;
            $fun_rec = $record.FUN_RECORD;
            $rec_nuweb_def = $rec_dir.NUWEB_DEF;
            if (is_dir($rec_dir))
            {
                if (file_exists($dir_rec))
                    del_rec_file($dir_rec);
                if (file_exists($dir_comment_rec))
                    unlink($dir_comment_rec);
                if (file_exists($fun_rec))
                     del_rec_file($fun_rec);
                if (file_exists($rec_nuweb_def))
                    unlink($rec_nuweb_def);
                /* 用 rmdir 刪除 record 目錄,若無法刪除,就使用 real_rm_dir 刪除(會刪除目錄內所有檔案) */
                if (@rmdir($rec_dir) !== true)
                    real_rm_dir($page_dir, $path."/".NUWEB_REC_PATH);
            }

            /* 取得 dir type */
            $type_file = $page_dir.$path."/".NUWEB_TYPE;
            if (file_exists($type_file))
                $type = read_conf($type_file);
            /* 如果是 table mode 或 shop mode 就同時刪除 table list 設定 */
            if (($type["DIR_TYPE"] == TYPE_TABLE_DIR) || ($type["DIR_TYPE"] == TYPE_SHOP_DIR))
            {
                /* 取出 table list 資料,刪除目錄資料後再重新儲存 */
                $table_list_file = $page_dir.TABLE_LIST;
                $table_list = @file($table_list_file);
                $table_cnt = count($table_list);
                if ($table_cnt > 0)
                {
                    $fp = fopen($table_list_file, "w");
                    for ($i = 0; $i < $table_cnt; $i++)
                    {
                        /* 儲存時跳過刪除的目錄 */
                        $table_path = trim($table_list[$i]);
                        if ((!empty($table_path)) && ($path != $table_path))
                            fputs($fp, $table_list[$i]);
                    }
                    fclose($fp);
                }
            }
            if (file_exists($dir."/".NUWEB_TYPE))
                unlink($dir."/".NUWEB_TYPE);
            /* 2014/9/5 新增,若網站設定檔 (.nuweb_conf) 存在就刪除網站設定檔 */
            if (file_exists($dir."/".NUWEB_CONF))
                unlink($dir."/".NUWEB_CONF);
            /* 用 rmdir 刪除目錄,若無法刪除,就使用 real_rm_dir 刪除(會刪除目錄內所有檔案) */
            if (@rmdir($dir) !== true)
                real_rm_dir($page_dir, $path);
            //del_file_list_item($path_name, $path);
        }
        return $del_flag;
    }

    /* 檢查是否為擁有者 */
    function chk_owner($path_name)
    {
        Global $login_user;

        /* 讀取網站設定 */
        $site_conf = get_site_conf($path_name);

        /* 如果是網站的 owner 就回傳 true,否則回傳 false (2013/8/14 修改多增加檢查 mail) */
        $owner = strtolower($site_conf["owner"]);
        if (($login_user["acn"] == $owner) || ($login_user["mail"] == $owner))
            return true;
        return false;
    }

    /* 檢查管理權限 */
    /* 2015/3/19 修改,因系統管理者 (is_manager) 已不使用,目前僅查是否有網站的管理權限,因 is_manager 已設為 false 所以程式不需特別修改 */
    function chk_manager_right($path_name)
    {
        Global $is_manager, $admin_manager, $login_user;

        /* 如果是管理者就直接回傳 PASS */
        if ($is_manager == true)
            return PASS;

        /* 2015/3/19 新增,若是有設定 DEL_SITE_FLAG 並為 true 就回傳 PASS (代表後端管理者要刪除網站,必須暫時提供管理權限) */
        if ((defined("DEL_SITE_FLAG")) && (DEL_SITE_FLAG === true))
            return PASS;

        /* 沒有 cookie 就直接回傳 DENY_COOKIE */
        if ((empty($login_user)) || ($login_user == false))
            return DENY_COOKIE;

        /* 讀取網站設定 */
        $site_conf = get_site_conf($path_name);

        /* 1. 如果是網站的 owner 就回傳 PASS (2013/8/14 修改多增加檢查 mail) */
        $owner = strtolower($site_conf["owner"]);
        if (($login_user["acn"] == $owner) || ($login_user["mail"] == $owner))
            return PASS;

        /* 2. 檢查 user 是否為網站管理者,如果是也回傳 PASS (2013/8/14 修改多增加檢查 mail) */
        $macn = explode(",", strtolower($site_conf["manager"]));
        $cnt = count($macn);
        for ($i = 0; $i < $cnt; $i++)
        {
            $manager = trim($macn[$i]);
            if (($login_user["acn"] == $manager) || ($login_user["mail"] == $manager))
                return PASS;
        }

        /* 2015/3/20 新增,後端管理者有 web 網站的管理者權限 */
        if (($site_conf["site_acn"] == "web") && ($admin_manager == true))
            return PASS;

        /* 其他 user 就回傳 DENY_FORBIDDEN */
        return DENY_FORBIDDEN;
    }

    /* 檢查瀏覽權限 */
    function chk_browse_right($page_dir, $path)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 如果有管理權限就直接回傳 PASS */
        if (chk_manager_right($path) == PASS)
            return PASS;

        /* 若是在 .nuweb_msg 目錄內,則一律回傳 PASS */
        if (strstr($path, NUWEB_MSG_PATH) !== false)
            return PASS;

        /* 改用新版權限檢查,檢查 record 內權限資料 (若瀏覽或下載為 PASS 就回傳 PASS) */
        $file_path = $page_dir.$path;
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        $right_status = chk_user_right($file_path);
        if (($right_status["browse"] == PASS) || ($right_status["download"] == PASS))
            return PASS;

        /* 若 pwd 欄位回傳 DENY_PWD 代表有設密碼,且密碼檢查錯誤,直接回傳 DENY_PWD,其他狀況直接回傳瀏覽權限回傳值 */
        /* 2015/9/11 修改,增加檢查是否有 share_pwd 欄位,若有 share_pwd 欄位且為 DENY_PWD 代表分享連結有設密碼,但密碼檢查錯誤,也必須回傳 DENY_PWD */
        if (($right_status["pwd"] == DENY_PWD) || ((isset($right_status["share_pwd"])) && ($right_status["share_pwd"] == DENY_PWD)))
            return DENY_PWD;
        else
            return $right_status["browse"];
    }

    /* 檢查上傳權限 */
    function chk_upload_right($page_dir, $path)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 如果有管理權限就直接回傳 PASS */
        if (chk_manager_right($path) == PASS)
            return PASS;

        /* 若是在 .nuweb_msg 目錄內,則一律回傳 PASS */
        if (strstr($path, NUWEB_MSG_PATH) !== false)
            return PASS;

        /* 改用新版權限檢查,檢查 record 內權限資料 (若上傳或編輯或刪除為 PASS 就回傳 PASS) */
        $file_path = $page_dir.$path;
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        $right_status = chk_user_right($file_path);
        if (($right_status["upload"] == PASS) || ($right_status["edit"] == PASS) || ($right_status["del"] == PASS))
            return PASS;

        /* 回傳上傳權限狀態 */
        return $right_status["upload"];
    }

    /* 取得目錄的權限屬性 */
    function get_right_attr($page_dir, $path)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 取得網站的 member list (判斷權限時可能會使用到) */
        $g_user = get_site_member($path);

        /* 設定預設值 */
        $attr_info["browse"] = true;
        $attr_info["upload"] = false;
        $attr_info["show_dir"] = true;
        $attr_info["set_pwd"] = false;
        $attr_info["set_user"] = false;

        /* 2014/12/16 新增,若是在 .nuweb_msg 目錄內,則一律可瀏覽也可上傳,但是不顯示 */
        if (strstr($path, NUWEB_MSG_PATH) !== false)
        {
            $attr_info["upload"] = true;
            $attr_info["show_dir"] = false;
            return $attr_info;
        }

        /* 檢查是否有網站管理權限 */
        if (chk_manager_right($path) == PASS)
            $manage_right = true;
        else
            $manage_right = false;

        /* 讀取權限檔資料 */
        /* 2015/2/13 修改,不再使用 .nuweb_def 改從 record 取得權限資料 */
        //$info = read_nuweb_def($page_dir, $path);
        $file_path = $page_dir.$path;
        $info = get_rec_right_info($file_path);
        if ($info == false)
            return false;
        /* 2014/3/13 修改,檢查若有設定 strong_deny,就沒有瀏覽與上傳權限 */
        //if ($info["strong_deny"] == true)
        if ((isset($info["strong_deny"])) && ($info["strong_deny"] == YES))
        {
            $attr_info["browse"] = false;
            $attr_info["upload"] = false;
            $attr_info["set_user"] = true;

            /* 如果有管理權限就一定可以顯示目錄 */
            if ($manage_right == true)
                $attr_info["show_dir"] = true;
            else
                $attr_info["show_dir"] = false;

            /* 回傳權限資料 */
            return $attr_info;
        }

        /* 2015/3/11 新增,取得登入者擁有的權限 */
        $right_status = chk_user_right($file_path);

        /* 1. 判斷密碼屬性 */
        if (!empty($info["pwd"]))
            $attr_info["set_pwd"] = true;
        else
            $attr_info["set_pwd"] = false;

        /* 2. 判斷瀏覽屬性 (2015/3/11 修改,改用新版權限檢查) */
        /* 只要有設密碼,瀏覽屬性一定為 true */
        if ($attr_info["set_pwd"] == true)
            $attr_info["browse"] = true;
        else
        {
            /* 若瀏覽名單中有設定 * 代表所有人可瀏覽 */
            if (strstr($info["browse"], ALL_USER) !== false)
                $attr_info["browse"] = true;
            else
            {
                /* 瀏覽名單沒有 * 代表有設定 user 權限,依據登入者瀏覽權限 (包括瀏覽與下載) 設定瀏覽屬性 */
                $attr_info["set_user"] = true;
                $attr_info["browse"] = (($right_status["browse"] == PASS) || ($right_status["download"] == PASS)) ? true : false;
            }
        }

        /* 3. 判斷上傳屬性 (2015/3/11 修改,改用新版權限檢查) */
        /* 依據登入者上傳權限 (包括上傳與編輯與刪除) 設定上傳屬性 */
        $attr_info["upload"] = (($right_status["upload"] == PASS) || ($right_status["edit"] == PASS) || ($right_status["del"] == PASS)) ? true : false;

        /* 4. 判斷是否顯示目錄 */
        /* 如果有管理權限就一定可以顯示目錄 */
        if ($manage_right == true)
            $attr_info["show_dir"] = true;
        else
        {
            /* 2015/3/11 修改,沒有設定隱藏就可以顯示目錄,否則就不可顯示 */
            if (chk_hidden($page_dir.$path) == true)
                $attr_info["show_dir"] = false;
            else
                $attr_info["show_dir"] = true;
        }

        /* 回傳權限資料 */
        return $attr_info;
    }

    /* 檢查 user 是否在 list 中,如果 list 中有 + 代表也要檢查是否在 user_list 中 */
    function exist_list($list, $user, $user_list=NULL)
    {
        Global $login_user;

        $acn = $user["acn"];
        $mail = $user["mail"];
        if ($login_user["site_owner"] !== true)
            $site_owner = false;
        else
            $site_owner = true;
        if ($login_user["site_manager"] !== true)
            $site_manager = false;
        else
            $site_manager = true;

        /* 取出 list 的元素 */
        $list_item = explode(" ", str_replace(",", " ", strtolower($list)));
        $list_cnt = count($list_item);
        $ulist_cnt = count($user_list);

        /* 判斷 acn 或 mail 是否存在 list 中 */
        for ($i = 0; $i < $list_cnt; $i++)
        {
            /* 若 list 項目沒內容就跳過 */
            if (empty($list_item[$i]))
                continue;

            /* 檢查 list 中是否有 acn 或 mail */
            if (($list_item[$i] == $acn) || ($list_item[$i] == $mail))
                return true;

            /* 如果 list 中有 + (SITE_MEMBER) 就檢查 acn 是否存在 user_list 中 */
            if ($list_item[$i] == SITE_MEMBER)
            {
                for ($j = 0; $j < $ulist_cnt; $j++)
                {
                    if ($user_list[$j] == $acn)
                        return true;
                }
            }

            /* 2013/8/27 新增,若 list 中有 site_owner 就檢查 $site_owner 是否為 true */
            if (($list_item[$i] == SITE_OWNER) && ($site_owner == true))
                return true;
            /* 若 list 中有 site_manager 就檢查 site_manager 是否為 true */
            if (($list_item[$i] == SITE_MANAGER) && ($site_manager == true))
                return true;

            /* 2013/12/9 新增,檢查 list_item 若沒有 '@' 但有 '.' 代表不是 E-mail 而是社群帳號,就取回社群成員帳號 list 進行檢查 */
            if ((strstr($list_item[$i], "@") === false) && (strstr($list_item[$i], ".") !== false))
            {
                $member_list = get_cs_site_member($list_item[$i]);
                if (($member_list !== false) && (exist_list($member_list, $user) == true))
                    return true;
            }
        }

        /* 如果都不在 list 中,就回傳不存在 */
        return false;
    }

    function get_header($title="", $style_file="", $query="")
    {
        Global $folder_dir;

        $path_name = str_replace(WEB_PAGE_DIR, "", substr($folder_dir, 0, -1));
        return get_site_header($path_name, $title, $style_file, $query);
    }

    function get_footer($path_name="")
    {
        Global $lang, $folder_dir;

        if (!empty($folder_dir))
            $path_name = str_replace(WEB_PAGE_DIR, "", substr($folder_dir, 0, -1));

        if (!empty($path_name))
        {
            /* 分離 path_name,取出 acn */
            $path = explode("/", $path_name);
            $acn = $path[0];

            /* 尋找第一層目錄內是否有 .nuweb_footer 若有就取出來當 footer content */
            $footer_file = WEB_PAGE_DIR.$acn."/".NUWEB_FOOTER;
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
                $footer_content = implode("", $buf);
            }
        }

        /* 把 footer_content 的內容,放入樣版中 */
        $template_file = WEB_TEMPLATE_DIR."footer.tpl.$lang";
        $footer = str_replace("{footer_content}", $footer_content, trim(implode("", @file($template_file))));
        return $footer;
    }

    /* 取出網站的 header */
    function get_site_header($path_name, $title="", $style_file="", $query="")
    {
        Global $uid, $uacn, $company, $lang, $dir_set, $fun_set_conf;

        /* 分離 path_name,取出 acn */
        $path = explode("/", $path_name);
        $acn = $path[0];

        /* 檢查是否有 .nuweb_dir_set 若有就取出設定資料 */
        $dir_set_file = WEB_PAGE_DIR.$path_name."/".NUWEB_DIR_SET;
        if (file_exists($dir_set_file))
            $dir_set = read_conf($dir_set_file);
        /* 轉寄分享預設為開啟,所以如果沒設定就預設為 ON */
        if (!isset($dir_set["share"]))
            $dir_set["share"] = ON;
        /* 預設 page_width 為 DEF_PAGE_WIDTH */
        if (!isset($dir_set["page_width"]))
            $dir_set["page_width"] = DEF_PAGE_WIDTH;
        $page_width = $dir_set["page_width"];
        $page_width_style = WEB_PAGE_DIR.$path_name."/".STYLE_URL.PAGE_WIDTH_CSS_PREFIX.$page_width.".css";

        /* 找出 template 檔 */
        if ($dir_set["menu_align"] == "top")
            $template_file = WEB_TEMPLATE_DIR."header_h.tpl.$lang";
        else
            $template_file = WEB_TEMPLATE_DIR."header.tpl.$lang";

        /* 設定樣版基本參數 */
        $tpl = new TemplateLib($template_file);
        $tpl->assignGlobal("path_name", $path_name);
        $tpl->assignGlobal("path_url", SITE_URL.$path_name);
        $tpl->assignGlobal("lang", $lang);
        $tpl->assignGlobal("site", $acn);
        $tpl->assignGlobal("site_url", SITE_URL);
        $tpl->assignGlobal("site_prog_url", SITE_PROG_URL);
        $tpl->assignGlobal("company", $company);
        if (empty($title))
            $tpl->assignGlobal("title", $company);
        else
            $tpl->assignGlobal("title", $title);
        if (!empty($query))
            $tpl->assignGlobal("query", $query);
        if (chk_manager_right($path_name) == PASS)
            $tpl->newBlock("MANAGER_BLOCK");
        if ($dir_set["logo_space"] == "top")
            $tpl->newBlock("LOGO_TOP_BLOCK");
        else
            $tpl->newBlock("LOGO_DEF_BLOCK");
        if (file_exists($page_width_style))
        {
            $tpl->newBlock("PAGE_WIDTH_STYLE");
            $tpl->assign("page_width", $page_width);
        }
        if (!empty($style_file))
        {
            $tpl->newBlock("SET_STYLE");
            $tpl->assign("style_file", $style_file);
        }
        if (!empty($uacn))
        {
            $tpl->newBlock("LOGIN_USER");
            $tpl->assign("user_name", $uacn);
        }
        else
            $tpl->newBlock("LOGIN");

        /* 取出 file.list 的資料 */
        //$file_list = get_file_list(WEB_PAGE_DIR);

        /* 尋找第一層目錄內是否有 .nuweb_menu 若有就取出來當 menu */
        $menu_file = WEB_PAGE_DIR.$acn."/".NUWEB_MENU;
        if (file_exists($menu_file))
        {
            $menu = @file($menu_file);
            $menu_cnt = count($menu);
            for ($i = 0; $i < $menu_cnt; $i++)
            {
                /* 如果有重覆的目錄就直接跳過 */
                $menu[$i] = trim($menu[$i]);
                $duplicate = false;
                for ($j = 0; $j < $i-1; $j++)
                {
                    $menu[$j] = trim($menu[$j]);
                    if ($menu[$i] == $menu[$j])
                    {
                        $duplicate = true;
                        break;
                    }
                }
                if ($duplicate == true)
                    continue;

                /* 若目錄不存在也直接跳過 */
                /* 2015/3/19 修改,若是系統目錄也要跳過 */
                $menu_path = $acn."/".$menu[$i];
                if ((!is_dir(WEB_PAGE_DIR.$menu_path)) || (strstr($menu_path, NUWEB_SYS_FILE) !== false))
                    continue;

                /* 2015/12/16 新增,若是活動目錄 (Events) 且功能設定項目 (fun_set_conf) 設定關閉 event 就代表不使用活動功能目錄,就直接跳過 */
                if (($menu[$i] == EVENT_DIR_NAME) && (isset($fun_set_conf["event"])) && ($fun_set_conf["event"] == NO))
                    continue;

                /* 2015/12/16 新增,若是行事曆目錄 (Calendar) 且功能設定項目 (fun_set_conf) 設定關閉 calendar 就代表不使用行事曆功能目錄,就直接跳過 */
                if (($menu[$i] == CALENDAR_DIR_NAME) && (isset($fun_set_conf["calendar"])) && ($fun_set_conf["calendar"] == NO))
                    continue;

                /* 2015/12/16 新增,若是成員目錄 (Members) 且功能設定項目 (fun_set_conf) 設定關閉 member_dir 就代表不使用成員目錄,就直接跳過 */
                if (($menu[$i] == MEMBER_DIR_NAME) && (isset($fun_set_conf["member_dir"])) && ($fun_set_conf["member_dir"] == NO))
                    continue;

                $menu_name = get_file_name(WEB_PAGE_DIR, $menu_path);
                if (empty($menu_name))
                    $menu_name = $menu[$i];
                $tpl->newBlock("MENU_LIST");
                $tpl->assign("name", $menu_name);
                $link = SITE_URL.$acn."/".INDEX_PROG."?file_path=".$menu_path."/";
                $tpl->assign("link", $link);
            }
        }
        else
        {
            $site_dir = WEB_PAGE_DIR.$acn;
            $handle = opendir($site_dir);
            while ($sub_dir = readdir($handle))
            {
                /* 只取出子目錄,並過濾掉 . & .. & .nuweb_* & symlink */
                $sub_path = $site_dir."/".$sub_dir;
                if ((!is_dir($sub_path)) || ($sub_dir == ".") || ($sub_dir == "..") || (substr($sub_dir, 0, 7) == NUWEB_SYS_FILE) || (is_link($sub_path)))
                    continue;

                /* 取出目錄名稱,並過濾掉 *.files 目錄 */
                $dir_path = $site_acn."/".$sub_dir;
                $dir_name = get_file_name(WEB_PAGE_DIR, $dir_path);
                if (substr($dir_name, -6) == ".files")
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

                $tpl->newBlock("MENU_LIST");
                $tpl->assign("name", $dir_name);
                $link = SITE_URL.$acn."/".INDEX_PROG."?file_path=".$dir_path."/";
                $tpl->assign("link", $link);
            }
        }

        /* 設定回首頁的連結 */
        $tpl->newBlock("MENU_LIST");
        $tpl->assign("name", MSG_HOMEPAGE);
        $tpl->assign("link", SITE_URL.$acn."/");

        $content = $tpl->getOutputContent();
        return $content;
    }

    /* 取出要顯示的 path list */
    //function get_show_path_list($path_name="", $page_file_name="")
    function get_show_path_list($path_name="")
    {
        Global $folder_dir;
        $page_dir = WEB_PAGE_DIR;

        if (!empty($folder_dir))
            $path_name = str_replace($page_dir, "", substr($folder_dir, 0, -1));

        if (empty($path_name))
            return false;

        /* 取得 path list 資料 */
        //$path_list = get_path_list($path_name, $page_file_name);
        $path_list = get_path_list($page_dir, $path_name);
        $path_cnt = count($path_list);

        /* 取得樣板檔 */
        $template_file = "/data/NUWeb_Site/Template/path_list.tpl";

        /* 設定樣版基本參數 */
        $tpl = new TemplateLib($template_file);
        $show_page_url = str_replace(ROOT_PATH, "", DEF_INDEX_PROG);
        $tpl->assignGlobal("show_page_url", $show_page_url);

        /* 整理 PATH 資料設定到樣版中 */
        for ($i = 0; $i < $path_cnt; $i++)
        {
            $tpl->newBlock("PATH");
            if ($i > 0)
                $tpl->assign("gap", PATH_GAP);
            $tpl->assign("path", $path_list[$i]["path"]);
            $tpl->assign("html_page", DEF_HTML_PAGE);
            $tpl->assign("path_link_name", $path_list[$i]["path_name"]);
        }

        $content = $tpl->getOutputContent();
        return $content;
    }

    /* 取出要顯示的項目選單 */
    function get_option_menu($option_menu, $path_name="")
    {
        Global $folder_dir;
        $page_dir = WEB_PAGE_DIR;

        if (!empty($folder_dir))
            $path_name = str_replace($page_dir, "", substr($folder_dir, 0, -1));

        if (empty($path_name))
            return false;

        /* 檢查是否有管理權限 */
        if (chk_manager_right($path_name) == PASS)
            $manage_right = true;
        else
            $manage_right = false;

        /* 檢查是否有上傳權限 */
        if (chk_upload_right($page_dir, $path_name) == PASS)
            $upload_right = true;
        else
            $upload_right = false;

        /* 取得樣板檔 */
        $template_file = "/data/NUWeb_Site/Template/option_menu.tpl";

        /* 設定樣版基本參數 */
        $tpl = new TemplateLib($template_file);
        $tpl->assignGlobal("tiny_mce_url", TINY_MCE_URL);

        /* 整理 option 資料,設定到樣版中 */
        $o_cnt = count($option_menu);
        for ($i = 0; $i < $o_cnt; $i++)
        {
            /* 檢查可顯示的權限,若權限不足就不顯示 */
            if ((($option_menu[$i]["o_right"] == "manager") && ($manage_right != true)) || (($option_menu[$i]["o_right"] == "upload") && ($upload_right != true)))
                continue;

            if (empty($option_menu[$i]["o_fun"]))
            {
                $tpl->newBlock("OPTION_BLOCK");
                $tpl->assign("o_id", $option_menu[$i]["o_id"]);

                $m_cnt = count($option_menu[$i]["m_id"]);
                for ($j = 0; $j < $m_cnt; $j++)
                {
                    $tpl->newBlock("MENU_ITEM");
                    $tpl->assign("m_id", $option_menu[$i]["m_id"][$j]);
                    $tpl->assign("m_name", $option_menu[$i]["m_name"][$j]);
                    $tpl->newBlock("MENU_SELECT");
                    $tpl->assign("m_id", $option_menu[$i]["m_id"][$j]);
                    $tpl->assign("m_fun", $option_menu[$i]["m_fun"][$j]);
                }
            }

            $tpl->newBlock("SHOW_OPTION");
            if (empty($option_menu[$i]["o_fun"]))
            {
                $tpl->newBlock("MULTI_MENU_OPTION");
                $tpl->assign("o_id", $option_menu[$i]["o_id"]);
                $tpl->assign("o_name", $option_menu[$i]["o_name"]);
            }
            else
            {
                $tpl->newBlock("SINGLE_MENU_OPTION");
                $tpl->assign("o_fun", $option_menu[$i]["o_fun"]);
                $tpl->assign("o_name", $option_menu[$i]["o_name"]);
            }
        }

        $content = $tpl->getOutputContent();
        return $content;
    }

    /* 2014/9/18 新增,建立 Member 網頁 */
    function add_member_page($site_acn, $member)
    {
        Global $lang;

        if ((empty($site_acn)) || (empty($member)))
            return false;

        /* 檢查是否有網站管理權限,有權限才可執行本功能 */
        if (chk_manager_right($site_acn) !== PASS)
            return false;

        /* 整理取得成員網頁位置 */
        $member_dir = WEB_PAGE_DIR.$site_acn."/".MEMBER_PATH;
        if (!is_dir($member_dir))
            return false;
        $user = get_user_data($member);
        if ($user == false)
            return false;
        $member_page = $member_dir."/".strtolower($user["acn"]).".html";
        $l = strlen(WEB_PAGE_DIR);
        $page_path = substr($member_page, $l);

        if (!file_exists($member_page))
        {
            /* 把 member 的資料放入樣版中整理出網頁內容,再存到 member 網頁中 */
            $template_file = WEB_TEMPLATE_DIR."member_page.tpl.$lang";
            $content = trim(implode("", @file($template_file)));
            $content = str_replace("{ssn}", $user["ssn"], $content);
            $content = str_replace("{acn}", $user["acn"], $content);
            $content = str_replace("{mail}", $user["mail"], $content);
            $content = str_replace("{sun}", $user["sun"], $content);
            $fp = fopen($member_page, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $content."\n");
            flock($fp, LOCK_UN);
            fclose($fp);

            /* 建立 Aritcle 檔案的 record 檔案 */
            write_def_record(WEB_PAGE_DIR, $page_path, $user["sun"]."-".$user["acn"].".html");

            /* 更新使用的空間 (有傳入內容才需處理) */
            update_use_space(WEB_PAGE_DIR, $page_path, MODE_ADD);
        }
        return true;
    }

    /* 2014/9/18 新增,刪除 Member 網頁 (放進垃圾桶,並不是直接刪除) */
    function del_member_page($site_acn, $member)
    {
        if ((empty($site_acn)) || (empty($member)))
            return false;

        /* 檢查是否有網站管理權限,有權限才可執行本功能 */
        if (chk_manager_right($site_acn) !== PASS)
            return false;

        /* 整理取得成員網頁位置,並將成員網頁放進垃圾桶 */
        $member_dir = WEB_PAGE_DIR.$site_acn."/".MEMBER_PATH;
        if (!is_dir($member_dir))
            return false;
        $user = get_user_data($member);
        if ($user == false)
            return false;
        $member_page = $member_dir."/".strtolower($user["acn"]).".html";
        if (file_exists($member_page))
            set_trash($member_page);
        return true;
    }

    /* 設定 sca 的 session */
    function sca_session($ssn, $sca)
    {
        /* 設定 sca 的 session */
        session_start();
        $_SESSION[$ssn."_sca"] = $sca;
        session_write_close();
    }

    /* 顯示跳出式的訊息 */
    function alert_msg($msg)
    {
        echo "<script language=\"JavaScript\">alert(\"".addslashes($msg)."\");</script>";
    }

    /* 顯示跳出式的錯誤訊息並離開程式 */
    function alert_err($err)
    {
        echo "<script language=\"JavaScript\">alert(\"".addslashes($err)."\"); history.back();</script>";
        exit;
    }

    function show_err($err)
    {
        header("Content-Type: text/html; charset=utf-8");
        echo $err;
        exit;
    }

    /*** 2015/2/2 新增,權限功能 (新修改版本) ***/
    /* 設定 record 內的權限資料 */
    function set_rec_right_info($file_path, $right_rec)
    {
        Global $reg_conf;

        /* 檢查參數 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        $l = strlen(WEB_PAGE_DIR);
        if ((!file_exists($file_path)) || (substr($file_path, 0, $l) !== WEB_PAGE_DIR))
            return false;
        $page_dir = WEB_PAGE_DIR;
        $path = substr($file_path, $l);
        /* 2015/6/12 新增,檢查是否為網站層 */
        if (strstr($path, "/") == false)
            $in_site = true;
        else
            $in_site = false;

        /* 檢查是否有網站管理權限,有權限才可執行本功能 */
        if (chk_manager_right($path) !== PASS)
        {
            /* 2015/3/13 修改,若非管理者就取得登入者的權限狀態,檢查若有設定權限也可執行本功能 */
            $right_status = chk_user_right($file_path);
            if ($right_status["set"] !== PASS)
                return false;
        }

        /* 找出 record file,並取得 record file 資料,先將所有權限欄位都清除 */
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file === false)
            return false;
        $rec = rec2array($rec_file);
        /* 將 record 內所有 RIGHT_PREFIX 開頭的欄位都清除 */
        $l1 = strlen(RIGHT_PREFIX);
        foreach($rec[0] as $key => $value)
        {
            if (substr($key, 0, $l1) == RIGHT_PREFIX)
                unset($rec[0][$key]);
        }

        /* 若 right_rec 是 reset,代表要將 file_path 的所有權限欄位清除 (變成採用上層權限),前面已清除不需處理,但要取得上層權限,以便設定 allow 欄位 */
        if ($right_rec == "reset")
        {
            /* 讀取上層權限 */
            $dir_path = substr($file_path, 0, strrpos($file_path, "/"));
            $right_info = get_rec_right_info($dir_path);
        }
        else
        {
            $right_info = $right_rec;

            /* right_rec 不是 reset,就檢查 right_rec 所有欄位前面都加上 RIGHT_PREFIX */
            foreach($right_rec as $key => $value)
            {
                /* 要過濾掉 right_url 與 share_mode 欄位 */
                if (($key == "right_url") || ($key == "share_mode"))
                    continue;

                /* 2015/3/16 修改,若 value 內有 ',' 代表是 list,就要過濾掉重覆的資料 */
                if (strstr($value, ",") !== false)
                    $value = update_list($value);

                /* 若傳入的欄位前面就已經是 RIGHT_PREFIX 就不必再加上 */
                if (substr($key, 0, $l1) == RIGHT_PREFIX)
                    $rec[0][$key] = $value;
                else
                    $rec[0][RIGHT_PREFIX.$key] = $value;
            }
            /* 只要有設定權限,一律將 flag 設為 Y */
            $rec[0][RIGHT_PREFIX."flag"] = YES;
        }
        /* 若有設定密碼,需將密碼進行編碼 */
        if ((isset($rec[0][RIGHT_PREFIX."pwd"])) && (!empty($rec[0][RIGHT_PREFIX."pwd"])))
            $rec[0][RIGHT_PREFIX."pwd"] = auth_encode($rec[0][RIGHT_PREFIX."pwd"]);

        /* 變更權限後需更新 record 內 allow 與 deny 欄位設定 */
        $r_info = get_page_right_info($page_dir, $path, $rec[0][RIGHT_PREFIX."browse"]);
        foreach($r_info as $key => $value)
            $rec[0][$key] = $value;

        /* 把 record 內容寫回 record file,並紀錄到 modify.list 中 */
        write_rec_file($rec_file, $rec[0]);
        write_modify_list("update", $rec_file, "rec");

        /* 2015/6/12 新增,若是目錄變更權限後需更新底下的子目錄或檔案的 record 資料 (allow 與 deny 欄位) */
        if (is_dir($file_path))
            update_rec_by_right($page_dir, $path, true);

        /* 2015/4/22 新增,將權限資料上傳至 Server */
        upload_rec_right_info($file_path, $rec[0]);

        /* 2015/6/12 新增,若是設定網站的權限,需要調整網站設定的 public 欄位與預設討論區的權限 */
        if ($in_site == true)
        {
            /* 讀取網站資訊 */
            $site_acn = $path;
            $site_dir = $file_path;
            $conf_file = $site_dir."/".NUWEB_CONF;
            $site_conf = read_conf($conf_file);
            $s_public = $site_conf["public"];

            /* 依權限設定調整網站設定的 public (公開) 欄位設定值,瀏覽或下載名單中有 * (ALL_USER) 或 W (WNS_MEMBER) 或 site_owner 就設為公開,否則就設為不公開 */
            $chk_list = $right_info["browse"].",".$right_info["download"];
            if ((strstr($chk_list, ALL_USER) !== false) || (strstr($chk_list, WNS_MEMBER) !== false) || (strstr($chk_list, SITE_OWNER) !== false))
                $site_conf["public"] = YES;
            else
                $site_conf["public"] = NO;
            /* 若 public 內容沒變更,就直接回傳 true */
            if ($s_public == $site_conf["public"])
                return true;

            /* 更新網站設定資料 & Site DB & 上傳 Group Server & Site index & modify.list */
            /* 2015/8/4 修改,改 call update_site_conf 函數來處理更新網站設定資料的相關工作 */
            update_site_conf($site_acn, $site_conf, "update");

            /* 若是個人網站,需要調整 .nuweb_forum 的權限設定 */
            if ($site_conf["type"] == SITE_TYPE_PERSONAL)
            {
                $forum_dir = $site_dir."/".SITE_FORUM_PATH;
                /* 公開就設定預設的討論區權限,不公開就取消權限 (reset) */
                if ($site_conf["public"] == NO)
                    $forum_right = "reset";
                else
                {
                    $site_id = $site_acn.".".strtolower($reg_conf["acn"]);
                    $forum_right["browse"] = ALL_USER.",".$site_id;
                    $forum_right["download"] = ALL_USER.",".$site_id;
                    $forum_right["upload"] = WNS_MEMBER.",".$site_id;
                    $forum_right["edit"] = WNS_MEMBER.",".$site_id;
                    $forum_right["del"] = WNS_MEMBER.",".$site_id;
                }
                set_rec_right_info($forum_dir, $forum_right);
            }
        }

        return true;
    }

    /* 2015/9/16 新增,還原目錄內所有 record 的權限設定 */
    function reset_dir_right_info($dir_path, $path=NULL, $manager_right=NULL)
    {
        /* 檢查參數 */
        $page_dir = WEB_PAGE_DIR;
        if (empty($path))
        {
            if (substr($dir_path, -1) == "/")
                $dir_path = substr($dir_path, 0, -1);
            $l = strlen($page_dir);
            if ((!is_dir($dir_path)) || (substr($dir_path, 0, $l) !== $page_dir))
                return false;
            $path = substr($dir_path, $l);
        }
        else if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);
        $file_path = $page_dir.$path;
        if (!file_exists($file_path))
            return false;

        /* 檢查是否為網站層 */
        if (strstr($path, "/") == false)
            $in_site = true;
        else
            $in_site = false;

        /* 檢查是否有網站管理權限,有權限才可執行本功能 */
        if (($manager_right !== PASS) && (chk_manager_right($path) !== PASS))
            return false;

        /* 找出 record file,並取得 record file 資料,先將所有權限欄位都清除 */
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file === false)
            return false;
        $rec = rec2array($rec_file);
        /* 將 record 內所有 RIGHT_PREFIX 開頭的欄位都清除 */
        $l1 = strlen(RIGHT_PREFIX);
        foreach($rec[0] as $key => $value)
        {
            if (substr($key, 0, $l1) == RIGHT_PREFIX)
                unset($rec[0][$key]);
        }

        /* 要取得上層權限,以便設定 allow 欄位 */
        $dir_path = substr($file_path, 0, strrpos($file_path, "/"));
        $right_info = get_rec_right_info($dir_path);

        /* 變更權限後需更新 record 內 allow 與 deny 欄位設定 */
        $r_info = get_page_right_info($page_dir, $path, NULL);
        foreach($r_info as $key => $value)
            $rec[0][$key] = $value;

        /* 把 record 內容寫回 record file,並紀錄到 modify.list 中 */
        write_rec_file($rec_file, $rec[0]);
        write_modify_list("update", $rec_file, "rec");

        /* 若是目錄變更權限後需更新底下的子目錄或檔案的 record 資料 (allow 與 deny 欄位) */
        if (is_dir($file_path))
            update_rec_by_right($page_dir, $path, true);

        /* 2015/4/22 新增,將權限資料上傳至 Server */
        upload_rec_right_info($file_path, $rec[0]);
    }

    /* 2015/4/20 新增,將權限資料上傳至 Server */
    function upload_rec_right_info($file_path, $rec)
    {
        Global $reg_conf, $wns_ser, $wns_port;

        /* 檢查參數 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        $l = strlen(WEB_PAGE_DIR);
        if ((!file_exists($file_path)) || (substr($file_path, 0, $l) !== WEB_PAGE_DIR))
            return false;

        /* 整理 share right 的 record 資料 */
        $cs = trim($reg_conf["acn"]);
        $url = "http://$cs.nuweb.cc".str_replace(WEB_ROOT_PATH, "", $file_path);
        $view_path = get_view_path($file_path);
        $right_rec["url"] = $url;
        $right_rec["view_path"] = $view_path;
        $l = strlen(RIGHT_PREFIX);
        foreach($rec as $k => $v)
        {
            if ($k == RIGHT_PREFIX."flag")
                continue;
            if (($k == "filename") || ($k == "title") || ($k == "owner") || ($k == "type") || ($k == "time") || (substr($k, 0, $l) == RIGHT_PREFIX))
                $right_rec[$k] = trim($v);
        }

        if (($fp = fsockopen($wns_ser, $wns_port, $errno, $errstr)) != false)
        {
            /* 設定傳送到 Server 的參數 */
            $arg = "ssn=".$reg_conf["ssn"]."&sca=".$reg_conf["sca"]."&acn=$cs";
            $arg .= "&rec=".json_encode($right_rec);

            $head = "POST ".UPDATE_SHARE_RIGHT_PROG." HTTP/1.0\r\n";
            $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: ". strlen($arg) . "\r\n\r\n";
            $head .= "$arg\r\n";
            fputs($fp, $head);
            fclose($fp);
        }
    }

    /* 取得功能 record 內的權限資料 */
    function get_funrec_right_info($rec, $dir_right_info=NULL)
    {
        /* 檢查參數 */
        if ((empty($rec)) || (empty($rec["url"])))
            return false;

        /* 若 record 內的 flag 為 Y 代表有設定權限,直接傳回權限資料 */
        if ((isset($rec[RIGHT_PREFIX."flag"])) && ($rec[RIGHT_PREFIX."flag"] == YES))
        {
            /* 取得 record 內所有 RIGHT_PREFIX 開頭的欄位資料,整理出權限資料 */
            $l = strlen(RIGHT_PREFIX);
            foreach($rec as $key => $value)
            {
                /* 找出 RIGHT_PREFIX 開頭的所有欄位(權限相關欄位),但過濾掉 flag (因為此欄位僅記錄是否有設權限,不必使用) */
                if ((substr($key, 0, $l) == RIGHT_PREFIX) && ($key != RIGHT_PREFIX."flag"))
                {
                    $field = substr($key, $l);
                    $right_info[$field] = $value;
                }
            }
            return $right_info;
        }

        /* record 內沒設權限就回傳目錄的權限資料,若有傳入 dir_right_info (目錄權限資料),就直接回傳 */
        if (empty($dir_right_info))
        {
            $n = strpos($rec["url"], "?");
            $file_path = WEB_ROOT_PATH.(($n == false) ? $rec["url"] : substr($rec["url"], 0, $n));
            $dir_path = substr($file_path, 0, strrpos($file_path, "/"));
            $dir_right_info =  get_rec_right_info($dir_path);
        }
        return $dir_right_info;
    }

    /* 取得 record 內的權限資料 */
    function get_rec_right_info($file_path, $share_mode=NULL)
    {
        Global $reg_conf;

        /* 檢查參數 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        $l = strlen(WEB_PAGE_DIR);
        if ((!file_exists($file_path)) || (substr($file_path, 0, $l) !== WEB_PAGE_DIR))
            return false;

        /* 找出 record file,並取得 record file 資料 */
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file !== false)
            $rec = rec2array($rec_file);
        /* 若有設定 share_mode,就取回 share_mode 資料 */
        /* 2015/5/11 修改,若有傳入 share_mode,代表由下層向上檢查,但下層有設 share_mode,所以必須以下層的 share_mode 為主 */
        if (!empty($share_mode))
            $right_info["share_mode"] = $share_mode;
        else if ((isset($rec[0]["share_mode"])) && (!empty($rec[0]["share_mode"])))
        {
             $right_info["share_mode"] = $rec[0]["share_mode"];
             $share_mode = $right_info["share_mode"];
        }

        /* 若 record 內的 flag 不是 Y 代表沒設定權限需向上層取得權限 */
        $rl = strlen(WEB_ROOT_PATH);
        $path_url = substr($file_path, $rl);
        if ((!isset($rec[0][RIGHT_PREFIX."flag"])) || ($rec[0][RIGHT_PREFIX."flag"] != YES))
        {
            /* 檢查 file_path 是否為 Driver 目錄,若是就回傳 Driver 目錄預設值 */
            if (is_Driver($path_url) == true)
            {
                $right_info["right_url"] = $path_url;
                $right_info["pwd"] = "";
                $right_info["strong_deny"] = "";
                $right_info["browse"] = "";
                $right_info["download"] = "";
                $right_info["upload"] = "";
                $right_info["edit"] = "";
                $right_info["del"] = "";
                return $right_info;
            }

            /* 取得上層目錄位置,並檢查若不是網站層就讀取權限資料,若是已到網站層就回傳預設值 */
            $dir_path = substr($file_path, 0, strrpos($file_path, "/"));
            if (strlen($dir_path) > $l)
                return get_rec_right_info($dir_path, $share_mode);

            /* 已讀取到網站層,就直接回傳權限預設值,需先取得網站設定資料,以便檢查是否為公開網站 */
            $path = explode("/", substr($file_path, $l));
            $site_acn = $path[0];
            $site_id = $site_acn.".".strtolower($reg_conf["acn"]);
            $site_conf = get_site_conf($site_acn);
            $right_info["right_url"] = $path_url;
            $right_info["pwd"] = "";
            $right_info["strong_deny"] = "";
            /* 網站公開時,瀏覽與下載名單都加上 * (所有人可瀏覽下載),不管是否公開,瀏覽/下載/上傳/編輯名單都要加上 site_id  */
            if ($site_conf["public"] !== NO)
            {
                $right_info["browse"] = "*,$site_id";
                $right_info["download"] = "*,$site_id";
            }
            else
            {
                $right_info["browse"] = $site_id;
                $right_info["download"] = $site_id;
            }
            $right_info["upload"] = $site_id;
            $right_info["edit"] = $site_id;
            $right_info["del"] = "";
            return $right_info;
        }

        /* 先設定讀取到權限資料的目錄位置 */
        $right_info["right_url"] = $path_url;
        /* 2016/1/11 新增,先將所有權限相關欄位都設為空的 */
        $right_info["pwd"] = "";
        $right_info["strong_deny"] = "";
        $right_info["browse"] = "";
        $right_info["download"] = "";
        $right_info["upload"] = "";
        $right_info["edit"] = "";
        $right_info["del"] = "";

        /* 取得 record 內所有 RIGHT_PREFIX 開頭的欄位資料,整理出權限資料 */
        $l1 = strlen(RIGHT_PREFIX);
        foreach($rec[0] as $key => $value)
        {
            /* 找出 RIGHT_PREFIX 開頭的所有欄位(權限相關欄位),但過濾掉 flag (因為此欄位僅記錄是否有設權限,不必使用) */
            if ((substr($key, 0, $l1) == RIGHT_PREFIX) && ($key != RIGHT_PREFIX."flag"))
            {
                $field = substr($key, $l1);
                /* 若是密碼欄位需先解碼 */
                if ($field == "pwd")
                    $value = auth_decode($value);
                $right_info[$field] = $value;
            }
        }
        return $right_info;
    }

    /* 檢查登入者擁有的權限 */
    function chk_user_right($file_path)
    {
        Global $login_user, $pwd_cookie, $auth_pwd, $set_share_pwd;

        /* 檢查參數 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        $rl = strlen(WEB_ROOT_PATH);
        $l = strlen(WEB_PAGE_DIR);
        if ((!file_exists($file_path)) || (substr($file_path, 0, $l) !== WEB_PAGE_DIR))
            return false;
        $path_name = substr($file_path, $l);

        /* 取得 file_path 的權限資料 */
        $r_info = get_rec_right_info($file_path);
        if ($r_info !== false)
        {
            $right_status["right_url"] = $r_info["right_url"];
            /* 檢查是否有設密碼 */
            if ((isset($r_info["pwd"])) && (!empty($r_info["pwd"])))
                $right_status["set_pwd"] = true;
            else
                $right_status["set_pwd"] = false;
        }

        /* 檢查是否有管理權限,若有權限可直接回傳所有權限都是 PASS */
        if (chk_manager_right($path_name) == PASS)
        {
            $right_status["right_url"] = substr($file_path, $rl);
            $right_status["pwd"] = PASS;
            $right_status["browse"] = PASS;
            $right_status["download"] = PASS;
            $right_status["upload"] = PASS;
            $right_status["edit"] = PASS;
            $right_status["del"] = PASS;
            $right_status["set"] = PASS;
            return $right_status;
        }

        /* 設定沒權限時的回傳值,若沒登入就設為 DENY_COOKIE,其他情況就設為 DENY_FORBIDDEN */
        if ((empty($login_user)) || ($login_user == false))
            $deny_code = DENY_COOKIE;
        else
            $deny_code = DENY_FORBIDDEN;

        /* 若有設定 strong_deny 則所有權限都設為 deny_code */
        if ((isset($r_info["strong_deny"])) && ($r_info["strong_deny"] == YES))
        {
            $right_status["pwd"] = $deny_code;
            $right_status["browse"] = $deny_code;
            $right_status["download"] = $deny_code;
            $right_status["upload"] = $deny_code;
            $right_status["edit"] = $deny_code;
            $right_status["del"] = $deny_code;
            $right_status["set"] =  $deny_code;
            return $right_status;
        }

        /* 取得 site member 名單,後面檢查權限時需使用 */
        $site_member_list = get_site_member($path_name);

        /* 檢查各名單的 user 權限 */
        $right_status["pwd"] = NULL;
        $right_status["browse"] = (user_in_list($r_info["browse"], $site_member_list) == true) ? PASS : $deny_code;
        $right_status["download"] = (user_in_list($r_info["download"], $site_member_list) == true) ? PASS : $deny_code;
        $right_status["upload"] = (user_in_list($r_info["upload"], $site_member_list) == true) ? PASS : $deny_code;
        $right_status["edit"] = (user_in_list($r_info["edit"], $site_member_list) == true) ? PASS : $deny_code;
        $right_status["del"] = (user_in_list($r_info["del"], $site_member_list) == true) ? PASS : $deny_code;
        $right_status["set"] = ((isset($r_info["set"])) && (user_in_list($r_info["set"], $site_member_list) == true)) ? PASS : $deny_code;

        /* 2015/6/9 新增,若沒有瀏覽權限,但有傳入 mail 參數,或有找到 mail 的 session,就檢查 mail 參數是否在瀏覽名單中,若存在就設定瀏覽權限 */
        if ($right_status["browse"] !== PASS)
        {
            session_start();
            $mail = "";
            if ((isset($_REQUEST["mail"])) && (!empty($_REQUEST["mail"])))
                $_SESSION["mail"] = $_REQUEST["mail"];
            if ((isset($_SESSION["mail"])) && (!empty($_SESSION["mail"])))
                $mail = $_SESSION["mail"];
            session_write_close();
            if ((!empty($mail)) && (user_in_list($r_info["browse"], $site_member_list, $mail) == true))
                $right_status["browse"] = PASS;
        }

        /* 若沒有瀏覽權限且是檔案就檢查是否為 owner,若是就設定 browse 與 download 為 PASS */
        if (($right_status["browse"] !== PASS) && ($right_status["download"] !== PASS) && (!is_dir($file_path)))
        {
            /* 找出 record file,並取得 record file 資料 */
            $rec_file = get_file_rec_path($file_path);
            if ($rec_file !== false)
            {
                $rec = rec2array($rec_file);
                /* 檢查是否為 owner */
                if ((isset($rec[0]["owner"])) && (($rec[0]["owner"] == $login_user["acn"]) || ($rec[0]["owner"] == $login_user["mail"])))
                {
                    $right_status["browse"] = PASS;
                    $right_status["download"] = PASS;
                }
            }
        }

        /* 檢查密碼 (若 browse 或 download 已有權限就不需再檢查密碼),若有設密碼且輸入密碼正確,就有 browse 與 download 權限 */
        if ((isset($r_info["pwd"])) && (!empty($r_info["pwd"])) && ($right_status["browse"] !== PASS) && ($right_status["download"] !== PASS))
        {
            /* 檢查 pwd_cookie 是否密碼正確 */
            $pwd_ok = false;
            $r_pwd = $r_info["pwd"];
            $r_pwd_cookie = auth_decode($pwd_cookie);
            $r_auth_pwd = auth_decode($auth_pwd);
            if ($r_pwd == $r_pwd_cookie)
                $pwd_ok = true;
            else
            {
                /* pwd_cookie 不正確時,再檢查輸入的密碼是否正確 */
                //$pwd = auth_encode(trim(base64_decode($auth_pwd)));
                if ($r_pwd == $r_auth_pwd)
                {
                    $pwd_ok = true;
                    /* 輸入密碼正確就將編碼後的密碼設定到 pwd_cookie 中 */
                    setcookie("auth_pwd", $auth_pwd, 0, "/");
                }
            }
            /* 若密碼檢查正確,就設定 pwd, browse, download 等權限為 PASS,若密碼錯誤則設定 pwd 權限為 DENY_PWD */
            if ($pwd_ok == true)
            {
                $right_status["pwd"] = PASS;
                $right_status["browse"] = PASS;
                $right_status["download"] = PASS;
            }
            else
                $right_status["pwd"] = DENY_PWD;
        }

        /* 檢查分享權限,先檢查是否有傳入 random_path 並設定 session */
        session_start();
        $random_path = "";
        if (isset($_REQUEST["random_path"]))
            $_SESSION["random_path"] = $_REQUEST["random_path"];
        if (isset($_SESSION["random_path"]))
            $random_path = $_SESSION["random_path"];
        session_write_close();
        /* 檢查是否有分享權限 (檢查 random_path 參數) */
        if ((!empty($random_path)) && (chk_share_code($random_path, $file_path) === true))
        {
            /* 有分享權限就有瀏覽權限*/
            $right_status["browse"] = PASS;
            /* 若沒設定 share_mode 或 share_mode 有 SHARE_DOWNLOAD 就有下載權限 */
            $share_mode = (isset($r_info["share_mode"])) ? $r_info["share_mode"] : DEF_SHARE_MODE;
            if ((empty($share_mode)) || (strstr($share_mode, SHARE_DOWNLOAD) !== false))
                $right_status["download"] = PASS;
            /* 檢查 share_mode 是否有 SHARE_WRITE 才算有上傳與編輯權限 */
            if ((!empty($share_mode)) && (strstr($share_mode, SHARE_WRITE) !== false))
            {
                $right_status["upload"] = PASS;
                $right_status["edit"] = PASS;
            }
        }
        /* 2015/9/14 新增,若沒有瀏覽與下載權限且 set_share_pwd 為 true,代表有設定分享密碼,但尚未輸入分享密碼或輸入密碼錯誤,就設定回傳 share_pwd 欄位為 DENY_PWD */
        if (($right_status["browse"] !== PASS) && ($right_status["download"] !== PASS) && ($set_share_pwd === true))
            $right_status["share_pwd"] = DENY_PWD;

        /* 回傳權限的狀態 */
        return $right_status;
    }

    /* 檢查 user 是否在 list 中 (list 中有 + 代表要檢查是否在 user_list 中) */
    function user_in_list($list, $user_list=NULL, $mail=NULL)
    {
        Global $login_user;

        /* 設定 login user 相關資料 */
        /* 2015/6/9 修改,若有傳入 mail 參數,就不使用 login_user 資料,僅使用 mail 資料進行檢查 */
        if ((empty($login_user)) || ($login_user === false) || (!empty($mail)))
        {
            $wns_member = false;
            $site_owner = false;
            $site_manager = false;
            $acn = NULL;
            //$mail = NULL;
        }
        else
        {
            $wns_member = true;
            $acn = $login_user["acn"];
            $mail = $login_user["mail"];
            $site_owner = ($login_user["site_owner"] !== true) ? false : true;
            $site_manager = ($login_user["site_manager"] !== true) ? false : true;
        }

        /* 取出 list 的元素 */
        /* 2015/3/4 修改,先不將 list 轉成小寫,因 WNS_MEMBER 預設值是大寫,判斷會出問題 */
        //$list_item = explode(" ", str_replace(",", " ", strtolower($list)));
        $list_item = explode(" ", str_replace(",", " ", $list));
        $list_cnt = count($list_item);
        $ulist_cnt = count($user_list);

        /* 判斷 acn 或 mail 是否存在 list 中 */
        for ($i = 0; $i < $list_cnt; $i++)
        {
            /* 若 list 項目沒內容就跳過 */
            if (empty($list_item[$i]))
                continue;

            /* 若 list 中有 * (ALL_USER) 代表所有人都有權限,直接回傳 true */
            if ($list_item[$i] == ALL_USER)
                return true;

            /* 如果沒登入,不需進行下列檢查可跳過 */
            /* 2016/6/9 修改,沒傳入 mail 時才可跳過,若有傳入 mail 還是要進行檢查 */
            if (($wns_member == false) && (empty($mail)))
                continue;

            /* 檢查 list 中是否有 acn 或 mail */
            /* 2015/3/4 修改,將 list_item 轉成小寫進行帳號比對 */
            $item = strtolower($list_item[$i]);
            if (($item == $acn) || ($item == $mail))
                return true;

            /* 如果 list 中有 + (SITE_MEMBER) 就檢查 acn 或 mail 是否存在 user_list 中 */
            if ($list_item[$i] == SITE_MEMBER)
            {
                for ($j = 0; $j < $ulist_cnt; $j++)
                {
                    $user_list[$j] = strtolower($user_list[$j]);
                    if (($user_list[$j] == $acn) || ($user_list[$j] == $mail))
                        return true;
                }
            }

            /* 若 list 中有 site_owner 就檢查 $site_owner 是否為 true */
            if (($list_item[$i] == SITE_OWNER) && ($site_owner == true))
                return true;
            /* 若 list 中有 site_manager 就檢查 site_manager 是否為 true */
            if (($list_item[$i] == SITE_MANAGER) && ($site_manager == true))
                return true;
            /* 若 list 中有 wns_member 就檢查 wns_member 是否為 true */
            if (($list_item[$i] == WNS_MEMBER) && ($wns_member == true))
                return true;

            /* 檢查 list_item 若沒有 '@' 但有 '.' 代表不是 E-mail 而是社群帳號,就取回社群成員帳號 list 進行檢查 */
            if ((strstr($item, "@") === false) && (strstr($item, ".") !== false))
            {
                $member_list = get_cs_site_member($item);
                if (($member_list !== false) && (user_in_list($member_list) == true))
                    return true;
            }
        }

        /* 如果都不在 list 中,就回傳不存在 */
        return false;
    }

    /* 檢查 site_tpl 是否存在 */
    function site_tpl_exists($tpl)
    {
        if (empty($tpl))
            return true;
        $tpl_exist = false;
        $list = @file(SITE_TPL_LIST);
        $cnt = count($list);
        for ($i = 0; $i < $cnt; $i++)
        {
            $tpl_mode = trim($list[$i]);
            if ($tpl_mode == $tpl)
            {
                $tpl_exist = true;
                break;
            }
        }
        return $tpl_exist;
    }

    /*** 網站垃圾桶功能 ***/
    /* 將網站丟到網站垃圾桶 */
    function set_site_trash($site_acn)
    {
        Global $is_manager, $admin_manager;

        $sute_acn = strtolower($site_acn);
        $site_dir = WEB_PAGE_DIR.$site_acn;
        $trash_dir = WEB_PAGE_DIR.NUWEB_TRASH_PATH;

        /* 檢查是否有管理權限,有管理權限才能執行本功能 */
        if (($is_manager != true) && ($admin_manager != true) && (chk_manager_right($site_acn) != PASS))
            return false;

        /* 若網站垃圾桶目錄不存在就建立 */
        if (!is_dir($trash_dir))
            mkdir($trash_dir);

        /* 建立新的垃圾桶儲存目錄 */
        $trash_id = date("Ymd001");
        while(is_dir($trash_dir.$trash_id))
            $trash_id++;
        $trash_id_dir = $trash_dir.$trash_id;
        mkdir($trash_id_dir);

        /* 取得網站資料 */
        $site_conf = get_site_conf($site_acn);
        //$name = $site_conf["name"];

        /* 將網站放進垃圾桶目錄中 */
        if (rename($site_dir, $trash_id_dir."/".$site_acn) == false)
            return false;

        /* 紀錄網站垃圾桶 log */
        //write_site_trash_log($site_acn, $name, $trash_id);
        write_site_trash_log($trash_id, $site_conf);

        return $trash_id;
    }

    /* 將網站從垃圾桶中還原 */
    function recover_site_trash($trash_id)
    {
        Global $is_manager, $admin_manager;

        if (empty($trash_id))
            return false;

        /* 僅系統管理者或後端管理者才能執行本程式 */
        if (($is_manager != true) && ($admin_manager != true))
            return false;

        /* 取得垃圾桶目錄位置,若目錄不存在就不處理 */
        $trash_dir = WEB_PAGE_DIR.NUWEB_TRASH_PATH;
        $trash_id_dir = $trash_dir.$trash_id;
        if (!is_dir($trash_id_dir))
            return false;

        /* 取得還原網站資訊,並檢查網站位置是否已存在,若已存在就不可進行還原,也要檢查 trash_id 目錄內網站目錄是否存在,若已不存在也無法進行還原 */
        $data = get_site_trash_log($trash_id);
        if ((!isset($data["site_acn"])) || (empty($data["site_acn"])))
            return false;
        $site_acn = $data["site_acn"];
        $site_dir = WEB_PAGE_DIR.$site_acn;
        $trash_site_dir = $trash_id_dir."/".$site_acn;
        if ((is_dir($site_dir)) || (!is_dir($trash_site_dir)))
            return false;

        /* 進行網站目錄還原 */
        rename($trash_site_dir, $site_dir);

        /* 取得網站資料 */
        $site_conf = get_site_conf($site_acn);

        /* 向 wns 註冊新網站,若註冊失敗就將網站目錄再移回垃圾桶中,並回傳 false */
        if (new_cs_site($site_acn, $site_conf["name"], $site_conf["owner"], $site_conf["type"], $site_conf["status"], $site_conf["crt_time"]) != true)
        {
            rename($site_dir, $trash_site_dir);
            return false;
        }

        /* 將網站目錄記錄到 site list 中 */
        $fp = fopen(WEB_PAGE_DIR.SITE_LIST, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $site_acn."\t".$site_conf["name"]."\t".$site_conf["owner"]."\t".$site_conf["crt_time"]."\t".$site_conf["status"]."\n");
        flock($fp, LOCK_UN);
        fclose($fp);

        /* call update_site_conf 函數來處理更新網站設定資料的相關工作 */
        update_site_conf($site_acn, $site_conf, "new");

        /* 記錄 register log */
        $log_dir = REGISTER_LOG_DIR.$log_year;
        if (!is_dir($log_dir))
            mkdir($log_dir);
        $log_file = $log_dir."/".$log_date;
        write_server_log($log_file, $site_acn."\t".$site_conf["name"]."\t".$site_conf["owner"]."\t".$site_conf["type"]);

        /* 將管理者資料記錄到 site_manager.list 中 */
        $manager_list = $site_conf["owner"].",".update_list($site_conf["owner"].",".$site_conf["manager"]);
        $fp = fopen(SITE_MANAGER_LIST, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $site_acn."\t".$manager_list."\n");
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 將 site_manager.list 上傳至 Group Server */
        group_upload_site_manager();

        /* 清除網站垃圾桶目錄與 log */
        rmdir($trash_id_dir);
        del_site_trash_log($trash_id);

        return true;
    }

    /* 記錄網站垃圾桶資訊 */
    //function write_site_trash_log($site_acn, $site_name, $trash_id)
    function write_site_trash_log($trash_id, $site_conf)
    {
        Global $uacn, $group_mode, $reg_conf, $set_conf;

        /* 檢查 site_conf 資料 */
        if ((empty($site_conf["site_acn"])) || (empty($site_conf["name"])) || (empty($site_conf["owner"])))
            return false;
        $site_acn = $site_conf["site_acn"];
        $name = $site_conf["name"];
        $owner = $site_conf["owner"];
        $type = $site_conf["type"];

        /* 取得垃圾桶資訊檔位置 */
        $trash_dir = WEB_PAGE_DIR.NUWEB_TRASH_PATH;
        $trash_log = $trash_dir.TRASH_LOG;
        $trash_id_dir = $trash_dir.$trash_id;
        if ((empty($trash_id)) || (!is_dir($trash_id_dir)))
            return false;

        /* 記錄垃圾桶資訊到 log file */
        $log_time = date("YmdHis");
        $log_msg = "$log_time\t$uacn\t$trash_id\t$site_acn\t$name\t$owner\t$type";
        $fp = fopen($trash_log, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$log_msg\n");
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 若是 Group Server 必須將垃圾桶資訊也寫到 group_trash.log 中 (要增加記錄 cs) */
        $cs = $reg_conf["acn"];
        if ($group_mode == GROUP_SERVER)
            write_group_site_trash_log($log_msg, $cs);

        /* 若是 Group Client 必須通知 Group Server 記錄垃圾桶資訊 */
        if ($group_mode == GROUP_CLIENT)
        {
            $grp_code = get_grp_code("write_site_trash_log", $log_msg);
            $url = "http://".$set_conf["group_server"].".nuweb.cc".GROUP_API."?grp_code=$grp_code";
            $content = trim(implode("", @file($url)));
        }

        return true;
    }

    /* 取得網站垃圾桶資訊 */
    function get_site_trash_log($trash_id="")
    {
        Global $group_mode;

        /* 若是 Group Server 就改 call get_group_site_trash_log */
        if ($group_mode == GROUP_SERVER)
            return get_group_site_trash_log($trash_id);

        /* 取得垃圾桶資訊檔位置 */
        $trash_log = WEB_PAGE_DIR.NUWEB_TRASH_PATH.TRASH_LOG;
        if (!file_exists($trash_log))
            return false;

        /* 取得 log 資料 */
        $buf = @file($trash_log);
        $cnt = count($buf);
        for ($i = 0; $i < $cnt; $i++)
        {

            list($time, $acn, $l_trash_id, $site_acn, $name, $owner, $type) = explode("\t", trim($buf[$i]));
            if (empty($trash_id))
            {
                $list[$i]["time"] = $time;
                $list[$i]["acn"] = $acn;
                $list[$i]["trash_id"] = $l_trash_id;
                $list[$i]["site_acn"] = $site_acn;
                $list[$i]["name"] = $name;
                $list[$i]["owner"] = $owner;
                $list[$i]["type"] = $type;
            }
            else if ($l_trash_id == $trash_id)
            {
                $list["time"] = $time;
                $list["acn"] = $acn;
                $list["trash_id"] = $l_trash_id;
                $list["site_acn"] = $site_acn;
                $list["name"] = $name;
                $list["owner"] = $owner;
                $list["type"] = $type;
            }
        }
        return $list;
    }

    /* 刪除網站垃圾桶資訊 */
    function del_site_trash_log($trash_id)
    {
        Global $uacn, $group_mode, $reg_conf, $set_conf;

        /* 取得垃圾桶資訊檔位置 */
        $trash_log = WEB_PAGE_DIR.NUWEB_TRASH_PATH.TRASH_LOG;
        if (!file_exists($trash_log))
            return false;

        /* 取得 log 資料,並過濾掉要刪除的資訊 */
        $buf = @file($trash_log);
        $cnt = count($buf);
        $change = false;
        $trash_content = "";
        for ($i = 0; $i < $cnt; $i++)
        {
            list($time, $acn, $t_id, $site_acn, $name, $owner, $type) = explode("\t", trim($buf[$i]));
            if ($t_id === $trash_id)
                $change = true;
            else
                $trash_content .= $buf[$i];
        }

        /* 檢查垃圾桶資訊檔內容是否有變更 */
        if ($change === true)
        {
            /* 若垃圾桶檔內容已清空,就刪除此檔案,否則就重新儲存 */
            if (!empty($trash_content))
            {
                $fp = fopen($trash_log, "w");
                flock($fp, LOCK_EX);
                fputs($fp, $trash_content);
                flock($fp, LOCK_UN);
                fclose($fp);
            }
            else
                unlink($trash_log);

            /* 若是 Group Server 必須將 group_trash.log 內的資料也刪除 */
            $cs = $reg_conf["acn"];
            if ($group_mode == GROUP_SERVER)
                del_group_site_trash_log($trash_id, $cs);

            /* 若是 Group Client 必須通知 Group Server 刪除垃圾桶資訊 */
            if ($group_mode == GROUP_CLIENT)
            {
                $grp_code = get_grp_code("del_site_trash_log", $trash_id);
                $url = "http://".$set_conf["group_server"].".nuweb.cc".GROUP_API."?grp_code=$grp_code";
                $content = trim(implode("", @file($url)));
            }
        }
        return true;
    }

    /* 清除網站垃圾桶 */
    function del_site_trash($trash_id, $chk_right=true)
    {
        Global $is_manager, $admin_manager;

        if (empty($trash_id))
            return false;

        /* 僅系統管理者或後端管理者才能執行本程式 (若 chk_right=false 代表程式內部使用不檢查權限) */
        if (($chk_right == true) && ($is_manager != true) && ($admin_manager != true))
            return false;

        /* 取得垃圾桶目錄位置,若目錄不存在就不處理 */
        $trash_dir = WEB_PAGE_DIR.NUWEB_TRASH_PATH;
        $trash_id_dir = $trash_dir.$trash_id;
        if (!is_dir($trash_id_dir))
            return false;

        /* 刪除垃圾桶 trash_id 目錄 */
        $cmd = "rm -rf $trash_id_dir";
        $fp = popen($cmd, "r");
        pclose($fp);

        /* 清除 trash_log 中的紀錄 */
        del_site_trash_log($trash_id);
        return true;
    }

    /* 清空網站垃圾桶 */
    function clean_site_trash()
    {
        Global $is_manager, $admin_manager, $group_mode, $reg_conf, $set_conf;

        /* 僅系統管理者或後端管理者才能執行本程式 */
        if (($is_manager != true) && ($admin_manager != true))
            return false;

        /* 取得垃圾桶目錄位置,並刪除垃圾桶目錄 */
        $trash_dir = WEB_PAGE_DIR.NUWEB_TRASH_PATH;
        if (is_dir($trash_dir))
        {
            $cmd = "rm -rf $trash_dir";
            $fp = popen($cmd, "r");
            pclose($fp);
        }

        /* 若是 Group Server 必須將 group_trash.log 內的資料也刪除 */
        $cs = $reg_conf["acn"];
        if ($group_mode == GROUP_SERVER)
            clean_group_site_trash_log($cs);

        /* 若是 Group Client 必須通知 Group Server 清除垃圾桶資訊 */
        if ($group_mode == GROUP_CLIENT)
        {
            $grp_code = get_grp_code("clean_site_trash_log");
            $url = "http://".$set_conf["group_server"].".nuweb.cc".GROUP_API."?grp_code=$grp_code";
            $content = trim(implode("", @file($url)));
        }

        return true;
    }

    /* 清除網站垃圾桶內過期的網站資料 */
    function del_over_site_trash()
    {
        /* 本功能僅由系統呼叫,所以不檢查權限 */
        /* 先取得垃圾桶資訊 */
        $log = get_site_trash_log();
        if ($log == false)
            return false;

        /* 檢查垃圾桶內網站是否過期,若過期就進行刪除 */
        $over_time = date("YmdHis", mktime(0, 0, 0, date("m"), date("d")-MAX_TRASH_DAY, date("Y")));
        $cnt = count($log);
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($log[$i]["time"] < $over_time)
                del_site_trash($log[$i]["trash_id"], false);
        }
        return true;
    }

    /* Group Servver 記錄網站垃圾桶資料 */
    function write_group_site_trash_log($log_msg, $cs)
    {
        Global $group_mode;

        /* 僅 Group Server 需要執行 */
        if ($group_mode !== GROUP_SERVER)
            return false;

        $trash_log = GROUP_SERVER_DIR.TRASH_LOG;
        $fp = fopen($trash_log, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$log_msg\t$cs\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /* 取得 Group Server 網站垃圾桶資訊 */
    function get_group_site_trash_log($trash_id="")
    {
        Global $group_mode;

        /* 僅 Group Server 需要執行 */
        if ($group_mode !== GROUP_SERVER)
            return false;

        /* 取得垃圾桶資訊檔位置 */
        $trash_log = GROUP_SERVER_DIR.TRASH_LOG;
        if (!file_exists($trash_log))
            return false;

        /* 取得 log 資料 */
        $buf = @file($trash_log);
        $cnt = count($buf);
        $n = 0;
        for ($i = 0; $i < $cnt; $i++)
        {

            list($time, $acn, $l_trash_id, $site_acn, $name, $owner, $type, $cs) = explode("\t", trim($buf[$i]));
            if ((empty($trash_id)) || ($l_trash_id == $trash_id))
            {
                $list[$n]["time"] = $time;
                $list[$n]["acn"] = $acn;
                $list[$n]["trash_id"] = $l_trash_id;
                $list[$n]["site_acn"] = $site_acn;
                $list[$n]["name"] = $name;
                $list[$n]["owner"] = $owner;
                $list[$n]["type"] = $type;
                $list[$n]["cs"] = $cs;
                $n++;
            }
        }
        return $list;
    }

    /* Group Servver 刪除網站垃圾桶資料 */
    function del_group_site_trash_log($trash_id, $cs)
    {
        Global $group_mode;

        /* 僅 Group Server 需要執行 */
        if ($group_mode !== GROUP_SERVER)
            return false;

        /* 取得垃圾桶目錄位置,若目錄不存在就不處理 */
        $trash_log = GROUP_SERVER_DIR.TRASH_LOG;
        if (!file_exists($trash_log))
            return false;

        /* 取得 log 資料,並過濾掉要刪除的資訊 */
        $buf = @file($trash_log);
        $cnt = count($buf);
        $change = false;
        $trash_content = "";
        for ($i = 0; $i < $cnt; $i++)
        {
            list($time, $acn, $t_id, $site_acn, $name, $owner, $type, $t_cs) = explode("\t", trim($buf[$i]));
            if (($t_id === $trash_id) && ($t_cs == $cs))
                $change = true;
            else
                $trash_content .= $buf[$i];
        }

        /* 檢查垃圾桶資訊檔內容是否有變更 */
        if ($change === true)
        {
            /* 若垃圾桶檔內容已清空,就刪除此檔案,否則就重新儲存 */
            if (!empty($trash_content))
            {
                $fp = fopen($trash_log, "w");
                flock($fp, LOCK_EX);
                fputs($fp, $trash_content);
                flock($fp, LOCK_UN);
                fclose($fp);
            }
            else
                unlink($trash_log);
        }
        return true;
    }

    /* Group Server 清空網站垃圾桶資料 */
    function clean_group_site_trash_log($cs=NULL)
    {
        Global $group_mode;

        /* 僅 Group Server 需要執行 */
        if ($group_mode !== GROUP_SERVER)
            return false;

        /* 取得垃圾桶目錄位置,若目錄不存在就不處理 */
        $trash_log = GROUP_SERVER_DIR.TRASH_LOG;
        if (!file_exists($trash_log))
            return false;

        /* 若沒傳入 cs 代表全部刪除 */
        if (empty($cs))
            unlink($trash_log);
        else
        {
            /* 取得 log 資料,並過濾掉要刪除的資訊 */
            $buf = @file($trash_log);
            $cnt = count($buf);
            $change = false;
            $trash_content = "";
            for ($i = 0; $i < $cnt; $i++)
            {
                list($time, $acn, $id, $site_acn, $name, $owner, $type, $t_cs) = explode("\t", trim($buf[$i]));
                if ($t_cs == $cs)
                    $change = true;
                else
                    $trash_content .= $buf[$i];
            }

            /* 檢查垃圾桶資訊檔內容是否有變更 */
            if ($change === true)
            {
                /* 若垃圾桶檔內容已清空,就刪除此檔案,否則就重新儲存 */
                if (!empty($trash_content))
                {
                    $fp = fopen($trash_log, "w");
                    flock($fp, LOCK_EX);
                    fputs($fp, $trash_content);
                    flock($fp, LOCK_UN);
                    fclose($fp);
                }
                else
                    unlink($trash_log);
            }
        }
        return true;
    }

    /* Group Server 更新網站垃圾桶資料 */
    function update_group_site_trash_log($cs, $content=NULL)
    {
        Global $group_mode;

        /* 僅 Group Server 需要執行 */
        if ($group_mode !== GROUP_SERVER)
            return false;

        /* 取得垃圾桶目錄位置,並取出資料 */
        $trash_log = GROUP_SERVER_DIR.TRASH_LOG;
        $change = false;
        $trash_content = "";
        if (file_exists($trash_log))
        {
            /* 取得 log 資料,並先過濾掉 cs 的資訊 */
            $buf = @file($trash_log);
            $cnt = count($buf);
            for ($i = 0; $i < $cnt; $i++)
            {
                list($time, $acn, $id, $site_acn, $name, $owner, $type, $t_cs) = explode("\t", trim($buf[$i]));
                if ($t_cs == $cs)
                    $change = true;
                else
                    $trash_content .= $buf[$i];
            }
        }

        /* 將 content 的資料加入 trash_content 中 */
        if (!empty($content))
        {
            $change = true;
            $trash_content .= $content;
        }

        /* 檢查垃圾桶資訊檔內容是否有變更 */
        if ($change === true)
        {
            /* 若垃圾桶檔內容已清空,就刪除此檔案,否則就重新儲存 */
            if (!empty($trash_content))
            {
                $fp = fopen($trash_log, "w");
                flock($fp, LOCK_EX);
                fputs($fp, $trash_content);
                flock($fp, LOCK_UN);
                fclose($fp);
            }
            else
                unlink($trash_log);
        }
        return true;
    }

    /* 將網站垃圾桶資料上傳到 Group Server */
    function upload_group_site_trash_log()
    {
        Global $reg_conf, $set_conf, $group_mode;

        /* 若不是 Group 內的 CS 就不必處理 */
        if ($group_mode == GROUP_NONE)
            return false;

        /* 取得並整理網站垃圾桶的資料 */
        $cs = $reg_conf["acn"];
        $trash_log = WEB_PAGE_DIR.NUWEB_TRASH_PATH.TRASH_LOG;
        $content = "";
        if (file_exists($trash_log))
        {
            $buf = @file($trash_log);
            $cnt = count($buf);
            for ($i = 0; $i < $cnt; $i++)
                $content .= trim($buf[$i])."\t$cs\n";
        }

        /* 若是 Group Server 就直接將網站垃圾桶資料進行更新 */
        if ($group_mode == GROUP_SERVER)
            return update_group_site_trash_log($cs, $content);

        /* 取得 Group Server 的 IP & Port */
        $ip_port = get_acn_ip_port($set_conf["group_server"]);
        if ($ip_port == false)
            return false;
        list($ip, $port) = explode(":", $ip_port);

        /* 整理要傳送的參數 */
        $grp_code = get_grp_code("update_site_trash_log");

        /* 將 site_conf 上傳到 Group Server 中 */
        if (($fp = fsockopen($ip, $port, $errno, $errstr)) != false)
        {
            /* 設定傳送到 Server 的參數 */
            $post_arg = "grp_code=$grp_code";
            $post_arg .= "&content=".rawurlencode($content);

            $head = "POST ".GROUP_API." HTTP/1.0\r\n";
            $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: ". strlen($post_arg) . "\r\n\r\n";
            $head .= "$post_arg\r\n";

            fputs($fp, $head);
            fclose($fp);
        }
        return true;
    }

    /*** 設定會員群組 (Group Alias) 相關功能 ***/
    /* 取得群組 List */
    function get_group_alias_list()
    {
        $grp_list_file = WEB_PAGE_DIR.GROUP_ALIAS_LIST;
        if (!file_exists($grp_list_file))
            return false;
        $buf = @file($grp_list_file);
        $cnt = count($buf);
        if ($cnt == 0)
            return false;
        $grp_list = array();
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 2015/11/25 修改,取消 quota 參數 */
            //list($site_acn, $name, $owner, $crt_time, $quota, $member) = explode("\t", StripSlashes(trim($buf[$i])));
            list($site_acn, $name, $owner, $crt_time, $member) = explode("\t", StripSlashes(trim($buf[$i])));
            if ((empty($site_acn)) || (empty($name)) || (empty($member)))
                continue;
            //array_push($grp_list, array("site_acn"=>$site_acn, "name"=>$name, "owner"=>$owner, "crt_time"=>$crt_time, "quota"=>$quota, "member"=>$member));
            array_push($grp_list, array("site_acn"=>$site_acn, "name"=>$name, "owner"=>$owner, "crt_time"=>$crt_time, "member"=>$member));
        }
        return $grp_list;
    }

    /* 檢查群組是否已存在 (需使用 name 來檢查) */
    function group_alias_exists($name)
    {
        $grp_list_file = WEB_PAGE_DIR.GROUP_ALIAS_LIST;
        if ((!file_exists($grp_list_file)) || (empty($name)))
            return false;
        $buf = @file($grp_list_file);
        $cnt = count($buf);
        if ($cnt == 0)
            return false;
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 2015/11/25 修改,取消 quota 參數 */
            //list($site_acn, $g_name, $owner, $crt_time, $quota, $member) = explode("\t", StripSlashes(trim($buf[$i])));
            list($site_acn, $g_name, $owner, $crt_time, $member) = explode("\t", StripSlashes(trim($buf[$i])));
            if ($name == $g_name)
                return true;
        }
        return false;
    }

    /* 刪除群組 */
    function del_group_alias($site_acn)
    {
        /* 先取得 group_alias.list 資料,將要刪除的群組資料移除後重新存檔 */
        $grp_list_file = WEB_PAGE_DIR.GROUP_ALIAS_LIST;
        if ((!file_exists($grp_list_file)) || (empty($site_acn)))
            return false;
        $site_acn = strtolower($site_acn);
        $buf = @file($grp_list_file);
        $cnt = count($buf);
        if ($cnt == 0)
            return false;
        $content = NULL;
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 2015/11/25 修改,取消 quota 參數 */
            //list($g_site_acn, $name, $owner, $crt_time, $quota, $member) = explode("\t", StripSlashes(trim($buf[$i])));
            list($g_site_acn, $name, $owner, $crt_time, $member) = explode("\t", StripSlashes(trim($buf[$i])));
            if (($site_acn == $g_site_acn) || (empty($g_site_acn)) || (empty($name)) || (empty($member)))
                continue;
            $content .= trim($buf[$i])."\n";
        }
        /* 若已沒有內容就直接移除 group_alias.list */
        if (empty($content))
        {
            unlink($grp_list_file);
            /* 設定 modify.list */
            write_modify_list("del", $grp_list_file, "conf");
        }
        else
        {
            $fp = fopen($grp_list_file, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
            /* 設定 modify.list */
            write_modify_list("update", $grp_list_file, "conf");
        }

        /* 將群組設定檔移除 (如果有備份檔也一併刪除) */
        $grp_file = WEB_PAGE_DIR.$site_acn;
        if (file_exists($grp_file))
            unlink($grp_file);
        $n = 1;
        do
        {
            $grp_bak_file = "$grp_file.bak$n";
            $chk_next = false;
            if (file_exists($grp_bak_file))
            {
                unlink($grp_bak_file);
                $chk_next = true;
                $n++;
            }
        } while ($chk_next);

        /* 通知 WNS 刪除群組 */
        if (del_cs_group_alias($site_acn) !== true)
            return false;
        /* 將群組資訊從 DB 中刪除 */
        group_alias2db_del($site_acn);
        /* 設定 modify.list */
        write_modify_list("del", $grp_file, "conf");

        return true;
    }

    /* 設定群組 */
    /* 2015/11/25 修改,取消 quota 參數 */
    //function set_group_alias($name, $member, $quota=NULL, $site_acn=NULL, $owner=NULL)
    function set_group_alias($name, $member, $site_acn=NULL, $owner=NULL)
    {
        Global $login_user, $is_manager, $admin_manager;

        /* 一定要有 name & member 資料 */
        if ((empty($name)) || (empty($member)))
            return false;

        /* 必須是系統管理者或後端管理者才能使用 */
        if (($is_manager !== true) && ($admin_manager !== true))
            return false;

        /* 先取得 group_alias.list 資料 */
        $grp_list = get_group_alias_list();
        $cnt = count($grp_list);

        /* 沒傳入 site_acn 代表是新增,需檢查 name 是否已存在,再建立群組資料 */
        if ($site_acn == NULL)
        {
            $mode = "new";
            for ($i = 0; $i < $cnt; $i++)
            {
                if ($grp_list[$i]["name"] == $name)
                    return false;
            }
            /* 若沒傳入 owner 就設定 login user 為 owner */
            if (empty($owner))
                $owner = $login_user["acn"];
            /* 建立群組檔案,並取得 site_acn (site_acn 必須是小寫,但取得的位置不一定都小寫,所以需要轉小寫,並將 grp_file 進行 rename) */
            $tmp_file = tempnam(WEB_PAGE_DIR, GROUP_ALIAS_PREFIX);
            if (empty($tmp_file))
                return false;
            $site_acn = strtolower(substr($tmp_file, strrpos($tmp_file, "/")+1));
            $grp_file = WEB_PAGE_DIR.$site_acn;
            rename($tmp_file, $grp_file);
            $grp["site_acn"] = $site_acn;
            $grp["name"] = $name;
            $grp["owner"] = $owner;
            $grp["crt_time"] = date("Y-m-d H:i", time());
            $grp["status"] = STATUS_ALLOW;
            $grp["type"] = SITE_TYPE_GROUP_ALIAS;
            /* 2015/11/25 修改,取消 quota 參數 */
            //$grp["quota"] = $quota;
            $grp["member"] = $member;
        }
        else
        {
            $mode = "update";
            $site_acn = strtolower($site_acn);
            $grp = get_group_alias_info($site_acn);
            /* 如果原設定的 name 與傳入的 name 不一樣,代表有問題,回傳 false */
            if ($grp["name"] !== $name)
                return false;
            /* 如果 member 與 quota 的資料沒變動,代表資料相同,不必更新,所以直接回傳 true,否則就更新資料 */
            /* 2015/11/25 修改,取消檢查 quota 參數 */
            //if (($grp["member"] == $member) && ($grp["quota"] == $quota))
            if ($grp["member"] == $member)
                return $site_acn;
            //$grp["quota"] = $quota;
            $grp["member"] = $member;
        }

        /* 將群組的相關資料寫入 group_alias.list 內 */
        /* 2015/11/25 修改,取消 quota 參數 */
        //$content = "$site_acn\t$name\t".$grp["owner"]."\t".$grp["crt_time"]."\t$quota\t$member\n";
        $content = "$site_acn\t$name\t".$grp["owner"]."\t".$grp["crt_time"]."\t$member\n";
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 跳過 site_acn 的原資料 */
            if ($grp_list[$i]["site_acn"] == $site_acn)
                continue;
            //$content .= $grp_list[$i]["site_acn"]."\t".$grp_list[$i]["name"]."\t".$grp_list[$i]["owner"]."\t".$grp_list[$i]["crt_time"]."\t".$grp_list[$i]["quota"]."\t".$grp_list[$i]["member"]."\n";
            $content .= $grp_list[$i]["site_acn"]."\t".$grp_list[$i]["name"]."\t".$grp_list[$i]["owner"]."\t".$grp_list[$i]["crt_time"]."\t".$grp_list[$i]["member"]."\n";
        }
        $grp_list_file = WEB_PAGE_DIR.GROUP_ALIAS_LIST;
        if (!file_exists($grp_list_file))
            $list_mode = "new";
        else
            $list_mode = "update";
        $fp = fopen($grp_list_file, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $content);
        flock($fp, LOCK_UN);
        fclose($fp);
        /* 設定 modify.list */
        write_modify_list($list_mode, $grp_list_file, "conf");

        /* 儲存群組設定檔資料 */
        $grp_file = WEB_PAGE_DIR.$site_acn;
        write_conf($grp_file, $grp);

        /* 將群組設定檔上傳到 wns server */
        update_cs_group_alias($site_acn);
        /* 將群組資訊更新到 DB 中 */
        group_alias2db($site_acn);
        /* 設定 modify.list */
        write_modify_list($mode, $grp_file, "conf");

        return $site_acn;
    }

    /* 取得群組資訊 */
    function get_group_alias_info($site_acn)
    {
        $site_acn = strtolower($site_acn);
        $grp_file = WEB_PAGE_DIR.$site_acn;
        if (!file_exists($grp_file))
            return false;
        $grp_info = read_conf($grp_file);
        return $grp_info;
    }

    /***  網站 tag 功能 ***/
    /* 設定網站 tag */
    function set_site_tag($site_acn, $tag=NULL)
    {
        Global $wns_ser, $is_manager, $admin_manager;

        $site_acn = strtolower($site_acn);

        /* 檢查參數是否正確 */
        $site_dir = WEB_PAGE_DIR.$site_acn;
        $conf_file = $site_dir."/".NUWEB_CONF;
        if ((empty($site_acn)) || (!is_dir($site_dir)) || (!file_exists($conf_file)) || (($is_manager != true) && ($admin_manager != true) && (chk_manager_right($acn) != PASS)))
            return false;

        /* 讀取網站資訊,若 tag 資料沒變更就不必處理 */
        $s_conf = read_conf($conf_file);
        if (((isset($s_conf["tag"])) && ($tag == $s_conf["tag"])) || ((empty($tag)) && ((!isset($s_conf["tag"])) || (empty($s_conf["tag"])))))
            return true;

        /* 檢查目前的 wns server 是否為 DEF_WNS_SER,若不是就不允許變更網站資料 */
        if ($wns_ser != DEF_WNS_SER)
            return false;

        /* 將 tag 資料設定到 tag 欄位中,再 call update_site_conf 函數來更新網站設定資料 */
        $s_conf["tag"] = $tag;
        update_site_conf($site_acn, $s_conf, "update");
        return true;
    }

    /* 取得網站 tag */
    function get_site_tag($site_acn)
    {
        $site_acn = strtolower($site_acn);

        /* 檢查參數是否正確 */
        $site_dir = WEB_PAGE_DIR.$site_acn;
        $conf_file = $site_dir."/".NUWEB_CONF;
        if ((empty($site_acn)) || (!is_dir($site_dir)) || (!file_exists($conf_file)))
            return false;

        /* 讀取網站資訊,並取得 tag 資料並回傳 */
        $s_conf = read_conf($conf_file);
        if ((!isset($s_conf["tag"])) || (empty($s_conf["tag"])))
            return NULL;
        return $s_conf["tag"];
    }
?>
