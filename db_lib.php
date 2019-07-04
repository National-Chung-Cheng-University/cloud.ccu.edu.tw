<?php
    require_once("/data/HTTPD/htdocs/tools/public_lib.php");

    /* DB 資訊 */
    define("DB_SERVER", "140.123.4.41");
    define("DB_PORT", 3000);
    define("DB_DESCRIPTION_LEN", 1024);
    define("DEF_LIMIT", 500);
    define("DEF_PS", 20);
    define("MESSAGE_API", "/api/numessages");
    define("MESSAGE_UPDATE_API", "/api/numessages/updateInsert");
    define("MESSAGE_DEL_DIR_API", "/api/numessages/urlStartsWith");
    define("MESSAGE_GET_API", "/api/numessages/findBy");
    define("MESSAGE_GROUP_API", "/api/numessages/findGroupBy");
    define("SITE_UPDATE_API", "/api/nusites/updateInsert");
    define("SITE_GET_API", "/api/nusites");

    /* 將動態訊息上傳到訊息 DB */
    /* 2015/12/25 修改,將 message 參數前面加上& (要變更原參數內容,通知功能會使用到) */
    function message2db($page_path, &$message)
    {
        Global $reg_conf;

        /* 若是目錄,url 最後必須有 / */
        if (($message["type"] == DIR_TYPE_NAME) && (substr($message["url"], -1) != "/"))
            $message["url"] .= "/";

        /* 若 mode 是 del 就代表要刪除此資料 (需傳入 type 欄位資料,因為若是目錄時須刪除底下所有資料) */
        if ($message["mode"] == "del")
            return message2db_del($message["url"], $message["type"]);

        /* 2016/1/7 新增,將 mode 設定到 key_mode 欄位 */
        $message["key_mode"] = $message["mode"];

        /* 若 mode 是 unset 且 key_list 是空的,代表已沒有共用或分享名單,就直接刪除此筆資料 (因為僅刪除此筆資料,所以不用傳入 type 欄位) */
        //if (($message["mode"] == "unset") && (empty($message["key_list"])))
        //    return message2db_del($message["url"]);
        /* 2015/3/6 修改,mode 為 unset 要將 key 替換為 key_list,並取消更新 upload_time (有權限的 user 才能看到訊息,並且不會重複收到新訊息) */
        /* 2015/11/27 修改,增加 unset_member 項目 */
        if (($message["mode"] == "unset") || ($message["mode"] == "unset_member"))
        {
            $message["mode"] = "update";
            /* 將 key 替換成 key_list (key_list 是可瀏覽名單) */
            $message["key"] = $message["key_list"];
            /* 清除 upload_time 才不會更新 upload_time,user 才不會收到新的訊息 */
            if (isset($message["upload_time"]))
                unset($message["upload_time"]);
        }

        /* 若 mode 是 set 或 unset 時,將 mode 改為 update,並將 key_list 設定到 key 欄位,且清除 key_list */
        //if (($message["mode"] == "set") || ($message["mode"] == "unset"))
        //{
        //    $message["mode"] = "update";
        //    $message["key"] = $message["key_list"];
        //    unset($message["key_list"]);
        //}
        /* 2015/3/6 修改,mode 為 set 就將 mode 改為 update */
        if ($message["mode"] == "set")
        {
            $message["mode"] = "update";
            /* 2015/3/12 新增,mode=set 時,在 url 欄位後面加上 #set={time},改變 url 可避免造成之前的動態訊息被覆蓋 */
            //$message["url"] .= "#set=".time();
            /* 2015/3/16 修改,mode=set 時,url 後面改成加上 #set={key_value 欄位資料} */
            if ((isset($message["key_value"])) && (!empty($message["key_value"])))
                $message["url"] .= "#set=".$message["key_value"];
            else
                $message["url"] .= "#set=".time();
        }

        /* 2015/11/27 新增,mode 為 set_member 就將 mode 改為 update,url 後面加上 #set_member={key_value 欄位資料} */
        if ($message["mode"] == "set_member")
        {
            $message["mode"] = "update";
            if ((isset($message["key_value"])) && (!empty($message["key_value"])))
                $message["url"] .= "#set_member=".$message["key_value"];
            else
                $message["url"] .= "#set_member=".time();
        }

        /* 2015/3/16 新增,mode 為 apply 代表有新增的申請者,將 mode 改為 update,url 後面加上 #apply={key_value 欄位資料} */
        if ($message["mode"] == "apply")
        {
            $message["mode"] = "update";
            if ((isset($message["key_value"])) && (!empty($message["key_value"])))
                $message["url"] .= "#apply=".$message["key_value"];
            else
                $message["url"] .= "#apply=".time();
        }

        /* 2015/3/6 修改,若有 key_list 就將 key_list 清除 */
        if (isset($message["key_list"]))
            unset($message["key_list"]);
        /* 2015/3/16 修改,若有 key_value 就將 key_value 清除 */
        /* 2016/1/7 修改,將 key_value 保留 */
        //if (isset($message["key_value"]))
        //    unset($message["key_value"]);

        /* 整理訊息資料 */
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $l = strlen($site_path);
        if (substr($page_path, 0, $l) != $site_path)
            return false;
        $path = explode("/", substr($page_path, $l));
        $message["site_id"] = strtolower($path[0].".".$reg_conf["acn"]);
        /* 2014/12/23 修改,因不更新 upload_time 時,不會傳入 upload_time 欄位資料,就不需調整內容 */
        if (isset($message["upload_time"]))
            $message["upload_time"] = gmdate("Y-m-d\TH:i:s\Z", strtotime($message["upload_time"]));
        $message["time"] = gmdate("Y-m-d\TH:i:s\Z", strtotime($message["time"]));
        $message["mtime"] = gmdate("Y-m-d\TH:i:s\Z", strtotime($message["mtime"]));
        $message["allow"] = list2array($message["allow"], ",");
        $message["tag"] = list2array($message["tag"], ",");
        $message["share"] = list2array(strtolower($message["share"]), ",");
        $message["use_acn"] = list2array(strtolower($message["use_acn"]), ",");
        $message["key"] = list2array(strtolower($message["key"]), ",");
        if (isset($message["us_like"]))
            $message["us_like"] = list2array(strtolower($message["us_like"]), ",");
        /* 將 description 內容改成由 content 取出前面一段資料,並移除 content 欄位 (不需送到 message DB)  */
        $message["description"] = mb_substr($message["content"], 0, DB_DESCRIPTION_LEN, 'UTF-8');
        unset($message["content"]);

        /* 將訊息資料上傳到 DB */
        /* 2015/2/26 修改,增加 get_content 參數,因不需要接收回傳值所以直接設為 false (可加快回傳速度) */
        data2db($message, MESSAGE_UPDATE_API, false);
    }

    /* 將網站資料由網站 DB 中刪除 */
    function message2db_del($url, $type=NULL)
    {
        if (($type == DIR_TYPE_NAME) || ($type == SITE_TYPE_NAME))
        {
            if (($fp = fsockopen(DB_SERVER, DB_PORT, $errno, $errstr)) != false)
            {
                $arg = rawurlencode("{ \"url\": \"$url\" }");
                $head = "DELETE ".MESSAGE_DEL_DIR_API."?data=$arg HTTP/1.0\r\n\r\n";
                fputs($fp, $head);
                fclose($fp);
            }
            return;
        }

        $msg = get_message_info($url);
        $id = $msg["id"];
        if (($fp = fsockopen(DB_SERVER, DB_PORT, $errno, $errstr)) != false)
        {
            $head = "DELETE ".MESSAGE_API."/$id HTTP/1.0\r\n\r\n";
            fputs($fp, $head);
            fclose($fp);
        }
    }

    /* 從 DB 取回訊息資料 */
    function get_message_info($url)
    {
        $arg = "filter=".rawurlencode("{ \"where\": { \"url\": {\"inq\": [\"$url\"]}}}");
        $url = "http://".DB_SERVER.":".DB_PORT.MESSAGE_API."?$arg";
        $result = json_decode(implode("", @file($url)), true);
        return $result[0];
    }

    /* 使用 id 取回i訊息資料 */
    function get_message_by_id($id)
    {
        $arg = "filter=".rawurlencode("{ \"where\": { \"id\": {\"inq\": [\"$id\"]}}}");
        $url = "http://".DB_SERVER.":".DB_PORT.MESSAGE_API."?$arg";
        $result = json_decode(implode("", @file($url)), true);
        return $result[0];
    }

    /* 取得訊息資料 */
    function get_message($q, $p=1, $ps=DEF_LIMIT)
    {
        Global $login_user;

        if (($login_user == false) || (empty($login_user["acn"])))
            return false;

        /* 2015/2/17 新增,取得 User 的訂閱名單 */
        $subscribe = get_subscribe_list();

        if ($p <= 0)
            $p = 1;
        if ($ps <= 0)
            $ps = DEF_LIMIT;
        $key["acn"] = strtolower($login_user["acn"]);
        $key["email"] = strtolower($login_user["mail"]);
        $key["searchtext"] = "";
        $key["limit"] = $ps;
        $key["skip"] = ($p - 1) * $ps;
        $key["order"] = "upload_time desc";
        $key["subscribe"] = $subscribe;
        $key["where"]["and"][0] = "";

        /* 設定搜尋 keyword */
        if ((isset($q["kw"])) && (!empty($q["kw"])))
            $key["searchtext"] = $q["kw"];

        /* 設定過濾條件 */
        $n = 0;
        /* 時間過濾,gt 代表查詢時間之後,lt 代表查詢時間之前,兩者不可共用 (gt 優先) */
        if ((isset($q["gt"])) && (!empty($q["gt"])))
            $key["where"]["and"][$n++]["upload_time"]["gt"] = gmdate("Y-m-d\TH:i:s\Z", $q["gt"]);
        else if ((isset($q["lt"])) && (!empty($q["lt"])))
            $key["where"]["and"][$n++]["upload_time"]["lt"] = gmdate("Y-m-d\TH:i:s\Z", $q["lt"]);
        /* owner 過濾,ao 代表查詢 owner,do 代表過濾 owner,兩者不可共用 (ao 優先) */
        if ((isset($q["ao"])) && (!empty($q["ao"])))
            $key["where"]["and"][$n++]["owner"]["inq"] = list2array($q["ao"], ",");
        else if ((isset($q["do"])) && (!empty($q["do"])))
            $key["where"]["and"][$n++]["owner"]["nin"] = list2array($q["do"], ",");
        /* site 過濾,as 代表查詢 site,ds 代表過濾 site,兩者不可共用 (as 優先) */
        if ((isset($q["as"])) && (!empty($q["as"])))
            $key["where"]["and"][$n++]["site_id"]["inq"] = list2array($q["as"], ",");
        else if ((isset($q["ds"])) && (!empty($q["ds"])))
            $key["where"]["and"][$n++]["site_id"]["nin"] = list2array($q["ds"], ",");
        /* 2015/6/2 新增,url 過濾,au 代表查詢 url 底下的資料,du 代表過濾 url 底下的資料,兩者不可共用 (au 優先) */
        if ((isset($q["au"])) && (!empty($q["au"])))
            $key["where"]["and"][$n++]["url"]["like"] = $q["au"];
        else if ((isset($q["du"])) && (!empty($q["du"])))
            $key["where"]["and"][$n++]["url"]["nlike"] = $q["du"];

        $result = array();
        $content = data2db($key, MESSAGE_GET_API);
        $result = json_decode($content, true);
        $cnt = count($result["numessages"]);
        for ($i = 0; $i < $cnt; $i++)
        {
            $result["numessages"][$i]["upload_time"] = date("YmdHis", strtotime($result["numessages"][$i]["upload_time"]));
            $result["numessages"][$i]["time"] = date("YmdHis", strtotime($result["numessages"][$i]["time"]));
            $result["numessages"][$i]["mtime"] = date("YmdHis", strtotime($result["numessages"][$i]["mtime"]));
        }
        return $result["numessages"];
    }

    /* 取得訊息資料 */
    function get_message_group($q=NULL)
    {
        Global $login_user;

        if (($login_user == false) || (empty($login_user["acn"])))
            return false;

        $key["acn"] = strtolower($login_user["acn"]);
        $key["email"] = strtolower($login_user["mail"]);
        $key["where"]["and"][0] = "";

        /* 設定過濾條件 */
        $n = 0;
        /* 時間過濾,gt 代表查詢時間之後,lt 代表查詢時間之前,兩者不可共用 (gt 優先) */
        if ((isset($q["gt"])) && (!empty($q["gt"])))
            $key["where"]["and"][$n++]["upload_time"]["gt"] = gmdate("Y-m-d\TH:i:s\Z", $q["gt"]);
        else if ((isset($q["lt"])) && (!empty($q["lt"])))
            $key["where"]["and"][$n++]["upload_time"]["lt"] = gmdate("Y-m-d\TH:i:s\Z", $q["lt"]);
        /* 2015/5/25 新增,site 過濾,as 代表查詢 site,ds 代表過濾 site,兩者不可共用 (as 優先) */
        if ((isset($q["as"])) && (!empty($q["as"])))
            $key["where"]["and"][$n++]["site_id"]["inq"] = list2array($q["as"], ",");
        else if ((isset($q["ds"])) && (!empty($q["ds"])))
            $key["where"]["and"][$n++]["site_id"]["nin"] = list2array($q["ds"], ",");
        /* 2015/6/2 新增,url 過濾,au 代表查詢 url 底下的資料,du 代表過濾 url 底下的資料,兩者不可共用 (au 優先) */
        if ((isset($q["au"])) && (!empty($q["au"])))
            $key["where"]["and"][$n++]["url"]["like"] = $q["au"];
        else if ((isset($q["du"])) && (!empty($q["du"])))
            $key["where"]["and"][$n++]["url"]["nlike"] = $q["du"];

        $result = array();
        $content = data2db($key, MESSAGE_GROUP_API);
        $result = json_decode($content, true);
        return $result["groups"];
    }

    /* 將網站資料上傳到網站 DB */
    function site2db($site_acn)
    {
        Global $reg_conf;

        /* 整理網站資料 */
        $site_acn = strtolower($site_acn);
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $conf_file = $site_path.$site_acn."/".NUWEB_CONF;
        $conf = read_conf($conf_file);
        if ($conf == false)
            return false;
        $site["site_id"] = strtolower($conf["site_acn"].".".$reg_conf["acn"]);
        $site["site_acn"] = strtolower($conf["site_acn"]);
        $site["cs_acn"] = strtolower($reg_conf["acn"]);
        $site["name"] = $conf["name"];
        $site["owner"] = strtolower($conf["owner"]);
        $site["owner_info"] = $conf["owner_info"];
        $site["manager"] = list2array(strtolower($conf["manager"]), ",");
        $site["time"] = gmdate("Y-m-d\TH:i:s\Z", strtotime($conf["crt_time"]));
        $site["status"] = $conf["status"];
        $site["type"] = $conf["type"];
        $site["public"] = ($conf["public"] == YES) ? true : false;
        /* 2015/8/3 修改,將 owner 加入 use_acn 中,因發現若 use_acn 為 NULL 會無法加入 DB 中,所以一律設定 owner 避開此問題 */
        //$site["use_acn"] = list2array(strtolower($conf["member"]), ",");
        $site["use_acn"] = list2array(update_list(strtolower($conf["owner"].",".$conf["member"])), ",");
        $site["share"] = list2array(strtolower($conf["share"]), ",");
        /* 2016/1/11 新增,加入 tag 欄位 */
        $site["tag"] = list2array($conf["tag"], ",");

        /* 將網站資料上傳到 DB */
        /* 2015/2/26 修改,增加 get_content 參數,因不需要接收回傳值所以直接設為 false (可加快回傳速度) */
        data2db($site, SITE_UPDATE_API, false);
    }

    /* 將網站資料由網站 DB 中刪除 */
    function site2db_del($site_acn)
    {
        $site = get_site($site_acn);
        $id = $site["id"];
        if (($fp = fsockopen(DB_SERVER, DB_PORT, $errno, $errstr)) != false)
        {
            $head = "DELETE ".SITE_GET_API."/$id HTTP/1.0\r\n\r\n";

            fputs($fp, $head);
            fclose($fp);
        }
    }

    /* 從 DB 取回 Site 資料 */
    function get_site($site_acn)
    {
        Global $reg_conf;

        $site_id = strtolower($site_acn.".".$reg_conf["acn"]);
        $arg = "filter=".rawurlencode("{ \"where\": { \"site_id\": {\"inq\": [\"$site_id\"]}}}");
        $url = "http://".DB_SERVER.":".DB_PORT.SITE_GET_API."?$arg";
        $result = json_decode(implode("", @file($url)), true);
        return $result[0];
    }

    /* 將群組資料上傳到網站 DB (與 site2db 相同,都是放到 Site DB 中) */
    function group_alias2db($site_acn)
    {
        Global $reg_conf;

        /* 整理網站資料 */
        $site_acn = strtolower($site_acn);
        $site_path = WEB_ROOT_PATH."/".SUB_SITE_NAME."/";
        $conf_file = $site_path.$site_acn;
        $conf = read_conf($conf_file);
        if ($conf == false)
            return false;
        $site["site_id"] = strtolower($conf["site_acn"].".".$reg_conf["acn"]);
        $site["site_acn"] = strtolower($conf["site_acn"]);
        $site["cs_acn"] = strtolower($reg_conf["acn"]);
        $site["name"] = $conf["name"];
        $site["owner"] = strtolower($conf["owner"]);
        $site["manager"] = list2array(strtolower($conf["manager"]), ",");
        $site["time"] = gmdate("Y-m-d\TH:i:s\Z", strtotime($conf["crt_time"]));
        $site["type"] = $conf["type"];
        $site["public"] = ($conf["public"] == YES) ? true : false;
        /* 將 owner 加入 use_acn 中,因發現若 use_acn 為 NULL 會無法加入 DB 中,所以一律設定 owner 避開此問題 */
        $site["use_acn"] = list2array(update_list(strtolower($conf["owner"].",".$conf["member"])), ",");
        $site["share"] = list2array(strtolower($conf["share"]), ",");

        /* 將網站資料上傳到 DB */
        data2db($site, SITE_UPDATE_API, false);
    }

    /* 將群組資料由網站 DB 中刪除 (與 site2db_del 相同,都是由 Site DB 中刪除) */
    function group_alias2db_del($site_acn)
    {
        $site = get_site($site_acn);
        $id = $site["id"];
        if (($fp = fsockopen(DB_SERVER, DB_PORT, $errno, $errstr)) != false)
        {
            $head = "DELETE ".SITE_GET_API."/$id HTTP/1.0\r\n\r\n";

            fputs($fp, $head);
            fclose($fp);
        }
    }

    /* 將資料上傳到 DB */
    /* 2015/2/26 修改,增加 get_content 參數,代表是否需要取得回傳值,若不需取得回傳值可加快處理效能 */
    function data2db($data_arr, $api, $get_content=true)
    {
        if (($api != MESSAGE_UPDATE_API) && ($api != SITE_UPDATE_API) && ($api != MESSAGE_GET_API) && ($api != MESSAGE_GROUP_API))
            return false;

        if (($fp = fsockopen(DB_SERVER, DB_PORT, $errno, $errstr)) != false)
        {
            /* 設定傳送到 Server 的參數 (傳入的陣列資料需要再包一層 data 欄位) */
            $data["data"] = $data_arr;
            $arg = json_encode($data);

            $head = "POST $api HTTP/1.0\r\n";
            $head .= "Content-Type: application/json\r\n";
            $head .= "Content-Length: ". strlen($arg) . "\r\n\r\n";
            $head .= "$arg\r\n";

            /* 2015/2/26 修改,若 get_content 不是 true,代表不需要取得回傳值,送出後就可直接關閉 (可加快處理效能) */
            if ($get_content !== true)
            {
                fputs($fp, $head);
                fclose($fp);
                return;
            }

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
        return $content;
    }
?>
