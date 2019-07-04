<?php
    define("SITE_DIR", "/data/HTTPD/htdocs/Site/");
    define("SITE_URL", "/Site/");
    define("WEB_SITE_DIR", SITE_DIR."web/");
    define("WEB_SITE_URL", SITE_URL."web/");
    define("ADMIN_URL", "/Admin/");
    define("REGISTER_URL", "/Register/");
    define("REGISTER_FLAG",  "/data/Admin/register.flag");
    /* 定義網站類型 */
    define("SITE_TYPE_PERSONAL", 0);
    define("SITE_TYPE_GROUP", 1);

    require_once("tools/public_lib.php");

    /* 檢查參數 */
    $mode = $_REQUEST["mode"];
    if ($mode !== "my_web")
        $mode = "my_driver";
    /* 2014/4/3 新增,若系統設定 use_drive = N 時就一律跳到網站目錄,不進入 Driver 目錄 */
    if (($mode == "my_driver") && ($set_conf["use_drive"] == NO))
        $mode = "my_web";
    if ((isset($_REQUEST["redirect"])) && ($_REQUEST["redirect"] !== "true"))
        $redirect = false;
    else
        $redirect = true;
    $link = $_REQUEST["link"];
    $code = $_REQUEST["code"];
    $site_acn = $_REQUEST["site_acn"];

    /* 2015/8/19 新增,若有傳入 login_code 參數,就用 login_code 檢查並設定 login cookie */
    if ((isset($_REQUEST["login_code"])) && (!empty($_REQUEST["login_code"])))
        chk_login_code($_REQUEST["login_code"]);

    /* 若有傳入 link 參數,就採用短網址功能,轉到正確網址中 */
    if ((!empty($link)) && (($url = get_url_by_short_code($link)) !== false))
    {
        /* 2015/10/22 新增,紀錄 short link log 資料 */
        write_short_link_log($link);
        header("Location: $url");
        exit;
    }

    /* 2015/1/15 新增,若是 Group 內的 CS 就要檢查 login 狀態 */
    if ($group_mode != GROUP_NONE)
        group_chk_login();

    /* 取得登入者資料 */
    $acn = NULL;
    if (($login_user = get_login_user($code)) !== false)
        $acn = $login_user["acn"];

    /* 若沒有註冊資料,代表 Server 尚未啟用,直接連到後端管理系統 */
    /* 2015/11/27 修改,若 register.flag 存在且沒有設定系統管理者時,也要跳到後端管理系統去設定系統管理者*/
    if ((empty($reg_conf)) || (empty($reg_conf["acn"])) || ((file_exists(REGISTER_FLAG)) && (empty($set_conf["manager"]))))
    {
        header("Location: ".ADMIN_URL);
        exit;
    }

    /* 2015/1/28 修改,若未登入且是 Group Client 就直接跳回 Group Server 的首頁 */
    if (($acn == NULL) && ($group_mode == GROUP_CLIENT))
    {
        header("Location: http://".$set_conf["group_server"].".nuweb.cc/");
        exit;
    }

    /* 2015/1/28 修改,若未登入且 /Register/ 存在,就跳到 /Register/ */
    if (($acn == NULL) && (is_dir(WEB_ROOT_PATH.REGISTER_URL)))
    {
        header("Location: ".REGISTER_URL);
        exit;
    }

    /* 若沒有登入或傳入 redirect 參數內容不是 "true",就連到預設 web 網站 */
    if (($acn == NULL) || ((isset($_REQUEST["redirect"])) && ($_REQUEST["redirect"] != "true")))
    {
        header("Location: ".WEB_SITE_URL);
        exit;
    }

    /* 若有傳入 site_acn 就直接設定 user 的網站 URL */
    $user_site_url = NULL;
    if (!empty($site_acn))
        $user_site_url = SITE_URL.$site_acn."/";
    else
    {
        /* 找出 user 的網站 URL,首先先檢查是否有與 acn 同名網站,若有就直接選取,若有多個就取第一個 (最早建立的網站) */
        /* 2015/10/28 修改,僅檢查 user 是 owner 的網站 */
        $user_site = get_user_site(NULL, true);
        $cnt = count($user_site);
        if ($cnt > 0)
        {
            /* 先預設第一個網站 */
            $user_site_url = SITE_URL.$user_site[0]["acn"]."/";
            for ($i = 0; $i < $cnt; $i++)
            {
                /* 尋找是否有同名網站,若有就選取同名網站 */
                if ($user_site[$i]["acn"] == $acn)
                {
                    $user_site_url = SITE_URL.$user_site[$i]["acn"]."/";
                    break;
                }
            }
        }
    }

    /* 若 user 沒建立網站就直接連到預設 web 網站,否則就依據 mode 跳到 user 網站位置 */
    if ($user_site_url == NULL)
    {
        /* 2014/7/8 新增,若有設定 group_server 就改由 group_server 來處理 redirect 功能 */
        if ($group_mode != GROUP_NONE)
        {
            /* 取得 server 的 IP & Port */
            //$ip_port = get_acn_ip_port($set_conf["group_server"], "user");
            //if ($ip_port !== false)
            //{
            //    $url = "http://$ip_port".GROUP_REDIRECT_API."?code=".$_COOKIE["nu_code"]."&acn=$acn&mode=$mode&cs_acn=".$reg_conf["acn"];
            //    header("Location: $url");
            //    exit;
            //}
            /* 2014/9/3 修改,取回 group 的 site list,檢查登入 user 帳號是否在 site list 內,若不在就直接跳到預設 web 網站 */
            $site_list = group_get_site_list();
            if ((!isset($site_list[$acn])) || (empty($site_list[$acn])))
                $url = WEB_SITE_URL;
            else
            {
                /* 整理參數 */
                $arg =  "";
                foreach($_REQUEST as $key => $value)
                {
                    if (!empty($arg))
                        $arg .= "&";
                    $arg .= "$key=$value";
                }
                if (empty($arg))
                    $url = "http://".$site_list[$acn].".nuweb.cc/";
                else
                    $url = "http://".$site_list[$acn].".nuweb.cc/index.php?$arg";
            }
        }
        else
            $url = WEB_SITE_URL;
        header("Location: $url");
    }
    else if ($mode == "my_web")
        header("Location: $user_site_url");
    else
    {
        /* 2014/3/18 新增,若是社群網站就跳到網站位置,若是個人網站才跳到 Driver 內 */
        $conf_file = WEB_ROOT_PATH.$user_site_url.NUWEB_CONF;
        $site_conf = read_conf($conf_file);
        /* 2014/9/3 新增,若網站還在等待審核,就跳到預設 web 網站 */
        if ($site_conf["status"] != SITE_STATUS_ALLOW)
            header("Location: ".WEB_SITE_URL);
        else if ($site_conf["type"] == SITE_TYPE_GROUP)
            header("Location: $user_site_url");
        else
            header("Location: ".$user_site_url."Driver/");
    }
?>
