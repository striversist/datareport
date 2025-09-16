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
    private $configData;//配置扩展数据

    //是否开启上报系统
    const OPEN_REPORT_SYS_NO = 0; //否
    const OPEN_REPORT_SYS_YES = 1; //是

    /**
     * [__construct description]
     * @author tux (8966723@qq.com) 2019-12-10
     * @param  [type]  $countryCode [ECS服务器所在地区]
     * @param  boolean $projectEnv  [项目当前环境，默认0测试]
     * @param  integer $linkType    [使用内网链接或是外网链接，默认0内网]
     */
    public function __construct($accessKeyId, $accessKeySecret, $countryCode, $projectEnv, $linkType, $configData)
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
        $this->configData = $configData;
        $this->env_source = ($projectEnv == 0) ? "test" : "prod";
        $this->log_info   = $this->logInfo($countryCode, $linkType);
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
            'th' => [
                'end_point'    => $linkType == 0 ? 'ap-southeast-7-intranet.log.aliyuncs.com' : 'ap-southeast-7.log.aliyuncs.com',
                'project_name' => 'data-th',
                'log_store'    => 'cashcash',
            ],
            'mx' => [
                'end_point'    => $linkType == 0 ? 'us-west-1-intranet.log.aliyuncs.com' : 'log-global.aliyuncs.com',
                'project_name' => 'data-mx',
                'log_store'    => 'cashcash',
            ],
            'in' => [
                'end_point'    => $linkType == 0 ? 'ap-southeast-1-intranet.log.aliyuncs.com' : 'ap-southeast-1.log.aliyuncs.com',
                'project_name' => 'data-in-v2',
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
                'end_point'    => $linkType == 0 ? 'us-west-1-intranet.log.aliyuncs.com' : 'log-global.aliyuncs.com',
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
            'pe' => [
                'end_point'    => $linkType == 0 ? 'us-east-1-intranet.log.aliyuncs.com' : 'us-east-1.log.aliyuncs.com',
                'project_name' => 'data-pe',
                'log_store'    => 'cashcash',
            ],
            'cl' => [
                'end_point'    => $linkType == 0 ? 'us-east-1-intranet.log.aliyuncs.com' : 'us-east-1.log.aliyuncs.com',
                'project_name' => 'data-cl',
                'log_store'    => 'cashcash',
            ],
            'pk' => [
                'end_point'    => $linkType == 0 ? 'me-central-1-intranet.log.aliyuncs.com' : 'me-central-1.log.aliyuncs.com',
                'project_name' => 'data-pk',
                'log_store'    => 'cashcash',
            ],
            'my' => [
                'end_point'    => $linkType == 0 ? 'ap-southeast-3-intranet.log.aliyuncs.com' : 'ap-southeast-3.log.aliyuncs.com',
                'project_name' => 'data-my',
                'log_store'    => 'cashcash',
            ],
        ];
        if (!isset($log_arr[$countryCode])){
            $log_arr = [
                $countryCode => [
                    'end_point'    => $this->configData['end_point'] ?? "ap-southeast-1.log.aliyuncs.com",
                    'project_name' => $this->configData['project_name'] ?? 'data-' . $countryCode,
                    'log_store'    => $this->configData['log_store'] ?? "cashcash",
                ],
            ];
        }
        $logInfo = $log_arr[$countryCode];
        $logInfo['report_url'] = $this->getReportLogInfo($countryCode);
        return $logInfo;
    }

    public function getReportLogInfo($countryCode)
    {
        $cs_host = $this->configData['cs_host'] ?? "";
        $log_arr = [
            'prod' => !empty($cs_host) ? $cs_host : "http://tool-$countryCode-int.toolsvqdr.com",
            'test' => !empty($cs_host) ? $cs_host : "http://devtool-$countryCode.toolsvqdr.com",
        ];
        return $log_arr[$this->env_source];
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

            # 是否开启自建上报系统: 默认开启 取configData中的配置信息 is_open_self_built_report_sys 0=否，1=是
            $is_open_self_built_report_sys = $this->configData['is_open_self_built_report_sys'] ?? $this::OPEN_REPORT_SYS_YES;
            //增加自建上报系统数据上报
            if ($is_open_self_built_report_sys == $this::OPEN_REPORT_SYS_YES) $this->selfBuiltReportSys($contents);

            # 是否开启Aliyun上报系统，默认开启 取configData中的配置信息 is_open_ali_report_sys 0=否，1=是
            $is_open_ali_report_sys = $this->configData['is_open_ali_report_sys'] ?? $this::OPEN_REPORT_SYS_YES;
            if ($is_open_ali_report_sys == $this::OPEN_REPORT_SYS_YES) {
                #写入日志
                $topic = "";
                $source = $this->env_source;
                $logitems = array();

                $logItem = new \Aliyun_Log_Models_LogItem();
                $logItem->setTime(time());
                $logItem->setContents($contents);
                array_push($logitems, $logItem);

                $req2 = new \Aliyun_Log_Models_PutLogsRequest($this->log_info['project_name'], $this->log_info['log_store'], $topic, $source, $logitems);
                $response = $client->putLogs($req2);
            }
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

    /**
     * 自建上报方法
     * @param $contents
     * @return bool|string
     */
    public function selfBuiltReportSys($contents)
    {
        try {
            $timeout = $this->configData['timeout'] ?? 3;
            $report_api = $this->log_info['report_url'] . '/api/dataset/report';
            return $this->doPost($report_api, json_encode($contents), [], $timeout * 1000);
        } catch (\Exception | \Error $e) {
            return $e->getMessage();
        }
    }

    /**
     * post请求方法
     * @param $url
     * @param $data
     * @param array $headers
     * @param int $timeout_ms
     * @return bool|string
     * @throws \Exception
     */
    public function doPost($url, $data, $headers = array(), $timeout_ms = 3000)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 设置请求方式为post
        curl_setopt($ch, CURLOPT_POST, true);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 请求头，可以传数组
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout_ms);
        $output = curl_exec($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if ($error || $info['http_code'] != 200) {
            if ($error) {
                throw new \Exception($error . ' 上报数据为：' . json_encode($data));
            }
            throw new \Exception('curl request failed ' . ' 上报数据为：' . json_encode($data));
        }
        return $output;
    }

}
