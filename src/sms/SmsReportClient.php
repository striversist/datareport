<?php
namespace Cashcash\DataReport\sms;

class SmsReportClient{

    //上报环境：dev
    const ENV_DEV = 0;
    //上报环境：prod
    const ENV_PROD = 1;

    //生产环境默认域名
    const DEFAULT_PROD_HOST = 'http://sms.haohaimobi.com';

    //测试环境默认域名
    const DEFAULT_DEV_HOST = 'http://test-sms.haohaimobi.com';

    const SIGN_TOKEN = 'fs2341231tafafdf';

    /**
     * 上报接口域名
     * @var string|null
     */
    private $host;

    private $countryCode;

    public function __construct($countryCode,$env = self::ENV_PROD)
    {
        $this->countryCode = strtoupper(trim($countryCode));
        $this->prepareEnv($env);
    }



    private function prepareEnv($env){
        if ($env === self::ENV_PROD){
            //每个国家的上报域名
            $hostMappers = [
                'MX' => 'http://cdn-sms.haohaimobi.com',
                'CO' => 'http://cdn-sms.haohaimobi.com',
                'CL' => 'http://cdn-sms.haohaimobi.com',
                'PE' => 'http://cdn-sms.haohaimobi.com',
            ];
            $this->host  = $hostMappers[$this->countryCode]??self::DEFAULT_PROD_HOST;
        }else{
            //每个国家的上报域名
            $hostMappers = [];
            $this->host  = $hostMappers[$this->countryCode]??self::DEFAULT_DEV_HOST;
        }

    }

    public function successCallback($requestId){
        $url = $this->host . '/api/report/sms';
        $params = [
            'requestId' => $requestId,
        ];

        list($msec, $sec) = explode(' ', microtime());
        $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $timestamp = substr($msectime,0,13);

        $sign = $this->buildSign($params,$timestamp);

        $header = [
            'Content-Type: application/x-www-form-urlencoded',
            'timestamp:' . $timestamp,
            'sign:' . $sign,
            'X-Trace-Log-Id:' . 'report-' . $requestId
        ];

        $rsp = $this->request($url,$params,$header);
    }



    /**
     * 生成签名
     * @param array $params         -请求参数
     * @param $timeStamp            -时间戳
     * @return string
     */
    private function buildSign(array $params,$timeStamp){
        ksort($params);
        $queryStr = '';

        foreach ($params as $key => $value){
            if (is_array($value) || is_object($value)){
                $value = json_encode($value);
            }

            $queryStr .= $key . '=' . $value;
        }

        $queryStr .= $timeStamp;
        $queryStr .= self::SIGN_TOKEN;

        return md5($queryStr);

    }

    /**
     * @param $url
     * @param array $params
     * @param array $headers
     * @return array|mixed
     */
    private function request($url,array $params = [],$headers = []){

        $queryString = '';
        foreach ($params as $k=>$v){
            $queryString .= $k . '=' . $v . '&';
        }
        $queryString = rtrim($queryString,'&');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $queryString,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errorCode = curl_errno($curl);

        curl_close($curl);

        $ret = json_decode($response,true);

        return is_array($ret)?$ret:[];
    }
}





?>