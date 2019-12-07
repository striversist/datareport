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
    private $file_path;
    public function __construct($filePath)
    {
        $this->file_path = $filePath;
    }

    public function writeJson($url, $data)
    {
        if (substr(trim($this->file_path), -1) != DIRECTORY_SEPARATOR) {
        	// 最后一位是否是斜杠
            $this->file_path = trim($this->file_path) . DIRECTORY_SEPARATOR;
        }
        $this->file_path .= date('Y-m-d'). DIRECTORY_SEPARATOR;
        if (!file_exists($this->file_path)) {
            //检查是否有该文件夹，如果没有就创建，并给予权限
            mkdir($path, 0777, true);
        }
        $file = str_replace("/", "_", $url);
        $file = $this->file_path . $file . ".json";

        $myfile = fopen($file, "a") or die("Unable to open file!");
        fwrite($myfile, json_encode($data) . "\r\n");
        fclose($myfile);
        unset($myfile);
    }
}
