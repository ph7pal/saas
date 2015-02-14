<?php

class zmf {

    public static function test($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public static function config($type) {
        if ($type == 'authcode') {
            return 'b93154b988e33fdf0d144fde73028b77';
        } elseif ($type == 'authorCode') {
            return '2014@zmf';
        } elseif ($type == 'authorPre') {
            return 'zmf_grantpower';
        } elseif (empty(Yii::app()->params['c'])) {
            $_c = Config::model()->findAll();
            $configs = CHtml::listData($_c, 'name', 'value');
            tools::writeSet($configs);
            return $configs[$type];
        } else {
            return Yii::app()->params['c'][$type];
        }
    }

    public static function userConfig($uid, $type = '') {
        $settings = self::getFCache("userSettings{$uid}");
        if (!$settings) {
            $dataProvider = UserInfo::model()->findAllByAttributes(array('uid' => $uid));
            $settings = CHtml::listData($dataProvider, 'name', 'value');
            self::setFCache("userSettings{$uid}", $settings);
        }
        if (!empty($type)) {
            return $settings[$type];
        } else {
            return $settings;
        }
    }

    public static function delUserConfig($uid) {
        self::delFCache("userSettings{$uid}");
    }

    public static function subStr($string, $sublen = 20, $start = 0, $separater = '...') {
        $code = 'UTF-8';
        if ($code == 'UTF-8') {
            $string = strip_tags($string);
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);
            if (count($t_string[0]) - $start > $sublen) {
                $str = join('', array_slice($t_string[0], $start, $sublen));
                return $str . $separater;
            } else {
                return join('', array_slice($t_string[0], $start, $sublen));
            }
        } else {
            $start = $start * 2;
            $sublen = $sublen * 2;
            $strlen = strlen($string);
            $tmpstr = '';
            for ($i = 0; $i < $strlen; $i++) {
                if ($i >= $start && $i < ($start + $sublen)) {
                    if (ord(substr($string, $i, 1)) > 129) {
                        $tmpstr .= substr($string, $i, 2);
                    } else {
                        $tmpstr .= substr($string, $i, 1);
                    }
                }
                if (ord(substr($string, $i, 1)) > 129)
                    $i++;
            }
            if (strlen($tmpstr) < $strlen)
                $tmpstr .= $separater;
            return $tmpstr;
        }
    }

    public static function createUploadDir($dir) {
        if (!is_dir($dir)) {
            $temp = explode('/', $dir);
            $cur_dir = '';
            for ($i = 0; $i < count($temp); $i++) {
                $cur_dir .= $temp[$i] . '/';
                if (!is_dir($cur_dir)) {
                    mkdir($cur_dir, 0777);
                }
            }
        }
    }

    public static function uploadDirs($logid, $base = 'site', $type = 'scenic', $return = '') {
        $dirConfig = self::config('imgThumbSize');
        if ($dirConfig == '') {
            return false;
        }
        //$dirConfig = '124,200,300,600,origin';
        $sizes = array_unique(array_filter(explode(",", $dirConfig)));
        if (empty($sizes)) {
            return false;
            exit;
        }
        $dir = array();
        $baseUrl = self::attachBase($base);
        foreach ($sizes as $size) {
            $dir[$size] = $baseUrl . $type . '/' . $size . '/' . $logid;
        }
        if (!empty($return)) {
            $dir = $dir[$return];
        }
        return $dir;
    }

    public static function attachBase($base) {
        if ($base === 'site') {
            //根据网站          
            if (zmf::config('imgVisitUrl') != '') {
                $baseUrl = zmf::config('imgVisitUrl') . '/';
            } else {
                $baseUrl = zmf::config('baseurl') . 'attachments/';
            }
        } elseif ($base === 'app') {
            //根据应用来
            if (zmf::config('imgUploadUrl') != '') {
                $baseUrl = zmf::config('imgUploadUrl') . '/';
            } else {
                $baseUrl = Yii::app()->basePath . "/../attachments/";
            }
        } elseif ($base == 'upload') {
            //解决imagick open图片问题
            if (zmf::config('imgUploadUrl') != '') {
                $baseUrl = zmf::config('imgUploadUrl') . '/';
            } else {
                $baseUrl = zmf::config('baseurl') . 'attachments/';
            }
        } else {
            $baseUrl = '';
        }
        return $baseUrl;
    }

    public static function imgurl($logid, $filepath, $imgtype, $type = 'scenic') {
        return self::config('baseurl') . 'attachments/' . $type . '/' . $imgtype . '/' . $logid . '/' . $filepath;
    }

    public static function noImg($type = '', $altTitle = '暂无图片', $size = 124) {
        if (!isset($size)) {
            $size = 124;
        }
        $url = self::config('baseurl') . 'common/images/nopic_' . $size . '.gif';
        if ($type == 'url') {
            return $url;
            exit();
        }
        return CHtml::image($url, $altTitle);
    }

