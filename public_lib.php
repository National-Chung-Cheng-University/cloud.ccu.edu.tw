<?php
    define("WEB_ROOT_PATH", "/data/HTTPD/htdocs");
    define("SYS_INFO_PROG", "/data/Admin/sys_info.php");
    define("DEF_LANG", "cht");
    define("WEB_USER", "nuweb");
    define("WEB_GROUP", "nuweb");
    define("NUCLOUD_DOMAIN", ".nuweb.cc");

    require_once(SYS_INFO_PROG);
    require_once(WEB_ROOT_PATH."/tools/fe_type.php");
    require_once(WEB_ROOT_PATH."/API/video_lib.php");
    require_once(WEB_ROOT_PATH."/API/content_type.php");
    require_once(WEB_ROOT_PATH."/tools/db_lib.php");

    /*** 檢查並載入 SSO (Single Sing On) 相關功能 ***/
    Global $use_sso;
    $use_sso = false;
    define("SSO_DIR", WEB_ROOT_PATH."/tools/SSO/");
    if (file_exists(SSO_DIR."init.php"))
    {
        $use_sso = true;
        require_once(SSO_DIR."init.php");
    }

    define("ADMIN_DIR", "/data/Admin/");
    define("REGISTER_CONFIG", ADMIN_DIR."register.conf");
    define("SETUP_CONFIG", ADMIN_DIR."setup.conf");
    define("GROUP_USER_LIST", ADMIN_DIR."group_user.list");
    define("DEF_TPL_MODE", ADMIN_DIR."def_tpl_mode.");
    define("SUB_SITE_LINK_NAME", ADMIN_DIR."subsite_linkname");
    define("DEF_LANG_FILE", ADMIN_DIR."def_lang.php");
    define("LANG_LIST_FILE", ADMIN_DIR."lang_list");
    define("EXT_SITE_NAME", "NUWeb_Site.");
    define("INT_SITE_NAME", "Internal_Site");
    define("SUB_SITE_NAME", "Site");
    define("PRIVATE_DIR_NAME", "Private");
    define("FRIEND_DIR_NAME", "Friend");
    define("DRIVER_DIR_NAME", "Driver");
    define("EVENT_DIR_NAME", "Events");
    define("MEMBER_DIR_NAME", "Members");
    define("CALENDAR_DIR_NAME", "Calendar");
    define("DRIVER_DIR_TYPE", "OokonStorage");
    define("GENERAL_DIR_TYPE", "directory");
    define("MULTIMEDIA_DIR_TYPE", "multimedia");
    define("PAGE_DIR_TYPE", "page");
    define("PAGES_DIR", "Pages/");
    define("LANG_MSG_DIR", WEB_ROOT_PATH."/tools/Language/");
    define("WEB_LANG_MSG_DIR", LANG_MSG_DIR."htdocs/");
    define("AD_CONF", ADMIN_DIR."ad.conf");
    define("AD_LIST", ADMIN_DIR."ad.list");
    define("DEF_AD_PLAY_TIME", 3);
    define("CONF_BACKUP_CNT", 5);
    define("FUN_SET_CONF", ADMIN_DIR."fun_set.conf");

    /* site mode */
    define("EXTERNAL_MODE", "External");
    define("INTERNAL_MODE", "Internal");

    /* 2015/8/12 修改,將預設首頁模式由 streaming 改成 link */
    //define("DEF_INDEX_MODE", "streaming");
    define("DEF_INDEX_MODE", "link");

    define("SYS_TMP_DIR", "/tmp/");
    define("NUWEB_DIR", "/data/NUWeb_Site/");
    define("NUWEB_BACKUP_DIR", NUWEB_DIR."Backup/");
    define("NUWEB_BIN_DIR", NUWEB_DIR."bin/");
    define("NUWEB_TMP_DIR", NUWEB_DIR."tmp/");
    define("NUWEB_LIST_DIR", NUWEB_DIR."List/");
    define("NUWEB_LOG_DIR", NUWEB_DIR."logs/");
    define("CLICK_LOG_DIR", NUWEB_LOG_DIR."Click/");
    define("UPLOAD_LOG_DIR", NUWEB_LOG_DIR."Upload/");
    define("REGISTER_LOG_DIR", NUWEB_LOG_DIR."Register/");
    define("AD_LOG_DIR", NUWEB_LOG_DIR."AD/");
    define("SHORT_LOG_DIR", NUWEB_LOG_DIR."Short/");
    define("DATA_SPACE_LOG", NUWEB_LOG_DIR."data_space.log");
    define("SITE_MANAGER_LIST", NUWEB_LIST_DIR."site_manager.list");
    define("SITE_MEMBER_LIST", NUWEB_LIST_DIR."site_member.list");
    define("INDEX_PROCESS_LIST", NUWEB_LIST_DIR."index_process.list");
    define("EXTRACT_LIST", NUWEB_LIST_DIR."extract.list");
    define("MODIFY_LIST", NUWEB_LIST_DIR."modify.list");
    define("SERVER_COPY_LIST", NUWEB_LIST_DIR."server_copy.list");
    define("SERVER_COPY_CLIENT", NUWEB_LIST_DIR."server_copy.client");
    define("SERVER_COPY_KEY", NUWEB_LIST_DIR."server_copy.key");
    define("SHORT_CODE_LIST", NUWEB_LIST_DIR."short_code.list");
    define("SHORT_CODE_DIR", NUWEB_LIST_DIR."ShortCode/");
    define("AUTO_INVITE_LIST", NUWEB_LIST_DIR."auto_invite.list");
    define("GROUP_SERVER_DIR", NUWEB_LIST_DIR."Group_Server/");
    define("GROUP_SITE_MANAGER_DIR", GROUP_SERVER_DIR."Site_Manager/");
    define("GROUP_SITE_CONF_REC_DIR", GROUP_SERVER_DIR."Site_Conf_Rec/");
    define("GROUP_SHORT_DIR", GROUP_SERVER_DIR."Short/");
    define("GROUP_CS_LIST", GROUP_SERVER_DIR."group_cs.list");
    define("GROUP_REG_CNT", GROUP_SERVER_DIR."group_reg.cnt");
    define("GROUP_SITE_INDEX", GROUP_SERVER_DIR."Site_Index");
    define("GROUP_REC_DIR", GROUP_SERVER_DIR."Record/");
    define("GROUP_INDEX_DIR", GROUP_SERVER_DIR."Index/");
    define("GROUP_EVENT_REC", GROUP_SERVER_DIR."event.rec");
    define("GROUP_EVENT_INDEX", GROUP_SERVER_DIR."Event_Index");
    define("GROUP_EVENT_UPDATE_LOG", GROUP_SERVER_DIR."event_update.log");
    define("GROUP_SITE_LIST", NUWEB_LIST_DIR."group_site.list");
    define("AUTH_ENCODE_PROG", NUWEB_BIN_DIR."AuthEncode.exe ");
    define("AUTH_DECODE_PROG", NUWEB_BIN_DIR."AuthDecode.exe ");
    define("REP_PROG", NUWEB_BIN_DIR."rep ");
    define("LATEX2TEXT_PROG", "/usr/bin/detex");
    define("LATEX2PDF_PROG", "/usr/bin/pdflatex");
    define("LATEX2PDF_CMD", LATEX2PDF_PROG." -output-directory=");
    define("PS2PDF_PROG", "/usr/bin/ps2pdf");
    define("PDF_CONVER_PROG", "/usr/bin/soffice");
    define("PDF_CONVER_CMD", PDF_CONVER_PROG." --headless --convert-to pdf --outdir ");
    define("MSOFFICE_EXTRACT", "/usr/bin/java -cp ".NUWEB_BIN_DIR."extractPlainText.jar ExtractPlainText ");
    //define("GET_LOCAL_IP_CMD", "/sbin/ifconfig | grep 'inet addr' | grep -v '127.0.0.1' | awk '{print $2}' | cut -d: -f2 | head -1");
    define("GET_LOCAL_IP_CMD", "/sbin/ifconfig | grep 'inet addr' | grep -v '127.0.0.1' | awk '{print $2}' | cut -d: -f2");
    define("SEARCH_DIR", NUWEB_DIR."Search/");
    define("SEARCH_BIN_DIR", SEARCH_DIR."bin/");
    define("SEARCH_RECORD_DIR", SEARCH_DIR."Record/");
    define("SEARCH_INDEX_DIR", SEARCH_DIR."Index/");
    define("ALL_INDEX_DIR", SEARCH_INDEX_DIR."All/");
    define("DYMANIC_INDEX_DIR", SEARCH_INDEX_DIR."Dymanic/");
    define("EVENT_INDEX_DIR", SEARCH_INDEX_DIR."Event/");
    define("CHILD_REC_DIR", SEARCH_RECORD_DIR."Child/");
    define("CHILD_INDEX_DIR", SEARCH_INDEX_DIR."Child/");
    define("EMPTY_ICON_URL", "/images/empty_icon.gif");
    define("STYLE_URL", "/Style/");
    define("DOC_TO_TEXT_PROG", "/usr/local/bin/decode.cc");
    define("SHARE_REC", SEARCH_RECORD_DIR."share.rec");
    define("DYMANIC_SHARE_REC", SEARCH_RECORD_DIR."dymanic_share.rec");
    define("EVENT_REC", SEARCH_RECORD_DIR."event.rec");
    define("EVENT_UPDATE_LOG", SEARCH_RECORD_DIR."event_update.log");
    define("EVENT_ID", SEARCH_RECORD_DIR."event_id");
    define("SITE_REC", SEARCH_RECORD_DIR."site.rec");
    define("SITE_INDEX_DIR", SEARCH_INDEX_DIR."Site/");
    define("UPDATE_SHARE_REC_PROG", "/Share/update_share_rec.php");
    define("UPDATE_SHARE_RIGHT_PROG", "/Share/update_share_right_rec.php");
    define("DYMANIC_SHARE_REC_PROG", "/Share/dymanic_share_rec.php");
    define("BACKUP_MKDIR_API", "/API/backup_mkdir.php");
    define("BACKUP_API", "/API/backup_api.php");
    define("GROUP_API", "/API/group_api.php");
    define("GROUP_GET_SITE_MANAGER_API", "/API/group_get_site_manager.php");
    define("GROUP_REDIRECT_API", "/API/group_redirect.php");
    define("SERCP_API", "/API/sercp_api.php");
    define("SSH_PUBLIC_KEY", "/home/nuweb/.ssh/id_rsa.pub");
    define("SSH_KEYS_FILE", "/home/nuweb/.ssh/authorized_keys");

    //define("UTF8_START_CODE", "﻿");
    define("UTF8_START_CODE",  "\xEF\xBB\xBF");
    define("UTF16_START_CODE",  "\xFF\xFE");
    define("UTF16BE_START_CODE", "\xFE\xFF");
    define("REC_START", "@\n");
    define("REC_FIELD_START", "@");
    define("REC_ID_FIELD", "@_i:");
    define("GAIS_REC_FIELD", "GAIS_Rec");
    define("REC_BEGIN_PATTERN", "@".GAIS_REC_FIELD.":\n");
    define("TITLE_PREFIX", "<title>");
    define("TITLE_POSTFIX", "</title>");
    define("EXTRACT_TN", "/data/cache/bin/extract_tn.sh");
    define("INDEX_PROCESS_FLAG", NUWEB_TMP_DIR."index_process.flag");
    define("MODIFY_FLAG", NUWEB_TMP_DIR."modify.flag");
    define("EXTRACT_FLAG", NUWEB_TMP_DIR."extract.flag");
    //define("BACKUP_MODE", NUWEB_TMP_DIR."backup.mode");
    define("BACKUP_MODE", SYS_TMP_DIR."backup.mode"); // 2014/9/22 修改,將 backup.mode 改存放在 /tmp/ 內,因原本存放在 /data/NUWeb_Site/tmp/ 在進行 full_backup 後,內容會被變更
    define("BACKUP_MODE_TIME", 5*60);
    define("MAX_BACKUP_UPLOAD_SIZE", 20*1024*1024);
    define("MAX_PROCESS_TIME", 60*60);
    define("MAX_EDIT_TIME", 6*60*60);
    define("MAX_BUILD_TIME", 24*60*60);
    define("MAX_TRASH_DAY", 30);
    define("MAX_ACTIVE_TIME", 5 * 60);
    define("MAX_CHECK_TIME", 10);
    define("CHUNK_SIZE", 1*(1024*1024));         // how many bytes per chunk
    define("MAX_BUFFER_LEN", 1024);
    define("MIN_INDEX_IMG_SIZE", 20*1024);
    define("CLICK_MAX_TIME", 30*60);
    define("CLICK_FILE_TIME", 24*60*60);
    define("DEF_RETRY_CNT", 3);
    define("DEF_RETRY_SLEEP", 100);
    define("DEF_LOGIN_TIME", 7*24*60*60); // 2015/3/26 新增,設定 Login cookie 的預設有效時間為 7 天
    define("FILE_LIST", "file.list");
    define("DEF_HTML_PAGE", "index.html");
    define("NUWEB_SYS_FILE", ".nuweb_");
    define("NUWEB_DEF", ".nuweb_def");
    define("NUWEB_TYPE", ".nuweb_type");
    define("NUWEB_CONF", ".nuweb_conf");
    define("NUWEB_SUBMENU", ".nuweb_submenu");
    define("NUWEB_SUBMENU_SET", ".nuweb_submenu_set");
    define("NUWEB_MENU", ".nuweb_menu");
    define("NUWEB_DIR_SET", ".nuweb_dir_set");
    define("NUWEB_FOOTER", ".nuweb_footer");
    define("NUWEB_MEMBER", ".nuweb_member");
    define("NUWEB_BG_IMG", ".nuweb_bg_img");
    #define("NUWEB_LOGO", ".nuweb_logo");
    define("NUWEB_LOGO", ".nuweb_public/.nuweb_logo");
    define("NUWEB_HEADER", ".nuweb_header");
    define("NUWEB_REC_PATH", ".nuweb_rec/");
    define("NUWEB_MEDIA_PATH", ".nuweb_media/");
    define("NUWEB_PDF_PATH", ".nuweb_pdf/");
    define("NUWEB_VER_PATH", ".nuweb_ver/");
    define("NUWEB_TRASH_PATH", ".nuweb_trash/");
    define("NUWEB_INDEX_PATH", ".nuweb_index");
    define("NUWEB_MSG_PATH", ".nuweb_msg");
    define("NUWEB_SHARE_LOG", ".nuweb_share_log");
    define("NUWEB_EVENT_LOG", ".nuweb_event_log");
    define("NUWEB_SUB_LIST", ".nuweb_sub_list");
    //define("MEMBER_PATH", "Members");
    define("MEMBER_PATH", MEMBER_DIR_NAME);
    define("TRASH_LOG", "trash.log");
    define("TRASH_REC", "trash.rec");
    define("DIR_INDEX", "dir_index");
    define("SYNC_DIR", ".sync");
    define("DIR_RECORD", "dir.rec");
    define("DIR_COMMENT_RECORD", "dir.comment.rec");
    define("FUN_RECORD", "fun.rec");
    define("CNT_VIEW_FIELD", "cnt_view");
    define("DEF_TN_SIZE", 300);
    define("MED_TN_SIZE", 640);
    define("MED2_TN_SIZE", 1024);
    define("BIG_TN_SIZE", 1920);
    define("TN_FE_NAME", ".thumbs.jpg");
    define("MED_TN_FE_NAME", ".".MED_TN_SIZE.TN_FE_NAME);
    define("MED2_TN_FE_NAME", ".".MED2_TN_SIZE.TN_FE_NAME);
    define("BIG_TN_FE_NAME", ".".BIG_TN_SIZE.TN_FE_NAME);
    define("SRC_TN_FE_NAME", ".src".TN_FE_NAME);
    define("LOCK_DIR_PICT", "lock_dir.gif");
    define("DEF_BG_IMG", "images/header.jpg");
    define("DEF_LOGO_URL", "/images/def_logo.gif");
    define("DEF_SERVER_LOGO_URL", "/images/logo.png");
    define("ICON_PATH", WEB_ROOT_PATH."/favicon.ico");
    define("DEF_ICON_PATH", WEB_ROOT_PATH."/images/favicon.ico");
    define("UPLOAD_IMAGE_DIR", WEB_ROOT_PATH."/images/Upload/");
    define("DEF_PAGE_WIDTH", 850);
    define("DEF_MENU_PLACE", "L");
    define("DEF_LOGO_PLACE", "M");
    define("PAGE_WIDTH_CSS_PREFIX", "template_");
    define("COOKIE_DOMAIN", "");
    define("EDIT_PASS_CODE", "nuweb_editpass");
    define("DEF_WEB_PORT", 80);
    define("DEF_SSH_PORT", 22);
    define("HIDDEN_TAG", "SYS_HIDE");
    define("DEF_USER_AGENT", "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)");

    define("DENY_NONE", "DENY_NONE");
    define("DENY_PASSWORD", "DENY_PASSWORD");
    define("DENY_ALL", "DENY_ALL");
    define("DENY_GROUP_USER", "DENY_GROUP_USER");
    define("ALLOW_NONE", "ALLOW_NONE");
    define("ALLOW_ALL", "ALLOW_ALL");
    define("ALLOW_GROUP_USER", "ALLOW_GROUP_USER");
    define("ALLOW_HIDDEN", "ALLOW_HIDDEN");

    define("CSS_DIR", WEB_ROOT_PATH."/css/");
    define("SUBMENU_ID_PREFIX", "bn_");
    define("DEF_SUBMENU_ID", SUBMENU_ID_PREFIX."000");
    define("DEF_SUBMENU_WIDTH", 70);
    define("DEF_SUBMENU_TOP_BOTTOM", 3);
    define("DEF_SUBMENU_LEFT_RIGHT", 5);
    define("SUBMENU_CSS", CSS_DIR."submenu.css");
    define("SUBMENU_CSS_DIR", CSS_DIR."submenu/");
    define("SITE_LIST", "site.list");
    define("GROUP_ALIAS_LIST", "group_alias.list");
    define("GROUP_ALIAS_PREFIX", "g_");

    define("DESCRIBE_PREFIX", "<!-- ##TEMP_DESCRIBE_BEGIN##");
    define("DESCRIBE_POSTFIX", "##TEMP_DESCRIBE_END## -->");
    define("DESCRIBE_DIV_START", "<div id=\"Article_Describe\">");
    define("DESCRIBE_DIV_END", "</div>");
    define("DESCRIBE_LEN", 128);
    define("CONTENT_LEN", 102400);

    /* 2014/2/18 新增,分享的權限模式 */
    define("SHARE_READ", "R");
    define("SHARE_DOWNLOAD", "D");
    define("SHARE_WRITE", "W");
    define("DEF_SHARE_MODE", SHARE_DOWNLOAD.",".SHARE_READ);

    /* 2015/1/22 新增,權限模式 */
    define("RIGHT_BROWSE", "B");
    define("RIGHT_DOWNLOAD", "L");
    define("RIGHT_UPLOAD", "U");
    define("RIGHT_EDIT", "E");
    define("RIGHT_DEL", "D");
    define("DEF_SITE_RIGHT_MODE", RIGHT_BROWSE);
    define("DEF_DIR_RIGHT_MODE", RIGHT_BROWSE.",".RIGHT_DOWNLOAD.",".RIGHT_UPLOAD.",".RIGHT_EDIT);
    define("DEF_FILE_RIGHT_MODE", RIGHT_BROWSE.",".RIGHT_EDIT);

    /* 2013/8/27 新增,目錄權限檢查用特殊設定值,分別代表本機的網站所有者 (site_owner) 與網站管理者 (site_manger) */
    /* 2014/3/11 新增,網站(社群)成員 (+),原本就有使用,但沒有設定 define */
    /* 2015/2/2 新增, 所有人 (*),原本就有使用,但沒有設定 define,以及所有 NUWeb 帳號 (W) 與好友名單 (F_{acn}) */
    define("ALL_USER", "*");
    define("SITE_MEMBER", "+");
    define("SITE_OWNER", "site_owner");
    define("SITE_MANAGER", "site_manager");
    define("WNS_MEMBER", "W");
    define("FRIEND_LIST", "F_");  // 好友名單用 F_{acn} 代表

    /* 參數格式 */
    define("FORMAT_CODE", "/^[0-9a-z]+$/i");
    define("FORMAT_FAIL_FILE_NAME", "/[\\\/:*?\"<>|]/");
    define("FORMAT_FAIL_PATH_NAME", "/[\\\:*?\"<>|]/");
    define("FORMAT_SPECIAL_CHAR", "/[<>\/\\\;\"|]/");
    define("FORMAT_DEL_XSS_CHAR", "/[<>\/]/");

    /* 權限回傳 code */
    define("PASS", 200);
    define("DENY_COOKIE", 302);
    define("DENY_PWD", 401);
    define("DENY_FORBIDDEN", 403);

    /* define SET_PWD / SET_END / GET / RESET / ON / OFF */
    define("SET_PWD", 5);
    define("SET_END", 4);
    define("GET", 3);
    define("RESET", 2);
    define("ON", 1);
    define("OFF", 0);

    /* define YES / NO / SET */
    define("YES", "Y");
    define("NO", "N");
    define("SET", "S");

    /* 子網站認證狀態 */
    define("SITE_STATUS_ALLOW", "A");
    define("SITE_STATUS_DENY", "D");
    define("SITE_STATUS_WAITING", "W");

    /* 2014/7/5 新增,前端多台 CS 群組的模式 */
    define("GROUP_SERVER", "S");
    define("GROUP_CLIENT", "C");
    define("GROUP_NONE", "N");

    /* 2014/8/8 新增,group cs 狀態碼 */
    define("GROUP_STATUS_FAIL", "*");

    /* 2014/7/25 新增,備份的模式 */
    define("BACKUP_SOURCE", "S");
    define("BACKUP_TARGET", "T");
    define("BACKUP_NONE", "N");

    /* 2014/9/8 新增,備份的型態 */
    define("SOURCE_BACKUP", "source"); //備份原始檔
    define("SIMPLE_BACKUP", "simple"); //精簡備份,備份影片轉檔與圖片縮圖檔

    /* 2015/2/2 新增,權限相關定義 */
    define("RIGHT_PREFIX", "r_");     // 權限欄位的 prefix
    define("FUN_PUBLIC", "Public");   // 動態 record 中設定 fun 欄位為公開
    define("FUN_PRIVATE", "Private"); // 動態 record 中設定 fun 欄位為不公開

    /* 2015/10/16 新增,外部帳號認證相關定義 */
    define("ACCOUNT_CHECK_CONF", "/data/Admin/account_check.conf");
    define("ACCOUNT_CHECK_LOG_DIR", NUWEB_LOG_DIR."AccountCheck/");
    define("DEF_LDAP_PORT", 389);
    define("DEF_POP3_PORT", 110);
    define("DEF_POP3S_PORT", 995);
    define("POP3_TIMEOUT", 5);

    /* 2015/11/12 新增,Local Sync 相關參數 */
    define("LOCAL_SYNC_FLAG", SYS_TMP_DIR."local_sync.flag");
    define("LOCAL_SYNC_CRON", NUWEB_LOG_DIR."local_sync.cron");
    define("LOCAL_SYNC_LOG", NUWEB_LOG_DIR."local_sync.log");
    define("LOCAL_SYNC_TMP_LOG", SYS_TMP_DIR."local_sync.log");
    define("LOCAL_SYNC_LAST_LOG", NUWEB_LOG_DIR."local_sync_last.log");
    define("LOCAL_SYNC_LAST_TMP_LOG", SYS_TMP_DIR."local_sync_last.log");

    Global $def_lang, $lang, $set_conf, $reg_conf, $login_user, $uid, $uacn, $is_manager, $admin_manager, $group_mode, $backup_server, $request_chk, $set_share_pwd, $fun_set_conf;
    if (!isset($reg_conf))
        $reg_conf = read_conf(REGISTER_CONFIG);
    if (!isset($set_conf))
        $set_conf = read_conf(SETUP_CONFIG);
    $group_mode = chk_group_mode();
    $backup_server = NULL;
    $set_share_pwd = false;
    /* 2015/12/14 新增,取得功能設定項目資料 */
    if (file_exists(FUN_SET_CONF))
        $fun_set_conf = read_conf(FUN_SET_CONF);
    else
        $fun_set_conf = NULL;

    /* 2014/2/21 先取消 local 端連線時直接使用系統帳號登入的功能 (因有發現偶爾會有一般帳號突然變成系統管理帳號問題,先暫時停用觀察是否還會發生) */
/*
    if (($reg_conf !== false) && (isset($_SERVER["REMOTE_ADDR"])) && (($_SERVER["REMOTE_ADDR"] == "localhost") || ($_SERVER["REMOTE_ADDR"] == "127.0.0.1")))
        set_login_cookie($reg_conf["ssn"], $reg_conf["acn"], $reg_conf["mail"], $reg_conf["sun"]);
*/
    //{
    //    $_COOKIE["ssn_acn"] = $reg_conf["ssn"].":".$reg_conf["acn"];
    //    setcookie("ssn_acn", $_COOKIE["ssn_acn"], 0, "/", COOKIE_DOMAIN);
    //}

    /* 取得登入 user 資料 */
    $uacn = "";
    if (!isset($login_user))
    {
        $login_user = get_login_user();
        $uid = $login_user["ssn"];
        $uacn = $login_user["acn"];
    }

    /* 檢查是否為系統管理者 */
    /* 2015/3/19 修改,管理權限進行分割,系統管理者僅擁有後端管理權限,前端管理僅限各網站管理者,所以將 is_manager 變數一律設為 false,改設定 admin_manager 代表後端管理者 */
    //$is_manager = chk_manager();
    $admin_manager = chk_manager();
    $is_manager = false;

    /* 取得系統預設語系 */
    if (file_exists(DEF_LANG_FILE))
        require_once(DEF_LANG_FILE);
    else
        $def_lang = DEF_LANG;

    /* 如果有傳入 lang 參數,就採用 lang 設定語系 */
    if ((!empty($_REQUEST["lang"])) && (file_exists(LANG_LIST_FILE.".".$_REQUEST["lang"])))
        $lang = $_REQUEST["lang"];

    /* 若沒有設定 lang (語系) 或找不到此語系的列表檔(代表不支援此語系),就使用預設值 */
    if ((empty($lang)) || (!file_exists(LANG_LIST_FILE.".$lang")))
        $lang = $def_lang;

    /* 因 quota_lib.php 與 wns_lib.php 與 Site_Prog/init.php 中有使用 public_lib.php 的定義資料,所以必須放在後面 include,不然會取不到相關定義值 */
    require_once(WEB_ROOT_PATH."/tools/quota_lib.php");
    require_once(WEB_ROOT_PATH."/tools/wns_lib.php");
    require_once(WEB_ROOT_PATH."/Site_Prog/init.php");

    /**********/
    /* 函數區 */
    /**********/

    /* 2015/5/29 新增,在 path 內建立唯一的目錄 */
    function tempdir($path, $prefix=NULL, $str_case="lower")
    {
        /* path 必須是目錄 */
        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);
        if (!is_dir($path))
            return false;

        do {
            /* 先建立一個暫存檔,並取得檔名 */
            $tmp_file = tempnam($path, $prefix);
            if (empty($tmp_file))
                return false;
            $fname = substr($tmp_file, strrpos($tmp_file, "/")+1);
            /* 檢查並調整檔案名稱 (轉成小寫或大寫),並整理出要建立的目錄位置 */
            if ($str_case == "lower")
                $fname = strtolower($fname);
            else if ($str_case == "upper")
                $fname = strtoupper($fname);
            $dir_path = "$path/$fname";
            /* 刪除暫存檔 */
            unlink($tmp_file);
            /* 若要建立的目錄已存在就重新處理 */
        } while (file_exists($dir_path));

        /* 建立目錄,並回傳位置 */
        if (mkdir($dir_path) == false)
            return false;
        return $dir_path;
    }

    /* 2015/3/4 新增,檢查並轉換 $_REQUEST 參數內的特殊字元 (會設定 Global 參數 request_chk 以避免重覆轉換) */
    function addslashes_request()
    {
        Global $request_chk;

        /* 若 request_chk 為 true 代表已轉換過,不必再處理 */
        if ($request_chk == true)
            return;

        /* 如果 PHP 的 magic_quotes_gpc 為 OFF,就將所有參數特殊字元轉換 */
        if (!get_magic_quotes_gpc())
        {
            /* 不用 array_map 函數處理,直接取出 $_REQUEST 的資料處理,才能跳過 Array 資料 */
            foreach ($_REQUEST as $key => $value)
            {
                if (!is_array($value))
                    $_REQUEST[$key] = addslashes($value);
            }
        }
        $request_chk = true;
    }

    /* 2014/8/15 新增,檢查字串中是否有特殊字元 (目前有 \";| 等4個符號) */
    function chk_special_char($str)
    {
        return (preg_match(FORMAT_SPECIAL_CHAR, $str));
    }

    /* 2015/6/10 新增,清除掉字串中的 < > / 等符號,以避免 XSS 問題 */
    function del_xss_char($str)
    {
        return (preg_replace("/[<>\/]/", "", $str));
    }

    /* 2014/6/30 新增,取得某 Server 的 service port */
    function get_server_serevice_port($acn, $service="")
    {
        $service = strtolower($service);

        /* 先取得 server 的 url */
        $ser_url = get_acn_url($acn);
        if ($ser_url == false)
            return false;

        /* 取得 server 的 service port 資料 */
        $url = $ser_url."API/get_service_port.php";
        $service_port = json_decode(trim(@file($url)), true);
        if (empty($service))
            return $service_port;

        /* 若有傳入 service 就只回傳該 service 的 port */
        $cnt = count($service);
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($service_port[$i]["name"] == $service)
                return $service_port[$i];
        }
        return false;
    }

    /* 2014/6/30 新增,取得 acn 的 url */
    /* 2014/7/5 修改 */
    function get_acn_url($acn, $mode="server")
    {
        /* 取得 acn 的 IP & Port */
        $ip_port = get_acn_ip_port($acn, $mode);
        if ($ip_port == false)
            return false;
        return "http://$ip_port/";
    }

    /* 2014/7/5 新增,取得 acn 的 IP & Port (已檢查需使用內部或外部的 IP & Port) */
    function get_acn_ip_port($acn, $mode="server")
    {
        /* 先向 wns 取得 acn 的 IP & Port (web) 資料,若資料錯誤或沒有上線就回傳 false */
        $info = get_ip($acn);
        if (($info == false) || (empty($info["ssn"])) || ($info["ip_int"] == EMPTY_IP) || ($info["ip_ext"] == EMPTY_IP))
            return false;

        /* 取得自己的外部 IP (若 mode = user 就取 REMOTE_ADDR 當成自己的 IP,否則就取得 server IP) */
        /* 2014/9/30 修改,若 mode = user 時,還需要再檢查是否與 Server 再同一個 LAN 內,若是就改取 server IP 當成自己 IP (避免在 Local Lan 卻取成外部 IP & Port) */
        if (($mode == "user") && (chk_local_lan() != true))
            $my_ip = $_SERVER["REMOTE_ADDR"];
        else
            $my_ip = get_server_ip();

        /* 檢查要用外部或內部的 IP & Port */
        if ($my_ip == $info["ip_ext"])
        {
            $s_ip = $info["ip_int"];
            $s_web_port = $info["port_int"];
        }
        else
        {
            $s_ip = $info["ip_ext"];
            $s_web_port = $info["port_ext"];
        }

        /* 回傳 acn_url */
        return "$s_ip:$s_web_port";
    }

    /* 2014/2/14 新增,取得 Server URL */
    function get_server_url()
    {
        $ser_domain = get_server_domain();
        if ($ser_domain !== false)
            return "http://$ser_domain/";
        $ser_ip = get_server_ip();
        $ser_port = get_server_port();
        if ($ser_port == 80)
            return "http://$ser_ip/";
        return "http://$ser_ip:$ser_port/";
    }

    /* 2014/2/14 新增,取得 Server Domain */
    function get_server_domain()
    {
        Global $reg_conf, $set_conf;

        if (!empty($set_conf["domain"]))
            return $set_conf["domain"];
        /* 若是封閉型 Server 就不用繼續檢查 */
        if (chk_close_server() == true)
            return false;
        /* 若不是封閉型 Server 就使用註冊的 alias1 或 acn */
        if (!empty($reg_conf["alias1"]))
            return $reg_conf["alias1"].NUCLOUD_DOMAIN;
        return $reg_conf["acn"].NUCLOUD_DOMAIN;
    }

    /* 2014/2/14 新增,取得 Server IP (外部 IP) */
    function get_server_ip()
    {
        Global $wns_ser, $wns_port;

        $url = "http://$wns_ser:$wns_port/wns/myip_get.php?ssn=00000&sca=00000000";
        return trim(implode("", @file($url)));
    }

    /* 2014/2/14 新增,取得 Server Port (外部 Port) */
    function get_server_port()
    {
        /* 因需使用 read_service_port 函數,所以要先 include upnp_lib.php */
        require_once("/data/HTTPD/htdocs/tools/upnp_lib.php");

        /* 讀取系統設定資料,取出外部 Port 資訊 (若沒設定就使用預設值 80) */
        $service = read_service_port();
        $cnt = count($service);
        $ser_port = 80;
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($service[$i]["name"] == "web")
                $ser_port = $service[$i]["ext_port"];
        }
        return $ser_port;
    }

    function hidden_url_set($file_path, $mode=GET)
    {
        $result["random_path"] = set_share_code($file_path, $mode);
        return $result;
    }

    function hidden_url_chk($random_path, $file_path)
    {
        return chk_share_code($random_path, $file_path);
    }

    /* 設定公開欄位資料 */
    function set_public($file_path, $public=OFF)
    {
        if ($public != ON)
            $public = OFF;

        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 本功能僅提供網站管理員使用 */
        if (chk_site_manager($file_path) === false)
            return false;

        /* 找出 record file */
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file === false)
            return false;

        /* 取出 record file 資料 */
        $rec = rec2array($rec_file);
        /* 比對 public 欄位內容與傳入設定值是否相同,若相同就不必處理 */
        if ((isset($rec[0]["public"])) && ($rec[0]["public"] == $public))
            return true;
        /* 若沒設定 public 欄位,且傳入設定值為 OFF 也不必處理 (因預設值為 OFF) */
        if ((!isset($rec[0]["public"])) && ($public == OFF))
            return true;

        /* 更新 public 欄位並寫回 record file */
        $rec[0]["public"] = $public;
        $rec[0]["public_date"] = date("YmdHis");
        write_rec_file($rec_file, $rec[0]);
        set_share_log($file_path, "public", $public, $rec, $rec[0]["public_date"]);
        return true;
    }

    /* 檢查 file_path 是否為公開 */
    function chk_public($file_path)
    {
        /* 檢查是否為子網站 file_path (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($file_path, 0, $l) !== $site_path) || (strlen($file_path) <= $l))
            return false;

        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 找出 record file */
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file !== false)
        {
            /* 取出 record file 資料 */
            $rec = rec2array($rec_file);

            /* 若 public 欄位設為 ON,就回傳 true */
            if ((isset($rec[0]["public"])) && ($rec[0]["public"] == ON))
                return true;
        }

        /* 向上層檢查是否有公開 */
        $path = substr($file_path, $l);
        while (($n = strrpos($path, "/")) != false)
        {
            /* 取得上層目錄位置 */
            $path = substr($path, 0, $n);

            /* 只要上層有設定公開就回傳 true */
            if (chk_public($site_path.$path) == true)
                return true;
        }

        /* 檢查是否為 .files 目錄,若是就找出原始文章位置,否則回傳 false */
        $src_page = get_files_page($site_path, $path);
        if ($src_page === false)
            return false;

        /* .files 目錄是否為公開,與原始文章權限相同 */
        return chk_public($site_path.$src_page);
    }

    /* 取得 share_code */
    function get_share_code($file_path)
    {
        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 整理 file_path */
        if (substr($file_path, -11) == "/".DEF_HTML_PAGE)
            $file_path = substr($file_path, 0, -11);

        /* 檢查是否為子網站 file_path (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($file_path, 0, $l) !== $site_path) || (strlen($file_path) <= $l))
            return false;

        /* 設定 type */
        if (is_dir($file_path))
            $type = "D";
        else
            $type = "F";

        /* 取得 file_path 的層數與最後的 path_name */
        $path = explode("/", substr($file_path, $l));
        $level = count($path);
        $site_acn = $path[0];
        $path_name = $path[$level-1];

        /* 將 type, level, path_name 整理產生 share_code */
        $share_code = auth_encode("$type,$level,$path_name");
        return $share_code;
    }

    /* 2015/8/10 新增,檢查分享狀態,若在期限內就回傳 true 否則回傳 false */
    function chk_share_status($share_end)
    {
        /* 沒傳入 share_end 或是 share_end 為空的,代表持續有效,所以回傳 true */
        if (empty($share_end))
            return true;
        $now_time = (int)date("YmdHis");
        if ($share_end < $now_time)
            return false;
        return true;
    }

    /* 2015/11/6 新增,檢查是否有設定分享連結的權限 */
    function chk_share_link_right()
    {
        Global $set_conf, $login_user;

        /* share_link_mode 為 NO 或者沒登入,都沒有設定分享連結權限 */
        if (($set_conf["share_link_mode"] == NO) || (empty($login_user)) || ($login_user === false))
            return false;
        /* share_link_mode 為 SET 就必須檢查登入的 user 是否在名單內 */
        if ($set_conf["share_link_mode"] == SET)
        {
            $user = explode(",", $set_conf["share_link_user"]);
            $n = count($user);
            for ($i = 0; $i < $n; $i++)
            {
                if (($login_user["acn"] == $user[$i]) || ($login_user["mail"] == $user[$i]))
                    return true;
            }
            return false;
        }
        /* share_link_mode 不是 NO 也不是 SET 時,一律有權限 */
        return true;
    }

    /* 2015/9/14 新增,檢查分享密碼是否正確 */
    function chk_share_pwd($share_pwd)
    {
        Global $set_share_pwd, $pwd_cookie, $auth_pwd;

        /* 若 share_pwd 是空的,代表沒設定分享密碼,將 set_share_pwd 設為 false 並直接回傳 true (因沒密碼所以分享有效) */
        if (empty($share_pwd))
        {
            $set_share_pwd = false;
            return true;
        }

        /* share_pwd 不是空的就將 set_share_pwd 設為 true,並檢查密碼是否正確 */
        $set_share_pwd = true;
        $r_pwd = auth_decode($share_pwd);
        $r_pwd_cookie = auth_decode($pwd_cookie);
        $r_auth_pwd = auth_decode($auth_pwd);
        /* pwd_cookie 與設定的 share_pwd 密碼內容相同 (需先經過解碼),就回傳 true */
        if ($r_pwd == $r_pwd_cookie)
            return true;
        else
        {
            /* pwd_cookie 不正確時,再檢查輸入的密碼是否正確 (也必須經過解碼) */
            if ($r_pwd == $r_auth_pwd)
            {
                /* 輸入密碼正確就將編碼後的密碼設定到 pwd_cookie 中,並回傳 true */
                setcookie("auth_pwd", $auth_pwd, 0, "/");
                return true;
            }
        }
        /* pwd_cookie 與輸入的密碼都不正確,就回傳 false */
        return false;
    }

    /* 檢查 share_code 是否正確 */
    function chk_share_code($share_code, $file_path, $chk_parent=true)
    {
        Global $fe_type;

        /* 檢查參數 */
        if ((empty($share_code)) || (empty($file_path)))
            return false;

        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 檢查是否為子網站 file_path (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($file_path, 0, $l) !== $site_path) || (strlen($file_path) <= $l))
            return false;

        /* 2014/11/4 新增,若 chk_parent 為 false 代表不向上層檢查 share_code,僅需要檢查 file_path (若是檔案則還須檢查目錄) 的 share_code 與傳入的 share_code 是否相同 */
        if ($chk_parent == false)
        {
            /* 2015/8/10 修改,取回 record 資料,再檢查 share_code 是否正確,另外要檢查分享是否已過期限,若已超過期限也認定 share_code 錯誤 */
            /* 2015/9/14 修改,還要檢查分享密碼是否正確 */
            //$r_share_code = get_rec_field($file_path, "share_code");
            $rec = get_rec_field($file_path);
            if ((isset($rec["share_code"])) && ((!isset($rec["share_end"])) || (chk_share_status($rec["share_end"]) == true)) && ((!isset($rec["share_pwd"])) || (chk_share_pwd($rec["share_pwd"]) == true)))
                $r_share_code = $rec["share_code"];
            if ($r_share_code == $share_code)
                return true;

            /* 若是檔案還必須檢查所在目錄的 share_code 是否與傳入的 share_code 相同 */
            if (is_file($file_path))
            {
                $dir_path = substr($file_path, 0, strrpos($file_path, "/"));
                /* 2015/8/10 修改,取回 record 資料,再檢查 share_code 是否正確,另外要檢查分享是否已過期限,若已超過期限也認定 share_code 錯誤 */
                //$r_share_code = get_rec_field($dir_path, "share_code");
                $rec = get_rec_field($dir_path);
                if ((isset($rec["share_code"])) && ((!isset($rec["share_end"])) || (chk_share_status($rec["share_end"]) == true)))
                    $r_share_code = $rec["share_code"];
                if ($r_share_code == $share_code)
                    return true;
            }
            return false;
        }

        /* 取得並檢查 share_code 內的資料 */
        $content = auth_decode($share_code);
        if ($content == false)
            return false;
        list($type, $level, $path_name) = explode(",", $content);
        if ((empty($type)) || (empty($level)) || (empty($path_name)))
            return false;

        /* 2013/7/22 取消檢查 level 參數,因檔案搬移後 level 可能變更造成判斷錯誤 */
        /* 先找出符合 path_name 的 share_path */
        $path = explode("/", substr($file_path, $l));
        $cnt = count($path);
        $share_path = $site_path;
        $is_path_name = false;
        switch($type)
        {
            /* 檔案 */
            case "F":
                /* 若是檔案且最後一個 path 就等於 path_name, share_path 就等於 file_path */
                if ((is_file($file_path)) && ($path[$cnt-1] == $path_name))
                {
                    $share_path = $file_path;
                    $is_path_name = true;
                    break;
                }

                /* 2014/6/2 修改,先檢查 path_name 是否為網頁,若是必須檢查 file_path 是否為網頁附件檔 (.files 目錄內檔案) */
                $fe = strtolower(substr($path_name, strrpos($path_name, ".")));
                if ($fe_type[$fe] == HTML_TYPE)
                {
                    if (is_dir($file_path))
                        $files_path = substr($file_path, $l);
                    else
                    {
                        $n = strrpos($file_path, "/");
                        if ($n == false)
                            return false;
                        $files_path = substr(substr($file_path, 0, $n), $l);
                    }
                    /* 檢查是否為 .files 目錄,若是就找出原始文章位置,否則回傳 false */
                    $src_path = get_files_page($site_path, $files_path);
                    if ($src_path === false)
                        return false;
                    $share_path = $site_path.$src_path;
                    /* 取出 share_path 的檔名,檢查是否就是 path_name,若不是就回傳 false */
                    $n = strrpos($share_path, "/");
                    $share_file = ($n === false) ? $share_path : substr($share_path, $n+1);
                    if ($share_file == $path_name)
                        $is_path_name = true;
                    break;
                }

                /* 因 type = F,除了 path_name 為網頁外,其他狀況 file_path 都必須是檔案 */
                if (!is_file($file_path))
                    return false;

                /* 若最後一個 path 不是 path_name 的衍生檔,代表不是要找的分享檔,直接回傳 false */
                if (strstr($path[$cnt-1], $path_name) !== $path[$cnt-1])
                    return false;

                /* 找出原始檔的 share_path,有可能在最後第一層(例:查看縮圖時),或是最後第二層(例:查看影片轉檔時) */
                for ($i = 0; $i < $cnt-2 ; $i++)
                    $share_path .= $path[$i]."/";
                if (file_exists($share_path.$path_name))
                {
                    $share_path .= $path_name;
                    $is_path_name = true;
                }
                else
                {
                    $share_path .= $path[$cnt-2]."/".$path_name;
                    if (file_exists($share_path))
                        $is_path_name = true;
                }
                break;

            /* 目錄 */
            case "D":
                /* 逐層尋找是否有 path_name */
                for ($i = 0; $i < $cnt ; $i++)
                {
                    $share_path .= $path[$i]."/";
                    /* 因 type = D,所以必須是目錄 */
                    if (!is_dir($share_path))
                        return false;
                    if ($path[$i] == $path_name)
                    {
                        $is_path_name = true;
                        break;
                    }
                }
                break;

            default:
                return false;

        }

        if ($is_path_name !== true)
            return false;

        /* 取得 share_path 的 share_code 欄位內容,必須與傳入的 share_code 相同 */
        /* 2015/8/10 修改,取回 record 資料,再檢查 share_code 是否正確,另外要檢查分享是否已過期限,若已超過期限也認定 share_code 錯誤 */
        /* 2015/9/14 修改,還要檢查分享密碼是否正確 */
        //$r_share_code = get_rec_field($share_path, "share_code");
        $rec = get_rec_field($share_path);
        if ((isset($rec["share_code"])) && ((!isset($rec["share_end"])) || (chk_share_status($rec["share_end"]) == true)) && ((!isset($rec["share_pwd"])) || (chk_share_pwd($rec["share_pwd"]) == true)))
            $r_share_code = $rec["share_code"];
        if ($r_share_code !== $share_code)
            return false;
        return true;
    }

    /* 設定分享欄位資料 */
    /* 2015/8/10 修改,增加 share_end 參數,用來設定分享結束時間,沒設定代表持續有效 */
    /* 2015/9/10 修改,增加 share_pwd 參數,用來設定分享連結的密碼 */
    function set_share_code($file_path, $mode=ON, $share_end=NULL, $share_pwd=NULL)
    {
        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 本功能僅提供網站管理員使用 */
        if (chk_site_manager($file_path) === false)
            return false;

        /* 2015/11/9 修改,檢查若沒有設定分享連結的權限就無法使用 */
        if (chk_share_link_right() !== true)
            return false;

        /* 找出 record file */
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file === false)
            return false;

        /* 2015/8/11 新增,檢查 share_end 是否正確,若不正確強制設為 NULL */
        if ((!empty($share_end)) && (chk_share_status($share_end) == false))
            $share_end = NULL;

        /* 取出 record file 資料,並找出 share_code 資料 */
        /* 2015/8/10 修改,另外要檢查分享是否已過期限,若已超過期限也認定 share_code 失效 */
        /* 2015/9/10 修改,不在此處檢查 share_code 是否過期,僅在 mode = get 時才需要檢查 */
        $rec = rec2array($rec_file);
        $share_code = "";
        if ((isset($rec[0]["share_code"])) && (!empty($rec[0]["share_code"])))
            $share_code = $rec[0]["share_code"];
        //if ((isset($rec[0]["share_code"])) && ((!isset($rec[0]["share_end"])) || (chk_share_status($rec[0]["share_end"]) == true)))
        //    $share_code = $rec[0]["share_code"];
        /* 2015/8/17 新增,取得原設定的 share_end */
        $s_share_end = NULL;
        if (isset($rec[0]["share_end"]))
            $s_share_end = $rec[0]["share_end"];
        /* 2015/9/10 新增,取得原設定的 share_pwd */
        $s_share_pwd = NULL;
        if (isset($rec[0]["share_pwd"]))
            $s_share_pwd = auth_decode($rec[0]["share_pwd"]);

        /* 依 mode 進行處理 */
        switch($mode)
        {
            /* OFF 代表關閉 share 功能 */
            case OFF:
                $share_code = "";
                /* 2015/8/17 修改,關閉 share 時也要清除 share_end 資料 */
                $share_end = NULL;
                $update_share_end = true;
                /* 2015/9/10 修改,關閉 share 時也要清除 share_pwd 資料 */
                $share_pwd = NULL;
                $update_share_pwd = true;
                break;
            /* ON 代表開啟 share 功能,取得 share_code 若不存在就建立 */
            case ON:
                if (empty($share_code))
                    $share_code = get_share_code($file_path);
                break;
            /* RESET 代表重設 share_code (原本的 share_code 會失效) */
            case RESET:
                $share_code = get_share_code($file_path);
                break;
            /* GET 代表只是要取得目前的 share_code */
            case GET:
                /* i2015/9/10 修改,檢查 share_code 是否過期,若過期就輸出空字串 */
                if ((!empty($share_code)) && (isset($rec[0]["share_end"])) && (chk_share_status($rec[0]["share_end"]) !== true))
                    $share_code = "";
                break;
            /* 2015/8/17 新增,SET_END 代表要設定分享結束時間 */
            case SET_END:
                /* mode 為 SET_END 時,只要 share_end 有變更就要更新資料 */
                $update_share_end = false;
                if ($s_share_end !== $share_end)
                    $update_share_end = true;
                break;
            /* 2015/9/10 新增,SET_PWD 代表要設定分享連結密碼,若 share_pwd 是空的代表清除密碼 */
            case SET_PWD:
                /* mode 為 SET_PWD 時,只要 share_pwd 有變更就要更新資料 */
                $update_share_pwd = false;
                if ($s_share_pwd !== $share_pwd)
                    $update_share_pwd = true;
                break;
            /* mode 參數錯誤回傳 false */
            default:
                return false;
        }
        /* 若 share_code 有變更,才需要更新 record file */
        /* 2015/8/10 修改,若有傳入 share_end,也要檢查 share_end 是否有變更 */
        /* 2015/9/10 修改,若有傳入 share_pwd,也要檢查 share_pwd 是否有變更 */
        if (($rec[0]["share_code"] !== $share_code) || ((!empty($share_end)) && ($share_end !== $s_share_end)) || ($update_share_end == true) || ((!empty($share_pwd)) && ($share_pwd !== $s_share_pwd)) || ($update_share_pwd == true))
        {
            $rec[0]["share_code"] = $share_code;
            /* mode 為 SET_END 時,不用調整 share_date 資料 */
            /* 2015/9/10 修改,mode 為 SET_PWD 時,也不用調整 share_date 資料 */
            if (($mode !== SET_END) && ($mode !== SET_PWD))
                $rec[0]["share_date"] = date("YmdHis");
            /* 2015/8/10 修改,若有傳入 share_end 代表要設定 share_code 有效期限,將 share_end 資料也寫入 record */
            if ((!empty($share_end)) || ($update_share_end == true))
            {
                $rec[0]["share_end"] = $share_end;
                /* 若 share_end 是空的代表清除結束時間,就將 share_end 欄位 unset 掉 */
                if (empty($rec[0]["share_end"]))
                    unset($rec[0]["share_end"]);
            }
            /* 2015/9/10 修改,若有傳入 share_pwd 代表要設定分享連結密碼,將 share_pwd 資料也寫入 record */
            if ((!empty($share_pwd)) || ($update_share_pwd == true))
            {
                $rec[0]["share_pwd"] = $share_pwd;
                /* 若 share_pwd 是空的代表清除密碼,就將 share_pwd 欄位 unset 掉 */
                if (empty($share_pwd))
                {
                    if (isset($rec[0]["share_pwd"]))
                        unset($rec[0]["share_pwd"]);
                }
                else
                    $rec[0]["share_pwd"] = auth_encode($share_pwd);
            }
            write_rec_file($rec_file, $rec[0]);
            set_share_log($file_path, "share", $share_code, $rec, $rec[0]["share_date"]);
        }
        return $share_code;
    }

    /* 從 share record 中取得資料 */
    function get_share_rec($file_path, $fun)
    {
        Global $reg_conf;

        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        $cs_url = "http://".$reg_conf["acn"].NUCLOUD_DOMAIN;
        $url = $cs_url.str_replace(WEB_ROOT_PATH, "", $file_path);
        $rec = rec2array(SHARE_REC);
        $cnt = count($rec);
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 若資料符合就回傳該筆資料 */
            if (($rec[$i]["url"] === $url) && ($rec[$i]["fun"] === $fun))
                return $rec[$i];
        }
        return false;
    }

    /* 取得共用名單 (包含所有上層目錄) */
    function get_use_acn_list($file_path)
    {
        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        $page_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $path = str_replace($page_dir, "", $file_path);
        $list = array();
        $i = 0;
        do {
            /* 取得權限檔資料,檢查是否有設定 strong_deny,若有就不必再向上層取資料,直接回傳目前的 list */
            /* 2015/2/11 刪除,已不再使用 .nuweb_def */
            //$info = read_nuweb_def($page_dir, $path);
            //if (($info == false) || ($info["strong_deny"] == true))
            //    return $list;

            /* 取得 record 內的 use_acn 資料,整理到 list 中,若 use_acn 中有設定 NONE_PARENT 代表不向上層查,直接回傳目前的 list */
            $rec_file = get_file_rec_path($page_dir.$path);
            $use_acn = NULL;
            if ($rec_file == false)
                continue;
            $rec = rec2array($rec_file);

            /* 2015/2/12 修改,取得權限資料檢查是否有設定 strong_deny,若有就不必再向上層取資料,直接回傳目前的 list */
            $r_info = get_rec_right_info($page_dir.$path);
            if ((isset($r_info["strong_deny"])) && ($ri_info["strong_deny"] == YES))
                return $list;

            if ((isset($rec[0]["use_acn"])) && (!empty($rec[0]["use_acn"])))
            {
                $list[$i]["path"] = $path;
                $list[$i]["use_acn"] = $rec[0]["use_acn"];
                if (strstr($rec[0]["use_acn"], "NONE_PARENT") != false)
                    return $list;
                $i++;
            }

            /* 設定上一層 path */
            $n = strrpos($path, "/");
            $path = substr($path, 0, $n);
        } while($n != false);

        return $list;
    }

    /* 將資料從 share record 中刪除 */
    function del_share_rec($url, $fun, $rec=NULL)
    {
        if (empty($rec))
            $rec = rec2array(SHARE_REC);
        $cnt = count($rec);
        $rec_content = "";
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 若資料符合就跳過不加入 rec_content 中 */
            if (($rec[$i]["url"] === $url) && ($rec[$i]["fun"] === $fun))
                continue;

            $rec_content .= REC_START.REC_BEGIN_PATTERN;
            foreach($rec[$i] as $key => $value)
            {
                if ($key == GAIS_REC_FIELD)
                    continue;
                $value = trim($value);
                $rec_content .= "@$key:$value\n";
            }
        }

        /* 若 rec_content 沒有資料,就移除 share record */
        if ((empty($rec_content)) && (file_exists(SHARE_REC)))
            unlink(SHARE_REC);
        else
        {
            $fp = fopen(SHARE_REC, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $rec_content);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    /* 新增 share record 資料 */
    function add_share_rec($share_rec)
    {
        /* 整理 share record 內容 */
        $rec_content = REC_START.REC_BEGIN_PATTERN;
        foreach($share_rec as $key => $value)
        {
            if ($key == GAIS_REC_FIELD)
                continue;
            $value = trim($value);
            $rec_content .= "@$key:$value\n";
        }

        /* 新增到 share record 中 */
        $fp = fopen(SHARE_REC, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /* 更新 share record */
    function update_share_rec($log_data)
    {
        Global $reg_conf, $wns_ser, $wns_port;

        /* 將 log_data 中 share log 資料分離出來 */
        /* 2015/8/11 新增 share_end 參數 */
        list($time, $uacn, $fun, $file_path, $filename, $type, $fun_value, $content, $share_end) = explode("\t", trim($log_data));

        /* 只處理 share 與 use_acn */
        if (($fun !== "share") && ($fun !== "use_acn"))
            return false;

        /* 整理參數 */
        $cs_url = "http://".$reg_conf["acn"].NUCLOUD_DOMAIN;
        $url = $cs_url.str_replace(WEB_ROOT_PATH, "", $file_path);
        $view_path = get_view_path($file_path);
        $page_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $file = str_replace($page_dir, "", $file_path);

        /* 將 share log 資料傳送到 Server 進行 share record 即時更新 */
        /* 2015/4/20 修改,改用 wns_ser 與 wns_port */
        //if (($fp = fsockopen(SHARE_SERVER, 80, $errno, $errstr)) != false)
        if (($fp = fsockopen($wns_ser, $wns_port, $errno, $errstr)) != false)
        {
            /* 設定傳送到 Server 的參數 */
            /* 2015/8/11 新增 share_end 參數 */
            $log_msg = "$time\t$uacn\t$fun\t$url\t$view_path\t$filename\t$type\t$fun_value\t$content\t$share_end";
            $arg = "ssn=".$reg_conf["ssn"]."&sca=".$reg_conf["sca"]."&acn=".$reg_conf["acn"];
            $arg .= "&log_msg=".rawurlencode($log_msg);

            $head = "POST ".UPDATE_SHARE_REC_PROG." HTTP/1.0\r\n";
            $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: ". strlen($arg) . "\r\n\r\n";
            $head .= "$arg\r\n";

            fputs($fp, $head);
            fclose($fp);
        }

        /* 取回所有 share record 資料,並找出此筆資料的原始 record */
        $rec = rec2array(SHARE_REC);
        $cnt = count($rec);
        $share_rec = false;
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 找出原本的 record */
            if (($rec[$i]["url"] === $url) && ($rec[$i]["fun"] === $fun))
                $share_rec = $rec[$i];
        }

        /* 檢查此筆資料是否已存在 share record 中 */
        if ($share_rec !== false)
        {
            /* 2014/12/25 新增,先將舊的 key 值保留下來 */
            $old_key = $share_rec["key"];

            /* 若 value 為空的或是 OFF 代表要刪除該項目 (因 value 是字串,所以 OFF 必須轉成字串才能比對) */
            if (($fun_value === strval(OFF)) || (empty($fun_value)))
            {
                /* 先上傳 dymanic share record */
                /* 2014/12/25 修改,要增加傳送 fun 參數 */
                upload_dymanic_share_rec($page_dir, $file, "unset", NULL, $fun);
                /* 再刪除此筆 share 資料 */
                del_share_rec($url, $fun, $rec);
                return true;
            }

            /* 先刪除此筆 share 資料 */
            del_share_rec($url, $fun, $rec);

            /* 若 fun=share 且 content 不是空的,要將 content 資料進行整合並新增到 share record 中 */
            if (($fun == "share") && (!empty($content)))
            {
                /* 取得 content 中的 mail 資料加入 key 欄位中 */
                preg_match_all("/[\w-.]+@[\w-.]+/", $content, $match);
                $match_cnt = count($match[0]);
                for ($i = 0; $i < $match_cnt; $i++)
                {
                    /* 2014/12/25 修改,改成更新 key 的資料,並非直接加入 (可避免重覆,另外若設定的項目前面有 '-' 代表要刪除,並非加入) */
                    $share_rec["key"] = update_list($share_rec["key"], $match[0][$i]);
                    //if (!empty($share_rec["key"]))
                    //    $share_rec["key"] .= ",";
                    //$share_rec["key"] .= $match[0][$i];
                }
                /* 整理 content 欄位資料,必須與原本的資料整合 */
                $share_rec["content"] .= "\n$time\t$uacn\t$content\n";
                /* 2015/8/11 新增 share_end 欄位資料 */
                if (!empty($share_end))
                    $share_rec["share_end"] = $share_end;
                /* 2014/12/22 新增,將 share 的資料更新到 record 中 */
                $rec_file = get_file_rec_path($file_path);
                $update_rec["share"] = $share_rec["key"];
                update_rec_file($rec_file, $update_rec);
                /* 新增 share record */
                add_share_rec($share_rec);
                /* 上傳 dymanic share record */
                upload_dymanic_share_rec($page_dir, $file, "set");
                /* 2014/12/25 修改,先取得 key 變更的新增與刪除名單,再上傳 dymanic share record */
                /* 2014/12/25 刪除,因改採用新版動態 DB,已不需要分別送新增與刪除名單,所以還原成直接送出動態 record */ 
                //$k_list = get_list_change($old_key, $share_rec["key"]);
                /* 新增名單 */
                //if (!empty($k_list["new"]))
                //    upload_dymanic_share_rec($page_dir, $file, "set", NULL, $k_list["new"]);
                /* 刪除名單 */
                //if (!empty($k_list["del"]))
                //    upload_dymanic_share_rec($page_dir, $file, "unset", NULL, $k_list["del"]);

                return true;
            }
        }
        else
        {
            /* 若不存在 share_record 中,且 value 為空的或是 OFF 代表要刪除該項目,就不必處理 */
            if (($fun_value === strval(OFF)) || (empty($fun_value)))
                return true;
        }

        /* 若 fun=share 時且 content 是空的,代表只是設定 share_code,也不必處理 */
        if (($fun === "share") && (empty($content)))
            return true;

        /* 整理 record 資料 */
        $share_rec["url"] = $url;
        $share_rec["view_path"] = $view_path;
        $share_rec["name"] = $filename;
        $share_rec["fun"] = $fun;
        $share_rec["time"] = $time;
        $share_rec["acn"] = $uacn;
        $share_rec["type"] = $type;
        if ($fun == "use_acn")
            $share_rec["key"] = strtolower($fun_value);
        if ($fun == "share")
        {
            $share_rec["share_code"] = $fun_value;
            /* 取得 content 中的 mail 資料放入 key 欄位中 */
            preg_match_all("/[\w-.]+@[\w-.]+/", $content, $match);
            $match_cnt = count($match[0]);
            $share_rec["key"] = "";
            for ($i = 0; $i < $match_cnt; $i++)
            {
                /* 2014/12/25 修改,改成更新 key 的資料,並非直接加入 (可避免重覆) */
                $share_rec["key"] = update_list($share_rec["key"], $match[0][$i]);
                //if ($i !== 0)
                //    $share_rec["key"] .= ",";
                //$share_rec["key"] .= $match[0][$i];
            }
            /* 整理 content 欄位資料,必須與原本的資料整合 */
            $share_rec["content"] .= "$time\t$uacn\t$content\n";
            /* 2015/8/11 新增 share_end 欄位資料 */
            if (!empty($share_end))
                $share_rec["share_end"] = $share_end;
            /* 2014/12/22 新增,將 share 的資料更新到 record 中 */
            $rec_file = get_file_rec_path($file_path);
            $update_rec["share"] = $share_rec["key"];
            update_rec_file($rec_file, $update_rec);
        }
        /* 新增 share record */
        add_share_rec($share_rec, $share_rec_file);
        /* 上傳 dymanic share record */
        upload_dymanic_share_rec($page_dir, $file, "set");
        /* 2014/12/25 修改,先取得 key 變更的新增與刪除名單,再上傳 dymanic share record */
        /* 2014/12/25 刪除,因改採用新版動態 DB,已不需要分別送新增與刪除名單,所以還原成直接送出動態 record */
        //$k_list = get_list_change($old_key, $share_rec["key"]);
        /* 新增名單 */
        //if (!empty($k_list["new"]))
        //    upload_dymanic_share_rec($page_dir, $file, "set", NULL, $k_list["new"]);
        /* 刪除名單 */
        //if (!empty($k_list["del"]))
        //    upload_dymanic_share_rec($page_dir, $file, "unset", NULL, $k_list["del"]);

        return true;
    }

    /* 更新 list 資料 (若 item 前面有 - 代表要刪除,否則代表要加入) */
    /* 2015/2/12 修改,可不傳入 item 參數,若沒傳入 item 代表要過濾掉 list 內重覆的資料 */
    function update_list($list, $item=NULL)
    {
        /* 沒傳入 item 代表沒變更,直接回傳 list */
        //if (empty($item))
        //    return $list;
        $item = trim($item);
        /* 若 item 第一個字元為 '-' 代表要刪除,否則代表要新增 */
        $del_flag = false;
        if ((!empty($item)) && (substr($item, 0, 1) == "-"))
        {
            $item = substr($item, 1);
            $del_flag = true;
        }

        /* 取出原 list 資料並整理新 list 資料 */
        $src = explode(",", $list);
        $cnt = count($src);
        if ($del_flag == false)
            $new_list = $item;
        else
            $new_list = "";
        for ($i = 0; $i < $cnt; $i++)
        {
            $src[$i] = trim($src[$i]);
            /* 若此筆資料是空的或是 item 就跳過 (不管新增或刪除都跳過,因為新增的部分一開始就已經加入,所以可直接跳過) */
            if ((empty($src[$i])) || ($src[$i] == $item))
                continue;
            /* 檢查是否有重覆,若重覆就跳過 */
            $exist = false;
            for ($j = 0; $j < $i; $j++)
            {
                if ($src[$j] == $src[$i])
                {
                    $exist = true;
                    break;
                }
            }
            if ($exist == true)
                continue;
            /* 將此筆資料加入新 list 中 */
            if (!empty($new_list))
                $new_list .= ",";
            $new_list .= $src[$i];
        }
        return $new_list;
    }

    /* 取得 list 變更的名單 (包括新增與刪除的名單) */
    function get_list_change($old_list, $new_list)
    {
        $list["new"] = NULL;
        $list["del"] = NULL;

        /* 若 old_list 是空的,代表 new_list 全部都是新加入 */
        if (empty($old_list))
        {
            $list["new"] = $new_list;
            return $list;
        }
        /* 若 new_list 是空的,代表 old_list 全部都是要刪除 */
        if (empty($new_list))
        {
            $list["del"] = $old_list;
            return $list;
        }

        /* 取出 old_list 與 new_list 的內容並進行比對找出新增與刪除的名單 */
        $old_item = explode(",", $old_list);
        $new_item = explode(",", $new_list);
        $o_cnt = count($old_item);
        $n_cnt = count($new_item);
        $n = 0;
        for ($i = 0; $i < $o_cnt; $i++)
        {
            $old_item[$i] = trim($old_item[$i]);
            $exist = false;
            for ($j = 0; $j < $n_cnt; $j++)
            {
                if ($i == 0)
                {
                    $new_item[$j] = trim($new_item[$j]);
                    /* 清除 new item 重覆名單 */
                    for ($k = 0; $k < $j; $k++)
                    {
                        if (!isset($new_item[$k]))
                            continue;
                        if ($new_item[$j] == $new_item[$k])
                        {
                            unset($new_item[$j]);
                            break;
                        }
                    }
                }
                if (!isset($new_item[$j]))
                    continue;
                /* 若已存在 old_item 中,代表是沒變動的名單,直接從 new_item 中清除 */
                if ($new_item[$j] == $old_item[$i])
                {
                    $exist = true;
                    unset($new_item[$j]);
                    break;
                }
            }
            /* 若已存在 new_item 中,代表是沒變動的名單,直接從 old_item 中清除,但要記錄到 exist_item 中,以便檢查是否有重覆的名單 */
            if ($exist == true)
            {
                $exist_item[$n++] = $old_item[$i];
                unset($old_item[$i]);
                continue;
            }

            /* 檢查是否在 exist_item 中,若存在代表是重覆名單,也要從 old_item 中清除 */
            for ($k = 0; $k < $n; $k++)
            {
                if ($old_item[$i] == $exist_item[$k])
                {
                    unset($old_item[$i]);
                    break;
                }
            }
        }

        /* 剩餘 old_item 代表是刪除的名單,剩餘 new_item 代表是新增名單,整理名單 list 並回傳 */
        $list["new"] = implode(",", $new_item);
        $list["del"] = implode(",", $old_item);
        return $list;
    }

    /* 2015/3/10 新增,檢查是否有設定瀏覽分享 */
    function chk_browse_share($site_conf, $r_info)
    {
        Global $reg_conf;

        if ((empty($site_conf)) || (empty($r_info)))
            return false;
        $site_id = $site_conf["site_acn"].".".$reg_conf["acn"];
        if (empty($site_conf["owner_info"]))
            $acn = strtolower($site_conf["owner"]);
        else
            list($ssn, $acn, $mail, $sun) = explode(":", strtolower($site_conf["owner_info"]));

        /* 檢查是否有設 member */
        $set_member = false;
        $member = explode(",", $site_conf["member"]);
        $cnt = count($member);
        for ($i = 0; $i < $cnt; $i++)
        {
            $member[$i] = trim(strtolower($member[$i]));
            if (empty($member[$i]))
                continue;
            if (($member[$i] !== $acn) && ($member[$i] !== $mail))
            {
                $set_member = true;
                break;
            }
        }

        /* 檢查瀏覽名單 */
        $b_item = explode(",", $r_info["browse"]);
        $cnt = count($b_item);
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 若瀏覽名單是空的或有 *,site_owner,site_manager,W 都跳過 (因不屬於真正的瀏覽分享名單) */
            if ((empty($b_item[$i])) || ($b_item[$i] == ALL_USER) || ($b_item[$i] == SITE_OWNER) || ($b_item[$i] == SITE_MANAGER) || ($b_item[$i] == SITE_MANAGER))
                continue;
            /* 若瀏覽名單中有 + 或此網站的 site_id,代表分享給網站 member,再檢查是否有設 member,若沒有就跳過 */
            if ((($b_item[$i] == SITE_MEMBER) || ($b_item[$i] == $site_id)) && ($set_member == false))
                continue;
            /* 其他狀況都代表有設定瀏覽分享名單,就直接回傳 true */
            return true;
        }
        /* 找不到瀏覽分享名單就回傳 false */
        return false;
    }

    /* 取得動態 share record */
    function get_dymanic_share_rec($page_dir, $file, $mode="new", $key_value=NULL)
    {
        Global $reg_conf;

        /* 檢查 mode */
        /* 2015/3/16 修改,增加 mode=apply */
        /* 2015/11/26 改,增加 mode=set_member 與 del_member */
        if (($mode !== "set") && ($mode !== "unset") && ($mode !== "new") && ($mode !== "update") && ($mode !== "del") && ($mode !== "apply") && ($mode !== "set_member") && ($mode !== "unset_member"))
            return false;

        /* 檢查檔案是否存在 */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        $file_path = $page_dir.$file;
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 取得目錄權限資料,若有設定 strong_deny,就不可上傳動態,直接回傳 false */
        /* 2015/2/11 修改,已不使用 .nuweb_def,改從 record 檔取回權限設定資料 */
        //$info = read_nuweb_def($page_dir, $file);
        $r_info = get_rec_right_info($file_path);
        if ((isset($r_info["strong_deny"])) && ($r_info["strong_deny"] == YES))
            return false;

        /* 2015/3/10 新增,先取得網站設定,檢查若是個人網站且沒有設定瀏覽分享,就不上傳動態 */
        $site_conf = get_site_conf($file);
        if (($site_conf !== false) && ($site_conf["type"] == SITE_TYPE_PERSONAL) && (chk_browse_share($site_conf, $r_info) == false))
            return false;

        /* 2014/10/29 新增,檢查若是隱藏的檔案或目錄就不上傳動態 */
        /* 2015/9/2 修改,因設定隱藏時會送出 mode=del 的動態訊息,所以 mode=del 還是必須處理 (mode!=del 才不處理)*/
        if ((chk_hidden($file_path) == true) && ($mode !== "del"))
            return false;

        /* 2014/12/23 新增,若是 .files 就不處理 */
        $filename = get_file_name($page_dir, $file);
        if (substr($filename, -6) == ".files")
            return false;

        /* 取出 file 的 url */
        $file_url = str_replace(WEB_ROOT_PATH, "", $file_path);

        /* 2015/2/11 修改,不再使用 share record 資料,直接取 record 資料,不管是否分享共用所有 file 都要送動態訊息 (除了前面已過濾掉的) */
        /* 先取得 record file 資料,整理要上傳的 dymanic record 基本資料 */
        $cs_url = "http://".$reg_conf["acn"].NUCLOUD_DOMAIN;
        $l = strlen($cs_url);
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file === false)
            return false;
        $file_rec = rec2array($rec_file);
        $dymanic_rec["url"] = $cs_url.$file_url;
        $dymanic_rec["view_path"] = get_view_path($file_path);
        $dymanic_rec["mode"] = $mode;
        $dymanic_rec["upload_time"] = date("YmdHis");

        /* 若是 .files 目錄內的檔案就不處理 */
        if (strstr($dymanic_rec["view_path"], ".files/") != false)
            return false;

        /* 將檔案 record 內容也加入,過濾掉不必要欄位 */
        $l = strlen(RIGHT_PREFIX);
        foreach($file_rec[0] as $key => $value)
        {
            /* 將不需要的欄位過濾掉 (site | site_mode | 權限相關欄位) */
            if (($key == GAIS_REC_FIELD) || ($key == "site") || ($key == "site_mode") || (substr($key, 0, $l) == RIGHT_PREFIX))
                continue;
            $dymanic_rec[$key] = trim($value);
        }

        /* 檢查瀏覽名單,若名單中有 * 代表是開放的,若沒有代表不開放,設定到 fun 欄位中 (因動態 record 一定要 fun 欄位,目前已不設定 share 與 use_acn,所以改設定是否開放,可提供訂閱功能使用) */
        if (strstr($r_info["browse"], ALL_USER) !== false)
            $dymanic_rec["fun"] = FUN_PUBLIC;
        else
            $dymanic_rec["fun"] = FUN_PRIVATE;

        /* 若有傳入 key_value,將 key_value 放到 key 欄位中 (但 key_value 不能是 share 或 use_acn,目前已沒有使用此參數值),否則將瀏覽名單放到 key 欄位中 */
        if ((!empty($key_value)) && ($key_value !== "share") && ($key_value !== "use_acn"))
        {
            $dymanic_rec["key"] = $key_value;
            /* 2015/3/6 新增,將瀏覽名單放到 key_list 中,匯入 DB 時可能會用到 */
            $dymanic_rec["key_list"] = $r_info["browse"];
        }
        else
            $dymanic_rec["key"] = $r_info["browse"];
        /* 2015/3/16 修改,若 mode=apply 代表有新申請成員,僅需通知管理者,先將 key 的資料清空 (後面會自動加入管理者) */
        if ($mode == "apply")
            $dymanic_rec["key"] = NULL;
        /* 2015/3/16 新增,mode = set 或 apply 時,若有傳入 key_value,就放到 key_value 欄位中,匯入 DB 時可能會用到 */
        /* 2015/11/26 修改,增加 mode = set_member 時也要處理 */
        if ((($mode == "set") || ($mode == "apply") || ($mode == "set_member")) && (!empty($key_value)))
            $dymanic_rec["key_value"] = $key_value;

        /* key 欄位要強制加入網站 owner 與管理者 */
        if ($site_conf !== false)
        {
            $dymanic_rec["key"] .= ",".$site_conf["owner"].",".$site_conf["manager"];
            /* 整理 key 資料,過濾掉重覆資料 */
            $dymanic_rec["key"] = update_list($dymanic_rec["key"]);
            /* 2015/3/6 新增,若有 key_list 資料,就將 key_list 強制加入網站 owner 與管理者,並過濾掉重覆資料 */
            if (isset($dymanic_rec["key_list"]))
            {
                $dymanic_rec["key_list"] .= ",".$site_conf["owner"].",".$site_conf["manager"];
                $dymanic_rec["key_list"] = update_list($dymanic_rec["key_list"]);
            }
        }

        return $dymanic_rec;
    }

    /* 取得功能目錄動態 share record */
    function get_fun_dymanic_share_rec($page_dir, $file, $mode, $rec, $key_value=NULL)
    {
        Global $reg_conf;

        /* 檢查參數 */
        if (($mode !== "set") && ($mode !== "unset") && ($mode !== "new") && ($mode !== "update") && ($mode !== "del"))
            return false;
        if ((empty($rec)) || (empty($rec["url"])) || (empty($rec["view_path"])))
            return false;

        /* 檢查檔案是否存在 */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        $file_path = $page_dir.$file;
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 取得目錄權限資料,若有設定 strong_deny,就不可上傳動態,直接回傳 false */
        /* 2015/2/11 修改,已不使用 .nuweb_def,改從 record 檔取回權限設定資料 */
        //$info = read_nuweb_def($page_dir, $file);
        $r_info = get_funrec_right_info($rec);
        if ($r_info["strong_deny"] == true)
            return false;

        /* 2015/3/10 新增,先取得網站設定,檢查若是個人網站且沒有設定瀏覽分享,就不上傳動態 */
        /* 2015/4/28 修改,若是 .nuweb_forum 目錄內的資料一律要送動態 */
        $site_conf = get_site_conf($file);
        if (($site_conf !== false) && ($site_conf["type"] == SITE_TYPE_PERSONAL) && (strstr($file, SITE_FORUM_PATH) == false) && (chk_browse_share($site_conf, $r_info) == false))
            return false;

        /* 2014/10/29 新增,檢查若是隱藏的功能目錄就不上傳動態 */
        if (is_dir($file_path))
            $dir_path = $file_path;
        else
        {
            $n = strrpos($file_path, "/");
            $dir_path = substr($file_path, 0, $n);
        }
        if (chk_hidden($dir_path) == true)
            return false;

        /* 取出 file 的 url */
        $file_url = str_replace(WEB_ROOT_PATH, "", $file_path);

        /* 2015/2/11 修改,不再使用 share record 資料,直接取 record 資料,不管是否分享共用所有 file 都要送動態訊息 (除了前面已過濾掉的) */
        /* 先取得 record file 資料,整理要上傳的 dymanic record 基本資料 */
        $cs_url = "http://".$reg_conf["acn"].NUCLOUD_DOMAIN;
        $l = strlen($cs_url);
        $dymanic_rec["url"] = $cs_url.$rec["url"];
        $dymanic_rec["mode"] = $mode;
        $dymanic_rec["upload_time"] = date("YmdHis");

        /* 將檔案 record 內容也加入,過濾掉不必要欄位 */
        $l = strlen(RIGHT_PREFIX);
        foreach($rec as $key => $value)
        {
            /* 將不需要的欄位過濾掉 (site | site_mode | 權限相關欄位) */
            if (($key == GAIS_REC_FIELD) || ($key == "url") || ($key == "site") || ($key == "site_mode") || ($key == "id") || ($key == "subclass") || ($key == "acn") || (substr($key, 0, $l) == RIGHT_PREFIX))
                continue;
            $dymanic_rec[$key] = trim($value);
        }

        /* 檢查瀏覽名單,若名單中有 * 代表是開放的,若沒有代表不開放,設定到 fun 欄位中 (因動態 record 一定要 fun 欄位,目前以不設定 share 與 use_acn,所以改設定是否開放,可提供訂閱功能使用) */
        if (strstr($r_info["browse"], ALL_USER) !== false)
            $dymanic_rec["fun"] = FUN_PUBLIC;
        else
            $dymanic_rec["fun"] = FUN_PRIVATE;

        /* 若有傳入 key_value,將 key_value 放到 key 欄位中 (但 key_value 不能是 share 或 use_acn,目前以沒有使用此參數值),否則將將瀏覽名單放到 key 欄位中 */
        if ((!empty($key_value)) && ($key_value !== "share") && ($key_value !== "use_acn"))
        {
            $dymanic_rec["key"] = $key_value;
            /* 2015/3/6 新增,將瀏覽名單放到 key_list 中,匯入 DB 時可能會用到 */
            $dymanic_rec["key_list"] = $r_info["browse"];
        }
        else
            $dymanic_rec["key"] = $r_info["browse"];

        /* key 欄位要強制加入網站 owner 與管理者 */
        if ($site_conf !== false)
        {
            $dymanic_rec["key"] .= ",".$site_conf["owner"].",".$site_conf["manager"];
            /* 整理 key 資料,過濾掉重覆資料 */
            $dymanic_rec["key"] = update_list($dymanic_rec["key"]);
            /* 2015/3/6 新增,若有 key_list 資料,就將 key_list 強制加入網站 owner 與管理者,並過濾掉重覆資料 */
            if (isset($dymanic_rec["key_list"]))
            {
                $dymanic_rec["key_list"] .= ",".$site_conf["owner"].",".$site_conf["manager"];
                $dymanic_rec["key_list"] = update_list($dymanic_rec["key_list"]);
            }
        }
        return $dymanic_rec;
    }

    /* 檢查並上傳動態 share record */
    function upload_dymanic_share_rec($page_dir, $file, $mode="new", $rec=NULL, $key_value=NULL, $update_upload_time=true)
    {
        Global $reg_conf, $wns_ser, $wns_port, $fun_set_conf;
		$bNotice = true;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        $file_path = $page_dir.$file;

        /* 2014/9/1 修改,若 rec 內容不是空的,代表是功能目錄的 record,改用功能目錄方式處理 */
        /* 2015/7/21 修改,若 mode = log_report 代表要送出當天的 log report 動態給系統管理者 */
        if ($mode == "log_report")
        {
			$bNotice = false;
			
            if (empty($rec))
                return false;
            $mode = "new";
            $dymanic_rec = $rec;
        }
        else if (!empty($rec))
        {
            set_fun_event_rec($mode, $rec);
            if (($dymanic_rec = get_fun_dymanic_share_rec($page_dir, $file, $mode, $rec, $key_value)) == false)
                return false;
        }
        else
        {
            /* 2014/5/8 新增,先記錄到 event record 中 */
            set_event_rec($page_dir, $file, $mode);

            /* 取得動態 record */
            if (($dymanic_rec = get_dymanic_share_rec($page_dir, $file, $mode, $key_value)) == false)
                return false;
        }

        /* 整理 dymanic record 內容 */
        $rec_content = REC_START.REC_BEGIN_PATTERN;
        foreach($dymanic_rec as $key => $value)
        {
            /* 2014/12/19 修改,過濾 content 與 key_list 欄位資料 (原動態 record 不需要 content 與 key_list 欄位資料) */
            /* 2015/3/16 修改,增加過濾 key_value 欄位 */
            if (($key == GAIS_REC_FIELD) || ($key == "content") || ($key == "key_list") || ($key == "key_value"))
                continue;
            $value = trim($value);
            $rec_content .= "@$key:$value\n";
        }

        /* 新增到 dymanic share record 中 */
        $fp = fopen(DYMANIC_SHARE_REC, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 加到 dymainc index 中 */
        rput_content(DYMANIC_INDEX_DIR, $rec_content);

        /* 2015/12/14 新增,若設定關閉動態,就不將動態 record 送出 (也就是不再處理下列動作) */
        if ((isset($fun_set_conf["message"])) && ($fun_set_conf["message"] == NO))
            return false;

        /* 將 dymanic share record 資料傳送到 Server 進行 dymanic share record 即時更新 */
        /* 2015/4/20 修改,改用 wns_ser 與 wns_port */
        //if (($fp = fsockopen(SHARE_SERVER, 80, $errno, $errstr)) != false)
        if (($fp = fsockopen($wns_ser, $wns_port, $errno, $errstr)) != false)
        {
            /* 設定傳送到 Server 的參數 */
            $arg = "ssn=".$reg_conf["ssn"]."&sca=".$reg_conf["sca"]."&acn=".$reg_conf["acn"];
            $arg .= "&rec_content=".rawurlencode($rec_content);

            $head = "POST ".DYMANIC_SHARE_REC_PROG." HTTP/1.0\r\n";
            $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: ". strlen($arg) . "\r\n\r\n";
            $head .= "$arg\r\n";

            fputs($fp, $head);
            fclose($fp);
        }

        /* 將動態資料送到 DB */
        /* 2014/12/23 修改,若 update_upload_time = false,代表不更新 upload_time,直接將 upload_time 欄位移除 */
        if ($update_upload_time == false)
            unset($dymanic_rec["upload_time"]);
        message2db($file_path, $dymanic_rec);

        /* 2015/11/23 取消 GCM */
		// whee google cloud message
        /*
        if (($fp = fsockopen("localhost", 80, $errno, $errstr)) != false)
        {
            $arg = "mode=send"
					."&nu_code=".$_COOKIE["nu_code"]
					."&rec=".rawurlencode($rec_content);

            $head = "POST /tools/GCM/gcm_api_tools.php HTTP/1.0\r\n";
            $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: ". strlen($arg) . "\r\n\r\n";
            $head .= "$arg\r\n";

            fputs($fp, $head);
            fclose($fp);
        }
        */
		
		// whee notice message
        if ($bNotice && ($fp = fsockopen("localhost", 80, $errno, $errstr)) != false)
        {
            $arg = "mode=send"
					."&nu_code=".$_COOKIE["nu_code"]
					."&rec=".rawurlencode(json_encode($dymanic_rec));

            $head = "POST /tools/api_notice.php HTTP/1.0\r\n";
            $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: ". strlen($arg) . "\r\n\r\n";
            $head .= "$arg\r\n";

            fputs($fp, $head);
            fclose($fp);
        }
    }

    /* 紀錄分享 log */
    function set_share_log($file_path, $fun, $fun_value, $rec="", $time="", $content="")
    {
        Global $uacn;

        /* 檢查 fun 參數是否正確 */
        if (($fun != "public") && ($fun != "share") && ($fun != "use_acn"))
            return false;

        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 本功能僅提供網站管理員使用 */
        if (chk_site_manager($file_path) === false)
            return false;

        /* 檢查是否為子網站 file_path (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($file_path, 0, $l) !== $site_path) || (strlen($file_path) <= $l))
            return false;

        /* 若沒傳入 rec 就先取得 record 資料 */
        if (empty($rec))
        {
            /* 找出 record file */
            $rec_file = get_file_rec_path($file_path);
            if ($rec_file === false)
                return false;

            /* 取出 record file 資料 */
            $rec = rec2array($rec_file);
        }

        /* 從 record 中取得 filename 與 type */
        $filename = $rec[0]["filename"];
        $type = $rec[0]["type"];

        /* fun 若是 share 時,fun_value 必須是 share_code */
        /* 2015/8/11 新增 share_end 參數 */
        $share_end = NULL;
        if ($fun == "share")
        {
            $share_code = "";
            if ((isset($rec[0]["share_code"])) && (!empty($rec[0]["share_code"])))
                $share_code = $rec[0]["share_code"];
            if ($share_code != $fun_value)
                return false;
            if ((isset($rec[0]["share_end"])) && (!empty($rec[0]["share_end"])))
                $share_end = $rec[0]["share_end"];
        }

        /* 取得網站的 share log 位置 */
        $path = explode("/", substr($file_path, $l));
        $site_acn = $path[0];
        $share_log = $site_path.$site_acn."/".NUWEB_SHARE_LOG;

        /* 若沒傳入 time 參數,就用目前時間 */
        if (empty($time))
            $time = date("YmdHis");

        /* 紀錄 share log */
        /* 2015/8/11 新增記錄 share_end */
        $fp = fopen($share_log, "a");
        flock($fp, LOCK_EX);
        $log_data = "$time\t$uacn\t$fun\t$file_path\t$filename\t$type\t$fun_value\t$content\t$share_end\n";
        fputs($fp, $log_data);
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 更新 share record (包括更新 Local & Server 端 share record) */
        update_share_rec($log_data);
        return true;
    }

    /* 取得分享 log */
    function get_share_log($site_acn, $fun="all", $sort_by="", $sort_mode="A", $get_mode="")
    {
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $site_acn_path = $site_path.$site_acn;
        if (($fun !== "public") && ($fun !== "share") && ($fun !== "use_acn"))
            $fun = "all";

        /* 本功能僅提供本地端呼叫 (必須有設定 PWD 且沒設定 REMOTE_ADDR)或網站管理員使用 */
        if (((isset($_SERVER["REMOTE_ADDR"])) || (!isset($_SERVER["PWD"]))) && (chk_site_manager($site_acn_path) === false))
            return false;

        /* 設定 sort 預設值 */
        if (empty($sort_by))
            $sort_by = "time";
        if ($sort_mode !== "D")
            $sort_mode = "A";

        /* 取得網站的 share log 位置 */
        $share_log = $site_acn_path."/".NUWEB_SHARE_LOG;
        if (!file_exists($share_log))
            return NULL;

        /* 取出 share log 資料 */
        $buf = @file($share_log);
        $cnt = count($buf);
        $n = 0;
        $log_list = array();
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 2015/8/11 新增 share_end 參數 */
            list($time, $acn, $l_fun, $path, $name, $type, $value, $content, $share_end) = explode("\t", trim($buf[$i]));

            /* 若 path 已不存在就跳過不處理 */
            if (!file_exists($path))
                continue;

            /* 2015/8/11 新增,檢查若有設定 share_end 且分享已過期就設定 end_flag 為 true */
            $now_time = (int)date("YmdHis");
            $end_flag = false;
            if ((!empty($share_end)) && (chk_share_status($share_end) == false))
                $end_flag = true;
 
            if (substr($path, -1) == "/")
                $path = substr($path, 0, -1);
            if (($fun === "all") || ($fun === $l_fun))
            {
                /* 檢查此 path & fun 是否已有紀錄,若有必須進行處理 */
                $process = true;
                for ($j = 0; $j < $n; $j++)
                {
                    if (($log_list[$j]["path"] == $path) && ($log_list[$j]["fun"] == $l_fun))
                    {
                        /* 若 content 為空的,代表該筆紀錄已重覆,若 content 有內容,則需整合在一起 */
                        /* 2015/8/11 修改,若 end_flag 為 true,代表分享已過期,就直接 unset 較早的紀錄 */
                        if ($end_flag == true)
                            unset($log_list[$j]);
                        else if (empty($content))
                        {
                            /* 若 value 與之前紀錄相同,此筆紀錄不必處理 */
                            if ($log_list[$j]["value"] == $value)
                            {
                                $process = false;
                                break;
                            }
                            /* 其餘紀錄重覆狀況就直接 unset 較早的紀錄 */
                            unset($log_list[$j]);
                        }
                        else
                            $log_list[$j]["content"] .= "$time\t$acn\t$content\n";
                        break;
                    }
                }
                /* 若設定不必處理,就直接跳過 */
                /* 2015/8/11 修改,若 end_flag 為 true,代表分享已過期,也跳過 */
                if (($process == false) || ($end_flag == true))
                    continue;

                /* 若 value 為空的或是 OFF 代表已關閉該功能,就不必輸出 (因 value 是字串,所以 OFF 必須轉成字串才能比對) */
                if (($value === strval(OFF)) || (empty($value)))
                    continue;

                /* 若 content 為空的,代表是一筆新紀錄,將紀錄資料放入 log_list 中 */
                if (empty($content))
                {
                    /* 取得顯示用的路徑 */
                    $view_path = get_view_path($path);

                    $log_list[$n]["time"] = $time;
                    $log_list[$n]["acn"] = $acn;
                    $log_list[$n]["fun"] = $l_fun;
                    $log_list[$n]["path"] = $path;
                    /* 2013/4/25,因 name 有可能 rename,所以 name 必須即時抓取,不再從 share_log 取得 */
                    $log_list[$n]["name"] = get_file_name($site_path, str_replace($site_path, "", $path));
                    $log_list[$n]["view_path"] = $view_path;
                    $log_list[$n]["type"] = $type;
                    $log_list[$n]["value"] = $value;
                    $log_list[$n]["content"] = $content;
                    $n++;
                    continue;
                }
            }
        }

        /* 2013/10/4 修改,當 get_mode 是 all 時就不必進行過濾 */
        if ($get_mode !== "all")
        {
            /* 要先過濾 fun 為 share,但是 content 沒有內容的資料 (也就是已 set_share,但沒有 set_share_log) */
            for ($i = 0; $i < $n; $i++)
            {
                if (($log_list[$i]["fun"] == "share") && (empty($log_list[$i]["content"])))
                    unset($log_list[$i]);
            }
        }

        /* sort 資料再輸出 */
        sort_array($log_list, $sort_by, $sort_mode);
        return $log_list;
    }

    /* 取得預設的 right_mode */
    function get_def_right_mode($file_path)
    {
        /* 檢查是否為子網站 file_path (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($file_path, 0, $l) !== $site_path) || (strlen($file_path) <= $l))
            return false;
        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 檢查是否為網站的目錄 (網站目錄的預設權限與其他目錄不同) */
        $path = substr($file_path, $l);
        if (strrpos($path, "/") == false)
            $right_mode = DEF_SITE_RIGHT_MODE;
        else if (is_dir($file_path))
            $right_mode = DEF_DIR_RIGHT_MODE;
        else
            $right_mode = DEF_FILE_RIGHT_MODE;
        return $right_mode;
    }

    /* 檢查是否為共用者 */
    /* 2015/1/22 修改,取消 use_acn 參數,改為 get_mode,當 get_mode=true 時,代表要回傳 right_mode 欄位資料 */
    function chk_use_acn($file_path, $get_mode=false, &$chk_parent=true, $is_site=NULL)
    {
        Global $login_user;

        /* 檢查是否為子網站 file_path (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($file_path, 0, $l) !== $site_path) || (strlen($file_path) <= $l))
            return false;

        /* 取得登入者資料 */
        if ((empty($login_user)) || ($login_user === false))
            return false;

        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 參數 is_site 為 NULL 時 (代表是首次 call chk_use_acn),要檢查是不是網站 (第一層目錄) */
        if ($is_site === NULL)
        {
            $path = substr($file_path, $l);
            if (strrpos($path, "/") == false)
                $is_site = true;
            else
                $is_site = false;
        }

        /* 找出 record file */
        $rec_file = get_file_rec_path($file_path);
        $use_acn = NULL;
        $right_mode = NULL;
        if ($rec_file !== false)
        {
            /* 取出 record file 資料 */
            $rec = rec2array($rec_file);

            /* 找出設定的 use_acn 與 right_mode */
            if ((isset($rec[0]["use_acn"])) && (!empty($rec[0]["use_acn"])))
            {
                $use_acn = $rec[0]["use_acn"];
                if (isset($rec[0]["right_mode"]))
                    $right_mode = $rec[0]["right_mode"];
                else if ($is_site == true)
                    $right_mode = DEF_SITE_RIGHT_MODE;
                else if (is_dir($file_path))
                    $right_mode = DEF_DIR_RIGHT_MODE;
                else
                    $right_mode = DEF_FILE_RIGHT_MODE;
            }
        }

        /* 檢查是否為共用者 */
        /* 2014/6/26 新增 none_parent 參數,代表不再向上層檢查 */
        $none_parent = false;
        $ret_value = ($get_mode == true) ? $right_mode : true;
        $acn_list = explode(",", $use_acn);
        $cnt = count($acn_list);
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 只要 acn 或 mail 與 acn_list 內的設定一樣就代表是共用者 */
            $acn_item = trim($acn_list[$i]);
            if (empty($acn_item))
                continue;
            /* 2014/6/26 新增,若 acn_item = NONE_PARENT 代表不再向上層檢查 */
            if ($acn_item == "NONE_PARENT")
                $none_parent = true;
            if (($login_user["acn"] == $acn_item) || ($login_user["mail"] == $acn_item))
                return $ret_value;
            /* 檢查 acn_item 若沒有 '@' 但有 '.' 代表不是 E-mail 而是社群帳號,就取回社群成員帳號 list 進行檢查 */
            if ((strstr($acn_item, "@") === false) && (strstr($acn_item, ".") !== false))
            {
                $member = get_cs_site_member($acn_item);
                if ($member == false)
                    continue;
                $member_list = explode(",", $member);
                $m_cnt = count($member_list);
                for ($j = 0; $j < $m_cnt; $j++)
                {
                    $member_item = trim($member_list[$j]);
                    if (empty($member_item))
                        continue;
                    if (($login_user["acn"] == $member_item) || ($login_user["mail"] == $member_item))
                        return $ret_value;
                }
            }
        }

        /* 2014/6/26 新增,若 none_parent 為 true 就不再向上層檢查 */
        if ($none_parent == true)
            return false;

        /* 2015/1/23 新增,若是 get_mode 為 true 且 use_acn 不是空的,就設定 chk_parent 為 false (不再向上層檢查,但檔案還是會再檢查目錄是否為共用) */
        if (($get_mode == true) && (!empty($use_acn)))
            $chk_parent = false;
        /* 2014/11/5 新增,若 chk_parent 為 false 且 file_path 為目錄也不再向上層檢查 (若是檔案還是會再檢查目錄是否為共用) */
        if (($chk_parent == false) && (is_dir($file_path)))
            return false;

        /* 向上層檢查是否為共用者 */
        $path = substr($file_path, $l);
        while (($n = strrpos($path, "/")) != false)
        {
            /* 取得上層目錄位置 */
            $path = substr($path, 0, $n);

            /* 檢查上層若有設定共用就回傳 */
            if (($ret_value = chk_use_acn($site_path.$path, $get_mode, $chk_parent, $is_site)) != false)
                return $ret_value;

            /* 2014/11/5 新增,若 chk_parent 為 false 就直接回傳 false 不再向上層檢查 */
            if ($chk_parent == false)
                return false;
        }

        return false;
    }

    /* 設定共用欄位資料 */
    function set_use_acn($file_path, $acn_list="")
    {
        /* 若 acn_list 傳入 OFF 也代表要關閉共用,就直接將 acn_list 清空 */
        if ($acn_list === strval(OFF))
            $acn_list = "";

        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 本功能僅提供網站管理員使用 */
        if (chk_site_manager($file_path) === false)
            return false;

        /* 找出 record file */
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file === false)
            return false;

        /* 取出 record file 資料 */
        $rec = rec2array($rec_file);

        /* 若沒設定 use_acn 欄位,且傳入的 acn_list 為空的就不必處理 */
        if ((!isset($rec[0]["use_acn"])) && (empty($acn_list)))
            return true;

        /* 將 acn_list 資料設定到 use_acn 欄位中,並寫回 record file (若資料沒變更就不必處理) */
        $use_acn = implode(",", $acn_list);
        if ((isset($rec[0]["use_acn"])) && ($rec[0]["use_acn"] == $use_acn))
            return true;
        $rec[0]["use_acn"] = $use_acn;
        $rec[0]["use_date"] = date("YmdHis");
        write_rec_file($rec_file, $rec[0]);
        set_share_log($file_path, "use_acn", $use_acn, $rec, $rec[0]["use_date"]);
        return true;
    }

    /* 將 list 轉成 array */
    function list2array($list, $delimiter=",")
    {
        /* 2016/1/11 新增,若 list 是空的,就回傳 NULL */
        if (empty($list))
            return NULL;

        $item = explode($delimiter, $list);
        $cnt = count($item);
        $n = 0;
        for ($i = 0;$i < $cnt; $i++)
        {
            $item[$i] = trim($item[$i]);
            if (!empty($item[$i]))
                $output[$n++] = $item[$i];
        }
        return $output;
    }

    /* 將日期時間 (YYYYMMDDhhmmss) 格式轉換成 mktime 格式 */
    function datetime_mktime($datetime)
    {
        $year = substr($datetime, 0, 4);
        $mon = substr($datetime, 4, 2);
        $day = substr($datetime, 6, 2);
        $hour = substr($datetime, 8, 2);
        $min = substr($datetime, 10, 2);
        $sec = substr($datetime, 12, 2);
        return mktime($hour, $min, $sec, $mon, $day, $year);
    }

    /* sort 陣列資料 */
    function sort_array(&$data, $sort_key, $sort_mode="A")
    {
        if ((empty($data)) || (!is_array($data)))
            return false;
        $sorter = array();
        $ret = array();
        reset($data);
        foreach ($data as $key => $value)
            $sorter[$key] = $value[$sort_key];
        if ($sort_mode == "D")
            arsort($sorter);
        else
            asort($sorter);
        foreach ($sorter as $key => $value)
            array_push($ret, $data[$key]);
        $data = $ret;
    }

    /* 取得網站 list */
    function get_sub_site_list()
    {
        $slist = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".SITE_LIST;
        $results = array();
        if (file_exists($slist))
        {
            $site_list = @file($slist);
            $cnt = count($site_list);
            for ($i = 0; $i < $cnt; $i++)
            {
                list($s_acn, $s_name, $s_owner, $s_time, $s_status) = explode("\t", StripSlashes(trim($site_list[$i])));
                $s_acn = strtolower($s_acn);
                $s_owner = strtolower($s_owner);
                array_push($results, array("acn"=>$s_acn, "name"=>$s_name, "owner"=>$s_owner, "time"=>$s_time, "status"=>$s_status));
            }
        }
        return $results;
    }

    /* 從 site.list 取得會員名單 */
    /* 2015/2/11 修改,新增 key 參數 */
    //function get_member_list($key=NULL)
    //{
    //    $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
    //    $slist = get_sub_site_list();
    //    $cnt = count($slist);
    //    $n = 0;
    //    $member = NULL;
    //    for ($i = 0; $i < $cnt; $i++)
    //    {
    //        /* 若有傳入 key 參數,就檢查 acn / name / owner 等欄位是否有符合的資料,若都不符合就跳過 */
    //        if ((!empty($key)) && (strstr($slist[$i]["acn"], $key) == false) && (strstr($slist[$i]["name"], $key) == false) && (strstr($slist[$i]["owner"], $key) == false))
    //            continue;

    //        $exist = false;
    //        for ($j = 0; $j < $n; $j++)
    //        {
    //            if ($member[$j]["acn"] == $slist[$i]["owner"])
    //            {
    //                $exist = true;
    //                break;
    //            }
    //        }
    //        if ($exist !== true)
    //        {
    //            $member[$n]["acn"] = $slist[$i]["owner"];

    //            $conf_file = $site_path.$slist[$i]["acn"]."/".NUWEB_CONF;
    //            $site_conf = read_conf($conf_file);
    //            if (isset($site_conf["owner_info"]))
    //            {
    //                list($ssn, $acn, $mail, $name) = explode(":", $site_conf["owner_info"]);
    //                $member[$n]["ssn"] = $ssn;
    //                $member[$n]["mail"] = $mail;
    //                $member[$n]["name"] = $name;
    //            }
    //            else
    //            {
    //                $user = get_user_data($slist[$i]["owner"]);
    //                if ($user !== false)
    //                {
    //                    $member[$n]["ssn"] = $user["ssn"];
    //                    $member[$n]["mail"] = $user["mail"];
    //                    $member[$n]["name"] = $user["sun"];
    //                }
    //            }
    //            /* 若沒有 mail 或 name 內容就跳過 (應該是已被刪除的帳號) */
    //            if ((empty($member[$n]["mail"])) || (empty($member[$n]["name"])))
    //                continue;
    //            $n++;
    //        }
    //    }
    //    return $member;
    //}
    /* 2015/4/16 修改,改取 Site index 資料 */
    function get_member_list($key=NULL)
    {
        /* 檢查 Site index 是否存在 */
        $index_file = SITE_INDEX_DIR."current";
        if (!file_exists($index_file))
            return false;

        /* 取出所有 site 資料 */
        $rBegin = str_replace("\n", "\\n", REC_START.REC_BEGIN_PATTERN);
        if (!empty($key))
            $select_arg = "-select \"@name:$key;@site_acn:$key;@owner_info:$key\"";
        else
            $select_arg = "";
        $cmd = SEARCH_BIN_DIR."dbman -recbeg \"$rBegin\" -flag \"@_f:Normal\" $select_arg -sort $index_file";
        $fp = popen($cmd, "r");
        $rec_start = false;
        $site_list = NULL;
        $n = 0;
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            $buf = trim($buf)."\n";
            if ($buf == "\n")
                continue;

            /* 判斷是否有新的 Record (Record 的啟始為 REC_START,第二行一開始必須是 REC_FIELD_START) */
            if ($buf == REC_START)
            {
                $rec_start = true;
                continue;
            }
            if (($rec_start == true) && ($buf[0] == REC_FIELD_START))
            {
                $owner = NULL;
                $owner_info = NULL;
                $cs = NULL;
            }
            $rec_start = false;

            /* 檢查是否為 owner | owner_info | cs 欄位,若是就取出內容,其餘的都跳過 */
            if (strstr($buf, "@owner:")  === $buf)
                $owner = strtolower(trim(substr($buf, 7)));
            else if (strstr($buf, "@owner_info:")  === $buf)
                $owner_info = trim(substr($buf, 12));
            else if (strstr($buf, "@cs:") === $buf)
                $cs = strtolower(trim(substr($buf, 4)));
            else
                continue;

            /* 整理 member list 資料 */
            if (($owner !== NULL) && ($owner_info !== NULL) && ($cs !== NULL))
            {
                $exist = false;
                for ($j = 0; $j < $n; $j++)
                {
                    if ($member_list[$j]["acn"] == $owner)
                    {
                        $exist = true;
                        break;
                    }
                }
                if ($exist == true)
                    continue;
                list($ssn, $acn, $mail, $name) = explode(":", $owner_info);
                if ((empty($mail)) || (empty($name)))
                    continue;
                $member_list[$n]["acn"] = $owner;
                $member_list[$n]["ssn"] = $ssn;
                $member_list[$n]["mail"] = $mail;
                $member_list[$n]["name"] = $name;
                $member_list[$n]["cs"] = $cs;
                $n++;
            }
        }
        pclose($fp);
        return $member_list;
    }

    /* 取得 user 管理的網站 */
    function get_user_site($acn="", $get_owner=false, $mail="")
    {
        Global $login_user;

        /* 若沒傳入 acn 就直接使用登入者的 acn 與 mail */
        if (empty($acn))
        {
            $acn = $login_user["acn"];
            $mail = $login_user["mail"];
        }
        else
        {
            /* 有傳入 acn 且沒有傳入 mail 就取得 user 的 mail */
            if (empty($mail))
            {
                $user = get_user_data($acn);
                if (!empty($user["mail"]))
                    $mail = $user["mail"];
            }
        }
        /* 如果沒有 acn 就不處理 */
        if (empty($acn))
            return false;
        $acn = strtolower($acn);

        /* 如果沒有 site.list 或 site_manager.list 也不處理 */
        $slist = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".SITE_LIST;
        $mlist = SITE_MANAGER_LIST;
        if ((!file_exists($slist)) || (!file_exists($mlist)))
            return false;

        /* 取得 site.list 與 site_manager.list 資料 */
        $site_list = @file($slist);
        $site_manager_list = @file($mlist);
        $cnt = count($site_list);
        $user_site = array();
        for ($i = 0; $i < $cnt; $i++)
        {
            list($s_acn, $s_name, $s_owner, $s_time, $s_status) = explode("\t", StripSlashes(trim($site_list[$i])));
            /* 2014/1/24 新增,若 mode=owner 代表只找由 user 為 owner 的網站,直接由 site.list 取得即可 */
            if ($get_owner == true)
            {
                $s_acn = strtolower($s_acn);
                $s_owner = strtolower($s_owner);
                /* 取出所有網站,不過濾已啟用,將 status 放入回傳 array 中 */
                if (($acn == $s_owner) || ($mail == $s_owner))
                    array_push($user_site, array("acn"=>$s_acn, "name"=>$s_name, "status"=>$s_status));
                continue;
            }

            /* 只處理已啟用的網站 */
            if ($s_status == SITE_STATUS_ALLOW)
                $site_name[$s_acn] = $s_name;
        }
        /* 2014/1/24 新增,若 mode=owner 直接由此回傳取得的資料 */
        if ($get_owner == true)
            return $user_site;

        /* 找出 user 所管理的網站 */
        $cnt = count($site_manager_list);
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 若在 site_name 中沒有記錄網站名稱,就跳過不處理(應該是尚未啟用網站) */
            list($site_acn, $manager_list) = explode("\t", strtolower(trim($site_manager_list[$i])));
            if (empty($site_name[$site_acn]))
                continue;
            $name = $site_name[$site_acn];
            $m_acn = explode(",", $manager_list);
            $m_cnt = count($m_acn);

            /* 檢查是否為網站的 owner 或 manager */
            for ($j = 0; $j < $m_cnt; $j++)
            {
                if (($acn == $m_acn[$j]) || ($mail == $m_acn[$j]))
                {
                    array_push($user_site, array("acn"=>$site_acn, "name"=>$name));
                    break;
                }
                /* 2014/1/20 新增,若 mode=owner 代表只找由 user 為 owner 的網站,第一筆就是 owner,後面的就不必比對 */
                if ($get_owner == true)
                    break;
            }
        }

        return $user_site;
    }

    /* 2015/9/24 新增,取得 user 的 site_id (site_acn.cs_acn) */
    function get_user_site_id($acn="", $mail="")
    {
        Global $login_user, $reg_conf, $group_mode;

        /* 若沒傳入 acn 就直接使用登入者的 acn 與 mail */
        if (empty($acn))
        {
            $acn = $login_user["acn"];
            $mail = $login_user["mail"];
        }
        else
        {
            /* 有傳入 acn 且沒有傳入 mail 就取得 user 的 mail */
            if (empty($mail))
            {
                $user = get_user_data($acn);
                if (!empty($user["mail"]))
                    $mail = $user["mail"];
            }
        }
        /* 如果沒有 acn 就不處理 */
        if (empty($acn))
            return false;
        $acn = strtolower($acn);
        $cs = $reg_conf["acn"];

        /* 若 group_mode 為 GROUP_NONE 就使用 get_user_site 取得 user 網站資料 */
        if ($group_mode == GROUP_NONE)
        {
            $user_site = get_user_site($acn, true, $mail);
            $cnt = count($user_site);
            $user_site_acn = NULL;
            if ($cnt > 0)
            {
                /* 先預設第一個網站 */
                $user_site_acn = $user_site[0]["acn"];
                for ($i = 0; $i < $cnt; $i++)
                {
                    /* 尋找是否有同名網站,若有就選取同名網站 */
                    if ($user_site[$i]["acn"] == $acn)
                    {
                        $user_site_acn = $user_site[$i]["acn"];
                        break;
                    }
                }
            }
            if (!empty($user_site_acn))
                return $user_site_acn.".".$reg_conf["acn"];
            return false;
        }

        /* group_mode 不是 GROUP_NONE 時 (代表是 Group 架構內的 CS),就改用 group_get_site_list 取得 user 網站資料 */
        $s_list = group_get_site_list(false, true);
        $s_cnt = count($s_list);
        $site_id = NULL;
        for ($i = 0; $i < $s_cnt ; $i++)
        {
            /* 網站 owner 不是 acn 就跳過 */
            if (($s_list[$i]["owner"] !== $acn) && ($s_list[$i]["owner"] !== $mail))
                continue;

            /* 若網站的 site_acn 與 acn 相同,就直接回傳此網站的 site_id */
            if ($s_list[$i]["site_acn"] == $acn)
                return $s_list[$i]["site_acn"].".".$s_list[$i]["cs"];

            /* 先設定取得的第一個網站 site_id,若沒找到與 acn 同名的網站,就回傳第一個網站 site_id */
            if (empty($site_id))
                $site_id = $s_list[$i]["site_acn"].".".$s_list[$i]["cs"];
        }
        if (!empty($site_id))
            return $site_id;
        return false;
    }

    /* 儲存設定檔 */
    /* 2015/9/17 修改,增加 backup 若為 true,代表要備份舊的設定檔 */
    function write_conf($conf_file, $conf_data, $backup=false)
    {
        /* 若沒有 conf_data 資料就不處理 */
        $cnt = count($conf_data);
        if ($cnt == 0)
            return false;
        clearstatcache();

        /* 2015/9/17 新增,若有設定 backup=true 就先備份舊設定檔,最多保留 CONF_BACKUP_CNT 筆 (目前為 5 筆) */
        if ($backup === true)
        {
            /* 將舊的備份檔序號重新調整,若序號已到達 CONF_BACKUP_CNT 就將最舊的刪除 */
            for ($i = CONF_BACKUP_CNT; $i > 0; $i--)
            {
                $bak_file = $conf_file.".bak$i";
                $last_bak_file = $conf_file.".bak".($i-1);
                if (file_exists($bak_file))
                    unlink($bak_file);
                if (file_exists($last_bak_file))
                    rename($last_bak_file, $bak_file);
            }
            /* 將目前的設定檔,搬移到第一個備份檔 (設定檔必須有內容才進行備份) */
            if ((file_exists($conf_file)) && (real_filesize($conf_file) > 0))
                rename($conf_file, $bak_file);
        }

        /* 將 con_data 的資料寫入 conf_file 中 */
        $fp = fopen($conf_file, "w");
        flock($fp, LOCK_EX);
        foreach($conf_data as $key => $value)
            fputs($fp, "$key=".str_replace("\n", "<br>", $value)."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

    /* 讀取設定檔 */
    function read_conf($conf_file)
    {
        if (file_exists($conf_file) == false)
            return false;

        $conf_list = @file($conf_file);
        $cnt = count($conf_list);
        for ($i = 0; $i < $cnt; $i++)
        {
            list($key, $value) = explode("=", trim($conf_list[$i]), 2);
            $key = trim($key);
            $value = trim($value);
            $conf_data[$key] = str_replace("<br>", "\n", StripSlashes($value));
        }
/*
        clearstatcache();
        $fp = fopen($conf_file, "r");
        flock($fp, LOCK_SH);
        while($buf = @fgets($fp, MAX_BUFFER_LEN))
        {
            $conf_list = $buf;
            while((strlen($buf) >= MAX_BUFFER_LEN-1) && (substr($buf, -1) !== "\n"))
            {
                $buf = @fgets($fp, MAX_BUFFER_LEN);
                $conf_list .= $buf;
            }
            list($key, $value) = explode("=", trim($conf_list), 2);
            $key = trim($key);
            $value = trim($value);
            $conf_data[$key] = str_replace("<br>", "\n", StripSlashes($value));
        }
        flock($fp, LOCK_UN);
        fclose($fp);
*/

        return $conf_data;
    }

    /* 用編碼程式 (AuthEncode.exe) 進行字串編碼 */
    function auth_encode($str)
    {
        /* 編碼字串中不可有 " 符號 */
        if (strstr($str, "\""))
            return false;

        /* 執行 AuthEncode.exe 進行編碼 */
        $cmd = AUTH_ENCODE_PROG."\"$str\"";
        $fp = popen($cmd, "r");
        $code = NULL;
        while($buf = fgets($fp, MAX_BUFFER_LEN))
            $code .= trim($buf);
        pclose($fp);
        return $code;
    }

    /* 用解碼程式 (AuthDecode.exe) 進行解碼 */
    function auth_decode($code)
    {
        /* 檢查 code 格式是否正確 */
        if (!preg_match("/^[0-9a-z]+$/i", $code))
            return false;

        /* 執行 AuthDecode.exe 進行解碼 */
        $cmd = AUTH_DECODE_PROG.$code;
        $content = NULL;
        $fp = popen($cmd, "r");
        while($buf = fgets($fp, MAX_BUFFER_LEN))
            $content .= trim($buf);
        pclose($fp);
        return $content;
    }

    /* 檢查 Login 的 cookie 是否正確 */
    function check_login_cookie()
    {
        $nu_code = $_COOKIE["nu_code"];

        /* 若沒有登入的 nu_code 直接回傳 false */
        /* 2014/2/14 修改,若沒有 nu_code 的 cookie,但有 nu_code 的 session 就重設 nu_code cookie */
        if (empty($nu_code))
        {
            session_start();
            if (!isset($_SESSION["nu_code"]))
                return false;
            $nu_code = $_SESSION["nu_code"];
            session_write_close();
            /* 2015/3/26 修改,nu_code cookie 有效時間設為預設的 7 天 */
            setcookie("nu_code", $nu_code, time() + DEF_LOGIN_TIME, "/", COOKIE_DOMAIN);
            $_COOKIE["nu_code"] = $nu_code;
        }

        $content = auth_decode($nu_code);
        if ($content == false)
            return false;

        //list($ssn, $acn, $mail, $sun) = explode(":", $content);
        list($ssn, $acn, $mail, $sun, $user_lang) = explode(":", $content);
        if ((empty($ssn)) || (empty($acn)) || (empty($mail)) || (empty($sun)) || (is_numeric($ssn) != true))
            return false;
        return true;
    }

    /* 由 user 資料整理出 nu_code */
    function get_nu_code($ssn, $acn, $mail, $sun)
    {
        Global $lang;

        /* 2014/6/17 新增,檢查 sun 若有 " 符號,就將 sun 內容改成 acn */
        if (strstr($sun, "\"") != false)
            $sun = $acn;

        /* 2014/4/15 新增,從 wns 取回 user profile 資料,若 user 有設定個人預設語系(user_lang),就將語系 (lang) 設為 user_lang */
        $code = auth_encode("$ssn:$acn:$mail:$sun");
        $buf = get_user_profile($code);
        if (strstr($buf, "Error") == false)
        {
            $profile = json_decode($buf, true);
            if ((isset($profile["LN"])) && (!empty($profile["LN"])))
                $user_lang = $profile["LN"];
        }
        /* 2014/5/31 修改,若有設定 user_lang 且此語系的列表檔存在(代表有支援此語系),就將語系 (lang) 設為 user_lang */
        if ((!empty($user_lang)) && (file_exists(LANG_LIST_FILE.".$user_lang")))
            $lang = $user_lang;

        /* 產生 nu_code */
        $nu_code = auth_encode("$ssn:$acn:$mail:$sun:$user_lang");
        return $nu_code;
    }

    /* 設定 Login 的 nu_code cookie */
    function set_login_cookie($ssn, $acn, $mail, $sun, $time=0)
    {
        /* 2015/10/28 新增,設定 login cookie 之前先清除 login cookie 以避免舊的 cookie 干擾 */
        del_login_cookie();

        /* 先設定 ssn_acn 的 cookie */
        $ssn_acn = "$ssn:$acn";
        setcookie("ssn_acn", $ssn_acn, $time, "/", COOKIE_DOMAIN);
        $_COOKIE["ssn_acn"] = $ssn_acn;

        /* 取得 nu_code 並設定 cookie */
        $nu_code = get_nu_code($ssn, $acn, $mail, $sun);
        setcookie("nu_code", $nu_code, $time, "/", COOKIE_DOMAIN);
        $_COOKIE["nu_code"] = $nu_code;

        /* 2014/2/14 新增,設定 nu_code 的 session (因 cookie 有可能因瀏覽太多網頁造成 cookie 超過長度,另外設定 session 以便可還原 cookie) */
        set_nu_code_session($nu_code);

        return $nu_code;
    }

    /* 刪除 Login 的 cookie */
    function del_login_cookie()
    {
        Global $login_user;

        /* 若已登出就不必再執行 */
        /* 2015/10/28 修改,不檢查 login_user 以避免沒有清除乾淨 */
        //if ($login_user == false)
        //    return;

        $ssn_acn = $_COOKIE["ssn_acn"];
        $nu_code = $_COOKIE["nu_code"];
        if (!empty($ssn_acn))
            setcookie("ssn_acn", "", time()-1, "/", COOKIE_DOMAIN);
        if (!empty($nu_code))
            setcookie("nu_code", "", time()-1, "/", COOKIE_DOMAIN);
        /* 2014/2/14 新增,清除 nu_code 的 session */
        //session_start();
        //if (isset($_SESSION["nu_code"]))
        //    unset($_SESSION["nu_code"]);
        //session_write_close();
        /* 2014/10/16 新增,刪除 group_login 的 cookie */
        if (isset($_COOKIE["group_login"]))
             setcookie("group_login", "", time()-1, "/", COOKIE_DOMAIN);
        /* 2015/10/26 新增,刪除 auth_pwd 的 cookie */
        if (isset($_COOKIE["auth_pwd"]))
            setcookie("auth_pwd", "", time()-1, "/", COOKIE_DOMAIN);

        /* 2015/1/14 修改,清空所有 session 資料 */
        /* 2015/11/17 修改,改成只清除部份與 login user 相關的 session,以避免誤刪其他 session (如:中正 SSO 的 session) */
        session_start();
        //session_unset();
        if (isset($_SESSION["nu_code"]))
            unset($_SESSION["nu_code"]);
        if (isset($_SESSION["random_path"]))
            unset($_SESSION["random_path"]);
        if ((isset($login_user["ssn"])) && (isset($_SESSION[$login_user["ssn"]."_sca"])))
            unset($_SESSION[$login_user["ssn"]."_sca"]);
        session_destroy();
        //$_SESSION = array();
    }

    /* 2014/2/14 新增,設定 nu_code 的 session */
    function set_nu_code_session($nu_code)
    {
        session_start();
        $_SESSION["nu_code"] = $nu_code;
        session_write_close();
    }

    /* 取得 Login 的 user 資料 (若有傳入 code 會以 code 當成登入的 nu_code,並設定登入的 cookie) */
    function get_login_user($code=NULL, $time=NULL)
    {
        Global $lang, $group_mode;

        /* 2015/3/26 新增,若沒有傳入 time 設定,就使用預設的有效時間 (目前時間算起 7 天) */
        if ($time === NULL)
            $time = time() + DEF_LOGIN_TIME;

        /* 2014/2/14 修改,檢查是否有 nu_code 的 session,若沒有才找 cookie */
        session_start();
        if (isset($_SESSION["nu_code"]))
        {
            $nu_code = $_SESSION["nu_code"];
            /* 2015/5/6 修改,若沒有設 nu_code 的 cookie 時,就用 nu_code 的 session 重設 cookie */
            if ((!isset($_COOKIE["nu_code"])) || (empty($_COOKIE["nu_code"])))
            {
                setcookie("nu_code", $nu_code, $time, "/", COOKIE_DOMAIN);
                $_COOKIE["nu_code"] = $nu_code;
            }
        }
        else if (isset($_COOKIE["nu_code"]))
        {
            $nu_code = $_COOKIE["nu_code"];
            /* 若有 nu_code 的 cookie,但沒有 session 就設定 session */
            set_nu_code_session($nu_code);
        }
        session_write_close();
        if (isset($_COOKIE["ssn_acn"]))
            $ssn_acn = $_COOKIE["ssn_acn"];

        /* 若有傳入 code 參數,就直接用 code 當成登入的 nu_code,並設定登入的 cookie */
        /* 2015/3/26 修改,增加檢查傳入的 code 是否與原本的 nu_code 相同,若相同就不必重新設定 (否則有效時間會造成改變) */
        $update_cookie = false;
        if ((!empty($code)) && ($code !== $nu_code))
        {
            $update_cookie = true;
            $nu_code = $code;
            /* 2015/10/28 新增,設定 login cookie 之前先清除 login cookie 以避免舊的 cookie 干擾 */
            del_login_cookie();
            setcookie("nu_code", $nu_code, $time, "/", COOKIE_DOMAIN);
            $_COOKIE["nu_code"] = $nu_code;
            /* 2014/12/27 新增,傳入 code 時除了要設定 nu_code 的 cookie 也要設定 session */
            set_nu_code_session($nu_code);
        }

        /* 若沒有登入的 nu_code 直接回傳 false */
        if (empty($nu_code))
        {
            /* 2013/8/15 多增加檢查 ssn_acn (因尚有部份程式只有使用 ssn_acn 的 cookie) */
            if (empty($ssn_acn))
                return false;

            list($user["ssn"], $user["acn"]) = explode(":", $ssn_acn);
            if ((empty($user["ssn"])) || (empty($user["acn"])))
                return false;
            return $user;
        }

        $content = auth_decode($nu_code);
        if ($content == false)
            return false;

        /* 2014/4/16 修改,多取出一個 lang 欄位資料 */
        list($user["ssn"], $user["acn"], $user["mail"], $user["sun"], $user["lang"]) = explode(":", $content);
        if ((empty($user["ssn"])) || (empty($user["acn"])) || (empty($user["mail"])) || (empty($user["sun"])) || (is_numeric($user["ssn"]) != true))
            return false;
        /* 2014/4/16 新增,若有取得 lang 欄位資料,就設定為使用語系 (lang) */
        /* 2014/5/31 修改,若有設定 user_lang 且此語系的列表檔存在(代表有支援此語系),就將語系 (lang) 設為 user_lang */
        if ((!empty($user["lang"])) && (file_exists(LANG_LIST_FILE.".".$user["lang"])))
            $lang = $user["lang"];

        /* 若有傳入 code 參數,也必須設定 ssn_acn 的 cookie */
        /* 2015/3/26 修改,要檢查是否需要更新 cookie */
        if ((!empty($code)) && ($update_cookie == true))
        {
            setcookie("ssn_acn", $user["ssn"].":".$user["acn"], $time, "/", COOKIE_DOMAIN);
            $_COOKIE["ssn_acn"] = $user["ssn"].":".$user["acn"];
        }

        /* 2013/8/26 新增,檢查 login user 是否為網站 owner 或 manager */
        $user["site_owner"] = false;
        $user["site_manager"] = false;
        /* 2015/3/4 修改,若是 Group 的 CS 改抓 group_site.list 資料 */
        if ($group_mode !== GROUP_NONE)
        {
            $s_list = group_get_site_list(false, true);
            $s_cnt = count($s_list);
            $cnt = 0;
            for ($i = 0; $i < $s_cnt ; $i++)
            {
                /* 檢查是否為網站的 owner */
                if (($user["acn"] == $s_list[$i]["owner"]) || ($user["mail"] == $s_list[$i]["owner"]))
                {
                    /* 是 owner 一定是 manager */
                    $user["site_owner"] = true;
                    $user["site_manager"] = true;
                    break;
                }

                /* 檢查是否為網站的 manager */
                $m_acn = explode(",", $s_list[$i]["manager"]);
                $m_cnt = count($m_acn);
                for ($j = 1; $j < $m_cnt; $j++)
                {
                    if (($user["acn"] == $m_acn[$j]) || ($user["mail"] == $m_acn[$j]))
                    {
                        $user["site_manager"] = true;
                        break;
                    }
                }
            }
        }
        else
        {
            /* 取出 site_manager.list 的資料 */
            $s_manager_list = @file(SITE_MANAGER_LIST);
            $s_cnt = count($s_manager_list);
            $cnt = 0;
            for ($i = 0; $i < $s_cnt ; $i++)
            {
                list($site_acn, $manager_list) = explode("\t", strtolower(trim($s_manager_list[$i])));
                $m_acn = explode(",", $manager_list);
                $m_cnt = count($m_acn);
                /* 檢查是否為網站的 owner (第一筆資料是 owner) */
                if (($user["acn"] == $m_acn[0]) || ($user["mail"] == $m_acn[0]))
                {
                    /* 是 owner 一定是 manager */
                    $user["site_owner"] = true;
                    $user["site_manager"] = true;
                    break;
                }

                /* 檢查是否為網站的 manager (第二筆資料以後是 manager) */
                for ($j = 1; $j < $m_cnt; $j++)
                {
                    if (($user["acn"] == $m_acn[$j]) || ($user["mail"] == $m_acn[$j]))
                    {
                        $user["site_manager"] = true;
                        break;
                    }
                }
            }
        }

        return $user;
    }

    /* 檢查 edit code 是否正確 */
    function check_edit_code($code)
    {
        /* 2013/10/4 新增,若 code 為 EDIT_PASS_CODE 就回傳 true (主要提供 mobile 裝置跳過 edit_code 檢查用) */
        if ($code == EDIT_PASS_CODE)
            return true;

        $ip = $_SERVER["REMOTE_ADDR"];
        $nowtime = date("YmdHi");

        $content = auth_decode($code);
        if ($content == false)
            return false;

        list($uip, $overtime) = explode(",", $content);
        if (($uip != $ip) || ($nowtime > $overtime))
            return false;
        return true;
    }

    /* 取得 edit code 讓 Client 端在進行編輯文章 or 傳送檔案時可檢查是否為正常使用 */
    function get_edit_code()
    {
        /* 產生 code */
        $ip = $_SERVER["REMOTE_ADDR"];
        $t = time()+MAX_EDIT_TIME;
        $overtime = date("YmdHi", $t);
        $code = auth_encode("$ip,$overtime");
        return $code;
    }

    function write_nuweb_def($page_dir, $path, $info = array())
    {
        $file = $page_dir.$path."/".NUWEB_DEF;
        $file = fopen($file, "wb");
        flock($file, LOCK_EX);
        if ($file === FALSE)
            return FALSE;
        if (!array_key_exists("pwd", $info))
            $info["pwd"] = "";
        if (!array_key_exists("b_allow", $info))
            $info["b_allow"] = "*";
        if (!array_key_exists("b_deny", $info))
            $info["b_deny"] = "";
        if (!array_key_exists("u_allow", $info))
            $info["u_allow"] = "";
        if (!array_key_exists("u_deny", $info))
            $info["u_deny"] = "*";
        if (!array_key_exists("s_allow", $info))
            $info["s_allow"] = true;
        if (!array_key_exists("s_deny", $info))
            $info["s_deny"] = true;
        if (!array_key_exists("share_mode", $info))
            $info["share_mode"] = DEF_SHARE_MODE;
        fwrite($file, "PASSWORD=".$info["pwd"]."\r\n");
        fwrite($file, "ALLOW_USER=".$info["b_allow"]."\r\n");
        fwrite($file, "DENY_USER=".$info["b_deny"]."\r\n");
        fwrite($file, "ALLOW_UPLOAD_USER=".$info["u_allow"]."\r\n");
        fwrite($file, "DENY_UPLOAD_USER=".$info["u_deny"]."\r\n");
        fwrite($file, "SHARE_MODE=".$info["share_mode"]."\r\n");
        if ($info["s_allow"] == true)
            fwrite($file, "SHOW_DIR_TO_ALLOW=YES\r\n");
        else
            fwrite($file, "SHOW_DIR_TO_ALLOW=NO\r\n");
        if ($info["s_deny"] == true)
            fwrite($file, "SHOW_DIR_TO_DENY=YES\r\n");
        else
            fwrite($file, "SHOW_DIR_TO_DENY=NO");
        /* 2014/3/13 新增,強制關閉 (STRONG_DENY) 項目 */
        if (!array_key_exists("strong_deny", $info))
            $info["strong_deny"] = false;
        if ($info["strong_deny"] == true)
            fwrite($file, "STRONG_DENY=YES\r\n");
        else
            fwrite($file, "STRONG_DENY=NO\r\n");
        flock($file, LOCK_UN);
        fclose($file);
        return TRUE;
    }

    /* 讀取權限檔 */
    function read_nuweb_def($page_dir, $path)
    {
        Global $set_conf;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);

        /* 設定預設值 */
        $info["pwd"] = "";
        $info["b_allow"] = "*";
        $info["b_deny"] = "";
        $info["u_allow"] = "";
        $info["u_deny"] = "*";
        $info["s_allow"] = true;
        $info["s_deny"] = true;
        $info["share_mode"] = DEF_SHARE_MODE;
        $info["strong_deny"] = false;

        /* 檢查目錄是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false))
            return false;

        /* 2014/4/21 新增,若系統設定 use_nuweb_def=N 代表一律使用預設權限,直接回傳預設權限資料 */
        if ((isset($set_conf["use_nuweb_def"])) && ($set_conf["use_nuweb_def"] == NO))
            return $info;

        /* 如果目錄是 .nuweb_* (系統目錄) 就改取上層的權限檔 (因為系統目錄的權限應與上層目錄一樣) */
        $n = strrpos($path, "/");
        $path_name = ($n === false) ? $path : substr($path, $n+1);
        $l = strlen(NUWEB_SYS_FILE);
        if (substr($path_name, 0, $l) == NUWEB_SYS_FILE)
            $path = substr($path, 0, $n);

        /* 權限檔位置 */
        $file = $page_dir.$path."/".NUWEB_DEF;

        /* 2013/8/27 修改,若是在 Driver 目錄內,且 .nuweb_def 不存在,就設定 .nuweb_def */
        $path_url = str_replace(WEB_ROOT_PATH, "", $page_dir.$path);
        if ((is_Driver($path_url) == true) && (!file_exists($file)))
        {
            /* 若是在 web/Driver 預設 site_owner 可瀏覽,但其他網站的 Driver 預設所有人不可瀏覽 */
            $path_item = explode("/", substr($path_url, 1));
            if ($path_item[1] == "web")
            {
                 $info["b_allow"] = SITE_OWNER;
                 $info["b_deny"] = "";
            }
            else
            {
                $info["b_allow"] = "";
                $info["b_deny"] = "*";
            }
            write_nuweb_def($page_dir, $path, $info);
            return $info;
        }

        /*
        $private_dir = $page_dir.$path;
        $sub_site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";

        if (substr($private_dir, 0, strlen($sub_site_path)) == $sub_site_path)
        {
            $n2 = strpos($private_dir, "/", strlen($sub_site_path));
            $private_dir = ($n2 === false) ? "" : substr($private_dir, $n2+1);
            $n2 = strpos($private_dir, "/");
            $private_subdir = ($n2 === false) ? "" : substr($private_dir, $n2+1);
            $private_dir = ($n2 === false) ? $private_dir : substr($private_dir, 0, $n2);
            if ($private_dir == DRIVER_DIR_NAME)
            {
                $info["b_allow"] = "";
                $info["b_deny"] = "*";
                if (is_dir($page_dir.$path))
                    write_nuweb_def($page_dir, $path, $info);
                return $info;
            }
        }
        */

        /* 檢查權限檔是否存在 (不存在就向上層尋找,直到 page_dir 都找不到就用預設值) */
        while (!file_exists($file))
        {
            /* 權限檔不存在,就向上層尋找,找到最上層 (page_dir) 為止 */
            $n = strrpos($path, "/");
            if ($n === false)
            {
                $file = $page_dir.NUWEB_DEF;
                /* 都找不到權限檔就用預設值 */
                if (!file_exists($file))
                    return $info;
                break;
            }

            $path = substr($path, 0, $n);
            $file = $page_dir.$path."/".NUWEB_DEF;
        }

        /* 讀取權限檔 */
        $buf = file($file);
        $cnt = count($buf);
        for ($i = 0; $i < $cnt; $i++)
        {
            list($key, $value) = explode("=", trim($buf[$i]), 2);
            if ($key == "PASSWORD")
                $info["pwd"] = $value;
            if ($key == "ALLOW_USER")
                $info["b_allow"] = $value;
            if ($key == "DENY_USER")
                $info["b_deny"] = $value;
            if ($key == "ALLOW_UPLOAD_USER")
                $info["u_allow"] = $value;
            if ($key == "DENY_UPLOAD_USER")
                $info["u_deny"] = $value;
            if ($key == "SHOW_DIR_TO_ALLOW")
            {
                if ($value == "YES")
                    $info["s_allow"] = true;
                else
                    $info["s_allow"] = false;
            }
            if ($key == "SHOW_DIR_TO_DENY")
            {
                if ($value == "YES")
                    $info["s_deny"] = true;
                else
                    $info["s_deny"] = false;
            }
            if ($key == "SHARE_MODE")
                $info["share_mode"] = $value;
            /* 2014/3/13 新增,強制關閉 (STRONG_DENY) 項目 */
            if ($key == "STRONG_DENY")
            {
                if ($value == "YES")
                    $info["strong_deny"] = true;
                else
                    $info["strong_deny"] = false;
            }
        }
        return $info;
    }

    /* 取得 Group user */
    function get_group_user()
    {
        Global $set_conf, $reg_conf;

        /* 讀取 Group user list */
        $user_list = @file(GROUP_USER_LIST);
        $user_cnt = count($user_list);
        $grp_user = array();
        for ($i = 0; $i < $user_cnt; $i++)
        {
            list($id, $per_grp_user, $mail) = explode("\t", trim($user_list[$i]));
            $grp_user[$per_grp_user] = "";
        }
        if (isset($reg_conf) && isset($reg_conf["acn"]))
            $grp_user[$reg_conf["acn"]] = "";
        if (!isset($set_conf))
            $set_conf = read_conf(SETUP_CONFIG);
        $manager = explode(",", $set_conf["manager"]);
        $cnt = count($manager);
        for ($i = 0; $i < $cnt; $i++)
            $grp_user[$manager[$i]] = "";
        return array_keys($grp_user);
    }

    /* 取得 group user list 資料 */
    function get_group_user_list()
    {
        if (file_exists(GROUP_USER_LIST))
        {
            $buf = @file(GROUP_USER_LIST);
            $cnt = count($buf);
            for ($i = 0; $i < $cnt; $i++)
            {
                list($ssn, $acn, $mail) = explode("\t", trim($buf[$i]));
                $group_user_list[$i]["ssn"] = $ssn;
                $group_user_list[$i]["acn"] = $acn;
                $group_user_list[$i]["mail"] = $mail;
            }
        }
        return $group_user_list;
    }

    /* 新增 user 到 group user ilist 中 */
    function add_group_user_list($acn)
    {
        $acn = strtolower($acn);
        $group_user_list = get_group_user_list();
        $cnt = count($group_user_list);

        /* 檢查帳號是否已記錄到 group user list 中 */
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($group_user_list[$i]["acn"] == $acn)
                return true;
        }

        /* 取得 user 資料 */
        $user = get_user_data($acn);
        if ($user === false)
            return false;

        /* 將新 user 寫入 group user list 中 */
        $fp = fopen(GROUP_USER_LIST, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, $user["ssn"]."\t".$user["acn"]."\t".$user["mail"]."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

    /* 將 user 從 group user ilist 中刪除 */
    function del_group_user_list($acn)
    {
        $acn = strtolower($acn);
        $group_user_list = get_group_user_list();
        $cnt = count($group_user_list);

        /* 若帳號已在 group user list 中就移除 */
        $content = "";
        $update = false;
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($group_user_list[$i]["acn"] == $acn)
                $update = true;
            else
                $content .= $group_user_list[$i]["ssn"]."\t".$group_user_list[$i]["acn"]."\t".$group_user_list[$i]["mail"]."\n";
        }

        /* 資料沒更新代表沒找到要刪除的帳號,就回傳 false,若有成功刪除就將新資料寫入 group user list 中 */
        if ($update !== true)
            return false;
        $fp = fopen(GROUP_USER_LIST, "w");
        flock($fp, LOCK_EX);
        fwrite($fp, $content);
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

    /* 檢查是否為系統管理者 */
    function chk_manager($acn="")
    {
        Global $login_user, $set_conf, $reg_conf;

        /* 若沒傳入 acn 就使用 login user 資料 */
        if (empty($acn))
        {
            if ((empty($login_user)) || ($login_user == false))
                return false;
            $acn = $login_user["acn"];
            $mail = $login_user["mail"];
        }

        /* 若是此 Server 的註冊帳號,一定是管理者 */
        /* 2014/2/24 先取消 Server 註冊帳號為預設管理者的設定 */
        /* 2014/4/21 恢復 Server 註冊帳號為預設管理者 */
        if ((isset($reg_conf)) && (isset($reg_conf["acn"])) && ($reg_conf["acn"] == $acn))
            return true;

        /* 取得系統設定中的管理者資料 */
        if (!isset($set_conf))
            $set_conf = read_conf(SETUP_CONFIG);
        $m_list = explode(",", $set_conf["manager"]);
        $cnt = count($m_list);
        for ($i = 0; $i < $cnt; $i++)
        {
            $manager = trim($m_list[$i]);
            if (($acn == $manager) || ((!empty($mail)) && ($mail == $manager)))
                return true;
        }
        return false;
    }

    /* 檢查是否為網站管理者 */
    function chk_site_manager($file_path, $acn="")
    {
        Global $login_user, $is_manager, $admin_manager;

        /* 若沒傳入 acn 就取出登入的帳號設為 acn */
        if (empty($acn))
        {
            if ((empty($login_user)) || ($login_user == false))
                return false;
            $acn = $login_user["acn"];
            $mail = $login_user["mail"];
        }

        /* 檢查是否為子網站 file_path (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($file_path, 0, $l) !== $site_path) || (strlen($file_path) <= $l))
            return false;

        /* 若是系統管理者,就回傳 true */
        if ($is_manager == true)
            return true;

        /* 取得 site_acn,並讀取此子網站的設定資料 */
        $path = explode("/", substr($file_path, $l));
        $cnt = count($path);
        $site_acn = $path[0];
        $conf_file = $site_path.$site_acn."/".NUWEB_CONF;
        $site_conf = read_conf($conf_file);

        /* 2015/11/2 新增,若是 web 網站且是後端管理者,就認定是網站管理者 (後端管理者預設為 web 網站管理者) */
        if (($site_acn == "web") && ($admin_manager == true))
            return true;

        /* 檢查是否為此子網站的管理者 */
        $owner = strtolower($site_conf["owner"]);
        if (($acn == $owner) || ((!empty($mail)) && ($mail == $owner)))
            return true;
        $m_list = explode(",", strtolower($site_conf["manager"]));
        $cnt = count($m_list);
        for ($i = 0; $i < $cnt; $i++)
        {
            $manager = trim($m_list[$i]);
            if (($acn == $manager) || ((!empty($mail)) && ($mail == $manager)))
                return true;
        }
        return false;
    }

    /* 取出 acn 所管理的子網站 list */
    function get_manage_site($acn="")
    {
        Global $login_user;

        /* 若沒傳入 acn 就直接使用登入者的 acn */
        if (empty($acn))
            $acn = $login_user["acn"];
        /* 如果沒有 acn 或 site_manager.list 不存在就不處理 */
        if ((empty($acn)) || (!file_exists(SITE_MANAGER_LIST)))
            return false;
        $acn = strtolower($acn);
        $mail = "";
        if (!empty($login_user["mail"]))
            $mail = $login_user["mail"];

        /* 取出 site_manager.list 的資料 */
        $s_manager_list = @file(SITE_MANAGER_LIST);
        $s_cnt = count($s_manager_list);
        $cnt = 0;
        for ($i = 0; $i < $s_cnt ; $i++)
        {
            list($site_acn, $manager_list) = explode("\t", strtolower(trim($s_manager_list[$i])));
            $m_acn = explode(",", $manager_list);
            $m_cnt = count($m_acn);
            /* 檢查 acn 是否為網站的管理者,若是就設定到 site_list 中傳回 */
            for ($j = 0; $j < $m_cnt; $j++)
            {
                if (empty($m_acn[$j]))
                    continue;
                if (($m_acn[$j] == $acn) || ($m_acn[$j] == $mail))
                {
                    $site_list[$cnt++] = $site_acn;
                    break;
                }
            }
        }

        return $site_list;
    }

    /* 取出 acn 已是成員的子網站 list */
    function get_member_site($acn="")
    {
        Global $login_user;

        /* 若沒傳入 acn 就直接使用登入者的 acn */
        if (empty($acn))
            $acn = $login_user["acn"];
        /* 如果沒有 acn 或 site_member.list 不存在就不處理 */
        if ((empty($acn)) || (!file_exists(SITE_MEMBER_LIST)))
            return false;
        $acn = strtolower($acn);
        $mail = "";
        if (!empty($login_user["mail"]))
            $mail = $login_user["mail"];

        /* 取出 site_member.list 的資料 */
        $s_member_list = @file(SITE_MEMBER_LIST);
        $s_cnt = count($s_member_list);
        $cnt = 0;
        for ($i = 0; $i < $s_cnt ; $i++)
        {
            list($site_acn, $member_list) = explode("\t", strtolower(trim($s_member_list[$i])));
            $m_acn = explode(",", $member_list);
            $m_cnt = count($m_acn);
            /* 檢查 acn 是否為網站的成員,若是就設定到 site_list 中傳回 */
            for ($j = 0; $j < $m_cnt; $j++)
            {
                if (empty($m_acn[$j]))
                    continue;
                if (($m_acn[$j] == $acn) || ($m_acn[$j] == $mail))
                {
                    $site_list[$cnt++] = $site_acn;
                    break;
                }
            }
        }

        return $site_list;
    }

    /* 檢查是否要顯示子網站連結 */
    function chk_show_subsite_link()
    {
        Global $uacn, $set_conf, $is_manager, $admin_manager, $fun_set_conf;

        /* 2015/12/14 新增,若功能設定項目中設定關閉 site_list 就代表不顯示子網站連結 */
        if ((isset($fun_set_conf["site_list"])) && ($fun_set_conf["site_list"] == NO))
            return false;

        /* 依系統設定子網站連結顯示對象選項決定是否顯示子網站連結 */
        switch ($set_conf["subsite_show"])
        {
            case 1:
                /* subsite_show = 1 代表所有人都顯示 */
                return true;
            case 2:
                /* subsite_show = 2 代表僅系統管理者才顯示 */
                /* 2015/3/19 修改,admin_manager 代表是後端管理者 */
                //return $is_manager;
                if (($is_manager == true) || ($admin_manager == true))
                    return true;
                return false;
                break;
            case 0:
            default:
                /* subsite_show = 0 (預設) 代表子網站管理者才顯示 */
                if ($is_manager)
                    return true;

                /* 如果沒有登入或 site_manager.list 不存在就回傳 false */
                if ((empty($uacn)) || (!file_exists(SITE_MANAGER_LIST)))
                    return false;

                /* 取出 site_manager.list 的資料 */
                $s_manager_list = @file(SITE_MANAGER_LIST);
                $s_cnt = count($s_manager_list);
                $cnt = 0;
                for ($i = 0; $i < $s_cnt ; $i++)
                {
                    list($site_acn, $manager_list) = explode("\t", strtolower(trim($s_manager_list[$i])));
                    $m_acn = explode(",", $manager_list);
                    $m_cnt = count($m_acn);
                    /* 檢查 acn 是否為網站的管理者,若是就設定到 site_list 中傳回 */
                    for ($j = 0; $j < $m_cnt; $j++)
                    {
                        if ($m_acn[$j] == $uacn)
                            return true;
                    }
                }
                return false;
                break;
        }
    }

    /* 檢查是否為網站第一層目錄 */
    function is_site_dir($path)
    {
        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($path, 0, $l) !== $site_path) || (strlen($path) <= $l) || (strstr(substr($path, $l), "/") !== false))
            return false;
        return true;
    }

    /* 取得 .nuweb_type 設定 */
    function get_nuweb_type($path="")
    {
        Global $type_list;

        /* 檢查 .nuweb_type 是否存在 */
        if (substr($path, -1) != "/")
            $path .= "/";
        $dir_path = $path.NUWEB_TYPE;
        if (file_exists($dir_path))
            $type = read_conf($dir_path);

        /* 2014/7/15 新增,網站首頁的 type 一律設為 page */
        if ((is_site_dir($path) == true) && ($type["DIR_TYPE"] != PAGE_DIR_TYPE))
        {
            /* 更新 .nuweb_type */
            $type["DIR_TYPE"] = PAGE_DIR_TYPE;
            write_conf($dir_path, $type);

            /* 若 .nuweb_dir_set 存在也更新 .nuweb_dir_set 內容 */
            $dir_set_file = $path.NUWEB_DIR_SET;
            if (file_exists($dir_set_file))
            {
                /* 先取得原資料,再移除 type & tpl_mode 設定,最後再存檔 */
                $dir_set = read_conf($dir_set_file);
                unset($dir_set["type"]);
                unset($dir_set["tpl_mode"]);
                /* 2015/7/24 新增,也要清除 def_frame 項目 */
                if (isset($dir_set["def_frame"]))
                    unset($dir_set["def_frame"]);
                write_conf($dir_set_file, $dir_set);
            }

        }

        /* 如果沒有取得 DIR_TYPE 類型,就用 dir type list 的第一種類型 */
        if (empty($type["DIR_TYPE"]))
            $type["DIR_TYPE"] = $type_list[0]['mode'];

        /* 2013/8/28 新增,檢查 type 若是 OokonStorage 且 dir_config.php 存在,必須將 dir_config.php 刪除 (因為 OokonStorage 不是功能目錄型態) */
        if ($type["DIR_TYPE"] == DRIVER_DIR_TYPE)
        {
            $dir_config = $path."dir_config.php";
            if (file_exists($dir_config))
                unlink($dir_config);

            /* 若不是在 Driver 目錄內,就將 dir_type 改成預設的 directory 並更新 .nuweb_dir_set 設定 */
            $path_url = str_replace(WEB_ROOT_PATH, "", $path);
            if (chk_inDriver($path_url) !== true)
            {
                $type["DIR_TYPE"] = GENERAL_DIR_TYPE;
                write_conf($dir_path, $type);

                /* 若 .nuweb_dir_set 存在才需處理 */
                $dir_set_file = $path.NUWEB_DIR_SET;
                if (file_exists($dir_set_file))
                {
                    /* 先取得原資料,再修改 type & tpl_mode 設定,最後再存檔 */
                    $dir_set = read_conf($dir_set_file);
                    $dir_set["type"] = GENERAL_DIR_TYPE;
                    $dir_set["tpl_mode"] = GENERAL_DIR_TYPE;
                    /* 2015/7/24 新增,要清除 def_frame 項目 */
                    if (isset($dir_set["def_frame"]))
                        unset($dir_set["def_frame"]);
                    write_conf($dir_set_file, $dir_set);
                }
            }
        }

        /* 2014/2/11 新增,檢查 type 若是 multimedia,就將 dir_type 改成預設的 directory (因為要強制將 multimedia 使用 directory 的版型) */
        /* 2014/3/6 新增 type 若是 page,也改成預設的 directory */
        /* 2014/3/11 修改,網站首頁的 page type 不變更 */
        /* 2015/4/21 修改,取消強制將 multimedia 與 page 轉成預設 directory,因特殊版型會用到,強制轉換改由 page_lib.php 進行處理 */
        //if (($type["DIR_TYPE"] == MULTIMEDIA_DIR_TYPE) || (($type["DIR_TYPE"] == PAGE_DIR_TYPE) && (is_site_dir($path) == false)))
        //    $type["DIR_TYPE"] = GENERAL_DIR_TYPE;

        return $type["DIR_TYPE"];
    }

    /* 取得 def_tpl_mode 資料 */
    function get_def_tpl_mode($lang)
    {
        $def_tpl_mode_file = DEF_TPL_MODE.$lang;
        if (file_exists($def_tpl_mode_file))
        {
            $buf = @file($def_tpl_mode_file);
            $cnt = count($buf);
            for ($i = 0; $i < $cnt; $i++)
            {
                list($t_type, $t_mode, $t_name, $t_def_frame) = explode("\t", trim($buf[$i]));
                $def_tpl_mode[$i]["type"] = $t_type;
                $def_tpl_mode[$i]["mode"] = $t_mode;
                $def_tpl_mode[$i]["name"] = $t_name;
                $def_tpl_mode[$i]["def_frame"] = $t_def_frame;
            }
        }
        return $def_tpl_mode;
    }

    /* 取得目錄設定資料 */
    function get_dir_set($page_dir, $path_name)
    {
        Global $set_conf;

        /* 2015/7/24 新增,先取得網站目錄的 tpl_mode 資料,並檢查是否存在 site_tpl.list 內,若存在就設定為 site_tpl */
        $path = explode("/", $path_name);
        $conf_file = $page_dir.$path[0]."/".NUWEB_DIR_SET;
        $site_tpl = NULL;
        if (file_exists($conf_file))
        {
            $conf = read_conf($conf_file);
            if ((isset($conf["tpl_mode"])) && (site_tpl_exists($conf["tpl_mode"]) !== false))
                $site_tpl = $conf["tpl_mode"];
        }

        /* 檢查是否有 .nuweb_dir_set 若有就取出設定資料 */
        $dir_path = $page_dir.$path_name;
        if (substr($dir_path, -1) !== "/")
            $dir_path .= "/";
        $dir_set_file = $dir_path.NUWEB_DIR_SET;
        if (file_exists($dir_set_file))
            $dir_set = read_conf($dir_set_file);

        /* 下載選項預設為開啟,所以如果沒設定就預設為 ON */
        if (!isset($dir_set["download"]))
            $dir_set["download"] = ON;
        /* 轉寄分享預設為開啟,所以如果沒設定就預設為 ON */
        if (!isset($dir_set["share"]))
            $dir_set["share"] = ON;
        /* 預設 page_width 為 DEF_PAGE_WIDTH */
        if (!isset($dir_set["page_width"]))
            $dir_set["page_width"] = DEF_PAGE_WIDTH;
        /* 預設 menu_place 為 DEF_MENU_PLACE (L: menu 在左邊, T: menu 在上面) */
        if (!isset($dir_set["menu_place"]))
            $dir_set["menu_place"] = DEF_MENU_PLACE;
        /* 2013/8/12 新增,若是在 Driver 內 type 與 tpl_mode 一律設為 OokonStorage */
        $url = str_replace(WEB_ROOT_PATH, "", $dir_path);
        if (chk_inDriver($url))
        {
            $dir_set["type"] = DRIVER_DIR_TYPE;
            $dir_set["tpl_mode"] = DRIVER_DIR_TYPE;
            /* 2013/10/30 新增,在 Driver 內 def_frame 一律設為 N */
            $dir_set["def_frame"] = NO;
        }

        /* 若沒設定 type,就取出 .nuweb_type 的設定值 */
        if (!isset($dir_set["type"]))
            $dir_set["type"] = get_nuweb_type($dir_path);
        if (!isset($dir_set["tpl_mode"]))
            $dir_set["tpl_mode"] = $dir_set["type"];
        if (!isset($dir_set["style"]))
            $dir_set["style"] = "style15";

        /* 過濾掉 '_' 參數資料 (ajax 使用 cache: false 時會產生的參數,用不到所以過濾掉) */
        if (isset($dir_set["_"]))
            unset($dir_set["_"]);

        /* 2014/2/11 新增,檢查 type 或 tpl_mode 若是 multimedia,就將 type 與 tpl_mode 改成預設的 directory (因為要強制將 multimedia 使用 directory 的版型) */
        /* 2014/3/6 新增 type 或 tpl_mode 若是 page 也改成預設的 directory */
        /* 2014/3/11 修改,網站首頁的 page type 不變更 */
        if (($dir_set["type"] == MULTIMEDIA_DIR_TYPE) || (strstr($dir_set["tpl_mode"], MULTIMEDIA_DIR_TYPE) !== false) ||
            ((is_site_dir($dir_path) == false) && (($dir_set["type"] == PAGE_DIR_TYPE) || (strstr($dir_set["tpl_mode"], PAGE_DIR_TYPE) !== false))))
        {
            $dir_set["type"] = GENERAL_DIR_TYPE;
            $dir_set["tpl_mode"] = GENERAL_DIR_TYPE;
        }

        /* 2015/7/24 新增,若有設定 site_tpl 且 tpl_mode 不是 DRIVER_DIR_TYPE 時,一律將 tpl_mode 設為 site_tpl,同時 def_frame 設為 NO */
        if ((!empty($site_tpl)) && ($dir_set["tpl_mode"] !== DRIVER_DIR_TYPE))
        {
            $dir_set["tpl_mode"] = $site_tpl;
            $dir_set["def_frame"] = NO;
        }

        /* 2015/7/24 新增,若沒有設定 site_tpl 且 tpl_mode 存在 site_tpl.list 內,必須將 tpl_mode 強制改成預設的 directory,並清除 def_frame */
        if ((empty($site_tpl)) && (site_tpl_exists($dir_set["tpl_mode"])))
        {
            $dir_set["tpl_mode"] = GENERAL_DIR_TYPE;
            if (isset($dir_set["def_frame"]))
                unset($dir_set["def_frame"]);
        }

        /* 2014/3/18 新增,檢查若沒設定留言選項,就用預設,有設定 domain 時預設為 FB 留言,若沒設定 domain 預設為一般留言 */
        if ((!isset($dir_set["fb_comment"])) && (!isset($dir_set["user_comment"])))
        {
            if (!empty($set_conf["domain"]))
            {
                $dir_set["fb_comment"] = ON;
                $dir_set["user_comment"] = OFF;
            }
            else
            {
                $dir_set["fb_comment"] = OFF;
                $dir_set["user_comment"] = ON;
            }
        }

        return $dir_set;
    }

    /* 更新 dir_set 資料 */
    function update_dir_set($path, $update, $inherit=OFF, $header_arg=NULL)
    {
        Global $def_tpl_mode, $lang;

        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);

        /* 因需使用子網站相關函數,所以要先 require Site_Prog/init.php */
        /* 2015/2/11 刪除,因修改新版權限,public_lib.php 必須 include Site_Prog/init.php 所以不必再 include 一次 */
        //require_once("/data/HTTPD/htdocs/Site_Prog/init.php");

        /* 2013/7/12 新增,若是在 Driver 目錄內就不進行更新 (Driver 目錄內的 dir_set 檔目前不會更改) */
        $dir_path = WEB_PAGE_DIR.$path;
        $url = str_replace(WEB_ROOT_PATH, "", $dir_path);
        if (chk_inDriver($url) !== false)
            return;

        /* 檢查是否為一般目錄,若是功能目錄就一律不繼承到子目錄 */
        if (chk_function_dir(WEB_PAGE_DIR, $path) == true)
            $inherit = OFF;

        /* 2013/8/12 新增 header_arg 參數,先取得 header 檔位置 */
        $header_file = $dir_path."/".NUWEB_HEADER;
        /* 若是 header_arg = del 代表要刪除 header 檔(先檢查 header 檔是否存在) */
        if (($header_arg == "del") && (file_exists($header_file)))
            unlink($header_file);
        /* 若是 header_arg = copy 代表要 copy 上一層 header 檔 */
        if ($header_arg == "copy")
        {
            /* 先找出上一層的 header 檔,若檔案存在就 copy 到此目錄中的 header 檔位置 */
            $n = strrpos($dir_path, "/");
            $last_dir_path = substr($dir_path, 0, $n);
            $last_header_file = $last_dir_path."/".NUWEB_HEADER;
            if (file_exists($last_header_file))
                copy($last_header_file, $header_file);
        }
        /* 若 header_arg 是檔案代表是上傳的 header 檔 */
        if (is_file($header_arg))
        {
            /* 執行 move_uploaded_file 將上傳的 header 檔搬移到 header 檔位置 */
            move_uploaded_file($header_arg, $header_file);
            /* 若有設定繼承,將 header_arg 改成 copy(因下層只需要 copy 本層的 header 即可) */
            if ($inherit == ON)
                $header_arg = "copy";
        }
        /* 2013/8/20 修改,若 header_arg = NULL 代表不需處理,但若是有設定繼承,必須檢查 header 檔是否存在 */
        if (($header_arg == NULL) && ($inherit == ON))
        {
            /* 若 header 檔存在就將 header_arg 改成 copy,若不存在則改成 del */
            if (file_exists($header_file))
                $header_arg = "copy";
            else
                $header_arg = "del";
        }

        /* 取出 def_tpl_mode 資料 */
        if (!isset($def_tpl_mode))
            $def_tpl_mode = get_def_tpl_mode($lang);

        /* 先取得原 dir set 資料 */
        $dir_set_file = $dir_path."/".NUWEB_DIR_SET;
        if (file_exists($dir_set_file))
        {
            $dir_set = read_conf($dir_set_file);
            /* 過濾掉 '_' 參數資料 (ajax 使用 cache: false 時會產生的參數,用不到所以過濾掉) */
            if (isset($dir_set["_"]))
                unset($dir_set["_"]);
        }

        /* 將要更新的資料設定到 dir set 中 */
        $change = false;
        $type_change = false;
        $tpl_mode_change = false;
        foreach ($update as $key => $value)
        {
            if ((!isset($dir_set[$key])) || ($dir_set[$key] !== $value))
            {
                $dir_set[$key] = $value;
                $change = true;

                /* 檢查 type 是否有更新 */
                if ($key == "type")
                    $type_change = true;

                /* 檢查 tpl_mode 是否有更新 */
                if ($key == "tpl_mode")
                    $tpl_mode_change = true;

            }
        }

        /* 若有變更 type 設定,要同時更新 .nuweb_type 設定,並檢查 tpl_mode 是否正確 */
        if ($type_change == true)
        {
            /* 先取出原 .nuweb_type 內容 */
            $type_file = $dir_path."/".NUWEB_TYPE;
            if (file_exists($type_file))
                $type_conf = read_conf($type_file);

            /* 將 DIR_TYPE 內容更新,並存回 .nuweb_type */
            $type_conf["DIR_TYPE"] = $dir_set["type"];
            write_conf($type_file, $type_conf);

            /* 檢查 tpl_mode 是否正確 */
            $cnt = count($def_tpl_mode);
            $tpl_mode_flag = false;
            for ($i = 0; $i < $cnt; $i++)
            {
                if (($def_tpl_mode[$i]["type"] == $dir_set["type"]) && ($def_tpl_mode[$i]["mode"] == $dir_set["tpl_mode"]))
                {
                    $tpl_mode_flag = true;
                    break;
                }
            }
            /* 若 tpl_mode 不正確就改用預設的 tpl_mode (預設 tpl_mode 與 type 名稱相同) */
            if ($tpl_mode_flag == false)
            {
                $change = true;
                $tpl_mode_change = true;
                $dir_set["tpl_mode"] = $dir_set["type"];
            }

            /* 2013/9/25 新增,若 type 變更必須更新目錄 record 的 dir_type 欄位內容 */
            $rec_file = get_file_rec_path($dir_path);
            $rec["dir_type"] = $dir_set["type"];
            update_rec_file($rec_file, $rec);
        }

        /* 若有變更設定,要檢查 tpl_mode 是否要使用預設 frame (def_frame),並寫回設定 */
        if ($change == true)
        {
            /* 若 tpl_mode 有變更,就檢查 tpl_mode 是否要是使用預設 frame */
            if ($tpl_mode_change == true)
            {
                $cnt = count($def_tpl_mode);
                for ($i = 0; $i < $cnt; $i++)
                {
                    if ($def_tpl_mode[$i]["mode"] == $dir_set["tpl_mode"])
                    {
                        $dir_set["def_frame"] = $def_tpl_mode[$i]["def_frame"];
                        break;
                    }
                }
            }

            write_conf($dir_set_file, $dir_set);

            /* 2014/9/6 新增,紀錄到 modify.list 中 */
            write_modify_list("update", $dir_set_file, "conf");
        }

        /* 檢查是否要繼承到子目錄 */
        if ($inherit == ON)
        {
            /* 取出子目錄 */
            $handle = opendir($dir_path);
            while ($sub_dir = readdir($handle))
            {
                $sub_path = $path."/".$sub_dir;
                $sdir_path = WEB_PAGE_DIR.$sub_path;
                /* 只取出子目錄,並過濾掉 . & .. & .nuweb_* & symlink */
                if ((!is_dir($sdir_path)) || ($sub_dir == ".") || ($sub_dir == "..") || (substr($sub_dir, 0, 7) == NUWEB_SYS_FILE) || (is_link($sdir_path)))
                    continue;

                /* 更新子目錄的 dir_set 資料 */
                update_dir_set($sub_path, $update, $inherit, $header_arg);
            }
            closedir($handle);
        }
    }

    /* 取出目錄內第一張縮圖 */
    function get_first_tn_pict($page_dir, $path)
    {
        Global $fe_type;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        $full_path = $page_dir.$path;
        if (!is_dir($full_path))
            return false;

        /* 檢查目錄是否有設權限,若有就用 LOCK_DIR_PICT 的圖片當目錄縮圖 */
        /* 2014/3/13 修改,增加檢查 strong_deny 項目 */
        /* 2015/2/11 修改,已不再使用 .nuweb_def,改從 record 中取得權限狀態 */
        //$info = read_nuweb_def($page_dir, $path);
        //if ((!empty($info["pwd"])) || ($info["b_allow"] != "*") || ($info["strong_deny"] == true))
        $r_status = chk_user_right($full_path);
        if (($r_status["browse"] !== PASS) && ($r_status["download"] !== PASS))
            return LOCK_DIR_PICT;

        /* 讀取目錄內的子目錄與檔案 */
        $handle = opendir($full_path);
        $item_cnt = 0;
        $subdir_first_tn = false;
        $tn_fe_len = strlen(TN_FE_NAME);
        $big_tn_fe_len = strlen(BIG_TN_FE_NAME);
        $med_tn_fe_len = strlen(MED_TN_FE_NAME);
        $med2_tn_fe_len = strlen(MED2_TN_FE_NAME);
        $src_tn_fe_len = strlen(SRC_TN_FE_NAME);
        while ($dir_file = readdir($handle))
        {
            /* 跳過子目錄名稱為 . & .. 不必處理 */
            if (($dir_file == ".") || ($dir_file == ".."))
                continue;

            /* 找到第一個縮圖檔就直接回傳 (但不可是大縮圖檔與中縮圖檔與影片的原始縮圖檔) */
            if ((substr($dir_file, -$tn_fe_len) == TN_FE_NAME) && (substr($dir_file, -$big_tn_fe_len) != BIG_TN_FE_NAME) && (substr($dir_file, -$med_tn_fe_len) != MED_TN_FE_NAME) && (substr($dir_file, -$med2_tn_fe_len) != MED2_TN_FE_NAME) && (substr($dir_file, -$src_tn_fe_len) != SRC_TN_FE_NAME))
                return $path."/".$dir_file;
            else
            {
                /* 尚未找到縮圖前,也要尋找子目錄的第一張縮圖,以便在目錄內找不到縮圖時也能顯示子目錄的縮圖 */
                if (($subdir_first_tn == false) && (is_dir($full_path."/".$dir_file)))
                    $subdir_first_tn = get_first_tn_pict($page_dir, $path."/".$dir_file);
                continue;
            }
        }
        closedir($handle);
        return $subdir_first_tn;
    }

    /* 將 Record File 資料取出並依欄位轉換成 array */
    function rec2array($rec_file)
    {
        if (!file_exists($rec_file))
            return;

        /* 讀取 Record File */
        $buf = @file($rec_file);
        $rec = recbuf2array($buf);
        return $rec;
    }

    /* 將 Record 內容依欄位轉換成 array */
    function recbuf2array($rec_buf)
    {
        if (empty($rec_buf))
            return false;
        $cnt = count($rec_buf);
        $rec_cnt = 0;
        $rec = "";

        /* 過濾掉 UTF-8 起始碼 */
        if (strstr($rec_buf[0], UTF8_START_CODE) === $rec_buf[0])
            $rec_buf[0] = substr($rec_buf[0], strlen(UTF8_START_CODE));

        for ($i = 0; $i < $cnt; $i++)
        {
            /* 2014/9/8 修改,若是前面為 " @xxx" 就取消 trim 過濾前後空白,以避免產生 record 欄位誤判錯誤問題 */
            if ((strlen($rec_buf[$i]) < 2) || (substr($rec_buf[$i], 0, 2) !== " @"))
                $rec_buf[$i] = trim($rec_buf[$i])."\n";
            if ($rec_buf[$i] == "\n")
                continue;

            /* 判斷是否有新的 Record (Record 的啟始為 REC_START,第二行一開始必須是 REC_FIELD_START) */
            if (($rec_buf[$i] == REC_START) && ($rec_buf[$i+1][0] == REC_FIELD_START))
            {
                $rec_cnt++;
                continue;
            }

            /* 判斷是否為 Record 欄位 (@field: 為 Record 欄位格式,例: "@U:") */
            if (($rec_buf[$i][0] == '@') && (strpos($rec_buf[$i], ':') !== false))
                list($field_name, $field_content) = explode(":", substr($rec_buf[$i],1), 2);
            else
                $field_content .= $rec_buf[$i];

            /* 尚未找到新 Record 前的資料直接跳過 */
            if ($rec_cnt == 0)
                continue;

            $rec[$rec_cnt-1][$field_name] = trim($field_content);
        }

        return $rec;
    }

    /* 建立縮圖 function */
    function extract_tn($img_file, $size=DEF_TN_SIZE, $fe="")
    {
        Global $fe_type;

        /* 2015/7/14 新增,檢查副檔名若不是圖片檔就不處理 */
        /* 2015/7/17 修改,若沒有副檔名還是要處理,因為有部份暫存檔沒設定副檔名 (如: 抓取連結圖片時的暫存檔),轉檔會出問題 */
        $p = strrpos($img_file, ".");
        if ($p !== false)
        {
            $f_fe = strtolower(substr($img_file, $p));
            if ($fe_type[$f_fe] != IMAGE_TYPE)
                return false;
        }

        /* 若沒傳入 fe 就用預設的 TN_FE_NAME (.thumbs.jpg) 當副檔名 */
        if (empty($fe))
            $fe = TN_FE_NAME;

        /* 先取得圖片尺寸,若原圖片尺寸比縮圖小,就轉成原圖尺寸 (長寬中取大的當 size) */
        $src_size = @getimagesize($img_file);
        if (($src_size != false) && ($src_size[0] <= $size) && ($src_size[1] <= $size))
            $size = ($src_size[0] > $src_size[1]) ? $src_size[0] : $src_size[1];

        $rename_hack = false;
        $hack_fe = "";
        if (SYS_OS == "Ubuntu")
        {
            //$cmd = SYS_CONVERT . " -resize ${size}x${size}\\> -quality 75 -strip +profile \"*\" ${img_file}[0] $img_file$fe";
            //$cmd = SYS_CONVERT." ".$img_file." -flatten -resize ".$size."x".$size." ".$img_file.$fe;
            $cmd = SYS_CONVERT." ".$img_file." -flatten -quality 80 -thumbnail ".$size."x".$size." ".$img_file.$fe;
            if (substr($img_file, -4) == ".png")
            {
                //$cmd = SYS_CONVERT . " -resize ${size}x${size}\\> -quality 75 -background white -flatten $img_file $img_file$fe";
                $rename_hack = true;
                $hack_fe = ".thumbs.png";
                $cmd = SYS_CONVERT." ".$img_file." -resize ".$size."x".$size." ".$img_file.$hack_fe;
            }
            else if(substr($img_file, -4) == ".gif")
            {
                $rename_hack = true;
                $hack_fe = ".thumbs.gif";
                $cmd = SYS_CONVERT." ".$img_file." -resize ".$size."x".$size." ".$img_file.$hack_fe;
            }
        }
        else
        {
            /* 必須先過濾掉最後的 .jpg */
            if (substr($fe, -4) == ".jpg")
                $fe = substr($fe, 0, -4);
            /* 設定縮圖的檔名 */
            $tn_filename = $img_file.$fe;

            $cmd = EXTRACT_TN . " $img_file $tn_filename $size";
        }
        $fp = popen($cmd, "r");
        pclose($fp);
        if ($rename_hack == true)
            rename($img_file.$hack_fe, $img_file.$fe);
    }

    /* 檢查是否為圖片檔,若是就建立縮圖 */
    function set_img_tn($page_dir, $file)
    {
        Global $fe_type;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 取出檔案副檔名 */
        $fe = strtolower(substr($file, strrpos($file, ".")));

        /* 檢查是否為圖片檔,若不是就離開不處理,若是就建立縮圖與大尺寸縮圖 */
        if ($fe_type[$fe] != IMAGE_TYPE)
            return;
        else
        {
            /* 先轉小縮圖 */
            extract_tn($page_dir.$file);
            /* 若是 SYS_HW 為 ARM 就把圖檔記錄在 extract.list 中,會由其他程式定時去轉出中與大縮圖,若不是 ARM 就直接轉縮圖 */
            if (SYS_HW !== "ARM")
            {
                extract_tn($page_dir.$file, MED_TN_SIZE, MED_TN_FE_NAME);
                extract_tn($page_dir.$file, MED2_TN_SIZE, MED2_TN_FE_NAME);
                extract_tn($page_dir.$file, BIG_TN_SIZE, BIG_TN_FE_NAME);
            }
            else
                write_extract_list($page_dir.$file);
        }
    }

    /* 紀錄 extract.list (準備要進行圖片轉檔用) */
    function write_extract_list($img_file)
    {
        $fp = fopen(EXTRACT_LIST, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$img_file\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /* 執行 extract.list 內的圖檔轉檔工作 */
    function exec_extract_list()
    {
        /* 若 extract.list 不存在,就不用處理 */
        if (!file_exists(EXTRACT_LIST))
            return false;

        /* 檢查系統是否已正在進行處理 (檢查 extract flag 是否存在),若是就先離開 */
        if (file_exists(EXTRACT_FLAG))
        {
            $flag_mtime = filemtime(EXTRACT_FLAG);
            $now_time = time();
            $process_time = $now_time - $flag_mtime;
            /* 如果處理時間不超過 MAX_PROCESS_TIME 就先離開 (認定尚未處理完) */
            if ($process_time < MAX_PROCESS_TIME)
                return false;
        }
        /* 設定 extract flag,並將 extract.list 內容移到 extract.list.old (避免處理過程中有新資料進來)  */
        touch(EXTRACT_FLAG);
        $extract_old = EXTRACT_LIST.".old";
        if (file_exists($extract_old))
        {
            /* 若 extract.list.old 已存在,就將 extract.list 內容加入,並移除 extract.list */
            $cmd = "cat ".EXTRACT_LIST." >> $extract_old ; rm -f ".EXTRACT_LIST;
            $fp = popen($cmd, "r");
            pclose($fp);
        }
        else
            rename(EXTRACT_LIST, $extract_old);

        /* 取出 extract list 資料 */
        $list = @file($extract_old);
        $cnt = count($list);
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 圖檔若已不存在就不必處理 */
            $img_file = trim($list[$i]);
            if (!file_exists($img_file))
               continue;

            /* 將圖片檔轉出中與大縮圖 */
            if (!file_exists($img_file.MED_TN_FE_NAME))
                extract_tn($img_file, MED_TN_SIZE, MED_TN_FE_NAME);
            if (!file_exists($img_file.MED2_TN_FE_NAME))
                extract_tn($img_file, MED2_TN_SIZE, MED2_TN_FE_NAME);
            if (!file_exists($img_file.BIG_TN_FE_NAME))
                extract_tn($img_file, BIG_TN_SIZE, BIG_TN_FE_NAME);
				
			
			
			// *** whee ODB 
			if (!function_exists("odb_api_upload_file_image"))
				require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
			odb_api_upload_file_image($img_file);
			
			
			
        }

        /* 刪除 extract.list.old 與 extract flag */
        unlink($extract_old);
        unlink(EXTRACT_FLAG);
        return true;
    }

    /* 將音樂檔轉出 mp3 檔 */
    function audio2mp3($file)
    {
        Global $fe_type;

        /* 整理 file 取得檔名與副檔名等資料 */
        $n = strrpos($file, "/");
        $filename = substr($file, $n+1);
        $n = strrpos($filename, ".");
        $fe = strtolower(substr($filename, $n));
        /* 若檔案不存在或不是需要轉換的音樂檔或檔案 size 為 0 就不處理 */
        if ((!is_file($file)) || ($fe_type[$fe] != AUDIO_TYPE) || ($fe == ".mp3") || (real_filesize($file) == 0))
            return false;

        /* 找出儲存 mp3 檔的目錄 (.nuweb_media) 位置,若目錄不存在就自動建立 */
        $n = strrpos($file, "/");
        $dir_path = substr($file, 0, $n);
        $mp3_dir = $dir_path."/".NUWEB_MEDIA_PATH;
        if (!is_dir($mp3_dir))
            mkdir($mp3_dir);

        /* 將音樂轉成 mp3 檔 */
        $mp3_file = "$mp3_dir$filename.mp3";
        $mp3_url = str_replace(WEB_ROOT_PATH, "", $mp3_file);
        if (multimedia2mp3($file, $mp3_file) == true)
            return $mp3_url;
        return false;
    }

    /* 將文件檔轉出 pdf 檔 */
    function doc2pdf($doc_file)
    {
        Global $fe_type;

        /* 整理 doc_file 取得檔名與副檔名等資料 */
        $n = strrpos($doc_file, "/");
        $filename = substr($doc_file, $n+1);
        $n = strrpos($filename, ".");
        $fe = strtolower(substr($filename, $n));
        $fn = substr($filename, 0, $n);
        /* 若檔案不存在或不是需要轉換的文件檔或檔案 size 為 0 就不處理 */
        if ((!is_file($doc_file)) || ($fe_type[$fe] != DOC_TYPE) || ($fe == ".pdf") || (real_filesize($doc_file) == 0))
            return false;
        /* 2014/6/23 新增,若相對應的 pdf 轉檔程式 (ps2pdf 或 pdflatex 或 soffice) 不存在就不處理 */
        if (($fe == ".ps") || ($fe == ".eps"))
        {
            if (!file_exists(PS2PDF_PROG))
                return false;
        }
        else if ($fe == ".tex")
        {
            /* 2014/6/25 暫時先取消 .tex 檔轉 pdf 檔功能 */
            //if (!file_exists(LATEX2PDF_PROG))
                return false;
        }
        else if (!file_exists(PDF_CONVER_PROG))
            return false;

        /* 找出儲存 pdf 檔的目錄 (.nuweb_pdf) 位置,若目錄不存在就自動建立 */
        $n = strrpos($doc_file, "/");
        $dir_path = substr($doc_file, 0, $n);
        $pdf_dir = $dir_path."/".NUWEB_PDF_PATH;
        if (!is_dir($pdf_dir))
            mkdir($pdf_dir);

        /* 將文件轉成 pdf 檔 */
        $pdf_file = "$pdf_dir$filename.pdf";
        $pdf_url = str_replace(WEB_ROOT_PATH, "", $pdf_file);
        /* 2014/6/23 修改,若是 .ps 或 .eps 檔案就用 ps2pdf 轉換 pdf 檔 */
        /* 2014/6/25 暫時先取消 .tex 檔轉 pdf 檔功能 */
        if (($fe == ".ps") || ($fe == ".eps"))
            $cmd = PS2PDF_PROG." $doc_file $pdf_file";
        //else if ($fe == ".tex")
        //    $cmd = LATEX2PDF_CMD."$pdf_dir $doc_file ; rm -f $pdf_dir*.log $pdf_dir*.aux";
        else
            $cmd = PDF_CONVER_CMD."$pdf_dir $doc_file";
        //$fp = popen($cmd, "r");
        //pclose($fp);
        exec($cmd);
        $out_file = "$pdf_dir$fn.pdf";
        if (file_exists($out_file))
            rename($out_file, $pdf_file);
        if (file_exists($pdf_file))
        {
            /* 2014/9/9 修改,若建立 pdf 檔,必須更新檔案 record 的相關欄位內容 */
            $rec_file = get_file_rec_path($doc_file);
            $fe = strtolower(substr($doc_file, strrpos($doc_file, ".")));
            $rec = get_document_field($doc_file, $fe);
            update_rec_file($rec_file, $rec);

            return $pdf_url;
        }
        return false;
    }

    /* 檢查是否為文件檔,若是就轉出 pdf 檔 */
    function chk_doc_file($page_dir, $file)
    {
        Global $fe_type;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 檢查 file 是否正確 */
        if (($file[0] == '/') || (strstr($file, "..") !== false) || (empty($file)))
            return false;

        /* 取出檔案副檔名 */
        $fe = strtolower(substr($file, strrpos($file, ".")));

        /* 檢查是否為文件檔,若不是或本身已是 pdf 檔就離開不處理,若是就轉出 pdf 檔 */
        if (($fe_type[$fe] != DOC_TYPE) || ($fe == ".pdf"))
            return false;
        else
            return doc2pdf($page_dir.$file);
    }

    /* 產生訂閱所需的 code */
    function subscribe_code($path_name, $show_path_name, $site, $lang)
    {
        Global $uacn;

        $code = auth_encode("$path_name,$show_path_name,$site,$lang,$uacn");
        return $code;
    }

    /* 設定目錄內的 submenu set 資料 */
    function set_submenu_set($page_dir, $path, $set_info)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 先取出原 submenu set 資料 */
        $submenu_set = $page_dir.$path."/".NUWEB_SUBMENU_SET;
        if (file_exists($submenu_set))
        {
            $submenu_set_info = read_conf($submenu_set);
            $modify_mode = "update";
        }
        else
            $modify_mode = "new";

        /* 整理新的 submenu set 資料,若沒傳入的欄位,代表用原本的設定 */
        foreach($set_info as $key => $value)
            $submenu_set_info[$key] = $value;

        /* 寫回 submenu set 資料 */
        write_conf($submenu_set, $submenu_set_info);

        /* 2014/9/6 新增,紀錄到 modify.list 中 */
        write_modify_list($modify_mode, $submenu_set, "conf");
    }

    /* 取出目錄內的 submenu set 資料 */
    function get_submenu_set($page_dir, $path)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 取出 submenu set 資料 */
        $submenu_set = $page_dir.$path."/".NUWEB_SUBMENU_SET;
        if (file_exists($submenu_set))
        {
            $set_info = read_conf($submenu_set);
            return $set_info;
        }
        else
            return false;
    }

    /* 整理出所有的 submenu id */
    function get_submenu_id()
    {
        $id_cnt = 0;
        $id_chk_str = "submenu/".SUBMENU_ID_PREFIX;
        $len = strlen($id_chk_str);

        /* 取得 submenu.css 的內容 */
        $css_buf = @file(SUBMENU_CSS);
        $cnt = count($css_buf);
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 檢查是否為 submenu id (必須符合 id_chk_str 內容才對) */
            $buf = explode("\"", $css_buf[$i]);
            if (substr($buf[1], 0, $len) == $id_chk_str)
            {
                /* 整理出 submanu id */
                $css_file = substr($buf[1], strpos($buf[1], SUBMENU_ID_PREFIX));
                $id = substr($css_file, 0, strrpos($css_file, "."));
                /* 檢查是否為已找過的 submenu id */
                if (isset($submenu_id[$id][0]))
                    continue;
                else
                    $submenu_id[$id][0] = $id;

                /* 找出 submenu id 所包含所有不同的 id_no */
                $id_css_file = SUBMENU_CSS_DIR.$css_file;
                $id_css_buf = @file($id_css_file);
                $id_cnt = count($id_css_buf);
                $id_no = 0;
                $chk_str = ".".$id."-";
                $chk_len = strlen($chk_str);
                for ($j = 0; $j < $id_cnt; $j++)
                {
                    $buf = explode(" ", $id_css_buf[$j]);
                    if (substr($buf[1], 0, $chk_len) == $chk_str)
                        $id_no = substr($buf[1], $chk_len);
                    if (isset($submenu_id[$id][$id_no]))
                        continue;
                    else
                        $submenu_id[$id][$id_no] = "$id-$id_no";
                }
            }
        }

        return $submenu_id;
    }

    /* 取出目錄內的 submenu list */
    function get_dir_submenu($page_dir, $path)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 檢查目錄中是否有 .nuweb_submenu,若有就取出內容傳回 */
        $dir_path = $page_dir.$path;
        $submenu_file = $dir_path."/".NUWEB_SUBMENU;
        if (!file_exists($submenu_file))
        {
            /* 2014/7/1 修改,若是 blog 目錄,就必須取得子目錄當成 submenu */
            $type_file = $dir_path."/".NUWEB_TYPE;
            $dir_type = read_conf($type_file);
            if ($dir_type['DIR_TYPE'] !== TYPE_BLOG_DIR)
                return false;

            /* 取得子目錄 */
            $handle = opendir($dir_path);
            $item_cnt = 0;
            while ($dir_file = readdir($handle))
            {
                /* 先過濾掉非目錄 & . & .. & nuweb_* & symlink */
                $sub_path = $dir_path."/".$dir_file;
                if ((!is_dir($sub_path)) || ($dir_file == ".") || ($dir_file == "..") || (substr($dir_file, 0, 7) == NUWEB_SYS_FILE) || (is_link($sub_path)))
                    continue;
                /* 過濾掉 .sync & *.files 目錄 */
                $page_name = get_file_name($page_dir, str_replace($page_dir, "", $sub_path));
                if (($page_name == SYNC_DIR) || (substr($page_name, -6) == ".files"))
                    continue;
                /* 其他子目錄都設定到 submenu list 中 */
                $s_list[$item_cnt++] = $dir_file;
            }

            /* 若 submenu list 內沒有任何項目就不處理 */
            if ($item_cnt == 0)
                return false;
        }
        else
            $s_list = @file($submenu_file);
        $s_cnt = count($s_list);
        $cnt = 0;
        for ($i = 0; $i < $s_cnt; $i++)
        {
            $submenu_path = $path."/".trim($s_list[$i]);
            /* 如果 submenu 的項目已不存在就略過 */
            if (!file_exists($page_dir.$submenu_path))
                continue;
            $submenu_list[$cnt++] = $submenu_path;
        }
        return $submenu_list;
    }

    /* 取出要顯示的 submenu list */
    function get_submenu_list($page_dir, $path)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 先取出目前目錄內的 submenu list */
        $submenu = get_dir_submenu($page_dir, $path);

        /* 若沒有設定 submenu list,就找上一層的 submenu list */
        if ($submenu === false)
        {
            $last_path = substr($path, 0, strrpos($path, "/"));

            /* 先取出 submenu set 資料 */
            $submenu_set = get_submenu_set($page_dir, $last_path);
            /* 檢查是否有提供下層顯示,若沒有就不處理 */
            if (($submenu_set["subdir_display"] == "N") || ($submenu_set["subdir_display"] == "n"))
                return false;

            /* 取出上一層的 submenu list */
            $submenu = get_dir_submenu($page_dir, $last_path);

            /* 檢查 submenu list 中,是否有該目錄,有該目錄才能使用 submenu list,若沒有就回傳 false */
            $cnt = count($submenu);
            $in_submenu = false;
            for ($i = 0; $i < $cnt; $i++)
            {
                if ($submenu[$i] == $path)
                {
                    $in_submenu = true;
                    break;
                }
            }
            if ($in_submenu == false)
                return false;
        }
        else
        {
            /* 取出 submenu set 資料 */
            $submenu_set = get_submenu_set($page_dir, $path);
        }

        /* 整理 submenu list 的名稱 */
        $cnt = count($submenu);
        for ($i = 0; $i < $cnt; $i++)
        {
            $s_path = $submenu[$i];
            $s_name = get_file_name($page_dir, $s_path);
            if (($s_name == false) || (empty($s_name)))
            {
                $n = strrpos($s_path, "/");
                if ($n === false)
                    $s_name = $s_path;
                else
                    $s_name = substr($s_path, $n+1);
            }

            /* 檢查 submenu 的項目是否為目錄,若不是就必須把名稱後面的副檔名拿掉 */
            if ((!is_dir($page_dir.$s_path)) && (($n = strrpos($s_name, ".")) !== false))
                $s_name = substr($s_name, 0, $n);

            $submenu_list["item"][$i]["path"] = $s_path;
            $submenu_list["item"][$i]["name"] = $s_name;
        }

        /* 將 submenu set 的設定資料放到 submenu list 中傳回 */
        if ($submenu_set !== false)
        {
            foreach($submenu_set as $key => $value)
                $submenu_list[$key] = $value;
        }

        return $submenu_list;
    }

    /* 記錄 server log */
    function write_server_log($log_file, $log_msg)
    {
        Global $uacn;

        $date_time = date("Y-m-d:H:i:s");
        $today = date("Ymd");

        /* 記錄 log_msg 到 log file */
        $fp = fopen($log_file, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $_SERVER["REMOTE_ADDR"]."\t".$date_time."\t".$uacn."\t".$log_msg."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /* 取得 work_dir 內的 table list */
    function get_table_list($page_dir, $work_dir)
    {
        Global $table_list, $table_cnt;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        if (empty($table_cnt))
            $table_cnt = 0;

        /* 讀取目錄內的檔案與子目錄 */
        $handle = opendir($work_dir);
        while ($sub_name = readdir($handle))
        {
            /* 過濾掉 . & .. */
            if (($sub_name == ".") || ($sub_name == ".."))
                continue;
            /* 檢查是否為子目錄,是才進行後續處理 */
            $sub_dir = str_replace("//", "/", $work_dir."/".$sub_name);
            if ((is_dir($sub_dir)) && (!is_link($sub_dir)))
            {
                /* 檢查 type 是否為 table mode */
                $type_file = $sub_dir."/".NUWEB_TYPE;
                if (file_exists($type_file))
                {
                    $dir_type = read_conf($type_file);
                    if ($dir_type['DIR_TYPE'] == TYPE_TABLE_DIR)
                        $table_list[$table_cnt++] = str_replace($page_dir, "", $sub_dir);
                }

                /* 繼續往下層尋找 table list */
                $table_list = get_table_list($page_dir, $sub_dir);
            }
        }
        closedir($handle);
        return $table_list;
    }

    /* 取得目錄下所有真實的 file list 資料 (要過濾掉 *.php 與 .nuweb_* 與 symlink) */
    function get_real_file_list($page_dir, $work_dir)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 讀取目錄內的檔案與子目錄 */
        $handle = opendir($work_dir);
        $f_cnt = 0;
        $l = strlen(NUWEB_SYS_FILE);
        while ($sub_name = readdir($handle))
        {
            /* 過濾掉 . & .. */
            if (($sub_name == ".") || ($sub_name == ".."))
                continue;

            /* 整理出副檔名與真實檔案 path */
            $fe = strtolower(substr($sub_name, strrpos($sub_name, ".")));
            $f_path = str_replace("//", "/", $work_dir."/".$sub_name);

            /* 過濾掉 *.php 與 .nuweb_* 與 symlink */
            if (($fe == ".php") || (substr($sub_name, 0, $l) == NUWEB_SYS_FILE) || (is_link($f_path)))
                continue;

            /* 將此檔案 or 目錄加到 f_list 中 */
            $f_list[$f_cnt++] = str_replace($page_dir, "", $f_path);

            /* 如果是子目錄,就繼續往下層處理 */
            if (is_dir($f_path))
            {
                $sub_f_list = get_real_file_list($page_dir, $f_path);
                $cnt = count($sub_f_list);
                for ($i = 0; $i < $cnt; $i++)
                    $f_list[$f_cnt++] = $sub_f_list[$i];
            }
        }
        closedir($handle);
        return $f_list;
    }

    /* 取得目錄的 file list 資料 (只取單層) */
    function get_dir_file_list($dir_path)
    {
        /* 檢查 dir_path 參數,若不是目錄就不處理 */
        if ((empty($dir_path)) || (!is_dir($dir_path)))
            return false;
        if (substr($dir_path, -1) != "/")
            $dir_path .= "/";
        /* 檢查是否在子網站目錄內,若不是就不處理 */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if (substr($dir_path, 0, $l) !== $site_path)
            return false;
        $dir_page = substr($dir_path, $l);

        /* 搜尋所需的資料 */
        $rBegin = str_replace("\n", "\\n", REC_START.REC_BEGIN_PATTERN);
        $index_file = $dir_path.NUWEB_REC_PATH.DIR_INDEX."/current";
        $cmd = SEARCH_BIN_DIR."dbman -recbeg \"$rBegin\" -flag \"@_f:Normal\" -sort $index_file";

        /* 檢查 index 是否存在 */
        if (!file_exists($index_file))
            return false;

        /* 執行搜尋功能取得結果 */
        $fp = popen($cmd, "r");
        $rec_start = false;
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            $buf = trim($buf)."\n";
            if ($buf == "\n")
                continue;

            /* 判斷是否有新的 Record (Record 的啟始為 REC_START,第二行一開始必須是 REC_FIELD_START) */
            if ($buf == REC_START)
            {
                $rec_start = true;
                continue;
            }
            if (($rec_start == true) && ($buf[0] == REC_FIELD_START))
            {
                $page_path = NULL;
                $filename = NULL;
            }
            $rec_start = false;

            /* 檢查是否為 page_name 或 filename 欄位,若是就取出內容,其餘的都跳過 */
            if (strstr($buf, "@page_name:")  === $buf)
                $page_path = $dir_page.trim(substr($buf, 11));
            else if (strstr($buf, "@filename:") === $buf)
                $filename = trim(substr($buf, 10));
            else
                continue;

            /* 整理 file list */
            if (($page_path !== NULL) && ($filename !== NULL))
            {
                if ((substr($page_path, -1)) == "/")
                    $page_path = substr($page_path, 0, -1);
                $file_list[$page_path] = $filename;
            }
        }
        pclose($fp);

        return $file_list;
    }

    /* 取得子網站的 file list 資料 */
    function get_site_file_list($site_acn)
    {
        /* 檢查 site_acn 參數 */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".$site_acn."/";
        if ((empty($site_acn)) || (!is_dir($site_path)))
            return false;

        /* 取得子網站 index 位置,並檢查 index 是否存在 */
        $index_file = $site_path.NUWEB_INDEX_PATH."/current";
        if (!file_exists($index_file))
            return false;

        /* 用 egrep 找出 index 中所需資料 */
        $cmd = "/bin/egrep \"@_f:|@url:|@filename:\" $index_file";
        $fp = popen($cmd, "r");
        $site_url = "/".SUB_SITE_NAME."/";
        $l = strlen($site_url);
        $del = false;
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            $buf = trim($buf);
            if ($buf == "")
                continue;

            /* @_f:Normal 代表此筆資料為新的資料 */
            if ($buf == "@_f:Normal")
            {
                /* 新資料就將 url & filename 先清空 */
                $del = false;
                $url = NULL;
                $filename = NULL;
                continue;
            }
            /* @_f:Deleted 代表此筆資料已被刪除 */
            if ($buf == "@_f:Deleted")
                $del = true;

            /* 若此筆資料已被刪除,就不處理 */
            if ($del == true)
                continue;

            /* 檢查是否為 url 或 filename 欄位,若是就取出內容 */
            if (strstr($buf, "@url:") === $buf)
                $url = trim(substr($buf, 5));
            if (strstr($buf, "@filename:") === $buf)
                $filename = trim(substr($buf, 10));

            /* 整理 file list */
            if (($url !== NULL) && ($filename !== NULL))
            {
                /* 若 url 不是在子網站目錄下,就不處理 */
                if (substr($url, 0, $l) !== $site_url)
                    continue;
                /* 若 url 中有 .php 或 ? 或 & 代表是程式不是一般檔案,就不處理 */
                if ((strstr($url, ".php") !== false) || (strstr($url, "?") !== false) || (strstr($url, "&") !== false))
                    continue;

                $page_path = substr($url, $l);
                if ((substr($page_path, -1)) == "/")
                    $page_path = substr($page_path, 0, -1);
                $file_list[$page_path] = $filename;
            }
        }
        pclose($fp);

        return $file_list;
    }

    /* 從 record file 取出 work dir 的 file list 資料 */
    function get_file_list_by_rec($page_dir, $work_dir="")
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        if (empty($work_dir))
            $work_dir = $page_dir;

        $page_url = str_replace(WEB_ROOT_PATH, "", $page_dir);
        $page_url_len = strlen($page_url);

        $file_list = "";
        $l = strlen(NUWEB_SYS_FILE);
        /* 讀取 work_dir 目錄內的檔案與子目錄 */
        $handle = opendir($work_dir);
        while ($sub_name = readdir($handle))
        {
            /* 過濾掉 . & .. */
            if (($sub_name == ".") || ($sub_name == ".."))
                continue;

            $f_path = str_replace("//", "/", $work_dir."/".$sub_name);
            /* 如果不是子目錄,就不處理 (symlink 也不處理) */
            if ((!is_dir($f_path)) || (is_link($f_path)))
                continue;

            /* 如果是 record 目錄,就取出所有 record 資料,並整理出 file list 資料 */
            if ($sub_name."/" == NUWEB_REC_PATH)
            {
                $rec_handle = opendir($f_path);
                while ($rec_path = readdir($rec_handle))
                {
                    /* 如果是系統設定檔的 record (.nuweb_*.rec) 就不需要加入 */
                    if (substr($rec_path, 0, $l) == NUWEB_SYS_FILE)
                        continue;
                    /* 如果 record file 不是檔案就不處理 */
                    $rec_file = $f_path."/".$rec_path;
                    if (!is_file($rec_file))
                        continue;

                    $rec = rec2array($rec_file);
                    $url = get_url_by_rec_file($rec_file);
                    $filename = $rec[0]["filename"];
                    if ((empty($url)) || (empty($filename)) || (substr($url, 0, $page_url_len) != $page_url))
                        continue;
                    $path = substr($url, $page_url_len);
                    if (substr($path, -1) == "/")
                        $path = substr($path, 0, -1);
                    /* 檔案必須存在才加入 file list 中 */
                    if (file_exists($page_dir.$path))
                        $file_list .= "$path\t$filename\n";
                }
            }
            else
                $file_list .= get_file_list_by_rec($page_dir, $f_path);
        }
        closedir($handle);
        return $file_list;
    }

    /* 取得 file list 資料 */
    //function get_file_list($page_dir)
    //{
    //    if (substr($page_dir, -1) != "/")
    //        $page_dir .= "/";

        /* 取出 file.list 的資料 */
    //    $file_list_file = $page_dir.FILE_LIST;

    //    $file_list = @file($file_list_file);
    //    $cnt = count($file_list);
    //    $page_file_name = array();
    //    for ($i = 0; $i < $cnt; $i++)
    //    {
    //        list($filename, $title) =  explode("\t", trim($file_list[$i]));
    //        if (empty($filename) || empty($title))
    //            continue;
    //        if ($filename[strlen($filename) - 1] == "/")
    //            $filename = substr($filename, 0, -1);
    //        $page_file_name[$filename] = $title;
    //    }
    //    return $page_file_name;
    //}

    //function get_file_list_realname($page_dir)
    //{
    //    if (substr($page_dir, -1) != "/")
    //        $page_dir .= "/";

        /* 取出 file.list 的資料 */
    //    $file_list_file = $page_dir.FILE_LIST;

    //    $file_list = @file($file_list_file);
    //    $cnt = count($file_list);
    //    for ($i = 0; $i < $cnt; $i++)
    //    {
    //        list($filename, $title) =  explode("\t", trim($file_list[$i]));
    //        if (empty($filename) || empty($title))
    //            continue;
    //        if($filename[strlen($filename) - 1] == "/"){
	//	$filename = substr($filename, 0, strlen($filename) - 1);
        //    }
        //    if(($n = strrpos($filename, "/")) !== false){
	//	$page_file_name[substr($filename, 0, $n+1).$title] = substr($filename, $n+1);
        //    }else{
	//	$page_file_name[$title] = $filename;
	//    }
        //}
        //return $page_file_name;
    //}

    /* 將單筆資料寫入 file list 中 (新增) */
    //function write_file_list($page_dir, $path, $name)
    //{
    //    if (substr($page_dir, -1) != "/")
    //        $page_dir .= "/";

        /* 檢查 path 是否正確 */
    //    if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
    //        return false;
    //    if($path[strlen($path) - 1] == "/"){
    //        $path = substr($path, 0, strlen($path) - 1);
    //    }

        /* 將資料寫入 file list 中 */
    //    $file_list_file = $page_dir.FILE_LIST;
    //    $fp = fopen($file_list_file, "a");
    //    flock($fp, LOCK_EX);
    //    fputs($fp, $path."\t".$name."\n");
    //    flock($fp, LOCK_UN);
    //    fclose($fp);
    //}

    /* 重新寫入新的 file list 資料檔 */
    //function rewrite_file_list($page_dir, $page_file_name)
    //{
    //    if (substr($page_dir, -1) != "/")
    //        $page_dir .= "/";

        /* 儲存新的 file list 資料檔 */
    //    $flist_buf = "";
    //    foreach($page_file_name as $path => $name){
	//    if(!file_exists($page_dir.$path)){
	//	continue;
	//    }
        //    if($path[strlen($path) - 1] == "/"){
	//	$path = substr($path, 0, strlen($path) - 1);
        //    }
        //    $flist_buf .= $path."\t".$name."\n";
	//}
        //$file_list_file = $page_dir.FILE_LIST;
        //$file_list_file_new = $file_list_file.".new";
        //$fp = fopen($file_list_file_new, "w");
        //flock($fp, LOCK_EX);
        //fputs($fp, $flist_buf);
        //flock($fp, LOCK_UN);
        //fclose($fp);

        /* 若發現新的 file list 是空的,就進行重建 file list */
        //if (real_filesize($file_list_file_new) === 0)
        //{
            //unlink($file_list_file_new);
            //rebuild_file_list($page_dir);
        //}
        //else
        //{
            /* 刪除舊的 file list,更新成新的 file list */
        //    if (file_exists($file_list_file))
        //        unlink($file_list_file);
        //    rename($file_list_file_new, $file_list_file);
        //}
    //}

    /* 從 file list 檔案中更新單筆資料 */
    //function update_file_list($page_dir, $path, $name)
    //{
	//Global $fe_type;
        //if (substr($page_dir, -1) != "/")
        //    $page_dir .= "/";

        /* 檢查 path 是否正確 */
        //if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
        //    return false;

        //$page_file_name = get_file_list($page_dir);
        //if (empty($page_file_name))
        //    return false;

	//if($path[strlen($path) - 1] == "/"){
	//	$path = substr($path, 0, strlen($path) - 1);
	//}

	//$n = strrpos($page_file_name[$path], ".");
	//if($n !== false && $n != 0 && $fe_type[strtolower(substr($page_file_name[$path], $n))] == HTML_TYPE){
	//	$append_file_realname = substr($page_file_name[$path], 0, $n+1)."files";
	//	$append_file_realname_path = $append_file_realname;
	//	$n = strrpos($path, "/");
	//	$contain_dir = "";
	//	if($n !== false){
	//		$contain_dir = substr($path, 0, $n+1);
	//		$append_file_realname_path = $contain_dir.$append_file_realname;
	//	}
	//	$realname_list = get_file_list_realname($page_dir);
	//	if(array_key_exists($append_file_realname_path, $realname_list) && ($n = strrpos($name, ".")) !== false){
	//		if($n!=0)
	//			$page_file_name[$contain_dir.$realname_list[$append_file_realname_path]] = substr($name, 0, $n+1)."files";
	//	}
	//}
        //if ($page_file_name[$path] != $name)
        //{
        //    $page_file_name[$path] = $name;
        //    rewrite_file_list($page_dir, $page_file_name);
        //}
        //return true;
    //}

    /* 從 file list 檔案中刪除單筆資料 */
    //function del_file_list($page_dir, $path)
    //{
    //    if (substr($page_dir, -1) != "/")
    //        $page_dir .= "/";

        /* 檢查 path 是否正確 */
        //if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
        //    return false;

        //$page_file_name = get_file_list($page_dir);
        //if (empty($page_file_name))
        //    return false;

	//if($path[strlen($path) - 1] == "/"){
	//	$path = substr($path, 0, strlen($path) - 1);
	//}

    //    if (!empty($page_file_name[$path]))
    //    {
    //        unset($page_file_name[$path]);
    //        rewrite_file_list($page_dir, $page_file_name);
    //    }
    //    return true;
    //}

    /* 刪除 file list 陣列中的項目 */
    //function del_file_list_item(&$page_file_name, $path)
    //{
	//if($path[strlen($path) - 1] == "/"){
	//	$path = substr($path, 0, strlen($path) - 1);
	//}
        //if (!empty($page_file_name[$path]))
        //    unset($page_file_name[$path]);
    //}

    /* 取得真實檔案名稱 */
    function get_file_name($page_dir, $path, $page_file_name="")
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 整理 path (最後不可有 "/") */
        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 如果檔案不存在,就回傳 false */
        if (!file_exists($page_dir.$path))
            return false;

        /* 找出 record file,若 record file 存在就直接取出 filename (不透過 file list) */
        $rec_file = get_file_rec_path($page_dir.$path);
        if ($rec_file != false)
        {
            /* 取出 record file 資料 */
            /* 2016/1/12 修改,要先檢查 filename 欄位是否存在,有取得資料才回傳 */
            $rec = rec2array($rec_file);
            if ((isset($rec[0]["filename"])) && (!empty($rec[0]["filename"])))
                return $rec[0]["filename"];
        }

        /* 若有傳入 page_file_name 資料,且可找到真實檔名就直接傳回 */
        if (isset($page_file_name[$path]))
            return $page_file_name[$path];

        /* 如果沒找到真實檔案名稱,就直接回傳儲存的檔名 */
        $n = strrpos($path, "/");
        if ($n === false)
            return $path;
        return substr($path, $n+1);
        /* 2013/4/17 修改,如果沒找到真實檔案名稱,就回傳空字串 */
        //return "";
    }

	/* 取得真實檔案名稱 */
    function get_file_md5($page_dir, $path)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 整理 path (最後不可有 "/") */
        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 如果檔案不存在,就回傳 false */
        if (!file_exists($page_dir.$path))
            return false;

        /* 找出 record file,若 record file 存在就直接取出 filename (不透過 file list) */
        $rec_file = get_file_rec_path($page_dir.$path);
        if ($rec_file != false)
        {
            /* 取出 record file 資料 */
            $rec = rec2array($rec_file);
            return $rec[0]["md5"];
        }

        /* 如果沒找到真實檔案名稱,就直接回傳儲存的檔名 */
        $n = strrpos($path, "/");
        if ($n === false)
            return $path;
        return substr($path, $n+1);
    }

    /* 檢查檔案名稱是否存在 (不存在傳回 false,存在傳回實際所在的 path) */
    function filename_exists($page_dir, $path, $name)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
	if (substr($path, - 1) == "/")
            $path = substr($path, 0, -1);

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 讀取目錄的 file list */
        $flist = get_dir_file_list($page_dir.$path);
        if ($flist == false)
            return false;
        foreach($flist as $f_path => $f_name)
        {
            if ($f_name === $name)
                return $f_path;
        }
        return false;
    }

    /* 取得序號的檔案名稱 (檔名重覆時使用) */
    function get_seq_name($page_dir, $path, $name)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        if (substr($path, - 1) == "/")
            $path = substr($path, 0, -1);

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 讀取目錄的 file list */
        $flist = get_dir_file_list($page_dir.$path);
        if ($flist == false)
            return false;

        /* 將 name 分解成 fn & fe */
        $n = strrpos($name, ".");
        $fe = NULL;
        if ($n !== false)
        {
            $fn = substr($name, 0, $n);
            $fe = substr($name, $n);
        }
        else
            $fn = $name;

        /* 檢查 name 是否存在若已存在就將 name 加上 id,一直找到 name 不存在為止 */
        $new_name = $name;
        $id = 1;
        while (1)
        {
            $name_exist = false;
            foreach($flist as $f_path => $f_name)
            {
                if ($f_name === $new_name)
                {
                    $name_exist = true;
                    break;
                }
            }
            if ($name_exist == false)
                return $new_name;
            $id_num = sprintf("%02d", $id++);
            $new_name = $fn."_".$id_num.$fe;
        }
        return false;
    }

    /* 找出 file 所附屬的 record file 位置 */
    function get_file_rec_path($file_path)
    {
        if (!file_exists($file_path))
            return false;

        /* 檢查 file_path 最後是否為 /index.html (代表要找目錄而非檔案的 record file),若是必須先濾掉 */
        if (substr($file_path, -11) == "/".DEF_HTML_PAGE)
            $file_path = substr($file_path, 0, -11);

        if (is_dir($file_path))
        {
            $file_path .= "/";
            $rec_dir = $file_path.NUWEB_REC_PATH;
            $rec_file = $rec_dir.DIR_RECORD;
        }
        else
        {
            $n = strrpos($file_path, "/");
            $dir_path = substr($file_path, 0, $n);
            $file_name = substr($file_path, $n+1);
            $rec_dir = $dir_path."/".NUWEB_REC_PATH;
            $rec_file = $rec_dir.$file_name.".rec";

            /* 若 record file 存在,就直接回傳,否則必須檢查是否為附屬檔,若是附屬檔必須取得原始檔的 record file */
            if (file_exists($rec_file))
                return $rec_file;

            /* 若是縮圖檔必須取得原圖檔的 record file */
            $tn_fe_len = strlen(TN_FE_NAME);
            if (substr($file_path, -$tn_fe_len) === TN_FE_NAME)
            { 
                $big_tn_fe_len = strlen(BIG_TN_FE_NAME);
                $med_tn_fe_len = strlen(MED_TN_FE_NAME);
                $med2_tn_fe_len = strlen(MED2_TN_FE_NAME);
                $src_tn_fe_len = strlen(SRC_TN_FE_NAME);
                if (file_exists(substr($file_path, 0, -$tn_fe_len)))
                    $file_path = substr($file_path, 0, -$tn_fe_len);
                else if (substr($file_path, -$big_tn_fe_len) === BIG_TN_FE_NAME)
                    $file_path = substr($file_path, 0, -$big_tn_fe_len);
                else if (substr($file_path, -$med_tn_fe_len) === MED_TN_FE_NAME)
                    $file_path = substr($file_path, 0, -$med_tn_fe_len);
                else if (substr($file_path, -$med2_tn_fe_len) === MED2_TN_FE_NAME)
                    $file_path = substr($file_path, 0, -$med2_tn_fe_len);
                else if (substr($file_path, -$src_tn_fe_len) === SRC_TN_FE_NAME)
                    $file_path = substr($file_path, 0, -$src_tn_fe_len);
            }

            /* 2014/6/13 修改,若是在 .nuweb_media 內的影片或 .nuweb_pdf 內的 PDF 檔,必須取得原始檔案的 record file */
            $n1 = strrpos($dir_path, "/");
            $dir_name = substr($dir_path, $n1+1);
            $dir_path = substr($dir_path, 0, $n1);
            if (($dir_name."/" === NUWEB_MEDIA_PATH) || ($dir_name."/" === NUWEB_PDF_PATH))
            {
                do {
                    $m = strrpos($file_name, ".");
                    if ($m == false)
                        return false;
                    $file_name = substr($file_name, 0, $m);
                    $file_path = $dir_path."/".$file_name;
                } while(!file_exists($file_path));
            }

            $n = strrpos($file_path, "/");
            $rec_dir = substr($file_path, 0, $n+1).NUWEB_REC_PATH;
            $rec_file = $rec_dir.substr($file_path, $n+1).".rec";
        }

        if (!file_exists($rec_file))
            return false;

        return $rec_file;
    }

    /* 取得目錄權限的資訊 */
    function get_page_right_info($page_dir, $path, $browse_list=NULL)
    {
        Global $reg_conf;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 2014/12/16 新增,若是在 .nuweb_msg 目錄內,則所有人可瀏覽,直接設為 ALLOW_ALL & DENY_NONE */
        if (strstr($path, NUWEB_MSG_PATH) !== false)
        {
            $right_info["allow"] = ALLOW_ALL;
            $right_info["deny"] = DENY_NONE;
            return $right_info;
        }

        /* 取得 CS 註冊資料,整理 site_id (要加到 allow 欄位中,主要要提供讓此網站的管理者可以搜尋到此筆資料用) */
        if (!isset($reg_conf))
            $reg_conf = read_conf(REGISTER_CONFIG);
        $path_list = explode("/", str_replace(WEB_ROOT_PATH, "", $page_dir.$path));
        $site_name = $path_list[1];
        if ($site_name == SUB_SITE_NAME)
            $site_id = $path_list[2].".".$reg_conf["acn"];

        /* 讀取權限檔資料 */
        /* 2015/2/11 修改,已不再使用 .nuweb_def,改從 record 內取得權限資料 */
        //$info = read_nuweb_def($page_dir, $path);
        if (!empty($browse_list))
            $info["browse"] = $browse_list;
        else
            $info = get_rec_right_info($page_dir.$path);

        /* 將瀏覽權限中的 * 轉換成 ALLOW_ALL,將 + 轉換成 ALLOW_GROUP_USER */
        if (!empty($info["browse"]))
        {
            $info["browse"] = str_replace(ALL_USER, ALLOW_ALL, $info["browse"]);
            $info["browse"] = str_replace(SITE_MEMBER, ALLOW_GROUP_USER, $info["browse"]);
        }

        /* 整理權限資料 */
        $right_info["allow"] = "";
        $right_info["deny"] = "";
        /* 2014/3/13 新增,優先檢查 strong_deny */
        /* 2015/2/13 修改,檢查是否為 strong_deny 或若沒設定瀏覽名單(且沒設密碼)就代表無人可瀏覽 (但網站管理者可瀏覽,所以需加上 site_id 來提供網站管理者搜尋) */
        if (($info["strong_deny"] == YES) || ((empty($info["browse"])) && (empty($info["pwd"]))))
        {
            $right_info["allow"] = ALLOW_NONE.",$site_id";
            $right_info["deny"] = DENY_ALL;
            return $right_info;
        }
        /* 檢查若有瀏覽名單,就直接將瀏覽名單放入 allow 中 */
        if (!empty($info["browse"]))
        {
            /* 檢查瀏覽名單中是否有 site_id,若沒有就加入 */
            if (strstr($info["browse"], $site_id) == false)
                $info["browse"] .= ",$site_id";
            $right_info["allow"] = $info["browse"];
            $right_info["deny"] = DENY_NONE;
        }
        /* 檢查是否有設密碼 */
        if (!empty($info["pwd"]))
        {
            /* 若沒有名單就將 allow 設為 ALLOW_NONE */
            if (empty($right_info["allow"]))
                $right_info["allow"] = ALLOW_NONE.",$site_id";
            $right_info["deny"] = DENY_PASSWORD;
        }

        return $right_info;

        //else if (!empty($info["b_deny"]))
        //{
        //    /* 2. 判斷是否有設定 DENY_USER */
        //    $right_info["allow"] = ALLOW_NONE;
        //    if ($info["b_deny"] == "*")
        //        $right_info["deny"] = DENY_ALL;
        //    else
        //    {
        //        $list_item = explode(" ", strtolower($info["b_deny"]));
        //        $list_cnt = count($list_item);
        //        for ($i = 0; $i < $list_cnt; $i++)
        //        {
        //            if ($i > 0)
        //                $right_info["deny"] .= ",";
        //            if ($list_item[$i] == SITE_MEMBER)
        //                $right_info["deny"] .= DENY_GROUP_USER;
        //            else
        //                $right_info["deny"] .= $list_item[$i];
        //        }
        //    }
        //}
        //else
        //{
        //    /* 3. 判斷 ALLOW_USER */
        //    $right_info["deny"] = DENY_NONE;
        //    /* 2014/8/25 新增,檢查網站是否公開,若不是公開,就先設定 allow 為 ALLOW_GROUP_USER */
        //    $url = str_replace(WEB_ROOT_PATH, "", $page_dir.$path);
        //    $right_info["allow"] = "";
        //    $site_public = chk_public_site($url);
        //    if ($site_public == false)
        //        $right_info["allow"] = ALLOW_GROUP_USER;
        //    if (empty($info["b_allow"]) || ($info["b_allow"] == "*"))
        //    {
        //        if (empty($right_info["allow"]))
        //            $right_info["allow"] = ALLOW_ALL;
        //    }
        //    else
        //    {
        //        $list_item = explode(" ", strtolower($info["b_allow"]));
        //        $list_cnt = count($list_item);
        //        for ($i = 0; $i < $list_cnt; $i++)
        //        {
        //            /* 2014/8/26 修改,若是不公開網站,且 ALLOW_USER 有設定 SITE_MEMBER (+),就不必再設定 (因為前面檢查網站不公開時已設定過 ALLOW_GROUP_USER) */
        //            if (($list_item[$i] == SITE_MEMBER) && ($site_public == false))
        //                continue;
        //            if (!empty($right_info["allow"]))
        //                $right_info["allow"] .= ",";
        //            if ($list_item[$i] == SITE_MEMBER)
        //                $right_info["allow"] .= ALLOW_GROUP_USER;
        //            else
        //                $right_info["allow"] .= $list_item[$i];
        //        }
        //    }
        //}

        /* 取得 CS 註冊資料 */
        //if (!isset($reg_conf))
        //    $reg_conf = read_conf(REGISTER_CONFIG);

        /* 2014/9/19 修改,不可直接使用 url (因為 url 僅在判斷 ALLOW_USER 才會產生,其他狀況不會產生,就會發生問題) */
        //$path_list = explode("/", $url);
        //$path_list = explode("/", str_replace(WEB_ROOT_PATH, "", $page_dir.$path));
        //$site_name = $path_list[1];
        ///* 如果是子網站,就設定子網站帳號到 allow 中 */
        //if ($site_name == SUB_SITE_NAME)
        //    $right_info["allow"] .= ",".$path_list[2].".".$reg_conf["acn"];

        //return $right_info;
    }

    /* 將摘要區塊進行轉換 */
    function replace_describe($content, $start_tag="", $end_tag="")
    {
        return str_replace(DESCRIBE_POSTFIX, $end_tag, str_replace(DESCRIBE_PREFIX, $start_tag, $content));
    }

    /* 將摘要區塊轉換到 div 區塊中 */
    function replace_describe_div($content)
    {
        return replace_describe($content, DESCRIBE_DIV_START, DESCRIBE_DIV_END);
    }

    /* 從 record 中取出摘要內容 (若 description 欄位沒資料,就從 content 欄位中取出一段內容) */
    function get_rec_description($rec)
    {
        if (!empty($rec["description"]))
            $description = $rec["description"];
        else
            $description = mb_substr($rec["content"], 0, DESCRIBE_LEN, 'UTF-8');
        return $description;
    }

    /* 從 html 中取出摘要內容 (若沒有 description 資料,就從網頁中取出一段內容) */
    function get_html_description($content)
    {
        $description = "";
        $p = $content;
        $prefix_len = strlen(DESCRIBE_PREFIX);
        /* 將 DESCRIBE_PREFIX 與 DESCRIBE_POSTFIX 之間的內容取出,就是摘要資料 */
        if (($n = strpos($p, DESCRIBE_PREFIX)) !== false)
        {
            $p =  substr($p, $n + $prefix_len);
            $n = strpos($p, DESCRIBE_POSTFIX);
            $description = substr($p, 0, $n);
        }
        /* 若沒有摘要資料就取出部分網頁內容當成摘要 */
        if (empty($description))
            $description = mb_substr(html2text($content), 0, DESCRIBE_LEN, 'UTF-8');
        return $description;
    }

    /* 取出要加到 index 的 file list */
    function get_index_list($page_dir, $page_path="")
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        $content = "";
        if (!empty($page_path))
        {
            if (substr($page_path, -1) != "/")
                $page_path .= "/";
            $page_path_len = strlen($page_path);

            /* 先取得 page_path 本身的資料 (因為用 get_file_list_by_rec 函數只會取得 page_path 目錄內的 file list 資料,不包含 page_path 本身) */
            $rec_file = get_file_rec_path($page_dir.$page_path);
            if ($rec_file === false)
                return NULL;
            $rec = rec2array($rec_file);
            $filename = $rec[0]["filename"];
            $content = substr($page_path, 0, -1)."\t$filename\n";
        }

        /* 取得 page_path 目錄內的 file list 資料 */
        $content .= get_file_list_by_rec($page_dir, $page_dir.$page_path);
        if (empty($content))
            return NULL;
        $list = explode("\n", $content);
        $cnt = count($list);
        for ($i = 0; $i < $cnt; $i++)
        {
            if (empty($list[$i]))
                continue;
            list($path, $name) = explode("\t", trim($list[$i]));
            if ((empty($path)) || (empty($name)))
            /* 先取得 page_path 本身的資料 (因為用 get_file_list_by_rec 函數只會取得 page_path 目錄內的 file list 資料,不包含 page_path 本身) */
            $rec_file = get_file_rec_path($page_dir.$page_path);
            if ($rec_file === false)
                return NULL;
            $rec = rec2array($rec_file);
            $filename = $rec[0]["filename"];
            $content = substr($page_path, 0, -1)."\t$filename\n";
        }

        /* 取得 page_path 目錄內的 file list 資料 */
        $content .= get_file_list_by_rec($page_dir, $page_dir.$page_path);
        if (empty($content))
            return NULL;
        $list = explode("\n", $content);
        $cnt = count($list);
        for ($i = 0; $i < $cnt; $i++)
        {
            if (empty($list[$i]))
                continue;
            list($path, $name) = explode("\t", trim($list[$i]));
            if ((empty($path)) || (empty($name)))
                continue;
            $page_file_name[$path] = $name;
        }

        $n = 0;
        foreach($page_file_name as $path => $name)
        {
            /* 如果有傳入 page_path 就只取出 page_path 目錄內的資料 */
            if ((!empty($page_path)) && ($page_path !== $path."/") && (substr($path, 0, $page_path_len) != $page_path))
            {
                unset($page_file_name[$path]);
                continue;
            }

            /* 檢查只要是 *.files 或 .sync 的資料,都不放到 index 中 */
            if ((substr($name, -6) == ".files") || ($name == SYNC_DIR))
            {
                $del_path[$n++] = $path."/";
                unset($page_file_name[$path]);
                continue;
            }

            /* 檢查檔案不存在,就不放到 index 中 */
            if (!file_exists($page_dir.$path))
            {
                unset($page_file_name[$path]);
                continue;
            }

            /* 過濾掉不放入 index 的資料 */
            for ($i = 0; $i < $n; $i++)
            {
                if (strstr($path, $del_path[$i]) === $path)
                {
                    unset($page_file_name[$path]);
                    break;
                }
            }
        }
        return $page_file_name;
    }

    /* 取得 Audio 檔的相關資訊 */
    function get_audio_info($audio_file)
    {
        $audio_info = array();
        $cmd = SYS_FFMPEG. " -i $audio_file 2>&1";
        $fp = popen($cmd, "r");
        /* 分離與拆解資訊 */
        while ($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            /* 取出 Audio 的長度與 bitrate 等資訊 */
            if (strstr($buf, "Duration:"))
            {
                $item = explode(",", $buf);
                $cnt = count($item);
                for ($i = 0; $i < $cnt; $i++)
                {
                    list($name, $value) = explode(":", $item[$i], 2);
                    $audio_info[trim($name)] = trim($value);
                }
            }
        }
        pclose($fp);

        return $audio_info;
    }
    /* 取得 video 的 type field */
    function get_video_field($page_path, $fe)
    {
        /* 檢查並設定縮圖 url */
        $tn_image = $page_path.TN_FE_NAME;
        $type_field["thumbs"] = "";
        if (file_exists($tn_image))
            $type_field["thumbs"] = substr($tn_image, strrpos($tn_image, "/")+1);

        /* 檢查並設定 flv 檔 url */
        $type_field["flv"] = "";
        if ($fe == ".flv")
            $type_field["flv"] = substr($page_path, strrpos($page_path, "/")+1);
        else
        {
            $n = strrpos($page_path, "/");
            $media_dir = substr($page_path, 0, $n+1).NUWEB_MEDIA_PATH;
            $flv_file = $media_dir.substr($page_path, $n+1).".flv";
            if (file_exists($flv_file))
                $type_field["flv"] = substr($flv_file, strrpos($flv_file, "/")+1);
        }

        /* 取得 Video File 的資訊 */
        $video_info = get_video_info($page_path);
        list($h, $m, $s) = explode(":", $video_info["Duration"]);
        $type_field["duration"] = (($h * 60) + $m) * 60 + $s;
        $type_field["width"] = $video_info["width"];
        $type_field["height"] = $video_info["height"];
        if ($video_info["height"] >= 720)
            $type_field["quality"] = "hd";
        else
            $type_field["quality"] = "general";
        return $type_field;
    }

    /* 取得 audio 的 type field */
    function get_audio_field($page_path)
    {
        /* 取得 Audio File 的資訊 */
        $audio_info = get_audio_info($page_path);
        list($h, $m, $s) = explode(":", $audio_info["Duration"]);
        $type_field["duration"] = (($h * 60) + $m) * 60 + $s;
        return $type_field;
    }

    /* 取得 image 的 type field */
    function get_image_field($page_path)
    {
        /* 取得縮圖位置 */
        $tn_image = $page_path.TN_FE_NAME;
        $type_field["thumbs"] = "";
        if (file_exists($tn_image))
            $type_field["thumbs"] = substr($tn_image, strrpos($tn_image, "/")+1);

        /* 取得圖片尺寸 */
        $size = @getimagesize($page_path);
        if ($size)
        {
            $type_field["width"] = $size[0];
            $type_field["height"] = $size[1];
        }
        else
        {
            $type_field["width"] = 0;
            $type_field["height"] = 0;
        }
        return $type_field;
    }

    /* 取得 document 的 type field */
    function get_document_field($page_path, $fe)
    {
        $type_field["content"] = "";
	$type_field["description"] = "";
        if (SYS_OS == "Ubuntu")
        {
            /* 2014/6/25 修改,取得文字內容都改由 PDF 檔抽取 (除 .tex 檔由 detex 抽取,因 .tex 檔沒有轉 pdf 檔) */
            if ($fe == ".pdf")
                $cmd = SYS_PDFTOTEXT." -nopgbrk $page_path -";
            else if ($fe == ".tex")
            {
                /* 先取出檔案內容,並檢查是否為 UTF-8 or UTF-16 or UFT-16BE,若都不是就先假設為 Big5,將內容轉成 UTF-8,再存到暫存檔中 */
                $buf = implode("", @file($page_path));
                if (substr($buf, 0, 3) == UTF8_START_CODE)
                    $text_content = substr($buf, 3);
                else if (substr($buf, 0, 2) == UTF16_START_CODE)
                    $text_content = iconv("UCS-2LE", "UTF-8//IGNORE", substr($buf, 2));
                else if (substr($buf, 0, 2) == UTF16BE_START_CODE)
                    $text_content = iconv("UCS-2BE", "UTF-8//IGNORE", substr($buf, 2));
                else
                    $text_content = iconv("Big5", "UTF-8//IGNORE", $buf);
                $tmp_file = tempnam(NUWEB_TMP_DIR, "tex_");
                $fp = fopen($tmp_file, "w");
                flock($fp, LOCK_EX);
                fputs($fp, $text_content);
                flock($fp, LOCK_UN);
                fclose($fp);
                /* 若 detex 檔存在就用 detex 抽取文字內容,若不存在就直接輸出檔案內容 */
                if (file_exists(LATEX2TEXT_PROG))
                    $cmd = LATEX2TEXT_PROG." $tmp_file";
                else
                    $cmd = "/bin/cat $tmp_file";
            }
            else
            {
                $n = strrpos($page_path, "/");
                $pdf_dir = substr($page_path, 0, $n+1).NUWEB_PDF_PATH;
                $pdf_file = $pdf_dir.substr($page_path, $n+1).".pdf";
                if (file_exists($pdf_file))
                {
                    $cmd = SYS_PDFTOTEXT." -nopgbrk $pdf_file -";
                    $type_field["pdf"] = substr($pdf_file, strrpos($pdf_file, "/")+1);
                }
                else
                {
                    /* 2014/7/22 新增,若 pdf 檔不存在,必須使用原本的方式 (MSOFFICE_EXTRACT) 轉出文字內容 */
                    /* 2015/7/14 修改,因部份檔案會發生卡住狀況,改成將 STDIN 改用 page_path 這個 file,不使用 pipes[0] */
                    //$process = proc_open(MSOFFICE_EXTRACT, array(0=>array("pipe", "r"), 1=>array("pipe", "w"), 2=>array("file", "/dev/null", "a")), $pipes, NULL, array('LANG'=>'zh_TW.UTF-8', 'LANGUAGE'=>'zh_TW:'));
                    $process = proc_open(MSOFFICE_EXTRACT, array(0=>array("file", $page_path, "r"), 1=>array("pipe", "w"), 2=>array("file", "/dev/null", "a")), $pipes, NULL, array('LANG'=>'zh_TW.UTF-8', 'LANGUAGE'=>'zh_TW:'));
                    if (is_resource($process))
                    {
                        //fwrite($pipes[0], file_get_contents($page_path));
                        //fclose($pipes[0]);
                        $type_field["content"] = mb_substr(stream_get_contents($pipes[1]), 0, CONTENT_LEN, 'UTF-8');
                        $type_field["description"] = mb_substr($type_field["content"], 0, DESCRIBE_LEN, 'UTF-8');
                        fclose($pipes[1]);
                        proc_close($process);
                        return $type_field;
                    }
                    else
                        return $type_field;
                }
            }
        }
        else
            $cmd = DOC_TO_TEXT_PROG." --decode_file $page_path --decode_type ".substr($fe, 1);
        $fp = popen($cmd, "r");
        $content = "";
        while ($buf = fgets($fp, MAX_BUFFER_LEN))
            $content .= $buf;
        pclose($fp);
        if ((!empty($tmp_file)) && (file_exists($tmp_file)))
            unlink($tmp_file);
        $type_field["content"] = mb_substr($content, 0, CONTENT_LEN, 'UTF-8');
        /* 取出一段內容當成描述資料 */
        $type_field["description"] = mb_substr($type_field["content"], 0, DESCRIBE_LEN, 'UTF-8');
        return $type_field;
    }

    /* 取得 text 的 type field */
    function get_text_field($page_path)
    {
        $buf = implode("", @file($page_path));
        /* 檢查是否為 UTF-8 or UTF-16 or UFT-16BE,若都不是就先假設為 Big5,將內容轉成 UTF-8 */
        if (substr($buf, 0, 3) == UTF8_START_CODE)
            $text_content = substr($buf, 3);
        else if (substr($buf, 0, 2) == UTF16_START_CODE)
            $text_content = iconv("UCS-2LE", "UTF-8//IGNORE", substr($buf, 2));
        else if (substr($buf, 0, 2) == UTF16BE_START_CODE)
            $text_content = iconv("UCS-2BE", "UTF-8//IGNORE", substr($buf, 2));
        else
            $text_content = iconv("Big5", "UTF-8//IGNORE", $buf);
        $type_field["content"] = mb_substr($text_content, 0, CONTENT_LEN, 'UTF-8');
        /* 取出一段內容當成描述資料 */
        $type_field["description"] = mb_substr($type_field["content"], 0, DESCRIBE_LEN, 'UTF-8');
        return $type_field;
    }

    /* 取得 str 中 title 資料 */
    function get_title($str)
    {
        if (strstr($str, TITLE_PREFIX) !== $str)
            return false;

        $l = strlen(TITLE_PREFIX);
        $title = substr($str, $l, strpos($str, TITLE_POSTFIX)-$l);
        return $title;
    }

    /* 取得 html 的 type field */
    function get_html_field($page_path)
    {
        $buf = @file($page_path);
	if(isset($buf[0]))
	        $tmp_buf = trim($buf[0]);
	else
		$tmp_buf = "";
        /* 取出 Title */
        /* 2015/10/14 修改,沒抓到 title 時,將 title 欄位設為空字串 */
        $title = get_title($tmp_buf);
        if ($title != false)
        {
            $type_field["title"] = $title;
            $l = strlen(TITLE_POSTFIX);
            $n = strpos($buf[0], TITLE_POSTFIX);
            $buf[0] = substr($buf[0], $n+$l);
        }
        else
            $type_field["title"] = "";
	$buf = implode("", $buf);
	$offset_t = 0;
	$second_off_t = 0;
	$type_field["images"] = array();
	while(($offset_t = strpos($buf, "<img", $offset_t)) !== FALSE){
		$second_off_t = $offset_t;
		$offset_t = strpos($buf, "src=\"", $offset_t);
		if(($offset_t === FALSE) || (strpos($buf, "data:image/", $offset_t) !== FALSE)){
			$offset_t = $second_off_t + 4;
			continue;
		}
		$offset_t += 5;
		$second_off_t = strpos($buf, "\"", $offset_t);
		if($second_off_t !== FALSE){
			$per_image = substr($buf, $offset_t, $second_off_t - $offset_t);
			if(!empty($per_image))
				array_push($type_field["images"], $per_image);
		}
	}
	$type_field["images"] = implode(",", $type_field["images"]);
        $type_field["content"] = mb_substr(html2text(replace_describe($buf)), 0, CONTENT_LEN, 'UTF-8');
        /* 整理出 html 檔的描述資料 */
        $type_field["description"] = get_html_description($buf);
        return $type_field;
    }

    /* 取得 link 的 type field */
    function get_link_field($page_path)
    {
        /* 取得 link 檔內容 */
        $link_data = read_conf($page_path);
        foreach($link_data as $key => $value)
        {
            /* 若 key 是空的,或開頭是 [ 或 # 就跳過 */
            if ((empty($key)) || (substr($key, 0, 1) == "[") || (substr($key, 0, 1) == "#"))
                continue;
            /* 若 key 是 url 就改成 link_url */
            $key = strtolower($key);
            if ($key == "url")
                $key = "link_url";
            /* 設定相關資料到 record 中 */
            $type_field[$key] = $value;
        }

        return $type_field;
    }

    /* 記錄預設的基本 Record */
    function write_def_record($page_dir, $path, $name="", $rewrite=false, $page_file_name="", $rebuild=false, $hidden=false, $clean_cnt=false, $sync=false)
    {
        Global $uacn, $fe_type, $login_user;

        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* path 最後不可有 '/' */
        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 檢查是否在特殊目錄內 (.nuweb_*, /db/, /attach/) 或是縮圖 (*.thumbs.jpg),若是就不處理 */
        /* 2015/3/17 修改,若是 .nuweb_forum 目錄就不跳過 */
        /* 2015/8/7 修改,若是 .nuweb_public 目錄就不跳過 */
        $dir_path = $path."/";
        if (((strstr($path, NUWEB_SYS_FILE) !== false) && (strstr($path, SITE_FORUM_PATH) == false) && (strstr($path, SITE_PUBLIC_PATH) == false)) || (strstr($dir_path, "/db/") !== false) || (strstr($dir_path, "/attach/") !== false) || (substr($path, -strlen(TN_FE_NAME)) === TN_FE_NAME))
            return false;

        /* 若 page path 不存在就不處理 */
        $page_path = $page_dir.$path;
        if (!file_exists($page_path))
            return false;

        $url = str_replace(WEB_ROOT_PATH, "", $page_path);
        $n = strrpos($url, "/");
        $page_name = substr($url, $n+1);

        $o_dir_type = "";

        /* 檢查是目錄 or 檔案,並找出 record file 位置 */
        if (is_dir($page_path))
        {
            $o_dir_type = get_nuweb_type($page_path."/");
            $rec_dir = $page_path."/".NUWEB_REC_PATH;
            $rec_file = $rec_dir.DIR_RECORD;
            /* 2015/6/4 新增,取得上層目錄的 record 位置 (為了檢查目錄的 dir_set 參數使用) */
            $dir_rec_file = substr($page_path, 0, strrpos($page_path, "/")+1).NUWEB_REC_PATH.DIR_RECORD;
        }
        else
        {
            $n = strrpos($page_path, "/");
            $rec_dir = substr($page_path, 0, $n+1).NUWEB_REC_PATH;
            $rec_file = $rec_dir.substr($page_path, $n+1).".rec";
            /* 2015/6/4 新增,取得檔案所在目錄的 record 位置 (為了檢查目錄的 dir_set 參數使用) */
            $dir_rec_file = $rec_dir.DIR_RECORD;
        }

        /* 檢查 record 檔是否存在 */
        if (file_exists($rec_file))
        {
            $new_rec = false;

            /* 如果不必覆寫,就直接更新到 index 中 */
            if ($rewrite != true)
            {
                /* rebuild 為 true,代表正在重建 index,因此直接用 rput */
                if ($rebuild == true)
                {
                    rec_put($rec_file, $url, $rebuild);

                    /* 若是目錄就檢查是否有 fun.rec 資料,若有也必須加到 index 中 */
                    $fun_rec = $rec_dir.FUN_RECORD;
                    if ((is_dir($page_path)) && (file_exists($fun_rec)))
                        rec_put($fun_rec, $url, $rebuild, true);
                }
                else
                    rec_update($rec_file, $url);
                return false;
            }

            /* 2015/4/24 新增,若 clean_cnt 為 true 時,就先將 record file 內的 cnt 資料先清空 (包括權限相關資料)*/
            if ($clean_cnt == true)
                clean_rec_cnt($rec_file);

            /* 需要覆寫時,先取出原 record file 資料 */
            $rec_buf = rec2array($rec_file);
            $rec = $rec_buf[0];
        }
        else
            $new_rec = true;

        /* 如果 rewrite == true,且沒有傳入 name,就取出真實的 file name */
        if (($rewrite == true) && (empty($name)))
            $name = get_file_name($page_dir, $path, $page_file_name);

        /* 若找不到真實的 file name 就不處理 */
        if (($name === false) || empty($name))
            return false;

        /* 整理部分 record file 資料(包含權限資料) */
        if (is_dir($page_path))
        {
            $fe = "";
            $page_file = "$page_path/".DEF_HTML_PAGE;
            if (!file_exists($page_file))
                $page_file = $page_path;
            $f_md5 = "";
            $type = DIR_TYPE_NAME;

            /* 讀取權限檔資料 */
            $right_info = get_page_right_info($page_dir, $path);
        }
        else
        {
            $fe_n = strrpos($path, ".");
            if ($fe_n == false)
                $fe = "";
            else
                $fe = strtolower(substr($path, $fe_n));
            $page_file = $page_path;
            $f_md5 = md5_file($page_file);

            /* 讀取權限檔資料 */
            $path_dir = substr($path, 0, strrpos($path, "/"));
            $right_info = get_page_right_info($page_dir, $path_dir);
        }

        /* 檢查 cookie 找出目前的 user 當成 owner */
        $owner = "";
        if (!empty($uacn))
            $owner = $uacn;

        /* 整理 record 相關資料 */
        $f_size = real_filesize($page_file);
        $st = stat($page_file);
        $f_ctime = date("YmdHis", $st[10]);
        $f_mtime = date("YmdHis", $st[9]);
        /* 2014/1/24 新增,若 mtime 比目前時間晚,就將檔案的 mtime 設成目前時間 */
        $now_time = time();
        if ($st[9] > $now_time)
        {
            touch($page_file, $now_time);
            $f_mtime = date("YmdHis", $now_time);
        }
        /* 2015/4/24 新增,若 sync 為 true 且原 record 內的 time 與 mtime 欄位資料存在,就使用原時間 */
        if ($sync == true)
        {
            if ((isset($rec["time"])) && (!empty($rec["time"])))
                $f_ctime = $rec["time"];
            if ((isset($rec["mtime"])) && (!empty($rec["mtime"])))
                $f_mtime = $rec["mtime"];
        }

        /* 依副檔名的類別,取出各類別所需之相關欄位資料 */
        if (!isset($fe_type[$fe]))
            $fe_type[$fe] = "";
        switch($fe_type[$fe])
        {
            case VIDEO_TYPE:
                $type = VIDEO_TYPE_NAME;
                $type_field = get_video_field($page_path, $fe);
                break;
            case AUDIO_TYPE:
                $type = AUDIO_TYPE_NAME;
                $type_field = get_audio_field($page_path);
                break;
            case IMAGE_TYPE:
                $type = IMAGE_TYPE_NAME;
                $type_field = get_image_field($page_path);
                break;
            case DOC_TYPE:
                $type = DOC_TYPE_NAME;
                $type_field = get_document_field($page_path, $fe);
                break;
            case TEXT_TYPE:
                $type = TEXT_TYPE_NAME;
                $type_field = get_text_field($page_path);
                break;
            case HTML_TYPE:
                $type = HTML_TYPE_NAME;
                $type_field = get_html_field($page_path);
                break;
            case LINK_TYPE:
                $type = LINK_TYPE_NAME;
                $type_field = get_link_field($page_path);
                break;
            default:
                /* 檢查是否為目錄 */
                if ((isset($type)) && ($type == DIR_TYPE_NAME))
                {
                    /* 檢查是否為一般目錄,若是就取出描述檔的資料 */
                    if ($page_file == "$page_path/".DEF_HTML_PAGE)
                        $type_field = get_html_field($page_file);
                }
                else
                    $type = OTHER_TYPE_NAME;
                break;
        }

        /* 2014/1/24 新增,檢查是否為放入 ODB 的檔案 (檔案 size 為 0),若是就必須保留原 record 內的 md5 & size */
        if (($type !== DIR_TYPE_NAME) && ($f_size == 0))
        {
            if (isset($rec["md5"]))
                $f_md5 = $rec["md5"];
            if (isset($rec["size"]))
                $f_size = $rec["size"];
        }

        /* 整理 record 各欄位內容 */
        $url = str_replace(WEB_ROOT_PATH, "", $page_path);
        $path_list = explode("/", $url);
        $site_name = $path_list[1];
        if ($site_name == INT_SITE_NAME)
            $site_mode = INTERNAL_MODE;
        else
            $site_mode = EXTERNAL_MODE;
        /* 檢查是否為子網站首頁,若是在 url 最後加上 '/',並把 type 改成 Site */
        $p_cnt = count($path_list);
        if (($site_name == SUB_SITE_NAME) && ($p_cnt == 3))
        {
            $url .= "/";
            $page_name .= "/";
            $type = SITE_TYPE_NAME;
        }

        $rec["site"] = $site_name;
        $rec["site_mode"] = $site_mode;
        if (isset($rec["url"]))
            unset($rec["url"]);
        $rec["page_name"] = $page_name;
        $rec["md5"] = $f_md5;
        /* 2014/10/23 修改,檢查是否有隱藏,若有隱藏要在 allow 欄位加入 ALLOW_HIDDEN */
        /* 2014/10/29 修改,若有傳入 hidden=true 也要加入 ALLOW_HIDDEN */
        if (($hidden == false) && (chk_hidden($page_path) != true))
            $rec["allow"] = $right_info["allow"];
        else
        {
            if (empty($right_info["allow"]))
                $rec["allow"] = ALLOW_HIDDEN;
            else
                $rec["allow"] = ALLOW_HIDDEN.",".$right_info["allow"];
        }
        $rec["deny"] = $right_info["deny"];
        if (empty($rec["owner"]))
            $rec["owner"] = $owner;
        $rec["last_acn"] = $owner;
        $rec["filename"] = $name;
        if (empty($rec["title"]))
            $rec["title"] = $name;
        $rec["fe"] = $fe;
        $rec["size"] = $f_size;
        $rec["time"] = $f_ctime;
        $rec["mtime"] = $f_mtime;
	$rec["dir_type"] = $o_dir_type;
        if (empty($rec["tag"]))
        {
            $rec["tag"] = "";
            /* 2015/5/27 新增,若是新的 record 且是子網站空間,就先取得網站資料 */
            //if (($new_rec == true) && ($site_name == SUB_SITE_NAME))
            //{
            //    $site_conf = get_site_conf($path);
            //    /* 檢查是否在社群網站內,且是目錄(要過濾掉第一層目錄)/文章/文件等資料,若是就要加上 owner 名稱 (sun) 的 tag */
            //    if (($site_conf["type"] == SITE_TYPE_GROUP) && ((($type == DIR_TYPE_NAME) && ($p_cnt > 4)) || ($type == HTML_TYPE_NAME) || ($type == DOC_TYPE_NAME)))
            //        $rec["tag"] = $login_user["sun"];
            //}
            /* 2015/6/4 修改,檢查目錄 record 內的 dir_set 欄位,若有設定 ATUN 就代表要自動設定 owner 名稱 */
            $dir_rec = rec2array($dir_rec_file);
            if ((isset($dir_rec[0]["dir_set"])) && (strstr($dir_rec[0]["dir_set"], "ATUN") !== false))
                $rec["tag"] = $login_user["sun"];
        }
        /* 2014/10/29 新增,若 hidden=true 就必須再 tag 欄位內加入 HIDDEN_TAG (須先檢查是否已有此 tag,若有就不需再新增) */
        if (($hidden == true) && (strstr($rec["tag"], HIDDEN_TAG) == false))
        {
            if (empty($rec["tag"]))
                $rec["tag"] = HIDDEN_TAG;
            else
                $rec["tag"] .= ",".HIDDEN_TAG;
        }
        $rec["type"] = $type;
        if (empty($rec["class"]))
            $rec["class"] = "";
        if (empty($rec["us_like"]))
            $rec["us_like"] = "";
        if (empty($rec["us_unlike"]))
            $rec["us_unlike"] = "";
        if (empty($rec["cnt_like"]))
            $rec["cnt_like"] = 0;
        if (empty($rec["cnt_unlike"]))
            $rec["cnt_unlike"] = 0;
        if (empty($rec["cnt_view"]))
            $rec["cnt_view"] = 0;
        if (empty($rec["open_edit"]))
            $rec["open_edit"] = "n";
        if (empty($rec["EditContent"]))
            $rec["EditContent"] = "";
        if (!empty($type_field))
        {
            foreach($type_field as $key => $value)
            {
                /* 2014/9/5 修改,檢查 description 與 content 內容,若有 "\n@" 一律改成 "\n @" 以避免造成 record 格是誤判問題 */
                if (($key == "description") || ($key == "content"))
                    $value = str_replace("\n@", "\n @", str_replace("\r", "", $value));
                $rec[$key] = $value;
            }
        }
        /* 2015/10/14 調整,將設定 title 移到 type_field 整理完成後再處理,因 type_field 有可能出現有 title 欄位,但內容是空的狀況,必須重新設定 title 欄位內容為 name */
        if (empty($rec["title"]))
            $rec["title"] = $name;
        if (empty($rec["description"]))
            $rec["description"] = "";
        /* 新增分享的 count 欄位 */
        if (empty($rec["cnt_share"]))
            $rec["cnt_share"] = 0;
        if (empty($rec["cnt_facebook"]))
            $rec["cnt_facebook"] = 0;
        if (empty($rec["cnt_plurk"]))
            $rec["cnt_plurk"] = 0;
        if (empty($rec["cnt_twitter"]))
            $rec["cnt_twitter"] = 0;
        if (empty($rec["cnt_tumblr"]))
            $rec["cnt_tumblr"] = 0;

        /* 寫入 record file (若 record 目錄尚未建立,就先建立) */
        if (!is_dir($rec_dir))
            mkdir($rec_dir);
        write_rec_file($rec_file, $rec, $rebuild, false, $url);
    }

    /* 將 record 寫入有多筆 record 的 record file 中,同時將 record 加到 index 中 */
    function write_multi_rec_file($rec_file, $rec, $rebuild=false)
    {
        /* 先檢查 record file 是否已經存在 */
        if (file_exists($rec_file))
            $rec_exist  = true;
        else
            $rec_exist = false;

        /* 因取消 url 欄位,目前 write_multi_rec_file 只處理功能目錄 record,先從 record file 取得 dir_url 位置 */
        $dir_url = get_url_by_rec_file($rec_file);
        if ($dir_url == false)
            return false;

        /* 整理多筆的 record 內容 */
        $rec_cnt = count($rec);
        $rec_content = "";
        $rec_begin = REC_START.REC_BEGIN_PATTERN;
        for ($i = 0; $i < $rec_cnt; $i++)
        {
            /* 整理出單筆的 Record 內容(不包含 REC_START 與 REC_BEGIN_PATTERN,因為 rupdate 不可有) */
            $rec_tmp = "";
            foreach($rec[$i] as $key => $value)
            {
                if ($key == GAIS_REC_FIELD)
                    continue;
                $value = trim($value);
                $rec_tmp .= "@$key:$value\n";
            }

            /* 設定單筆的 Record 暫存檔,並整理出該筆資料的 url */
            $tmp_rec_file = tempnam(NUWEB_TMP_DIR, "rec_");
            $url = $dir_url."/".$rec[$i]["page_name"];

            /* 若 record file 不存在或正在重建 index 就用 rput 將 record 加入 index 中,否則就用 rupdate 方式更新 index */
            if (($rec_exist != true) || ($rebuild == true))
            {
                /* 將單筆的 Record 存到暫存檔中 (使用 rput 在前面要加入 REC_START 與 REC_BEGIN_PATTERN) */
                $fp = fopen($tmp_rec_file, "w");
                flock($fp, LOCK_EX);
                fputs($fp, $rec_begin);
                fputs($fp, $rec_tmp);
                flock($fp, LOCK_UN);
                fclose($fp);

                rec_put($tmp_rec_file, $url, $rebuild, true);
            }
            else
            {
                /* 將單筆的 Record 存到暫存檔中 (使用 rupdate 不可有 REC_START 與 REC_BEGIN_PATTERN) */
                $fp = fopen($tmp_rec_file, "w");
                flock($fp, LOCK_EX);
		fputs($fp, $rec_begin);//rupdate rewrite
                fputs($fp, $rec_tmp);
                flock($fp, LOCK_UN);
                fclose($fp);

                rec_update($tmp_rec_file, $url, true);
            }

            /* 刪除暫存的 record file */
            unlink($tmp_rec_file);

            /* 將單筆的 Record 內容整併到 rec_content 中 */
            $rec_content .= $rec_begin.$rec_tmp;
        }

        /* 將 record 內容寫入 record file 中 */
        if (($fp = @fopen($rec_file, "w")) == false)
            return;
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /* 將 record 寫入 record file 中,同時將 record 加到 index 中 */
    function write_rec_file($rec_file, $rec, $rebuild=false, $add_cnt=false, $url=NULL, $is_fun=false)
    {
        /* 2014/7/22 新增,檢查 rec_file 是否在除了 .nuweb_rec 以外的其他系統目錄內 (.nuweb_*),若是就不處理 */
        /* 2015/3/17 修改,若是在 .nuweb_forum 內還是要處理 */
        /* 2015/8/7 修改,若是在 .nuweb_public 內還是要處理 */
        if (($rec["filename"] !== SITE_FORUM_PATH) && ($rec["filename"] !== SITE_PUBLIC_PATH) && (rec_in_sys_dir($rec_file) == true))
            return;

        /* 先檢查 record file 是否已經存在 */
        if (file_exists($rec_file))
            $rec_exist  = true;
        else
            $rec_exist = false;

        /* 2015/3/9 新增,檢查 record 內容,一定要有幾個特定欄位資料 (避免傳入 record 內容出問題,造成存檔後資料遺失) */
        /* 目前檢查 page_name, owner, time, mtime, title 等欄位,因 owner 與 title (討論區留言有可能 title 為空的),所以僅檢查是否有設定此欄位 */
        if ((empty($rec["page_name"])) || (!isset($rec["owner"])) || (empty($rec["time"])) || (empty($rec["mtime"])) || (!isset($rec["title"])))
            return false;

        /* 將 record 內容寫入 record file 中 */
        $rec_content = "@\n";
        $rec_content .= REC_BEGIN_PATTERN;
        foreach($rec as $key => $value)
        {
            if ($key == GAIS_REC_FIELD)
                continue;
            $value = trim($value);
            $rec_content .= "@$key:$value\n";
        }
        if (($fp = @fopen($rec_file, "w")) == false)
            return false;
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 檢查特殊檔案不需要建立 record file 就刪除 */
        if ((substr($rec["filename"], 0 - strlen(TN_FE_NAME)) == TN_FE_NAME) || (substr($rec["filename"], -11) == ".thumb.lock") || ($rec["filename"] == "style_setting.rec") || (substr($rec["filename"], strrpos($rec["filename"], "."), strlen(".nuweb")) == ".nuweb"))
        {
            /* 2015/3/17 修改,若是 .nuweb_forum 就不要刪除 record */
            /* 2015/8/10 修改,若是 .nuweb_public 也不要刪除 record */
            if (($rec["filename"] !== SITE_FORUM_PATH) && ($rec["filename"] !== SITE_PUBLIC_PATH))
                unlink($rec_file);
            return false;
        }

        /* 若 record file 不存在或正在重建 index 就用 rput 將 record 加入 index 中 */
        if ($url == NULL)
            $url = get_url_by_rec_file($rec_file);
        if (($rec_exist != true) || ($rebuild == true))
            rec_put($rec_file, $url, $rebuild, $is_fun);
        else
            rec_update($rec_file, $url, $is_fun, $add_cnt);
    }

    /* 更新有多筆 record 的 record file 內容 */
    function update_multi_rec_file($rec_file, $rec)
    {
        /* 先取出原始 record file */
        if (file_exists($rec_file))
        {
            $src_rec = rec2array($rec_file);
            $rec_cnt = count($src_rec);
            for ($i = 0; $i < $rec_cnt; $i++)
            {
                /* 把變更的 record 欄位,設定到原始 record 中 */
                foreach($rec as $key => $value)
                    $src_rec[$i][$key] = $value;
            }
        }
        /* 把更新後的 record 內容寫回 record file */
        write_multi_rec_file($rec_file, $src_rec);
    }

    /* 更新 record file 內容 */
    function update_rec_file($rec_file, $rec)
    {
        /* 先取出原始 record file */
        if (file_exists($rec_file))
        {
            $rec_buf = rec2array($rec_file);
            $src_rec = $rec_buf[0];
            /* 把變更的 record 欄位,設定到原始 record 中 */
            foreach($rec as $key => $value)
            {
                $src_rec[$key] = $value;
            }
        }
        /* 把更新後的 record 內容寫回 record file */
        write_rec_file($rec_file, $src_rec);

        /* 2014/9/6 新增,紀錄到 modify.list 中 */
        write_modify_list("update", $rec_file, "rec");
    }

    /* 更新 record file 中的 filename (title) */
    function update_rec_by_filename($page_dir, $path, $name)
    {
        Global $fe_type;

        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* path 最後不可有 '/' */
        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 若 page path 不存在就不處理 */
        $page_path = $page_dir.$path;
        if (!file_exists($page_path))
            return false;

        /* 檢查是目錄 or 檔案,並找出 record file 位置 */
        if (is_dir($page_path))
        {
            $rec_dir = $page_path."/".NUWEB_REC_PATH;
            $rec_file = $rec_dir.DIR_RECORD;
            $page_file = "$page_path/".DEF_HTML_PAGE;
            if (file_exists($page_file))
                $fe = ".html";
        }
        else
        {
            $n = strrpos($page_path, "/");
            $rec_dir = substr($page_path, 0, $n+1).NUWEB_REC_PATH;
            $rec_file = $rec_dir.substr($page_path, $n+1).".rec";
            $fe = strtolower(substr($page_path, strrpos($page_path, ".")));
            $page_file = $page_path;
        }

        /* 若 record file 不存在就不處理 */
        if (!file_exists($rec_file))
            return false;

        if ($fe_type[$fe] == HTML_TYPE)
        {
            $buf = @file($page_file);
            $tmp_buf = trim($buf[0]);
            /* 取出 Title */
            if (strstr($tmp_buf, TITLE_PREFIX) === $tmp_buf)
            {
                $l = strlen(TITLE_PREFIX);
                $title = substr($tmp_buf, $l, strpos($tmp_buf, TITLE_POSTFIX)-$l);
            }
        }

        /* 設定要更新的 filename 與 title,並更新 record file */
        $rec["filename"] = $name;
        if (!empty($title))
            $rec["title"] = $title;
        else
            $rec["title"] = $name;
        update_rec_file($rec_file, $rec);
    }

    /* 更新目錄中所有 record 檔的權限資料 (allow & deny),若 update_subdir 為 true 代表要包含底下的所有子目錄 */
    function update_rec_by_right($page_dir, $path, $update_subdir=false)
    {
        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* path 最後不可有 '/' */
        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 若 page_path 不是目錄就不處理 */
        $page_path = $page_dir.$path;
        if (!is_dir($page_path))
            return;

        /* record 目錄存在才進行處理 */
        $rec_dir = $page_path."/".NUWEB_REC_PATH;
        if (is_dir($rec_dir))
        {
            /* 取出目錄的權限資料 */
            $right_info = get_page_right_info($page_dir, $path);

            /* 取得 record 目錄內的所有 record 檔 */
            $handle = opendir($rec_dir);
            while ($rec_name = readdir($handle))
            {
                $rec_file = $rec_dir.$rec_name;

                /* 只處理 record 檔,將權限資料更新到 record 檔中 */
                if ((substr($rec_name, -4) == ".rec") && (substr($rec_file, -12) !== ".comment.rec"))
                {
                    /* 如果是 fun.rec 就改用 update_multi_rec_file,將權限資料更新到 record 檔中 */
                    if ($rec_name == FUN_RECORD)
                        update_multi_rec_file($rec_file, $right_info);
                    else
                        update_rec_file($rec_file, $right_info);
                }
            }
            closedir($handle);
        }

        /* 檢查是否也要更新底下的所有子目錄的 record 檔 */
        if ($update_subdir != true)
            return;

        /* 讀取目錄內的檔案與子目錄 */
        $handle = opendir($page_path);
        $f_cnt = 0;
        $l = strlen(NUWEB_SYS_FILE);
        while ($sub_name = readdir($handle))
        {
            /* 過濾掉 . & .. & nuweb 系統檔(目錄) */
            if (($sub_name == ".") || ($sub_name == "..") || (substr($sub_name, 0, $l) == NUWEB_SYS_FILE))
                continue;

            /* 如果是子目錄,就繼續往下層處理 */
            $sub_dir = $page_path."/".$sub_name;
            $sub_path = $path."/".$sub_name;
            if (is_dir($sub_dir))
                update_rec_by_right($page_dir, $sub_path, $update_subdir);
        }
        closedir($handle);
    }

    /* 取得 record 欄位資料 */
    /* 2015/8/10 修改,若沒傳入 field 參數,就直接回傳 record 完整資料 */
    function get_rec_field($file_path, $field=NULL)
    {
        /* 檢查參數及檔案是否存在 */
        /* 2015/8/17 修改,不必再檢查 field 是否為空的 */
        //if ((empty($field)) || (!file_exists($file_path)))
        if (!file_exists($file_path))
            return false;

        /* 找出 record file */
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file === false)
            return false;

        /* 取出 record file 資料 */
        $rec = rec2array($rec_file);

        /* 回傳欄位資料,若沒有該欄位就回傳 NULL */
        /* 2015/8/10 修改,若沒傳入 field 參數,就直接回傳 record 完整資料 */
        if (empty($field))
            return $rec[0];
        if (isset($rec[0][$field]))
            return $rec[0][$field];
        return NULL;
    }

    /* 取得檔案(目錄)的 record 欄位資料 (若無傳入欄位,代表要取得完整 record 資料) */
    function get_path_rec_field($page_dir, $path, $field=false)
    {
        /* page_dir 最後必須有 '/' */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* path 最後不可有 '/' */
        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 若 page path 不存在就不處理 */
        $page_path = $page_dir.$path;
        if (!file_exists($page_path))
            return false;

        /* 找出 record file 位置 (若 record 不存在就建立) */
        $rec_file = get_file_rec_path($page_dir.$path);
        if ($rec_file == false)
        {
            write_def_record($page_dir, $path, NULL, true);
            $rec_file = get_file_rec_path($page_dir.$path);
            if ($rec_file == false)
                return false;
        }

        /* 取出 record file 資料 */
        $rec = rec2array($rec_file);

        if ($field == false)
            return $rec[0];
        else
            return $rec[0][$field];
    }

    /* 將 record file 中的某個欄位值加 1 (用來計算 count) */
    function add_rec_field_cnt($rec_file, $field)
    {
        /* record file 不存在就不處理 */
        if (!file_exists($rec_file))
            return false;

        /* 先取出原始 record file */
        $rec = rec2array($rec_file);
        /* 2015/3/9 新增,檢查若 rec 為空的或 false 就不處理 */
        if ((empty($rec)) || ($rec == false))
            return false;

        /* 將 field 的值加 1 */
        if (!isset($rec[0][$field]))
            $rec[0][$field] = 0;
        $rec[0][$field]++;

        /* 把更新後的 record 內容寫回 record file */
        write_rec_file($rec_file, $rec[0], false, true);
    }

    /* 將 record file 中所有相關的 count 都清為 0 */
    function clean_rec_cnt($rec_file, $update_index=false)
    {
        /* record file 不存在就不處理 */
        if (!file_exists($rec_file))
            return false;
        /* 先取出原始 record file */
        $rec = rec2array($rec_file);

        /* 設定所有的 count 都為 0,包括把 like 與 unlike 清空 */
        $rec[0]["us_like"] = "";
        $rec[0]["us_unlike"] = "";
        $rec[0]["cnt_like"] = 0;
        $rec[0]["cnt_unlike"] = 0;
        $rec[0]["cnt_view"] = 0;
        /* 新增分享的相關 count 也設為 0 */
        $rec[0]["cnt_share"] = 0;
        $rec[0]["cnt_facebook"] = 0;
        $rec[0]["cnt_plurk"] = 0;
        $rec[0]["cnt_twitter"] = 0;
        $rec[0]["cnt_tumblr"] = 0;
        /* 若 tag 內有設定 HIDDEN_TAG,必須清除 HIDDEN_TAG (其餘 tag 要保留) */
        if ((isset($rec[0]["tag"])) && (strstr($rec[0]["tag"], HIDDEN_TAG) !== false))
        {
            $tag_item = explode(",", $rec[0]["tag"]);
            $cnt = count($tag_item);
            $rec[0]["tag"] = "";
            for ($i = 0; $i < $cnt; $i++)
            {
                $tag_item[$i] = trim($tag_item[$i]);
                if ($tag_item[$i] == HIDDEN_TAG)
                    continue;
                if (!empty($rec[0]["tag"]))
                    $rec[0]["tag"] .= ",";
                $rec[0]["tag"] .= $tag_item[$i];
            }
        }

        /* 2013/7/23 新增,將公開/分享/共用等相關欄位清空 */
        /* 2015/4/24 修改,改成將原始 record 內相關欄位移除掉 */
        //$rec["public"] = "";
        //$rec["public_date"] = "";
        //$rec["share_code"] = "";
        //$rec["share_date"] = "";
        //$rec["use_acn"] = "";
        //$rec["use_date"] = "";
        if (isset($rec[0]["public"]))
            unset($rec[0]["public"]);
        if (isset($rec[0]["public_date"]))
            unset($rec[0]["public_date"]);
        if (isset($rec[0]["share_code"]))
            unset($rec[0]["share_code"]);
        if (isset($rec[0]["share_date"]))
            unset($rec[0]["share_date"]);
        if (isset($rec[0]["use_acn"]))
            unset($rec[0]["use_acn"]);
        if (isset($rec[0]["use_date"]))
            unset($rec[0]["use_date"]);
        /* 2015/4/24 新增,將原始 record 內權限相關欄位移除掉 */
        $l1 = strlen(RIGHT_PREFIX);
        foreach($rec[0] as $key => $value)
        {
            if (substr($key, 0, $l1) == RIGHT_PREFIX)
                unset($rec[0][$key]);
        }

        /* 更新到 record file 中,若要更新 index,就 call update_rec_file,否則就直接寫入 record file */
        /* 2015/4/24 修改,因有刪除原始 record 的欄位,無法直接使用 update_rec_file,改用 write_rec_file (會更新 index) 寫入 record file */
        if ($update_index == true)
            write_rec_file($rec_file, $rec[0]);
        else
        {
            /* 將 record 內容寫入 record file 中 */
            $rec_content = "@\n";
            $rec_content .= REC_BEGIN_PATTERN;
            foreach($rec[0] as $key => $value)
            {
                if ($key == GAIS_REC_FIELD)
                    continue;
                $value = trim($value);
                $rec_content .= "@$key:$value\n";
            }
            if (($fp = @fopen($rec_file, "w")) == false)
                return false;
            flock($fp, LOCK_EX);
            fputs($fp, $rec_content);
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        /* 紀錄到 modify.list 中 */
        write_modify_list("update", $rec_file, "rec");
        return true;
    }

    /* 將 record file 刪除 */
    function del_rec_file($rec_file)
    {
        /* record file 不存在就不處理 */
        if (!file_exists($rec_file))
            return false;

        /* 先取出 record file 內容 */
        $rec = rec2array($rec_file);

        /* 從 record file 取得 page_url */
        $page_url = get_url_by_rec_file($rec_file);
        if ($page_url == false)
            return false;

        /* 檢查是否為功能目錄 record */
        $l = strlen(FUN_RECORD);
        $is_fun = false;
        if (substr($rec_file, -$l) === FUN_RECORD)
            $is_fun = true;

        /* 將 record 從 index 中刪除 */
        $cnt = count($rec);
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($is_fun !== true)
                $url = $page_url;
            else
                $url = $page_url."/".$rec[$i]["page_name"];
            rec_delete($url);
        }

        /* 將 record file 刪除 */
        unlink($rec_file);
    }

    /* 從 rec_file 位置取得 url */
    function get_url_by_rec_file($rec_file)
    {
        /* rec_file 必須存在,而且必須是 *.rec (但不可是 .comment.rec) */
        if ((!file_exists($rec_file)) || (substr($rec_file, -4) !== ".rec") || (substr($rec_file, -12) == ".comment.rec"))
            return false;

        /* 若不是在網站目錄內也不必處理,若是就先整理出 rec_url */
        $l = strlen(WEB_ROOT_PATH);
        if (substr($rec_file, 0, $l) !== WEB_ROOT_PATH)
            return false;
        $rec_url = substr($rec_file, $l+1);

        /* 將 rec_url 分解出每層目錄,若倒數第二層不是 .nuweb_rec 就不處理 */
        $path = explode("/", $rec_url);
        $cnt = count($path);
        if ($path[$cnt-2]."/" !== NUWEB_REC_PATH)
            return false;
        $path[$cnt-2] = "";

        /* 找出檔案名稱,若是 dir.rec (目錄的 record) 或是 fun.rec (功能目錄的 record) 就不需要檔案名稱 */
        if (($path[$cnt-1] == DIR_RECORD) || ($path[$cnt-1] == FUN_RECORD))
            $path[$cnt-1] = "";
        else
            $path[$cnt-1] = substr($path[$cnt-1], 0, -4);

        /* 整理出 url */
        $url = "";
        for ($i = 0; $i < $cnt; $i++)
        {
            if (empty($path[$i]))
                continue;
            $url .= "/".$path[$i];
        }
        return $url;
    }

    /* 更新並檢查瀏覽的 url 是否已存在 click_file 中且在有效時間內 */
    function update_chk_click($click_id, $file_url)
    {
        $click_file = NUWEB_TMP_DIR.$click_id;
        $now_time = time();
        $over_time = $now_time + CLICK_MAX_TIME;
        $key = md5($file_url);

        /* 若 click_file 不存在或 size 為 0,代表 url 不存在,直接記錄到 click_file 中,並回傳 false */
        if ((!file_exists($click_file)) || (real_filesize($click_file) === 0))
        {
            $fp = fopen($click_file, "a");
            flock($fp, LOCK_EX);
            fputs($fp, "$key\t$over_time\n");
            flock($fp, LOCK_UN);
            fclose($fp);
            return false;
        }

        /* 檢查並更新 click_file */
        $content = "";
        $update = false;
        $exist = false;
        $fp = fopen($click_file, "r");
        flock($fp, LOCK_SH);
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            list($u_key, $u_time) = explode("\t", trim($buf));
            /* 過濾掉已過期的 click 資料 */
            if ($u_time < $now_time)
            {
                $update = true;
                continue;
            }

            /* 檢查 key 是否已存在 */
            if ($u_key == $key)
                $exist = true;
            $content .= $buf;
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 若 key 不存在,就要記錄到 click_file 中 */
        if ($exist == false)
        {
            $content .= "$key\t$over_time\n";
            $update = true;
        }

        /* 檢查是否需要更新 click_file */
        if ($update == true)
        {
            $fp = fopen($click_file, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        /* 回傳 key 是否已存在 */
        return $exist;
    }

    /* 記錄 click log,並把 record file 中的 cnt_view 欄位值加1 */
    function write_click_log($file_url)
    {
        Global $login_user;

        /* 2014/7/22 新增,若 file_url 中有 .nuweb_ 代表是系統檔案或目錄,不需要記錄 Click log */
        if (strstr($file_url, NUWEB_SYS_FILE) !== false)
            return;

        /* 整理 file_url */
        if (substr($file_url, -11) == "/".DEF_HTML_PAGE)
            $file_url = substr($file_url, 0, -11);
        else if (substr($file_url, -1) == "/")
            $file_url = substr($file_url, 0, -1);

        /* 檢查是否在時間範圍內曾經瀏覽過,若是就不再記錄 click log */
        /* 2014/4/25 修改,取消 cookie 改記錄到檔案中 */
        /* 已登入 user 直接使用固定 click_id */
        if ((isset($login_user["ssn"])) && (!empty($login_user["ssn"])))
            $click_id = "click_".$login_user["ssn"];
        else
        {
            /* 若未登入就建立亂數檔,並記錄到 click_id 的 cookie 中 */
            if ((isset($_COOKIE["click_id"])) && (!empty($_COOKIE["click_id"])))
                $click_id = $_COOKIE["click_id"];
            else
            {
                $click_file = tempnam(NUWEB_TMP_DIR, "click_");
                $click_id = substr($click_file, strrpos($click_file, "/")+1);
            }
            /* 每次都要重新 setcookie,以確保 click_id cookie 的有效期限 (目前有效期限為1天) */
            setcookie("click_id", $click_id, time()+CLICK_FILE_TIME);
        }
        /* 更新並檢查瀏覽的 url 是否已存在 click_file 中且在有效時間內,若是就離開不處理 */
        if (update_chk_click($click_id, $file_url) == true)
            return;

        /* 建立 CLICK_LOG_DIR */
        if (!is_dir(CLICK_LOG_DIR))
            mkdir(CLICK_LOG_DIR);

        /* 建立儲存(當年度) click log 的目錄 */
        $click_log_dir = CLICK_LOG_DIR.date("Y")."/";
        if (!is_dir($click_log_dir))
            mkdir($click_log_dir);

        $click_file = $click_log_dir.date("Ymd");
        write_server_log($click_file, $file_url);
        $file_path = WEB_ROOT_PATH.$file_url;
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file !== false)
            add_rec_field_cnt($rec_file, CNT_VIEW_FIELD);
    }

    /* 記錄 upload log */
    function write_upload_log($file_url, $mode=NULL)
    {
        /* 整理 file_url */
        if (substr($file_url, -11) == "/".DEF_HTML_PAGE)
            $file_url = substr($file_url, 0, -11);
        else if (substr($file_url, -1) == "/")
            $file_url = substr($file_url, 0, -1);

        /* 建立 UPLOAD_LOG_DIR */
        if (!is_dir(UPLOAD_LOG_DIR))
            mkdir(UPLOAD_LOG_DIR);

        /* 建立儲存(當年度) upload log 的目錄 */
        $upload_log_dir = UPLOAD_LOG_DIR.date("Y")."/";
        if (!is_dir($upload_log_dir))
            mkdir($upload_log_dir);

        $upload_file = $upload_log_dir.date("Ymd");
        $log_msg = $file_url;
        if ($mode !== NULL)
            $log_msg .= "\t$mode";
        write_server_log($upload_file, $log_msg);
    }

    /* 記錄 modify list */
    function write_modify_list($mode, $file_path, $type=NULL, $option=NULL, $log=false)
    {
        /* 檢查 mode */
        if (($mode !== "new") && ($mode !== "update") && ($mode !== "del") && ($mode !== "rename"))
            return false;

        /* 整理 file_path */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);

        /* 2015/11/25 新增,目前僅能處理 /data/ 內的資料,所以必須檢查 file_path 是否在 /data/ 內 */
        if (substr($file_path, 0, 6) !== "/data/")
        {
            /* 因有 file_path 可能是真實路徑,不一定是以 /data/ 開頭 (如 /mnt/data/),必須檢查是否在 /data/ 的真實路徑內,若是要將 file_path 轉成 /data/ 開頭的路徑 */
            $data_path = realpath("/data")."/";
            $l = strlen($data_path);
            if (substr($file_path, 0, $l) !== $data_path)
                return false;
            $file_path = "/data/".substr($file_path, $l);
        }

        $list_file = MODIFY_LIST;
        if ($log == true)
            $list_file .= ".log";
        $fp = fopen($list_file, "a");
        flock($fp, LOCK_EX);
        if ($log == true)
            fputs($fp, date("Y-m-d:H:i:s")."\t");
        fputs($fp, "$mode\t$file_path\t$type\t$option\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /* 取得 index 中符合 url 的 list */
    function get_url_match_list($index_dir, $url)
    {
        $index_file = str_replace("//", "/", "$index_dir/current");
        if (!file_exists($index_file))
            return false;

        /* 用 egrep 找出 index 中所需資料 */
        $cmd = "/bin/egrep \"@_i:|@_f:|@url:|@filename:\" $index_file";
        $fp = popen($cmd, "r");
        $site_url = "/".SUB_SITE_NAME."/";
        $l = strlen($site_url);
        $del = false;
        $n = 0;
        $list = array();
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            $buf = trim($buf);
            if ($buf == "")
                continue;

            /* @_i: 代表此筆資料為新的資料 */
            if (strstr($buf, "@_i:") === $buf)
            {
                /* 新資料就設定 r_id,並將 r_url & r_name 先清空 */
                $del = false;
                $r_id = trim(substr($buf, 4));
                $r_url = NULL;
                $r_name = NULL;
                continue;
            }
            /* @_f:Deleted 代表此筆資料已被刪除 */
            if ($buf == "@_f:Deleted")
                $del = true;

            /* 若此筆資料已被刪除,就不處理 */
            if ($del == true)
                continue;

            /* 取得 url 欄位內容,並檢查是否符合 url,若不符合就設為刪除不處理 */
            if (strstr($buf, "@url:") === $buf)
            {
                $r_url = trim(substr($buf, 5));
                /* 若 r_url 不是在子網站目錄下或不是一般檔案(有 .php 或 ? 或 & 代表是程式)或不是符合的 url,就不處理 */
                if ((substr($r_url, 0, $l) !== $site_url) || (strstr($r_url, ".php") !== false) || (strstr($r_url, "?") !== false) || (strstr($r_url, "&") !== false) || (strstr($r_url, $url) !== $r_url))
                {
                    $del = true;
                    continue;
                }
            }

            /* 取得 filename */
            if (strstr($buf, "@filename:") === $buf)
                $r_name = trim(substr($buf, 10));

            /* 整理 match list */
            if ((!empty($r_id)) && ($r_url !== NULL) && ($r_name !== NULL))
            {
                $list[$n]["rid"] = $r_id;
                $list[$n]["url"] = $r_url;
                $list[$n]["name"] = $r_name;
                $n++;
            }
        }
        pclose($fp);

        return $list;
    }

    /* 建立 index 目錄 */
    function rdb_gen($index_dir)
    {
        $cmd = "";
        if (is_dir($index_dir))
            $cmd = "rm -rf $index_dir ; ";
        $cmd .= SEARCH_BIN_DIR."rdb_gen -s 2147483647 -H $index_dir";
        $fp = popen($cmd, "r");
        pclose($fp);
    }

    /* 從 index 中找出符合的 record id */
    function get_rec_id($index_dir, $key, $value)
    {
        $cmd = SEARCH_BIN_DIR."rdb -H $index_dir -keyTag \"@$key:\" -getpage -key \"@$key:$value\"";
        $fp = popen($cmd, "r");
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            if (substr($buf, 0, 4) == REC_ID_FIELD)
            {
                $rec_id = trim(substr($buf, 4));
                break;
            }
        }
        pclose($fp);
        if (empty($rec_id))
            return false;
        return $rec_id;
    }

    /* 檢查 record file 是否在除了 .nuweb_rec 以外的其他系統目錄中 (.nuweb_*) */
    function rec_in_sys_dir($rec_file)
    {
        $n = strpos($rec_file, NUWEB_REC_PATH);
        if ($n !== false)
            $f_path = substr($rec_file, 0, $n);
        else
            $f_path = $rec_file;
        if (strstr($f_path, NUWEB_SYS_FILE) !== false)
            return true;
        return false;
    }

    /* 將 record put 到 index 中 */
    function rput($index_dir, $rec_file, $key="url")
    {
        /* 2014/7/22 新增,檢查 rec_file 是否在除了 .nuweb_rec 以外的其他系統目錄內 (.nuweb_*),若是就不處理 */
        if (rec_in_sys_dir($rec_file) == true)
            return false;

        /* 檢查 index dir 是否存在,若不存在就建立 */
        if (!is_dir($index_dir))
            rdb_gen($index_dir);

        /* rput 到 index 中 (2014/1/14 改用 rdb 處理) */
        //$cmd = SEARCH_BIN_DIR."rdb -H $index_dir -keyTag \"@$key:\" -put $rec_file";
        /* 2014/3/28 修改,將 -put 參數改成 -update 可避免若重覆 put 相同 key 的 record 會出現多筆狀況 */
        $cmd = SEARCH_BIN_DIR."rdb -H $index_dir -keyTag \"@$key:\" -update $rec_file";
        $fp = popen($cmd, "r");
        $result = "";
        while($buf = fgets($fp, MAX_BUFFER_LEN))
            $result = $buf;
        pclose($fp);
        list($rid, $offset, $len) = explode("\t", trim($result));
        return $rid;
    }

    /* 將 record 內容 rput 到 index 中 */
    function rput_content($index_dir, $rec_content, $key="url")
    {
        /* 若沒傳入 rec_content 就不處理 */
        if (empty($rec_content))
            return false;

        /* 檢查 index dir 是否存在,若不存在就建立 */
        if (!is_dir($index_dir))
            rdb_gen($index_dir);

        /* 將 rec_content 存到暫存的 record file,再 call rput 函數 */
        $tmp_rec = tempnam(NUWEB_TMP_DIR, "rec_");
        $fp = fopen($tmp_rec, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);
        $rid = rput($index_dir, $tmp_rec, $key);

        /* 刪除暫存的 record file */
        unlink($tmp_rec);
        return $rid;
    }

    /* 將更新的 record update 到 index 中 */
    function rupdate($index_dir, $rec_file, $key, $value)
    {
        /* 2014/7/22 新增,檢查 rec_file 是否在除了 .nuweb_rec 以外的其他系統目錄內 (.nuweb_*),若是就不處理 */
        if (rec_in_sys_dir($rec_file) == true)
            return false;

        /* 檢查 index dir 是否存在,若不存在就不處理 */
        if (!is_dir($index_dir))
            return false;

        /* rupdate 到 index 中 (2014/1/14 改用 rdb 處理) */
        if ($key == "rid")
            $cmd = SEARCH_BIN_DIR."rdb -H $index_dir -keyTag \"@$key:\" -update $rec_file -rid \"$value\"";
        else
            $cmd = SEARCH_BIN_DIR."rdb -H $index_dir -keyTag \"@$key:\" -update $rec_file -key \"@$key:$value\"";
        $fp = popen($cmd, "r");
        $buf = fgets($fp, MAX_BUFFER_LEN);
        pclose($fp);
        list($rid, $offset, $len) = explode("\t", trim($buf));
        return $rid;
    }

    /* 將更新的功能 record update 到 index 中 */
    function rupdate_fun($index_dir, $rec_file, $url)
    {
        /* 檢查 index dir 是否存在,若不存在就不處理 */
        if (!is_dir($index_dir))
            return false;

        /* 整理 record 內容 */
        $rec = rec2array($rec_file);
        $rec_cnt = count($rec);
        $view_path = get_view_path(WEB_ROOT_PATH.$url);
        for ($n = 0; $n < $rec_cnt; $n++)
        {
            $real_url = $url."/".$rec[$n]["page_name"];
            $real_view_path = $view_path."/".$rec[$n]["page_name"];

            $rec_content = REC_START.REC_BEGIN_PATTERN;
            $rec_content .= "@url:$real_url\n";
            $rec_content .= "@view_path:$real_view_path\n";
            foreach($rec[$n] as $key => $value)
            {
                if (($key == GAIS_REC_FIELD) || ($key == "url") || ($key == "view_path"))
                    continue;
                $value = trim($value);
                $rec_content .= "@$key:$value\n";
            }

            /* 將 record 更新到 index 中 */
            rupdate_content($index_dir, $rec_content, "url", $real_url);
        }
        return true;
    }

    /* 將更新的一般檔案(目錄) record update 到 index 中 */
    function rupdate_file($index_dir, $rec_file, $url)
    {
        /* 檢查 index dir 是否存在,若不存在就不處理 */
        if (!is_dir($index_dir))
            return false;

        /* 取得 record 內容,檔案(目錄) record 一般都只有1筆,若不是1筆代表有問題,就不處理 */
        $rec = rec2array($rec_file);
        if (count($rec) !== 1)
            return false;

        /* 整理 record 內容 */
        $dir_url = substr($url, 0, strrpos($url, "/")+1);
        $view_path = get_view_path(WEB_ROOT_PATH.$url);
        $rec_content = REC_START.REC_BEGIN_PATTERN;
        $rec_content .= "@url:$url\n";
        $rec_content .= "@view_path:$view_path\n";
        foreach($rec[0] as $key => $value)
        {
            if (($key == GAIS_REC_FIELD) || ($key == "url") || ($key == "view_path"))
                continue;
            $value = trim($value);

            /* 檢查縮圖位置 */
            if (($key == "thumbs") && (!empty($value)))
            {
                $thumbs_url = $dir_url.$value;
                if (file_exists(WEB_ROOT_PATH.$thumbs_url))
                    $value = $thumbs_url;
            }

            /* 檢查 flv 檔位置 */
            if (($key == "flv") && (!empty($value)))
            {
                $flv_url = $dir_url.$value;
                if (file_exists(WEB_ROOT_PATH.$flv_url))
                    $value = $flv_url;
            }

            $rec_content .= "@$key:$value\n";
        }

        /* 將 record 更新到 index 中 */
        rupdate_content($index_dir, $rec_content, "url", $url);

        return true;
    }

    /* 將 record 內容 rupdate 到 index 中 */
    function rupdate_content($index_dir, $rec_content, $key, $value)
    {
        /* 若沒傳入 rec_content 就不處理 */
        if (empty($rec_content))
            return false;

        /* 檢查 index dir 是否存在,若不存在就不處理 */
        if (!is_dir($index_dir))
            return false;

        /* 將 rec_content 存到暫存的 record file,再 call rupdate 函數 */
        $tmp_rec = tempnam(NUWEB_TMP_DIR, "rec_");
        $fp = fopen($tmp_rec, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);
        $rid = rupdate($index_dir, $tmp_rec, $key, $value);

        /* 刪除暫存的 record file */
        unlink($tmp_rec);
        return $rid;
    }

    /* 將 record 從 index 中 delete 掉 */
    function rdelete($index_dir, $key, $value)
    {
        /* 檢查 index dir 是否存在,若不存在就不處理 */
        if (!is_dir($index_dir))
            return false;

        /* 執行 rdelete (2014/1/14 改用 rdb 處理) */
        if ($key == "rid")
            $cmd = SEARCH_BIN_DIR."rdb -H $index_dir -keyTag \"@$key:\" -del -rid \"$value\"";
        else
            $cmd = SEARCH_BIN_DIR."rdb -H $index_dir -keyTag \"@$key:\" -del -key \"@$key:$value\"";
        $fp = popen($cmd, "r");
        $buf = fgets($fp, MAX_BUFFER_LEN);
        pclose($fp);
        return true;
    }

    /* 紀錄 index_process.list (準備要變更 index 的 list) */
    function write_index_process_list($mode, $index_dir, $arg1, $arg2, $is_fun=false)
    {
        if ($is_fun == true)
            $is_fun = 1;
        else
            $is_fun = 0;
        $fp = fopen(INDEX_PROCESS_LIST, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$mode\t$index_dir\t$arg1\t$arg2\t$is_fun\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /* 執行 index_process.list 內的相關功能 */
    function exec_index_process()
    {
        /* 若 index_process.list 不存在,就不用處理 */
        if (!file_exists(INDEX_PROCESS_LIST))
            return false;

        /* 檢查系統是否已正在進行處理 (檢查 index process flag 是否存在),若是就先離開 */
        if (file_exists(INDEX_PROCESS_FLAG))
        {
            $flag_mtime = filemtime(INDEX_PROCESS_FLAG);
            $now_time = time();
            $process_time = $now_time - $flag_mtime;
            /* 如果處理時間不超過 MAX_PROCESS_TIME 就先離開 (認定尚未處理完) */
            if ($process_time < MAX_PROCESS_TIME)
                return false;
        }
        /* 設定 index process flag,並將 index_process.list 內容移到 index_process.list.old (避免處理過程中有新資料進來)  */
        touch(INDEX_PROCESS_FLAG);
        $index_process_old = INDEX_PROCESS_LIST.".old";
        if (file_exists($index_process_old))
        {
            /* 若 index_process.list.old 已存在,就將 index_process.list 內容加入,並移除 index_process.list */
            $cmd = "cat ".INDEX_PROCESS_LIST." >> $index_process_old ; rm -f ".INDEX_PROCESS_LIST;
            $fp = popen($cmd, "r");
            pclose($fp);
        }
        else
            rename(INDEX_PROCESS_LIST, $index_process_old);

        /* 取出 index process 資料 */
        $list = @file($index_process_old);
        $cnt = count($list);
        for ($i = 0; $i < $cnt; $i++)
        {
            list($mode, $index_dir, $arg1, $arg2, $is_fun) = explode("\t", trim($list[$i]));
            if ((empty($mode)) || (empty($index_dir)) || (!file_exists($index_dir)))
               continue; 

            /* 依 mode 執行相關處理 */
            switch($mode)
            {
                case "del":
                    $rec_file = $arg1;
                    $url = $arg2;
                    if (empty($url))
                        continue;
                    rdelete($index_dir, "url", $url);
                    break;

                case "update":
                    $rec_file = $arg1;
                    $url = $arg2;
                    if ($is_fun === "1")
                        $is_fun = true;
                    else
                        $is_fun = false;
                    if ((empty($url)) || (empty($rec_file)) || (!file_exists($rec_file)))
                        continue;

                    /* 整理 record 內容 */
                    $rec = rec2array($rec_file);
                    $rec_cnt = count($rec);
                    $dir_url = substr($url, 0, strrpos($url, "/")+1);
                    $view_path = get_view_path(WEB_ROOT_PATH.$url);
                    for ($n = 0; $n < $rec_cnt; $n++)
                    {
                        if ($is_fun !== true)
                        {
                            $real_url = $url;
                            $real_view_path = $view_path;
                        }
                        else
                        {
                            $real_url = $url."/".$rec[$n]["page_name"];
                            $real_view_path = $view_path."/".$rec[$n]["page_name"];
                        }

                        $rec_content = REC_START.REC_BEGIN_PATTERN;
                        $rec_content .= "@url:$real_url\n";
                        $rec_content .= "@view_path:$real_view_path\n";
                        foreach($rec[$n] as $key => $value)
                        {
                            if (($key == GAIS_REC_FIELD) || ($key == "url") || ($key == "view_path"))
                                continue;
                            $value = trim($value);

                            /* 檢查縮圖位置 */
                            if (($key == "thumbs") && (!empty($value)))
                            {
                                $thumbs_url = $dir_url.$value;
                                if (file_exists(WEB_ROOT_PATH.$thumbs_url))
                                    $value = $thumbs_url;
                            }

                            /* 檢查 flv 檔位置 */
                            if (($key == "flv") && (!empty($value)))
                            {
                                $flv_url = $dir_url.$value;
                                if (file_exists(WEB_ROOT_PATH.$flv_url))
                                    $value = $flv_url;
                            }

                            $rec_content .= "@$key:$value\n";
                        }
                        rupdate_content($index_dir, $rec_content, "url", $url);
                    }
                    break;

                case "update_move":
                    $src_url = $arg1;
                    $target_url = $arg2;
                    if (substr($index_dir, -1) !== "/")
                        $index_dir .= "/";
                    $index_file = $index_dir."current";
                    update_move_file_content($index_file, $src_url, $target_url);
                    break;
            }
        }

        /* 刪除 index_process.list.old 與 index process flag */
        unlink($index_process_old);
        unlink(INDEX_PROCESS_FLAG);
        return true;
    }

    /* 檢查此筆 record 是否要加入 index 中 */
    function chk_in_index($rec, $rec_file)
    {
        /* 如果 filename 是 *.files 與 .sync 的目錄就不放到 index 中 */
        if ((substr($rec["filename"], -6) == ".files") || ($rec["filename"] == SYNC_DIR))
            return false;

        /* 如果是 gif 或 png 圖片檔,且 size 小於 MIN_INDEX_IMG_SIZE 就不放到 All index 中 */
        if ((($rec["fe"] == ".gif") || ($rec["fe"] == ".png")) && ($rec["size"] < MIN_INDEX_IMG_SIZE))
            return false;

        /* 2014/3/6 新增,若是檔案的 record 就檢查所在的目錄是否為 *.files 或 .sync 若是也不放到 index 中 */
        $n = strrpos($rec_file, "/");
        $rec_dir = substr($rec_file, 0, $n);
        $rec_name = substr($rec_file, $n+1);
        /* 若 rec_name 為 dir.rec 代表是目錄,就不必再檢查,直接回傳 true */
        if ($rec_name == DIR_RECORD)
            return true;
        /* 取得所在目錄的 record 檢查是否為 *.files 或 .sync 若是就不放到 index 中 */
        $dir_rec = rec2array($rec_dir."/".DIR_RECORD);
        if ((substr($dir_rec[0]["filename"], -6) == ".files") || ($dir_rec[0]["filename"] == SYNC_DIR))
            return false;

        return true;
    }

    /* 將 record 新增到 index 中 */
    function rec_put($rec_file, $url, $rebuild=false, $is_fun=false)
    {
        if (substr($url, -1) == "/")
            $url = substr($url, 0, -1);

        /* 檢查 record file 是否存在 */
        if (!file_exists($rec_file))
            return false;

        /* 檢查是否為子網站首頁,若是在 url 最後加上 '/' */
        $path_list = explode("/", $url);
        if (($path_list[1] == SUB_SITE_NAME) && (count($path_list) == 3))
            $url .= "/";

        /* 找出 index 目錄位置 */
        $all_index_dir = ALL_INDEX_DIR;
        $site_index_dir = get_site_index_dir($url);
        $dir_index_dir = get_dir_index_dir($url);

        /* 若是在重建 index,必須把 record 新增到新的 index 目錄內 */
        if ($rebuild == true)
        {
            $all_index_dir = substr($all_index_dir, 0, -1)."_new";
            $site_index_dir .= "_new";
            $dir_index_dir .= "_new";
        }

        /* 取出 record file 內容,並檢查此筆 record 是否要加到 index 中 (dir_index 所有 record 都要加入) */
        $in_index = true;
        $rec = rec2array($rec_file);
        if ($rec == false)
            return false;
        if ($is_fun == false)
            $in_index = chk_in_index($rec[0], $rec_file);

        /* 檢查若已設定不新增到 index 就不新增到 All index 與 Site index 中 */
        if ($in_index == false)
        {
            $all_rid = NULL;
            $site_rid = NULL;
        }
        else
        {
            /* 檢查是否要加入 All index 中 */
            $add_all_index = chk_add_all_index($url);

            /* 取得顯示用的路徑 */
            $view_path = get_view_path(WEB_ROOT_PATH.$url);

            /* 整理 record 內容 */
            $cnt = count($rec);
            for ($i = 0; $i < $cnt; $i++)
            {
                $rec_content = REC_START.REC_BEGIN_PATTERN;
                if ($is_fun == false)
                {
                    $rec_content .= "@url:$url\n";
                    $rec_content .= "@view_path:$view_path\n";
                }
                else
                {
                    $rec_content .= "@url:$url/".$rec[$i]["page_name"]."\n";
                    $rec_content .= "@view_path:$view_path/".$rec[$i]["page_name"]."\n";
                }
                foreach($rec[$i] as $key => $value)
                {
                    if (($key == GAIS_REC_FIELD) || ($key == "url"))
                        continue;
                    $value = trim($value);

                    /* 檢查縮圖位置 */
                    $dir_url = substr($url, 0, strrpos($url, "/")+1);
                    if (($key == "thumbs") && (!empty($value)))
                    {
                        $thumbs_url = $dir_url.$value;
                        if (file_exists(WEB_ROOT_PATH.$thumbs_url))
                            $value = $thumbs_url;
                    }

                    /* 檢查 flv 檔位置 */
                    if (($key == "flv") && (!empty($value)))
                    {
                        $flv_url = $dir_url.$value;
                        if (file_exists(WEB_ROOT_PATH.$flv_url))
                            $value = $flv_url;
                    }

                    $rec_content .= "@$key:$value\n";
                }

                /* 檢查是否要新增到 All index 中 */
                if ($add_all_index == false)
                    $all_rid = NULL;
                else
                    $all_rid = rput_content($all_index_dir, $rec_content);

                /* 新增到 site index 中 */
                $site_rid = rput_content($site_index_dir, $rec_content);
            }
        }

        /* 整理出 dir index 所需的 record,並新增到 dir index 中 */
        if ($is_fun == false)
        {
            $rec_content = get_dir_index_rec_content($rec[0], $url, $all_rid, $site_rid);
            rput_content($dir_index_dir, $rec_content, "page_name");
        }
    }

    /* 將 record 更新到 index 中 */
    function rec_update($rec_file, $url, $is_fun=false, $add_cnt=false)
    {
        if (substr($url, -1) == "/")
            $url = substr($url, 0, -1);

        /* 檢查 record file 是否存在 */
        if (!file_exists($rec_file))
            return false;

        /* 檢查是否為子網站首頁,若是在 url 最後加上 '/' */
        $path_list = explode("/", $url);
        if (($path_list[1] == SUB_SITE_NAME) && (count($path_list) == 3))
            $url .= "/";

        /* 找出 index 目錄位置 */
        $all_index_dir = ALL_INDEX_DIR;
        $site_index_dir = get_site_index_dir($url);
        $dir_index_dir = get_dir_index_dir($url);

        /* 取出 record file 內容,並檢查此筆 record 是否要加到 index 中 (dir_index 所有 record 都要加入) */
        $in_index = true;
        $rec = rec2array($rec_file);
        if ($rec == false)
            return false;
        if ($is_fun == false)
        {
            $in_index = chk_in_index($rec[0], $rec_file);

            /* 整理出 dir index 所需的 record,並更新到 dir index 中 */
            $rec_content = get_dir_index_rec_content($rec[0], $url);
            rupdate_content($dir_index_dir, $rec_content, "page_name", $rec[0]["page_name"]);
        }

        /* 檢查若已設定不更新到 index,就不處理 */
        if ($in_index == false)
            return;

        /* 檢查是否要新增到 All index 中 */
        if (chk_add_all_index($url))
        {
            //write_index_process_list("update", $all_index_dir, $rec_file, $url, $is_fun);
            if ($is_fun == true)
                rupdate_fun($all_index_dir, $rec_file, $url);
            else
                rupdate_file($all_index_dir, $rec_file, $url);
        }

        /* 更新 site index */
        if ($is_fun == true)
            rupdate_fun($site_index_dir, $rec_file, $url);
        else
            rupdate_file($site_index_dir, $rec_file, $url);
    }

    /* 將 record 從 index 中刪除 (僅刪除 url 本身) */
    function rec_delete($url)
    {
        if (substr($url, -1) == "/")
            $url = substr($url, 0, -1);

        /* 找出 index 目錄位置 */
        $all_index_dir = ALL_INDEX_DIR;
        $site_index_dir = get_site_index_dir($url);
        $dir_index_dir = get_dir_index_dir($url);

        /* 從 dir index 中刪除(需改用 page_name 來進行刪除而非 url) */
        $page_name = substr($url, strrpos($url, "/")+1);
        rdelete($dir_index_dir, "page_name", $page_name);

        /* 從 site index 中刪除 */
        rdelete($site_index_dir, "url", $url);

        /* 檢查是否要新增到 All index 中,若不是就不用刪除 */
        if (!chk_add_all_index($url))
            return;
        /* 從 all index 中刪除 */
        rdelete($all_index_dir, "url", $url);

    }

    /* 將符合的所有 url 從 index 中刪除 (所有在 url 底下的檔案或目錄都刪除) */
    function urls_delete($url)
    {
        if (substr($url, -1) == "/")
            $url = substr($url, 0, -1);

        /* 找出 index 目錄位置 */
        $all_index_dir = ALL_INDEX_DIR;
        $site_index_dir = get_site_index_dir($url);
        $dir_index_dir = get_dir_index_dir($url);

        /* 從 dir index 中刪除(需改用 page_name 來進行刪除而非 url) */
        $page_name = substr($url, strrpos($url, "/")+1);
        rdelete($dir_index_dir, "page_name", $page_name);

        /* 先取得要刪除的所有資料,從 site index 中刪除 */
        $del_list = get_url_match_list($site_index_dir, $url);
        del_index_url($site_index_dir, $del_list);

        /* 檢查是否要新增到 All index 中,若不是就不用刪除 */
        if (!chk_add_all_index($url))
            return;

        /* 先取得要刪除的所有資料,從 all index 中刪除 */
        $del_list = get_url_match_list($all_index_dir, $url);
        del_index_url($all_index_dir, $del_list);
    }

    /* 從 index 刪除 list 中 rid 的資料 */
    function del_index_url($index_dir, $list)
    {
        /* 整理 rid_list */
        $cnt = count($list);
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($i == 0)
                $rid_list = $list[$i]["rid"];
            else
                $rid_list .= ",".$list[$i]["rid"];
        }

        /* 執行 rdelete 將 index 中 rid_list 的資料設定成刪除 */
        rdelete($index_dir, "rid", $rid_list);
    }

    /* 重建 dir_index */
    function rebuild_dir_index($dir_url, $recursive=true)
    {
        if (substr($dir_url, -1) == "/")
            $dir_url = substr($dir_url, 0, -1);

        /* 檢查 dir_url 是否正確 (必須是目錄,且不可在系統設定目錄內) */
        $work_dir = WEB_ROOT_PATH.$dir_url;
        if ((empty($dir_url)) || (strstr($dir_url, NUWEB_SYS_FILE) !== false) || (!is_dir($work_dir)))
            return false;

        /* 檢查是否為子網站內的目錄 (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($work_dir, 0, $l) !== $site_path) || (strlen($work_dir) <= $l))
            return false;
        /* 2015/2/11 刪除,因修改新版權限,public_lib.php 必須 include Site_Prog/init.php 所以不必再 include 一次 */
        //require_once($site_path."init.php");

        /* 檢查是否為一般目錄,若是功能目錄就不處理 */
        if (chk_function_dir(WEB_PAGE_DIR, str_replace(WEB_PAGE_DIR, "", $work_dir) == true))
            return false;

        /* 取得 dir_index 目錄位置 */
        $dir_index_dir = $work_dir."/".NUWEB_REC_PATH.DIR_INDEX;
        $dir_index_dir_new = $dir_index_dir."_new";
        $dir_index_dir_old = $dir_index_dir."_old";

        /* 讀取 work_dir 目錄內的檔案與子目錄 */
        $l = strlen(NUWEB_SYS_FILE);
        $l1 = strlen(TN_FE_NAME);
        $handle = opendir($work_dir);
        while ($sub_name = readdir($handle))
        {
            /* 過濾掉 . & .. & index.html (目錄本身不加入 dir_index) & 縮圖 (.thumbs.jpg) & 系統設定檔 (.nuweb_*) */
            if (($sub_name == ".") || ($sub_name == "..") || ($sub_name == DEF_HTML_PAGE) || (substr($sub_name, -$l1) == TN_FE_NAME) || (substr($sub_name, 0, $l) == NUWEB_SYS_FILE))
                continue;

            /* 檢查若是 symlink 就不處理 */
            $file_path = $work_dir."/".$sub_name;
            if (is_link($file_path))
                continue;

            /* 找出 record file */
            $rec_file = get_file_rec_path($file_path);
            if ($rec_file === false)
                continue;

            /* 先整理出 dir index 所需的 record file,並更新到 dir index 中,更新完再刪除 */
            $tmp_rec_file = get_dir_index_rec_file($rec_file);
            rput($dir_index_dir_new, $tmp_rec_file, "page_name");
            unlink($tmp_rec_file);

            /* 若 recursive 為 true,且是子目錄,就繼續往下處理 */
            if (($recursive == true) && (is_dir($file_path)))
                rebuild_dir_index($dir_url."/".$sub_name, $recursive);
        }
        closedir($handle);

        /* 新舊 index 更換 */
        $cmd = "";
        if (is_dir($dir_index_dir_old))
            $cmd .= "rm -rf $dir_index_dir_old ; ";
        if (is_dir($dir_index_dir))
            $cmd .= "mv $dir_index_dir $dir_index_dir_old ; mv $dir_index_dir_new $dir_index_dir";
        else
            $cmd .= "mv $dir_index_dir_new $dir_index_dir";
        $fp = popen($cmd, "r");
        pclose($fp);

        return true;
    }

    /* 取得子網站的 index 目錄位置 */
    function get_site_index_dir($url, $index_path=NUWEB_INDEX_PATH)
    {
        if (substr($url, 0, 1) != "/")
            return false;
        $path_list = explode("/", substr($url, 1));
        if ($path_list[0] == SUB_SITE_NAME)
        {
            $site_index_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".$path_list[1]."/".$index_path;
            if (!is_dir($site_index_dir))
                rdb_gen($site_index_dir);
            return $site_index_dir;
        }
        return false;
    }

    /* 取得 dir index 目錄位置 */
    function get_dir_index_dir($url)
    {
        if (substr($url, 0, 1) != "/")
            return false;
        if (substr($url, -1) == "/")
            $url = substr($url, 0, -1);
        $n = strrpos($url, "/");
        if ($n === false)
            return false;
        /* 若 url 有 ? 代表不是一般檔案或目錄,就不進行處理 */
        if (strstr($url, "?") !== false)
            return false;
        $dir_index_dir = WEB_ROOT_PATH."/".substr($url, 0, $n+1).NUWEB_REC_PATH.DIR_INDEX;
        if (!is_dir($dir_index_dir))
            rdb_gen($dir_index_dir);
        return $dir_index_dir;
    }

    /* 整理 dir index 所需的 record content */
    function get_dir_index_rec_content($rec, $url, $all_rid=NULL, $site_rid=NULL)
    {
        Global $fe_type;

        /* 如果 page_name 中有發現 ? 或 & 代表是功能目錄內的資料,就不處理 */
        if ((strstr($rec["page_name"], "?") !== false) || (strstr($rec["page_name"], "&") !== false))
            return false;

        /* 若有傳入 all_rid 與 site_rid 就加入 record 中 */
        if ((!empty($all_rid)) && ($all_rid !== false))
            $rec["all_rid"] = $all_rid;
        if ((!empty($site_rid)) && ($site_rid !== false))
            $rec["site_rid"] = $site_rid;

        /* 如果是目錄要檢查是否有圖片,若有要取回第一張圖片的縮圖 */
        if ($rec["type"] == "Directory")
        {
            $path_list = explode("/", substr($url, 1));
            if ($path_list[0] == SUB_SITE_NAME)
                $page_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
            else
                $page_dir = WEB_ROOT_PATH."/".$path_list[0]."/".PAGES_DIR;
            $page_path = str_replace($page_dir, "", WEB_ROOT_PATH.$url);
            $first_tn_path = get_first_tn_pict($page_dir, $page_path);
            if ($first_tn_path !== false)
            {
                if ($first_tn_path == LOCK_DIR_PICT)
                    $first_tn = LOCK_DIR_PICT;
                else
                    $first_tn = substr($first_tn_path, strrpos($first_tn_path, "/")+1);
                $rec["first_tn"] = $first_tn;
            }
        }

        /* 如果是圖片就取出縮圖 */
        if (isset($fe_type[$rec["fe"]]) && ($fe_type[$rec["fe"]] == IMAGE_TYPE))
        {
            /* 整理縮圖 (只需取 med 與 big,因預設縮圖 已在 record 中) */
            $file_path = WEB_ROOT_PATH.$url;
            $med_tn_img_file = $file_path.MED_TN_FE_NAME;
            $med2_tn_img_file = $file_path.MED2_TN_FE_NAME;
            $big_tn_img_file = $file_path.BIG_TN_FE_NAME;
            if (file_exists($med_tn_img_file))
                $rec["med_tn_img"] = substr($med_tn_img_file, strrpos($med_tn_img_file, "/")+1);
            if (file_exists($med2_tn_img_file))
                $rec["med2_tn_img"] = substr($med2_tn_img_file, strrpos($med2_tn_img_file, "/")+1);
            if (file_exists($big_tn_img_file))
                $rec["big_tn_img"] = substr($big_tn_img_file, strrpos($big_tn_img_file, "/")+1);
        }

        /* 整理 record 內容 */
        $rec_content = "@\n";
        $rec_content .= REC_BEGIN_PATTERN;
        foreach($rec as $key => $value)
        {
            /* 將 content 欄位過濾掉 */
            if (($key == GAIS_REC_FIELD) || ($key == "content"))
                continue;
            $value = trim($value);
            $rec_content .= "@$key:$value\n";
        }

        return $rec_content;
    }

    /* 整理 dir index 所需的 record file */
    function get_dir_index_rec_file($rec_file, $all_rid=NULL, $site_rid=NULL)
    {
        Global $fe_type;

        /* record file 不存在就不處理 */
        if (!file_exists($rec_file))
            return false;

        /* 若是功能目錄的 fun.rec 不需加入 dir index,就不處理 */
        $l = strlen(FUN_RECORD);
        if (substr($rec_file, -$l) === FUN_RECORD)
            return false;

        /* 先取出原始 record file 與 url */
        $rec = rec2array($rec_file);
        $url = get_url_by_rec_file($rec_file);

        /* 如果 page_name 中有發現 ? 或 & 代表是功能目錄內的資料,就不處理 */
        if ((strstr($rec[0]["page_name"], "?") !== false) || (strstr($rec[0]["page_name"], "&") !== false))
            return false;

        /* 若有傳入 all_rid 與 site_rid 就加入 record 中 */
        if ((!empty($all_rid)) && ($all_rid !== false))
            $rec[0]["all_rid"] = $all_rid;
        if ((!empty($site_rid)) && ($site_rid !== false))
            $rec[0]["site_rid"] = $site_rid;

        /* 如果是目錄要檢查是否有圖片,若有要取回第一張圖片的縮圖 url */
        if ($rec[0]["type"] == "Directory")
        {
            $path_list = explode("/", substr($url, 1));
            if ($path_list[0] == SUB_SITE_NAME)
                $page_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
            else
                $page_dir = WEB_ROOT_PATH."/".$path_list[0]."/".PAGES_DIR;
            $page_path = str_replace($page_dir, "", WEB_ROOT_PATH.$url);
            $first_tn_path = get_first_tn_pict($page_dir, $page_path);
            if ($first_tn_page !== false)
            {
                if ($first_tn_path == LOCK_DIR_PICT)
                    $first_tn = LOCK_DIR_PICT;
                else
                    $first_tn = substr($first_tn_path, strrpos($first_tn_path, "/")+1);
                $rec[0]["first_tn"] = $first_tn;
            }
        }

        /* 如果是圖片就取出縮圖 url */
        if (isset($fe_type[$rec[0]["fe"]]) && ($fe_type[$rec[0]["fe"]] == IMAGE_TYPE))
        {
            /* 整理縮圖 url (只需取 med 與 big,因預設縮圖 url 已在 record 中) */
            $file_path = WEB_ROOT_PATH.$url;
            $med_tn_img_file = $file_path.MED_TN_FE_NAME;
            $med2_tn_img_file = $file_path.MED2_TN_FE_NAME;
            $big_tn_img_file = $file_path.BIG_TN_FE_NAME;
            if (file_exists($med_tn_img_file))
                $rec[0]["med_tn_img"] = substr($med_tn_img_file, strrpos($med_tn_img_file, "/")+1);
            if (file_exists($med2_tn_img_file))
                $rec[0]["med2_tn_img"] = substr($med2_tn_img_file, strrpos($med2_tn_img_file, "/")+1);
            if (file_exists($big_tn_img_file))
                $rec[0]["big_tn_img"] = substr($big_tn_img_file, strrpos($big_tn_img_file, "/")+1);
        }

        /* 整理 record 內容 */
        $rec_content = "@\n";
        $rec_content .= REC_BEGIN_PATTERN;
        foreach($rec[0] as $key => $value)
        {
            /* 將 content 欄位過濾掉 */
            if (($key == GAIS_REC_FIELD) || ($key == "content"))
                continue;
            $value = trim($value);
            $rec_content .= "@$key:$value\n";
        }

        /* 建立暫存的 tmp_rec_file,把更新後的 record 內容寫入 */
        $tmp_rec_file = tempnam(NUWEB_TMP_DIR, "rec_");
        if (($fp = @fopen($tmp_rec_file, "w")) == false)
            return;
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);

        return $tmp_rec_file;
    }

    /* 檢查是否要加入 All index 中 */
    function chk_add_all_index($url)
    {
        if ((chk_inDriver($url) == true) || (chk_public_site($url) == false))
            return false;
        return true;
    }

    /* 檢查是否為開放網站 */
    function chk_public_site($url)
    {
        if (substr($url, 0 ,1) != "/")
            return false;
        $path_list = explode("/", substr($url, 1));
        if ($path_list[0] !== SUB_SITE_NAME)
            return false;
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $site_acn = $path_list[1];
        $conf_file = $site_path.$site_acn."/".NUWEB_CONF;
        $site_conf = read_conf($conf_file);
        if ((isset($site_conf["public"])) && ($site_conf["public"] == NO))
            return false;
        return true;
    }

    /* 檢查是否為 Driver 目錄內的資料 */
    function chk_inDriver($url)
    {
        if (substr($url, 0 ,1) != "/")
            return false;
        $path_list = explode("/", substr($url, 1));
        if (($path_list[0] == SUB_SITE_NAME) && (isset($path_list[2])) && ($path_list[2] == DRIVER_DIR_NAME))
            return true;
        return false;
    }

    /* 檢查是否為 Driver 目錄 */
    function is_Driver($url)
    {
        if (substr($url, 0 ,1) != "/")
            return false;
        if (substr($url, -1) == "/")
            $url = substr($url, 0, -1);
        $path_list = explode("/", substr($url, 1));
        $cnt = count($path_list);
        if (($cnt == 3) && ($path_list[0] == SUB_SITE_NAME) && ($path_list[2] == DRIVER_DIR_NAME))
            return true;
        return false;
    }

    /* 真實搬移單一檔案或目錄 (若有傳入 subname 代表要搬移相關檔案,需將 subname 加到 src_file 與 target_file 後面) */
    function move_path($src_path, $target_path, $subname="")
    {
        $src_path = $src_path.$subname;
        $target_path = $target_path.$subname;
        if (!file_exists($src_path))
            return false;
        return rename($src_path, $target_path);
    }

    /* 搬移檔案或目錄與相關附屬檔 */
    function move_page_path($page_dir, $src_path, $target_path, $name="", $fmtime="")
    {
        Global $fe_type;

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

        /* 檢查 src_path 必須存在 */
        $src_page = $page_dir.$src_path;
        if (!file_exists($src_page))
            return false;

        /* target_path 必須是目錄 */
        $target_dir = $page_dir.$target_path;
        if (!is_dir($target_dir))
            return false;

        /* 檢查 src_path 所在的目錄是否與 target_dir 相同,若相同就不處理 (因為是自己 move 自己) */
        $pos = strrpos($src_page, "/");
        if ($pos === false)
            return false;
        $src_dir = substr($src_page, 0, $pos);
        $src_page_name = substr($src_page, $pos+1);
        if ($src_dir == $target_dir)
            return false;

        /* 如果沒傳入 name,取得原始名稱 */
        if (empty($name))
        {
            $name = get_file_name($page_dir, $src_path);
            if (($name === false) || (empty($name)))
                return false;
        }

        /* 檢查原始名稱是否已存在 target_path 中,若已存在就不處理 */
        if (filename_exists($page_dir, $target_path, $name) !== false)
            return false;
        $target_page_name = $src_page_name;
        $target_page_path = $target_path."/".$target_page_name;
        $target_page = $page_dir.$target_page_path;
        if (file_exists($target_page))
            return false;

        /* 從原始位置 move 到目的位置 */
        if (move_path($src_page, $target_page) === false)
            return false;

        /* 如果有傳入 fmtime 就要設定最後修改時間為 fmtime */
        if (!empty($fmtime))
            touch($target_page, $fmtime);

        /* 檢查搬移的是檔案 or 目錄分別進行相關處理 */
        if (is_dir($target_page))
        {
            $src_url = str_replace(WEB_ROOT_PATH, "", $src_page);
            $target_url = str_replace(WEB_ROOT_PATH, "", $target_page);
            /* 更新搬移後的 index */
            update_move_index($page_dir, $src_url, $target_url);

            /* 更新目錄內文章的 link 與功能目錄設定 */
            update_move_html_dir_config($page_dir, $src_url, $target_url, $target_page);

            /* 2013/7/26 新增檢查是否從 web-->Driver 或 Driver-->web,若是必須繼承上一層的 .nuweb_dir_set 與 .nuweb_def,要不然顯示或權限可能會有問題 */
            $src_in_Driver = chk_inDriver($src_url);
            $target_in_Driver = chk_inDriver($target_url);
            if ((($src_in_Driver == true) && ($target_in_Driver !== true)) || (($src_in_Driver !== true) && ($target_in_Driver == true)))
                update_copy_move_sys_file($target_page, $target_in_Driver);
        }
        else
        {
            /* 搬移及處理相關附屬檔案 */
            move_related_file($page_dir, $src_dir, $src_page_name, $target_dir, $target_page_name, $name);

            /* 更新預設的 Record */
            write_def_record($page_dir, $target_page_path, $name);
        }

        /* 更新 video list */
        update_move_file_content($page_dir.VIDEO_LIST, $src_path, $target_page_path);
        /* 更新 table list */
        update_move_file_content($page_dir."table.list", $src_path, $target_page_path);
        /* 更新其他相關 list */
        if (file_exists($page_dir.VIDEO_WAITING_LIST))
            update_move_file_content($page_dir.VIDEO_WAITING_LIST, $src_path, $target_page_path);
        if (file_exists(CONVERT_LIST))
            update_move_file_content(CONVERT_LIST, $src_path, $target_page_path);

        return $target_page_path;
    }

    /* 搬移及處理相關附屬檔案 */
    function move_related_file($page_dir, $src_dir, $src_page_name, $target_dir, $target_page_name, $name)
    {
        Global $fe_type;

        /* 1. 搬移縮圖檔 */
        $src_page = $src_dir."/".$src_page_name;
        $src_page_path = str_replace($page_dir, "", $src_page);
        $src_path = str_replace($page_dir, "", $src_dir);
        $src_url = str_replace(WEB_ROOT_PATH, "", $src_page);
        $target_page = $target_dir."/".$target_page_name;
        $target_page_path = str_replace($page_dir, "", $target_page);
        $target_path = str_replace($page_dir, "", $target_dir);
        $target_url = str_replace(WEB_ROOT_PATH, "", $target_page);
        move_path($src_page, $target_page, TN_FE_NAME);
        move_path($src_page, $target_page, MED_TN_FE_NAME);
        move_path($src_page, $target_page, MED2_TN_FE_NAME);
        move_path($src_page, $target_page, BIG_TN_FE_NAME);
        move_path($src_page, $target_page, SRC_TN_FE_NAME);

        /* 2. 搬移 record 檔 */
        $src_rec_dir = $src_dir."/".NUWEB_REC_PATH;
        $src_rec_file_prefix = $src_rec_dir.$src_page_name;
        $src_rec_file = $src_rec_file_prefix.".rec";
        $target_rec_dir = $target_dir."/".NUWEB_REC_PATH;
        $target_rec_file_prefix = $target_rec_dir.$target_page_name;
        /* 若 record 檔存在就先從 index 中刪除 */
        if (file_exists($src_rec_file))
        {
            /* 將該筆資料 delete 掉 */
            rec_delete($src_url);

            /* 若 target rec dir 不存在就建立 */
            if (!is_dir($target_rec_dir))
                mkdir($target_rec_dir);
        }
        move_path($src_rec_file_prefix, $target_rec_file_prefix, ".rec");
        move_path($src_rec_file_prefix, $target_rec_file_prefix, ".comment.rec");

        /* 3. 搬移影片轉檔 */
        $src_media_dir = $src_dir."/".NUWEB_MEDIA_PATH;
        $src_video_file_prefix = $src_media_dir.$src_page_name;
        $src_flv_file = $src_video_file_prefix.".flv";
        $src_mp4_file = $src_video_file_prefix.".mp4";
        $target_media_dir = $target_dir."/".NUWEB_MEDIA_PATH;
        $target_video_file_prefix = $target_media_dir.$target_page_name;
        $target_flv_file = $target_video_file_prefix.".flv";
        if (((file_exists($src_flv_file)) || (file_exists($src_mp4_file))) && (!is_dir($target_media_dir)))
            mkdir($target_media_dir);
        move_path($src_video_file_prefix, $target_video_file_prefix, ".flv");
        move_path($src_video_file_prefix, $target_video_file_prefix, ".480.flv");
        move_path($src_video_file_prefix, $target_video_file_prefix, ".720.flv");
        move_path($src_video_file_prefix, $target_video_file_prefix, ".mp4");
        move_path($src_video_file_prefix, $target_video_file_prefix, ".480.mp4");
        move_path($src_video_file_prefix, $target_video_file_prefix, ".720.mp4");

        /* 2014/6/13 新增,搬移 pdf 檔 */
        $src_pdf_dir = $src_dir."/".NUWEB_PDF_PATH;
        $src_pdf_file = $src_pdf_dir.$src_page_name.".pdf";
        $target_pdf_dir = $target_dir."/".NUWEB_PDF_PATH;
        $target_pdf_file = $target_pdf_dir.$target_page_name.".pdf";
        if ((file_exists($src_pdf_file)) && (!is_dir($target_pdf_dir)))
            mkdir($target_pdf_dir);
        move_path($src_pdf_file, $target_pdf_file);

        /* 2015/10/2 新增,搬移 mp3 檔 */
        $src_media_dir = $src_dir."/".NUWEB_MEDIA_PATH;
        $src_mp3_file = $src_media_dir.$src_page_name.".mp3";
        $target_media_dir = $target_dir."/".NUWEB_MEDIA_PATH;
        $target_mp3_file = $target_media_dir.$target_page_name.".mp3";
        if ((file_exists($src_mp3_file)) && (!is_dir($target_media_dir)))
            mkdir($target_media_dir);
        move_path($src_mp3_file, $target_mp3_file);

        /* 取得原始檔案的副檔名 */
        $pos = strrpos($src_page_name, '.');
        if ($pos == false)
            $fe = "";
        else
            $fe = strtolower(substr($src_page_name, $pos));

        /* 如果是網頁檔案,就檢查及 move 附件目錄 (.files),並調整網頁中的 link */
        if ($fe_type[$fe] == HTML_TYPE)
        {
            /* 若沒有找到來源檔的附件目錄,就不處理 */
            $src_files_path = get_files_dir($page_dir, $src_page_path, $name);
            if ($src_files_path === false)
                return;

            /* 將附件目錄搬移到目的目錄內 */
            $src_files_dir = $page_dir.$src_files_path;
            $src_files_url = str_replace(WEB_ROOT_PATH, "", $src_files_dir);
            $n = strrpos($src_files_path, "/");
            $target_files_dir = $target_dir."/".substr($src_files_path, $n+1);
            $target_files_url = str_replace(WEB_ROOT_PATH, "", $target_files_dir);
            move_path($src_files_dir, $target_files_dir);

            /* 更新有關 .files 目錄的 index */
            update_move_index($page_dir, $src_files_url, $target_files_url);

            /* 更新網頁中的 link */
            update_move_file_content($target_page, $src_path, $target_path);

            /* 更新 record 內的 link (主要是更新 images 欄位) */
            $rec_file = get_file_rec_path($target_page);
            if ($rec_file === false)
                continue;
            update_move_file_content($rec_file, $src_path, $target_path);
        }
    }

    /* 更新搬移目錄後的 index */
    function update_move_index($page_dir, $src_url, $target_url)
    {
        /* 1. 從 dir index 將 src_url 刪除掉*/
        $dir_index_dir = get_dir_index_dir($src_url);
        $page_name = substr($src_url, strrpos($src_url, "/")+1);
        rdelete($dir_index_dir, "page_name", $page_name);

        /* 2. 新增 target 到 dir index */
        $dir_index_dir = get_dir_index_dir($target_url);
        $rec_file = get_file_rec_path(WEB_ROOT_PATH.$target_url);
        rput($dir_index_dir, $rec_file, "page_name");

        /* 3. 將 All index 中符合 src_url 的資料替換成 target_url */
        rep_index($src_url, $target_url, ALL_INDEX_DIR);

        /* 4. 將 Site index 中符合 src_url 的資料替換成 target_url */
        $site_index_dir = get_site_index_dir($src_url);
        rep_index($src_url, $target_url, $site_index_dir);
    }

    /* 重新整理 record 內容,加入 url 欄位,並新增到 Site index 與 All index 中 */
    function update_target_index($rec_file, $url, $site_index_dir)
    {
        /* 整理 record 內容 */
        $rec = rec2array($rec_file);
        $cnt = count($rec);
        for ($i = 0; $i < $cnt; $i++)
        {
            $rec_content = REC_START.REC_BEGIN_PATTERN;
            $rec_content .= "@url:$url\n";
            foreach($rec[$i] as $key => $value)
            {
                if (($key == GAIS_REC_FIELD) || ($key == "url"))
                    continue;
                $value = trim($value);

                /* 檢查縮圖位置 */
                $dir_url = substr($url, 0, strrpos($url, "/")+1);
                if (($key == "thumbs") && (!empty($value)))
                {
                    $thumbs_url = $dir_url.$value;
                    if (file_exists(WEB_ROOT_PATH.$thumbs_url))
                        $value = $thumbs_url;
                }

                /* 檢查 flv 檔位置 */
                if (($key == "flv") && (!empty($value)))
                {
                    $flv_url = $dir_url.$value;
                    if (file_exists(WEB_ROOT_PATH.$flv_url))
                        $value = $flv_url;
                }

                $rec_content .= "@$key:$value\n";
            }

            /* 新增到 Site index */
            rput_content($site_index_dir, $rec_content);

            /* 檢查是否要新增到 All index 中 */
            if (chk_add_all_index($url))
                rput_content(ALL_INDEX_DIR, $rec_content);
        }
    }

    /* 更新搬移目錄後所有 HTML 網頁內的 link 與功能目錄設定 */
    function update_move_html_dir_config($page_dir, $src_url, $target_url, $target_dir)
    {
        Global $fe_type;

        /* 檢查是否有 dir_config.php (功能目錄),若有就必須調整內容 */
        $dir_config = $target_dir."/dir_config.php";
        if (file_exists($dir_config))
            update_move_file_content($dir_config, $src_url, $target_url);

        /* 讀取目錄內的子目錄與檔案 */
        $l = strlen(NUWEB_SYS_FILE);
        $handle = opendir($target_dir);
        while ($dir_file = readdir($handle))
        {
            /* 跳過子目錄名稱為 . & .. & .nuweb_* 不必處理 */
            if (($dir_file == ".") || ($dir_file == "..") || (substr($dir_file, 0, $l) == NUWEB_SYS_FILE))
                continue;

            /* 若是 symlink 也不處理 */
            $target_file_path = $target_dir."/".$dir_file;
            if (is_link($target_file_path))
                continue;

            /* 若是子目錄就繼續向下層處理*/
            if (is_dir($target_file_path))
                update_move_html_dir_config($page_dir, $src_url, $target_url, $target_file_path);

            /* 只處理 HTML 類型檔案 */
            $fe = strtolower(substr($dir_file, strrpos($dir_file, '.')));
            if ($fe_type[$fe] != HTML_TYPE)
                continue;

            /* 更新網頁內的 link */
            update_move_file_content($target_file_path, $src_url, $target_url);

            /* 更新 record 內的 link (主要是更新 images 欄位) */
            $rec_file = get_file_rec_path($target_file_path);
            if ($rec_file === false)
                continue;
            update_move_file_content($rec_file, $src_url, $target_url);
        }
        closedir($handle);
    }

    /* 更新 move 後的檔案內容 (主要更換原始與目的 Link 位置) */
    function update_move_file_content($file, $src_path, $target_path)
    {
        if (!file_exists($file))
            return false;

        $f_mtime = filemtime($file);
        $fp1 = fopen($file, "r");
        $fp2 = fopen($file."_new", "w");
        flock($fp1, LOCK_SH);
        flock($fp2, LOCK_EX);
        while($buf = fgets($fp1, MAX_BUFFER_LEN))
            fputs($fp2, str_replace($src_path, $target_path, $buf));
        flock($fp1, LOCK_UN);
        flock($fp2, LOCK_UN);
        fclose($fp1);
        fclose($fp2);
        unlink($file);
        rename($file."_new", $file);
        touch($file, $f_mtime);

        return true;
    }

    /* 更新 copy 或 move 後的相關目錄設定檔 (主要用於 web-->Driver 或 Driver-->web 時,需調整 .nuweb_def 與 .nuweb_dir_set) */
    function update_copy_move_sys_file($dir_path, $in_Driver, $last_dir=NULL)
    {
        if (substr($dir_path, -1) == "/")
            $dir_path = substr($dir_path, 0, -1);

        /* 要先將原本的 .nuweb_dir_set 與 .nuweb_def 刪除 */
        if (file_exists("$dir_path/.nuweb_dir_set"))
            unlink("$dir_path/.nuweb_dir_set");
        /* 2015/2/10 移除,已不再使用 .nuweb_def */
        //if (file_exists("$dir_path/.nuweb_def"))
        //    unlink("$dir_path/.nuweb_def");

        /* 因會向下繼承,所以只要找一次 last_dir,底下的目錄都可使用 */
        if ($last_dir == NULL) 
            $last_dir = substr($dir_path, 0, strrpos($dir_path, "/"));

        $def_file = $last_dir."/".NUWEB_DEF;
        $dir_set_file = $last_dir."/".NUWEB_DIR_SET;
        $dir_config = $dir_path."/dir_config.php";
        /* 將上一層目錄的 .nuweb_def copy 到 dir_path 目錄中 */
        /* 2015/2/10 修改,已不再使用 .nuweb_def,直接設定將目錄 record 內的權限相關欄位進行 reset,就可繼承上層目錄權限 */
        //if (file_exists($def_file))
        //    copy($def_file, $dir_path."/".NUWEB_DEF);
        set_rec_right_info($dir_path, "reset");

        /* 處理 .nuweb_dir_set */
        if (file_exists($dir_set_file))
        {
            /* 先讀取上一層的 .nuweb_dir_set */
            $dir_set = read_conf($dir_set_file);
            /* 若是在 Driver 內就設定 type 與 tpl_mode 為 OokonStorage */
            if ($in_Driver == true)
            {
                $dir_set["type"] = DRIVER_DIR_TYPE;
                $dir_set["tpl_mode"] = DRIVER_DIR_TYPE;
                /* 2015/7/24 新增,def_frame 設為 N */
                $dir_set["def_frame"] = NO;
            }
            /* 若是功能目錄 (目錄內有 dir_config.php),就清除 type 與 tpl_mode 項目 */
            if (file_exists($dir_config))
            {
                unset($dir_set["type"]);
                unset($dir_set["tpl_mode"]);
                /* 2015/7/24 新增,也要清除 def_frame 項目 */
                if (isset($dir_set["def_frame"]))
                    unset($dir_set["def_frame"]);
            }
            else
            {
                /* 若不是功能目錄,先取得 .nuweb_type 的設定值 */
                $type_file = $dir_path."/".NUWEB_TYPE;
                if (file_exists($type_file))
                    $type_conf = read_conf($type_file);
                /* 若是在 Driver 內 type 一律設為 directory 否則必須與 dir_set["type"] 一樣 */
                if ($in_Driver == true)
                    $type = GENERAL_DIR_TYPE;
                else
                    $type = $dir_set["type"];
                if ($type_conf["DIR_TYPE"] !== $type)
                {
                    $type_conf["DIR_TYPE"] = $type;
                    write_conf($type_file, $type_conf);
                }
                /* 2015/7/24 新增,要清除 def_frame 項目 */
                if (isset($dir_set["def_frame"]))
                    unset($dir_set["def_frame"]);
            }

            /* 儲存 .nuweb_dir_set */
            write_conf($dir_path."/".NUWEB_DIR_SET, $dir_set);
        }

        /* 檢查是否有 dir_config.php (功能目錄),若有就直接離開不向下繼承 */
        if (file_exists($dir_config))
            return;

        /* 向下繼承 */
        $l = strlen(NUWEB_SYS_FILE);
        $handle = opendir($dir_path);
        while ($sub_dir = readdir($handle))
        {
            /* 跳過子目錄名稱為 . & .. & .nuweb_* 不必處理 */
            if (($sub_dir == ".") || ($sub_dir == "..") || (substr($sub_dir, 0, $l) == NUWEB_SYS_FILE))
                continue;

            /* 只處理目錄 */
            $sub_dir_path = $dir_path."/".$sub_dir;
            if (is_dir($sub_dir_path))
                update_copy_move_sys_file($sub_dir_path, $in_Driver, $last_dir);
        }
        closedir($handle);
    }

    /* 將 index 檔案內的資料進行替換 (主要用於搬移目錄時,將 src_url 替換成 target_url) */
    function rep_index($src, $target, $index_dir)
    {
        $cmd = SEARCH_BIN_DIR."rdb -H $index_dir -replace \"$src\" \"$target\"";
        $fp = popen($cmd, "r");
        pclose($fp);
    }

    /* 更新 move 後的 file list */
    //function update_move_file_list($page_dir, $src_path, $target_path)
    //{
    //    if (substr($page_dir, -1) != "/")
    //        $page_dir .= "/";
    //    if (substr($src_path, -1) == "/")
    //        $src_path = substr($src_path, 0, -1);
    //    if (substr($target_path, -1) == "/")
    //        $target_path = substr($target_path, 0, -1);

        /* 取出 file.list 的資料 */
    //    $file_list_file = $page_dir.FILE_LIST;
    //    $new_file_list = $file_list_file."_new";

        /* 若發現 file list 是空的 or 不存在,就不處理 */
    //    if ((!file_exists($file_list_file)) || (real_filesize($file_list_file) === 0))
    //        return false;

    //    $fp = fopen($file_list_file, "r");
    //    $fp1 = fopen($new_file_list, "w");
    //    flock($fp, LOCK_SH);
    //    flock($fp1, LOCK_EX);
    //    $src_path_len = strlen($src_path);
    //    while($buf = fgets($fp, MAX_BUFFER_LEN))
    //    {
    //        list($path, $name) = explode("\t", trim($buf));
    //        if (empty($path) || empty($name))
    //            continue;

            /* 找出所有符合 src_path 的資料,並更換成 target_path */
    //        if (substr($path, 0, $src_path_len) == $src_path)
    //            $path = str_replace($src_path, $target_path, $path);

            /* 把資料加到新的 file list 中 */
    //        fputs($fp1, "$path\t$name\n");
    //    }
    //    flock($fp, LOCK_UN);
    //    flock($fp1, LOCK_UN);
    //    fclose($fp);
    //    fclose($fp1);

        /* 更新 file list 檔 */
    //    rename($file_list_file, $file_list_file."_old");
    //    rename($new_file_list, $file_list_file);
    //}

    /* copy or move directory */
    function copy_move_dir($mode, $src_dir, $target_dir, $sync=false)
    {
        /* mode 必須是 copy or move */
        if (($mode != "copy") && ($mode != "move"))
            return false;

        /* 如果 src_dir 不是目錄,就不處理 */
        if (!is_dir($src_dir))
            return false;

        /* 如果 target_dir 不存在,就建立目錄 */
        if (!is_dir($target_dir))
            mkdir($target_dir);

        /* 2015/5/8 新增,若 sync 為 true 就要將目的檔的時間調整成與來源檔相同,先取得來源檔時間 */
        if ($sync == true)
            $f_mtime = filemtime($src_dir);

        /* 讀取原始目錄內的子目錄與檔案 */
        $handle = opendir($src_dir);
        while ($dir_file = readdir($handle))
        {
            /* 跳過子目錄名稱為 . & .. 不必處理 */
            if (($dir_file == ".") || ($dir_file == ".."))
                continue;

            /* copy or move 所有的檔案與子目錄 (包括 link) */
            $src_file_path = $src_dir."/".$dir_file;
            $target_file_path = $target_dir."/".$dir_file;
            if (is_link($src_file_path))
                copy_move_link($mode, $src_file_path, $target_file_path, $sync);
            else if (is_dir($src_file_path))
                copy_move_dir($mode, $src_file_path, $target_file_path, $sync);
            else
                copy_move_file($mode, $src_file_path, $target_file_path, $sync);
        }
        closedir($handle);

        /* 2015/5/8 新增,若 sync 為 true 就將目的檔的時間設成來源檔時間 */
        if ($sync == true)
            touch($target_dir, $f_mtime);

        /* 如果是 move 就移除掉原始目錄 */
        if ($mode == "move")
            @rmdir($src_dir);

        return true;
    }

    /* copy or move file */
    function copy_move_file($mode, $src_file, $target_file, $sync=false)
    {
        /* mode 必須是 copy or move */
        if (($mode != "copy") && ($mode != "move"))
            return false;

        /* 如果原始檔不存在,就不處理 */
        if (!file_exists($src_file))
            return false;

        /* 如果目的檔已存在,就先刪除掉 */
        if (file_exists($target_file))
            unlink($target_file);

        /* 2015/5/8 新增,若 sync 為 true 就要將目的檔的時間調整成與來源檔相同,先取得來源檔時間 */
        if ($sync == true)
            $f_mtime = filemtime($src_file);

        /* 將原始檔案 copy or move 到目的檔案 */
        $status = false;
        if ($mode == "copy")
            $status = copy($src_file, $target_file);
        if ($mode == "move")
            $status = rename($src_file, $target_file);
        if ($status == false)
            return false;

        /* 2015/5/8 新增,若 sync 為 true 就將目的檔的時間設成來源檔時間 */
        if ($sync == true)
            touch($target_file, $f_mtime);

        return true;
    }

    /* copy or move link */
    function copy_move_link($mode, $src_file, $target_file, $sync=false)
    {
        /* mode 必須是 copy or move */
        if (($mode != "copy") && ($mode != "move"))
            return false;

        /* 如果原始檔不存在或不是 link,就不處理 */
        if ((!file_exists($src_file)) || (!is_link($src_file)))
            return false;

        /* 如果目的檔已存在,就先刪除掉 */
        if (file_exists($target_file))
            unlink($target_file);

        /* 2015/5/8 新增,若 sync 為 true 就要將目的檔的時間調整成與來源檔相同,先取得來源檔時間 */
        if ($sync == true)
            $f_mtime = filemtime($src_file);

        /* 設定 target 的 link */
        $link_path = readlink($src_file);
        symlink($link_path, $target_file);

        /* 2015/5/8 新增,若 sync 為 true 就將目的檔的時間設成來源檔時間 */
        if ($sync == true)
            touch($target_file, $f_mtime);

        /* 如果是 move 必須把 src_file 刪除 */
        if ($mode == "move")
            unlink($src_file);

        return true;
    }

    /* 檢查並取得 .files 目錄位置 */
    function get_files_dir($page_dir, $path, $name="")
    {
        Global $fe_type;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 取得檔案的正副檔名 */
        if (empty($name))
            $name = get_file_name($page_dir, $path);
        $pos = strrpos($name, '.');
        if ($pos != false)
        {
            $fe = strtolower(substr($name, $pos));
            $fn = substr($name, 0, $pos);
        }

        /* 只處理 HTML 類型檔案 */
        if ($fe_type[$fe] != HTML_TYPE)
            return false;

        /* 檢查網頁的 .files 是否存在,若不存在就不處理 */
        $n = strrpos($path, "/");
        if ($n !== false)
            $path_dir = substr($path, 0, $n+1);
        $files_path = filename_exists($page_dir, $path_dir, $fn.".files");

        return $files_path;
    }

    /* 找出 .files 目錄的原始文件位置 */
    function get_files_page($page_dir, $path)
    {
        Global $fe_type;

        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);

        /* 檢查 path 是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false) || (empty($path)))
            return false;

        /* 目錄才需要檢查 */
        if (!is_dir($page_dir.$path))
            return false;

        /* 取得檔案的正副檔名 */
        $name = get_file_name($page_dir, $path);
        $pos = strrpos($name, '.');
        if ($pos != false)
        {
            $fe = strtolower(substr($name, $pos));
            $fn = substr($name, 0, $pos);
        }

        /* 只處理 .files 目錄 */
        if ($fe !== ".files")
            return false;

        /* 檢查 .files 的原始網頁是否存在,若不存在就不處理 */
        $n = strrpos($path, "/");
        if ($n !== false)
            $path_dir = substr($path, 0, $n+1);
        $files_path = filename_exists($page_dir, $path_dir, $fn.".html");

        return $files_path;
    }

    /* 更新網頁檔中的 .files link */
    function update_files_link($page_dir, $html_file_path, $old_files_path, $new_files_path)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";

        /* 取出網頁檔內容 */
        $html_file = $page_dir.$html_file_path;
        if (!file_exists($html_file))
            return;
        $html_content = implode("", @file($html_file));

        /* 更換 .files link */
        $page_url = str_replace(WEB_ROOT_PATH, "", $page_dir);
        $old_files_url = $page_url.$old_files_path;
        $new_files_url = $page_url.$new_files_path;
        $new_html_content = str_replace($old_files_url, $new_files_url, $html_content);
        if ($new_html_content != $html_content)
        {
            $fp = fopen($html_file, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $new_html_content);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    /* 更新 copy or move 後的 file list */
    //function update_copy_move_file_list($mode, $page_dir, $src_path, $target_path)
    //{
    //    if (substr($page_dir, -1) != "/")
    //        $page_dir .= "/";
    //    if (substr($src_path, -1) != "/")
    //        $src_path .= "/";
    //    if (substr($target_path, -1) != "/")
    //        $target_path .= "/";

        /* 先取出 file list 資料 */
    //    $page_file_name = get_file_list($page_dir);
    //    if (empty($page_file_name))
    //        return false;

    //    $src_path_len = strlen($src_path);
    //    $change = false;
    //    foreach($page_file_name as $path => $name)
    //    {
            /* 找出所有符合的 file list */
    //        if (substr($path, 0, $src_path_len) == $src_path)
    //        {
                /* 把 target file 加到 file list 中 */
    //            $target_file_path = $target_path.substr($path, $src_path_len);
    //            $page_file_name[$target_file_path] = $name;

                /* 如果是 move 就把 src file 從 file list 中移除 */
    //            if ($mode == "move")
    //                unset($page_file_name[$path]);

    //            $change = true;
    //        }
    //    }

        /* 有變更就更新 file list 檔 */
    //    if ($change == true)
    //        rewrite_file_list($page_dir, $page_file_name);
    //}

    /* 更新 copy or move 後的 record file */
    function update_copy_move_rec($page_dir, $target_path, $clean_cnt=false, $sync=false)
    {
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        if (substr($target_path, -1) != "/")
            $target_path .= "/";

        /* 更新 record,並加入 All index */
        $page_file_name = get_index_list($page_dir, $target_path);
        foreach($page_file_name as $path => $name)
            write_def_record($page_dir, $path, $name, true, NULL, false, false, $clean_cnt, $sync);
    }

    /* 2014/10/14 新增,檢查是否要隱藏 */
    function chk_hidden($file_path)
    {
        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 取得 tag 資料,檢查若有 SYS_HIDE 就代表要隱藏 */
        $tag = get_rec_field($file_path, "tag");
        if (!empty($tag))
        {
            $list = explode(",", $tag);
            $cnt = count($list);
            for ($i = 0; $i < $cnt; $i++)
            {
                if (trim($list[$i]) == HIDDEN_TAG)
                    return true;
            }
        }
        /* 新增檢查上層目錄的 @allow 欄位,若有 ALLOW_HIDDEN 就代表要隱藏 */
        $file_dir = substr($file_path, 0, strrpos($file_path, "/"));
        $allow = get_rec_field($file_dir, "allow");
        if (strstr($allow, ALLOW_HIDDEN) !== false)
            return true;
        return false;
    }

    /* 2014/10/17 新增,更新 record file 的隱藏顯示 (更新 allow 欄位,若有 ALLOW_HIDDEN 代表要隱藏) */
    function update_rec_by_hidden($dir_path, $mode=NULL)
    {
        /* 若沒傳入 mode 參數就檢查目錄是否隱藏,若是隱藏就將 mode 設為 set,若不是隱藏就將 mode 設為 del */
        if (empty($mode))
        {
            if (chk_hidden($dir_path) == true)
                $mode = "set";
            else
                $mode = "del";
        }
        if (($mode !== "del") && ($mode !== "set"))
            return false;

        /* 2015/9/1 新增,將 dir_path 分解出 page_dir 與 page_path (call upload_dymanic_share_rec 時需要使用) */
        $page_dir = WEB_PAGE_DIR;
        $l = strlen($page_dir);
        if (substr($dir_path, 0, $l) !== $page_dir)
            return false;
        $page_path = substr($dir_path, $l);
        if (substr($page_path, -1) == "/")
            $page_path = substr($page_path, 0, -1);

        /* 若是檔案就直接更新 record file 的隱藏顯示 */
        if (!is_dir($dir_path))
        {
            /* 取得原 allow 欄位內容,並檢查 ALLOW_HIDDEN 是否已存在 */
            $allow = get_rec_field($dir_path, "allow");
            $a_list = explode(",", $allow);
            $cnt = count($a_list);
            $exist = false;
            $new_allow = "";
            for ($i = 0; $i < $cnt; $i++)
            {
                if ($a_list[$i] == ALLOW_HIDDEN)
                {
                    $exist = true;
                    if ($mode == "del")
                        continue;
                }
                if (!empty($new_allow))
                    $new_allow .= ",";
                $new_allow .= $a_list[$i];
            }
            /* 若 mode = set 時,allow 內已有 ALLOW_HIDDEN 就不需要變更,若沒有就加入 ALLOW_HIDDEN */
            if ($mode == "set")
            {
                if ($exist == true)
                    return true;
                if (empty($new_allow))
                    $rec["allow"] = ALLOW_HIDDEN;
                else
                    $rec["allow"] = ALLOW_HIDDEN.",$new_allow";
            }
            /* 若 mode = del 時,allow 內沒有 ALLOW_HIDDEN 就不需要變更,若已存在就移除 ALLOW_HIDDEN */
            if ($mode == "del")
            {
                if ($exist != true)
                    return true;
                $rec["allow"] = $new_allow;
            }

            /* 取得 record file 位置,並更新 record file 的 allow 欄位內容 */
            $rec_file = get_file_rec_path($dir_path);
            update_rec_file($rec_file, $rec);

            /* 2015/9/1 新增,若是設定隱藏時,必須清除動態 share record */
            if ($mode == "set")
                upload_dymanic_share_rec($page_dir, $page_path, "del");
            return true;
        }

        /* record 目錄存在才進行處理 */
        if (substr($dir_path, -1) != "/")
            $dir_path .= "/";
        $rec_dir = $dir_path.NUWEB_REC_PATH;
        if (!is_dir($rec_dir))
            return false;

        /* 取得 record 目錄內的所有 record 檔 */
        $handle = opendir($rec_dir);
        while ($rec_name = readdir($handle))
        {
            $rec_file = $rec_dir.$rec_name;

            /* 只處理 record 檔 (但不可是 .comment.rec) */
            if ((substr($rec_name, -4) !== ".rec") || (substr($rec_file, -12) == ".comment.rec"))
                continue;

            /* 取出 record file 資料,並整理 allow 欄位 */
            $rec = rec2array($rec_file);
            $cnt = count($rec);
            $change = false;
            for ($i = 0; $i < $cnt; $i++)
            {
                if ($mode == "del")
                {
                    /* 若 mode=del 時,allow 欄位中沒有 ALLOW_HIDDEN 就不必處理 */
                    if (strpos($rec[$i]["allow"], ALLOW_HIDDEN) === false)
                        continue;
                    /* 更新 allow 欄位內容,將 ALLOW_HIDDEN 刪除掉 */
                    $list = explode(",", $rec[$i]["allow"]);
                    $n = count($list);
                    $rec[$i]["allow"] = "";
                    for ($j = 0; $j < $n; $j++)
                    {
                        if (trim($list[$j]) == ALLOW_HIDDEN)
                            continue;
                        if (!empty($rec[$i]["allow"]))
                            $rec[$i]["allow"] .= ",";
                        $rec[$i]["allow"] .= $list[$j];
                    }
                }
                if ($mode == "set")
                {
                    /* 若 mode=set 時,allow 欄位中已經有 ALLOW_HIDDEN 就不必處理 */
                    if (strpos($rec[$i]["allow"], ALLOW_HIDDEN) !== false)
                        continue;
                    /* 更新 allow 欄位內容,將 ALLOW_HIDDEN 刪除掉 */
                    if (empty($rec[$i]["allow"]))
                        $rec[$i]["allow"] = ALLOW_HIDDEN;
                    else
                        $rec[$i]["allow"] = ALLOW_HIDDEN.",".$rec[$i]["allow"];
                }
                /* 設定需要變更 record 檔 */
                $change = true;
            }
            /* 把更新後的 record 內容寫回 record file */
            if ($change == true)
            {
                if ($cnt > 1)
                    write_multi_rec_file($rec_file, $rec);
                if ($cnt == 1)
                    update_rec_file($rec_file, $rec[0]);
            }
        }
        closedir($handle);

        /* 向下層子目錄進行更新 */
        $handle = opendir($dir_path);
        $l = strlen(NUWEB_SYS_FILE);
        while ($sub_name = readdir($handle))
        {
            /* 過濾掉 . & .. & nuweb 系統檔(目錄) */
            if (($sub_name == ".") || ($sub_name == "..") || (substr($sub_name, 0, $l) == NUWEB_SYS_FILE))
                continue;

            /* 如果是子目錄,就繼續往下層處理 */
            $sub_dir = $dir_path.$sub_name."/";
            if (is_dir($sub_dir))
            {
                /* 向下層子目錄更新時,若 mode=del 要改成 mode=NULL 讓程式自動檢查下層是否有設定隱藏 */
                if ($mode == "del")
                    $mode = NULL;
                update_rec_by_hidden($sub_dir, $mode);
            }
        }
        closedir($handle);

        /* 2015/9/1 新增,若是設定隱藏時,必須清除動態 share record (必須在 page_path 後面加上 '/' 才能清除目錄底下的所有動態訊息) */
        if ($mode == "set")
            upload_dymanic_share_rec($page_dir, $page_path."/", "del");
        return true;
    }

    /* 將 html 轉成 text */
    function html2text($html)
    {
        $search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
            "'&(quot|#34);'i",
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
            "'&#(\d+);'e");
        $replace = array ("",
            "\"",
            "&",
            "<",
            ">",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            "chr(\\1)");
        $html = preg_replace ($search, $replace, $html);
        return strip_tags($html);
    }

    /* 可取得真實的 file size (可正常取得超過 2GB 以上的 file size) */
    function real_filesize($file) 
    {
        $fsize = filesize($file);
        if ($fsize >= 0)
            return $fsize;

        $i = 0;
        $fp = fopen($file, "r");
        flock($fp, LOCK_SH);
        while (strlen(fread($fp, 1)) === 1)
        {
            fseek($fp, PHP_INT_MAX, SEEK_CUR);
            $i++;
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        if ($i % 2 == 1)
            $i--;
        $filesize = ((float)($i) * (PHP_INT_MAX + 1)) + $fsize;
        return $filesize;
    }

    /* 可 fseek 到 2GB 以外的檔案 */
    function fseek64(&$fh, $offset)
    {
        fseek($fh, 0, SEEK_SET);

        if ($offset <= PHP_INT_MAX)
            return fseek($fh, $offset, SEEK_SET);

        $t_offset = PHP_INT_MAX;
        $offset = $offset - $t_offset;

        while (fseek($fh, $t_offset, SEEK_CUR) === 0)
        {
            if ($offset > PHP_INT_MAX)
            {
                $t_offset = PHP_INT_MAX;
                $offset = $offset - $t_offset;
            }
            else if ($offset > 0)
            {
                $t_offset = $offset;
                $offset = 0;
            }
            else
                return 0;
        }

        return -1;
    }

    /* 利用片段方式讀取檔案 (可避免 readfile() 無法讀取超過 4GB 檔案的問題) */
    function readfile_chunked($filename, $retbytes=true, $nPos=null, $nEnd=null)
    {
        $buffer = '';
        $cnt = false;
        $cnt_read = 0;
        $handle = fopen($filename, 'rb');
        if ($handle === false)
            return false;

        if (empty($nPos))
        {
            while (!feof($handle))
            {
                $buffer = fread($handle, CHUNK_SIZE);
                echo $buffer;
                flush();
                if ($retbytes)
                    $cnt_read += strlen($buffer);
            }
        }
        else
        {
            $pos = $nPos;
            while ($pos > 0)
            {
                $l = min($pos, PHP_INT_MAX);
                fseek($handle, $l, SEEK_CUR);
                $pos -= $l;
            }

            if (!empty($nEnd))
                $cnt = $nEnd - $nPos + 1;

            while (!feof($handle))
            {
                $l = (!empty($nEnd)) ? min($cnt - $cnt_read, CHUNK_SIZE) : CHUNK_SIZE;
                $buffer = fread($handle, $l);
                echo $buffer;
                flush();
                $cnt_read += strlen($buffer);
                if ((!empty($nEnd)) && ($cnt_read >= $cnt))
                    break;
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status)
            return $cnt_read; // return num. bytes delivered like readfile() does.

        return $status;
    }

    /* 真實刪除目錄 */
    function real_rm_dir($page_dir, $path)
    {
        /* 檢查 page_dir 是否正確 */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        $root_len = strlen(WEB_ROOT_PATH)+1;
        if (strstr($page_dir, WEB_ROOT_PATH) !== $page_dir)
            return false;
        $site_path = substr($page_dir, $root_len);
        if ((strstr($site_path, EXT_SITE_NAME) != $site_path) && (strstr($site_path, INT_SITE_NAME) != $site_path) && (strstr($site_path, SUB_SITE_NAME) != $site_path))
            return false;

        /* 檢查目錄是否正確 */
        if (($path[0] == '/') || (strstr($path, "..") !== false))
            return false;

        $dir = $page_dir.$path;
        if (!is_dir($dir))
            return false;
        $cmd = "rm -rf $dir";
        $fp = popen($cmd, "r");
        pclose($fp);
        return true;
    }

    /* 取出 path list */
    function get_path_list($page_dir, $path_name)
    {
        $path = explode("/", $path_name);
        $path_cnt = count($path);
        $now_path = $path[0];
        for ($i = 0; $i < $path_cnt; $i++)
        {
            if ($i > 0)
                $now_path .= "/".$path[$i];
            $path_list[$i]["path"] = $now_path;
            $path_list[$i]["path_name"] = get_file_name($page_dir, $now_path);
        }
        return $path_list;
    }

    /* 2015/3/6 新增,使用 curl 取得 url 內容 */
    function get_url_by_curl($url, $connect_timout=5000)
    {
        /* 2015/5/12 新增,檢查並設定 User Agent */
        if ((isset($_SERVER['HTTP_USER_AGENT'])) && (!empty($_SERVER['HTTP_USER_AGENT'])))
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        else
            $userAgent = DEF_USER_AGENT;

        $url = trim($url);
        if ((substr($url, 0, 7) !== "http://") && (substr($url, 0, 8) !== "https://"))
            return false;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($ch, CURLOPT_HEADER, false);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connect_timout);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        if (substr($url, 0, 8) == "https://")
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);  
        return $data;  
    }  

    /* 檢查參數格式 */
    function chk_arg_format($format, $arg)
    {
        /* 檢查參數格式是否正確 */
        if (!preg_match($format, $arg))
            return false;
        return true;
    }

    /* 取得 Local IP */
    /* 2014/11/12 修改,增加 all_ip 參數,若為 true 代表要取得所有 local ip (可能有多張網卡),會輸出 local ip 的 array,若為 false 僅輸出第一筆 local ip*/
    function get_local_ip($all_ip=false)
    {
        $cmd = GET_LOCAL_IP_CMD;
        $fp = popen($cmd, "r");
        //$local_ip = trim(fgets($fp, MAX_BUFFER_LEN));
        $n = 0;
        while($l_ip = @fgets($fp, MAX_BUFFER_LEN))
            $local_ip[$n++] = trim($l_ip);
        pclose($fp);
        if ($all_ip == true)
            return $local_ip;
        return $local_ip[0];
    }

    /* 檢查是否為 local lan */
    function chk_local_lan($ip=NULL)
    {
        if (empty($ip))
            $ip = $_SERVER['REMOTE_ADDR'];
        if (($ip == "127.0.0.1") || ($ip == "localhost"))
            return true;
        if (empty($ip))
            return false;

        /* 取得 Local ip */
        $local_ip = get_local_ip();

        /* 若 ip 位置前3碼與 Local ip 相同,就代表是在 Local LAN 中 */
        if (substr($ip, 0, strrpos($ip, ".")) === substr($local_ip, 0, strrpos($local_ip, ".")))
            return true;
        return false;
    }

    /* 檢查是否為一般內部 IP 範圍 (10.x.x.x 或 192.168.x.x)*/
    function chk_int_net_ip($ip=NULL)
    {
        if (empty($ip))
            $ip = get_local_ip();
        if ($ip == "127.0.0.1")
            return true;
        $ip_item = explode(".", $ip);
        if (($ip_item[0] == "10") || (($ip_item[0] == "192") && ($ip_item[1] == "168")))
            return true;
        return false;
    }

    /* 取得目前的 microtime */
    function get_microtime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /* 回傳錯誤 herader */
    function err_header($err_code)
    {
        switch($err_code)
        {
            case 401:
                header("HTTP/1.1 401 Unauthorized");
                header("WWW-Authenticate: Basic realm=\"NUWeb\"");
                break;

            case 403:
                header("HTTP/1.1 403 Forbidden");
                echo "<h1>403 Access Forbiden!</h1>";
                echo "Page access is forbidden.<br>";
                break;

            case 404:
                header('HTTP/1.0 404 Not Found');
                echo "<h1>404 Not Found</h1>";
                echo "The page that you have requested could not be found.<br>";
                break;

            case 500:
                header('HTTP/1.0 500 Not Found');
                echo "<h1>500 Internal Server Error</h1>";
                break;

            default:
                header("HTTP/1.1 ".$err_code);
                echo "<h1>$err_code</h1>";
        }
        exit;
    }

    /*** 檔案版本相關函數 ***/
    /* 將檔案設定新版本 */
    function set_file_ver($file_path)
    {
        /* 檢查檔案是否存在 */
        if (!file_exists($file_path))
            return false;

        /* 先取回上次修改者帳號,若沒有此欄位資料,就改取 owner 欄位資料 */
        $last_acn = get_rec_field($file_path, "last_acn");
        if (($last_acn == false) || ($last_acn == NULL) || (empty($last_acn)))
            $last_acn = get_rec_field($file_path, "owner");

        /* 將相關檔案搬移到版本目錄內並取得版本編號 (版本編號就是版本目錄的副檔名) */
        $file_ver_path = move_ver($file_path);
        $ver = substr($file_ver_path, strrpos($file_ver_path, ".")+1);

        /* 記錄檔案版本資訊 */
        $ver_log = get_ver_log_path($file_path);
        write_ver_log($ver_log, $ver, $last_acn);

        /* 傳回版本編號 */
        return $ver;
    }

    /* 取得檔案的所有版本 */
    function get_file_ver($file_path)
    {
        /* 檢查檔案是否存在 */
        if (!file_exists($file_path))
            return false;

        /* 本功能僅提供網站管理員使用 */
        if (chk_site_manager($file_path) === false)
            return false;

        $ver_log = get_ver_log_path($file_path);
        if (($ver_log === false) || (!file_exists($ver_log)))
            return false;

        $ver_list = array();
        $vlist = @file($ver_log);
        $cnt = count($vlist);
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 2015/12/4 修改,log 增加 IP 項目 (為避免舊版本 log 沒有 IP 資料會出現 PHP 的警告訊息,修改設定方式) */
            //list($ver_list[$i]["time"], $ver_list[$i]["last_acn"], $ver_list[$i]["acn"], $ver_list[$i]["ver"]) = explode("\t", trim($vlist[$i]));
            $v_item = array();
            $v_item = explode("\t", trim($vlist[$i]));
            $ver_list[$i]["time"] = $v_item[0];
            $ver_list[$i]["last_acn"] = $v_item[1];
            $ver_list[$i]["acn"] = $v_item[2];
            $ver_list[$i]["ver"] = $v_item[3];
            if (isset($v_item[4]))
                $ver_list[$i]["ip"] = $v_item[4];
            else
                $ver_list[$i]["ip"] = NULL;
        }
        return $ver_list;
    }

    /* 刪除檔案版本 */
    function del_file_ver($file_path, $ver="all")
    {
        /* 檢查檔案是否存在 */
        if ((!file_exists($file_path)) || (empty($ver)))
            return false;

        /* 本功能僅提供網站管理員使用 */
        if (chk_site_manager($file_path) === false)
            return false;

        /* 若 ver=all 代表要刪除 file_path 的所有版本目錄 */
        if ($ver === "all")
        {
            /* 取得檔案的所有版本資訊,並逐一刪除 */
            $ver_log = get_ver_log_path($file_path);
            if (($ver_log === false) || (!file_exists($ver_log)))
                return false;
            $vlist = @file($ver_log);
            $cnt = count($vlist);
            for ($i = 0; $i < $cnt; $i++)
            {
                /* 2015/12/4 版本記錄檔增加 IP 記錄,因舊版本沒有 IP 資料且刪除檔案版本時不需 IP 資料,所以下列程式沒有增加取得 IP 資料 */
                list($l_time, $l_last_acn, $l_acn, $l_ver) = explode("\t", trim($vlist[$i]));
                if (empty($l_ver))
                    continue;
                /* 取得版本目錄,並刪除 */
                $file_ver_path = get_file_ver_path($file_path, $l_ver);
                $cmd = "rm -rf $file_ver_path";
                $fp = popen($cmd, "r");
                pclose($fp);
            }
            /* 刪除版本資訊檔 */
            unlink($ver_log);
        }
        else
        {
            /* 刪除版本目錄 */
            $file_ver_path = get_file_ver_path($file_path, $ver);
            $cmd = "rm -rf $file_ver_path";
            $fp = popen($cmd, "r");
            pclose($fp);
            /* 最後從檔案版本資訊檔中將被還原的版本移除掉 */
            remove_ver_log($file_path, $ver);
        }
        return true;
    }

    /* 還原檔案版本 */
    function recover_file_ver($file_path, $ver)
    {
        /* 檢查檔案是否存在 */
        if ((!file_exists($file_path)) || (empty($ver)))
            return false;

        /* 本功能僅提供網站管理員使用 */
        if (chk_site_manager($file_path) === false)
            return false;

        /* 先將目前檔案設定新版本  */
        if (set_file_ver($file_path) === false)
            return false;

        /* 再將要還原的版本還原回來 */
        if (move_ver($file_path, $ver, "recover") === false)
            return false;
        return true;
    }

    /* 取得版本實體檔案 */
    function read_ver_file($file_path, $ver, $code=NULL)
    {
        /* 檢查檔案是否存在 */
        if ((!file_exists($file_path)) || (empty($ver)))
            return false;

        /* 若有傳入 code 就以此參數,先取得登入者資料 */
        if (!empty($code))
        {
            $user = get_login_user($code);
            $acn = $user["acn"];
        }
        else
            $acn = "";

        /* 本功能僅提供網站管理員使用 */
        if (chk_site_manager($file_path, $acn) === false)
        {
            /* 2013/9/5 新增檢查 mail,因管理者設定可能使用 mail */
            if ((empty($acn)) || (empty($user["mail"])) || (chk_site_manager($file_path, $user["mail"]) === false))
                return false;
        }

        /* 取得並檢查版本目錄位置 */
        $file_ver_path = get_file_ver_path($file_path, $ver);
        if (($file_ver_path === false) || (!is_dir($file_ver_path)))
            return false;

        /* 先將 file_path 分離出 path_name & file_name */
        $n = strrpos($file_path, "/");
        $path_name = substr($file_path, 0, $n);
        $file_name = substr($file_path, $n+1);

        /* 取得版本實體檔位置並輸出 */
        $ver_file = $file_ver_path."/".$file_name;
        if (!file_exists($ver_file))
            return false;
        return readfile_chunked($ver_file);
    }

    /* 將相關檔案搬移到版本目錄或還原回來 */
    function move_ver($file_path, $ver=NULL, $mode="set")
    {
        Global $fe_type;

        /* 檢查 file_path 是否存在 */
        if (!file_exists($file_path))
            return false;

        if ($mode != "recover")
            $mode = "set";

        /* 若有傳入 ver 就取得版本目錄位置 */
        if (!empty($ver))
            $file_ver_path = get_file_ver_path($file_path, $ver);
        else
        {
            /* 若沒傳入 ver 且 mode=set 代表要自動建立新的版本目錄 */
            if ($mode !== "set")
                return false;
            $file_ver_path = get_file_ver_path($file_path);
        }

        /* 檢查版本目錄是否存在 */
        if (!is_dir($file_ver_path))
            return false;

        /* 先將 file_path 分離出 path_name & file_name */
        $n = strrpos($file_path, "/");
        $path_name = substr($file_path, 0, $n);
        $file_name = substr($file_path, $n+1);

        /* mode = set,代表要將檔案搬移到版本目錄 */
        if ($mode == "set")
        {
            /* 將相關檔案搬移(包含縮圖檔)到版本目錄中 */
            $cmd = "mv $file_path* $file_ver_path ";
            /* 若有影片轉檔也要搬移過去 */
            /* 2015/10/1 修改,先檢查是否為影片檔 */
            $fe = strtolower(substr($file_path, strrpos($file_path, ".")));
            $media_path = $path_name."/".NUWEB_MEDIA_PATH;
	    //if ((file_exists($media_path.$file_name.".flv")) || (file_exists($media_path.$file_name.".mp4")))
            if (($fe_type[$fe] == VIDEO_TYPE) && (is_dir($media_path)))
            {
                $ver_media_path = $file_ver_path."/".NUWEB_MEDIA_PATH;
                mkdir($ver_media_path);
                /* 2015/10/1 修改,後面加上 rmdir $vet_media_path 指令,避免沒有影片轉檔時可清除空的 ver_media_path 目錄 */
                $cmd .= "; mv $media_path$file_name* $ver_media_path ; rmdir $ver_media_path";
            }
            /* 2015/10/1 新增,若有 mp3 轉檔也要搬移過去 */
            if (file_exists($media_path.$file_name.".mp3"))
            {
                $ver_media_path = $file_ver_path."/".NUWEB_MEDIA_PATH;
                mkdir($ver_media_path);
                $cmd .= "; mv $media_path$file_name* $ver_media_path";
            }
            /* 2014/6/13 新增,若有 pdf 轉檔也要搬移過去 */
            $pdf_path = $path_name."/".NUWEB_PDF_PATH;
            if (file_exists($pdf_path.$file_name.".pdf"))
            {
                $ver_pdf_path = $file_ver_path."/".NUWEB_PDF_PATH;
                mkdir($ver_pdf_path);
                $cmd .= "; mv $pdf_path$file_name* $ver_pdf_path";
            }
            /* 找出 record file 位置,並 copy 到版本目錄中 */
            $rec_file = get_file_rec_path($file_path);
            if ($rec_file !== false)
            {
                $ver_rec_path = $file_ver_path."/".NUWEB_REC_PATH;
                mkdir($ver_rec_path);
                $cmd .= "; cp $rec_file $ver_rec_path";
            }
            $fp = popen($cmd, "r");
            pclose($fp);
            /* 建立空的 file_path */
            touch($file_path, filemtime($file_ver_path."/".$file_name));

            return $file_ver_path;
        }

        /* mode = recover 代表要還原版本 */
        if ($mode == "recover")
        {
            /* 將相關檔案(包含縮圖檔)還原(copy)到原目錄 */
            $cmd = "cp -f $file_ver_path/$file_name* $path_name/ ";
            /* 將影片轉檔還原 */
            $ver_media_path = $file_ver_path."/".NUWEB_MEDIA_PATH;
            if (is_dir($ver_media_path))
            {
                $media_path = $path_name."/".NUWEB_MEDIA_PATH;
                if (!is_dir($media_path))
                    mkdir($media_path);
                $cmd .= "; cp -f $ver_media_path$file_name* $media_path ";
            }
            /* 將 PDF 轉檔還原 */
            $ver_pdf_path = $file_ver_path."/".NUWEB_PDF_PATH;
            if (is_dir($ver_pdf_path))
            {
                $pdf_path = $path_name."/".NUWEB_PDF_PATH;
                if (!is_dir($pdf_path))
                    mkdir($pdf_path);
                $cmd .= "; cp -f $ver_pdf_path$file_name* $pdf_path ";
            }
            /* 將 record 檔還原 */
            $ver_rec_path = $file_ver_path."/".NUWEB_REC_PATH;
            if (is_dir($ver_rec_path))
            {
                $rec_path = $path_name."/".NUWEB_REC_PATH;
                if (!is_dir($rec_path))
                    mkdir($rec_path);
                $cmd .= "; cp -f $ver_rec_path* $rec_path ";
            }
            $fp = popen($cmd, "r");
            pclose($fp);

            /* 更新 record (與 index) */
            $page_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
            $l = strlen($page_dir);
            write_def_record($page_dir, substr($file_path, $l), "", true);

            return true;
        }
    }

    /* 取得版本目錄的位置 (若沒傳入 ver 參數,代表要建立新的版本) */
    function get_file_ver_path($file_path, $ver="")
    {
        /* 檢查檔案是否存在 */
        if (!file_exists($file_path))
            return false;

        /* 先將 file_path 分離出 path_name & file_name */
        $n = strrpos($file_path, "/");
        $path_name = substr($file_path, 0, $n);
        $file_name = substr($file_path, $n+1);

        /* 整理版本工作目錄位置 */
        $ver_dir = $path_name."/".NUWEB_VER_PATH;
        if (!is_dir($ver_dir))
        {
            /* 若版本工作目錄不存在就先建立 */
            if ((!empty($ver)) || (mkdir($ver_dir) === false))
                return false;
        }

        /* 若沒傳入 ver 參數,代表要建立新的版本目錄 */
        if (empty($ver))
        {
            /* 取出 file_path 的 mtime 當成是版本編號 */
            $mtime = date("YmdHis", filemtime($file_path));
            $ver = $mtime;
            $file_ver_path = $ver_dir.$file_name.".".$ver;

            /* 若此版本編號已存在,就自動再設定序號 */
            $n = 1;
            while (file_exists($file_ver_path))
            {
                $ver = $mtime."-".$n++;
                $file_ver_path = $ver_dir.$file_name.".".$ver;
            }
            /* 建立新的版本目錄 */
            mkdir($file_ver_path);
        }
        else
        {
            /* 有傳入 ver 參數,代表要找已存在版本目錄,整理版本目錄位置,並檢查是否存在 */
            $file_ver_path = $ver_dir.$file_name.".".$ver;
            if (!is_dir($file_ver_path))
                return false;
        }
        return $file_ver_path;
    }

    /* 找出檔案版本資訊檔位置 */
    function get_ver_log_path($file_path)
    {
        /* 檢查檔案是否存在 */
        if (!file_exists($file_path))
            return false;

        /* 先將 file_path 分離出 path_name & file_name */
        $n = strrpos($file_path, "/");
        $path_name = substr($file_path, 0, $n);
        $file_name = substr($file_path, $n+1);

        /* 整理版本工作目錄位置 */
        $ver_dir = $path_name."/".NUWEB_VER_PATH;
        if (!is_dir($ver_dir))
            return false;

        /* 整理版本資訊檔位置 */
        $ver_log = $ver_dir.$file_name.".ver";
        return $ver_log;
    }

    /* 記錄檔案版本資訊 */
    function write_ver_log($ver_log, $ver, $last_acn)
    {
        Global $uacn;

        if (empty($ver))
            return;
        $date_time = date("YmdHis");

        /* 記錄版本資訊到 ver log file */
        /* 2015/12/4 修改,增加記錄修改者的 IP 位置 */
        $fp = fopen($ver_log, "a");
        flock($fp, LOCK_EX);
        //fputs($fp, $date_time."\t".$last_acn."\t".$uacn."\t".$ver."\n");
        fputs($fp, $date_time."\t".$last_acn."\t".$uacn."\t".$ver."\t".$_SERVER["REMOTE_ADDR"]."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /* 從檔案版本資訊檔中移除某個版本資訊 */
    function remove_ver_log($file_path, $ver)
    {
        /* 檢查檔案是否存在 */
        if ((!file_exists($file_path)) || (empty($ver)))
            return false;

        /* 取得檔案版本資訊檔位置 */
        $ver_log = get_ver_log_path($file_path);
        if (($ver_log === false) || (!file_exists($ver_log)))
            return false;

        /* 取出版本資訊檢查是否有 ver 資訊,若有就移除掉 */
        $vlist = @file($ver_log);
        $cnt = count($vlist);
        $ver_content = "";
        $change = false;
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 2015/12/4 版本記錄檔增加 IP 記錄,因舊版本沒有 IP 資料且移除版本資訊時不需 IP 資料,所以下列程式沒有增加取得 IP 資料 */
            list($l_time, $l_last_acn, $l_acn, $l_ver) = explode("\t", trim($vlist[$i]));
            if ($l_ver === $ver)
                $change = true;
            else
                $ver_content .= $vlist[$i];
        }

        /* 檢查版本資訊檔內容是否有變更 */
        if ($change === true)
        {
            /* 若資訊檔內容已清空,就刪除此檔案,否則就重新儲存 */
            if (!empty($ver_content))
            {
                $fp = fopen($ver_log, "w");
                flock($fp, LOCK_EX);
                fputs($fp, $ver_content);
                flock($fp, LOCK_UN);
                fclose($fp);
            }
            else
                unlink($ver_log);
        }
        return true;
    }

    /*** 垃圾桶相關函數 ***/
    /* 將檔案或目錄放進垃圾桶 */
    function set_trash($file_path)
    {
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);

        /* 檢查 file_path 必須在子網站內,但不可以是子網站本身 */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if (substr($file_path, 0, $l) !== $site_path)
            return false;
        $path = explode("/", substr($file_path, $l));
        $cnt = count($path);
        if ($cnt <= 1)
            return false;

        /* 檢查是否有上傳權限,僅提供有上傳權限 user 使用 (因需使用 chk_upload_right 函數,所以要先 require 子網站的 init.php)*/
        /* 2015/2/11 刪除,因修改新版權限,public_lib.php 必須 include Site_Prog/init.php 所以不必再 include 一次 */
        //require_once($site_path."init.php");
        $f_path = substr($file_path, $l);
        if (chk_upload_right($site_path, $f_path) !== PASS)
            return false;

        /* 取得垃圾桶目錄位置 */
        $trash_path = get_trash_path($file_path);
        if ($trash_path == false)
            return false;

        /* 取得 trash_id 並建立儲存目錄 */
        $trash_id = new_trash($trash_path);
        if ($trash_id == false)
            return false;
        $trash_id_path = $trash_path.$trash_id;

        /* 取得顯示用的路徑 */
        $view_path = get_view_path($file_path);

        /* 取得 type (若是目錄就取 dir_type,若是檔案就取 type) */
        if (is_dir($file_path))
            $type = get_rec_field($file_path, "dir_type");
        else
            $type = get_rec_field($file_path, "type");

        /* 將相關檔案或目錄都放進垃圾桶目錄中 */
        if (move_trash($file_path, $trash_id_path) == false)
            return false;

        /* 2014/1/8 新增,更新垃圾桶使用量 */
        update_trash_space($trash_id_path, MODE_ADD);

        /* 紀錄 trase_log */
        write_trash_log($file_path, $view_path, $trash_id, $trash_path, $type);

        return $trash_id;
    }

    /* 清除垃圾桶內的檔案或目錄 */
    function del_trash($site_acn, $trash_id, $chk_right=true)
    {
        /* 2014/1/10 修改,增加檢查 chk_right 參數,因刪除過期垃圾桶是由系統呼叫,不會有權限設定,所以必須跳過權限檢查 */
        /* 本功能僅提供網站管理員使用 */
        if ($chk_right == true)
        {
            $site_acn_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".$site_acn;
            if (chk_site_manager($site_acn_path) === false)
                return false;
        }

        /* 取得垃圾桶目錄位置 */
        $file_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".$site_acn;
        $trash_path = get_trash_path($file_path);
        if ($trash_path == false)
            return false;
        $trash_id_path = $trash_path.$trash_id;
        if (!is_dir($trash_id_path))
            return false;

        /* 2014/1/8 新增,更新垃圾桶使用量,因為需要計算使用量,所以必須在刪除前處理 */
        update_trash_space($trash_id_path, MODE_DEL);
		
		
		
		// whee odb
		if (!function_exists("odb_api_del_trash"))
			require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
		odb_api_del_trash($trash_id_path);
		
		

        /* 刪除垃圾桶 trash_id 目錄 */
        $cmd = "rm -rf $trash_id_path";
        $fp = popen($cmd, "r");
        pclose($fp);

        /* 清除 trash_log 中的紀錄 */
        del_trash_log($trash_id, $trash_path);
        return true;
    }

    /* 清空垃圾桶 */
    function clean_trash($site_acn)
    {
        /* 本功能僅提供網站管理員使用 */
        $site_acn_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".$site_acn;
        if (chk_site_manager($site_acn_path) === false)
            return false;

        /* 取得垃圾桶目錄位置 */
        $file_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".$site_acn;
        $trash_path = get_trash_path($file_path);
        if ($trash_path == false)
            return false;
			
			
			
		// whee odb
		if (!function_exists("odb_api_del_trash"))
			require_once("/data/HTTPD/htdocs/tools/rs_odb_lib.php");
		odb_api_del_trash($trash_path);
		
		

        /* 刪除垃圾桶內所有資料 */
        $cmd = "rm -rf $trash_path*";
        $fp = popen($cmd, "r");
        pclose($fp);

        /* 2014/1/8 新增,更新垃圾桶使用量 */
        update_trash_space($trash_path, MODE_CLEAN);

        return true;
    }

    /* 清除垃圾桶內過期的檔案或目錄 */
    function del_over_trash($site_acn)
    {
        /* 本功能僅由系統呼叫,所以不檢查權限 */
        /* 先取得垃圾桶資訊 */
        $log = get_trash_log($site_acn);
        if ($log == false)
            return false;

        /* 檢查垃圾桶內檔案或目錄是否過期,若過期就進行刪除 */
        $over_time = date("YmdHis", mktime(0, 0, 0, date("m"), date("d")-MAX_TRASH_DAY, date("Y")));
        $cnt = count($log);
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($log[$i]["time"] < $over_time)
                del_trash($site_acn, $log[$i]["trash_id"], false);
        }
        return true;
    }

    /* 還原垃圾桶內的檔案或目錄 */
    function recover_trash($site_acn, $trash_id)
    {
        /* 本功能僅提供網站管理員使用 */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $site_acn_path = $site_path.$site_acn;
        if (chk_site_manager($site_acn_path) === false)
            return false;

        /* 取得垃圾桶目錄位置 */
        $trash_path = get_trash_path($site_acn_path);
        if ($trash_path == false)
            return false;
        $trash_id_path = $trash_path.$trash_id;
        if (!is_dir($trash_id_path))
            return false;

        /* 取得垃圾桶資訊 */
        $data = get_trash_log($site_acn, $trash_id);
        if (!isset($data["file_path"]))
            return false;
        $file_path = $data["file_path"];

        /* 取得還原的位置 */
        $recover_path = get_recover_path($site_acn, $data);
        if ($recover_path == false)
            return false;
        $recover_url = str_replace(WEB_ROOT_PATH, "", $recover_path);

        /* 2014/1/8 新增,更新垃圾桶使用量,因為需要計算使用量,所以必須在還原前處理 */
        update_trash_space($trash_id_path, MODE_UPDATE);

        /* 將檔案還原 */
        move_trash($recover_path, $trash_id_path, "recover", $file_path);

        /* 清除 trash_log 中的紀錄 */
        del_trash_log($trash_id, $trash_path);

        return $recover_url;
    }

    /* 取得還原的位置 */
    function get_recover_path($site_acn, $trash_data)
    {
        /* 因會使用到 new_dir 功能,所以必須 require Site_Prog/init.php */
        /* 2015/2/11 刪除,因修改新版權限,public_lib.php 必須 include Site_Prog/init.php 所以不必再 include 一次 */
        //require_once("/data/HTTPD/htdocs/Site_Prog/init.php");

        /* 整理所需參數 */
        $page_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $file_path = $trash_data["file_path"];
        $view_path = $trash_data["view_path"];
        $l = strlen($page_dir);

        /* 將 file_path 與 view_path 分解出各層目錄 */
        $f_path_name = explode("/", substr($file_path, $l));
        $v_path_name = explode("/", $view_path);

        /* 檢查每層目錄是否都存在 (第一層子網站目錄與最後一層不用檢查) */
        $cnt = count($v_path_name);
        $now_path = $site_acn;
        for ($i = 1; $i < $cnt-1; $i++)
        {
            $next_path = filename_exists($page_dir, $now_path, $v_path_name[$i]);
            /* 若此層目錄不存在就建立 */
            if ($next_path === false)
            {
                $next_path = new_dir($page_dir, $now_path, $v_path_name[$i]);
                /* 若建立失敗,就回傳 false */
                if ($next_path === false)
                    return false;
            }
            $now_path = $next_path;
        }

        /* 檢查最後一層檔案(目錄)是否存在,若存在就回傳 false,否則回傳整理出來的還原位置 */
        $recover_path = $page_dir.$now_path."/".$f_path_name[$cnt-1];
        if ((file_exists($recover_path)) || (filename_exists($page_dir, $now_path, $v_path_name[$cnt-1]) !== false))
            return false;
        return $recover_path;
    }

    /* 檢查垃圾桶中的檔案在網站中是否已存在 */
    function chk_trash_file_exists($site_acn, $trash_id)
    {
        /* 本功能僅提供網站管理員使用 */
        $page_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $site_acn_path = $page_dir.$site_acn;
        if (chk_site_manager($site_acn_path) === false)
            return false;

        /* 取得垃圾桶資訊 */
        $data = get_trash_log($site_acn, $trash_id);
        $view_path = $data["view_path"];

        /* 分解並檢查每層是否都存在 (第一層子網站目錄不用檢查) */
        $l = strlen($page_dir);
        $path_name = explode("/", $view_path);
        $cnt = count($path_name);
        $now_path = $site_acn;
        for ($i = 1; $i < $cnt; $i++)
        {
            $now_path = filename_exists($page_dir, $now_path, $path_name[$i]);
            if ($now_path === false)
                return NO;
        }
        /* 2014/7/24 修改,若已存在就回傳路徑位置 */
        return $now_path;
    }

    /* 設定或還原影片轉檔的 list (避免尚未完成轉檔就被刪除,未來還原不會自動轉檔問題) */
    function move_trash_video_list($file_path, $trash_id_path, $mode)
    {
        Global $fe_type;

        if ($mode != "recover")
            $mode = "set";

        /* 檢查 trash_id_path 是否存在 */
        if (!is_dir($trash_id_path))
            return false;

        $page_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $page_path = str_replace($page_dir, "", $file_path);
        $video_waiting_list = $page_dir.VIDEO_WAITING_LIST;
        $video_convert_list = CONVERT_LIST;
        $w_list_file = $trash_id_path."/".VIDEO_WAITING_LIST;
        $c_list_file = $trash_id_path."/video_convert.list";


        /* mode = set,代表要將影片轉檔的 list 設定到垃圾桶中 */
        if ($mode == "set")
        {
            /* 檢查 file_path 是否存在 */
            if (!file_exists($file_path))
                return false;

            /* 若 video_waiting.list 與 video_convert.list 都不存在,就不必處理 */
            if ((!file_exists($video_waiting_list)) && (!file_exists($video_convert_list)))
                return false;

            /* 若不是目錄就檢查是否為影片檔,若不是就不必處理 */
            if (!is_dir($file_path))
            {
                $fe = strtolower(substr($file_path, strrpos($file_path, ".")));
                if ($fe_type[$fe] != VIDEO_TYPE)
                    return false;
            }

            /* 檢查 video_waiting.list 是否存在 */
            if (file_exists($video_waiting_list))
            {
                /* 取得 video_waiting.list 資料,並取出符合 page_path 的資料 */
                $content = "";
                $w_list = @file($video_waiting_list);
                $cnt = count($w_list);
                for ($i = 0; $i < $cnt; $i++)
                {
                    if (strstr($w_list[$i], $page_path) == $w_list[$i])
                        $content .= $w_list[$i];
                }
                /* 將符合的資料寫入垃圾桶目錄內的 video_waiting.list 中 */
                if (!empty($content))
                {
                    $fp = fopen($w_list_file, "w");
                    flock($fp, LOCK_EX);
                    fputs($fp, $content);
                    flock($fp, LOCK_UN);
                    fclose($fp);
                }
            }

            /* 檢查 video_convert.list 是否存在 */
            if (file_exists($video_convert_list))
            {
                /* 取得 video_convert.list 資料,並取出符合 file_path 的資料 */
                $content = "";
                $c_list = @file($video_convert_list);
                $cnt = count($c_list);
                for ($i = 0; $i < $cnt; $i++)
                {
                    if (strstr($c_list[$i], $file_path) == $c_list[$i])
                        $content .= $c_list[$i];
                }
                /* 將符合的資料寫入垃圾桶目錄內的 video_convert.list 中 */
                if (!empty($content))
                {
                    $fp = fopen($c_list_file, "w");
                    flock($fp, LOCK_EX);
                    fputs($fp, $content);
                    flock($fp, LOCK_UN);
                    fclose($fp);
                }
            }
        }

        /* mode = recover 代表要從垃圾桶還原影片轉檔的 list */
        if ($mode == "recover")
        {
            /* 若垃圾桶目錄內 video_waiting.list 存在,就新增到系統的 video_waiting.list 中 */
            $w_list_file = $trash_id_path."/".VIDEO_WAITING_LIST;
            if (file_exists($w_list_file))
            {
                $fp = popen("cat $w_list_file >> $video_waiting_list", "r");
                pclose($fp);
                unlink($w_list_file);
            }

            /* 若垃圾桶目錄內 video_convert.list 存在,就新增到系統的 CONVERT_LIST 中 */
            $c_list_file = $trash_id_path."/video_convert.list";
            if (file_exists($c_list_file))
            {
                $fp = popen("cat $c_list_file >> $video_convert_list", "r");
                pclose($fp);
                unlink($c_list_file);
            }
        }
    }

    /* 將相關檔案搬移到垃圾桶目錄或從垃圾桶還原回來 */
    function move_trash($file_path, $trash_id_path, $mode="set", $src_file_path="")
    {
        Global $fe_type;

        if ($mode != "recover")
            $mode = "set";

        /* 檢查 trash_id_path 是否存在 */
        if (!is_dir($trash_id_path))
            return false;

        $page_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $page_path = str_replace($page_dir, "", $file_path);

        /* 先將 file_path 分離出 path_name & file_name */
        $n = strrpos($file_path, "/");
        $path_name = substr($file_path, 0, $n);
        $file_name = substr($file_path, $n+1);

        /* mode = set,代表要將檔案搬移到垃圾桶 */
        if ($mode == "set")
        {
            /* 檢查 file_path 是否存在 */
            if (!file_exists($file_path))
                return false;

            /* 檢查並上傳動態 share record (設為 del,因會檢查檔案是否存在,所以必須在檔案搬移前執行) */
            upload_dymanic_share_rec($page_dir, $page_path, "del");

            /* 2014/7/24 新增,設定影片轉檔的 list */
            move_trash_video_list($file_path, $trash_id_path, $mode);

            /* 若是目錄就直接將相關目錄搬移到垃圾桶目錄中 */
            if (is_dir($file_path))
                rename($file_path, $trash_id_path."/".$file_name);
            else
            {
                /* 建立相關目錄,並將相關檔案或目錄搬移到垃圾桶目錄中 */
                $fe = strtolower(substr($file_name, strrpos($file_name, ".")));
                $cmd = "mv $file_path* $trash_id_path ; ";
                $trash_rec_path = $trash_id_path."/".NUWEB_REC_PATH;
                mkdir($trash_rec_path);
                $cmd .= "mv $path_name/".NUWEB_REC_PATH."$file_name* $trash_rec_path ";
                /* 2014/7/23 修改,檢查是否為影片檔,若是就搬移 .nuweb_media 內的轉檔 */
                if (($fe_type[$fe] == VIDEO_TYPE) && (is_dir($path_name."/".NUWEB_MEDIA_PATH)))
                {
                    $trash_media_path = $trash_id_path."/".NUWEB_MEDIA_PATH;
                    mkdir($trash_media_path);
                    $cmd .= "; mv $path_name/".NUWEB_MEDIA_PATH."$file_name* $trash_media_path ";
                }
                /* 2015/10/2 新增,若有 mp3 轉檔也要搬移過去 */
                if (file_exists($path_name."/".NUWEB_MEDIA_PATH.$file_name.".mp3"))
                {
                    $trash_media_path = $trash_id_path."/".NUWEB_MEDIA_PATH;
                    mkdir($trash_media_path);
                    $cmd .= "; mv $path_name/".NUWEB_MEDIA_PATH."$file_name* $trash_media_path ";
                }
                /* 2014/6/13 新增,將 PDF 檔搬到垃圾桶中 */
                if (file_exists($path_name."/".NUWEB_PDF_PATH.$file_name.".pdf"))
                {
                    $trash_pdf_path = $trash_id_path."/".NUWEB_PDF_PATH;
                    mkdir($trash_pdf_path);
                    $cmd .= "; mv $path_name/".NUWEB_PDF_PATH."$file_name* $trash_pdf_path ";
                }
                if (get_file_ver($file_path) !== false)
                {
                    $trash_ver_path = $trash_id_path."/".NUWEB_VER_PATH;
                    mkdir($trash_ver_path);
                    $cmd .= "; mv $path_name/".NUWEB_VER_PATH."$file_name* $trash_ver_path ";
                }
                /* 如果是網頁檔案,就檢查並搬移附件目錄 (.files) 到垃圾桶目錄中 */
                if ($fe_type[$fe] == HTML_TYPE)
                {
                    /* 若沒有找到來源檔的附件目錄,就不處理 */
                    $files_page_path = get_files_dir($page_dir, $page_path, $name);
                    if ($files_page_path !== false)
                    {
                        $files_path = $page_dir.$files_page_path;
                        $trash_files_path = $trash_id_path."/files";
                        mkdir($trash_files_path);
                        $cmd .= "; mv $files_path $trash_files_path";
                    }
                }
                $fp = popen($cmd, "r");
                pclose($fp);
            }
            /* 從 index 中刪除檔案與目錄資料 */
            $url = str_replace(WEB_ROOT_PATH, "", $file_path);
            urls_delete($url);
        }

        /* mode = recover 代表要從垃圾桶還原檔案 */
        if ($mode == "recover")
        {
            if (!is_dir($path_name))
                return;

            /* 先刪除 trash_id_path 內的權限檔 */
            unlink($trash_id_path."/".NUWEB_DEF);
            /* 取得要還原的檔案或目錄的位置 */
            $trash_file_path = $trash_id_path."/".$file_name;
            /* 若要還原的是目錄就直接將目錄搬移回去 */
            if (is_dir($trash_file_path))
                rename($trash_file_path, $file_path);
            else
            {
                /* 若是還原檔案,必須將相關檔案搬移回去 */
                $cmd = "mv $trash_file_path* $path_name/ ; ";
                $trash_rec_path = $trash_id_path."/".NUWEB_REC_PATH;
                $cmd .= "mv $trash_rec_path$file_name* $path_name/".NUWEB_REC_PATH;
                $trash_media_path = $trash_id_path."/".NUWEB_MEDIA_PATH;
                if (is_dir($trash_media_path))
                {
                    $media_path = $path_name."/".NUWEB_MEDIA_PATH;
                    if (!is_dir($media_path))
                        mkdir($media_path);
                    $cmd .= "; mv $trash_media_path$file_name* $media_path ";
                }
                /* 2013/6/13 新增,將 PDF 檔搬回去 */
                $trash_pdf_path = $trash_id_path."/".NUWEB_PDF_PATH;
                if (is_dir($trash_pdf_path))
                {
                    $pdf_path = $path_name."/".NUWEB_PDF_PATH;
                    if (!is_dir($pdf_path))
                        mkdir($pdf_path);
                    $cmd .= "; mv $trash_pdf_path$file_name* $pdf_path ";
                }
                $trash_ver_path = $trash_id_path."/".NUWEB_VER_PATH;
                if (is_dir($trash_ver_path))
                {
                    $ver_path = $path_name."/".NUWEB_VER_PATH;
                    if (!is_dir($ver_path))
                        mkdir($ver_path);
                    $cmd .= "; mv $trash_ver_path$file_name* $ver_path ";
                }
                $trash_files_path = $trash_id_path."/files";
                if (is_dir($trash_files_path))
                    $cmd .= "; mv $trash_files_path/* $path_name/";
                $fp = popen($cmd, "r");
                pclose($fp);
                rmdir($trash_rec_path);
                if (is_dir($trash_media_path))
                    rmdir($trash_media_path);
                /* 2014/6/13 新增,若有 PDF 目錄就刪除掉 */
                if (is_dir($trash_pdf_path))
                    rmdir($trash_pdf_path);
                if (is_dir($trash_ver_path))
                    rmdir($trash_ver_path);
                if (is_dir($trash_files_path))
                {
                    rmdir($trash_files_path);
                    if ((!empty($src_file_path)) && ($src_file_path !== $file_path))
                    {
                        /* 先將 file_path 分離出 path_name & file_name */
                        $n = strrpos($src_file_path, "/");
                        $src_path = str_replace($page_dir, "", substr($src_file_path, 0, $n));
                        $target_path = str_replace($page_dir, "", $path_name);
                        update_move_file_content($file_path, $src_path, $target_path);
                    }
                }
            }

            /* 2014/7/24 新增,還原影片轉檔的 list */
            move_trash_video_list($file_path, $trash_id_path, $mode);

            /* 2014/7/23 修改,若 rmdir 垃圾桶目錄失敗時,就直接用 rm -rf 指令刪除垃圾桶目錄 */
            if (rmdir($trash_id_path) == false)
            {
                $cmd = "rm -rf $trash_id_path";
                $fp = popen($cmd, "r");
                pclose($fp);
            }

            /* 將還原檔案加到 index 中 */
            add_to_index($file_path);

            /* 檢查並上傳動態 share record (設為 new,因會檢查檔案是否存在,所以必須在檔案還原後執行) */
            upload_dymanic_share_rec($page_dir, $page_path, "new");
        }
        return true;
    }

    /* 建立新的垃圾檔案儲存目錄 (trash_id) */
    function new_trash($trash_path)
    {
        /* 垃圾桶目錄不存在就回傳 false */
        if (!is_dir($trash_path))
            return false;

        /* 先取得 trash_id,再建立新的垃圾檔案儲存目錄 */
        $trash_id = date("Ymd001");
        while(is_dir($trash_path.$trash_id))
            $trash_id++;
        mkdir($trash_path.$trash_id);

        /* 將垃圾桶目錄內的權限檔 copy 進來 */
        copy($trash_path.NUWEB_DEF, $trash_path.$trash_id."/".NUWEB_DEF);

        return $trash_id;
    }

    /* 取得垃圾桶目錄位置 */
    function get_trash_path(&$file_path)
    {
        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 檢查是否為子網站 file_path (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($file_path, 0, $l) !== $site_path) || (strlen($file_path) <= $l))
            return false;

        /* 取得垃圾桶目錄位置,若不存在就建立 */
        $path = explode("/", substr($file_path, $l));
        $site_acn = $path[0];
        $trash_path = $site_path.$site_acn."/".NUWEB_TRASH_PATH;
        if (!is_dir($trash_path))
            mkdir($trash_path);

        /* 檢查是否有權限檔,若沒有就直接 copy Driver 內的權限檔 */
        $def_file = $trash_path.NUWEB_DEF;
        $driver_def = $site_path.$site_acn."/".DRIVER_DIR_NAME."/".NUWEB_DEF;
        if ((!file_exists($def_file)) && (file_exists($driver_def)))
            copy($driver_def, $def_file);

        return $trash_path;
    }

    /* 記錄垃圾桶資訊 */
    function write_trash_log($file_path, $view_path, $trash_id, $trash_path="", $type="")
    {
        Global $uacn;

        /* 檢查垃圾桶位置並取得垃圾桶資訊檔位置 */
        if (empty($trash_path))
        {
            $trash_path = get_trash_path($file_path);
            if ($trash_path == false)
                return false;
        }
        if (substr($trash_path, -1) !== "/")
            $trash_path .= "/";
        $trash_log = $trash_path.TRASH_LOG;
        $trash_id_path = $trash_path.$trash_id;
        if ((empty($trash_id)) || (!is_dir($trash_id_path)))
            return false;

        /* 取得要紀錄的相關資訊 */
        $n = strrpos($view_path, "/");
        $name = ($n === false) ? $view_path : substr($view_path, $n+1);
        $date_time = date("YmdHis");

        /* 記錄垃圾桶資訊到 log file */
        $fp = fopen($trash_log, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $date_time."\t".$uacn."\t".$trash_id."\t".$file_path."\t".$name."\t".$view_path."\t".$type."\n");
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 2015/7/20 新增,記錄垃圾桶的 record */
        $trash_rec = $trash_path.TRASH_REC;
        $url = str_replace(WEB_ROOT_PATH, "", $file_path);
        $rec_content = "@\n";
        $rec_content .= "@time:$date_time\n";
        $rec_content .= "@acn:$uacn\n";
        $rec_content .= "@id:$trash_id\n";
        $rec_content .= "@url:$url\n";
        $rec_content .= "@filename:$name\n";
        $rec_content .= "@view_path:$view_path\n";
        $rec_content .= "@type:$type\n";
        $rec_content .= "@mode:del\n";
        $fp = fopen($trash_rec, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }

    /* 取得垃圾桶資訊 */
    function get_trash_log($site_acn, $trash_id="")
    {
        /* 取得垃圾桶資訊檔位置 */
        $trash_log = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".$site_acn."/".NUWEB_TRASH_PATH.TRASH_LOG;
        if (!file_exists($trash_log))
            return false;

        $buf = @file($trash_log);
        $cnt = count($buf);
        for ($i = 0; $i < $cnt; $i++)
        {

            list($time, $acn, $l_trash_id, $file_path, $file_name, $view_path, $type) = explode("\t", trim($buf[$i]));
            if (empty($trash_id))
            {
                $list[$i]["time"] = $time;
                $list[$i]["acn"] = $acn;
                $list[$i]["trash_id"] = $l_trash_id;
                $list[$i]["file_path"] = $file_path;
                $list[$i]["file_name"] = $file_name;
                $list[$i]["view_path"] = $view_path;
                $list[$i]["type"] = $type;
            }
            else if ($l_trash_id == $trash_id)
            {
                $list["time"] = $time;
                $list["acn"] = $acn;
                $list["trash_id"] = $l_trash_id;
                $list["file_path"] = $file_path;
                $list["file_name"] = $file_name;
                $list["view_path"] = $view_path;
                $list["type"] = $type;
            }
        }
        return $list;
    }

    /* 刪除垃圾桶資訊 */
    function del_trash_log($trash_id, $trash_path)
    {
        /* 檢查垃圾桶位置並取得垃圾桶資訊檔位置 */
        if ((empty($trash_id)) || (empty($trash_path)))
            return false;
        if (substr($trash_path, -1) !== "/")
            $trash_path .= "/";
        $trash_log = $trash_path.TRASH_LOG;
        if (!file_exists($trash_log))
            return false;

        /* 取得垃圾桶資訊,並過濾掉要刪除的資訊 */
        $buf = @file($trash_log);
        $cnt = count($buf);
        $change = false;
        $trash_content = "";
        for ($i = 0; $i < $cnt; $i++)
        {
            list($t_time, $t_acn, $t_id, $t_path, $t_name, $t_view, $t_type) = explode("\t", trim($buf[$i]));
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
        }
        return true;
    }

    /* 取得顯示用的路徑 */
    function get_view_path($file_path)
    {
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;
        /* 檢查是否為子網站 file_path (僅提供子網站使用) */
        $page_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($page_dir);
        if ((substr($file_path, 0, $l) !== $page_dir) || (strlen($file_path) <= $l))
            return false;
        $path = substr($file_path, $l);

        $path_list = explode("/", $path);
        $cnt = count($path_list);
        $now_path = $path_list[0];
        $view_path = get_file_name($page_dir, $now_path);
        for ($i = 1; $i < $cnt; $i++)
        {
            $now_path .= "/".$path_list[$i];
            $view_path .= "/".get_file_name($page_dir, $now_path);
        }
        return $view_path;
    }

    /* 由 view_path 取得 file_path */
    function view_path_to_file_path($site_acn, $v_path)
    {
        /* 過濾掉 v_path 最前面與最後面的 / */
        if (substr($v_path, -1) == "/")
            $v_path = substr($v_path, 0, -1);
        if (substr($v_path, 0, 1) == "/")
            $v_path = substr($v_path, 1);

        /* 檢查 site_acn 參數 */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".$site_acn."/";
        if ((empty($site_acn)) || (!is_dir($site_path)))
            return false;

        /* 取得子網站 index 位置,並檢查 index 是否存在 */
        $index_file = $site_path.NUWEB_INDEX_PATH."/current";
        if (!file_exists($index_file))
            return false;

        /* 用 egrep 找出 index 中所需資料 */
        $cmd = "/bin/egrep \"@_f:|@url:|@view_path:\" $index_file";
        $fp = popen($cmd, "r");
        $del = false;
        $file_path = NULL;
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            $buf = trim($buf);
            if ($buf == "")
                continue;

            /* 若已經找到 file_path 就直接都跳過 (因為找到 file_path 後直接跳出會出現 egrep 錯誤訊息,所以必須將所有輸出資料讀取完) */
            if ($file_path !== NULL)
                continue;

            /* @_f:Normal 代表此筆資料為新的資料 */
            if ($buf == "@_f:Normal")
            {
                /* 新資料就將 url & view_path 先清空 */
                $del = false;
                $url = NULL;
                $view_path = NULL;
                continue;
            }
            /* @_f:Deleted 代表此筆資料已被刪除 */
            if ($buf == "@_f:Deleted")
                $del = true;

            /* 若此筆資料已被刪除,就不處理 */
            if ($del == true)
                continue;

            /* 檢查是否為 url 或 view_path 欄位,若是就取出內容 */
            if (strstr($buf, "@url:") === $buf)
                $url = trim(substr($buf, 5));
            if (strstr($buf, "@view_path:") === $buf)
                $view_path = trim(substr($buf, 11));

            /* 檢查是否為要找的資料,若是就取得 file_path */
            if (($url !== NULL) && ($view_path !== NULL) && ($view_path == $v_path))
                $file_path = str_replace("/".SUB_SITE_NAME."/", "", $url);
        }
        pclose($fp);
        return $file_path;
    }

    /* 將檔案或目錄加到 index 中 */
    function add_to_index($file_path)
    {
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;
        /* 檢查是否為子網站 file_path (僅提供子網站使用) */
        $page_dir = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($page_dir);
        if ((substr($file_path, 0, $l) !== $page_dir) || (strlen($file_path) <= $l))
            return false;
        $path = substr($file_path, $l);
        /* 因需使用 chk_function_dir 函數,所以要先 require Site_Prog/init.php */
        /* 2015/2/11 刪除,因修改新版權限,public_lib.php 必須 include Site_Prog/init.php 所以不必再 include 一次 */
        //require_once("/data/HTTPD/htdocs/Site_Prog/init.php");

        /* 找出 record file 與 url */
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file === false)
            return false;
        $url = str_replace(WEB_ROOT_PATH, "", $file_path);

        /* 將 record 新增到 index 中 */
        rec_put($rec_file, $url);

        /* 若是目錄必須將所有的子目錄與檔案都加入 */
        $nuweb_len = strlen(NUWEB_SYS_FILE);
        $tn_len = strlen(TN_FE_NAME);
        if (is_dir($file_path))
        {
            /* 2013/8/1 新增,若 file_path 是目錄,先檢查 dir_index 是否已存在 */
            $dir_index = $file_path."/".NUWEB_REC_PATH.DIR_INDEX;
            if (is_dir($dir_index))
            {
                /* 若 dir_index 已存在必須先移除,否則會出現重覆的資料 */
                $old_dir_index = $dir_index."_old";
                $cmd = "";
                /* 若舊的 dir_index 存在就先刪除 */
                if (is_dir($old_dir_index))
                    $cmd .= "rm -rf $old_dir_index ; ";
                /* 將目前的 dir_index 變更成舊的 dir_index */
                $cmd .= "mv $dir_index $old_dir_index";
                $fp = popen($cmd, "r");
                pclose($fp);
            }

            /* 檢查是否為功能目錄,若是就不向下層處理,但要處理 fun.rec */
            if (chk_function_dir($page_dir, substr($file_path, $l)) == true)
            {
                /* 檢查是否有 fun.rec 資料,若有也必須加到 index 中 */
                $fun_rec = $file_path."/".NUWEB_REC_PATH.FUN_RECORD;
                if (file_exists($fun_rec))
                    rec_put($fun_rec, $url, false, true);
            }
            else
            {
                /* 若不是功能目錄就下層處理*/
                $handle = opendir($file_path);
                while ($sub_name = readdir($handle))
                {
                    /* 過濾掉 . & .. & index.html (目錄本身會處理,所以 index.html 不必處理) & .nuweb_* & *.thumbs.jpg */
                   if (($sub_name == ".") || ($sub_name == "..") || ($sub_name == DEF_HTML_PAGE) || (substr($sub_dir, 0, $nuweb_len) == NUWEB_SYS_FILE) || (substr($sub_name, -$tn_len) == TN_FE_NAME))
                        continue;
                    $sub_path = $file_path."/".$sub_name;

                    /* 若是 link 就不處理 */
                    if (is_link($sub_path))
                        continue;

                    /* 若是子目錄就遞迴呼叫 add_to_index,若是一般檔案就加到 index 中 */
                    if (is_dir($sub_path))
                        add_to_index($sub_path);
                    else
                    {
                        /* 找出 record file 與 url,將 record 新增到 index 中 */
                        $rec_file = get_file_rec_path($sub_path);
                        if ($rec_file === false)
                            continue;
                        $url = str_replace(WEB_ROOT_PATH, "", $sub_path);
                        rec_put($rec_file, $url);
                    }
                }
                closedir($handle);
            }
        }
        return true;
    }

    /*** Event Log 功能 ***/
    /* 寫入 Event Log */
    function write_event_log($file_path, $fun, $target_path=NULL, $msg=NULL)
    {
        Global $uacn;

        if ((empty($file_path)) || (empty($fun)) || (!file_exists($file_path)))
            return false;

        /* 取得 Event Log 目錄位置 */
        $event_log = get_event_log_path($file_path);
        if ($event_log == false)
            return false;

        /* 取得顯示用的路徑 */
        $view_path = get_view_path($file_path);
        $view_target = NULL;
        if (!empty($target_path))
            $view_target = get_view_path($target_path);

        $time = date("YmdHis");
        $fp = fopen($event_log, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$time/t$uacn/t$fun/t$file_path/t$view_path/t$target_path/t$target_view/t$msg\n");
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

    /* 讀取 Event Log */
    function read_event_log($site_acn, $fun=NULL, $date=NULL, $range=NULL)
    {
        if (empty($site_acn))
            return false;

        if (!empty($date))
        {
            $year = intval(substr($date, 0, 4));
            $mon = intval(substr($date, 4, 2));
            $day = intval(substr($date, 6, 2));
            if (!empty($range))
                $range = intval($range);
            else
                $range = 0;
            if ($range < 0)
            {
                $end_day = $day;
                $start_day = $end_date + $range;
            }
            else
            {
                $start_day = $day;
                $end_day = $start_day + $range;
            }
            $start_time = date("YmdHis", mktime(0, 0, 0, $mon, $start_day, $year));
            $end_time = date("YmdHis", mktime(23, 59, 59, $mon, $end_day, $year));
        }

        /* 取得 Event Log 目錄位置 */
        $file_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/".$site_acn;
        $event_log = get_event_log_path($file_path);
        if (($event_log == false) || (!file_exists($event_log)))
            return false;

        /* 讀取 Event Log 資料 */
        $list = @file($event_log);
        $cnt = count($list);
        for ($i = 0; $i < $cnt; $i++)
        {
            list($time, $acn, $l_fun, $file_path, $view_path, $target_path, $target_view, $msg) = explode("/t", $list[$i]);
            if ((!empty($fun)) && ($fun !== $l_fun))
                continue;
            if ((isset($start_time)) && (isset($end_time)) && (($start_time > $time) || ($end_time < $time)))
                continue;
            $log_list["time"] = $time;
            $log_list["acn"] = $acn;
            $log_list["fun"] = $fun;
            $log_list["file_path"] = $file_path;
            $log_list["view_path"] = $view_path;
            $log_list["target_path"] = $target_path;
            $log_list["target_view"] = $target_view;
            $log_list["msg"] = $msg;
        }

        return $log_list;
    }

    /* 取得 Event Log 位置 */
    function get_event_log_path(&$file_path)
    {
        /* 檢查檔案是否存在 */
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;

        /* 檢查是否為子網站 file_path (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($file_path, 0, $l) !== $site_path) || (strlen($file_path) <= $l))
            return false;

        /* 取得 Event Log 位置 */
        $path = explode("/", substr($file_path, $l));
        $site_acn = $path[0];
        $event_log = $site_path.$site_acn."/".NUWEB_EVENT_LOG_PATH;
        return $event_log;
    }

    /*** 短網址功能 ***/
    /* 設定短網址代碼 */
    function set_short_code($page_url)
    {
        Global $uacn, $reg_conf;

        /* page_url 開頭必須是 / 且不可以有 ./ (過濾掉 ./ 連 ../ 也會過濾掉) */
        if ((substr($page_url, 0, 1) != "/") || (strstr($page_url, "./") != false))
            return false;
        /* 先嘗試取得短網址代碼,若取得到代表已設定過,直接回傳不必再處理 */
        if (($short_code = get_short_code($page_url)) !== false)
            return $short_code;

        /* 檢查 ShortCode 目錄是否已存在,若不存在就建立 */
        if (!is_dir(SHORT_CODE_DIR))
            mkdir(SHORT_CODE_DIR);

        /* 在 ShortCode 目錄內建立一個唯一的 short_code_file,並將檔名當成 short_code */
        /* 2014/8/21 修改,將 CS 的 ssn 轉成 16 進制,放到 short_code 前面,可確保各 CS 間的 short_code 不會重覆 */
        $hex_code = dechex($reg_conf["ssn"]);
        $short_code_file = tempnam(SHORT_CODE_DIR, $hex_code);
        $short_code = substr($short_code_file, strrpos($short_code_file, "/")+1);
        /* 將相關資料轉成 record 記錄到 short_code_file 中 */
        $rec_content = REC_START.REC_BEGIN_PATTERN;
        $rec_content .= "@url:$page_url\n";
        $rec_content .= "@short_code:$short_code\n";
        $rec_content .= "@time:".date("YmdHis")."\n";
        $rec_content .= "@acn:$uacn\n";
        $fp = fopen($short_code_file, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 將 short_code 與 page_url 資料記錄到 short_code.list 中 */
        $fp = fopen(SHORT_CODE_LIST, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$short_code\t$page_url\n");
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 2014/8/21 新增,將 short_code 資料傳到 Group Server */
        group_add_short($short_code, $page_url);

        return $short_code;
    }

    /* 取得短網址代碼 */
    function get_short_code($page_url)
    {
        /* page_url 開頭必須是 / 且不可以有 ./ (過濾掉 ./ 連 ../ 也會過濾掉) */
        if ((substr($page_url, 0, 1) != "/") || (strstr($page_url, "./") != false))
            return false;

        /* 取得 short_code 的資料,並進行比對,回傳符合的 short_code */
        if (!file_exists(SHORT_CODE_LIST))
            return false;
        $short_code = false;
        $fp = fopen(SHORT_CODE_LIST, "r");
        flock($fp, LOCK_SH);
        while($buf = @fgets($fp, MAX_BUFFER_LEN))
        {
            list($code, $url) = explode("\t", trim($buf), 2);
            if ($url == $page_url)
            {
                $short_code = $code;
                break;
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return $short_code;
    }

    /* 由短網址代碼取得一般網址 */
    function get_url_by_short_code($short_code)
    {
        if (empty($short_code))
            return false;

        /* 取得 short_code 的資料,並進行比對,回傳符合的 url */
        if (!file_exists(SHORT_CODE_LIST))
            return group_get_url_by_short($short_code);
        $page_url = false;
        $fp = fopen(SHORT_CODE_LIST, "r");
        flock($fp, LOCK_SH);
        while($buf = @fgets($fp, MAX_BUFFER_LEN))
        {
            list($code, $url) = explode("\t", trim($buf), 2);
            if ($code == $short_code)
            {
                $page_url = $url;
                break;
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        if ($page_url == false)
            return group_get_url_by_short($short_code);
        return $page_url;
    }

    /* 更新 short_code */
    function update_short_code($short_code)
    {
        Global $uacn;

        if (empty($short_code))
            return false;

        /* 取得 short_code 的資料,並進行比對找出符合的資料 */
        if (!file_exists(SHORT_CODE_LIST))
            return false;
        $new_short_code = false;
        $fp = fopen(SHORT_CODE_LIST, "r+");
        flock($fp, LOCK_SH);
        while($buf = @fgets($fp, MAX_BUFFER_LEN))
        {
            list($code, $url) = explode("\t", trim($buf), 2);
            if ($code == $short_code)
            {
                /* 在 ShortCode 目錄內新建立一個唯一的 short_code_file,並將檔名當成新的 short_code */
                /* 2014/8/21 修改,將 CS 的 ssn 轉成 16 進制,放到 short_code 前面,可確保各 CS 間的 short_code 不會重覆 */
                $hex_code = dechex($reg_conf["ssn"]);
                $short_code_file = tempnam(SHORT_CODE_DIR, $hex_code);
                $new_short_code = substr($short_code_file, strrpos($short_code_file, "/")+1);
                /* 將相關資料轉成 record 記錄到 short_code_file 中 */
                $rec_content = REC_START.REC_BEGIN_PATTERN;
                $rec_content .= "@url:$url\n";
                $rec_content .= "@short_code:$new_short_code\n";
                $rec_content .= "@time:".date("YmdHis")."\n";
                $rec_content .= "@acn:$uacn\n";
                $rfp = fopen($short_code_file, "w");
                flock($rfp, LOCK_EX);
                fputs($rfp, $rec_content);
                flock($rfp, LOCK_UN);
                fclose($rfp);

                /* 更新 short_code.list 資料 */
                $l = strlen($buf);
                fseek($fp, -$l, SEEK_CUR);
                fputs($fp, "$new_short_code\t$url\n");
                flock($fp, LOCK_UN);
                fclose($fp);

                /* 將舊的 short_code_file 刪除 */
                $old_short_code_file = SHORT_CODE_DIR.$short_code;
                if (file_exists($old_short_code_file))
                    unlink($old_short_code_file);

                return $new_short_code;
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 2014/8/21 新增,更新 short_code 到 Group Server */
        group_update_short($short_code, $new_short_code);

        return false;
    }

    /* 刪除 short_code */
    function del_short_code($short_code)
    {
        Global $uacn;

        if (empty($short_code))
            return false;

        /* 取得 short_code 的資料,並進行比對找出符合的資料 */
        if (!file_exists(SHORT_CODE_LIST))
            return false;
        $fp = fopen(SHORT_CODE_LIST, "r+");
        flock($fp, LOCK_SH);
        while($buf = @fgets($fp, MAX_BUFFER_LEN))
        {
            list($code, $url) = explode("\t", trim($buf), 2);
            if ($code == $short_code)
            {
                /* 更新 short_code.list 資料,將該筆資料內容清空 */
                $l = strlen($buf);
                fseek($fp, -$l, SEEK_CUR);
                $content = "";
                for ($i = 0; $i < $l-1; $i++)
                    $content .= " ";
                fputs($fp, "$content\n");
                flock($fp, LOCK_UN);
                fclose($fp);

                /* 將 short_code_file 刪除 */
                $short_code_file = SHORT_CODE_DIR.$short_code;
                if (file_exists($short_code_file))
                    unlink($short_code_file);

                return true;
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 2014/8/21 新增,刪除 Group Server 的 short_code 資料 */
        group_del_short($short_code);

        return false;
    }

    /* 2015/10/20 新增,紀錄 short link 的 access log */
    function write_short_link_log($short_code)
    {
        /* 建立 SHORT_LOG_DIR */
        if (!is_dir(SHORT_LOG_DIR))
            mkdir(SHORT_LOG_DIR);

        /* 建立儲存(當年度) short log 的目錄 */
        $log_dir = SHORT_LOG_DIR.date("Y")."/";
        if (!is_dir($log_dir))
            mkdir($log_dir);

        $log_file = $log_dir.date("Ymd");
        $log_msg = $short_code."\t".get_url_by_short_code($short_code);
        write_server_log($log_file, $log_msg);
    }

    /* 2015/10/23 新增,取得 short link 的 access log */
    function get_short_link_log($log_day=NULL, $short_code=NULL)
    {
        /* 若沒傳入 log_day,就以今天當成 log_day */
        if (empty($log_day))
            $log_day = date("Ymd");
        if ($log_day == "all")
            $cmd = "cat ".SHORT_LOG_DIR."*/*";
        else
        {
            $year = substr($log_day, 0, 4);
            $log_file = SHORT_LOG_DIR."$year/$log_day";
            if (!file_exists($log_file))
                return false;
            $cmd = "cat $log_file";
        }
        $log = array();
        $fp = popen($cmd, "r");
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            list($ip, $time, $uacn, $s_code, $url) = explode("\t", trim($buf));
            if ((!empty($short_code)) && ($short_code !== $s_code))
                continue;
            array_push($log, array("ip"=>$ip, "time"=>$time, "acn"=>$uacn, "short_code"=>$s_code, "url"=>$url));
        }
        return $log;
    }

    /* 調整目錄或檔案顯示順序功能 */
    /* 設定 .nuweb_sub_list (顯示順序設定檔) */
    /* 2014/11/2 取消 show_list 參數,隱藏功能不再利用 .nuweb_sub_list 處理 */
    //function set_sub_list($page_url, $path_list, $type="", $show_list="")
    function set_sub_list($page_url, $path_list, $type="")
    {
        Global $fe_type;

        /* page_url 開頭必須是 / 且不可以有 ./ (過濾掉 ./ 連 ../ 也會過濾掉) 且必須是目錄 */
        if ((substr($page_url, 0, 1) != "/") || (strstr($page_url, "./") != false) || (!is_dir(WEB_ROOT_PATH.$page_url)))
            return false;
        if (substr($page_url, -1) !== "/")
            $page_url .= "/";
        $page_path = WEB_ROOT_PATH.$page_url;

        /* 檢查是否在子網站內 (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($page_path, 0, $l) !== $site_path) || (strlen($page_path) <= $l))
            return false;

        /* 若 type 與 path_list 都是空的,就直接刪除 .nuweb_sub_list */
        $file = $page_path.NUWEB_SUB_LIST;
        if ((empty($path_list)) && (empty($type)))
        {
            if (file_exists($file))
            {
                unlink($file);
                /* 2014/9/6 新增,紀錄到 modify.list 中 */
                write_modify_list("del", $file, "conf");
            }
            return true;
        }

        /* 檢查是否有傳入 type 參數 */
        $content = "";
        if (!empty($type))
        {
            /* 2014/4/15 新增,檢查 type 參數,可接受多個 type (用 ',' 分隔) */
            $t_list = explode(",", $type);
            $t_cnt = count($t_list);

            /* 取出原 .nuweb_sub_list 資料,將 type 的資料先過濾掉 */
            $s_list = get_nuweb_sub_list($page_url);
            $s_cnt = ($s_list == false) ? 0 : count($s_list);
            for ($i = 0; $i < $s_cnt; $i++)
            {
                $t_match = false;
                for ($j = 0; $j < $t_cnt; $j++)
                {
                    if ($s_list[$i]["type"] == $t_list[$j])
                    {
                        $t_match = true;
                        continue;
                    }
                }
                /* 先過濾掉符合 type 的資料 */
                if ($t_match !== true)
                    $content .= $s_list[$i]["type"]."\t".$s_list[$i]["page_name"]."\n";
            }
        }

        /* 整理 path_list,將 path_list 的資料新增到 content 中 */
        $cnt = count($path_list);
        for ($i = 0; $i < $cnt; $i++)
        {
            
            /* 2014/9/19 新增,檢查 show_list 參數,決定是否要顯示 */
            /* 2014/11/2 取消 show_list 參數 */
            //$show = "";
            //$show_cnt = count($show_list);
            //$exist = false;
            //for ($j = 0; $j < $show_cnt; $j++)
            //{
            //    if ($show_list[$j] == $path_list[$i])
            //    {
            //        $exist = true;
            //        break;
            //    }
            //}
            //if ($exist == false)
            //    $show = NO;

            $sub_path = $page_path.$path_list[$i];
            /* 若是目錄就將 type 設定為 DIR_TYPE */
            if (is_dir($sub_path))
            {
                //$content .= DIR_TYPE."\t".$path_list[$i]."\t$show\n";
                $content .= DIR_TYPE."\t".$path_list[$i]."\n";
                continue;
            }
            /* 其他檔案就取出副檔名,依附檔名設定 type */
            $fe = strtolower(substr($path_list[$i], strrpos($path_list[$i], ".")));
            if (!isset($fe_type[$fe]))
                $content .= OTHER_TYPE."\t".$path_list[$i]."\n";
            else
                $content .= $fe_type[$fe]."\t".$path_list[$i]."\n";
            //    $content .= OTHER_TYPE."\t".$path_list[$i]."\t$show\n";
            //else
            //    $content .= $fe_type[$fe]."\t".$path_list[$i]."\t$show\n";
        }

        /* 若整理好的資料是空的且 .nuweb_sub_list 存在,就刪除 .nuweb_sub_list */
        if (empty($content))
        {
            if (file_exists($file))
            {
                unlink($file);
                /* 2014/9/6 新增,紀錄到 modify.list 中 */
                write_modify_list("del", $file, "conf");
            }
            return true;
        }

        /* 將整理好的資料存入 .nuweb_sub_list 中 */
        $fp = fopen($file, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $content);
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 2014/9/6 新增,紀錄到 modify.list 中 */
        write_modify_list("update", $file, "conf");

        return true;
    }

    /* 取得 sub_list 資料 */
    function get_sub_list($page_url, $type="")
    {
        /* page_url 開頭必須是 / 且不可以有 ./ (過濾掉 ./ 連 ../ 也會過濾掉) 且必須是目錄 */
        if ((substr($page_url, 0, 1) != "/") || (strstr($page_url, "./") != false) || (!is_dir(WEB_ROOT_PATH.$page_url)))
            return false;
        if (substr($page_url, -1) !== "/")
            $page_url .= "/";
        $page_path = WEB_ROOT_PATH.$page_url;

        /* 檢查是否在子網站內 (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($page_path, 0, $l) !== $site_path) || (strlen($page_path) <= $l))
            return false;

        /* 先取得此目錄 index 中所有資料 */
        $rBegin = str_replace("\n", "\\n", REC_START.REC_BEGIN_PATTERN);
        $index_file = $page_path.NUWEB_REC_PATH.DIR_INDEX."/current";
        $cmd = SEARCH_BIN_DIR."dbman -recbeg \"$rBegin\" -flag \"@_f:Normal\" -sort $index_file";
        $fp = popen($cmd, "r");
        $rec_content = "";
        while($buf = fgets($fp, MAX_BUFFER_LEN))
            $rec_content .= $buf;
        pclose($fp);
        $rec_content = str_replace("@\n@\n", "@\n", $rec_content);
        $rec_content = str_replace("\n@@", "\n@", $rec_content);
        if (preg_match("#^total:\s*(\d+)#mi", $rec_content, $m))
            $match_cnt = (int)$m[1];
        else
            $match_cnt = 0;
        if (($match_cnt == 0) || (empty($match_cnt)))
            return;
        $recs = recbuf2array(explode("\n", $rec_content));

        /* 2015/3/4 新增,先將資料依時間排序 */
        sort_array($recs, "time", "D");

        /* 取出 .nuweb_sub_list 資料 */
        $s_list = get_nuweb_sub_list($page_url, $type);

        /* 2014/4/15 新增,檢查 type 參數,可接受多個 type (用 ',' 分隔) */
        if (!empty($type))
        {
            $t_list = explode(",", $type);
            $t_cnt = count($t_list);
        }
        else
            $t_cnt = 0;

        /* 整理 sub list 資料 */
        $cnt = count($recs);
        $s_cnt = ($s_list == false) ? 0 : count($s_list);
        for ($i = 0; $i < $cnt; $i++)
        {
            switch($recs[$i]["type"])
            {
                case DIR_TYPE_NAME:
                    $s_type = DIR_TYPE;
                    break;
                case VIDEO_TYPE_NAME:
                    $s_type = VIDEO_TYPE;
                    break;
                case AUDIO_TYPE_NAME:
                    $s_type = AUDIO_TYPE;
                    break;
                case IMAGE_TYPE_NAME:
                    $s_type = IMAGE_TYPE;
                    break;
                case DOC_TYPE_NAME:
                    $s_type = DOC_TYPE;
                    break;
                case TEXT_TYPE_NAME:
                    $s_type = TEXT_TYPE;
                     break;
                case HTML_TYPE_NAME:
                    $s_type = HTML_TYPE;
                    break;
                case LINK_TYPE_NAME:
                    $s_type = LINK_TYPE;
                    break;
                default:
                    $s_type = OTHER_TYPE;
            }
            /* 若有傳入 type 參數,且與 s_type 不相同就跳過不處理 */
            if ($t_cnt > 0)
            {
                $t_match = false;
                for ($j = 0; $j < $t_cnt; $j++)
                {
                    if ($s_type == $t_list[$j])
                    {
                        $t_match = true;
                        break;
                    }
                }
                if ($t_match == false)
                    continue;
            }

            $item_exist = false;
            for ($j = 0; $j < $s_cnt; $j++)
            {
                if ($s_list[$j]["page_name"] == $recs[$i]["page_name"])
                {
                    $item_exist = true;
                    break;
                }
            }
            /* 若是不存在 .nuweb_sub_list 中,代表是新的資料,就加到 sub_list 後面 */
            if ($item_exist !== true)
            {
                $s_list[$s_cnt]["type"] = $s_type;
                $s_list[$s_cnt]["page_name"] = $recs[$i]["page_name"];
                $s_list[$s_cnt]["path"] = $page_path.$recs[$i]["page_name"];
                $s_list[$s_cnt]["name"] = $recs[$i]["filename"];
                $s_cnt++;
            }
        }
        return $s_list;
    }

    /* 取出 .nuweb_sub_list 資料 */
    function get_nuweb_sub_list($page_url, $type="")
    {
        /* page_url 開頭必須是 / 且不可以有 ./ (過濾掉 ./ 連 ../ 也會過濾掉) 且必須是目錄 */
        if ((substr($page_url, 0, 1) != "/") || (strstr($page_url, "./") != false) || (!is_dir(WEB_ROOT_PATH.$page_url)))
            return false;
        if (substr($page_url, -1) !== "/")
            $page_url .= "/";
        $page_path = WEB_ROOT_PATH.$page_url;

        /* 檢查是否在子網站內 (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($page_path, 0, $l) !== $site_path) || (strlen($page_path) <= $l))
            return false;

        /* 檢查 .nuweb_sub_list 是否存在 */
        $file = $page_path.NUWEB_SUB_LIST;
        if (!file_exists($file))
            return false;

        /* 取得 .nuweb_sub_list 資料 */
        $buf = @file($file);
        $cnt = count($buf);
        if ($cnt == 0)
            return false;

        /* 2014/4/15 新增,檢查 type 參數,可接受多個 type (用 ',' 分隔) */
        if (!empty($type))
        {
            $t_list = explode(",", $type);
            $t_cnt = count($t_list);
        }
        else
            $t_cnt = 0;

        /* 整理 .nuweb_sub_list 資料 (若有設定 type 就只取出符合的資料) */
        $n = 0;
        $sub_list = array();
        for ($i = 0; $i < $cnt; $i++)
        {
            $buf[$i] = trim($buf[$i]);
            if (empty($buf[$i]))
                continue;
            /* 2014/11/2 取消 .nuweb_sub_list 的 show 參數 */
            //list($s_type, $s_path, $s_show) = explode("\t", trim($buf[$i]));
            list($s_type, $s_path) = explode("\t", trim($buf[$i]));
            $sub_path = $page_path.$s_path;

            /* 若 s_type 或 s_path 為空的或 sub_path 已不存在就跳過不處理 */
            if ((empty($s_type)) || (empty($s_path)) || (!file_exists($sub_path)))
                continue;

            /* 若有傳入 type 參數,且與 s_type 不相同就跳過不處理 */
            if ($t_cnt > 0)
            {
                $t_match = false;
                for ($j = 0; $j < $t_cnt; $j++)
                {
                    if ($s_type == $t_list[$j])
                    {
                        $t_match = true;
                        break;
                    }
                }
                if ($t_match == false)
                    continue;
            }

            $sub_list[$n]["type"] = $s_type;
            $sub_list[$n]["page_name"] = $s_path;
            $sub_list[$n]["path"] = $sub_path;
            $sub_list[$n]["name"] = get_file_name($site_path, str_replace($site_path, "", $sub_path));
            /* 2014/11/2 取消 .nuweb_sub_list 的 show 參數 */
            //if ($s_show == NO)
            //    $sub_list[$n]["show"] = $s_show;
            $n++;
        }
        if ($n == 0)
            return false;
        return $sub_list;
    }

    /* 依據 .nuweb_sub_list 資料進行排序 */
    function sort_sub_list(&$recs, $page_url)
    {
        /* page_url 開頭必須是 / 且不可以有 ./ (過濾掉 ./ 連 ../ 也會過濾掉) 且必須是目錄 */
        if ((substr($page_url, 0, 1) != "/") || (strstr($page_url, "./") != false) || (!is_dir(WEB_ROOT_PATH.$page_url)))
            return false;
        if (substr($page_url, -1) !== "/")
            $page_url .= "/";
        $page_path = WEB_ROOT_PATH.$page_url;

        /* 檢查是否在子網站內 (僅提供子網站使用) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($page_path, 0, $l) !== $site_path) || (strlen($page_path) <= $l))
            return false;

        /* 若 .nuweb_sub_list 不存在就不處理 */
        $file = $page_path.NUWEB_SUB_LIST;
        if (!file_exists($file))
            return false;

        /* 2015/3/4 新增,先將資料依時間排序 (可避免不在 .nuweb_sub_list 內的資料沒有排序效果) */
        sort_array($recs, "time", "D");

        /* 先取得 .nuweb_sub_list 資料 */
        $sub_list = get_nuweb_sub_list($page_url);
        $s_cnt = count($sub_list);
        $cnt = count($recs);
        $ret = array();
        $n = 0;
        /* 先整理已在 sub_list 的資料,放到前面 */
        for ($i = 0; $i < $s_cnt; $i++)
        {
            for ($j = 0; $j < $cnt; $j++)
            {
                if ($sub_list[$i]["page_name"] == $recs[$j]["page_name"])
                    $ret[$n++] = $recs[$j];
            }
        }
        /* 不在 sub_list 中就放到最後面 */
        for ($i = 0; $i < $cnt; $i++)
        {
            $item_exist = false;
            for ($j = 0; $j < $s_cnt; $j++)
            {
                if ($recs[$i]["page_name"] == $sub_list[$j]["page_name"])
                {
                    $item_exist = true;
                    continue;
                }
            }
            if ($item_exist == true)
                continue;
            $ret[$n++] = $recs[$i];
        }
        /* 設定整理好的 recs 資料 */
        $recs = $ret;
        return true;
    }

    /*** 網站 Event Record (訊息) 功能 ***/
    /* 取得 event id */
    function get_event_id()
    {
        if (file_exists(EVENT_ID))
        {
            $fp = fopen(EVENT_ID, "r");
            flock($fp, LOCK_SH);
            $event_id = intval(@fgets($fp, MAX_BUFFER_LEN));
            flock($fp, LOCK_UN);
            fclose($fp);
            $event_id++;
        }
        else
            $event_id = 1;

        /* 儲存到 event id 中 */
        $fp = fopen(EVENT_ID, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $event_id);
        flock($fp, LOCK_UN);
        fclose($fp);

        return $event_id;
    }

    /* 設定 Event record */
    function set_event_rec($page_dir, $file, $mode)
    {
        Global $reg_conf;

        /* 檢查 mode (目前只處理 new | update | del) */
        if (($mode !== "new") && ($mode !== "update") && ($mode !== "del"))
            return false;

        /* 檢查檔案是否存在 */
        if (substr($page_dir, -1) != "/")
            $page_dir .= "/";
        $file_path = $page_dir.$file;
        if (substr($file_path, -1) == "/")
            $file_path = substr($file_path, 0, -1);
        if (!file_exists($file_path))
            return false;
        $url = str_replace(WEB_ROOT_PATH, "", $file_path);
        $view_path = get_view_path($file_path);

        /* 檢查 file_path 必須在子網站內,且不在 Driver 目錄內 (僅處理網站資料,不處理網路硬碟資料) */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if (substr($file_path, 0, $l) !== $site_path)
            return false;
        if (chk_inDriver($url) == true)
            return false;
        /* 取得 site_acn */
        $path = explode("/", substr($file_path, $l));
        $site_acn = $path[0];

        /* 找出 record file */
        $rec_file = get_file_rec_path($file_path);
        if ($rec_file === false)
            return false;

        /* 取出 record file 資料 */
        $rec = rec2array($rec_file);

        /* 整理 event record 所需資料 */
        $rec_content = "@\n";
        $rec_content .= REC_BEGIN_PATTERN;
        $rec_content .=  "@eid:".get_event_id()."\n";
        $rec_content .=  "@url:$url\n";
        $rec_content .=  "@view_path:$view_path\n";
        $rec_content .=  "@filename:".$rec[0]["filename"]."\n";
        $rec_content .=  "@title:".$rec[0]["title"]."\n";
        $rec_content .=  "@owner:".$rec[0]["owner"]."\n";
        $rec_content .=  "@site_acn:$site_acn\n";
        $rec_content .=  "@mode:$mode\n";
        $rec_content .=  "@description:".$rec[0]["description"]."\n";
        $rec_content .=  "@fe:".$rec[0]["fe"]."\n";
        $rec_content .=  "@size:".$rec[0]["size"]."\n";
        $rec_content .=  "@time:".date("YmdHis")."\n";
        $rec_content .=  "@mtime:".$rec[0]["mtime"]."\n";
        $rec_content .=  "@type:".$rec[0]["type"]."\n";
        $rec_content .=  "@cnt_view:".$rec[0]["cnt_view"]."\n";
        /* 2014/8/26 修改 allow 依 record 的 allow 欄位設定 (因網站首頁可能出現沒權限資料) */
        $rec_content .=  "@allow:".$rec[0]["allow"]."\n";
        $rec_content .=  "@comment:\n";

        /* 儲存到 event record 中 */
        $fp = fopen(EVENT_REC, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 將 event record 上傳到 Group Server */
        group_upload_event_rec($rec_content);

        /* 將 record 加到 event index 中 */
        return rput_content(EVENT_INDEX_DIR, $rec_content, "eid");
    }

    /* 設定功能目錄的 Event record */
    function set_fun_event_rec($mode, $rec)
    {
        Global $reg_conf;

        /* 檢查 mode (目前只處理 new | update | del) */
        if (($mode !== "new") && ($mode !== "update") && ($mode !== "del"))
            return false;

        /* 檢查 record 內容是否有必要欄位 */
        if ((!isset($rec["url"])) || (!isset($rec["view_path"])) || (!isset($rec["filename"])) || (!isset($rec["title"])) ||
            (!isset($rec["owner"])) || (!isset($rec["description"])) || (!isset($rec["size"])) || (!isset($rec["type"])) || (!isset($rec["cnt_view"])))
            return false;

        /* 由 url 整理出 file_path,並檢查 file_path 是否在子網站內,且不在 Driver 目錄內 (僅處理網站資料,不處理網路硬碟資料) */
        $url = $rec["url"];
        $file_path = WEB_ROOT_PATH.$url;
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if (substr($file_path, 0, $l) !== $site_path)
            return false;
        if (chk_inDriver($url) == true)
            return false;

        /* 取得 site_acn */
        $path = explode("/", substr($file_path, $l));
        $site_acn = $path[0];

        /* 整理 event record 所需資料 */
        $rec_content = "@\n";
        $rec_content .= REC_BEGIN_PATTERN;
        $rec_content .=  "@eid:".get_event_id()."\n";
        $rec_content .=  "@site_acn:$site_acn\n";
        $rec_content .=  "@mode:$mode\n";
        foreach ($rec as $key => $value)
        {
            /* 2014/9/1 修改,只保留必要欄位 */
            if (($key == "url") || ($key == "view_path") || ($key == "filenname") || ($key == "title") || ($key == "owner") ||
                ($key == "description") || ($key == "size") || ($key == "type") || ($key == "cnt_view") || ($key == "allow"))
                $rec_content .=  "@$key:$value\n";
        }
        $rec_content .=  "@time:".date("YmdHis")."\n";
        /* 2014/8/26 修改 allow 依 record 的 allow 欄位設定 (因網站首頁可能出現沒權限資料) */
        $rec_content .=  "@comment:\n";

        /* 儲存到 event record 中 */
        $fp = fopen(EVENT_REC, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 將 event record 上傳到 Group Server */
        group_upload_event_rec($rec_content);

        /* 將 record 加到 event index 中 */
        return rput_content(EVENT_INDEX_DIR, $rec_content, "eid");
    }

    /* 取得 event rec 內容 */
    function get_event_rec($eid, $index_dir=EVENT_INDEX_DIR)
    {
        $cmd = SEARCH_BIN_DIR."rdb -H $index_dir -getpage -key \"@eid:$eid\"";
        $fp = popen($cmd, "r");
        $rec_content = "";
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            $buf = trim($buf);
            if ((empty($buf)) || (substr($buf, 0, 2) == "@_"))
                continue;
            $rec_content .= $buf."\n";
        }
        pclose($fp);
        $rec = recbuf2array(explode("\n", $rec_content));
        return $rec[0];
    }

    /* 新增 event rec 留言 */
    function add_event_comment($eid, $comment, $index_dir=EVENT_INDEX_DIR)
    {
        Global $uacn;
        $nowtime = date("YmdHis");
        $comment = str_replace("\n", "<br>", str_replace("\r", "", $comment));
        $comment_content = $_SERVER["REMOTE_ADDR"]."\t".$nowtime."\t".$uacn."\t".$comment;

        /* 記錄到 event_update.log 中 */
        event_update("add_comment", $eid, $comment_content, $index_dir);

        /* 新增 comment 欄位內容 */
        $cmd = SEARCH_BIN_DIR."rdb -H $index_dir -update -field '@comment:+\"\\n$comment_content\"' -key \"@eid:$eid\"";
        $fp = popen($cmd, "r");
        $result = "";
        while($buf = fgets($fp, MAX_BUFFER_LEN))
            $result .= $buf;
        pclose($fp);
        list($rid, $offset, $len) = explode("\t", trim($result));
        return $rid;
    }

    /* 修改 event rec 留言 */
    function update_event_comment($eid, $comment_content, $index_dir)
    {
        /* 記錄到 event_update.log 中 */
        event_update("update_comment", $eid, str_replace("\n", "\\n", str_replace("\r", "", $comment_content)), $index_dir);

        /* 更新 comment 欄位內容 */
        $cmd = SEARCH_BIN_DIR."rdb -H $index_dir -update -field \"@comment:$comment_content\" -key \"@eid:$eid\"";
        $fp = popen($cmd, "r");
        $result = "";
        while($buf = fgets($fp, MAX_BUFFER_LEN))
            $result .= $buf;
        pclose($fp);
        list($rid, $offset, $len) = explode("\t", trim($result));
        return $rid;
    }

    /* 變更 event rec 權限 */
    function update_event_allow($eid, $allow_mode, $index_dir=EVENT_INDEX_DIR)
    {
        /* 若 allow_mode 不是 all | manager | member | none 就不處理 */
        if (($allow_mode !== "all") && ($allow_mode !== "manager") && ($allow_mode !== "member") && ($allow_mode !== "none"))
            return false;

        /* 若 allow_mode 是 all 時,allow_value 就設為 ALLOW_ALL,否則就先讀取 record 取得 site_acn 整理設定到 allow_value */
        if ($allow_mode == "all")
            $allow_value = ALLOW_ALL;
        else if ($allow_mode == "none")
            $allow_value = ALLOW_NONE;
        else
        {
            $rec = get_event_rec($eid, $index_dir);
            /* 若 site_acn 不存在就不處理 */
            if ((!isset($rec["site_acn"])) || (empty($rec["site_acn"])))
                return false;
            $allow_value = $rec["site_acn"]."_".$allow_mode;
            /* 若 allow_mode 是 member 時,也必須設定 manager */
            if ($allow_mode == "member")
                $allow_value .= ",".$rec["site_acn"]."_manager";
        }

        /* 記錄到 event_update.log 中 */
        event_update("update_allow", $eid, $allow_value, $index_dir);

        /* 更新 allow 欄位設定 */
        $cmd = SEARCH_BIN_DIR."rdb -H $index_dir -update -field \"@allow:$allow_value\" -key \"@eid:$eid\"";
        $fp = popen($cmd, "r");
        $result = "";
        while($buf = fgets($fp, MAX_BUFFER_LEN))
            $result .= $buf;
        pclose($fp);
        list($rid, $offset, $len) = explode("\t", trim($result));
        return $rid;
    }

    /* 記錄 event 變更資料 (目前包括權限與留言) */
    function event_update($mode, $eid, $content, $index_dir)
    {
        /* 儲存 event 變更資料 */
        if ($index_dir == GROUP_EVENT_INDEX)
            $log_file = GROUP_EVENT_UPDATE_LOG;
        else
            $log_file = EVENT_UPDATE_LOG;
        $fp = fopen($log_file, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$mode\t$eid\t$content\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /*** Server Copy 功能 ***/
    /* 記錄 server_copy.list 資料 */
    function write_server_copy_list($s_path, $s_code, $t_acn, $t_path, $t_code)
    {
        $ser_code = auth_encode("$s_path\t$s_code");
        $cli_code = auth_encode("$t_acn\t$t_path\t$t_code");
        $fp = fopen(SERVER_COPY_LIST, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$ser_code\t$cli_code\n");
        flock($fp, LOCK_UN);
        fclose($fp);
        return $cli_code;
    }

    /* 取得第一行 server_copy.list 資料 */
    function get_first_server_copy_list()
    {
        if (!file_exists(SERVER_COPY_LIST))
            return false;
        $fp = fopen(SERVER_COPY_LIST, "r");
        flock($fp, LOCK_SH);
        $log_code = fgets($fp, MAX_BUFFER_LEN);
        flock($fp, LOCK_UN);
        fclose($fp);
        list($info["ser_code"], $info["cli_code"]) = explode("\t", trim($log_code));
        if ((empty($info["ser_code"])) || (empty($info["cli_code"])))
            return false;
        $content = auth_decode($info["ser_code"]);
        list($info["s_path"], $info["s_code"]) = explode("\t", $content);
        $content = auth_decode($info["cli_code"]);
        list($info["t_acn"], $info["t_path"], $info["t_code"]) = explode("\t", $content);
        return $info;
    }

    /* 刪除第一行 server_copy.list 資料 */
    function del_first_server_copy_list()
    {
        if (!file_exists(SERVER_COPY_LIST))
            return false;
        $buf = @file(SERVER_COPY_LIST);
        $cnt = count($buf);
        if ($cnt == 1)
        {
            unlink(SERVER_COPY_LIST);
            return true;
        }
        $content = "";
        for ($i = 1; $i < $cnt; $i++)
            $content .= $buf[$i];
        $fp = fopen(SERVER_COPY_LIST, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $content);
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

    /* 記錄 server_copy.client 資料 */
    function write_server_copy_client($code)
    {
        $fp = fopen(SERVER_COPY_CLIENT, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$code\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /* 刪除一筆 server_copy.client 資料 */
    function del_server_copy_client($code)
    {
        if (!file_exists(SERVER_COPY_CLIENT))
            return false;
        $buf = @file(SERVER_COPY_CLIENT);
        $cnt = count($buf);
        $content = "";
        $exist = false;
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($code == trim($buf[$i]))
            {
                $exist = true;
                continue;
            }
            $content .= $buf[$i];
        }
        if (empty($content))
            unlink(SERVER_COPY_CLIENT);
        else if ($exist == true)
        {
            $fp = fopen(SERVER_COPY_CLIENT, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
        return $exist;
    }

    /*** Group Server 功能 (前端多台 CS Server 組合在一起) ***/
    /* 檢查 group 模式 */
    function chk_group_mode()
    {
        Global $reg_conf, $set_conf;

        if ((!isset($set_conf["group_server"])) || (empty($set_conf["group_server"])))
            return GROUP_NONE;
        if (($reg_conf["acn"] == $set_conf["group_server"]) || ($reg_conf["alias1"] == $set_conf["group_server"]))
            return GROUP_SERVER;
        return GROUP_CLIENT;
    }

    /* 取得 grp_code */
    function get_grp_code($mode=NULL, $arg=NULL)
    {
        Global $reg_conf;

        $grp_code = auth_encode($reg_conf["acn"].":".$reg_conf["alias1"].":$mode:$arg");
        return $grp_code;
    }

    /* 將 Group Server 的 setup.conf 內容傳送到 Group Client */
    function group_update_setup_conf($set_conf=NULL)
    {
        Global $group_mode, $is_manager, $admin_manager, $reg_conf;

        /* 僅 Group Server 且是系統管理者才可執行 */
        /* 2015/3/19 修改,系統管理者與後端管理者可執行 (目前已取消系統管理者) */
        //if (($group_mode != GROUP_SERVER) || ($is_manager != true))
        if (($group_mode != GROUP_SERVER) || (($is_manager != true) && ($admin_manager != true)))
            return false;

        /* 取得 setup.conf 的內容 */
        if (empty($set_conf))
            $set_conf = read_conf(SETUP_CONFIG);

        /* 2015/9/17 修改,檢查 manager 與 subsite_mode 欄位必須有資料 */
        if ((empty($set_conf["manager"])) || ($set_conf["subsite_mode"] == ""))
            return false;

        /* call Client 的 group_api 設定 setup.conf */
        return group_api_server2all("update_setup", json_encode($set_conf));
    }

    /* 將 Group Server 的 subsite_linkname 內容傳送到 Group Client */
    function group_update_subsite_linkname()
    {
        Global $group_mode, $is_manager, $admin_manager, $reg_conf;

        /* 僅 Group Server 且是系統管理者才可執行 */
        /* 2015/3/19 修改,系統管理者與後端管理者可執行 (目前已取消系統管理者) */
        //if (($group_mode != GROUP_SERVER) || ($is_manager != true))
        if (($group_mode != GROUP_SERVER) || (($is_manager != true) && ($admin_manager != true)))
            return false;

        /* 取得 subsite_linkname 內容 */
        $content = implode("", @file(SUB_SITE_LINK_NAME));

        /* call Client 的 group_api 設定 subsite_linkname */
        return group_api_server2all("update_subsite_linkname", $content);
    }

    /* 將 event record 上傳到 Group Server */
    function group_upload_event_rec($rec_content)
    {
        Global $reg_conf, $set_conf, $group_mode;

        /* 若不是 Group 內的 CS 就不必處理 */
        if ($group_mode == GROUP_NONE)
            return false;

        /* 更新 event record 內的資料 */
        $cs = $reg_conf["acn"];
        $rec = recbuf2array(explode("\n", $rec_content));
        if (($rec == false) || (empty($rec)))
            return false;
        $rec[0]["eid"] = $cs."_".$rec[0]["eid"];
        $rec[0]["url"] = "http://$cs".NUCLOUD_DOMAIN.$rec[0]["url"];
        $rec_content = REC_START.REC_BEGIN_PATTERN;
        foreach($rec[0] as $key => $value)
        {
            if ($key == GAIS_REC_FIELD)
                continue;
            $value = trim($value);
            $rec_content .= "@$key:$value\n";
        }

        /* 若是 Group Server 就直接將 event record 加入 index 中 */
        if ($group_mode == GROUP_SERVER)
            return group_set_event_rec($rec_content);

        /* 取得 Group Server 的 IP & Port */
        $ip_port = get_acn_ip_port($set_conf["group_server"]);
        if ($ip_port == false)
            return false;
        list($ip, $port) = explode(":", $ip_port);

        /* 整理要傳送的參數 */
        $grp_code = get_grp_code("set_event_rec");

        /* 將 site_conf 上傳到 Group Server 中 */
        if (($fp = fsockopen($ip, $port, $errno, $errstr)) != false)
        {
            /* 設定傳送到 Server 的參數 */
            $post_arg = "grp_code=$grp_code";
            $post_arg .= "&content=".rawurlencode($rec_content);

            $head = "POST ".GROUP_API." HTTP/1.0\r\n";
            $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: ". strlen($post_arg) . "\r\n\r\n";
            $head .= "$post_arg\r\n";

            fputs($fp, $head);
            fclose($fp);
        }
        return true;
    }

    /* 設定 Group 的 event record */
    function group_set_event_rec($rec_content)
    {
        Global $group_mode;

        /* 若不是 Group Server 就不必處理 */
        if ($group_mode !== GROUP_SERVER)
            return false;

        /* 儲存到 event record 中 */
        $fp = fopen(GROUP_EVENT_REC, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);
        /* 將 record 加到 event index 中 */
        return rput_content(GROUP_EVENT_INDEX, $rec_content, "eid");
    }

    /* 將 site_manager.list 上傳至 Group Server */
    function group_upload_site_manager()
    {
        Global $reg_conf, $set_conf, $group_mode;

        /* 若不是 Group 內的 CS 或 site_manager.list 不存在,就不必處理 */
        if (($group_mode == GROUP_NONE) || (!file_exists(SITE_MANAGER_LIST)) || (real_filesize($SITE_MANAGER_LIST) === 0))
            return false;

        /* 若是 Group Server 就直接將 site_manager.list copy 到 Site_Manager 目錄內 */
        if ($group_mode == GROUP_SERVER)
        {
            if (!is_dir(GROUP_SERVER_DIR))
                return false;
            if (!is_dir(GROUP_SITE_MANAGER_DIR))
                mkdir(GROUP_SITE_MANAGER_DIR);
            $file = GROUP_SITE_MANAGER_DIR.$reg_conf["acn"];
            copy(SITE_MANAGER_LIST, $file);
            return true;
        }

        /* 取得 site_manager.list 內容 */
        $content = implode("", @file(SITE_MANAGER_LIST));

        /* 取得 server 的 IP & Port */
        $ip_port = get_acn_ip_port($set_conf["group_server"]);
        if ($ip_port == false)
            return false;
        list($ip, $port) = explode(":", $ip_port);

        /* 將 site_manager.list 上傳到 Group Server */
        if (($fp = fsockopen($ip, $port, $errno, $errstr)) != false)
        {
            /* 設定傳送到 Server 的參數 */
            $arg = "acn=".$reg_conf["acn"]."&content=$content";

            $head = "POST ".GROUP_GET_SITE_MANAGER_API." HTTP/1.0\r\n";
            $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: ". strlen($arg) . "\r\n\r\n";
            $head .= "$arg\r\n";

            fputs($fp, $head);
            fclose($fp);
        }
        return true;
    }

    /* 取得 Client CS 所上傳的 site_manager.list 並儲存起來 */
    function group_get_site_manager($cs_acn, $content)
    {
        Global $reg_conf, $group_mode;

        /* 僅 Group Server 才會取得 Client 端的 site_manager.list */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 取得 group_cs.list 資料,要檢查上傳的 cs_acn 是否在 list 中,若不在就不處理 */
        if (in_group_cs_list($cs_acn) == false)
            return false;

        /* 將 CS 所上傳的 site_manager.list 儲存起來 */
        if (!is_dir(GROUP_SERVER_DIR))
            return false;
        if (!is_dir(GROUP_SITE_MANAGER_DIR))
            mkdir(GROUP_SITE_MANAGER_DIR);
        $file = GROUP_SITE_MANAGER_DIR.$cs_acn;
        $fp = fopen($file, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $content);
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

    /* 輸出 site list 資料 */
    function group_site_list()
    {
        Global $login_user, $group_mode;

        /* 僅 Group Server 有此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 檢查 Site Index 是否存在 */
        $index_file = GROUP_SITE_INDEX."/current";
        if (!file_exists($index_file))
            return false;

        /* 取出所有 site 資料 */
        $rBegin = str_replace("\n", "\\n", REC_START.REC_BEGIN_PATTERN);
        $cmd = SEARCH_BIN_DIR."dbman -recbeg \"$rBegin\" -flag \"@_f:Normal\" -sort $index_file";
        $fp = popen($cmd, "r");
        $rec_start = false;
        $content = "";
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            $buf = trim($buf)."\n";
            if ($buf == "\n")
                continue;

            /* 判斷是否有新的 Record (Record 的啟始為 REC_START,第二行一開始必須是 REC_FIELD_START) */
            if ($buf == REC_START)
            {
                $rec_start = true;
                continue;
            }
            if (($rec_start == true) && ($buf[0] == REC_FIELD_START))
            {
                $site_acn = NULL;
                $cs = NULL;
                $owner = NULL;
                $manager = NULL;
                $member = NULL;
            }
            $rec_start = false;

            /* 檢查是否為 site_acn 或 cs 欄位,若是就取出內容,其餘的都跳過 */
            /* 2015/3/3 修改,要另外取得 owner, manager, member 等欄位資料 */
            if (strstr($buf, "@site_acn:")  === $buf)
                $site_acn = strtolower(trim(substr($buf, 10)));
            else if (strstr($buf, "@cs:") === $buf)
                $cs = strtolower(trim(substr($buf, 4)));
            else if (strstr($buf, "@owner:") === $buf)
                $owner = strtolower(trim(substr($buf, 7)));
            else if (strstr($buf, "@manager:") === $buf)
                $manager = strtolower(trim(substr($buf, 9)));
            else if (strstr($buf, "@member:") === $buf)
                $member = strtolower(trim(substr($buf, 8)));
            else
                continue;

            /* 整理 site list 資料,目前僅需要輸出 cs 與 site_acn 資料 */
            /* 2015/3/3 修改,新增 owner, manager, member 資料 */
            if (($site_acn !== NULL) && ($cs !== NULL))
                $content .= strtolower("$cs\t$site_acn\t$owner\t$manager\t$member\n");
        }
        pclose($fp);

        return $content;
    }

    /* 取得 group 中的 site list 資料 */
    function group_get_site_list($rewrite=false, $all_field=false)
    {
        Global $group_mode, $set_conf;

        /* 若不是 Group 內的 CS 就不必處理 */
        if ($group_mode == GROUP_NONE)
            return false;

        /* 檢查 group_site.list 是否存在 */
        $list = NULL;
        if (file_exists(GROUP_SITE_LIST))
        {
            /* 檢查是否在有效時間內,若是就取出 group_site.list 資料 */
            $ftime = filemtime(GROUP_SITE_LIST);
            $ntime = time();
            $atime = $ntime - $ftime;
            if ($atime < MAX_ACTIVE_TIME)
                $list = @file(GROUP_SITE_LIST);
            else
                unlink(GROUP_SITE_LIST);
        }
        /* 若 list 沒資料或傳入 rewrite 參數為 true (代表要重寫 group_site.list),就向 Group Server 取得 */
        if ((empty($list)) || ($rewrite == true))
        {
            /* 若是 Group Server 本身就直接取回,若是 Client 就透過 group_api 取得 */
            if ($group_mode == GROUP_SERVER)
                $content = group_site_list();
            else
            {
                $grp_code = get_grp_code("get_site_list");
                $url = "http://".$set_conf["group_server"].NUCLOUD_DOMAIN.GROUP_API."?grp_code=$grp_code";
                $content = trim(implode("", @file($url)))."\n";
                if (strstr($content, "Error") != false)
                    return false;
            }
            $list = explode("\n", trim($content));
            /* 寫入 group_site.list */
            $fp = fopen(GROUP_SITE_LIST, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        /* 由 list 資料整理出 site list */
        if ((empty($list)) || (($cnt = count($list)) == 0))
            return false;
        /* 第一筆一律先設定 Group Server 的 web 網站 */
        if ($all_field == true)
        {
            $site_list[0]["site_acn"] = "web";
            $site_list[0]["cs"] = $set_conf["group_server"];
        }
        else
            $site_list["web"] = $set_conf["group_server"];
        $n = 1;
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 2015/3/3 修改,取得資料時,多增加 owner, manager, member 等資料 */
            //list($cs_site, $site_acn, $owner, $manager, $member) = explode("\t", trim($list[$i]));
            /* 2015/6/1 修改,調整取得資料檢查,以避免 error log 中出現 php 警告訊息 */
            $s_list = explode("\t", trim($list[$i]));
            $cs_site = $s_list[0];
            $site_acn = $s_list[1];
            $owner = (isset($s_list[2])) ? $s_list[2] : NULL;
            $manager = (isset($s_list[3])) ? $s_list[3] : NULL;
            $member = (isset($s_list[4])) ? $s_list[4] : NULL;
            /* 若 site_acn 已存在就自動跳過,也就是僅保留第一筆 (web 已設定過,所以只會保留 Group Server 的 web 網站) */
            /* 2015/3/4 移除 */
            //if (isset($site_list[$site_acn]))
            //    continue;
            /* 2015/3/4 修改,檢查是否為 Group Server 的 web 網站 */
            if ($site_acn == "web")
            {
                /* 其他 Client 的 web 網站都跳過 */
                if ($cs_site != $set_conf["group_server"])
                    continue;

                /* 若是 all_field 為 true 就更新第一筆資料 */
                if ($all_field == true)
                {
                    $site_list[0]["owner"] = $owner;
                    $site_list[0]["manager"] = $manager;
                    $site_list[0]["member"] = $member;
                }
                continue;
            }

            /* 2015/3/3 修改,若 all_field 為 true,代表要輸出所有欄位,否則就使用原輸出資料 */
            if ($all_field == true)
            {
                $site_list[$n]["site_acn"] = $site_acn;
                $site_list[$n]["cs"] = $cs_site;
                $site_list[$n]["owner"] = $owner;
                $site_list[$n]["manager"] = $manager;
                $site_list[$n]["member"] = $member;
                $n++;
            }
            else
                $site_list[$site_acn] = $cs_site;
        }
        return $site_list;
    }

    /* 更新一筆 group_site.list 資料 */
    function update_group_site_list($site_acn, $cs, $mode="add", $conf=NULL)
    {
        if ((empty($site_acn)) || (empty($cs)))
            return false;
        if (($mode != "add") && ($mode != "del"))
            return false;

        $site_acn = strtolower($site_acn);
        $cs = strtolower($cs);

        $s_list = @file(GROUP_SITE_LIST);
        $cnt = count($s_list);
        $content = "";
        $change = false;
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 2015/3/3 修改,增加 owner, manager, member 等資料 */
            list($g_cs, $g_site, $owner, $manager, $member) = explode("\t", trim($s_list[$i]));
            if (($g_site == $site_acn) && ($g_cs == $cs))
            {
                /* 找到符合的資料且 mode 為 add 時,代表已有相同資料存在,不需要再變更,直接回傳 true */
                /* 2015/3/3 修改,要檢查 owner, manager, member 等資料是否也相同 */
                if (($mode == "add") && ($owner == $conf["owner"]) && ($manager == $conf["manager"]) && ($member ==  $conf["member"]))
                    return true;
                $change = true;
                if ($mode == "add")
                    $content .= $s_list[$i];
            }
            else
                $content .= $s_list[$i];
        }

        /* 若 mode 為 add 且沒找到符合的資料 (change 為 true),就將資料新增到後面 */
        /* 2015/3/3 修改,增加 owner, manager, member 等資料 */
        if (($mode == "add") && ($change == false))
        {
            $change = true;
            $content .= "$cs\t$site_acn\t".$conf["owner"]."\t".$conf["manager"]."\t".$conf["member"]."\n";
        }

        /* 若資料有變更就更新 group_site.list 檔 */
        if ($change == true)
        {
            $fp = fopen(GROUP_SITE_LIST, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
        return true;
    }

    /* 取得 group 中 user 管理的網站 */
    function group_get_user_site($acn="", $mail="", $get_owner=false)
    {
        Global $login_user, $group_mode;

        /* 僅 Group Server 有此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 若 Site_Manager 目錄不存在就不必處理 */
        if (!is_dir(GROUP_SITE_MANAGER_DIR))
            return false;

        /* 若沒傳入 acn 就直接使用登入者的 acn 與 mail */
        if (empty($acn))
        {
            $acn = $login_user["acn"];
            $mail = $login_user["mail"];
        }
        else
        {
            /* 有傳入 acn 且沒有傳入 mail 就取得 user 的 mail */
            if (empty($mail))
            {
                $user = get_user_data($acn);
                if (!empty($user["mail"]))
                    $mail = $user["mail"];
            }
        }
        /* 如果沒有 acn 就不處理 */
        if (empty($acn))
            return false;
        $acn = strtolower($acn);

        /* 取出 Site_Manager 目錄內的 site manager list 資料 */
        $user_site = array();
        $handle = opendir(GROUP_SITE_MANAGER_DIR);
        while ($cs_acn = readdir($handle))
        {
            /* 只處理檔案 */
            $m_file = GROUP_SITE_MANAGER_DIR.$cs_acn;
            if (!is_file($m_file))
                continue;

            /* 取得 site manager list 資料 */
            $m_list = @file($m_file);
            $cnt = count($m_list);
            for ($i = 0; $i < $cnt; $i++)
            {
                list($site_acn, $manager_list) = explode("\t", strtolower(trim($m_list[$i])));
                $m_acn = explode(",", $manager_list);
                $m_cnt = count($m_acn);

                /* 檢查是否為網站的 owner 或 manager */
                for ($j = 0; $j < $m_cnt; $j++)
                {
                    if (($acn == $m_acn[$j]) || ($mail == $m_acn[$j]))
                    {
                        array_push($user_site, array("cs_acn"=>$cs_acn, "site_acn"=>$site_acn));
                        break;
                    }
                    /* 2014/1/20 新增,若 mode=owner 代表只找由 user 為 owner 的網站,第一筆就是 owner,後面的就不必比對 */
                    if ($get_owner == true)
                        break;
                }
            }
        }
        closedir($handle);
        return $user_site;
    }

    /* 取得網站在 group 中的那台 CS 中 */
    function group_get_site_cs($site_acn)
    {
        Global $group_mode;

        if (empty($site_acn))
            return false;
        $site_acn = strtolower($site_acn);

        /* 僅 Group Server 有此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 若 Site_Manager 目錄不存在就不必處理 */
        if (!is_dir(GROUP_SITE_MANAGER_DIR))
            return false;

        /* 取出 Site_Manager 目錄內的 site manager list 資料 */
        $cs = false;
        $handle = opendir(GROUP_SITE_MANAGER_DIR);
        while ($cs_acn = readdir($handle))
        {
            /* 只處理檔案 */
            $m_file = GROUP_SITE_MANAGER_DIR.$cs_acn;
            if (!is_file($m_file))
                continue;

            /* 取得 site manager list 資料 */
            $m_list = @file($m_file);
            $cnt = count($m_list);
            for ($i = 0; $i < $cnt; $i++)
            {
                /* 若網站 acn 與 site_acn 相同,就取得 cs 資料 */
                list($s_acn, $s_mlist) = explode("\t", strtolower(trim($m_list[$i])));
                if ($s_acn != $site_acn)
                    continue;
                $cs = $cs_acn;
                break;
            }
            if ($cs != false)
                break;
        }
        closedir($handle);
        return $cs;
    }

    /* 檢查網站是否存在 group 中 */
    function group_site_exists($site_acn)
    {
        Global $group_mode, $set_conf;

        /* 2015/8/19 修改,若不是 Group 內的 CS 就不處理,若是 Group Client 就向 Group Server 查詢 */
        if ($group_mode == GROUP_NONE)
            return false;
        if ($group_mode == GROUP_CLIENT)
        {
            /* 向 Group Server 查詢 site_acn 是否存在 */
            $grp_code = get_grp_code("site_exists", $site_acn);
            $url = "http://".$set_conf["group_server"].NUCLOUD_DOMAIN.GROUP_API."?grp_code=$grp_code";
            $content = trim(implode("", @file($url)));
            if ($content == YES)
                return true;
            return false;
        }

        if (group_get_site_cs($site_acn) != false)
            return true;
        return false;
    }

    /* 寫入 group_cs.list 資料 */
    function write_group_cs_list($cs_list)
    {
        Global $group_mode;

        /* 僅 group server 才會用到此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 整理 group_cs.list 資料並寫入 */
        $cnt = count($cs_list);
        $content = "";
        for ($i = 0; $i < $cnt; $i++)
            $content .= trim($cs_list[$i]["status"].$cs_list[$i]["cs_acn"])."\n";
        $fp = fopen(GROUP_CS_LIST, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $content);
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

    /* 取得 group_cs.list 資料 (mode=online 代表只取出目前可連線的資料) */
    function get_group_cs_list($mode="")
    {
        Global $group_mode;

        /* 僅 group server 才會用到此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 取得 group_cs.list 資料 */
        if (!file_exists(GROUP_CS_LIST))
            return false;
        $list = @file(GROUP_CS_LIST);
        $cnt = count($list);
        $n = 0;
        for ($i = 0; $i < $cnt; $i++)
        {
            $cs_acn = strtolower(trim($list[$i]));
            $status = NULL;
            /* 若 cs_acn 前面有 * 代表為無法連線的狀態 */
            if (substr($cs_acn, 0, 1) == GROUP_STATUS_FAIL)
            {
                /* 若 mode=online 因為此筆 cs 是無法連線狀態,所以直接跳過不處理 */
                if ($mode == "online")
                    continue;
                $cs_acn = substr($cs_acn, 1);
                $status = GROUP_STATUS_FAIL;
            }

            /* 若 mode!=online 就傳回 cs_acn 與 status 資料,若 mode=online 就只需傳回 cs_acn 資料 */
            if ($mode != "online")
            {
                $cs_list[$i]["cs_acn"] = $cs_acn;
                $cs_list[$i]["status"] = $status;
            }
            else
                $cs_list[$n++] = $cs_acn;
        }
        return $cs_list;
    }

    /* 檢查是否在 group_cs.list 內 */
    function in_group_cs_list($cs)
    {
        Global $group_mode;

        /* 僅 group server 才會用到此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 取得 group_cs.list 資料,並檢查 cs 是否在 list 中 */
        $cs_list = get_group_cs_list();
        if ($cs_list == false)
            return false;
        $cs = strtolower($cs);
        $cnt = count($cs_list);
        $in_list = false;
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($cs == $cs_list[$i]["cs_acn"])
            {
                $in_list = true;
                break;
            }
        }
        return $in_list;
    }

    /* 設定 group_cs 的狀態 (預設為設定 offline) */
    function set_group_cs_status($cs, $status=GROUP_STATUS_FAIL)
    {
         Global $group_mode;

        /* 僅 group server 才會用到此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 取得 group_cs.list 資料 */
        $cs_list = get_group_cs_list();
        if ($cs_list == false)
            return false;

        /* status 若不是 GROUP_STATUS_FAIL 就設為空的 */
        if ($status !== GROUP_STATUS_FAIL)
            $status = NULL;

        /* 整理並更新 group_cs.list 資料 */
        $cs = strtolower($cs);
        $cnt = count($cs_list);
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($cs !== $cs_list[$i]["cs_acn"])
                continue;
            /* status 與原設定不相同才需要重新寫入 */
            if ($status !== $cs_list[$i]["status"])
            {
                $cs_list[$i]["status"] = $status;
                write_group_cs_list($cs_list);
            }
            return true;
        }
        return false;
    }

    /* 檢查 group_cs 是否 online */
    function chk_group_cs_online($cs="")
    {
        Global $group_mode;

        /* 僅 group server 才會用到此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 取得 group_cs.list 資料 */
        $cs_list = get_group_cs_list();
        if ($cs_list == false)
            return false;

        /* 檢查 cs 是否在 group_cs.list 中,並檢查是否 online,若沒傳入 cs 就檢查所有 cs list 是否 online */
        $cs = strtolower($cs);
        $cnt = count($cs_list);
        $update = false;
        for ($i = 0; $i < $cnt; $i++)
        {
            if ((!empty($cs)) && ($cs != $cs_list[$i]["cs_acn"]))
                continue;
            /* 由 wns 取得 cs_acn 的 IP & Port,若回傳 false 代表此 cs_acn 沒有 online,就設定 status 為 GROUP_STATUS_FAIL */
            $status = NULL;
            if (get_acn_ip_port($cs_list[$i]["cs_acn"]) == false)
                $status = GROUP_STATUS_FAIL;
            if ($status !==  $cs_list[$i]["status"])
            {
                $cs_list[$i]["status"] = $status;
                $update = true;
            }
        }

        /* 若內容有變更,就要重新寫入 group_cs.list */
        if ($update == true)
            write_group_cs_list($cs_list);
        return true;
    }

    /* 將 group_reg.cnt 內容加 1 */
    function add_group_reg_cnt()
    {
        Global $group_mode;

        /* 僅 group server 才會用到此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        if (!file_exists(GROUP_REG_CNT))
            $reg_cnt = 0;
        else
            $reg_cnt = trim(implode("", @file(GROUP_REG_CNT)));
        $reg_cnt++;
        $fp = fopen(GROUP_REG_CNT, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $reg_cnt);
        flock($fp, LOCK_UN);
        fclose($fp);
        return $reg_cnt;
    }

    /* 在 group 中建立網站 */
    function group_new_site($ssn, $acn, $mail, $sun, $site_acn=NULL)
    {
        Global $group_mode, $reg_conf;

        /* 僅 group server 才會用到此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 2014/8/22 新增,檢查 user 網站是否已建立 */
        //if (group_site_exists($acn) == true)
        //    return true;
        /* 2015/1/9 修改,改用 group_get_site_cs 來檢查 user 網站是否已建立,若已建立就回傳 cs */
        $cs = group_get_site_cs($acn);
        if ($cs != false)
            return $cs;
        /* 2015/10/19 新增,若有傳入 site_acn 就再檢查 site_acn 是否已存在 */
        if ((!empty($site_acn)) && ($site_acn !== $acn))
        {
            $cs = group_get_site_cs($site_acn);
            if ($cs != false)
                return $cs;
        }

        /* 取得 group_cs.list 資料 (只取 online 的資料) */
        $cs_list = get_group_cs_list("online");
        if (($cs_list == false) || (empty($cs_list)))
            return false;
        $cnt = count($cs_list);

        /* 將 group_reg.cnt 內容加 1,並以此數值決定要在那台 Client 建立網站 */
        $grp_cnt = add_group_reg_cnt();
        $n = $grp_cnt % $cnt;
        $cs = trim($cs_list[$n]);

        /* 整理相關參數,通知選到的 Client 建立網站 */
        /* 2015/10/19 修改,若有傳入 site_acn 就再增加傳送 site_acn 參數 */
        $arg = "grp_code=".auth_encode($reg_conf["acn"].":".$reg_conf["alias1"].":$ssn:$acn:$mail:$sun:$site_acn");
        $url = "http://$cs".NUCLOUD_DOMAIN."/Site_Prog/add_site.php?$arg";
        $content = trim(implode("", @file($url)));
        if ($content == "ok")
        {
            /* 2015/6/9 新增,記錄 register log (若目錄不存在就建立) */
            $log_year = date("Y");
            $log_date = date("Ymd");
            if (!is_dir(REGISTER_LOG_DIR))
                mkdir(REGISTER_LOG_DIR);
            $log_dir = REGISTER_LOG_DIR.$log_year;
            if (!is_dir($log_dir))
                mkdir($log_dir);
            $log_file = $log_dir."/group_".$log_date;
            write_server_log($log_file, "$acn\t$sun\t$acn\t$cs\t$site_acn");
            return $cs;
        }
        /* 若不是錯誤訊息,代表 Client 有問題,必須設定 group_cs.list 將這台 group_cs 設為 GROUP_STATUS_FAIL,同時再重新 call group_new_site 建立網站 */
        if (strstr($content, "Error") != false)
        {
            set_group_cs_status($cs, GROUP_STATUS_FAIL);
            return group_new_site($ssn, $acn, $mail, $sun, $site_acn);
        }
        return false;
    }

    /* 從傳入的系統參數 (_REQUEST) 中取得要重新導向到 Group Client 的 url */
    function group_redirect_url()
    {
        Global $group_mode, $reg_conf;

        /* 僅 Group Server 有此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 若 Site_Manager 目錄不存在就不必處理 */
        if (!is_dir(GROUP_SITE_MANAGER_DIR))
            return false;

        /* 一定要有 page_url 參數 */
        if (!isset($_REQUEST["page_url"]))
            return false;
        $page_url = $_REQUEST["page_url"];

        /* page_url 開頭必須是 / 且不可以有 ./ 及其他非檔名允許的特殊符號 */
        if ((substr($page_url, 0, 1) != "/") || (strstr($page_url, "./") != false) || (preg_match(FORMAT_FAIL_PATH_NAME, $page_url)))
            return false;
        /* 檢查 page_url 是否為合法網站位置 (/Site/{site_acn}...) */
        $path = explode("/", substr($page_url, 1));
        if (($path[0] != SUB_SITE_NAME) || (empty($path[1])))
            return false;
        $site_acn = $path[1];
        /* 若網站目錄存在,代表此網站就在 Group Server 內,直接回傳 false (由 show_page.php 處理) */
        if (is_dir(WEB_ROOT_PATH."/".SUB_SITE_NAME."/$site_acn"))
            return false;

        /* 取出 Site_Manager 目錄內的 site manager list 資料 */
        $redirect_url = false;
        $handle = opendir(GROUP_SITE_MANAGER_DIR);
        while ($cs_acn = readdir($handle))
        {
            /* 只處理檔案 */
            $m_file = GROUP_SITE_MANAGER_DIR.$cs_acn;
            if (!is_file($m_file))
                continue;

            /* Group Server 本身的網站不進行檢查 */
            if (($reg_conf["acn"] == $cs_acn) || ($reg_conf["alias1"] == $cs_acn))
                continue;

            /* 取得 site manager list 資料 */
            $m_list = @file($m_file);
            $cnt = count($m_list);
            for ($i = 0; $i < $cnt; $i++)
            {
                /* 若網站 acn 與 page_url 的 site_acn 相同,就設定 redirect url */
                list($s_acn, $s_mlist) = explode("\t", strtolower(trim($m_list[$i])));
                if ($s_acn != $site_acn)
                    continue;
                $redirect_url = "http://$cs_acn".NUCLOUD_DOMAIN.$page_url;
                break;
            }
            if ($redirect_url != false)
                break;
        }
        closedir($handle);
        return $redirect_url;
    }

    /* 將網站設定檔上傳更新到 Group Server */
    function group_upload_site_conf($conf)
    {
        Global $group_mode, $reg_conf, $set_conf;

        /* 若不是 Group 內的 CS 就不必處理 */
        if ($group_mode == GROUP_NONE)
            return false;

        /* 2015/1/21 新增,先清除 conf 內的 quota 資料 */
        if (isset($conf["quota"]))
            unset($conf["quota"]);

        /* 2014/11/18 修改,新增取得 quota 與使用空間資料 */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $site_acn = $conf["site_acn"];
        $owner = $conf["owner"];
        $q_list = get_quota_list(true);
        /* 2015/7/17 修改,因個人網站不一定是帳號當 site_acn 所以應該要先檢查 owner 的 quota 設定,找不到再找 site_acn 的 quota 設定,都找不到就設為 0 (代表無限制) */
        //if (($conf["type"] == 0) && (isset($q_list[$site_acn])))
        //    $conf["quota"] = $q_list[$site_acn];
        if ($conf["type"] == 0)
        {
            if (isset($q_list[$owner]))
                $conf["quota"] = $q_list[$owner];
            else if (isset($q_list[$site_acn]))
                $conf["quota"] = $q_list[$site_acn];
            else
                $conf["quota"] = 0;
        }
        $q_conf = read_conf($site_path.$site_acn."/".NUWEB_QUOTA);
        if ($q_conf !== false)
        {
            foreach($q_conf as $key => $value)
                $conf[$key]=$value;
        }

        /* 若是 Group Server 就直接更新網站設定檔資料 */
        if ($group_mode == GROUP_SERVER)
        {
            group_update_site_conf($conf, $reg_conf["acn"]);
            return true;
        }

        /* 2015/1/15 新增,更新 group_site.list 資料 */
        update_group_site_list($site_acn, $reg_conf["acn"], "add", $conf);

        /* 整理要傳送的參數 */
        $grp_code = get_grp_code("update_site_conf");
        $content = json_encode($conf);

        /* 取得 Group Server 的 IP & Port */
        $ip_port = get_acn_ip_port($set_conf["group_server"]);
        if ($ip_port == false)
            return false;
        list($ip, $port) = explode(":", $ip_port);

        /* 將 site_conf 上傳到 Group Server 中 */
        if (($fp = fsockopen($ip, $port, $errno, $errstr)) != false)
        {
            /* 設定傳送到 Server 的參數 */
            $post_arg = "grp_code=$grp_code";
            if (!empty($content))
                $post_arg .= "&content=$content";

            $head = "POST ".GROUP_API." HTTP/1.0\r\n";
            $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: ". strlen($post_arg) . "\r\n\r\n";
            $head .= "$post_arg\r\n";

            fputs($fp, $head);
            fclose($fp);
        }
        return true;
    }

    /* 通知 Group Server 刪除網站設定資料 */
    function group_del_site_conf($site_acn)
    {
        Global $group_mode, $reg_conf, $set_conf;

        /* 若不是 Group 內的 CS 就不必處理 */
        if ($group_mode == GROUP_NONE)
            return false;

        /* 若是 Group Server 就直接刪除網站設定資料 */
        if ($group_mode == GROUP_SERVER)
        {
            group_ser_del_site_conf($site_acn, $reg_conf["acn"]);
            return true;
        }

        /* 2015/1/15 新增,更新 group_site.list 資料 */
        update_group_site_list($site_acn, $reg_conf["acn"], "del");

        /* 通知 Group Server 刪除 site_acn 設定資料 */
        $grp_code = get_grp_code("del_site_conf", $site_acn);
        $url = "http://".$set_conf["group_server"].NUCLOUD_DOMAIN.GROUP_API."?grp_code=$grp_code";
        $content = trim(implode("", @file($url)));
        if ($content != "ok")
            return false;
        return true;
    }

    /* 更新 Group Server 內的網站設定資料 */
    function group_update_site_conf($conf, $cs_acn)
    {
        Global $group_mode;

        /* 僅 Group Server 才會用到此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 檢查 cs_acn 是否在 group_cs.list 內,若不在就不處理 */
        if (in_group_cs_list($cs_acn) == false)
            return false;

        /* 若沒有 site_acn 欄位就不處理 */
        if ((!isset($conf["site_acn"])) || (empty($conf["site_acn"])))
            return false;

        /* 更新 Site_Index 的 site_acn 設定資料 */
        $url = "http://$cs_acn".NUCLOUD_DOMAIN."/Site/".strtolower($conf["site_acn"])."/";
        $rec_content = REC_START.REC_BEGIN_PATTERN;
        $rec_content .= "@url:$url\n";
        foreach($conf as $key => $value)
            $rec_content .= "@$key:".trim($value)."\n";
        $rec_content .= "@cs:$cs_acn\n";
        rupdate_content(GROUP_SITE_INDEX, $rec_content, "url", $url);

        /* 更新 site managet list 資料 */
        $file = GROUP_SITE_MANAGER_DIR.$cs_acn;
        $s_list = @file($file);
        $content = "";
        $update = false;
        $cnt = count($s_list);
        for ($i = 0; $i < $cnt; $i++)
        {
            list($s_acn, $s_manager) = explode("\t", $s_list[$i], 2);
            if (($s_acn == $conf["site_acn"]) && ($s_manager != $conf["manager"]))
            {
                $update = true;
                $content .= $s_acn."\t".$conf["manager"]."\n";
            }
            else
                $content .= $s_list[$i];
        }

        if ($update == true)
        {
            $fp = fopen($file, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        /* 2015/1/15 新增,更新 group_site.list 資料 */
        update_group_site_list($conf["site_acn"], $cs_acn, "add", $conf);

        return true;
    }

    /* 刪除 Group Server 內 site_acn 的網站設定資料 */
    function group_ser_del_site_conf($site_acn, $cs_acn)
    {
        Global $group_mode;

        /* 僅 Group Server 才會用到此功能 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 檢查 cs_acn 是否在 group_cs.list 內,若不在就不處理 */
        if (in_group_cs_list($cs_acn) == false)
            return false;

        /* 若沒有 site_acn 欄位就不處理 */
        if (empty($site_acn))
            return false;

        /* 2015/1/15 新增,更新 group_site.list 資料 */
        update_group_site_list($site_acn, $cs_acn, "del");

        /* 刪除 Group Server 內 Site_Index 的 site_acn 設定資料 */
        $url = "http://$cs_acn".NUCLOUD_DOMAIN."/Site/$site_acn/";
        if (rdelete(GROUP_SITE_INDEX, "url", $url) == true)
        {
            /* 同時整理 site managet list 資料,將 site_acn 刪除 */
            $file = GROUP_SITE_MANAGER_DIR.$cs_acn;
            $s_list = @file($file);
            $content = "";
            $cnt = count($s_list);
            for ($i = 0; $i < $cnt; $i++)
            {
                list($s_acn, $s_manager) = explode("\t", $s_list[$i], 2);
                if ($s_acn == $site_acn)
                    continue;
                $content .= $s_list[$i];
            }
            $fp = fopen($file, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);

            return true;
        }
        return false;
    }

    /* 將 Server 內所有網站的設定資料轉成 record */
    function group_get_site_conf_rec()
    {
        Global $group_mode;

        /* 若不是 Group 內的 CS 就不必處理 */
        if ($group_mode == GROUP_NONE)
            return false;

        /* 2015/1/21 修改,改 call get_server_site_conf_rec 函數取得網站設定資料 record */
        return get_server_site_conf_rec();
    }

    /* Group Server 取得所有 CS 的網站設定 record */
    function group_ser_get_site_conf_rec()
    {
        Global $group_mode, $reg_conf;

        /* 若不是 Group Server 就不處理 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 檢查與整理目錄 */
        if (!is_dir(GROUP_SERVER_DIR))
            return false;
        /* 2015/1/19 修改,不清除 GROUP_SITE_CONF_REC_DIR 以避免發生找不到網站的問題 */
        //if (is_dir(GROUP_SITE_CONF_REC_DIR))
        //{
        //    $fp = popen("rm -rf ".GROUP_SITE_CONF_REC_DIR, "r");
        //    pclose($fp);
        //}
        if (!is_dir(GROUP_SITE_CONF_REC_DIR))
            mkdir(GROUP_SITE_CONF_REC_DIR);
        $index_dir = GROUP_SITE_INDEX;
        $new_index_dir = GROUP_SITE_INDEX."_new";
        $old_index_dir = GROUP_SITE_INDEX."_old";
        rdb_gen($new_index_dir);

        /* 先取出 Group Server 的網站設定資料 record 並存檔同時加入新 Index 內 */
        /* 2015/1/19 修改,先將抓到的 record 存到 new_rec_file,確認檔案存在且 size > 0 才替換掉原 rec_file */
        $rec_content = group_get_site_conf_rec();
        $rec_file = GROUP_SITE_CONF_REC_DIR.$reg_conf["acn"];
        $new_rec_file = $rec_file."_new";
        $fp = fopen($new_rec_file, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $rec_content);
        flock($fp, LOCK_UN);
        fclose($fp);
        if ((file_exists($new_rec_file)) && (real_filesize($new_rec_file) > 0))
        {
            unlink($rec_file);
            rename($new_rec_file, $rec_file);
        }
        else
            unlink($new_rec_file);
        rput($new_index_dir, $rec_file);

        /* 取得 group_cs.list 資料 (只取 online 的資料) */
        /* 2015/3/3 修改,取消只取 online 改成所有都取出,以避免發生找不到網站的問題 */
        //$cs_list = get_group_cs_list("online");
        $cs_list = get_group_cs_list();
        if (($cs_list != false) && (!empty($cs_list)))
        {
            $cnt = count($cs_list);
            $grp_code = get_grp_code("get_site_conf_rec");
            for ($i = 0; $i < $cnt; $i++)
            {
                /* 若 Client CS 是 Group Server 本身,就跳過不處理 */
                if (($reg_conf["acn"] == $cs_list[$i]["cs_acn"]) || ($reg_conf["alias1"] == $cs_list[$i]["cs_acn"]))
                    continue;

                /* 取得 Client 的網站設定資料 record 並存檔同時加入新 Index 內 */
                /* 2015/1/19 修改,先將抓到的 record 存到 new_rec_file,確認檔案存在且 size > 0 才替換掉原 rec_file */
                /* 2015/3/3 修改,若是沒有 online 的 Client 就不抓取 record 直接使用舊的 record 檔 */
                $rec_file = GROUP_SITE_CONF_REC_DIR.$cs_list[$i]["cs_acn"];
                if ($cs_list[$i]["status"] !== GROUP_STATUS_FAIL)
                {
                    $url = "http://".$cs_list[$i]["cs_acn"].NUCLOUD_DOMAIN.GROUP_API."?grp_code=$grp_code";
                    $rec_content = trim(implode("", @file($url)));
                    if (empty($rec_content))
                    {
                        /* 2015/3/6/ 修改,若抓到的 record content 為空的,就用舊的 record 加入 Index 中 */
                        rput($new_index_dir, $rec_file);
                        continue;
                    }
                    $new_rec_file = $rec_file."_new";
                    $fp = fopen($new_rec_file, "w");
                    flock($fp, LOCK_EX);
                    fputs($fp, $rec_content);
                    flock($fp, LOCK_UN);
                    fclose($fp);
                    if ((file_exists($new_rec_file)) && (real_filesize($new_rec_file) > 0))
                    {
                        unlink($rec_file);
                        rename($new_rec_file, $rec_file);
                    }
                    else
                        unlink($new_rec_file);
                }
                rput($new_index_dir, $rec_file);
            }
        }

        /* 將新 Index 切換上線 */
        if (is_dir($old_index_dir))
        {
            $fp = popen("rm -rf $old_index_dir", "r");
            pclose($fp);
        }
        if (is_dir($index_dir))
            rename($index_dir, $old_index_dir);
        rename($new_index_dir, $index_dir);
        return true;
    }

    /* 設定 Group CS 的 member quota */
    function group_set_member_quota($acn, $quota)
    {
        Global $group_mode, $reg_conf, $set_conf;

        /* 若不是 Group 內的 CS 就不處理 */
        if ($group_mode == GROUP_NONE)
            return false;

        /* 若是 Group Client,就先設定 member_quota */
        if ($group_mode == GROUP_CLIENT)
            set_member_quota($acn, $quota);

        /* 更新網站的設定資料 */
        $conf_file = WEB_ROOT_PATH."/".SUB_SITE_NAME."/$acn/".NUWEB_CONF;
        $conf = read_conf($conf_file);
        if ($conf != false)
            group_upload_site_conf($conf);
    }

    /* 新增 short_code 資料到 Group Server */
    function group_add_short($short_code, $url)
    {
        Global $group_mode, $reg_conf, $set_conf;

        /* 若不是 Group 內的 CS 就不處理 */
        if ($group_mode == GROUP_NONE)
            return false;

        /* 若是 Group Server 就直接更新 short_code 檔案 */
        if ($group_mode == GROUP_SERVER)
        {
            if (!is_dir(GROUP_SERVER_DIR))
                return false;
            if (!is_dir(GROUP_SHORT_DIR))
                mkdir(GROUP_SHORT_DIR);
            $file = GROUP_SHORT_DIR.$reg_conf["acn"];
            copy(SHORT_CODE_LIST, $file);
            return true;
        }

        /* 通知 Group Server 新增 short_code 資料 */
        $grp_code = get_grp_code("add_short", "$short_code:$url");
        $url = "http://".$set_conf["group_server"].NUCLOUD_DOMAIN.GROUP_API."?grp_code=$grp_code";
        $content = trim(implode("", @file($url)));
        if ($content != "ok")
            return false;
        return true;
    }

    /* 更新 Group Server 的 short_code 資料 */
    function group_update_short($short_code, $new_short_code)
    {
        Global $group_mode, $reg_conf, $set_conf;

        /* 若不是 Group 內的 CS 就不處理 */
        if ($group_mode == GROUP_NONE)
            return false;

        /* 若是 Group Server 就直接更新 short_code 檔案 */
        if ($group_mode == GROUP_SERVER)
        {
            if (!is_dir(GROUP_SERVER_DIR))
                return false;
            if (!is_dir(GROUP_SHORT_DIR))
                mkdir(GROUP_SHORT_DIR);
            $file = GROUP_SHORT_DIR.$reg_conf["acn"];
            copy(SHORT_CODE_LIST, $file);
            return true;
        }

        /* 通知 Group Server 更新 short_code 資料 */
        $grp_code = get_grp_code("update_short", "$short_code:$new_short_code");
        $url = "http://".$set_conf["group_server"].NUCLOUD_DOMAIN.GROUP_API."?grp_code=$grp_code";
        $content = trim(implode("", @file($url)));
        if ($content != "ok")
            return false;
        return true;
    }

    /* 將 Group Server 的 short_code 資料刪除 */
    function group_del_short($short_code)
    {
        Global $group_mode, $reg_conf, $set_conf;

        /* 若不是 Group 內的 CS 就不處理 */
        if ($group_mode == GROUP_NONE)
            return false;

        /* 若是 Group Server 就直接更新 short_code 檔案 */
        if ($group_mode == GROUP_SERVER)
        {
            if (!is_dir(GROUP_SERVER_DIR))
                return false;
            if (!is_dir(GROUP_SHORT_DIR))
                mkdir(GROUP_SHORT_DIR);
            $file = GROUP_SHORT_DIR.$reg_conf["acn"];
            copy(SHORT_CODE_LIST, $file);
            return true;
        }

        /* 通知 Group Server 刪除 short_code 資料 */
        $grp_code = get_grp_code("del_short", "$short_code");
        $url = "http://".$set_conf["group_server"].NUCLOUD_DOMAIN.GROUP_API."?grp_code=$grp_code";
        $content = trim(implode("", @file($url)));
        if ($content != "ok")
            return false;
        return true;
    }

    /* 從 Group Server 新增 short_code 資料 */
    function group_ser_add_short($short_code, $url, $cs_acn)
    {
        Global $group_mode;

        /* 若不是 Group Server 就不處理 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 檢查 cs_acn 是否在 group_cs.list 內,若不在就不處理 */
        if (in_group_cs_list($cs_acn) == false)
            return false;

        /* 檢查相關參數與目錄 */
        if ((empty($short_code)) || (empty($url)))
            return false;
        if (!is_dir(GROUP_SERVER_DIR))
            return false;
        if (!is_dir(GROUP_SHORT_DIR))
            mkdir(GROUP_SHORT_DIR);

        /* 將 short_code 資料新增到資料檔 */
        $file = GROUP_SHORT_DIR.$cs_acn;
        $fp = fopen($file, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$short_code\t$url\n");
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

    /* 從 Group Server 更新 short_code 資料 */
    function group_ser_update_short($short_code, $new_short_code, $cs_acn)
    {
        Global $group_mode;

        /* 若不是 Group Server 就不處理 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 檢查 cs_acn 是否在 group_cs.list 內,若不在就不處理 */
        if (in_group_cs_list($cs_acn) == false)
            return false;

        /* 檢查相關參數與目錄 */
        if ((empty($short_code)) || (empty($new_short_code)))
            return false;
        if (!is_dir(GROUP_SERVER_DIR))
            return false;
        if (!is_dir(GROUP_SHORT_DIR))
            return false;

        /* 將 short_code 資料更新到資料檔 */
        $file = GROUP_SHORT_DIR.$cs_acn;
        $fp = fopen($file, "r+");
        flock($fp, LOCK_SH);
        while($buf = @fgets($fp, MAX_BUFFER_LEN))
        {
            list($code, $url) = explode("\t", trim($buf), 2);
            if ($code == $short_code)
            {
                /* 更新 short_code 資料 */
                $l = strlen($buf);
                fseek($fp, -$l, SEEK_CUR);
                fputs($fp, "$new_short_code\t$url\n");
                flock($fp, LOCK_UN);
                fclose($fp);
                return true;
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return false;
    }

    /* 從 Group Server 刪除 short_code 資料 */
    function group_ser_del_short($short_code, $cs_acn)
    {
        Global $group_mode;

        /* 若不是 Group Server 就不處理 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 檢查 cs_acn 是否在 group_cs.list 內,若不在就不處理 */
        if (in_group_cs_list($cs_acn) == false)
            return false;

        /* 檢查相關參數與目錄 */
        if (empty($short_code))
            return false;
        if (!is_dir(GROUP_SERVER_DIR))
            return false;
        if (!is_dir(GROUP_SHORT_DIR))
            return false;

        /* 將 short_code 資料從資料檔刪除 */
        $file = GROUP_SHORT_DIR.$cs_acn;
        $fp = fopen($file, "r+");
        flock($fp, LOCK_SH);
        while($buf = @fgets($fp, MAX_BUFFER_LEN))
        {
            list($code, $url) = explode("\t", trim($buf), 2);
            if ($code == $short_code)
            {
                /* 更新 short_code.list 資料,將該筆資料內容清空 */
                $l = strlen($buf);
                fseek($fp, -$l, SEEK_CUR);
                $content = "";
                for ($i = 0; $i < $l-1; $i++)
                    $content .= " ";
                fputs($fp, "$content\n");
                flock($fp, LOCK_UN);
                fclose($fp);
                return true;
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return false;
    }

    /* 用 short_code 取得 Group 中的真實 url */
    function group_get_url_by_short($short_code)
    {
        Global $group_mode;

        /* 若不是 Group Server 或 Short 目錄不存在或沒傳入 short_code 就不處理 */
        if (($group_mode != GROUP_SERVER) || (!is_dir(GROUP_SHORT_DIR)) || (empty($short_code)))
            return false;

        /* 取出 Short 目錄內的 short code 資料 */
        $page_url = false;
        $handle = opendir(GROUP_SHORT_DIR);
        while ($cs_acn = readdir($handle))
        {
            /* 只處理檔案 */
            $s_file = GROUP_SHORT_DIR.$cs_acn;
            if (!is_file($s_file))
                continue;

            /* 取得 short code 資料 */
            $s_list = @file($s_file);
            $cnt = count($s_list);
            for ($i = 0; $i < $cnt; $i++)
            {
                list($s_code, $url) = explode("\t", trim($s_list[$i]));
                if ($s_code == $short_code)
                {
                    $page_url = "http://$cs_acn".NUCLOUD_DOMAIN.$url;
                    break;
                }
            }
            if ($page_url != false)
                break;
        }
        closedir($handle);

        return $page_url;
    }

    /* Group Server 取得所有的 member_list 資料 */
    function group_get_member_list($key=NULL)
    {
        Global $group_mode, $reg_conf;

        /* 若不是 Group Server 就不處理 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 檢查 Site Index 是否存在 */
        $index_file = GROUP_SITE_INDEX."/current";
        if (!file_exists($index_file))
            return false;

        /* 取出所有 site 資料 */
        $rBegin = str_replace("\n", "\\n", REC_START.REC_BEGIN_PATTERN);
        if (!empty($key))
            $select_arg = "-select \"@name:$key;@site_acn:$key;@owner_info:$key\"";
        else
            $select_arg = "";
        $cmd = SEARCH_BIN_DIR."dbman -recbeg \"$rBegin\" -flag \"@_f:Normal\" $select_arg -sort $index_file";
        $fp = popen($cmd, "r");
        $rec_start = false;
        $member_list = NULL;
        $n = 0;
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            $buf = trim($buf)."\n";
            if ($buf == "\n")
                continue;

            /* 判斷是否有新的 Record (Record 的啟始為 REC_START,第二行一開始必須是 REC_FIELD_START) */
            if ($buf == REC_START)
            {
                $rec_start = true;
                continue;
            }
            if (($rec_start == true) && ($buf[0] == REC_FIELD_START))
            {
                $owner = NULL;
                $owner_info = NULL;
                $cs = NULL;
            }
            $rec_start = false;

            /* 檢查是否為 owner | owner_info | cs 欄位,若是就取出內容,其餘的都跳過 */
            if (strstr($buf, "@owner:")  === $buf)
                $owner = strtolower(trim(substr($buf, 7)));
            else if (strstr($buf, "@owner_info:")  === $buf)
                $owner_info = trim(substr($buf, 12));
            else if (strstr($buf, "@cs:") === $buf)
                $cs = strtolower(trim(substr($buf, 4)));
            else
                continue;

            /* 整理 member list 資料 */
            if (($owner !== NULL) && ($owner_info !== NULL) && ($cs !== NULL))
            {
                $exist = false;
                for ($j = 0; $j < $n; $j++)
                {
                    if ($member_list[$j]["acn"] == $owner)
                    {
                        $exist = true;
                        break;
                    }
                }
                if ($exist == true)
                    continue;
                list($ssn, $acn, $mail, $name) = explode(":", $owner_info);
                if ((empty($mail)) || (empty($name)))
                    continue;
                $member_list[$n]["acn"] = $owner;
                $member_list[$n]["ssn"] = $ssn;
                $member_list[$n]["mail"] = $mail;
                $member_list[$n]["name"] = $name;
                $member_list[$n]["cs"] = $cs;
                $n++;
            }
        }
        pclose($fp);
        return $member_list;
    }

    /* Group Server 取得網站相關資料 */
    function group_get_site_info($key=NULL)
    {
        Global $group_mode, $reg_conf;

        /* 若不是 Group Server 就不處理 */
        if ($group_mode != GROUP_SERVER)
            return false;

        /* 檢查 Site Index 是否存在 */
        $index_file = GROUP_SITE_INDEX."/current";
        if (!file_exists($index_file))
            return false;

        /* 取出所有 site 資料 */
        $rBegin = str_replace("\n", "\\n", REC_START.REC_BEGIN_PATTERN);
        if (!empty($key))
            $select_arg = "-select \"@name:$key;@site_acn:$key\"";
        else
            $select_arg = "";
        $cmd = SEARCH_BIN_DIR."dbman -recbeg \"$rBegin\" -flag \"@_f:Normal\" $select_arg -sort $index_file";
        $fp = popen($cmd, "r");
        $rec_start = false;
        $site_info = NULL;
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            $buf = trim($buf)."\n";
            if (($buf == "\n") || ($buf == REC_BEGIN_PATTERN) || (substr($buf, 0, 2) == "@_"))
                continue;

            /* 判斷是否有新的 Record (Record 的啟始為 REC_START,第二行一開始必須是 REC_FIELD_START) */
            if ($buf == REC_START)
            {
                $rec_start = true;
                continue;
            }
            if (($rec_start == true) && ($buf[0] == REC_FIELD_START))
            {
                if (empty($site_info))
                    $n = 0;
                else
                    $n++;
            }
            $rec_start = false;

            /* 取得內容 */
            list($key, $value) = explode(":", trim(substr($buf, 1)), 2);
            $site_info[$n][$key] = $value;
        }
        pclose($fp);
        return $site_info;
    }

    /* Group Client 檢查是否已登入 (需 redirect 到 Group Server 檢查 login cookie) */
    function group_chk_login()
    {
        Global $group_mode, $set_conf;

        /* 2014/10/21 修改,若不是 Group 內的 CS 就不處理,若是 Group Server 要檢查是否使用 domain,若是就要處理 */
        if (($group_mode == GROUP_NONE) || (($group_mode == GROUP_SERVER) && ($_SERVER["SERVER_NAME"] == $_SERVER["SERVER_ADDR"])))
            return;

        /* 若 group_login 這項 cookie 不存在或已超過 MAX_ACTIVE_TIME 就要檢查 login cookie */
        /* 2015/10/28 修改,增加 last_chk 的 cookie,若時間已超過 MAX_CHECK_TIME (目前設為 10 秒),就設定要檢查 login cookie */
        /* (增加 last_chk 主要是縮短檢查時間,但為避免 user 正在瀏覽時不斷檢查,所以每次會更新,也避免瀏覽時 user 從其他地方 logout,所以保留 group_login) */
        $chk_login = false;
        $now_time = time();
        if ((empty($_COOKIE["group_login"])) || (empty($_COOKIE["last_chk"])) || ($now_time - $_COOKIE["group_login"] > MAX_ACTIVE_TIME) || ($now_time - $_COOKIE["last_chk"] > MAX_CHECK_TIME))
            $chk_login = true;

        /* 每次都必須更新 last_chk 的 cookie */
        setcookie("last_chk", $now_time, 0, "/", COOKIE_DOMAIN);

        /* 若 chk_login 為 true 代表要檢查 login cookie,就 redirect 到 Group Server 檢查 login cookie */
        if ($chk_login === true)
        {
            /* 2014/10/23 修改,多傳送 $_SERVER["SERVER_NAME"] 資料,可直接用 Domain 或 IP 進行 redirect (也可解決 Group Server 的 domain 問題) */
            $grp_code = get_grp_code("chk_login", $_SERVER["SERVER_NAME"]);
            $url = "http://".$set_conf["group_server"].NUCLOUD_DOMAIN.GROUP_API."?grp_code=$grp_code&content=".rawurlencode($_SERVER["REQUEST_URI"]);
            header("Location: $url");
            exit;
        }
    }

    /* 2015/3/27 新增,取得 Group Server 的資料 */
    function group_server_info()
    {
        Global $group_mode, $set_conf, $reg_conf;

        if ($group_mode == GROUP_CLIENT)
        {
            $ip_port = get_acn_ip_port($set_conf["group_server"]);
            if ($ip_port == false)
                $ser_url = "http://".$set_conf["group_server"].NUCLOUD_DOMAIN;
            else
            {
                list($ip, $port) = explode(":", $ip_port);
                $ser_url = "http://$ip:$port";
            }
            $grp_code = get_grp_code("get_group_server_info");
            $url = $ser_url.GROUP_API."?grp_code=$grp_code";
            $info = json_decode(trim(implode("", @file($url))), true);
        }
        else
        {
            $info["ssn"] = $reg_conf["ssn"];
            $info["acn"] = $reg_conf["acn"];
            $info["sun"] = $reg_conf["sun"];
            $info["alias"] = $reg_conf["alias1"];
        }
        return $info;
    }

    /* 2015/9/25 新增,Group Server 將檔案傳送到 Group Client */
    function group_s2c_file($file_path)
    {
        Global $group_mode, $is_manager, $admin_manager, $reg_conf;

        /* 僅 Group Server 且是系統管理者或後端管理者才可執行 */
        if (($group_mode != GROUP_SERVER) || (($is_manager != true) && ($admin_manager != true)))
            return false;
        /* file_path 必須在 /data/ 目錄內且不可有 /.. */
        if ((empty($file_path)) || (substr($file_path, 0, 6) != "/data/") || (strstr($file_path, "/..") != false))
            return false;

        /* 取得 file_path 的內容並使用 base64 編碼,若 file_path 不存在,代表要通知 Group Client 刪除此檔案,將 content 設為空的 */
        $content = NULL;
        if (file_exists($file_path))
            $content = base64_encode(implode("", @file($file_path)));

        /* call Client 的 group_api 來傳送 file_path */
        return group_api_server2all("s2c_file", $content, $file_path);
    }

    /* 由 Group Server call 所有 Client 的 group_api 執行工作 */
    function group_api_server2all($mode, $content=NULL, $arg=NULL)
    {
        Global $group_mode, $reg_conf;

        /* 若不是 Group Server 或沒傳入 mode 就不處理 */
        if (($group_mode != GROUP_SERVER) || (empty($mode)))
            return false;

        /* 取得 group_cs.list 資料 (只取 online 的資料) */
        $cs_list = get_group_cs_list("online");
        if (($cs_list == false) || (empty($cs_list)))
            return false;
        $cnt = count($cs_list);
        $grp_code = get_grp_code($mode, $arg);
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 若 Client CS 是 Group Server 本身,就跳過不處理 */
            if (($reg_conf["acn"] == $cs_list[$i]) || ($reg_conf["alias1"] == $cs_list[$i]))
                continue;

            /* 取得 Client 的 IP & Port */
            $ip_port = get_acn_ip_port($cs_list[$i]);
            if ($ip_port == false)
                continue;
            list($ip, $port) = explode(":", $ip_port);

            /* 執行 Group Client 的 group_api */
            if (($fp = fsockopen($ip, $port, $errno, $errstr)) != false)
            {
                /* 設定傳送到 Server 的參數 */
                $post_arg = "grp_code=$grp_code";
                if (!empty($content))
                    $post_arg .= "&content=".rawurlencode($content);

                $head = "POST ".GROUP_API." HTTP/1.0\r\n";
                $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
                $head .= "Content-Length: ". strlen($post_arg) . "\r\n\r\n";
                $head .= "$post_arg\r\n";

                fputs($fp, $head);
                fclose($fp);
            }
        }
        return true;
    }

    /*** 備份功能 ***/
    /* 取得備份 Server 資料 */
    function get_backup_server()
    {
        Global $set_conf, $backup_server;

        /* 若沒設定 backup_server 就回傳 false */
        if ((!isset($set_conf["backup_server"])) || (empty($set_conf["backup_server"])))
            return false;

        /* 取得並回傳 backup_server 的 IP & Port (web & ssh) 資料,若沒設定 Port 就使用預設的 Port (web:80 , ssh:22) */
        if (strstr($set_conf["backup_server"], ":") != false)
            list($backup_server["ip"], $backup_server["web"], $backup_server["ssh"]) = explode(":", $set_conf["backup_server"], 3);
        else
            $backup_server["ip"] = $set_conf["backup_server"];
        if (empty($backup_server["web"]))
            $backup_server["web"] = DEF_WEB_PORT;
        if (empty($backup_server["ssh"]))
            $backup_server["ssh"] = DEF_SSH_PORT;
        if ($set_conf["backup_type"] == SIMPLE_BACKUP)
            $backup_server["type"] = SIMPLE_BACKUP;
        else
            $backup_server["type"] = SOURCE_BACKUP;
        return $backup_server;
    }

    /* 取得備份 Server 的模式 */
    function get_backup_mode()
    {
        Global $set_conf;

        /* 取得備份 Server 資料 (若沒設定 backup_server 就回傳 false) */
        $backup_server = get_backup_server();
        if ($backup_server == false)
            return BACKUP_NONE;

        /* 檢查是否有 backup.mode 檔 */
        if (file_exists(BACKUP_MODE))
        {
            /* 檢查 backup.mode 檔案時間,若沒有超過 BACKUP_MODE_TIME 就直接取出 backup.mode 內容回傳 */
            $f_time = filemtime(BACKUP_MODE);
            $n_time = time();
            $process_time = $n_time - $f_time;
            if ($process_time < BACKUP_MODE_TIME)
                return trim(implode("", @file(BACKUP_MODE)));
        }

        /* 分別檢查 local ip 與 global ip 是否為 backup_server ,若是就設為 BACKUP_TARGET,否則就設為 BACKUP_SOURCE */
        /* 2014/11/12 修改,取得所有 local ip (有可能有多張網卡),逐一檢查是否為 BACKUP_TARGET */
        $backup_mode = BACKUP_SOURCE;
        $l_ip = get_local_ip(true);
        $cnt = count($l_ip);
        for ($i = 0; $i < $cnt; $i++)
        {
            if ($backup_server["ip"] == $l_ip[$i])
                $backup_mode = BACKUP_TARGET;
        }
        /* local ip 沒有檢查符合 (backup_mode 沒有設定成 BACKUP_TARGET),必須再檢查 global ip */
        if ($backup_mode !== BACKUP_TARGET)
        {
            $g_ip = get_server_ip();
            if ($backup_server["ip"] == $g_ip)
                $backup_mode = BACKUP_TARGET;
        }

        /* 紀錄到 backup.mode 並回傳 backup_mode */
        $fp = fopen(BACKUP_MODE, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $backup_mode);
        flock($fp, LOCK_UN);
        fclose($fp);
        return $backup_mode;
    }

    /* 2014/9/8 新增,處理 modify.list (一次一筆) */
    function process_modify_list()
    {
        /* 如果 modify.list 不存在,代表已處理完 */
        if (!file_exists(MODIFY_LIST))
            return false;

        /* 先檢查是否上次的 process 還在執行中,若是就先離開 */
        $lockfp = fopen(MODIFY_FLAG, "ab");
        if (($lockfp === false) || (!flock($lockfp, LOCK_EX | LOCK_NB)))
            return false;

        /* 讀取 modify.list 的第一筆資料 */
        $fp = fopen(MODIFY_LIST, "r");
        flock($fp, LOCK_SH);
        list($mode, $file_path, $type, $option) = explode("\t", trim(fgets($fp, MAX_BUFFER_LEN)), 4);
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 執行 modify list 的相關處理 */
        exec_modify_list($mode, $file_path, $type, $option);

        /* 把處理完的資料(第一筆資料)從 modify.list 中移除 */
        $content = "";
        $fp = fopen(MODIFY_LIST, "r");
        flock($fp, LOCK_SH);
        /* 先讀出第一筆不處理,其他的再整理到 content 中 */
        fgets($fp, MAX_BUFFER_LEN);
        while($buf = fgets($fp, MAX_BUFFER_LEN))
            $content .= $buf;
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 如果 content 是空的,代表已處理完 modify.list 就直接刪除 modify.list,否則存回 modify.list */
        if (empty($content))
            unlink(MODIFY_LIST);
        else
        {
            $fp = fopen(MODIFY_LIST, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        /* 刪除 flag file */
        flock($lockfp, LOCK_UN);
        fclose($lockfp);
        unlink(MODIFY_FLAG);

        return true;
    }

    /* 執行 modify list 的相關處理 */
    function exec_modify_list($mode, $file_path, $type=NULL, $option=NULL)
    {
        /* 檢查 mode */
        if (($mode !== "new") && ($mode !== "update") && ($mode !== "del") && ($mode !== "rename"))
            return false;

        /* 若是新增或更新一般檔案,就檢查是否需要進行文件檔轉 pdf 檔處理 */
        if ((($mode == "new") || ($mode == "update")) && ($type == "file"))
        {
            doc2pdf($file_path);
            /* 2014/12/30 新增,檢查若是音樂檔就自動轉 mp3 檔 */
            /* 2015/5/3 修改,轉 mp3 改成立即轉,此處先移除不進行轉檔 */
            //audio2mp3($file_path);
        }

        /* 檢查備份模式 */
        $backup_mode = get_backup_mode();
        if ($backup_mode == BACKUP_SOURCE)
            backup_modify($mode, $file_path, $type, $option);

        /* 處理完成後記錄到 modify.list.log 中 */
        write_modify_list($mode, $file_path, $type, $option, true);
    }

    /* 備份 modify 的資料 */
    function backup_modify($mode, $file_path, $type, $option)
    {
        Global $backup_server;

        /* 若 backup_server 是空的,必須先取得 backup_mode (取得 backup_mode 會同時取得 backup_server 資料),backup_mode 必須是 BACKUP_SOURCE 才需要處理 */
        if (empty($backup_server))
        {
            $backup_mode = get_backup_mode();
            if ($backup_mode != BACKUP_SOURCE)
                return false;
        }

        $tmp_file = NULL;
        switch($mode)
        {
            case "new":
            case "update":
                if (!file_exists($file_path))
                    return false;

                /* 若是目錄,通常都是新建立的目錄,目錄佔用空間都不大,直接將目錄壓縮後傳給 Backup Client 進行解壓縮處理 */
                if ($type == "dir")
                {
                    $tmp_file = get_tgz_file($file_path);
                    $post = array("mode"=>"upload_tgz", "file"=>"@".$tmp_file);
                    exec_backup($post);
                    break;
                }

                $f_size = real_filesize($file_path);
                if ($f_size < MAX_BACKUP_UPLOAD_SIZE)
                    $post = array("mode"=>"upload_file", "path"=>$file_path, "mtime"=>filemtime($file_path), "file"=>"@".$file_path);
                else
                    $post = array("mode"=>"download_file", "path"=>$file_path, "mtime"=>filemtime($file_path));
                exec_backup($post);

                /* 若是一般檔案,要另外上傳檔案的 record 檔 */
                /* 2015/11/25 修改,record 檔存在才需要上傳 */
                if ($type == "file")
                {
                    $rec_file = get_file_rec_path($file_path);
                    if (!empty($rec_file))
                    {
                        $post = array("mode"=>"upload_file", "path"=>$rec_file, "mtime"=>filemtime($rec_file), "file"=>"@".$rec_file);
                        exec_backup($post);
                    }
                }
                    
                break;

            case "del":
                /* 依據 type 設定不同參數 */
                if ($type == "file")
                    $post = array("mode"=>"del_obj_file", "path"=>$file_path);
                else if ($type == "dir")
                    $post = array("mode"=>"del_obj_dir", "path"=>$file_path);
                else
                    $post = array("mode"=>"del", "path"=>$file_path);
                exec_backup($post);
                break;

            case "rename":
                /* 找出 record file */
                $rec_file = get_file_rec_path($file_path);
                if ($rec_file == false)
                    return false;

                /* 設定傳送到 Server 的參數 */
                $post = array("mode"=>"upload_file", "path"=>$rec_file, "mtime"=>filemtime($rec_file), "file"=>"@".$rec_file);
                exec_backup($post);
                break;

            default:
                return false;
        }

        if ((!empty($tmp_file)) && (file_exists($tmp_file)))
            unlink($tmp_file);
        return true;
    }

    /* 將資料傳送到 Backup Target */
    function exec_backup($post)
    {
        Global $backup_server;

        /* 設定程式即使 timeout 或被 user 中斷,還是會執行到完成 */
        ignore_user_abort(true);
        set_time_limit(0);

        /* 若 backup_server 是空的,必須先取得 backup_mode (取得 backup_mode 會同時取得 backup_server 資料),backup_mode 必須是 BACKUP_SOURCE 才需要處理 */
        if (empty($backup_server))
        {
            $backup_mode = get_backup_mode();
            if ($backup_mode != BACKUP_SOURCE)
                return false;
        }

        /* 將資料傳送到 Backup Target 進行處理 */
        if ($backup_server["web"] !== DEF_WEB_PORT)
            $url = "http://".$backup_server["ip"].":".$backup_server["web"].BACKUP_API;
        else
            $url = "http://".$backup_server["ip"].BACKUP_API;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        curl_close($ch);
        return true;
    }

    /* 將目錄或檔案進行壓縮 (建立出 .tgz 檔) */
    function get_tgz_file($path, $tgz_file=NULL)
    {
        /* 若沒傳入 tgz_file 參數,就自動建立一個暫存檔*/
        if (empty($tgz_file))
            $tgz_file = tempnam(NUWEB_TMP_DIR, "tgz_");

        /* 整理出要壓縮的檔案 list (必須都是 /data/ 開頭的檔案,若不是就不處理)  */
        $f_list = "";
        if (is_array($path))
        {
            $cnt = count($path);
            for ($i = 0; $i < $cnt; $i++)
            {
                $file = trim($path[$i]);
                if ((file_exists($file)) && (substr($file, 0, 6) == "/data/"))
                    $f_list .= substr($file, 6)." ";
            }
        }
        else
        {
            if ((file_exists($path)) && (substr($path, 0, 6) == "/data/"))
                $f_list = substr($path, 6);
        }
        if (empty($f_list))
            return false;

        $cmd = "cd /data ; /bin/tar cfz $tgz_file $f_list";
        $fp = popen($cmd, "r");
        pclose($fp);
        return $tgz_file;
    }

    /* 將壓縮檔進行解壓縮 (因僅備份 /data/ 內的檔案,所以直接解壓縮到 /data/ 內) */
    function untar_tgz_file($tgz_file)
    {
        if ((empty($tgz_file)) || (!file_exists($tgz_file)))
            return false;

        $cmd = "cd /data ; /bin/tar xzfp $tgz_file";
        $fp = popen($cmd, "r");
        pclose($fp);
        return true;
    }

    /*** 跨 Server copy 功能 ***/
    /* server_copy source 端 */
    function sercp($src_url, $target_url, $target_acn, $sync=false)
    {
        Global $login_user, $reg_conf;

        /* 設定程式即使 timeout 或被 user 中斷,還是會執行到完成 */
        ignore_user_abort(true);
        set_time_limit(0);

        if (substr($src_url, -1) == "/")
            $src_url = substr($src_url, 0, -1);
        if (substr($target_url, -1) == "/")
            $target_url = substr($target_url, 0, -1);

        if ((empty($login_user)) || ($login_user === false))
            return DENY_COOKIE;
        $nu_code = $_COOKIE["nu_code"];

        /* 檢查 src_url 與 target_url 必須在子網站內 */
        $src_path = WEB_ROOT_PATH.$src_url;
        $target_path = WEB_ROOT_PATH.$target_url;
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($src_path, 0, $l) !== $site_path) || (substr($target_path, 0, $l) !== $site_path) || (!file_exists($src_path)))
            return false;

        /* 檢查是否有 src_path 的瀏覽權限 (需先 require 子網站的 init.php)*/
        /* 2015/2/11 刪除,因修改新版權限,public_lib.php 必須 include Site_Prog/init.php 所以不必再 include 一次 */
        //require_once($site_path."init.php");
        $s_path = substr($src_path, $l);
        $b_right = chk_browse_right($site_path, $s_path);
        if ($b_right !== PASS)
            return $b_right;

        /* call sercp_api 向 target 檢查檔案或目錄是否已存在以及是否有上傳權限 */
        $name = get_file_name($site_path, $s_path);
        $src_name = substr($src_url, strrpos($src_url, "/") + 1);
        $sercp_code = get_sercp_code("chk_upload", $target_acn, "$name:$src_name");
        //$name = get_file_name($site_path, substr($target_path, $l));
        $url = "http://$target_acn".NUCLOUD_DOMAIN.SERCP_API."?code=$sercp_code&content=$nu_code&target_url=".rawurlencode($target_url);
        /* 2015/4/15 修改,若傳入 sync=true 就在 url 中設定 sync 參數為 Y */
        if ($sync == true)
            $url .= "&sync=".YES;
        $content = trim(implode("", @file($url)));
        if ($content != PASS)
            return $content;

        /* 取得要 copy 的 list 與 sercp_code,再 call server copy API */
        //$cp_list = get_sercp_list($src_path, $target_path);
        //$sercp_code = get_sercp_code("sercp_list", $target_acn);

        /* 將資料傳送到 Target 進行處理 */
        /* 取得 server 的 IP & Port */
        $ip_port = get_acn_ip_port($target_acn);
        if ($ip_port == false)
            return false;
        list($ip, $port) = explode(":", $ip_port);

        /* 將 copy list 上傳到 Target Server 處理 */
        /* 2015/6/16 修改,通知 target 來抓取檔案 */
        $sercp_code = get_sercp_code("fetch_all_file", $target_acn);
        if (($fp = fsockopen($ip, $port, $errno, $errstr)) != false)
        {
            /* 設定傳送到 Server 的參數 */
            $arg = "code=$sercp_code&src_url=".rawurlencode($src_url)."&target_url=".rawurlencode($target_url);
            /* 2015/4/21 新增,若 sync 為 true 就增加 sync=Y 參數 */
            if ($sync == true)
                $arg .= "&sync=".YES;

            $head = "POST ".SERCP_API." HTTP/1.0\r\n";
            $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: ". strlen($arg) . "\r\n\r\n";
            $head .= "$arg\r\n";

            /* 接收回傳的 header */
            fputs($fp, $head);
            $headers = "";
            while ($str = trim(fgets($fp, MAX_BUFFER_LEN)))
                $headers .= "$str\n";

            /* 接收回傳的內容 */
            $body = "";
            while (!feof($fp))
                $body .= fgets($fp, MAX_BUFFER_LEN);
            $content = trim($body);
            fclose($fp);
        }

        if ($content != "ok")
            return $content;
        return true;
    }

    /* 取得 sercp_code */
    function get_sercp_code($mode, $acn, $arg=NULL)
    {
        Global $reg_conf;

        $sercp_code = auth_encode("$mode,$acn,".$reg_conf["acn"].",$arg");
        return $sercp_code;
    }

    /* 取得要 server copy 的 list */
    function get_sercp_list($src_path, $target_path)
    {
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($src_path, 0, $l) !== $site_path) || (substr($target_path, 0, $l) !== $site_path))
            return false;

        if (substr($src_path, -1) == "/")
            $src_path = substr($src_path, 0, -1);
        if (substr($target_path, -1) != "/")
            $target_path .= "/";
        $src_dir = substr($src_path, 0, strrpos($src_path, "/")+1);

        $sercp_list = array();
        if (is_dir($src_path))
            add_sercp_list($src_path, $src_dir, $target_path, $sercp_list);
        else
            $sercp_list = get_sercp_file_list($src_path, $src_dir, $target_path);

        return $sercp_list;
    }

    /* 新增資料到 server copy list 中 */
    function add_sercp_list($src_path, $src_dir, $target_path, &$list, &$n=0)
    {
        if (substr($src_path, -1) == "/")
            $src_path = substr($src_path, 0, -1);
        if (!file_exists($src_path))
            return false;
        $list[$n]["src_path"] = $src_path;
        $list[$n]["mtime"] = filemtime($src_path);
        $list[$n]["target_path"] = str_replace($src_dir, $target_path, $src_path);
        if (is_dir($src_path))
            $list[$n]["type"] = "D";
        else
            $list[$n]["type"] = "F";
        $n++;

        /* 若是目錄就取出目錄內所有檔案與子目錄 */
        if (is_dir($src_path))
        {
            $handle = opendir($src_path);
            while ($sub_name = readdir($handle))
            {
                /* . 與 .. 與 .nuweb_def (server copy 時不傳送權限設定資料) 直接跳過不處理 */
                if (($sub_name == ".") || ($sub_name == "..") || ($sub_name == NUWEB_DEF))
                    continue;
                /* 將所有檔案或目錄都加入 server copy list 中 */
                add_sercp_list("$src_path/$sub_name", $src_dir, $target_path, $list, $n);
            }
        }
        return true;
    }

    /* 取得 server copy 檔案的所有相關檔案 list */
    function get_sercp_file_list($src_path, $src_dir, $target_path)
    {
        Global $fe_type;

        /* 若 src_path 不在子網站目錄內或不是檔案或沒有 record file 代表檔案有問題,就回傳 false */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        $src_file_name = substr($src_path, strlen($src_dir));
        $fe = strtolower(substr($src_file_name, strrpos($src_file_name, ".")));
        $rec_file = $src_dir.NUWEB_REC_PATH.$src_file_name.".rec";
        if ((substr($src_path, 0, $l) !== $site_path) || (!is_file($src_path)) || (!file_exists($rec_file)))
            return false;

        /* 先將檔案本身設定到 list 的第一筆資料 */
        $list = array();
        $n = 0;
        add_sercp_list($src_path, $src_dir, $target_path, $list, $n);

        /* 將 record 檔設定到 list 中 */
        add_sercp_list($rec_file, $src_dir, $target_path, $list, $n);

        /* 若有轉出的 pdf 檔就設定到 list 中 */
        add_sercp_list($src_dir.NUWEB_PDF_PATH.$src_file_name.".pdf", $src_dir, $target_path, $list, $n);

        /* 若有縮圖檔就設定到 list 中 */
        add_sercp_list($src_path.TN_FE_NAME, $src_dir, $target_path, $list, $n);
        add_sercp_list($src_path.MED_TN_FE_NAME, $src_dir, $target_path, $list, $n);
        add_sercp_list($src_path.MED2_TN_FE_NAME, $src_dir, $target_path, $list, $n);
        add_sercp_list($src_path.BIG_TN_FE_NAME, $src_dir, $target_path, $list, $n);
        add_sercp_list($src_path.SRC_TN_FE_NAME, $src_dir, $target_path, $list, $n);

        /* 檢查 .nuweb_media 目錄內若有轉出的影片檔也設定到 list 中 */
        $media_dir = $src_dir.NUWEB_MEDIA_PATH;
        $m_list = get_prefix_file_list($media_dir, $src_file_name);
        if ($m_list != false)
        {
            $cnt = count($m_list);
            for ($i = 0; $i < $cnt; $i++)
                add_sercp_list($media_dir.$m_list[$i], $src_dir, $target_path, $list, $n);
        }

        /* 若是 html 檔案,就要找出 .files 目錄並加入 list 中 */
        if ($fe_type[$fe] == HTML_TYPE)
        {
            $src_files_path = get_files_dir($site_path, substr($src_path, $l));
            if ($src_files_path !== false)
                add_sercp_list($site_path.$src_files_path, $src_dir, $target_path, $list, $n);
        }
        return $list;
    }

    /* 新增資料到 src_list 中 */
    function add_sercp_src_list($src_path, $src_dir, &$list, &$n=0, $l=NULL)
    {
        if ($l === NULL)
            $l = strlen($src_dir);
        if (substr($src_path, -1) == "/")
            $src_path = substr($src_path, 0, -1);
        if (!file_exists($src_path))
            return false;
        $list[$n++] = substr($src_path, $l);
        return true;
    }

    /* 取得 server copy 檔案的所有相關檔案 list */
    function get_sercp_src_list($src_path)
    {
        Global $fe_type;

        /* 若 src_path 不在子網站目錄內或不是檔案或沒有 record file 代表檔案有問題,就回傳 false */
        if (substr($src_path, -1) == "/")
            $src_path = substr($src_path, 0, -1);
        $src_dir = substr($src_path, 0, strrpos($src_path, "/")+1);
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if ((substr($src_path, 0, $l) !== $site_path) || (!file_exists($src_path)))
            return false;
        if (is_dir($src_path))
        {
            $list[0] = substr($src_path, strlen($src_dir));
            return $list;
        }
        $src_file_name = substr($src_path, strlen($src_dir));
        $fe = strtolower(substr($src_file_name, strrpos($src_file_name, ".")));
        $rec_file = $src_dir.NUWEB_REC_PATH.$src_file_name.".rec";
        if ((substr($src_path, 0, $l) !== $site_path) || (!is_file($src_path)) || (!file_exists($rec_file)))
            return false;

        /* 先將檔案本身設定到 list 的第一筆資料 */
        $list = array();
        $n = 0;
        $l = strlen($src_dir);
        add_sercp_src_list($src_path, $src_dir, $list, $n, $l);

        /* 將 record 檔設定到 list 中 */
        add_sercp_src_list($rec_file, $src_dir, $list, $n, $l);

        /* 若有轉出的 pdf 檔就設定到 list 中 */
        add_sercp_src_list($src_dir.NUWEB_PDF_PATH.$src_file_name.".pdf", $src_dir, $list, $n, $l);

        /* 若有縮圖檔就設定到 list 中 */
        add_sercp_src_list($src_path.TN_FE_NAME, $src_dir, $list, $n, $l);
        add_sercp_src_list($src_path.MED_TN_FE_NAME, $src_dir, $list, $n, $l);
        add_sercp_src_list($src_path.MED2_TN_FE_NAME, $src_dir, $list, $n, $l);
        add_sercp_src_list($src_path.BIG_TN_FE_NAME, $src_dir, $list, $n, $l);
        add_sercp_src_list($src_path.SRC_TN_FE_NAME, $src_dir, $list, $n, $l);

        /* 檢查 .nuweb_media 目錄內若有轉出的影片檔也設定到 list 中 */
        $media_dir = $src_dir.NUWEB_MEDIA_PATH;
        $m_list = get_prefix_file_list($media_dir, $src_file_name);
        if ($m_list != false)
        {
            $cnt = count($m_list);
            for ($i = 0; $i < $cnt; $i++)
                add_sercp_src_list($media_dir.$m_list[$i], $src_dir, $list, $n, $l);
        }

        /* 若是 html 檔案,就要找出 .files 目錄並加入 list 中 */
        if ($fe_type[$fe] == HTML_TYPE)
        {
            $src_files_path = get_files_dir($site_path, substr($src_path, $l));
            if ($src_files_path !== false)
                add_sercp_src_list($site_path.$src_files_path, $src_dir, $list, $n, $l);
        }
        return $list;
    }

    /* 取得目錄內所有開頭為 prefix 的檔案 */
    function get_prefix_file_list($dir_path, $prefix)
    {
        if ((!is_dir($dir_path)) || (empty($prefix)))
            return false;

        $l = strlen($prefix);
        $n = 0;
        $handle = opendir($dir_path);
        while ($sub_name = readdir($handle))
        {
            /* . 與 .. 直接跳過不處理 */
            if (($sub_name == ".") || ($sub_name == ".."))
                continue;

            if (substr($sub_name, 0, $l) == $prefix)
                $list[$n++] = $sub_name;
        }
        if ($n == 0)
            return false;
        return $list;
    }

    /* 2015/6/4 新增,將要 copy 的檔案全部都下載回來 */
    function sercp_all_file($s_list, $t_list, $s_acn)
    {
        /* 取得 sercp_code,再 call server copy API */
        $sercp_code = get_sercp_code("sercp_all_file", $s_acn);

        /* 取得 server 的 IP & Port */
        $ip_port = get_acn_ip_port($s_acn);
        if ($ip_port == false)
            return false;
        list($ip, $port) = explode(":", $ip_port);

        /* 將 copy list 上傳到 Source Server 進行下載 */
        if (($fp = fsockopen($ip, $port, $errno, $errstr)) != false)
        {
            /* 設定傳送到 Server 的參數 */
            $arg = "code=$sercp_code&content=".json_encode($s_list);
            $head = "POST ".SERCP_API." HTTP/1.0\r\n";
            $head .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: ". strlen($arg) . "\r\n\r\n";
            $head .= "$arg\r\n";

            /* 接收回傳的 header */
            fputs($fp, $head);
            $headers = "";
            while ($str = trim(fgets($fp, MAX_BUFFER_LEN)))
                $headers .= "$str\n";

            /* 接收回傳的內容 */
            $size = NULL;
            $file_fp = NULL;
            $n = 0;
            while (!feof($fp))
            {
                if ($size === NULL)
                {
                    $size = fgets($fp, MAX_BUFFER_LEN);
                    if ($size > 0)
                        $file_fp = fopen($t_list[$n], "w");
                }
                else
                {
                    /* size 為 0 時要讀取檔尾的 \n (直接丟棄不用),並將檔案存檔,將 size 設為 NULL 準備讀取下ㄧ個檔案 */
                    if ($size == 0)
                    {
                        if ($file_fp !== NULL)
                            fclose($file_fp);
                        $content = fread($fp, 1);
                        $size = NULL;
                        $file_fp = NULL;
                        $n++;
                        continue;
                    }
                    if ($size > MAX_BUFFER_LEN)
                        $r_size = MAX_BUFFER_LEN;
                    else
                        $r_size = $size;
                    $content = fread($fp, $r_size);
                    if ($file_fp !== NULL)
                        fputs($file_fp, $content);
                    $size = $size - $r_size;
                }
            }
            fclose($fp);
        }
        return true;
    }

    /* 2015/6/16 新增,將要 copy 的檔案全部都下載回來 */
    function fetch_all_file($src_url, $target_dir, $s_acn)
    {
        /* 取得 sercp_code,再 call server copy API */
        $sercp_code = get_sercp_code("get_all_file", $s_acn, $src_url);

        /* 取得 server 的 IP & Port */
        $ip_port = get_acn_ip_port($s_acn);
        if ($ip_port == false)
            return false;
        list($ip, $port) = explode(":", $ip_port);
        $cmd = "cd $target_dir ; wget -O - \"http://$ip:$port".SERCP_API."?code=$sercp_code\" | tar xf -";
        $fp = popen($cmd, "r");
        pclose($fp);
        return true;
    }

    /*** Site Index 功能 ***/
    /* 將網站的設定資料轉成 record */
    function get_site_conf_rec($site_acn, $quota_list=NULL)
    {
        Global $reg_conf;

        if (empty($site_acn))
            return false;
        $site_acn = strtolower($site_acn);
        $cs = $reg_conf["acn"];
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";

        /* 先取得 quota list 資料 */
        if ($quota_list == NULL)
            $quota_list = get_quota_list(true);

        /* 取得網站設定檔內容,並轉成 record */
        $rec_content = "";
        $conf = read_conf($site_path.$site_acn."/".NUWEB_CONF);
        if (($conf == false) || (empty($conf["site_acn"])))
            return false;
        $rec_content = REC_START.REC_BEGIN_PATTERN;
        $rec_content .= "@url:http://$cs".NUCLOUD_DOMAIN."/Site/$site_acn/\n";
        foreach($conf as $key => $value)
        {
            /* 跳過 quota 設定 (因 .nuweb_conf 內的 quota 設定已不使用) */
            if ($key == "quota")
                continue;
            $rec_content .= "@$key:".trim($value)."\n";
        }
        $rec_content .= "@cs:$cs\n";
        /* 若是個人網站就設定 quota 欄位資料 */
        /* 2015/7/17 修改,因個人網站不一定是帳號當 site_acn 所以應該要先檢查 owner 的 quota 設定,找不到再找 site_acn 的 quota 設定,都找不到就設為 0 (代表無限制) */
        //if (($conf["type"] == 0) && (isset($quota_list[$site_acn])))
        //    $rec_content .= "@quota:".$quota_list[$site_acn]."\n";
        $owner = $conf["owner"];
        $quota_field = NULL;
        if ($conf["type"] == 0)
        {
            if (isset($quota_list[$owner]))
                $member_quota = $quota_list[$owner];
            else if (isset($quota_list[$site_acn]))
                $member_quota = $quota_list[$site_acn];
            else
                $member_quota = 0;
            $rec_content .= "@quota:$member_quota\n";
        }
        /* 取得 .nuweb_quota 資料,加入 record 中 */
        $conf = read_conf($site_path.$site_acn."/".NUWEB_QUOTA);
        if ($conf != false)
        { 
            foreach($conf as $key => $value)
                $rec_content .= "@$key:".trim($value)."\n";
        }
        return $rec_content;
    }

    /* 將 Server 內所有網站的設定資料轉成 record */
    function get_server_site_conf_rec()
    {
        /* 先取得 quota list 資料 */
        $quota_list = get_quota_list(true);

        /* 取出 site.list 資料 */
        $slist = get_sub_site_list();
        $cnt = count($slist);
        $rec_content = "";
        /* 將所有網站設定資料轉成 record */
        for ($i = 0; $i < $cnt; $i++)
        {
            $site_acn = strtolower($slist[$i]["acn"]);
            $result = get_site_conf_rec($site_acn, $quota_list);
            if ((empty($result)) || ($result == false))
                continue;
            $rec_content .= $result;
        }
        return $rec_content;
    }

    /* 更新 Site_Index 資料 */
    function update_site_index($site_acn)
    {
        Global $reg_conf;

        /* 取得 url 與 record 內容 */
        $site_acn = strtolower($site_acn);
        $url = "http://".$reg_conf["acn"].NUCLOUD_DOMAIN."/Site/$site_acn/";
        $rec_content = get_site_conf_rec($site_acn);
        /* 更新 Site Index 資料 */
        /* 2015/8/4 修改,若 SITE_INDEX_DIR 不存在就先建立 */
        if (!is_dir(SITE_INDEX_DIR))
            rdb_gen(SITE_INDEX_DIR);
        rupdate_content(SITE_INDEX_DIR, $rec_content, "url", $url);
    }

    /* 刪除 Site_Index 資料 */
    function del_site_index($site_acn)
    {
        Global $reg_conf;

        /* 取得 url 並清除 Site Index 資料 */
        $site_acn = strtolower($site_acn);
        $url = "http://".$reg_conf["acn"].NUCLOUD_DOMAIN."/Site/$site_acn/";
        rdelete(SITE_INDEX_DIR, "url", $url);
    }

    /*** 動態訊息訂閱功能 ***/
    /* 取得 user 的訂閱名單 */
    function get_subscribe_list()
    {
        Global $login_user, $set_conf;

        if ((empty($login_user)) || ($login_user === false))
            return false;

        $subscribe = NULL;
        $n = 0;
        if ((isset($set_conf["ad_list"])) && (!empty($set_conf["ad_list"])))
        {
            $list = explode(",", strtolower($set_conf["ad_list"]));
            $cnt = count($list);
            for ($i = 0; $i < $cnt; $i++)
            {
                if (empty($list[$i]))
                    continue;
                $subscribe[$n++] = $list[$i];
            }
        }
        $my_list = get_my_list();
        if ((isset($my_list["subscribe"])) && (!empty($my_list["subscribe"])))
        {
            $list = explode(",", strtolower($my_list["subscribe"]));
            $cnt = count($list);
            for ($i = 0; $i < $cnt; $i++)
            {
                if (empty($list[$i]))
                    continue;
                $subscribe[$n++] = $list[$i];
            }
        }
        return $subscribe;
    }

    /*** 自動邀請功能 ***/
    /* 取得自動邀請名單 */
    function get_auto_invite_list()
    {
        Global $group_mode;

        if ($group_mode == GROUP_CLIENT)
            return false;

        if (!file_exists(AUTO_INVITE_LIST))
            return false;
        $list = @file(AUTO_INVITE_LIST);
        $cnt = count($list);
        $a_list = array();
        for ($i = 0; $i < $cnt; $i++)
        {
            $list[$i] = trim($list[$i]);
            if (empty($list[$i]))
                continue;
            list($mail, $auth_time, $acn, $path, $mode) = explode("\t", $list[$i]);
            if ((empty($mail)) || (empty($auth_time)))
                continue;
            array_push($a_list, array("mail"=>$mail, "auth_time"=>$auth_time, "acn"=>$acn, "path"=>$path, "mode"=>$mode));
        }
        return $a_list;
    }

    /* 新增自動邀請名單 */
    function add_auto_invite($mail, $path=NULL, $mode=NULL, $acn=NULL, $sun=NULL)
    {
        Global $login_user, $set_conf, $group_mode;

        if ($set_conf["auto_invite"] !== YES)
            return false;
        //if ((empty($mail)) || (empty($login_user)) || ($login_user === false))
        if (empty($mail))
            return false;
        if (empty($acn))
            $acn = $login_user["acn"];
        if (empty($sun))
            $sun = $login_user["sun"];
        if ((empty($acn)) || (empty($sun)))
            return false;

        /* 若是 Group Client 就通知 Group Server 處理 */
        if ($group_mode == GROUP_CLIENT)
        {
            $grp_code = get_grp_code("add_auto_invite", "$mail:$path:$mode:$acn:$sun");
            $url = "http://".$set_conf["group_server"].NUCLOUD_DOMAIN.GROUP_API."?grp_code=$grp_code";
            $content = trim(implode("", @file($url)))."\n";
            if (strstr($content, "Error") != false)
                return false;
            return true;
        }

        $user = get_user_data($mail);
        if ((!empty($user["acn"])) && (is_site_owner($user["acn"]) == true))
            return false;
        if (in_auto_invite_list($mail) == true)
            return true;
        mt_srand(time());
        $auth_time = mt_rand();
        $content = "$mail\t$auth_time\t$acn\t$path\t$mode\n";
        $fp = fopen(AUTO_INVITE_LIST, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $content);
        flock($fp, LOCK_UN);
        fclose($fp);
        /* 2015/8/14 移除,因調整自動更新流程,已不需要寄送邀請信,因此不必建立 auto_invite.list.send 檔 */
        //$content = "$mail\t$auth_time\t$sun\n";        
        //$fp = fopen(AUTO_INVITE_LIST.".send", "a");
        //flock($fp, LOCK_EX);
        //fputs($fp, $content);
        //flock($fp, LOCK_UN);
        //fclose($fp);
        return true;
    }

    /* 刪除自動邀請名單 */
    function del_auto_invite($mail)
    {
        Global $is_manager, $admin_manager, $group_mode;

        /* 僅系統管理者或後端管理者可刪除自動邀請名單 */
        //if (($is_manager !== true) && ($admin_manager !== true))
        //    return false;

        if ($group_mode == GROUP_CLIENT)
            return false;

        if (empty($mail))
            return false;
        $list = get_auto_invite_list();
        if ($list == false)
            return false;
        $cnt = count($list);
        $new_list = NULL;
        $l_item = NULL;
        for ($i = 0; $i < $cnt; $i++)
        {
            $l_item = $list[$i]["mail"]."\t".$list[$i]["auth_time"]."\t".$list[$i]["acn"]."\t".$list[$i]["path"]."\t".$list[$i]["mode"]."\n";
            if ($mail == $list[$i]["mail"])
            {
                $ret_content = $l_item;
                continue;
            }
            $new_list .= $l_item;
        }
        if (empty($new_list))
        {
            unlink(AUTO_INVITE_LIST);
            return $l_item;
        }
        $fp = fopen(AUTO_INVITE_LIST, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $new_list);
        flock($fp, LOCK_UN);
        fclose($fp);
        return $ret_content;
    }

    /* 自動邀請名單 user 完成註冊 */
    function reg_auto_invite($mail, $acn)
    {
        Global $wns_ser, $wns_port, $group_mode;

        if ($group_mode == GROUP_CLIENT)
            return false;

        if ((empty($mail)) || (empty($acn)))
            return false;
        $l_item = del_auto_invite($mail);
        if ($l_item == false)
            return false;
        $fp = fopen(AUTO_INVITE_LIST.".ok", "a");
        flock($fp, LOCK_EX);
        fputs($fp, $acn."\t".$l_item);
        flock($fp, LOCK_UN);
        fclose($fp);

        /* 通知 wns 將邀請者與被邀請者互設為好友 */
        list($i_mail, $auth_time, $i_acn, $path, $mode) = explode("\t", $l_item);
        $url = "http://$wns_ser:$wns_port/UserProfile/friend_api.php?mode=mutual&code=".$_COOKIE["nu_code"]."&acn=$i_acn";
        $content = trim(implode("", @file($url)));
        return true;
    }

    /* 檢查 mail 是否在自動邀請名單中 */
    function in_auto_invite_list($mail, $auth_time=NULL)
    {
        Global $group_mode;

        if ($group_mode == GROUP_CLIENT)
            return false;

        $list = get_auto_invite_list();
        if ($list == false)
            return false;
        $cnt = count($list);
        for ($i = 0; $i < $cnt; $i++)
        {
            if (($mail == $list[$i]["mail"]) && (empty($auth_time)))
                return true;
            if (($mail == $list[$i]["mail"]) && ($auth_time == $list[$i]["auth_time"]))
                return true;
        }
        return false;
    }

    /* 檢查 mail 是否已在自動邀請註冊名單中 */
    function in_auto_invite_ok($mail)
    {
        Global $group_mode;

        if ($group_mode == GROUP_CLIENT)
            return false;

        $ok_list = AUTO_INVITE_LIST.".ok";
        if (!file_exists($ok_list))
            return false;
        $list = @file($ok_list);
        $cnt = count($list);
        for ($i = 0; $i < $cnt; $i++)
        {
            $list[$i] = trim($list[$i]);
            if (empty($list[$i]))
                continue;
            list($r_acn, $r_mail, $auth_time, $acn, $path, $mode) = explode("\t", $list[$i]);
            if ($mail = $r_mail)
                return true;
        }
        return false;
    }

    /* 檢查是否為網站的 owner (檢查是否已在本台 Server 建立網站空間) */
    function is_site_owner($acn=NULL)
    {
        Global $login_user, $group_mode;

        if (empty($acn))
            $acn = $login_user["acn"];
        if (empty($acn))
            return false;

        /* 檢查 login user 是否為網站 owner 或 manager */
        $site_owner = false;
        /* 若是 Group 的 CS 改抓 group_site.list 資料 */
        if ($group_mode !== GROUP_NONE)
        {
            $s_list = group_get_site_list(false, true);
            $s_cnt = count($s_list);
            $cnt = 0;
            for ($i = 0; $i < $s_cnt ; $i++)
            {
                /* 檢查是否為網站的 owner */
                if ($acn == $s_list[$i]["owner"])
                    return true;
            }
        }
        else
        {
            /* 取出 site_manager.list 的資料 */
            $s_manager_list = @file(SITE_MANAGER_LIST);
            $s_cnt = count($s_manager_list);
            $cnt = 0;
            for ($i = 0; $i < $s_cnt ; $i++)
            {
                list($site_acn, $manager_list) = explode("\t", strtolower(trim($s_manager_list[$i])));
                $m_acn = explode(",", $manager_list);
                $m_cnt = count($m_acn);
                /* 檢查是否為網站的 owner (第一筆資料是 owner) */
                if ($acn == $m_acn[0])
                    return true;
            }
        }
        return false;
    }

    /*** 廣告功能 ***/
    /* 取得廣告列表 */
    function get_ad_list($online=false)
    {
        /* 先取得目前時間與 ad.list 的資料 */
        $now_time = (int)date("YmdHi");
        $out = array();
        $buf = @file(AD_LIST);
        $cnt = count($buf);
        for ($i = 0; $i < $cnt; $i++)
        {
            $buf[$i] = trim($buf[$i]);
            /* 過濾掉空白行 */
            if (empty($buf))
                continue;
            /* 過濾掉錯誤資料必須有 title 與 url 資料 */
            $item = explode("\t", $buf[$i]);
            if ((empty($item[0])) || (empty($item[1])))
                continue;
            $title = $item[0];
            $url = $item[1];
            /* start 預設為 0,end 預設為 999999999999,若有設定 start 與 end 就改用設定值,若沒有就用預設值 */
            $start = 0;
            $end = 999999999999;
            /* 2015/7/29 修改,先不調整 url */
            //if (substr($url, 0, 1) == "/")
            //    $url = get_server_url().substr($url, 1);
            //else if ((substr($url, 0, 7) !== "http://") && (substr($url, 0, 8) !== "https://"))
            //    $url = "http://".$url;
            if ((isset($item[2])) && (!empty($item[2])))
                $start = (int)$item[2];
            if ((isset($item[3])) && (!empty($item[3])))
                $end = (int)$item[3];
            /* 檢查本廣告目前是否有效 */
            if (($start <= $now_time) && ($end >= $now_time))
                $online_status = true;
            else
                $online_status = false;
            /* 2015/10/1 新增,增加取得 t_color (文字顏色) 與 b_color (背景顏色) 欄位資料 */
            $t_color = $item[4];
            $b_color = $item[5];
            /* 2015/10/1 修改,增加 t_color (文字顏色) 與 b_color (背景顏色) 欄位 */
            /* 若傳入的 online 參數為 true,代表僅輸出目前上線的廣告,否則全部廣告都輸出 */
            if (($online == true) && ($online_status == true))
                array_push($out, array("title"=>$title, "url"=>$url, "start"=>$start, "end"=>$end, "t_color"=>$t_color, "b_color"=>$b_color));
            if ($online !== true)
                array_push($out, array("title"=>$title, "url"=>$url, "start"=>$start, "end"=>$end, "t_color"=>$t_color, "b_color"=>$b_color, "online"=>$online_status));
        }
        return $out;
    }

    /* 新增廣告 */
    function add_ad($ad_data, $s2c=true)
    {
        /* 檢查必須傳入 title 與 url 資料 */
        if ((!isset($ad_data["title"])) || (empty($ad_data["title"])) || (!isset($ad_data["url"])) || (empty($ad_data["url"])))
            return false;

        /* 2015/10/1 修改,增加 t_color (文字顏色) 與 b_color (背景顏色) 欄位 */
        //$ad_content = $ad_data["title"]."\t".$ad_data["url"]."\t".$ad_data["start"]."\t".$ad_data["end"]."\n";
        $ad_content = $ad_data["title"]."\t".$ad_data["url"]."\t".$ad_data["start"]."\t".$ad_data["end"]."\t".$ad_data["t_color"]."\t".$ad_data["b_color"]."\n";
        $fp = fopen(AD_LIST, "a");
        flock($fp, LOCK_EX);
        fputs($fp, $ad_content);
        flock($fp, LOCK_UN);
        fclose($fp);
        /* 2015/9/25 新增,若 s2c 為 true,就將 AD_LIST 資料更新到 Group Client */
        if ($s2c == true)
            group_s2c_file(AD_LIST);
    }

    /* 修改廣告 */
    function update_ad($id, $ad_data)
    {
        /* 檢查 id 必須大於 0 也必須傳入 title 與 url 資料 */
        if (($id <= 0) || (!isset($ad_data["title"])) || (empty($ad_data["title"])) || (!isset($ad_data["url"])) || (empty($ad_data["url"])))
            return false;

        /* 先刪除原廣告,再新增 (先不將 AD_LIST 更新到 Group Client,等處理完再執行) */
        del_ad($id, false);
        add_ad($ad_data, false);

        /* 2015/9/25 新增,將 AD_LIST 資料更新到 Group Client */
        group_s2c_file(AD_LIST);
    }

    /* 刪除廣告 */
    function del_ad($id, $s2c=true)
    {
        if (($id <= 0) || (!file_exists(AD_LIST)))
            return false;

        /* 先取出 ad.list 資料,再清除 id 的資料,最後再將其他資料存檔,若已無資料就刪除 ad.list */
        $ad = @file(AD_LIST);
        $n = $id - 1;
        if (isset($ad[$n]))
            unset($ad[$n]);
        $ad_content = implode("", $ad);
        if (empty($ad_content))
            unlink(AD_LIST);
        else
        {
            $fp = fopen(AD_LIST, "w");
            flock($fp, LOCK_EX);
            fputs($fp, $ad_content);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
        /* 2015/9/25 新增,若 s2c 為 true,就將 AD_LIST 資料更新到 Group Client */
        if ($s2c == true)
            group_s2c_file(AD_LIST);
        return true;
    }

    /* 記錄廣告 log */
    function write_ad_log($title, $url)
    {
        if ((empty($title)) || (empty($url)))
            return false;

        /* 建立 AD_LOG_DIR */
        if (!is_dir(AD_LOG_DIR))
            mkdir(AD_LOG_DIR);

        /* 建立儲存(當年度) ad log 的目錄 */
        $ad_log_dir = AD_LOG_DIR.date("Y")."/";
        if (!is_dir($ad_log_dir))
            mkdir($ad_log_dir);

        $ad_file = $ad_log_dir.date("Ymd");
        $log_msg = "$title\t$url";
        write_server_log($ad_file, $log_msg);
    }

    /*** login code ***/
    /* 定義 login code 有效時間 (10 分鐘) */
    define("LOGIN_CODE_TIME", 10*60);

    /* 取得 login code */
    function get_login_code($ssn, $acn, $mail, $sun)
    {
        $time = time() + LOGIN_CODE_TIME;
        return auth_encode("$time:$ssn:$acn:$mail:$sun");
    }

    /* 檢查 login code 並進行登入 */
    function chk_login_code($code)
    {
        Global $login_user, $uacn, $uid;

        $content = auth_decode($code);
        if ($content == false)
            return false;

        $now_time = time();
        list($time, $ssn, $acn, $mail, $sun) = explode(":", $content);
        if ((empty($time)) || (empty($ssn)) || (empty($acn)) || (empty($mail)) || (empty($sun)) || (is_numeric($ssn) != true) || ($time < $now_time))
            return false;

        /* 設定 login cookie 並跳轉到 user 網站 */
        set_login_cookie($ssn, $acn, $mail, $sun);
        if (($sca = get_sca($ssn, $acn, $sun)) !== false)
              sca_session($ssn, $sca);
        $login_user = get_login_user();
        $uid = $login_user["ssn"];
        $uacn = $login_user["acn"];
        return true;
    }

    /*** Local 端備份功能 ***/
    /* Local 端 sync */
    function local_sync($src_path, $target_path)
    {
        /* src_path 必須存在,target_path 必須是目錄,src_path 與 target_path 都不可以有 /.. */
        //if (substr($src_path, -1) == "/")
        //    $src_path = substr($src_path, 0, -1);
        //if (substr($target_path, -1) == "/")
        //    $target_path = substr($target_path, 0, -1);
        if ((substr($src_path, -1) !== "/") && (is_dir($src_path)))
            $src_path .= "/";
        if (substr($target_path, -1) !== "/")
            $target_path .= "/";
        /* 2015/12/11 修改,取消 target_path 目錄檢查 (因為第一次備份時目錄可能不存在) */
        //if ((!file_exists($src_path)) || (strstr($src_path, "/..") !== false) || (!is_dir($target_path)) || (strstr($target_path, "/..") !== false))
        if ((!file_exists($src_path)) || (strstr($src_path, "/..") !== false) || (strstr($target_path, "/..") !== false))
            return false;

        /* 記錄 sync.log */
        /* 2015/12/11 取消,因 rsync 過程中,可能造成 log 被覆蓋問題,所以改成完成後再記錄到 log,但要先取得執行時間 */
        $exec_time = (int)date("YmdHis");
        $log_msg = "$exec_time\t$src_path\t$target_path";
        //write_server_log(LOCAL_SYNC_LOG, $log_msg);
        /* 2015/12/11 新增,為避免 local_sync.log 被覆蓋,先將 local_sync.log 先複製到 /tmp 中 */
        if (file_exists(LOCAL_SYNC_LOG))
        {
            if (file_exists(LOCAL_SYNC_TMP_LOG))
                unlink(LOCAL_SYNC_TMP_LOG);
            copy(LOCAL_SYNC_LOG, LOCAL_SYNC_TMP_LOG);
        }

        /* 執行 rsync 並紀錄 last.log */
        /* 2015/12/11 修改,先將 last log 記錄到 tmp log 中,等 rsync 執行完畢,才將 tmp log 複製到 last log 中 (因 rsync 過程中,可能造成 last log 被覆蓋問題,所以先記錄在不會被覆蓋的 /tmp 目錄中,等完成後再複製回來) */
        $last_log = LOCAL_SYNC_LAST_LOG;
        $tmp_log = LOCAL_SYNC_LAST_TMP_LOG;
        if (file_exists($last_log))
            unlink($last_log);
        if (file_exists($tmp_log))
            unlink($tmp_log);
        /* 2015/12/11 修改,最後加上 chmod -R 755 target_path 因為發現備份或還原時檔案與目錄 mode 可能會改變造成程式執行權限有問題,所以一律 chmod 成 755 以確保程式可正常執行 */
        $cmd = "touch $tmp_log ; ln -s $tmp_log $last_log ; echo '$log_msg' > $tmp_log ; date '+%F %T\tStart' >> $tmp_log ; /usr/bin/rsync -av --delete $src_path $target_path >> $tmp_log ; date '+%F %T\tEnd' >> $tmp_log ; rm -f $last_log ; cp $tmp_log $last_log ; rm -f $tmp_log ; chmod -R 755 $target_path";
        $fp = popen($cmd, "r");
        pclose($fp);

        /* 2015/12/11 新增,先將 /tmp 中的 local_sync.log 複製回來,再進行記錄 log */
        if (file_exists(LOCAL_SYNC_TMP_LOG))
        {
            copy(LOCAL_SYNC_TMP_LOG, LOCAL_SYNC_LOG);
            unlink(LOCAL_SYNC_TMP_LOG);
        }

        /* 記錄 sync.log */
        write_server_log(LOCAL_SYNC_LOG, $log_msg);
        /* 為避免 log 檔是由 root 產生 (定時備份時),後續可能發生權限問題,需將 local_sync_last.log 與 local_sync.log 都 chown 成 web_user 與 web_group */
        $web_user = WEB_USER;
        $web_group = WEB_GROUP;
        $cmd = "chown $web_user:$web_group $last_log ".LOCAL_SYNC_LOG;
        $fp = popen($cmd, "r");
        pclose($fp);

        return true;
    }

    /* Local 端的備份 */
    function local_backup($page_path, $device_path)
    {
        /* page_path 必須在 /data 內且必須存在,device_path 必須是目錄但不可在 /data 內 */
        /* 2015/12/11 修改,取消 device_path 目錄檢查 */
        //if ((substr($page_path, 0, 5) !== "/data") || (!file_exists($page_path)) || (!is_dir($device_path)) || (substr($device_path, 0, 5) == "/data"))
        if ((substr($page_path, 0, 5) !== "/data") || (!file_exists($page_path)) || (substr($device_path, 0, 5) == "/data"))
            return false;

        //return local_rsync($page_path, $device_path);
        return set_local_sync_flag($page_path, $device_path, "backup");
    }

    /* Local 端的還原 */
    function local_recover($page_path, $device_path)
    {
        /* page_path 必須是目錄且在 /data 內,device_path 必須存在但不可在 /data 內 */
        /* 2015/12/11 修改,取消 page_path 目錄檢查 */
        //if ((!is_dir($page_path)) || (substr($page_path, 0, 5) !== "/data") || (!file_exists($device_path)) || (substr($device_path, 0, 5) == "/data"))
        if ((substr($page_path, 0, 5) !== "/data") || (!file_exists($device_path)) || (substr($device_path, 0, 5) == "/data"))
            return false;

        //return local_rsync($device_path, $page_path);
        return set_local_sync_flag($device_path, $page_path, "recover");
    }

    /* 依據 local_sync.flag 設定執行 local_sync */
    function exec_local_sync_flag()
    {
        /* 檢查若 local_sync.flag 不存在就不處理 */
        if (!file_exists(LOCAL_SYNC_FLAG))
            return false;

        /* 取得 local_sync.flag 的資料,並執行 local_sync */
        list($src_path, $target_path) = explode("\t", trim(implode("", @file(LOCAL_SYNC_FLAG))));
        /* 2015/12/11 修改,取消 target_path 目錄檢查 (因為第一次備份時目錄可能不存在) */
        //if ((!file_exists($src_path)) || (!is_dir($target_path)))
        if (!file_exists($src_path))
            return false;
        /* 2015/12/11 修改,先刪除 local_sync.flag 再執行 local_sync 以避免一直重複執行 local_sync */
        unlink(LOCAL_SYNC_FLAG);
        return local_sync($src_path, $target_path);
    }

    /* 檢查並執行 local_sync */
    function exec_local_sync($time=NULL, $day=NULL, $week=NULL)
    {
        /* 檢查若 local_sync.flag 存在,代表要立即備份或還原,就直接執行 exec_local_sync_log */
        if (file_exists(LOCAL_SYNC_FLAG))
            return exec_local_sync_flag();

        /* 若 local_sync.cron 不存在,代表沒有定時備份設定,就不必處理 */
        if (!file_exists(LOCAL_SYNC_CRON))
            return false;

        /* 檢查時間參數,若沒傳入就立即取得 */
        if ($time === NULL)
            $time = date("Hi");
        if ($day === NULL)
            $day = date("d");
        if ($week === NULL)
            $week = date("w");

        /* 讀取 local_sync.cron 的設定資料,並檢查現在是否為設定的時間,若是就設定 local_sync.flag 並執行 exec_local_sync_log */
        $cron_list = @file(LOCAL_SYNC_CRON);
        $cnt = count($cron_list);
        for ($i = 0; $i < $cnt; $i++)
        {
            list($c_mode, $c_day, $c_time, $c_src, $c_target) = explode("\t", trim($cron_list[$i]));
            /* 若時間不一樣就不必再檢查,直接跳下ㄧ筆 */
            if ($c_time !== $time)
                continue;
            $match = false;
            switch($c_mode)
            {
                case "D":
                    $match = true;
                    break;

                case "W":
                    if ($c_day === $week)
                        $match = true;
                    break;

                case "M":
                    if ($c_day === $day)
                        $match = true;
                    break;
            }

            /* 若符合設定的時間,就設定 local_sync.flag 並執行 exec_local_sync_log */
            if ($match == true)
            {
                set_local_sync_flag($c_src, $c_target, "cron");
                return exec_local_sync_flag();
            }
        }
        return false;
    }

    /* 設定 local_sync 的 flag */
    function set_local_sync_flag($src_path, $target_path, $mode="set")
    {
        /* 如果 local_sync.flag 存在且系統內有 rsync 正在執行,就認定目前正在執行 local sync,直接回傳 false 不可再重複執行 */
        if ((file_exists(LOCAL_SYNC_FLAG)) && (chk_rsync() == true))
            return false;
        /* 設定 local_sync.flag */
        $log_msg = "$src_path\t$target_path";
        $fp = fopen(LOCAL_SYNC_FLAG, "w");
        flock($fp, LOCK_EX);
        fputs($fp, $log_msg);
        flock($fp, LOCK_UN);
        fclose($fp);
        /* 記錄 local_sync.log */
        /* 2015/12/11 修改,增加記錄 set 模式,代表此筆記錄是設定 local_sync_flag */
        write_server_log(LOCAL_SYNC_LOG, "$mode\t$log_msg");
        return true;
    }

    /* 檢查是否有 rsync 正在執行 */
    function chk_rsync()
    {
        $cmd = "ps ax | grep rsync | grep -v grep";
        $fp = popen($cmd, "r");
        $content = NULL;
        while ($buf = fgets($fp, MAX_BUFFER_LEN))
            $content .= trim($buf);
        pclose($fp);
        if (!empty($content))
            return true;
        return false;
    }

    /* 取得 df 的資訊 */
    function get_df_info($path=NULL)
    {
        if ((!empty($path)) && (!is_dir($path)))
            return false;
        $cmd = "df -BG $path";
        $fp = popen($cmd, "r");
        $info = array();
        while($buf = fgets($fp, MAX_BUFFER_LEN))
        {
            $buf = trim($buf);
            if (empty($buf))
                continue;
            list($dev, $total, $use, $free, $percent, $path) = explode(" ", preg_replace('/\s+/', ' ', trim($buf)));
            if (substr($path, 0, 1) !== "/")
                continue;
            /* 將 total / use / free / percent 等資料後面的單位先拿掉 */
            $total = (int)substr($total, 0, -1);
            $use = (int)substr($use, 0, -1);
            $free = (int)substr($free, 0, -1);
            $percent = (int)substr($percent, 0, -1);
            array_push($info, array("dev"=>$dev, "total"=>$total, "use"=>$use, "free"=>$free, "percent"=>$percent, "path"=>$path));
        }
        pclose($fp);
        return $info;
    }

    /* 記錄 /data 使用量資料 */
    function write_data_space_log()
    {
        /* 2015/12/4 修改,將 data_dir 由 /data 改成 /data/ 因 /data 有可能是 link,執行 du 檢查時,若用 /data 會查不到資料,要改成 /data/ 才查得到 */
        $data_dir = "/data/";
        $now_time = (int)date("YmdHis");

        $df_info = get_df_info($data_dir);
        $df_use = $df_info[0]["use"];
        $cmd = "du -sBG $data_dir | awk '{print \$1 }' | sed '/G\$/s///'";
        $fp = popen($cmd, "r");
        $du_space = trim(fgets($fp, MAX_BUFFER_LEN));
        pclose($fp);
        $fp = fopen(DATA_SPACE_LOG, "a");
        flock($fp, LOCK_EX);
        fputs($fp, "$now_time\t$df_use\t$du_space\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /* 取得最近一筆 /data 使用量資料 */
    function get_data_space_info()
    {
        if (!file_exists(DATA_SPACE_LOG))
            return false;
        $cmd = "tail -1 ".DATA_SPACE_LOG;
        $fp = popen($cmd, "r");
        $data = fgets($fp, MAX_BUFFER_LEN);
        pclose($fp);
        list($info["time"], $info["df_use"], $info["du_use"]) = explode("\t", trim($data));
        return $info;
    }

    /* 取得可進行 local 端備份的裝置資料 */
    function can_local_backup_dev()
    {
        /* 取得系統 Device 與 /data 的 df 資料,以及 /data 使用空間的最後一次紀錄 */
        $sys_df = get_df_info();
        $data_df = get_df_info("/data");
        $data_info = get_data_space_info();

        /* 算出目前 /data 使用空間 */
        $data_now_space = $data_df[0]["use"] - $data_info["df_use"] + $data_info["du_use"];

        /* 檢查與過濾可進行備份的裝置資料 */
        $cnt = count($sys_df);
        $n = 0;
        $info = false;
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 要先過濾掉 /data 的 Device (因為不可備份到 /data 所在的 Device 內) */
            if ($sys_df[$i]["dev"] == $data_df[0]["dev"])
                continue;

            /* 2015/12/12 新增,過濾掉部分可能是系統的 Device */
            $pre_path = substr($sys_df[$i]["path"], 0, 4);
            if (($pre_path == "/dev") || ($pre_path == "/run") || ($pre_path == "/sys") || (substr($sys_df[$i]["path"], 0, 5) == "/boot"))
                continue;

            /* 用 data 使用空間與 df 資料檢查是否有足夠空間可進行備份,若沒有就跳過 */
            $bak_data_path = $sys_df[$i]["path"]."/data";
            $bak_data_space_log = $sys_df[$i]["path"].DATA_SPACE_LOG;
            /* 取得 Device 沒使用的空間,代表可用來備份的空間 */
            $bak_space = $sys_df[$i]["free"];
            /* 若 Device 內已有備份資料,就先取得之前備份時的空間,加上目前沒使用的空間,代表可用來備份的空間 */
            if (is_dir($bak_data_path))
            {
                if (file_exists($bak_data_space_log))
                {
                    $cmd = "tail -1 $bak_data_space_log";
                    $fp = popen($cmd, "r");
                    $buf = fgets($fp, MAX_BUFFER_LEN);
                    pclose($fp);
                    list($b_time, $b_df_use, $b_du_use) = explode("\t", trim($buf));
                    $bak_space += $b_du_use;
                }
            }
            /* 檢查備份空間是否足夠,若不足夠就跳過 */
            if ($bak_space <= $data_now_space)
                continue;
            $info[$n++] = $sys_df[$i];
        }
        return $info;
    }

    /* 取得可進行 local 端還原的裝置資料 */
    function can_local_recover_dev()
    {
        /* 取得系統 Device 與 /data 的資料 */
        $sys_df = get_df_info();
        $data_df = get_df_info("/data");

        /* 檢查與過濾可進行還原的裝置資料 */
        $cnt = count($sys_df);
        $n = 0;
        $info = false;
        for ($i = 0; $i < $cnt; $i++)
        {
            /* 要先過濾掉 /data 的 Device */
            if ($sys_df[$i]["dev"] == $data_df[0]["dev"])
                continue;

            /* 2015/12/12 新增,過濾掉部分可能是系統的 Device */
            $pre_path = substr($sys_df[$i]["path"], 0, 4);
            if (($pre_path == "/dev") || ($pre_path == "/run") || ($pre_path == "/sys") || (substr($sys_df[$i]["path"], 0, 5) == "/boot"))
                continue;

            /* 若 Device 內沒有備份資料,就跳過 */
            $bak_data_path = $sys_df[$i]["path"]."/data";
            if (!is_dir($bak_data_path))
                continue;
            $info[$n++] = $sys_df[$i];
        }
        return $info;
    }

    /*** 外部認證功能 ***/
    /* 取得外部認證設定檔資料 */
    function get_account_check_conf()
    {
        $conf = NULL;
        if (file_exists(ACCOUNT_CHECK_CONF))
            $conf = read_conf(ACCOUNT_CHECK_CONF);
        if (empty($conf))
            return false;
        if ((isset($conf["ldap"])) && (!empty($conf["ldap"])))
        {
            $ldap_conf = json_decode($conf["ldap"], true);
            //if ((!empty($ldap_conf["domain"])) && (!empty($ldap_conf["server"])) && (!empty($ldap_conf["port"])))
            if ((!empty($ldap_conf["server"])) && (!empty($ldap_conf["port"])) && (!empty($ldap_conf["base_dn"])) && (!empty($ldap_conf["adm_dn"])) && (!empty($ldap_conf["adm_pwd"])) && (!empty($ldap_conf["chk_field"])))
                $ac_conf["ldap"] = $ldap_conf;
        }
        if ((isset($conf["pop3"])) && (!empty($conf["pop3"])))
        {
            $pop3_conf = json_decode($conf["pop3"], true);
            if ((!empty($pop3_conf["domain"])) && (!empty($pop3_conf["server"])) && (!empty($pop3_conf["port"])))
                $ac_conf["pop3"] = $pop3_conf;
        }
        return $ac_conf;
    }

    /* 設定帳號認證相關資料 */
    function set_account_check_conf($ac_conf)
    {
        if (empty($ac_conf))
        {
            if (file_exists(ACCOUNT_CHECK_CONF))
                unlink(ACCOUNT_CHECK_CONF);
            return true;
        }
        $conf = NULL;
        if (isset($ac_conf["ldap"]))
            $conf["ldap"] = json_encode($ac_conf["ldap"]);
        if (isset($ac_conf["pop3"]))
            $conf["pop3"] = json_encode($ac_conf["pop3"]);
        if (write_conf(ACCOUNT_CHECK_CONF, $conf, true) == false)
            return false;
        /* 將帳號認證設定檔更新到 Group Client */
        group_s2c_file(ACCOUNT_CHECK_CONF);
        return true;
    }

    /* AD/LDAP 認證 */
    function ldap_check($mail, $pwd, $ac_conf=NULL)
    {
        /* 若沒傳入 ac_conf 就自動讀取,若 ac_conf 內沒有 LDAP 設定就不處理回傳 false */
        if (empty($ac_conf))
            $ac_conf = get_account_check_conf();
        if ((!isset($ac_conf["ldap"])) || (empty($ac_conf["ldap"])) || (empty($ac_conf["ldap"]["server"])) || (empty($ac_conf["ldap"]["port"])) || (empty($ac_conf["ldap"]["base_dn"])) || (empty($ac_conf["ldap"]["adm_dn"])) || (empty($ac_conf["ldap"]["adm_pwd"])) || (empty($ac_conf["ldap"]["chk_field"])))
            return false;

        /* 若有設定 domain 就檢查 mail 的 domain 是否一樣,若不一樣就不處理 */
        $mail_domain = NULL;
        if ((isset($ac_conf["ldap"]["domain"])) && (!empty($ac_conf["ldap"]["domain"])))
        {
            list($user, $mail_domain) = explode("@", $mail);
            if ($mail_domain !== $ac_conf["ldap"]["domain"])
                return false;
        }

        /* 若無法連結到 Server 就不處理 */
        $ds = ldap_connect($ac_conf["ldap"]["server"]);
        if (!$ds)
            return false;
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);

        /* 若無法 ldap_bind 管理帳號,就不處理 */
        $ldapbind = @ldap_bind($ds, $ac_conf["ldap"]["adm_dn"], $ac_conf["ldap"]["adm_pwd"]);
        if (!$ldapbind)
            return false;

        /* 查詢 mail 資料是否存在,若不存在就不處理 */
        $filter = "(".$ac_conf["ldap"]["chk_field"]."=$mail)";
        if ((!empty($mail_domain)) && (!empty($user)))
            $filter = "(|$filter(".$ac_conf["ldap"]["chk_field"]."=$user))";
        $result = ldap_search($ds, $ac_conf["ldap"]["base_dn"], $filter);
        if (!$result)
        {
            ldap_close($ds);
            return false;
        }
        $data = ldap_get_entries($ds, $result);
        if ($data["count"] == 0)
        {
            ldap_close($ds);
            return false;
        }

        $ret = false;
        for ($i = 0; $i < $data["count"]; $i++)
        {
            $ldapbind = @ldap_bind($ds, $data[$i]["dn"], $pwd);
            if ($ldapbind)
            {
                ldap_close($ds);
                return true;
            }
        }
        ldap_close($ds);
        return false;
    }

    /* POP3 認證 */
    function pop3_check($user_id, $pwd, $ac_conf=NULL)
    {
        /* 若沒傳入 ac_conf 就自動讀取,若 ac_conf 內沒有 POP3 設定就不處理回傳 false */
        if (empty($ac_conf))
            $ac_conf = get_account_check_conf();
        if ((!isset($ac_conf["pop3"])) || (empty($ac_conf["pop3"])) || (empty($ac_conf["pop3"]["server"])) || (empty($ac_conf["pop3"]["port"])) || (empty($ac_conf["pop3"]["domain"])))
            return false;

        /* 檢查使否有設定使用 SSL 連線,若有連線時 server 前面要加上 "ssl://" */
        if ($ac_conf["pop3"]["ssl"] == YES)
            $connect_server = "ssl://".$ac_conf["pop3"]["server"];
        else
            $connect_server = $ac_conf["pop3"]["server"];

        /* 無法連線到 POP3 Server 就不處理 */
        if (($fp = fsockopen($connect_server, $ac_conf["pop3"]["port"], $errno, $errstr, POP3_TIMEOUT)) == false)
            return false;
        $msg = fgets($fp, MAX_BUFFER_LEN);

        /* 傳送與檢查帳號 */
        fputs($fp, "USER $user_id\r\n");
        $msg = fgets($fp, MAX_BUFFER_LEN);
        if (strpos($msg, "+OK")  === false)
        {
            fclose($fp);
            return false;
        }

        /* 傳送與檢查密碼 */
        fputs($fp, "PASS $pwd\r\n");
        $msg = fgets($fp, MAX_BUFFER_LEN);
        if (strpos($msg, "+OK")  === false)
        {
            fclose($fp);
            return false;
        }

        /* 通過認證離開 POP3 */
        fputs($fp, "QUIT\r\n");
        fclose($fp);
        return true;
    }

    /* 外部認證檢查 */
    function account_check($mail, $pwd)
    {
        /* 取得外部認證設定資料 */
        $ac_conf = get_account_check_conf();

        /* 檢查 AD/LDAP 認證是否通過 */
        if ((isset($ac_conf["ldap"])) && (!empty($ac_conf["ldap"])) && (ldap_check($mail, $pwd, $ac_conf) == true))
            return "LDAP";

        /* 檢查 POP3 認證是否通過 (mail domain 與 pop3 domain 相同才需要進行認證) */
        list($user_id, $mail_domain) = explode("@", $mail);
        if ((isset($ac_conf["pop3"])) && (!empty($ac_conf["pop3"])) && ($mail_domain == $ac_conf["pop3"]["domain"]) && (pop3_check($user_id, $pwd, $ac_conf) == true))
            return "POP3";

        return false;
    }

    /* 記錄外部認證 log */
    function write_account_check_log($user_info, $ac_mode, $mode="login")
    {
        /* 建立 ACCOUNT_CHECK_LOG_DIR */
        if (!is_dir(ACCOUNT_CHECK_LOG_DIR))
            mkdir(ACCOUNT_CHECK_LOG_DIR);

        /* 建立儲存(當年度)外部認證 log 的目錄 */
        $log_dir = ACCOUNT_CHECK_LOG_DIR.date("Y")."/";
        if (!is_dir($log_dir))
            mkdir($log_dir);

        $log_file = $log_dir.date("Ym");
        $log_msg = "$user_info\t$ac_mode\t$mode";
        write_server_log($log_file, $log_msg);
    }
?>
