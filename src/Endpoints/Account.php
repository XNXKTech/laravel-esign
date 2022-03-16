<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Endpoints;

use XNXK\LaravelEsign\Adapter\Adapter;
use XNXK\LaravelEsign\Traits\BodyAccessorTrait;

class Account implements API
{
    use BodyAccessorTrait;

    // API URL
    public const CREATE_PERSONAL_ACCOUNT = '/v1/accounts/createByThirdPartyUserId';                // 创建个人账户
    public const UPDATE_ACCOUNT_BY_THIRD_ID = '/v1/accounts/updateByThirdId?thirdPartyUserId=%s';  // 个人账户修改(按照第三方用户ID修改)
    public const QUERY_ACCOUNT_BY_THIRD_ID = '/v1/accounts/getByThirdId';                          // 查询个人账户（按照第三方用户ID查询）
    public const DEL_ACCOUNT_BY_THIRD_ID = '/v1/accounts/deleteByThirdId?thirdPartyUserId=%s';     // 注销个人账户（按照第三方用户ID查询）
    public const SET_ACCOUNT_SIGN_PAW = '/v1/accounts/%s/setSignPwd';                              // 设置签署密码
    public const ACCOUNT_BY_ID = '/v1/accounts/%s';                                                // 个人账户修改\查询个人账户\注销个人账户(按照账号ID)
    public const ORG_BY_ID = '/v1/organizations/%s';                                               // 机构账户修改\查询机构账号\注销机构账号(按照账号ID)
    public const CREATE_COMPANY_ACCOUNT = '/v1/organizations/createByThirdPartyUserId';            // 创建企业账户
    public const UPDATE_ORG_BY_THIRD_ID = '/v1/organizations/updateByThirdId?thirdPartyUserId=%s'; // 机构账号修改（按照第三方机构ID修改）
    public const QUERY_ORG_BY_THIRD_ID = '/v1/organizations/getByThirdId?thirdPartyUserId=%s';     // 查询机构账号（按照第三方机构ID查询）
    public const DEL_ORG_BY_THIRD_ID = '/v1/organizations/deleteByThirdId?thirdPartyUserId=%s';    // 注销机构账号（按照账号ID注销）
    public const SIGN_AUTH = '/v1/signAuth/%s';                                                    // 设置静默签署/撤销静默签署

    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function queryPersonalAccountByThirdId(string $thirdId)
    {
        $params = [
            'thirdPartyUserId' => $thirdId,
        ];

        $response = $this->adapter->get(self::QUERY_ACCOUNT_BY_THIRD_ID, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body->result;
    }

    /**
     * 创建个人账号.
     *
     * @param  string  $thirdPartyUserId 用户唯一标识
     * @param  string|null  $mobile 手机号码
     * @param  string|null  $name 姓名
     * @param  string|null  $idNumber 证件号
     * @param  string|null  $email 邮箱地址
     *
     * @param  string  $idType 证件类型, 默认: CRED_PSN_CH_IDCARD
     */
    public function createPersonalAccount(string $thirdPartyUserId, ?string $mobile = null, ?string $name = null, ?string $idNumber = null, ?string $email = null, string $idType = 'CRED_PSN_CH_IDCARD')
    {
        $params = [
            'thirdPartyUserId' => $thirdPartyUserId,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
            'mobile' => $mobile,
            'email' => $email,
        ];

        $response = $this->adapter->post(self::CREATE_PERSONAL_ACCOUNT, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body->result;
    }

    /**
     * 个人账户修改(按照账号ID修改).
     *
     * @param  string  $accountId  个人账号id
     * @param  null  $email  联系方式，邮箱地址
     * @param  null  $mobile  联系方式，手机号码
     * @param  null  $name  姓名，默认不变
     * @param  null  $idType  证件类型，默认为身份证
     * @param  null  $idNumber  证件号，该字段只有为空才允许修改
     *
     * @throws HttpException
     */
    public function updatePersonalAccountById(string $accountId, $mobile = null, $idNumber = null, $name = null, $idType = null, $email = null)
    {
        $url = sprintf(self::ACCOUNT_BY_ID, $accountId);
        $params = [
            'mobile' => $mobile,
            'email' => $email,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
        ];

        $response = $this->adapter->post($url, $params);

        $this->body = json_decode((string) $response->getBody());

        return $this->body->result;
    }
}
