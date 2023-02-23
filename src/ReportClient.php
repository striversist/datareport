<?php
/**
 * description: 统计和计费
 * @author      tux (8966723@qq.com)
 * @date        2019-12-02 14:21:34
 * @version     [1.0]
 * @copyright   firestorm phper
 */

namespace Cashcash\DataReport;

use Cashcash\DataReport\sms\SmsReportClient;

class ReportClient
{
    private $realtimeProcess; //实时数据处理类
    private $offlineProcess; //离线数据处理类
    private $dataWroldProcess; //全球数据处理类
    private $report_area;//上报地区
    private $projectEnv;
    private $reportData;//上报数据

    const REPORT_AREA_ID = 0; //上报地区：印尼
    const REPORT_AREA_PH = 1;//上报地区：菲律宾
    const REPORT_AREA_IN = 2;//上报地区：印度
    const REPORT_AREA_TH = 3;//上报地区：泰国
    const REPORT_AREA_MX = 4;//上报地区：墨西哥
    const REPORT_AREA_LA = 5;//上报地区：老挝
    const REPORT_AREA_CO = 6;//上报地区：哥伦比亚
    const REPORT_AREA_MM = 7;//上报地区：缅甸

    const USER_DEVICE   = 'report/stat/userdevice'; //新增设备信息
    const USER_ACTIVE   = 'report/stat/useractive'; //日活数据
    const USER_ORDER    = 'report/stat/userorder'; //进件订单
    const FREE_TICKET   = 'report/stat/freeticket'; //使用免息券
    const POIN_TREDEEM  = 'report/stat/pointredeem'; //使用积分兑换
    const APP_INSTALL   = 'report/stat/appinstall'; // 马甲包激活
    const OFFER_INSTALL = 'report/stat/offerinstall'; //cpi激活
    const USER_INFO     = 'report/stat/userinfo'; //用户数据
    const OFFER_PRICE   = 'report/stat/offerprice'; //每日价格同步
    const USER_PRIMARY  = 'report/stat/userprimary';//用户平台级审批数据

    const SMS          = 'report/service/sms'; //发送短信
    const WHITELIST    = 'report/service/whitelist'; //白名单验证
    const BLACKLIST    = 'report/service/blacklist'; //黑名单验证
    const CHECK_CARD   = 'report/service/checkcard'; //银行卡验证
    const KTP          = 'report/service/ktp'; //实名验证
    const OCR          = 'report/service/ocr'; //图片2文字
    const FACE_COMPARE = 'report/service/facecompare'; //人脸比对
    const FACE_SERVICE = 'report/service/faceservice'; //人脸比对(cashservice人脸比对)
    const BIOPSY       = 'report/service/biopsy'; //活体
    const PHONE_AGE    = 'report/service/phoneage'; //在网时长
    const PHONE_OWNER  = 'report/service/phoneowner'; //一人多号
    const FACE_SEARCH  = 'report/service/facesearch'; //人脸搜索
    const TEL_SCORE    = 'report/service/telscore'; //电信分
    const FK_SCORE     = 'report/service/fkscore'; //风控分
    const SPIDER       = 'report/service/spider'; //爬虫数据
    const PHONE_IDCARD = 'report/service/phoneidcard'; //手机号证件
    const PHONE_AUTH   = 'report/service/phoneauth'; //手机号实名
    const MULTIHEAD    = 'report/service/multihead'; //多头查询
    const RISK_LIST    = 'report/service/risklist'; //风险名单
    const RISKY_FACE   = 'report/service/riskyface'; //风险人脸
    const FK360        = 'report/service/fk360'; //360风控
    const PAY          = 'report/service/pay'; //放款
    const FK_CLOUDUN   = 'report/service/fkcloudun'; //cloudun风控
    const FK_ITIK      = 'report/service/fkitik'; //itik外部数据

    const SMS_SEND    = 'report/stat/smssend'; //短信发送
    const SMS_RECEIVE = 'report/stat/smsreceive'; //短信到达
    const OFFER_CAP   = 'report/stat/offercap'; //cap值同步

