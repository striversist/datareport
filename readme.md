- v2.2.2，短信上报添加request_id

```
1.ReportClient::__construct(..)，添加国家码传参

2.ReportClient::smsSend(..)，添加request_id传参

3.ReportClient::smsReceive(..)，添加request_id传参
```

- 2.2.3，短信回填上报至新短信系统的回填接口
```
1.引入新短信系统的上报类
2.ReportClient::smsReceive(...)，追加调用新短信系统的回填上报接口
```

- 2.3.1，短信回填上报：修改测试环境回填接口地址
```
\Cashcash\DataReport\sms:ENV_DEV_HOST
```

- 2.3.3

```
\Cashcash\DataReport\SmsLength异常类undefinde InvalidArgumentException修复
```

- 2.3.4

```
ReportClient::appInstall(..)，添加account_id传参
```

- 2.4.0

```
1.增加appid上报字段(全公司唯一的ID号),封装在构造函数中，增加reportData(数组)字段
2.request_id和report_time(时间日期格式)字段必传(具体看更新后的代码)
(原有逻辑不变，实例化ReportClient类时，增加reportData字段至构造函数中)
    $reportData = array(
        'appid' => 1000
    );
    $report = new ReportClient(.......,$reportData);
    $report->userOrder(.........);
后期有新增字段都可以封装在$reportData中，不用再更新composer包
```

- 2.5.1
```
增加孟加拉（BD）配置信息
```