    public static function avatar($uid, $type = 'small', $urlonly = false) {
        if (!$uid) {
            $_img = Yii::app()->baseUrl . "/common/avatar/{$type}.gif";
            if ($urlonly) {
                return $_img;
            } else {
                return "<img src='{$_img}' class='thumbnail img-responsive'/>";
            }
        }
        $logo = self::userConfig($uid, 'logo');
        $img = '';
        if ($logo) {
            $attachinfo = Attachments::getOne($logo);
            if ($attachinfo) {
                if ($type == 'small') {
                    $_type = 124;
                } elseif ($type == 'big') {
                    $_type = 600;
                } else {
                    $_type = 124;
                }
                $img = zmf::imgurl($attachinfo['logid'], $attachinfo['filePath'], $_type, $attachinfo['classify']);
            } else {
                $img = '';
            }
        }
        if ($img) {
            if ($urlonly) {
                return $img;
            } else {
                return "<img src='{$img}' class='thumbnail img-responsive'/>";
            }
        } else {
            $_img = Yii::app()->baseUrl . "/common/avatar/{$type}.gif";
            if ($urlonly) {
                return $_img;
            } else {
                return "<img src='{$_img}' class='thumbnail img-responsive'/>";
            }
        }
    }

    public static function creditIcon($uid = '', $return = '', $creditType = '') {
        if (!$uid && !$creditType) {
            return false;
        }
        if ($uid) {
            $creditlogo = zmf::userConfig($uid, 'creditlogo');
            if (!$creditlogo) {
                return false;
            }
        } else {
            $creditlogo = $creditType;
        }
        if ($return == 'type') {
            return $creditlogo;
        }
        $info = tools::creditLogos($creditlogo);
        if (!$info) {
            return false;
        }

        $url = self::config('baseurl') . 'common/images/credits/' . $creditlogo . '.png';
        $_url = self::attachBase('app') . '../common/images/credits/' . $creditlogo . '.png';
        if (file_exists($_url)) {
            return "<img src='{$url}' title='" . $info['desc'] . "' alt='" . $info['title'] . "'/>";
        } else {
            return "<span class='btn btn-primary btn-xs' title='" . $info['desc'] . "'>" . $info['title'] . "</span>";
        }
    }

    //fileCache
    public static function setFCache($key, $value, $expire = '60') {
        Yii::app()->filecache->set($key, $value, $expire);
    }

    public static function getFCache($key) {
        return Yii::app()->filecache->get($key);
    }

    public static function delFCache($key) {
        Yii::app()->filecache->delete($key);
    }

    public static function filterInput($str, $type = 'n', $textonly = false) {
        if ($textonly) {
            $str = strip_tags(trim($str));
        }
        if ($type === 'n') {
            $str = strip_tags($str);
            if (!is_numeric($str)) {
                $str = (int) $str;
            }
        } elseif ($type === 't') {
            
        }
        return $str;
    }

    public static function isSystem($return = '') {
        $positions = array(
          '1' => '是',
          '0' => '否',
        );
        if ($return != '') {
            return $positions[$return];
        } else {
            return $positions;
        }
    }

    public static function freeOrPayed($return = '') {
        $positions = array(
          'free' => '免费',
          'perHour' => '每小时',
          'perday' => '每天',
          'perWeek' => '每周',
          'perMonth' => '每月',
          'perSea' => '季度',
          'perYear' => '每年',
        );
        if ($return != '') {
            return $positions[$return];
        } else {
            return $positions;
        }
    }

    public static function exStatus($status) {
        switch ($status) {
            case 0:
                return '未编辑';
            case 1:
                return '已通过';
            case 2:
                return '未通过';
            case 3:
                return '已删除';
            default :
                return '未编辑';
        }
    }

    public static function checkApp() {
        if (empty(Yii::app()->params['author']) || empty(Yii::app()->params['copyrightInfo'])) {
            //self::destory(Yii::app()->basePath);
            exit(Yii::t('default', 'notServiced'));
        } else {
            if (md5(Yii::app()->params['author']) != '067e73ad3739f7e6a1fc68eb391fc5ba' || md5(Yii::app()->params['copyrightInfo']) != 'acc869dee704131e9024decebb3ef0c3') {
                //self::destory(Yii::app()->basePath);
                exit(Yii::t('default', 'notServiced'));
            }
        }
    }

    public static function checkRight($type, $code) {
        if (!$type || !$code) {
            return false;
        }
        //author
        if ($type == 'a') {
            if ($code != '067e73ad3739f7e6a1fc68eb391fc5ba') {
                return false;
            } else {
                return true;
            }
        } elseif ($type == 'r') {//copyright
            if ($code != 'acc869dee704131e9024decebb3ef0c3') {
                return false;
            } else {
                return true;
            }
        }
    }

