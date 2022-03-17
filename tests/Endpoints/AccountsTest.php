<?php

declare(strict_types=1);

namespace Tests\Endpoints;

use Tests\TestCase;
use Tests\TestHelpers;

class AccountsTest extends TestCase
{
    public function testCreatePersonalAccount()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->account()
            ->createPersonalAccount(
                'ef9ab3d3-82f7-4243-976c-312298b51526',
                '',
                '马化腾',
                '',
                ''
            );

        $data = $response->data;

        $this->assertObjectHasAttribute('accountId', $data);

        putenv('TEST_ESIGN_ACCOUNT_ID=' . $data->accountId);
    }

    public function testQueryPersonalAccountByThirdId()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->account()
            ->queryPersonalAccountByThirdId('ef9ab3d3-82f7-4243-976c-312298b51526');

        $data = $response->data;

        $this->assertObjectHasAttribute('mobile', $data);
        $this->assertObjectHasAttribute('email', $data);
        $this->assertObjectHasAttribute('cardNo', $data);
        $this->assertObjectHasAttribute('name', $data);
        $this->assertObjectHasAttribute('accountId', $data);
        $this->assertObjectHasAttribute('idType', $data);
        $this->assertObjectHasAttribute('idNumber', $data);
        $this->assertObjectHasAttribute('thirdPartyUserId', $data);
        $this->assertObjectHasAttribute('thirdPartyUserType', $data);
        $this->assertObjectHasAttribute('status', $data);
    }

    public function testQueryPersonalAccountByAccountId()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->account()
            ->queryPersonalAccountByAccountId(env('TEST_ESIGN_ACCOUNT_ID'));

        $data = $response->data;

        $this->assertObjectHasAttribute('mobile', $data);
        $this->assertObjectHasAttribute('email', $data);
        $this->assertObjectHasAttribute('cardNo', $data);
        $this->assertObjectHasAttribute('name', $data);
        $this->assertObjectHasAttribute('accountId', $data);
        $this->assertObjectHasAttribute('idType', $data);
        $this->assertObjectHasAttribute('idNumber', $data);
        $this->assertObjectHasAttribute('thirdPartyUserId', $data);
        $this->assertObjectHasAttribute('thirdPartyUserType', $data);
        $this->assertObjectHasAttribute('status', $data);
    }

    public function testCreateOrganizeAccount()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->account()
            ->createOrganizeAccount(
                'a66c05ca-edfc-45d9-a594-12db90b8a933',
                env('TEST_ESIGN_ACCOUNT_ID'),
                '腾讯科技（深圳）有限公司',
                '9144030071526726XG',
                '440301197110292910',
                '马化腾'
            );

        $data = $response->data;

        $this->assertObjectHasAttribute('orgId', $data);

        putenv('TEST_ESIGN_ORG_ID=' . $data->orgId);
    }

    public function testQueryOrganizeAccountByOrgId()
    {
        $response = app(TestHelpers::class)
        ->esign()
        ->account()
        ->queryOrganizeAccountByOrgId(env('TEST_ESIGN_ORG_ID'));

        $data = $response->data;

        $this->assertObjectHasAttribute('name', $data);
        $this->assertObjectHasAttribute('creator', $data);
        $this->assertObjectHasAttribute('orgLegalName', $data);
        $this->assertObjectHasAttribute('orgLegalIdNumber', $data);
        $this->assertObjectHasAttribute('orgId', $data);
        $this->assertObjectHasAttribute('idNumber', $data);
        $this->assertObjectHasAttribute('idType', $data);
        $this->assertObjectHasAttribute('thirdPartyUserId', $data);
        $this->assertObjectHasAttribute('thirdPartyUserType', $data);
        $this->assertObjectHasAttribute('status', $data);
    }

    public function testDeleteOrganizeAccountByOrgId()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->account()
            ->deleteOrganizeAccountByOrgId(env('TEST_ESIGN_ORG_ID'));

        $this->assertEquals(0, $response->code);
    }

    public function testDeletePersonalAccountById()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->account()
            ->deletePersonalAccountById(env('TEST_ESIGN_ACCOUNT_ID'));

        $this->assertEquals(0, $response->code);
    }
}
