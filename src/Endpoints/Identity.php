<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Endpoints;

use XNXK\LaravelEsign\Adapter\Adapter;
use XNXK\LaravelEsign\Traits\BodyAccessorTrait;

class Identity implements API
{
    use BodyAccessorTrait;
    
    // API URL
    public const ORG_IDENTITY_URL = '/v2/identity/auth/web/%s/orgIdentityUrl';                                    // 获取组织机构实名认证地址
    public const CHECK_BANK_CARD_4FACTORS = '/v2/identity/auth/api/individual/bankCard4Factors';                  // 银行卡4要素核身校验
    public const CHECK_BANK_MOBILE_AUTH_CODE = '/v2/identity/auth/pub/individual/%s/bankCard4Factors';            // 银行预留手机号验证码检验

    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param  string  $orgId  机构 id
     * @param  string  $agentAccountId  办理人账号Id
     * @param  string  $notifyUrl  发起方接收实名认证状态变更通知的地址
     * @param  string  $redirectUrl  实名结束后页面跳转地址
     * @param  string  $contextId  发起方业务上下文标识
     * @param  string  $authType  指定默认认证类型
     * @param  bool  $repeatIdentity  是否允许重复实名，默认允许
     * @param  bool  $showResultPage  实名完成是否显示结果页,默认显示
     */
    public function getOrgIdentityUrl(string $orgId, string $agentAccountId, string $notifyUrl = '', string $redirectUrl = '', string $contextId = '', string $authType = '', bool $repeatIdentity = true, bool $showResultPage = true)
    {
        $url = sprintf(self::ORG_IDENTITY_URL, $orgId);

        $params = [
            'authType' => $authType,
            'repeatIdentity' => $repeatIdentity,
            'agentAccountId' => $agentAccountId,
            'contextInfo' => [
                'contextId' => $contextId,
                'notifyUrl' => $notifyUrl,
                'redirectUrl' => $redirectUrl,
                'showResultPage' => $showResultPage,
            ],
        ];

        $response = $this->adapter->post($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * @param  string  $name  用户真实姓名
     * @param  string  $idNo  身份证号
     * @param  string  $mobileNo  用户在银行预留的手机号
     * @param  string  $bankCardNo  银行卡号
     * @param  string  $contextId  发起方业务上下文标识
     * @param  string  $notifyUrl  认证结束后异步通知地址
     * @param  string  $certType  个人证件类型 不传默认为身份证
     */
    public function verifyBankCard4Factors(string $name, string $idNo, string $mobileNo = '', string $bankCardNo = '', string $contextId = '', string $certType = '', string $notifyUrl = '')
    {
        $url = self::CHECK_BANK_CARD_4FACTORS;

        $params = [
            'name' => $name,
            'contextId' => $contextId,
            'idNo' => $idNo,
            'mobileNo' => $mobileNo,
            'bankCardNo' => $bankCardNo,
            'certType' => $certType,
            'notifyUrl' => $notifyUrl,
        ];

        $response = $this->adapter->post($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }

    /**
     * @param  string  $flowId  实名认证流程Id
     * @param  string  $authcode  短信验证码，用户收到的6位数字验证码
     */
    public function verifyAuthCodeOfMobile(string $flowId, string $authcode)
    {
        $url = sprintf(self::CHECK_BANK_MOBILE_AUTH_CODE, $flowId);

        $params = ['authcode' => $authcode];

        $response = $this->adapter->post($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body;
    }
}
