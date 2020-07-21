<?php
/**
 * description: Offline Data Process
 * @author         tux (8966723@qq.com)
 * @date        2019-12-02 14:21:34
 * @version     [1.0]
 * @copyright    firestorm phper
 */

namespace Cashcash\DataReport;

class OfflineProcess
{
    private $logChannel;

    /**
     * [__construct description]
     * @author tux (8966723@qq.com) 2019-12-10
     * @param  [type]  $countryCode [ECS服务器所在地区]
     * @param  integer $projectEnv  [项目当前环境，默认0测试，1生产]
     * @param  integer $linkType    [使用内网链接或是外网链接，默认0内网，1外网]
     * @param  integer $logType     [日志服务商，默认0阿里云，1华为云]
     */
    public function __construct($accessKeyId, $accessKeySecret, $countryCode, $projectEnv = 0, $linkType = 0, $logType = 0)
    {
        if ($logType == 0) {
            //阿里云日志
            $this->logChannel = new AliyunLog($accessKeyId, $accessKeySecret, $countryCode, $projectEnv, $linkType);
        }
    }

    public function addLog($url, $data)
    {
        if (!empty($this->logChannel)) {
            $name    = str_replace("/", "_", $url);
            $content = json_encode($data);
            $this->logChannel->addLog($name, $content);
            return true;
        }
        return false;
    }
}
