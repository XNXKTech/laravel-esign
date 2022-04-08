<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Endpoints;

use XNXK\LaravelEsign\Adapter\Adapter;
use XNXK\LaravelEsign\Traits\BodyAccessorTrait;

class File implements API
{
    use BodyAccessorTrait;

    // Api URL
    public const CREATE_SIGN_DOCUMENT = '/v1/files/getUploadUrl';                          // 通过上传方式创建文件
    public const CREATE_UPLOAD_URL = '/v1/docTemplates/createByUploadUrl';                 // 通过上传方式创建模板
    public const ADD_DOC_TEMPLATES = '/v1/docTemplates/%s/components';                     // 通过模板添加输入项组件
    public const DEL_DOC_TEMPLATES = '/v1/docTemplates/%s/components/%s';                  // 删除输入项组件
    public const QUERY_DOC_TEMPLATES = '/v1/docTemplates/%s';                              // 查询模板详情/下载模板
    public const CREATE_TEMPLATE = '/v1/files/createByTemplate';                           // 通过模板创建文件
    public const QUERY_FILE = '/v1/files/%s';                                              // 查询文件详情/下载文件
    public const ADD_WATERMARK = '/v1/files/batchAddWatermark';                            // 文件添加数字水印
    public const SEARCH_WORDS_POSITION = '/v1/documents/%s/searchWordsPosition';           // 搜索关键字坐标

    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * 获取上传文件地址.
     *
     * @param  string  $contentMd5  先计算文件md5值，在对该md5值进行base64编码
     * @param  string  $contentType  目标文件的MIME类型，支持：（1）application/octet-stream（2）application/pdf
     * @param  bool  $convert2Pdf  是否转换成pdf文档，默认false
     * @param  string  $fileName  文件名称（必须带上文件扩展名，不然会导致后续发起流程校验过不去 示例：合同.pdf ）
     * @param  int  $fileSize  文件大小，单位byte
     */
    private function getUploadFileUrl(string $contentMd5, string $fileName, int $fileSize, string $contentType = 'application/pdf', bool $convert2Pdf = false)
    {
        $params = [
            'contentMd5' => $contentMd5,
            'contentType' => $contentType,
            'convert2Pdf' => $convert2Pdf,
            'fileName' => $fileName,
            'fileSize' => $fileSize,
        ];

        $response = $this->adapter->post(self::CREATE_SIGN_DOCUMENT, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 通过获取上传文件地址 && 上传文件.
     *
     * @param $filePath
     * @param string $fileName 文件名称（必须带上文件扩展名，不然会导致后续发起流程校验过不去 示例：合同.pdf ）
     * @param int $fileSize 文件大小，单位byte
     * @param string $contentType 目标文件的MIME类型，支持：（1）application/octet-stream（2）application/pdf
     * @param bool $convert2Pdf 是否转换成pdf文档，默认false
     * @return mixed
     */
    public function uploadFile($filePath, string $fileName, int $fileSize, string $contentType = 'application/pdf', bool $convert2Pdf = false)
    {
        $contentMd5 = getContentBase64Md5($filePath);

        $result = $this->getUploadFileUrl($contentMd5, $fileName, $fileSize, $contentType, $convert2Pdf);

        $this->upload($result->data->uploadUrl, $filePath, $contentMd5, $contentType);

        return $result;
    }

    /**
     * (模板方式) 获取上传文件地址.
     *
     * @param  string  $contentMd5  先计算文件md5值，在对该md5值进行base64编码
     * @param  string  $contentType  目标文件的MIME类型，支持：（1）application/octet-stream（2）application/pdf
     * @param  string  $fileName  文件名称（必须带上文件扩展名，不然会导致后续发起流程校验过不去 示例：合同.pdf ）
     * @param  bool  $convert2Pdf  是否转换成pdf文档，默认false
     */
    private function getUploadFileUrlByDocTemplates(string $contentMd5, string $fileName, string $contentType = 'application/pdf', bool $convert2Pdf = false)
    {
        $params = [
            'contentMd5' => $contentMd5,
            'contentType' => $contentType,
            'convert2Pdf' => $convert2Pdf,
            'fileName' => $fileName,
        ];

        $response = $this->adapter->post(self::CREATE_UPLOAD_URL, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * (模板方式) 获取上传文件地址 && 上传文件.
     *
     * @param  string  $contentMd5
     */
    public function uploadFileByDocTemplates($filePath, string $fileName, string $contentType = 'application/pdf', bool $convert2Pdf = false)
    {
        $contentMd5 = getContentBase64Md5($filePath);

        $result = $this->getUploadFileUrlByDocTemplates($contentMd5, $fileName, $contentType = 'application/pdf', $convert2Pdf);

        $this->upload($result->data->uploadUrl, $filePath, $contentMd5, $contentType);

        return $result;
    }

    /**
     * 添加输入项组件.
     *
     * @param  string  $templateId  模板id
     * @param  int  $type  输入项组件类型，1-文本，2-数字,3-日期，6-签约区
     * @param  string  $label  输入项组件显示名称
     * @param  float  $width  输入项组件宽度
     * @param  float  $height  输入项组件高度
     * @param  int  $page  页码
     * @param  float  $x  x轴坐标，左下角为原点
     * @param  float  $y  y轴坐标，左下角为原点
     * @param  int  $font  填充字体,默认1，1-宋体，2-新宋体，3-微软雅黑，4-黑体，5-楷体
     * @param  int  $fontSize  填充字体大小,默认12
     * @param  string  $textColor  字体颜色，默认#000000黑色
     * @param  null  $id  输入项组件id，使用时可用id填充，为空时表示添加，不为空时表示修改
     * @param  null  $key  模板下输入项组件唯一标识，使用模板时也可用根据key值填充
     * @param  bool  $required  是否必填，默认true
     * @param  null  $limit  输入项组件type=2,type=3时填充格式校验规则;数字格式如：# 或者 #00.0# 日期格式如： yyyy-MM-dd
     */
    public function createInputOption(
        string $templateId,
        int $type,
        string $label,
        float $width,
        float $height,
        int $page,
        float $x,
        float $y,
        int $font = 1,
        int $fontSize = 12,
        string $textColor = '#000000',
        $id = null,
        $key = null,
        bool $required = true,
        $limit = null
    ) {
        $url = sprintf(self::ADD_DOC_TEMPLATES, $templateId);

        $params = [
            'structComponent' => [
                'id' => $id,
                'key' => $key,
                'type' => $type,
                'context' => [
                    'label' => $label,
                    'required' => $required,
                    'limit' => $limit,
                    'style' => [
                        'width' => $width,
                        'height' => $height,
                        'font' => $font,
                        'fontSize' => $fontSize,
                        'textColor' => $textColor,
                    ],
                    'pos' => [
                        'page' => $page,
                        'x' => $x,
                        'y' => $y,
                    ],
                ],
            ],
        ];

        $response = $this->adapter->post($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 删除输入项组件.
     *
     * @param  string  $templateId  模板id
     * @param  string  $ids  输入项组件id集合，逗号分隔
     */
    public function deleteInputOptions(string $templateId, string $ids)
    {
        $url = sprintf(self::DEL_DOC_TEMPLATES, $templateId, $ids);

        $response = $this->adapter->delete($url);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 查询模板详情/下载模板.
     *
     * @param  string  $templateId  模板id
     */
    public function downloadFileByDocTemplate(string $templateId)
    {
        $url = sprintf(self::QUERY_DOC_TEMPLATES, $templateId);

        $response = $this->adapter->get($url);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * (模板方式)通过模板创建文件.
     *
     * @param  string  $templateId  模板编号
     * @param  string  $name  文件名称（必须带上文件扩展名，不然会导致后续发起流程校验过不去 示例：合同.pdf ）；
     * @param  string  $simpleFormFields  输入项填充内容，key:value 传入
     */
    public function createByTemplate(string $templateId, string $name, string $simpleFormFields)
    {
        $params = [
            'name' => $name,
            'templateId' => $templateId,
            'simpleFormFields' => $simpleFormFields,
        ];

        $response = $this->adapter->post(self::CREATE_TEMPLATE, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 查询文件详情/下载文件.
     *
     * @param  string  $fileId  文件id
     */
    public function downloadFile(string $fileId)
    {
        $url = sprintf(self::QUERY_FILE, $fileId);

        $response = $this->adapter->get($url);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 文件添加数字水印.
     *
     * @param  array  $files  文件信息
     * @param  string|null  $notifyUrl  水印图片全部添加完成回调地址
     * @param  string|null  $thirdOrderNo  三方流水号（唯一），有回调必填
     */
    public function batchAddWatermark(array $files, ?string $notifyUrl = null, ?string $thirdOrderNo = null)
    {
        $params = [
            'files' => $files,
            'notifyUrl' => $notifyUrl,
            'thirdOrderNo' => $thirdOrderNo,
        ];

        $response = $this->adapter->post(self::ADD_WATERMARK, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 搜索关键字坐标.
     *
     * @param  string  $fileId  文档ID
     * @param  string  $keywords  关键字列表，逗号分割；注意要英文的逗号，不能中文逗号；关键字建议不要设置特殊字符，因Adobe无法识别部分符号，某些特殊字符会因解析失败从而导致搜索不到。
     */
    public function searchWordsPosition(string $fileId, string $keywords)
    {
        $url = sprintf(self::SEARCH_WORDS_POSITION, $fileId);
        
        $params = [
            'keywords' => $keywords
        ];

        $response = $this->adapter->get($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 上传文件.
     */
    private function upload(string $uploadUrls, string $filePath, string $contentMd5, $contentType = 'application/pdf'): mixed
    {
        $params = [
            'body' => file_get_contents($filePath),
        ];

        $headers = [
            'Content-Type' => $contentType,
            'Content-MD5' => $contentMd5,
        ];

        $response = $this->adapter->put($uploadUrls, $params, $headers);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }
}