    const AUDIT   = 'report/stay/auditing'; //现金贷机审服务
    const WHATS_APP   = 'report/service/whatsapp'; // whatsapp短信
    const COLLECTION   = 'report/service/collection'; // 催收数据上报
    const BLACK   = 'report/service/black'; // 黑名单（新版）
    const ZEUSSECOND = 'report/service/zeussecond'; //宙斯二推费用
    const ID_CHECK = 'report/service/idcheck'; //官方图片验证idcheck
    const FKDK = 'report/service/dkfk'; //dk风控
    const FK_AISKOR = 'report/service/fkaiskor'; //aiskor定制风控
    const FK_FEATURE = 'report/service/feature'; //风控特征模型分
    const LIVENESS_CHECK = 'report/service/livenesscheck'; //静默活体检测
    const COMMENT_GENERATE    = 'report/service/comment'; // 评论生成上报
    //印度服务
    const NAME_CHECK   = 'report/service/namecheck'; // 姓名一致性校验
    const BANKCHECK    = 'report/service/bankcheck'; //印度银行卡校验
    //印度服务-业务上报
    const NAME_CHECK_IN   = 'report/stat/namecheck'; // 姓名一致性校验-业务上报
    const BANK_CHECK_IN    = 'report/stat/bankcheck'; //印度银行卡校验-业务上报

    /**
     * [__construct description]
     * @author tux (8966723@qq.com) 2019-12-10
     * @param  [type]  $countryCode [ECS服务器所在地区]
     * @param  integer $projectEnv  [项目当前环境，默认0测试，1生产]
     * @param  integer $linkType    [使用内网链接或是外网链接，默认0内网，1外网]
     * @param  integer $logType     [日志服务商，默认0阿里云，1华为云]
     * @param  integer $report_area    [上报地区，0=印尼，1=菲律宾，2=印度，3=泰国]
     * @param array $reportData
     */
    public function __construct($accessKeyId, $accessKeySecret, $countryCode, $projectEnv = 0, $linkType = 0, $logType = 0, $report_area = self::REPORT_AREA_ID, $reportData = array())
    {
        $this->realtimeProcess  = new RealtimeProcess($projectEnv);
        $this->offlineProcess   = new OfflineProcess($accessKeyId, $accessKeySecret, $countryCode, $projectEnv, $linkType, $logType);
        $this->dataWroldProcess = new DataWroldProcess($accessKeyId, $accessKeySecret, $projectEnv);
        $this->report_area = $report_area;
        $this->projectEnv = $projectEnv;
        $this->reportData = $reportData;
    }

