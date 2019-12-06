<?php
/**
 * description:
 * @author         tux (8966723@qq.com)
 * @date        2019-12-02 14:21:34
 * @version     [1.0]
 * @copyright    firestorm phper
 */

namespace Cashcash\DataReport;

class DataReport
{
    private $offlineDataPath; //离线数据存储路径
    private $isProdEnv; //当前是否生成环境

    private $realtimeProcess; //实时数据处理类
    private $offlineProcess; //离线数据处理类

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
        // 离线数据存储
        // 实时数据上报
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
        // 离线数据存储
        // 实时数据上报
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
        // 离线数据存储
        // 实时数据上报
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
        // 离线数据存储
        // 实时数据上报
    }
    /**
     * [freeTicket 免息券使用]
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
        # code...
    }
    /**
     * [pointRedeem 积分兑换]
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
        # code...
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
        // 离线数据存储
        // 实时数据上报
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
        // 离线数据存储
        // 实时数据上报
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
        $data['ctime'] = time();
    }
    /**
     * [blacklist description]
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
        $data['ctime'] = time();
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
        $data['ctime'] = time();
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
        $data['ctime'] = time();
    }
    /**
     * [ocr description]
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
        $data['ctime'] = time();
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
        $data['ctime'] = time();
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
        $data['ctime'] = time();
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
        # code...
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
        # code...
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
        # code...
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
        # code...
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
        # code...
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
        # code...
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
        # code...
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
        # code...
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
        # code...
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
        # code...
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
        # code...
    }

}
