<?php

class VersionValidator
{   
    public function runValidations() {
        return [
            'xmlParse' => $this->xmlParse(),
            'mbstrings' => $this->mbstrings(),
            'config' => $this->configExists(),
            'customdir' => $this->customdir(),
            'modulesdir' => $this->modulesdir(),
            'uploaddir' => $this->uploaddir(),
            'datadir' => $this->datadir(),
            'cachedir' => $this->cachedir(),
            'zlib' => $this->zlib(),
            'zip' => $this->zip(),
            'PCRE' => $this->PCRE(),
            // 'imap' => $this->imap(),
            'cURL' => $this->cURL(),
            'uploadFileSize' => $this->uploadFileSize(),
            'spriteSupport' => $this->spriteSupport(),
            'phpini' => $this->phpini(),
            'memlimit' => $this->memlimit(),
            'ext-gd' => $this->extgd(),
        ];
    }

    public function xmlParse() {
        return ['label' => 'xml parse', 'status' => 1, 'message' => ""];
    }

    public function mbstrings() {
        return ['label' => 'mb strings', 'status' => 1, 'message' => ""];
    }

    public function configExists() {
        return ['label' => 'config', 'status' => 1, 'message' => ""];
    }

    public function customdir() {
        return ['label' => 'custom dir', 'status' => 1, 'message' => ""];
    }

    public function modulesdir() {
        return ['label' => 'modules dir', 'status' => 1, 'message' => ""];
    }

    public function uploaddir() {
        return ['label' => 'upload dir', 'status' => 1, 'message' => ""];
    }

    public function datadir() {
        return ['label' => 'data dir', 'status' => 1, 'message' => ""];
    }

    public function cachedir() {
        return ['label' => 'cache dir', 'status' => 1, 'message' => ""];
    }

    public function zlib() {
        if (function_exists('gzclose')) {
            $status = 1;
            $message = "";
        } else {
            $status = -1;
            $message = "";
        }

        return ['label' => 'zlib', 'status' => $status, 'message' => $message];
    }

    public function zip() {
        if (class_exists("ZipArchive")) {
            $status = 1;
            $message = "";
        } else {
            $status = -1;
            $message = "";
        }

        return ['label' => 'zip', 'status' => $status, 'message' => $message];
    }

    public function PCRE() {
        if (defined('PCRE_VERSION')) {
            if (version_compare(PCRE_VERSION, '7.0') < 0) {
                $status = -1;
                $message = "";
            } else {
                $status = 1;
                $message = "";
            }
        } else {
            $status = -1;
            $message = "";
        }

        return ['label' => 'PCRE', 'status' => $status, 'message' => $message];
    }

    public function imap() {
        chdir('../legacy/');
        require_once ('include/Imap/ImapHandlerFactory.php');
        require_once ('include/Imap/ImapHandlerOAuth2.php');

        $imapFactory = new \ImapHandlerFactory();
        $imap = $imapFactory->getImapHandler();
        if ($imap->isAvailable()) {
            $status = 1;
            $message = "";
        } else {
            $status = -1;
            $message = "";
        }

        chdir('../install/');
        return ['label' => 'imap', 'status' => $status, 'message' => $message];
    }

    public function cURL() {
        if (function_exists('curl_init')) {
            $status = 1;
            $message = "";
        } else {
            $status = -1;
            $message = "";
        }

        return ['label' => 'curl', 'status' => $status, 'message' => $message];
    }

    public function uploadFileSize() {
        $upload_max_filesize = ini_get('upload_max_filesize');
        $upload_max_filesize_bytes = $this->return_bytes($upload_max_filesize);
        $SUGARCRM_MIN_UPLOAD_MAX_FILESIZE_BYTES = 6 * 1024 * 1024;
        
        if ($upload_max_filesize_bytes > $SUGARCRM_MIN_UPLOAD_MAX_FILESIZE_BYTES) {
            $status = 1;
            $message = "";
        } else {
            $status = -1;
            $message = "";
        }

        return ['label' => 'upload size', 'status' => $status, 'message' => $message];
    }

    public function spriteSupport() {
        if (function_exists('imagecreatetruecolor')) {
            $status = 1;
            $message = "";
        } else {
            $status = -1;
            $message = "";
        }

        return ['label' => 'sprite', 'status' => $status, 'message' => $message];
    }

    public function phpini() {
        $phpIniLocation = get_cfg_var("cfg_file_path");
        if(!empty($phpIniLocation)){
            $status = 1;
        } else {
            $status = -1;
        }

        return ['label' => 'php.ini', 'status' => $status, 'message' => $phpIniLocation];
    }

    public function memlimit() {
        $memory_limit = ini_get('memory_limit');
        if (empty($memory_limit)) {
            $status = 0;
            $message = "LBL_MEMORY_UNLIMITED";
        } else {
            $SUGARCRM_MIN_MEM = 40 * 1024 * 1024;

            if ($memory_limit == "") {
                $status = 1;
                $message = "";
            } else {

                preg_match('/^\s*([0-9.]+)\s*([KMGTPE])B?\s*$/i', $memory_limit, $matches);
                $num = (float) $matches[1];

                switch (strtoupper($matches[2])) {
                    case 'G':
                        $num = $num * 1024;
                    case 'M':
                        $num = $num * 1024;
                    case 'K':
                        $num = $num * 1024;
                }
                $memory_limit_int = intval($num);
                if ($memory_limit_int < $SUGARCRM_MIN_MEM) {
                    $status = -1;
                    $message = "ERR_MEMORY_TOO_SMALL";
                } else {
                    $status = 1;
                    $message = "";
                }
            }
        }

        return ['label' => 'memory', 'status' => $status, 'message' => $message];
    }

    //this is for uploading
    public function suhosin() {
        if (\UploadStream::getSuhosinStatus() == true || (strpos(ini_get('suhosin.perdir'), 'e') !== false && strpos($_SERVER["SERVER_SOFTWARE"], 'Microsoft-IIS') === false)) {
            $status = 1;
            $message = "";
        } else {
            $status = -1;
            $message = "Suhosin error";
        }

        return ['label' => 'PHP allows stream', 'status' => $status, 'message' => $message];
    }

    public function extgd() {
        if (extension_loaded('gd')) {
            $status = 1;
            $message = "";
        } else {
            $status = -1;
            $message = "";
        }
        
        return ['label' => 'GD', 'status' => $status, 'message' => $message];
    }

    function return_bytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        $val = preg_replace("/[^0-9,.]/", "", $val);

        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

}