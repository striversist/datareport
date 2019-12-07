<?php
/**
 * description: 统计和计费
 * @author      tux (8966723@qq.com)
 * @date        2019-12-02 14:21:34
 * @version     [1.0]
 * @copyright   firestorm phper
 */

namespace Cashcash\DataReport;

class reportClient
{
    private $offlineDataPath; //离线数据存储路径
    private $isProdEnv; //当前是否生成环境

    private $realtimeProcess; //实时数据处理类
    private $offlineProcess; //离线数据处理类

    const USER_DEVICE   = 'report/api/userdevice'; //新增设备信息
    const USER_ACTIVE   = 'report/api/useractive'; //日活数据
    const USER_REG      = 'report/api/userreg'; //注册
    const USER_ORDER    = 'report/cpi/userorder'; //进件订单
    const FREE_TICKET   = 'report/cpi/freeticket'; //使用免息券
    const POIN_TREDEEM  = 'report/cpi/pointredeem'; //使用积分兑换
    const APP_INSTALL   = 'report/cpi/appinstall'; // 马甲包激活
    const OFFER_INSTALL = 'report/cpi/offerinstall'; //cpi激活
    const SMS           = 'report/service/sms'; //发送短信
    const WHITELIST     = 'report/service/whitelist'; //白名单验证
    const BLACKLIST     = 'report/service/blacklist'; //黑名单验证
    const CHECK_CARD    = 'report/service/checkcard'; //银行卡验证
    const KTP           = 'report/service/ktp'; //实名验证
    const OCR           = 'report/service/ocr'; //图片2文字
    const FACE_COMPARE  = 'report/service/facecompare'; //人脸比对
    const BIOPSY        = 'report/service/biopsy'; //活体
    const PHONE_AGE     = 'report/service/phoneage'; //在网时长
    const PHONE_OWNER   = 'report/service/phoneowner'; //一人多号
    const FACE_SEARCH   = 'report/service/facesearch'; //人脸搜索
    const TEL_SCORE     = 'report/service/telscore'; //电信分
    const FK_SCORE      = 'report/service/fkscore'; //风控分
    const SPIDER        = 'report/service/spider'; //爬虫数据
    const TEL_AUTH      = 'report/service/telauth'; //手机号实名
    const MULTIHEAD     = 'report/service/multihead'; //多头查询
    const RISK_LIST     = 'report/service/risklist'; //风险名单
    const RISKY_FACE    = 'report/service/riskyface'; //风险人脸
    const FK360         = 'report/service/fk360'; //360风控

    public function __construct($offlineDataPath, $isProdEnv = false)
    {
        $this->offlineDataPath = $offlineDataPath;
        $this->isProdEnv       = $isProdEnv;

        $this->realtimeProcess = new RealtimeProcess($isProdEnv);
        $this->offlineProcess  = new OfflineProcess($offlineDataPath);
    }

