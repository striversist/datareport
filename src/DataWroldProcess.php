<?php
/**
 * description: 
 * @author      tux (8966723@qq.com)
 * @date        2019-12-02 14:21:34
 * @version     [1.0]
 * @copyright   firestorm phper
 */

namespace Cashcash\DataReport;

class DataWroldProcess
{
    //获取阿里云oss的accessKeyId
    private $accessKeyId;
    //获取阿里云oss的accessKeySecret
    private $accessKeySecret;
    private $env_source;
    private $code = 400;

    /**
     * 初始化,
     */
    public function __construct($accessKeyId, $accessKeySecret, $projectEnv = 0)
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
        $this->env_source = ($projectEnv == 0) ? "test" : "prod";
    }

    /*
    日志配置
     */
    private function logInfo($store_index)
    {
        $store_list = [
            0 => 'default',
            1 => 'appsflyer',
            2 => 'cash-market',
            3 => 'cash-loan',
        ];
        return [
            'end_point'    => 'ap-southeast-1.log.aliyuncs.com',
            'project_name' => 'data-world',
            'log_store'    => $store_list[$store_index],
        ];
    }

    /**
     * 写入日志
     * @param $url
     * @param $data
     * @param int $store
     * @throws \Aliyun_Log_Exception
     * @throws \Exception
     */
    public function addLog($url, $data, $store = 0)
    {
        try {
            require_once realpath(dirname(__FILE__) . '/aliyun-log-php-sdk-master/Log_Autoload.php');
            $log_info = $this->logInfo($store);

            $client = new \Aliyun_Log_Client($log_info['end_point'], $this->accessKeyId, $this->accessKeySecret);

            $name    = str_replace("/", "_", $url);
            $content = json_encode($data);
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

            $req2     = new \Aliyun_Log_Models_PutLogsRequest($log_info['project_name'], $log_info['log_store'], $topic, $source, $logitems);
            $response = $client->putLogs($req2);
        } catch (\Aliyun_Log_Exception $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
