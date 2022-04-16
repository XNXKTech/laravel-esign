<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Endpoints;

use XNXK\LaravelEsign\Adapter\Adapter;
use XNXK\LaravelEsign\Traits\BodyAccessorTrait;

class SignFlow implements API
{
    use BodyAccessorTrait;

    // Api URL
    public const CREATE_FLOW_NOE_STEP = '/api/v2/signflows/createFlowOneStep';         // 一步发起签署
    public const CREATE_SIGN_PROCESS = '/v1/signflows';                                // 签署流程创建
    public const PROCESS_DOCUMENT_ADD = '/v1/signflows/%s/documents';                  // 流程文档添加
    public const PLATFORM_SIGN_ADD = '/v1/signflows/%s/signfields/platformSign';       // 添加平台方自动盖章签署区
    public const HAND_SIGN_ADD = '/v1/signflows/%s/signfields/handSign';               // 添加手动盖章签署区
    public const AUTO_SIGN_ADD = 'v1/signflows/%s/signfields/autoSign';                // 添加签署方自动盖章签署区
    public const SIGN_PROCESS_START = '/v1/signflows/%s/start';                        // 签署流程开启
    public const EXECUTE_URL = '/v1/signflows/%s/executeUrl';                          // 获取签署地址
    public const SIGN_PROCESS_ARCHIVE = '/v1/signflows/%s/archive';                    // 签署流程归档
    public const SIGN_PROCESS_DOCUMENT = '/v1/signflows/%s/documents';                 // 流程文档下载
    public const SIGN_REVOKE = '/v1/signflows/%s/revoke';                              // 签署流程撤销
    public const SIGN_PROCESS_STATUS = '/v1/signflows/%s';                             // 签署流程状态查询
    public const SIGN_SIGNERS = '/v1/signflows/%s/signers';                             // 流程签署人列表
    public const SIGN_SIGNERS_RUSHSIGN = '/v1/signflows/%s/signers/rushsign';          // 流程签署人催签

    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * 一步发起签署.
     *
     * @param  array  $docs  附件信息
     * @param  array  $flowInfo  抄送人人列表
     * @param  array  $signers  待签文档信息
     * @param  array  $attachments  流程基本信息
     * @param  array  $copiers  签署方信息
     */
    public function createFlowOneStep(array $docs, array $flowInfo, array $signers, array $attachments = [], array $copiers = [])
    {
        $params = compact('docs', 'flowInfo', 'signers');
        $attachments and $params['attachments'] = $attachments;
        $copiers and $params['copiers'] = $copiers;

        $response = $this->adapter->post(self::CREATE_FLOW_NOE_STEP, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 签署流程创建.
     *
     * @param string $businessScene 文件主题
     * @param array|null $configInfo
     * @param bool $autoArchive 是否自动归档
     * @return mixed
     */
    public function createSignFlow(string $businessScene, ?array $configInfo = null, bool $autoArchive = true)
    {
        $params = [
            'autoArchive' => $autoArchive,
            'businessScene' => $businessScene,
            'configInfo' => $configInfo,
        ];

        $response = $this->adapter->post(self::CREATE_SIGN_PROCESS, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 流程文档添加.
     *
     * @param  string  $flowId  流程id
     * @param  string  $fileId  文档id
     * @param  int  $encryption  是否加密
     * @param  null  $fileName  文件名称
     * @param  null  $filePassword  文档密码
     */
    public function addDocuments(string $flowId, string $fileId, int $encryption = 0, $fileName = null, $filePassword = null)
    {
        $url = sprintf(self::PROCESS_DOCUMENT_ADD, $flowId);
        $params = [
            'docs' => [
                [
                    'fileId' => $fileId,
                    'encryption' => $encryption,
                    'fileName' => $fileName,
                    'filePassword' => $filePassword,
                ],
            ],
        ];

        $response = $this->adapter->post($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 添加平台自动盖章签署区.
     * https://open.esign.cn/doc/detail?id=opendoc%2Fpaas_api%2Fog6zl5&namespace=opendoc%2Fpaas_api.
     *
     * @param string $flowId 流程id
     * @param array $signFields 签署区列表数据
     * @return mixed
     */
    public function addPlatformSign(string $flowId, array $signFields)
    {
        $url = sprintf(self::PLATFORM_SIGN_ADD, $flowId);
        $params = [
            'signfields' => $signFields,
        ];

        $response = $this->adapter->post($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 添加签署方自动盖章签署区.
     * https://open.esign.cn/doc/detail?id=opendoc%2Fpaas_api%2Fvgi378&namespace=opendoc%2Fpaas_api.
     *
     * @param  string  $flowId  流程id
     * @param  array  $signFields  签署区列表数据
     */
    public function addAutoSign(string $flowId, array $signFields)
    {
        $url = sprintf(self::AUTO_SIGN_ADD, $flowId);
        $params = [
            'signfields' => $signFields,
        ];

        $response = $this->adapter->post($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 添加手动盖章签署区.
     * https://open.esign.cn/doc/detail?id=opendoc%2Fpaas_api%2Figvhzd&namespace=opendoc%2Fpaas_api.
     *
     * @param  string  $flowId  流程id
     * @param  array  $signFields  签署区列表数据
     */
    public function addHandSign(string $flowId, array $signFields)
    {
        $url = sprintf(self::HAND_SIGN_ADD, $flowId);
        $params = [
            'signfields' => $signFields,
        ];

        $response = $this->adapter->post($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 签署流程开启.
     *
     * @param  string  $flowId  流程id
     */
    public function startSignFlow(string $flowId)
    {
        $url = sprintf(self::SIGN_PROCESS_START, $flowId);

        $response = $this->adapter->put($url);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 获取签署地址
     *
     * @param  string  $flowId  流程id
     * @param  string  $accountId  签署人账号id
     * @param  null  $orgId  指定机构id
     * @param  int  $urlType  链接类型: 0-签署链接;1-预览链接;默认0
     * @param  null  $appScheme  app内对接必传
     */
    public function getExecuteUrl(string $flowId, string $accountId, int $urlType = 0, $orgId = 0, $appScheme = null)
    {
        $url = sprintf(self::EXECUTE_URL, $flowId);
        $params = [
            'accountId' => $accountId,
            'organizeId' => $orgId,
            'urlType' => $urlType,
            'appScheme' => $appScheme,
        ];

        $response = $this->adapter->get($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 签署流程归档.
     *
     * @param  string  $flowId  流程id
     */
    public function archiveSign(string $flowId)
    {
        $url = sprintf(self::SIGN_PROCESS_ARCHIVE, $flowId);

        $response = $this->adapter->put($url);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 流程文档下载.
     *
     * @param  string  $flowId  流程id
     */
    public function downloadDocument(string $flowId)
    {
        $url = sprintf(self::SIGN_PROCESS_DOCUMENT, $flowId);

        $response = $this->adapter->get($url);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 签署流程撤销.
     *
     * @param  string  $flowId  流程id
     */
    public function revoke(string $flowId)
    {
        $url = sprintf(self::SIGN_REVOKE, $flowId);

        $response = $this->adapter->put($url);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 签署流程结果查询.
     *
     * @param  string  $flowId  流程id
     */
    public function getSignFlowStatus(string $flowId)
    {
        $url = sprintf(self::SIGN_PROCESS_STATUS, $flowId);

        $response = $this->adapter->get($url);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 流程签署人列表.
     *
     * @param  string  $flowId  流程id
     */
    public function getSignFlowSigners(string $flowId)
    {
        $url = sprintf(self::SIGN_SIGNERS, $flowId);

        $response = $this->adapter->get($url);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * 流程签署人催签.
     *
     * @param  string  $flowId  流程id
     */
    public function rushSign(string $flowId, ?string $accountId = null, ?string $noticeTypes = null, ?string $rushsignAccountId = null)
    {
        $url = sprintf(self::SIGN_SIGNERS_RUSHSIGN, $flowId);
        $params = [
            'accountId' => $accountId,
            'noticeTypes' => $noticeTypes,
            'rushsignAccountId' => $rushsignAccountId,
        ];

        $response = $this->adapter->put($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }
}