    /**
     * [userDevice 新增设备信息]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package    [马甲包名]
     * @param  [type] $app_channel    [马甲包内置渠道]
     * @param  [type] $app_version    [马甲包版本]
     * @param  [type] $advertising_id [谷歌广告id, gaid]
     * @param  [type] $guid           [自定义设备id，唯一]
     * @param  [int] $create_time   [创建时间，时间戳]
     * @return [type]                 [description]
     */
    public function userDevice($app_package, $app_channel, $app_version, $advertising_id, $guid, $create_time)
    {
        $data = array(
            'app_package'    => $app_package,
            'app_channel'    => $app_channel,
            'app_version'    => $app_version,
            'advertising_id' => $advertising_id,
            'guid'           => $guid,
            'create_time'    => $create_time,
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::USER_DEVICE, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::USER_DEVICE, $data);
    }
    /**
     * [userActive 用户活跃数据，按天计算，不重复]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package [马甲包名]
     * @param  [type] $app_channel [马甲包内置渠道]
     * @param  [type] $app_version [马甲包版本]
     * @param  [type] $uid         [自定义用户id，唯一]
     * @param  [type] $guid        [自定义设备id，唯一]
     * @param  [int] $create_time   [创建时间，时间戳]
     * @return [type]              [description]
     */
    public function userActive($app_package, $app_channel, $app_version, $uid, $guid, $create_time)
    {
        $data = array(
            'app_package' => $app_package,
            'app_channel' => $app_channel,
            'app_version' => $app_version,
            'uid'         => $uid,
            'guid'        => $guid,
            'create_time' => $create_time,
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::USER_ACTIVE, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::USER_ACTIVE, $data);
    }
    /**
     * [userReg 新增用户注册]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package [马甲包名]
     * @param  [type] $user_name   [用户姓名]
     * @param  [type] $user_mobile [用户手机]
     * @param  [type] $uid         [自定义用户id，唯一]
     * @param  [type] $guid        [自定义设备id，唯一]
     * @param  [int] $create_time   [创建时间，时间戳]
     * @return [type]              [description]
     */
    public function userReg($app_package, $user_name, $user_mobile, $uid, $guid, $create_time)
    {
        $data = array(
            'app_package' => $app_package,
            'user_name'   => $user_name,
            'user_mobile' => $user_mobile,
            'uid'         => $uid,
            'guid'        => $guid,
            'create_time' => $create_time,
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::USER_REG, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::USER_REG, $data);
    }
    /**
     * [userOrder 新增用户订单]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [马甲包名]
     * @param  [type] $offer_package [机构包名]
     * @param  [type] $order_no      [业务系统订单号，唯一]
     * @param  [int] $push_time     [推送时间，时间戳]
     * @param  [type] $uid         [自定义用户id，唯一]
     * @param  [type] $guid        [自定义设备id，唯一]
     * @param  [int] $create_time   [创建时间，时间戳]
     * @return [type]                [description]
     */
    public function userOrder($app_package, $offer_package, $order_no, $push_time, $uid, $guid, $create_time)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'order_no'      => $order_no,
            'push_time'     => $push_time,
            'uid'           => $uid,
            'guid'          => $guid,
            'create_time'   => $create_time,
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::USER_ORDER, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::USER_ORDER, $data);
    }
    /**
     * [freeTicket 免息券使用，成本数据]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $order_no      [description]
     * @param  [type] $ticket_num    [description]
     * @param  [type] $ticket_price  [该免息券的唯一索引]
     * @param  [type] $uid           [该免息券总金额]
     * @param  [type] $guid          [description]
     * @param  [int] $create_time   [description]
     * @return [type]                [description]
     */
    public function freeTicket($app_package, $offer_package, $order_no, $ticket_num, $ticket_price, $uid, $guid, $create_time)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'order_no'      => $order_no,
            'ticket_num'    => $ticket_num,
            'ticket_price'  => $ticket_price,
            'uid'           => $uid,
            'guid'          => $guid,
            'create_time'   => $create_time,
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::FREE_TICKET, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::FREE_TICKET, $data);
    }
    /**
     * [pointRedeem 积分兑换，成本数据]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package [description]
     * @param  [type] $goods_point [兑换商品消耗的积分]
     * @param  [type] $goods_price [兑换商品总金额]
     * @param  [type] $uid         [description]
     * @param  [type] $guid        [description]
     * @param  [int] $create_time [description]
     * @return [type]              [description]
     */
    public function pointRedeem($app_package, $goods_point, $goods_price, $uid, $guid, $create_time)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'order_no'      => $order_no,
            'ticket_num'    => $ticket_num,
            'ticket_price'  => $ticket_price,
            'uid'           => $uid,
            'guid'          => $guid,
            'create_time'   => $create_time,
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::POIN_TREDEEM, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::POIN_TREDEEM, $data);
    }
    /**
     * [appInstall 广告主回调数据，马甲包]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package    [马甲包名]
     * @param  [int] $install_time   [时间戳]
     * @param  [type] $advertising_id [description]
     * @param  [type] $appsflyer_id   [description]
     * @param  [type] $channel        [description]
     * @param  [type] $campaign       [description]
     * @return [type]                 [description]
     */
    public function appInstall($app_package, $install_time, $advertising_id, $appsflyer_id, $channel, $campaign, $country, $ip)
    {
        // $raw_package, $shop_channel
        $raw_package  = $app_package;
        $shop_channel = '';
        if (strrpos($app_package, "-") != false) {
            $package_arr  = explode("-", $app_package);
            $raw_package  = $package_arr[0];
            $shop_channel = $package_arr[1];
        }
        $data = array(
            'app_package'    => $app_package,
            'raw_package'    => $raw_package,
            'shop_channel'   => $shop_channel,
            'install_time'   => $install_time,
            'advertising_id' => $advertising_id,
            'appsflyer_id'   => $appsflyer_id,
            'channel'        => $channel,
            'campaign'       => $campaign,
            'country'        => $country,
            'ip'             => $ip,
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::APP_INSTALL, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::APP_INSTALL, $data);
    }
    /**
     * [offerInstall 广告商回调数据，cpi]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $offer_package  [cpi包名]
     * @param  [int] $install_time   [时间戳]
     * @param  [type] $advertising_id [description]
     * @param  [type] $cid            [description]
     * @param  [type] $sub_id         [description]
     * @param  [type] $cc_id          [description]
     * @param  [type] $clickid        [description]
     * @param  [type] $country        [description]
     * @param  [type] $ip             [description]
     * @return [type]                 [description]
     */
    public function offerInstall($offer_package, $install_time, $advertising_id, $cid, $sub_id, $cc_id, $clickid, $country, $ip)
    {
        // $raw_package, $shop_channel
        $raw_package  = $offer_package;
        $shop_channel = '';
        if (strrpos($offer_package, "-") != false) {
            $package_arr  = explode("-", $offer_package);
            $raw_package  = $package_arr[0];
            $shop_channel = $package_arr[1];
        }
        $data = array(
            'offer_package'  => $offer_package,
            'raw_package'    => $raw_package,
            'shop_channel'   => $shop_channel,
            'install_time'   => $install_time,
            'advertising_id' => $advertising_id,
            'cid'            => $cid,
            'sub_id'         => $sub_id,
            'cc_id'          => $cc_id,
            'clickid'        => $clickid,
            'country'        => $country,
            'ip'             => $ip,
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::OFFER_INSTALL, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::OFFER_INSTALL, $data);
    }
    /**
     * [sms 短信]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [马甲包名]
     * @param  [type] $offer_package [机构包名]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $sms_content   [description]
     * @param  [type] $sms_type      [description]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function sms($app_package, $offer_package, $user_mobile, $sms_content, $sms_type, $channel_type, $is_pay)
    {
        $sms_count = 1;
        $smsLength = new SmsLength($sms_content);
        $sms_len   = $smsLength->getSize();
        if ($sms_len > 160) {
            $sms_count = ceil($sms_len / 153);
        }

        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'sms_content'   => $sms_content,
            'sms_count'     => $sms_count,
            'sms_type'      => $sms_type,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::SMS, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::SMS, $data);
    }
    /**
     * [whitelist 白名单]
     * @author tux (8966723@qq.com) 2019-12-07
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $user_name     [description]
     * @param  [type] $user_idcard   [description]
     * @param  [type] $bank_card     [description]
     * @param  [type] $is_hit        [description]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function whitelist($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $bank_card, $is_hit, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'bank_card'     => $bank_card,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::BLACKLIST, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::BLACKLIST, $data);
    }
    /**
     * [blacklist 黑名单]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $user_name     [description]
     * @param  [type] $user_idcard   [description]
     * @param  [type] $is_hit        [是否命中，命中为1]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function blacklist($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $is_hit, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::BLACKLIST, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::BLACKLIST, $data);
    }
    /**
     * [checkcard 银行卡验证]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $bank_code     [银行代码]
     * @param  [type] $bank_card     [银行卡]
     * @param  [type] $user_name     [description]
     * @param  [type] $is_hit        [是否验证通过，通过为1]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function checkCard($app_package, $offer_package, $bank_code, $bank_card, $user_name, $is_hit, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'bank_code'     => $bank_code,
            'bank_card'     => $bank_card,
            'user_name'     => $user_name,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::CHECK_CARD, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::CHECK_CARD, $data);
    }
    /**
     * [ktp 实名验证]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_name     [description]
     * @param  [type] $user_idcard   [description]
     * @param  [type] $is_hit        [是否验证通过，通过为1]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function ktp($app_package, $offer_package, $user_name, $user_idcard, $is_hit, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::KTP, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::KTP, $data);
    }
    /**
     * [ocr 图片2文字]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $ocr_img       [ocr图片地址]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function ocr($app_package, $offer_package, $ocr_img, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'ocr_img'       => $ocr_img,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::OCR, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::OCR, $data);
    }
    /**
     * [faceCompare 人脸比对]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $raw_img       [参照图片，或是身份证图片的地址]
     * @param  [type] $diff_img      [需要对比的图片地址]
     * @param  [type] $return_code   [针对平安人脸的返回码，默认99]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function faceCompare($app_package, $offer_package, $raw_img, $diff_img, $return_code, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'raw_img'       => $raw_img,
            'diff_img'      => $diff_img,
            'return_code'   => $return_code,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::FACE_COMPARE, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::FACE_COMPARE, $data);
    }
    /**
     * [biopsy 活体检测]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $return_code   [针对平安活体的返回码，默认99]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function biopsy($app_package, $offer_package, $return_code, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'return_code'   => $return_code,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::BIOPSY, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::BIOPSY, $data);
    }
    /**
     * [phoneAge 在网时长]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $phone_age     [description]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function phoneAge($app_package, $offer_package, $user_mobile, $phone_age, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'phone_age'     => $phone_age,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::PHONE_AGE, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::PHONE_AGE, $data);
    }
    /**
     * [phoneOwner 一人多号]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [待检测的手机号]
     * @param  [type] $user_idcard   [待检测的身份证]
     * @param  [type] $mobile_info   [返回的手机号信息]
     * @param  [type] $idcard_info   [返回的身份证信息]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function phoneOwner($app_package, $offer_package, $user_mobile, $user_idcard, $mobile_info, $idcard_info, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_idcard'   => $user_idcard,
            'mobile_info'   => $mobile_info,
            'idcard_info'   => $idcard_info,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::PHONE_OWNER, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::PHONE_OWNER, $data);
    }
    /**
     * [faceSearch 人脸搜索]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_idcard   [description]
     * @param  [type] $face_img      [description]
     * @param  [type] $is_hit        [是否验证通过，通过为1]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function faceSearch($app_package, $offer_package, $user_idcard, $face_img, $is_hit, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_idcard'   => $user_idcard,
            'face_img'      => $face_img,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::FACE_SEARCH, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::FACE_SEARCH, $data);
    }
    /**
     * [telScore 电信评分]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $return_score  [返回分]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function telScore($app_package, $offer_package, $user_mobile, $return_score, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'return_score'  => $return_score,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::TEL_SCORE, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::TEL_SCORE, $data);
    }
    /**
     * [fkScore 风控评分]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $user_name     [description]
     * @param  [type] $user_idcard   [description]
     * @param  [tinyint] $fk_type       [     1=信用分，2=多头分，3=欺诈分]
     * @param  [type] $return_score  [返回分]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function fkScore($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $fk_type, $return_score, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'fk_type'       => $fk_type,
            'return_score'  => $return_score,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::FK_SCORE, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::FK_SCORE, $data);
    }
    /**
     * [spider 爬虫]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $task_id       [爬虫任务id]
     * @param  [type] $auth_code     [爬虫产品类型标识码]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function spider($app_package, $offer_package, $task_id, $auth_code, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'task_id'       => $task_id,
            'auth_code'     => $auth_code,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::SPIDER, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::SPIDER, $data);
    }
    /**
     * [telAuth 手机号实名检测]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $is_hit        [是否验证通过，通过为1]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function telAuth($app_package, $offer_package, $user_mobile, $is_hit, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::TEL_AUTH, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::TEL_AUTH, $data);
    }
    /**
     * [multihead 多头查询，多个平台借款]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $is_hit        [是否命中，命中为1]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function multihead($app_package, $offer_package, $user_idcard, $is_hit, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_idcard'   => $user_idcard,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::MULTIHEAD, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::MULTIHEAD, $data);
    }
    /**
     * [riskList 风险关注名单]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $user_name     [description]
     * @param  [type] $user_idcard   [description]
     * @param  [type] $is_hit        [是否命中，命中为1]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function riskList($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $is_hit, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::RISK_LIST, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::RISK_LIST, $data);
    }
    /**
     * [riskyface 风险人脸]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_idcard   [description]
     * @param  [type] $face_img      [搜索的人脸图片地址]
     * @param  [type] $is_hit        [是否命中，命中为1]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function riskyface($app_package, $offer_package, $user_idcard, $face_img, $is_hit, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_idcard'   => $user_idcard,
            'face_img'      => $face_img,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::RISKY_FACE, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::RISKY_FACE, $data);
    }
    /**
     * [fk360 360风控]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $user_name     [description]
     * @param  [type] $user_idcard   [description]
     * @param  [type] $fk_type       [360风控ABCD产品]
     * @param  [type] $return_score  [返回分数，命中某项分数为0]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @return [type]                [description]
     */
    public function fk360($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $fk_type, $return_score, $channel_type, $is_pay)
    {
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'fk_type'       => $fk_type,
            'return_score'  => $return_score,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'create_time'   => time(),
        );
        // 离线数据存储
        $this->offlineProcess->writeJson(self::FK360, $data);
        // 实时数据上报
        $this->realtimeProcess->sendOut(self::FK360, $data);
    }

}