    public static function myGetImageSize($url, $type = 'curl', $isGetFilesize = false) {
        // 若需要获取图片体积大小则默认使用 fread 方式
        $type = $isGetFilesize ? 'fread' : $type;
        if ($type == 'fread') {
            // 或者使用 socket 二进制方式读取, 需要获取图片体积大小最好使用此方法
            $handle = fopen($url, 'rb');
            if (!$handle)
                return false;
            // 只取头部固定长度168字节数据
            $dataBlock = fread($handle, 256);
        } else {
            // 据说 CURL 能缓存DNS 效率比 socket 高
            $ch = curl_init($url);
            // 超时设置
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            // 取前面 168 个字符 通过四张测试图读取宽高结果都没有问题,若获取不到数据可适当加大数值
            curl_setopt($ch, CURLOPT_RANGE, '0-256');
            // 跟踪301跳转
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            // 返回结果
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataBlock = curl_exec($ch);
            curl_close($ch);
            if (!$dataBlock)
                return false;
        }
        // 将读取的图片信息转化为图片路径并获取图片信息,经测试,这里的转化设置 jpeg 对获取png,gif的信息没有影响,无须分别设置
        // 有些图片虽然可以在浏览器查看但实际已被损坏可能无法解析信息 
        $size = getimagesize('data://image/jpeg;base64,' . base64_encode($dataBlock));
        if (empty($size)) {
            return false;
        }
        $result['width'] = $size[0];
        $result['height'] = $size[1];
        // 是否获取图片体积大小
        if ($isGetFilesize) {
            // 获取文件数据流信息
            $meta = stream_get_meta_data($handle);
            // nginx 的信息保存在 headers 里，apache 则直接在 wrapper_data 
            $dataInfo = isset($meta['wrapper_data']['headers']) ? $meta['wrapper_data']['headers'] : $meta['wrapper_data'];
            foreach ($dataInfo as $va) {
                if (preg_match('/length/iU', $va)) {
                    $ts = explode(':', $va);
                    $result['size'] = trim(array_pop($ts));
                    break;
                }
            }
        }
        if ($type == 'fread')
            fclose($handle);
        return $result;
    }

    //判断是平板电脑还是手机
    public static function checkmobile() {
        if (!self::config("mobile")) {
            return false;
            exit();
        }
        $mobile = array();
        static $mobilebrowser_list = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
          'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
          'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
          'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
          'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
          'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
          'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
        $pad_list = array('pad', 'gt-p1000');

        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (self::dstrpos($useragent, $pad_list)) {
            return false;
        }
        if (($v = self::dstrpos($useragent, $mobilebrowser_list, true))) {
            return true;
        }
        $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
        if (self::dstrpos($useragent, $brower))
            return false;
    }

    public static function dstrpos($string, &$arr, $returnvalue = false) {
        if (empty($string))
            return false;
        foreach ((array) $arr as $v) {
            if (strpos($string, $v) !== false) {
                $return = $returnvalue ? $v : true;
                return $return;
            }
        }
        return false;
    }

    public static function qrcode($content, $origin, $keyid) {
        if (!$content || !$origin || !$keyid) {
            return false;
        }
        $filename = 'qrcode.png';
        $siteUrl = self::attachBase('site') . 'qrcode/' . $origin . '/' . $keyid . '/';
        $appUrl = self::attachBase('app') . '/qrcode/' . $origin . '/' . $keyid . '/';
        self::createUploadDir($appUrl);
        if (file_exists($appUrl . $filename)) {
            return $siteUrl . $filename;
        } else {
            Yii::import('ext.qrcode.QRCode');
            $code = new QRCode($content);
            $code->create($appUrl . $filename);
            return $siteUrl . $filename;
        }
    }
    /**
     * 根据时区获取当前时间
     * @param type $timestamp
     * @return type
     */
    public static function now($timestamp = '') {
        $timeset = date_default_timezone_get();
        if (!in_array($timeset, array('Etc/GMT-8', 'PRC', 'Asia/Shanghai', 'Asia/Shanghai', 'Asia/Chongqing'))) {
            date_default_timezone_set('Etc/GMT-8');
        }
        if ($timestamp == '') {
            return time();
        } else {
            return strtotime($timestamp, time());
        }
    }

    /**
     * 格式化时间戳
     * @param type $time
     * @param type $format
     * @return type
     */
    public static function time($time = '', $format = 'Y-m-d H:i:s') {
        if (!$time) {
            $time = zmf::now();
        }
        $timeset = date_default_timezone_get();
        if (!in_array($timeset, array('Etc/GMT-8', 'PRC', 'Asia/Shanghai', 'Asia/Shanghai', 'Asia/Chongqing'))) {
            date_default_timezone_set('Etc/GMT-8');
        }
        return date($format, $time);
    }

}
