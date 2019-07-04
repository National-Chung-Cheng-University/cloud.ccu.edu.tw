<?php
    Global $fe_type;

    define("DIR_TYPE", "R");
    define("VIDEO_TYPE", "V");
    define("AUDIO_TYPE", "A");
    define("IMAGE_TYPE", "I");
    define("DOC_TYPE", "D");
    define("TEXT_TYPE", "T");
    define("HTML_TYPE", "H");
    define("LINK_TYPE", "L");
    define("OTHER_TYPE", "O");

    define("SITE_TYPE_NAME", "Site");
    define("DIR_TYPE_NAME", "Directory");
    define("VIDEO_TYPE_NAME", "Video");
    define("AUDIO_TYPE_NAME", "Audio");
    define("IMAGE_TYPE_NAME", "Image");
    define("DOC_TYPE_NAME", "Document");
    define("TEXT_TYPE_NAME", "Text");
    define("HTML_TYPE_NAME", "Html");
    define("LINK_TYPE_NAME", "Link");
    define("OTHER_TYPE_NAME", "Other");

    /* Video */
    $fe_type[".3gp"] = VIDEO_TYPE;
    $fe_type[".asf"] = VIDEO_TYPE;
    $fe_type[".asx"] = VIDEO_TYPE;
    $fe_type[".avi"] = VIDEO_TYPE;
    $fe_type[".flv"] = VIDEO_TYPE;
    $fe_type[".f4v"] = VIDEO_TYPE;
    $fe_type[".mov"] = VIDEO_TYPE;
    $fe_type[".mp4"] = VIDEO_TYPE;
    $fe_type[".mpe"] = VIDEO_TYPE;
    $fe_type[".mpeg"] = VIDEO_TYPE;
    $fe_type[".mpg"] = VIDEO_TYPE;
    $fe_type[".mts"] = VIDEO_TYPE;
    $fe_type[".m2ts"] = VIDEO_TYPE;
    $fe_type[".m2t"] = VIDEO_TYPE;
    $fe_type[".rm"] = VIDEO_TYPE;
    $fe_type[".rmvb"] = VIDEO_TYPE;
    $fe_type[".wmv"] = VIDEO_TYPE;
    $fe_type[".vob"] = VIDEO_TYPE;

    /* Audio */
    $fe_type[".acc"] = AUDIO_TYPE;
    $fe_type[".amr"] = AUDIO_TYPE;
    $fe_type[".ape"] = AUDIO_TYPE;
    $fe_type[".flac"] = AUDIO_TYPE;
    $fe_type[".m4a"] = AUDIO_TYPE;
    $fe_type[".mp3"] = AUDIO_TYPE;
    $fe_type[".ogg"] = AUDIO_TYPE;
    $fe_type[".wav"] = AUDIO_TYPE;
    $fe_type[".wma"] = AUDIO_TYPE;

    /* Image */
    $fe_type[".bmp"] = IMAGE_TYPE;
    $fe_type[".gif"] = IMAGE_TYPE;
    $fe_type[".ico"] = IMAGE_TYPE;
    $fe_type[".jpeg"] = IMAGE_TYPE;
    $fe_type[".jpg"] = IMAGE_TYPE;
    $fe_type[".pcd"] = IMAGE_TYPE;
    $fe_type[".png"] = IMAGE_TYPE;
    $fe_type[".tiff"] = IMAGE_TYPE;
    $fe_type[".ai"] = IMAGE_TYPE;
    $fe_type[".psd"] = IMAGE_TYPE;
    $fe_type[".webp"] = IMAGE_TYPE;

    /* Document */
    $fe_type[".doc"] = DOC_TYPE;
    $fe_type[".docm"] = DOC_TYPE;
    $fe_type[".docx"] = DOC_TYPE;
    $fe_type[".dot"] = DOC_TYPE;
    $fe_type[".dotm"] = DOC_TYPE;
    $fe_type[".dotx"] = DOC_TYPE;
    $fe_type[".eps"] = DOC_TYPE;
    $fe_type[".fodp"] = DOC_TYPE;
    $fe_type[".fods"] = DOC_TYPE;
    $fe_type[".fodt"] = DOC_TYPE;
    $fe_type[".odp"] = DOC_TYPE;
    $fe_type[".ods"] = DOC_TYPE;
    $fe_type[".odt"] = DOC_TYPE;
    $fe_type[".otp"] = DOC_TYPE;
    $fe_type[".ots"] = DOC_TYPE;
    $fe_type[".ott"] = DOC_TYPE;
    $fe_type[".pdf"] = DOC_TYPE;
    $fe_type[".pot"] = DOC_TYPE;
    $fe_type[".potm"] = DOC_TYPE;
    $fe_type[".potx"] = DOC_TYPE;
    $fe_type[".ppam"] = DOC_TYPE;
    $fe_type[".pps"] = DOC_TYPE;
    $fe_type[".ppsm"] = DOC_TYPE;
    $fe_type[".ppsx"] = DOC_TYPE;
    $fe_type[".ppt"] = DOC_TYPE;
    $fe_type[".pptm"] = DOC_TYPE;
    $fe_type[".pptx"] = DOC_TYPE;
    $fe_type[".ps"] = DOC_TYPE;
    $fe_type[".rtf"] = DOC_TYPE;
    $fe_type[".tex"] = DOC_TYPE;
    $fe_type[".xla"] = DOC_TYPE;
    $fe_type[".xlam"] = DOC_TYPE;
    $fe_type[".xls"] = DOC_TYPE;
    $fe_type[".xlsb"] = DOC_TYPE;
    $fe_type[".xlsm"] = DOC_TYPE;
    $fe_type[".xlsx"] = DOC_TYPE;
    $fe_type[".xlt"] = DOC_TYPE;
    $fe_type[".xltm"] = DOC_TYPE;
    $fe_type[".xltx"] = DOC_TYPE;

    /* Text */
    $fe_type[".text"] = TEXT_TYPE;
    $fe_type[".txt"] = TEXT_TYPE;

    /* Html */
    $fe_type[".htm"] = HTML_TYPE;
    $fe_type[".html"] = HTML_TYPE;

    /* Link */
    $fe_type[".url"] = LINK_TYPE;
?>
