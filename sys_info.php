<?php
    /* 作業系統資訊 */
    define("SYS_OS", "Ubuntu");
    //define("SYS_VER", "12.04");
    //define("SYS_BIT", "64");

    /* 系統相關程式位置 */
    define("SYS_HTPASSWD", "/usr/bin/htpasswd");
    define("SYS_TAR" , "/bin/tar");
    define("SYS_CONVERT", "/usr/bin/convert");
    define("SYS_MENCODER", "/usr/bin/mencoder");
    define("SYS_FFMPEG", "/usr/bin/ffmpeg");
    define("SYS_FLVTOOL2", "/usr/bin/flvtool2");
    define("SYS_PDFTOTEXT", "/usr/bin/pdftotext");

    /* NUWEB_CS 相關資訊 */
    define("NUWEB_CS_VER", "1.1");
    define("NUWEB_CS_TYPE", "HOME");
    define("SYS_HW", "ARM");
    $SYS_OEM = "Simple";
    global $NUWEB_VERSION_LIST;
    if ($SYS_OEM == "Complete")
        $NUWEB_VERSION_LIST = array("COMPLETE"=>true, "SIMPLE"=>false);
    else
        $NUWEB_VERSION_LIST = array("COMPLETE"=>false, "SIMPLE"=>true);
?>
