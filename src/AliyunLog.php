<?php
/**
 * description: Aliyun Log
 * @author      tux (8966723@qq.com)
 * @date        2019-12-02 14:21:34
 * @version     [1.0]
 * @copyright   firestorm phper
 */

namespace Cashcash\DataReport;

class AliyunLog
{
    //获取阿里云oss的accessKeyId
    private $accessKeyId;
    //获取阿里云oss的accessKeySecret
    private $accessKeySecret;

    private $log_info;
    private $env_source;
    private $code = 400;

    /**
     * [__construct description]
     * @author tux (8966723@qq.com) 2019-12-10
     * @param  [type]  $countryCode [ECS服务器所在地区]
     * @param  boolean $projectEnv  [项目当前环境，默认0测试]
     * @param  integer $linkType    [使用内网链接或是外网链接，默认0内网]
     */
    public function __construct($accessKeyId, $accessKeySecret, $countryCode, $projectEnv, $linkType)
    {
        $accessKeyId     = trim($accessKeyId);
        $accessKeySecret = trim($accessKeySecret);
        if (empty($accessKeyId)) {
            throw new \Aliyun_Log_Exception($this->code, "aliyun log access key id is empty");
        }
        if (empty($accessKeySecret)) {
            throw new \Aliyun_Log_Exception($this->code, "aliyun log access key secret is empty");
        }
        $this->accessKeyId     = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
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
            'mx' => [
                'end_point'    => $linkType == 0 ? 'us-west-1-intranet.log.aliyuncs.com' : 'us-west-1.log.aliyuncs.com',
                'project_name' => 'data-mx',
                'log_store'    => 'cashcash',
            ],
            'in' => [
                'end_point'    => $linkType == 0 ? 'ap-south-1-intranet.log.aliyuncs.com' : 'ap-south-1.log.aliyuncs.com',
                'project_name' => 'data-in',
                'log_store'    => 'cashcash',
            ],
            'ph' => [
                'end_point'    => $linkType == 0 ? 'ap-southeast-6-intranet.log.aliyuncs.com' : 'ap-southeast-6.log.aliyuncs.com',
                'project_name' => 'data-ph',
                'log_store'    => 'cashcash',
            ],
            'la' => [
                'end_point'    => $linkType == 0 ? 'ap-southeast-1-intranet.log.aliyuncs.com' : 'ap-southeast-1.log.aliyuncs.com',
                'project_name' => 'data-la',
                'log_store'    => 'cashcash',
            ],
            'co' => [
                'end_point'    => $linkType == 0 ? 'us-west-1-intranet.log.aliyuncs.com' : 'us-west-1.log.aliyuncs.com',
                'project_name' => 'data-co',
                'log_store'    => 'cashcash',
            ],
            'mm' => [
                'end_point'    => $linkType == 0 ? 'ap-southeast-1-intranet.log.aliyuncs.com' : 'ap-southeast-1.log.aliyuncs.com',
                'project_name' => 'data-mm',
                'log_store'    => 'cashcash',
            ],
            'bd' => [
                'end_point'    => $linkType == 0 ? 'ap-southeast-1-intranet.log.aliyuncs.com' : 'ap-southeast-1.log.aliyuncs.com',
                'project_name' => 'data-bd',
                'log_store'    => 'cashcash',
            ],
            'vn' => [
                'end_point'    => $linkType == 0 ? 'ap-southeast-1-intranet.log.aliyuncs.com' : 'ap-southeast-1.log.aliyuncs.com',
                'project_name' => 'data-vn',
                'log_store'    => 'cashcash',
            ],
        ];
        return $log_arr[$countryCode];
    }

    /**
     * 写入日志
     * @param $name
     * @param $content
     * @throws \Aliyun_Log_Exception
     * @throws \Exception
     */
    public function addLog($name, $content)
    {
        try {
            require_once realpath(dirname(__FILE__) . '/aliyun-log-php-sdk-master/Log_Autoload.php');

            $client = new \Aliyun_Log_Client($this->log_info['end_point'], $this->accessKeyId, $this->accessKeySecret);

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
        } catch (\Aliyun_Log_Exception $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * 查询日志
     * @param $from
     * @param $to
     * @param int $offset
     * @param string $line
     * @param string $query
     * @param string $topic
     * @param bool $reverse
     * @return array
     * @throws \Aliyun_Log_Exception
     * @throws \Exception
     */
    public function queryLog($from, $to, $offset = 0, $line = '100', $query = '', $topic = '', $reverse = false)
    {
        try {
            require_once realpath(dirname(__FILE__) . '/aliyun-log-php-sdk-master/Log_Autoload.php');

            $client = new \Aliyun_Log_Client($this->log_info['end_point'], $this->accessKeyId, $this->accessKeySecret);

            $request = new \Aliyun_Log_Models_GetLogsRequest($this->log_info['project_name'], $this->log_info['log_store'], $from, $to, $topic, $query, $line, $offset, $reverse);

            $response = null;
            while (is_null($response) || (!$response->isCompleted())) {
                $response = $client->getLogs($request);
            }
            return array('count' => $response->getCount(), 'logs' => $response->getLogs());
        } catch (\Aliyun_Log_Exception $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
