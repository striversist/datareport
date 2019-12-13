<?php
/**
 * description: Aliyun Log
 * @author         tux (8966723@qq.com)
 * @date        2019-12-02 14:21:34
 * @version     [1.0]
 * @copyright    firestorm phper
 */

namespace Cashcash\DataReport;

class AliyunLog
{
    //获取阿里云oss的accessKeyId
    const ACCESS_KEY_ID = '';
    //获取阿里云oss的accessKeySecret
    const ACCESS_KEY_SECRET = '';

    private $log_info;
    private $env_source;

    /**
     * [__construct description]
     * @author tux (8966723@qq.com) 2019-12-10
     * @param  [type]  $countryCode [ECS服务器所在地区]
     * @param  boolean $projectEnv  [项目当前环境，默认0测试]
     * @param  integer $linkType    [使用内网链接或是外网链接，默认0内网]
     */
    public function __construct($countryCode, $projectEnv, $linkType)
    {
        $this->log_info   = $this->logInfo($countryCode, $linkType);
        $this->env_source = ($projectEnv == 0) ? "test" : "prod";
    }

    /*
    日志配置
     */
    private function logInfo($countryCode, $linkType = 0)
    {
        if (empty($countryCode)) {
            $countryCode = 'sg';
        }
        $countryCode = strtolower(trim($countryCode));

        // $linkType 默认为0，内网地址
        $log_arr = [
            'id' => [
                'end_point'    => $linkType == 0 ? 'ap-southeast-5-intranet.log.aliyuncs.com' : 'ap-southeast-5.log.aliyuncs.com',
                'project_name' => 'data-id',
                'log_store'    => 'cashcash',
            ],
            'sg' => [
                'end_point'    => $linkType == 0 ? 'ap-southeast-1-intranet.log.aliyuncs.com' : 'ap-southeast-1.log.aliyuncs.com',
                'project_name' => 'data-sg',
                'log_store'    => 'cashcash',
            ],
        ];
        return $log_arr[$countryCode];
    }

    /*
    写入日志
     */
    public function addLog($name, $content)
    {
        $client = null;
        try {
            $client = new \Aliyun_Log_Client($this->log_info['end_point'], self::ACCESS_KEY_ID, self::ACCESS_KEY_SECRET);
        } catch (Exception $ex) {
            require_once realpath(dirname(__FILE__) . '/aliyun-log-php-sdk-master/Log_Autoload.php');
        };

        try {
            if (empty($client)) {
                $client = new \Aliyun_Log_Client($this->log_info['end_point'], self::ACCESS_KEY_ID, self::ACCESS_KEY_SECRET);
            }

            $contents = array(
                'name'    => $name,
                'content' => $content,
                'time'    => time(),
            );

            #写入日志
            $topic    = "";
            $source   = $this->env_source;
            $logitems = array();

            $logItem = new \Aliyun_Log_Models_LogItem();
            $logItem->setTime(time());
            $logItem->setContents($contents);
            array_push($logitems, $logItem);

            $req2     = new \Aliyun_Log_Models_PutLogsRequest($this->log_info['project_name'], $this->log_info['log_store'], $topic, $source, $logitems);
            $response = $client->putLogs($req2);
        } catch (Aliyun_Log_Exception $ex) {
            // logVarDump($ex);
        } catch (Exception $ex) {
            // logVarDump($ex);
        };
    }

}