    /**
     * 时间处理
     * @param $date
     * @return array
     */
    public function getDateDetail($date)
    {
        $tempArr = explode(' ', $date);
        $dateArr = explode('-', $tempArr[0]);
        $res = array(
            'year' => isset($dateArr[0]) ? (int)$dateArr[0] : 0,
            'month' => isset($dateArr[1]) ? (int)$dateArr[1] : 0,
            'day' => isset($dateArr[2]) ? (int)$dateArr[2] : 0,
            'time' => isset($tempArr[1]) ? $tempArr[1] : '',
        );
        return $res;
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
     * @param  [int] $country_code   [国家编码]
     * @return [type]                 [description]
     */
    public function userDevice($app_package, $app_channel, $app_version, $advertising_id, $guid, $create_time, $country_code = self::REPORT_AREA_ID)
    {
        $data = array(
            'app_package'    => $app_package,
            'app_channel'    => $app_channel,
            'app_version'    => $app_version,
            'advertising_id' => $advertising_id,
            'guid'           => $guid,
            'country_code'   => $country_code,
            'create_time'    => $create_time,
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::USER_DEVICE, $data);
        // 全球数据上报
        $this->dataWroldProcess->addLog(self::USER_DEVICE, $data, 2);
        return true;
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
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::USER_ACTIVE, $data);
        // 全球数据上报
        $this->dataWroldProcess->addLog(self::USER_ACTIVE, $data, 2);
        return true;
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
     * @param  [type] $country_code[国家编码]
     * @return [type]              [description]
     */
    public function userReg($app_package, $user_name, $user_mobile, $uid, $guid, $create_time, $country_code = self::REPORT_AREA_ID)
    {
        $data = array(
            'app_package' => $app_package,
            'user_name'   => $user_name,
            'user_mobile' => $user_mobile,
            'event_id'    => 10, //用户行为事件
            'uid'         => $uid,
            'guid'        => $guid,
            'country_code'=> $country_code,
            'create_time' => $create_time,
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::USER_INFO, $data);
        // 全球数据上报
        $this->dataWroldProcess->addLog(self::USER_INFO, $data, 2);
        return true;
    }

    /**
     * [userOrder 新增用户订单]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [马甲包名]
     * @param  [type] $offer_package [机构包名]
     * @param  [type] $order_no      [业务系统订单号，唯一]
     * @param  [int] $push_time     [推送时间，时间戳]
     * @param  [type] $order_status  [订单状态，贷超订单80以上所有状态]
     * @param  [type] $order_type    [订单分类，10=标准进件，11=多推进件]
     * @param  [type] $uid         [自定义用户id，唯一]
     * @param  [type] $guid        [自定义设备id，唯一]
     * @param  [int] $create_time   [创建时间，时间戳]
     * @param  [int] $product_type   [产品类型]
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @param  [type] $user_type     [用户类型]
     * @param  [type] $channel_source[渠道来源]
     * @return [type]                [description]
     */
    public function userOrder($app_package, $offer_package, $order_no, $push_time, $order_status, $order_type, $uid, $guid, $create_time, $product_type, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '', $user_type = -1, $channel_source = 0)
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'order_no'      => $order_no,
            'push_time'     => $push_time,
            'order_status'  => $order_status,
            'order_type'    => $order_type,
            'uid'           => $uid,
            'guid'          => $guid,
            'create_time'   => $create_time,
            'product_type'  => $product_type,
            'country_code'  => $country_code,
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
            'user_type'     => $user_type,
            'channel_source'=> $channel_source,
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::USER_ORDER, $data);
        // 全球数据上报
        $this->dataWroldProcess->addLog(self::USER_ORDER, $data, 2);
        return true;
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
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::FREE_TICKET, $data);
        return true;
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
            'app_package' => $app_package,
            'goods_point' => $goods_point,
            'goods_price' => $goods_price,
            'uid'         => $uid,
            'guid'        => $guid,
            'create_time' => $create_time,
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::POIN_TREDEEM, $data);
        return true;
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
     * @param  [type] $guid           [description]
     * @param  [type] $guid_ctime     [description]
     * @param  [type] $account_id     [description]
     * @return [type]                 [description]
     */
    public function appInstall($app_package, $install_time, $advertising_id, $appsflyer_id, $channel, $campaign, $country, $ip, $guid = '', $guid_ctime = '', $account_id = '')
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
            'guid'           => $guid,
            'guid_ctime'     => $guid_ctime,
            'account_id'     => $account_id
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::APP_INSTALL, $data);
        // 全球数据上报
        $this->dataWroldProcess->addLog(self::APP_INSTALL, $data, 1);
        return true;
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
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::OFFER_INSTALL, $data);
        // 全球数据上报
        $this->dataWroldProcess->addLog(self::OFFER_INSTALL, $data, 1);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function sms($app_package, $offer_package, $user_mobile, $sms_content, $sms_type, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $sms_count = 1;
        $smsLength = new SmsLength($sms_content);
        $sms_len   = $smsLength->getSize();
        if ($sms_len > 160) {
            $sms_count = ceil($sms_len / 153);
        }

        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'sms_content'   => $sms_content,
            'sms_count'     => $sms_count,
            'sms_type'      => $sms_type,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::SMS, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::SMS, $data);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function whitelist($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $bank_card, $is_hit, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
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
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::WHITELIST, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::WHITELIST, $data);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function blacklist($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $is_hit, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::BLACKLIST, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::BLACKLIST, $data);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function checkCard($app_package, $offer_package, $bank_code, $bank_card, $user_name, $is_hit, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'bank_code'     => $bank_code,
            'bank_card'     => $bank_card,
            'user_name'     => $user_name,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::CHECK_CARD, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::CHECK_CARD, $data);
        return true;
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
     * @param  [type] $version_type  [版本类型1=详版，2=简版]
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [description]
     * @param  [type] $request_time  [description]
     * @return [type]                [description]
     */
    public function ktp($app_package, $offer_package, $user_name, $user_idcard, $is_hit, $channel_type, $is_pay, $version_type = 1, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'version_type'  => $version_type,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::KTP, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::KTP, $data);
        return true;
    }
    /**
     * [ocr 图片2文字]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $ocr_img       [ocr图片地址]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [description]
     * @param  [type] $request_time  [description]
     * @return [type]                [description]
     */
    public function ocr($app_package, $offer_package, $ocr_img, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'ocr_img'       => $ocr_img,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::OCR, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::OCR, $data);
        return true;
    }
    /**
     * [faceCompare 人脸比对]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $raw_img       [参照图片，或是身份证图片的地址]
     * @param  [type] $diff_img      [需要对比的图片地址]
     * @param  [type] $request_id    [每笔调用的唯一索引，平安旧request id，新Authorization]
     * @param  [type] $return_code   [针对平安人脸的返回码，默认99]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @param  [string] $order_no    订单号
     * @param  [type] $source_code  [description]
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id_dt [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function faceCompare($app_package, $offer_package, $raw_img, $diff_img, $request_id, $return_code, $channel_type, $is_pay,$order_no = '',$source_code = 0, $country_code = self::REPORT_AREA_ID, $request_id_dt = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'raw_img'       => $raw_img,
            'diff_img'      => $diff_img,
            'request_id'    => $request_id,
            'return_code'   => $return_code,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'order_no'      => $order_no,
            'source_code'   => $source_code,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id_dt' => $request_id_dt,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::FACE_COMPARE, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::FACE_COMPARE, $data);
        return true;
    }

    /**
     * [faceCompare cashservice人脸比对]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $raw_img       [参照图片，或是身份证图片的地址]
     * @param  [type] $diff_img      [需要对比的图片地址]
     * @param  [type] $request_id    [每笔调用的唯一索引，平安旧request id，新Authorization]
     * @param  [type] $return_code   [针对平安人脸的返回码，默认99]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id_dt [description]
     * @param  [type] $request_time  [description]
     * @return [type]                [description]
     */
    public function faceService($app_package, $offer_package, $raw_img, $diff_img, $request_id, $return_code, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id_dt = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'raw_img'       => $raw_img,
            'diff_img'      => $diff_img,
            'request_id'    => $request_id,
            'return_code'   => $return_code,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id_dt' => $request_id_dt,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::FACE_SERVICE, $data);
        // 实时数据上报
        //$data['source_code'] = 1;
        //$this->realtimeProcess->sendOut(self::FACE_COMPARE, $data);
        return true;
    }

    /**
     * [biopsy 活体检测]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $request_id    [每笔调用的唯一索引，平安旧request id，新Authorization]
     * @param  [type] $return_code   [针对平安活体的返回码，默认99]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @param  [string] $order_no    订单号
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id_dt    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function biopsy($app_package, $offer_package, $request_id, $return_code, $channel_type, $is_pay, $order_no = '', $country_code = self::REPORT_AREA_ID, $request_id_dt = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'request_id'    => $request_id,
            'return_code'   => $return_code,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'order_no'      => $order_no,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id_dt' => $request_id_dt,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],

        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::BIOPSY, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::BIOPSY, $data);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function phoneAge($app_package, $offer_package, $user_mobile, $phone_age, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'phone_age'     => $phone_age,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::PHONE_AGE, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::PHONE_AGE, $data);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function phoneOwner($app_package, $offer_package, $user_mobile, $user_idcard, $mobile_info, $idcard_info, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_idcard'   => $user_idcard,
            'mobile_info'   => $mobile_info,
            'idcard_info'   => $idcard_info,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::PHONE_OWNER, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::PHONE_OWNER, $data);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function faceSearch($app_package, $offer_package, $user_idcard, $face_img, $is_hit, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_idcard'   => $user_idcard,
            'face_img'      => $face_img,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::FACE_SEARCH, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::FACE_SEARCH, $data);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function telScore($app_package, $offer_package, $user_mobile, $return_score, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'return_score'  => $return_score,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::TEL_SCORE, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::TEL_SCORE, $data);
        return true;
    }
    /**
     * [fkScore 风控评分]
     * @author tux (8966723@qq.com) 2019-12-06
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $user_name     [description]
     * @param  [type] $user_idcard   [description]
     * @param  [tinyint] $fk_type    [1=信用分,2=多头分,3=欺诈分,4=定制分]
     * @param  [type] $return_score  [返回分]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @param  [type] $service_type  [description]
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $feature_type  [特征类型]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function fkScore($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $fk_type, $return_score, $channel_type, $is_pay,$service_type=1, $country_code = self::REPORT_AREA_ID, $feature_type = 0, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
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
            'service_type'  => $service_type,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'feature_type'  => $feature_type,
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::FK_SCORE, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::FK_SCORE, $data);
        return true;
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
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::SPIDER, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::SPIDER, $data);
        return true;
    }
    /**
     * [phoneAuth 手机号实名检测]
     * @author tux (8966723@qq.com) 2019-12-17
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $is_hit        [description]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function phoneAuth($app_package, $offer_package, $user_mobile, $is_hit, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::PHONE_AUTH, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::PHONE_AUTH, $data);
        return true;
    }
    /**
     * [phoneIdcard 手机号证件检测]
     * @author tux (8966723@qq.com) 2019-12-17
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $user_idcard   [description]
     * @param  [type] $is_hit        [description]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function phoneIdcard($app_package, $offer_package, $user_mobile, $user_idcard, $is_hit, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_idcard'   => $user_idcard,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::PHONE_IDCARD, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::PHONE_IDCARD, $data);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function multihead($app_package, $offer_package, $user_idcard, $is_hit, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_idcard'   => $user_idcard,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::MULTIHEAD, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::MULTIHEAD, $data);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function riskList($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $is_hit, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::RISK_LIST, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::RISK_LIST, $data);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function riskyFace($app_package, $offer_package, $user_idcard, $face_img, $is_hit, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_idcard'   => $user_idcard,
            'face_img'      => $face_img,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::RISKY_FACE, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::RISKY_FACE, $data);
        return true;
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
     * @param  [type] $country_code  [国家编码]
     * @param  [type] $request_id    [请求ID]
     * @param  [type] $request_time  [请求时间]
     * @return [type]                [description]
     */
    public function fk360($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $fk_type, $return_score, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
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
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::FK360, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::FK360, $data);
        return true;
    }

    /**
     * [smsSend 验证码短信发送记录]
     * @author tux (8966723@qq.com) 2019-12-20
     * @param  [type] $partner_id  [接入toolcash项目的partner id]
     * @param  [type] $app_package [马甲包名]
     * @param  [type] $user_mobile [发送手机号码]
     * @param  [type] $sms_type    [短信发送场景]
     * @param  [type] $request_id    [发送短信成功后返回的请求ID]
     */
    public function smsSend($partner_id, $app_package, $user_mobile, $sms_type = 1001,$request_id = '')
    {
        $data = array(
            'partner_id'  => $partner_id,
            'app_package' => $app_package,
            'user_mobile' => $user_mobile,
            'sms_type'    => $sms_type,
            'create_time' => time(),
            'country_code' => $this->report_area,
            'request_id' => $request_id,
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::SMS_SEND, $data);
        return true;
    }

    /**
     * [smsReceive 验证码短信使用记录]
     * @author tux (8966723@qq.com) 2019-12-20
     * @param  [type] $partner_id  [接入toolcash项目的partner id]
     * @param  [type] $app_package [马甲包名]
     * @param  [type] $user_mobile [发送手机号码]
     * @param  string $sms_type    [短信发送场景]
     * @param  string $request_id    [发送短信成功后返回的请求ID]
     */
    public function smsReceive($partner_id, $app_package, $user_mobile, $sms_type = 1001,$request_id = '')
    {
        $data = array(
            'partner_id'  => $partner_id,
            'app_package' => $app_package,
            'user_mobile' => $user_mobile,
            'sms_type'    => $sms_type,
            'create_time' => time(),
            'country_code' => $this->report_area,
            'request_id' => $request_id,
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::SMS_RECEIVE, $data);

        //新短信系统回填
        $smsReport = new SmsReportClient($this->projectEnv);
        $smsReport->successCallback($request_id);

        return true;
    }

    /**
     * cap值同步
     * country_code:0=印尼(默认)，1=菲律宾，2=印度
     */
    public function offerCap($offer_package, $offer_type, $old_value, $new_value, $country_code = self::REPORT_AREA_ID)
    {
        $data = array(
            'offer_package' => $offer_package,
            'offer_type'    => $offer_type,
            'old_value'     => $old_value,
            'new_value'     => $new_value,
            'country_code'  => $country_code,
            'create_time'   => time(),
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::OFFER_CAP, $data);
        return true;
    }

    /**
     *用户数据
     */
    public function userInfo($app_package, $user_name, $user_mobile, $event_id, $uid, $guid, $create_time, $country_code = self::REPORT_AREA_ID)
    {
        $data = array(
            'app_package' => $app_package,
            'user_name'   => $user_name,
            'user_mobile' => $user_mobile,
            'event_id'    => $event_id, //用户行为事件
            'uid'         => $uid,
            'guid'        => $guid,
            'country_code'=> $country_code,
            'create_time' => $create_time,
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::USER_INFO, $data);
        // 全球数据上报
        $this->dataWroldProcess->addLog(self::USER_INFO, $data, 2);
        return true;
    }

    /**
     * 放款手续费
     */
    public function pay($app_package, $offer_package, $order_no, $user_name, $bank_card, $pay_money, $pay_time, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'order_no'      => $order_no,
            'user_name'     => $user_name,
            'bank_card'     => $bank_card,
            'pay_money'     => $pay_money,
            'pay_time'      => $pay_time,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::PAY, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::USER_ORDER, $data);
        return true;
    }

    /**
     *  cloudun风控
     */
    public function fkCloudun($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $user_level, $is_pass, $channel_type, $merchantId, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'user_level'    => $user_level,
            'is_pass'       => $is_pass,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'merchantId'    => $merchantId,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::FK_CLOUDUN, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::FK_CLOUDUN, $data);
        return true;
    }

    /**
     * 外部数据风控
     */
    public function fkItik($app_package, $offer_package, $user_birth, $user_name, $user_idcard, $channel_type, $is_pay, $count_num = 1, $compare_score = 0, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_birth'    => $user_birth,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'count_num'     => $count_num,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'compare_score' => $compare_score,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::FK_ITIK, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::FK_ITIK, $data);
        return true;
    }

    /**
     * 每日价格同步
     * @param $offer_package
     * @param $unit_price
     * @param $income_type
     * @param $income_date
     * @param string $country ID=印尼，IN=印度
     * @return bool
     */
    public function offerPrice($offer_package, $unit_price, $income_type, $income_date, $country = 'ID')
    {
        $data = array(
            'offer_package' => $offer_package,
            'unit_price'    => $unit_price,
            'income_type'   => $income_type,
            'income_date'   => $income_date,
            'country'       => $country,
        );
        $data = array_merge($data, $this->reportData);
        // 全球数据上报
        $this->dataWroldProcess->addLog(self::OFFER_PRICE, $data, 2);
        return true;
    }

    /**
     * 用户平台级审批数据
     * @param array $reports
     * @return bool
     * @throws \Aliyun_Log_Exception
     */
    public function userPrimary($reports = array())
    {
        $data = array(
            'app_package'    => $reports['app_package'] ?? '',
            'app_channel'    => $reports['app_channel'] ?? '',
            'app_version'    => $reports['app_version'] ?? '',
            'advertising_id' => $reports['advertising_id'] ?? '',
            'guid'           => $reports['guid'] ?? '',
            'uid'            => $reports['uid'] ?? '',
            'appsflyer_id'   => $reports['appsflyer_id'] ?? '',
            'user_mobile'    => $reports['user_mobile'] ?? '',
            'user_idcard'    => $reports['user_idcard'] ?? '',
            'event_type'     => $reports['event_type'] ?? 0,
            'country_code'   => $reports['country_code'] ?? self::REPORT_AREA_ID,
            'create_time'    => $reports['create_time'] ?? time(),
        );
        $data = array_merge($data, $this->reportData);
        // 全球数据上报
        $this->dataWroldProcess->addLog(self::USER_PRIMARY, $data, 2);
        return true;
    }

    /**
     * 现金贷机审服务
     */
    public function machineAudit($app_package, $offer_package, $user_name, $user_mobile, $user_idcard, $product_type, $order_no = '', $count_num = 1, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_name'     => $user_name,
            'user_mobile'   => $user_mobile,
            'user_idcard'   => $user_idcard,
            'product_type'  => $product_type,
            'count_num'     => $count_num,
            'order_no'      => $order_no,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::AUDIT, $data);
        return true;
    }

    /**
     * @param $app_package
     * @param $offer_package
     * @param $user_mobile
     * @param $sms_content
     * @param $sms_type
     * @param $channel_type
     * @param $is_pay
     * @param int $country_code
     * @param string $request_id
     * @param string $request_time
     * @return bool
     */
    public function whatsApp($app_package, $offer_package, $user_mobile, $sms_content, $sms_type, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $sms_count = 1;
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'sms_content'   => $sms_content,
            'sms_count'     => $sms_count,
            'sms_type'      => $sms_type,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::WHATS_APP, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::WHATS_APP, $data);
        return true;
    }

    /**
     * @param $app_package
     * @param $offer_package
     * @param $user_mobile
     * @param $channel_type
     * @param $is_pay
     * @param int $country_code
     * @param string $request_id
     * @param string $request_time
     * @return bool
     */
    public function collection($app_package, $offer_package, $user_mobile, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::COLLECTION, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::COLLECTION, $data);
        return true;
    }

    /**
     * [black 黑名单(新版)]
     * @param  [type] $app_package   [description]
     * @param  [type] $offer_package [description]
     * @param  [type] $user_mobile   [description]
     * @param  [type] $user_name     [description]
     * @param  [type] $user_idcard   [description]
     * @param  [type] $is_hit        [是否命中，命中为1]
     * @param  [type] $channel_type  [description]
     * @param  [type] $is_pay        [description]
     * @param  [type] $country_code  [description]
     * @param  [type] $request_id    [description]
     * @param  [type] $request_time  [description]
     * @return [type]                [description]
     */
    public function black($app_package, $offer_package, $user_mobile, $user_name, $user_idcard, $is_hit, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_name'     => $user_name,
            'user_idcard'   => $user_idcard,
            'is_hit'        => $is_hit,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::BLACK, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::BLACK, $data);
        return true;
    }

    /**
     * DK(宙斯)二推费用
     * @param $app_package
     * @param $offer_package
     * @param $user_name
     * @param $user_mobile
     * @param $order_no
     * @param $user_idcard
     * @param int $is_pay
     * @param int $service_type
     * @param int $count_num
     * @param int $channel_source
     * @param string $request_id
     * @param string $request_time
     * @return bool
     * @throws \Error
     * @throws \Exception
     */
    public function zeusSecondCost($app_package, $offer_package, $user_name, $user_mobile,$order_no, $user_idcard, $is_pay=1, $service_type=91, $count_num = 1, $channel_source = 0, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_name'     => $user_name,
            'user_mobile'   => $user_mobile,
            'user_idcard'   => $user_idcard,
            'service_type'  => $service_type,
            'is_pay'        => $is_pay,
            'count_num'     => $count_num,
            'order_no'      => $order_no,
            'channel_source'=> $channel_source,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::ZEUSSECOND, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::ZEUSSECOND, $data);
        return true;
    }


    /**
     * 姓名一致性校验
     * @param $app_package
     * @param $offer_package
     * @param $user_name
     * @param $user_mobile
     * @param $channel_type
     * @param $is_pay
     * @param int $country_code
     * @param string $request_id
     * @param string $request_time
     * @return bool
     * @throws \Error
     * @throws \Exception
     */
    public function nameCheck($app_package, $offer_package, $user_name, $user_mobile, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_name'     => $user_name,
            'user_mobile'   => $user_mobile,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::NAME_CHECK, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::NAME_CHECK, $data);
        return true;
    }

    /**
     * 银行卡验证
     * @param $app_package
     * @param $offer_package
     * @param $transactionId (用户编号)
     * @param $bankAccount (银行卡号)
     * @param $ifscCode (支行编号)
     * @param $pan (pan卡)
     * @param $mobile (手机号)
     * @param $aadhaar (身份证号)
     * @param $is_pay
     * @param $channel_type
     * @param int $country_code
     * @param string $request_id
     * @param string $request_time
     * @return bool
     * @throws \Exception
     */
    public function bankCheck($app_package, $offer_package, $transactionId, $bankAccount, $ifscCode, $pan, $mobile, $aadhaar, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package' => $app_package,
            'offer_package' => $offer_package,
            'transaction_id' => $transactionId,
            'bank_account' => $bankAccount,
            'ifsc_code' => $ifscCode,
            'pan' => $pan,
            'mobile' => $mobile,
            'aadhaar' => $aadhaar,
            'is_pay' => $is_pay,
            'channel_type' => $channel_type,
            'country_code' => $country_code,
            'create_time' => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::BANKCHECK, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::BANKCHECK, $data);
        return true;
    }

    /**
     * 官方照片验证(图片版idCheck)
     * @param $app_package
     * @param $offer_package
     * @param $user_idcard
     * @param $img_url
     * @param $channel_type
     * @param $is_pay
     * @param int $country_code
     * @param string $request_id
     * @param string $request_time
     * @return bool
     * @throws \Error
     * @throws \Exception
     */
    public function idCheck($app_package, $offer_package, $user_idcard, $img_url, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_idcard'   => $user_idcard,
            'img_url'       => $img_url,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::ID_CHECK, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::ID_CHECK, $data);
        return true;
    }

    /**
     * dk风控上报
     * @param $app_package
     * @param $offer_package
     * @param $order_no [订单id]
     * @param $id_number [订单身份证号]
     * @param $phone     [手机号]
     * @param $real_name [真实姓名]
     * @param $channel_type
     * @param $is_pay
     * @param int $country_code
     * @param string $request_id
     * @param string $request_time
     * @return bool
     * @throws \Exception
     */
    public function fkdk($app_package, $offer_package, $order_no, $id_number, $phone, $real_name, $channel_type, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package' => $app_package,
            'offer_package' => $offer_package,
            'order_no' => $order_no,
            'id_number' => $id_number,
            'phone' => $phone,
            'real_name' => $real_name,
            'channel_type' => $channel_type,
            'is_pay' => $is_pay,
            'country_code' => $country_code,
            'create_time' => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::FKDK, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::FKDK, $data);
        return true;
    }

    /**
     * Aiskor定制风控
     * @param $app_package
     * @param $offer_package
     * @param $user_mobile
     * @param $user_idcard
     * @param $order_no
     * @param $channel_type
     * @param $model_id
     * @param $is_pay
     * @param int $country_code
     * @param string $request_id
     * @param string $request_time
     * @return bool
     * @throws \Error
     * @throws \Exception
     */
    public function fkAiskor($app_package, $offer_package, $user_mobile, $user_idcard, $order_no, $channel_type, $model_id, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'user_idcard'   => $user_idcard,
            'order_no'      => $order_no,
            'channel_type'  => $channel_type,
            'model_id'      => $model_id,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::FK_AISKOR, $data);
        // 实时数据上报
        //$this->realtimeProcess->sendOut(self::FK_AISKOR, $data);
        return true;
    }

    /**
     * 印度-姓名一致性校验
     * @param $app_package
     * @param $offer_package
     * @param $user_name
     * @param $user_mobile
     * @param $pan
     * @param $aadhaar
     * @param $channel_type
     * @param $is_pay
     * @param int $country_code
     * @param string $request_id
     * @param string $request_time
     * @return bool
     */
    public function nameCheckStat($app_package, $offer_package, $user_name, $user_mobile, $pan, $aadhaar, $channel_type, $is_pay, $country_code = self::REPORT_AREA_IN, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_name'     => $user_name,
            'user_mobile'   => $user_mobile,
            'user_pan'      => $pan,
            'aadhaar'       => $aadhaar,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::NAME_CHECK_IN, $data);
        return true;
    }

    /**
     * 印度-银行卡校验
     * @param $app_package
     * @param $offer_package
     * @param $user_mobile
     * @param $bank_card
     * @param $ifscCode
     * @param $pan
     * @param $aadhaar
     * @param $channel_type
     * @param $is_pay
     * @param int $country_code
     * @param string $request_id
     * @param string $request_time
     * @return bool
     */
    public function bankCheckStat($app_package, $offer_package, $user_mobile, $bank_card, $ifscCode, $pan, $aadhaar, $channel_type, $is_pay, $country_code = self::REPORT_AREA_IN, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package'   => $app_package,
            'offer_package' => $offer_package,
            'user_mobile'   => $user_mobile,
            'bank_card'     => $bank_card,
            'ifsc_code'     => $ifscCode,
            'user_pan'      => $pan,
            'aadhaar'       => $aadhaar,
            'channel_type'  => $channel_type,
            'is_pay'        => $is_pay,
            'country_code'  => $country_code,
            'create_time'   => time(),
            'request_id'    => $request_id,
            'report_year'   => $res['year'],
            'report_month'  => $res['month'],
            'report_day'    => $res['day'],
            'report_time'   => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::BANK_CHECK_IN, $data);
        return true;
    }

    /**
     * 特征模型分上报
     * @param $app_package
     * @param $offer_package
     * @param $service_type
     * @param $order_no
     * @param $create_time
     * @param $is_pay
     * @param int $country_code
     * @param string $request_id
     * @param string $request_time
     * @return bool
     */
    public function feature($app_package, $offer_package, $service_type, $order_no, $create_time, $is_pay, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package' => $app_package,
            'offer_package' => $offer_package,
            'service_type' => $service_type,
            'order_no' => $order_no,
            'create_time' => $create_time,
            'is_pay' => $is_pay,
            'country_code' => $country_code,
            'request_id' => $request_id,
            'report_year' => $res['year'],
            'report_month' => $res['month'],
            'report_day' => $res['day'],
            'report_time' => $res['time'],
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::FK_FEATURE, $data);
        return true;
    }

    /**
     * 静默活体检测
     * @param $app_package
     * @param $offer_package
     * @param $img_url
     * @param $return_code
     * @param $channel_type
     * @param $is_pay
     * @param $order_no
     * @param int $country_code
     * @param string $request_id
     * @param string $request_time
     * @return bool
     */
    public function livenessCheck($app_package, $offer_package, $img_url, $return_code, $channel_type, $is_pay, $order_no, $country_code = self::REPORT_AREA_ID, $request_id = '', $request_time = '')
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package' => $app_package,
            'offer_package' => $offer_package,
            'img_url' => $img_url,
            'return_code' => $return_code,
            'channel_type' => $channel_type,
            'is_pay' => $is_pay,
            'order_no' => $order_no,
            'country_code' => $country_code,
            'create_time' => time(),
            'request_id' => $request_id,
            'report_year' => $res['year'],
            'report_month' => $res['month'],
            'report_day' => $res['day'],
            'report_time' => $res['time']
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::LIVENESS_CHECK, $data);
        return true;
    }

    /**
     * 生成评论服务
     * @param $app_package
     * @param $offer_package
     * @param $channel_type
     * @param $num
     * @param $is_pay
     * @param $country_code
     * @param $request_time
     * @param $request_id
     * @return bool
     */
    public function commentGenerate($app_package, $offer_package, $channel_type, $num, $is_pay, $country_code, $request_time, $request_id): bool
    {
        $res = $this->getDateDetail($request_time);
        $data = array(
            'app_package' => $app_package,
            'offer_package' => $offer_package,
            'num' => $num,
            'channel_type' => $channel_type,
            'is_pay' => $is_pay,
            'country_code' => $country_code,
            'create_time' => time(),
            'request_id' => $request_id,
            'report_year' => $res['year'],
            'report_month' => $res['month'],
            'report_day' => $res['day'],
            'report_time' => $res['time']
        );
        $data = array_merge($data, $this->reportData);
        // 离线数据存储
        $this->offlineProcess->addLog(self::COMMENT_GENERATE, $data);
        return true;
    }

}
