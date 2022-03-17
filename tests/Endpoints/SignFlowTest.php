<?php

declare(strict_types=1);

namespace Tests\Endpoints;

use Tests\TestCase;
use Tests\TestHelpers;

class SignFlowTest extends TestCase
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

    public function testSetSignAuth()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->account()
            ->setSignAuth(
                env('TEST_ESIGN_ACCOUNT_ID')
            );

        $this->assertEquals(0, $response->code);
    }

    public function testUploadFile()
    {
        $filePath = dirname(__DIR__) . '/Public/test.pdf';
        $response = app(TestHelpers::class)
            ->esign()
            ->file()
            ->uploadFile(
                $filePath,
                basename($filePath),
                filesize($filePath),
                'application/pdf',
                false
            );

        $data = $response->data;

        $this->assertObjectHasAttribute('fileId', $data);
        $this->assertObjectHasAttribute('uploadUrl', $data);

        putenv('TEST_ESIGN_FILE_ID=' . $data->fileId);
        putenv('TEST_ESIGN_FILE_UPLOAD_URL=' . $data->uploadUrl);
    }

    public function testCreateSignFlow()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->signFlow()
            ->createSignFlow(
                '测试电子合同'
            );

        $data = $response->data;

        $this->assertObjectHasAttribute('flowId', $data);

        putenv('TEST_ESIGN_FLOW_ID=' . $data->flowId);
    }

    public function testAddDocuments()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->signFlow()
            ->addDocuments(
                env('TEST_ESIGN_FLOW_ID'),
                env('TEST_ESIGN_FILE_ID')
            );

        $this->assertEquals(0, $response->code);
    }

    public function testAddAutoSign()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->signFlow()
            ->addHandSign(
                env('TEST_ESIGN_FLOW_ID'),
                [
                    [
                        'fileId' => env('TEST_ESIGN_FILE_ID'),
                        'signerAccountId' => env('TEST_ESIGN_ACCOUNT_ID'),
                        'posBean' => [
                            'posPage' => '1',
                            'posX' => '100.00',
                            'posY' => '100.00',
                            'addSignTime' => true
                        ],
                        'thirdOrderNo' => 'd39f333c-9d1b-4786-a501-544ca8112361'
                    ]
                ]
            );

        $this->assertEquals(0, $response->code);
    }

    public function testAddPlatformSign()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->signFlow()
            ->addPlatformSign(
                env('TEST_ESIGN_FLOW_ID'),
                [
                    [
                        'fileId' => env('TEST_ESIGN_FILE_ID'),
                        'posBean' => [
                            'posPage' => '1',
                            'posX' => '100.00',
                            'posY' => '100.00',
                            'addSignTime' => true
                        ],
                        'thirdOrderNo' => 'd39f333c-9d1b-4786-a501-544ca8112361'
                    ]
                ]
            );

        $data = $response->data;
        
        $this->assertObjectHasAttribute('signfieldBeans', $data);
    }
    
    public function testStartSignFlow()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->signFlow()
            ->startSignFlow(
                env('TEST_ESIGN_FLOW_ID')
            );

        $this->assertEquals(0, $response->code);
    }
    
    public function testGetExecuteUrl()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->signFlow()
            ->getExecuteUrl(
                env('TEST_ESIGN_FLOW_ID'),
                env('TEST_ESIGN_ACCOUNT_ID')
            );

        $data = $response->data;

        $this->assertObjectHasAttribute('url', $data);
        $this->assertObjectHasAttribute('shortUrl', $data);
    }
    
    public function testGetSignFlowStatus()
    {

        $response = app(TestHelpers::class)
            ->esign()
            ->signFlow()
            ->getSignFlowStatus(
                env('TEST_ESIGN_FLOW_ID')
            );

        $data = $response->data;

        $this->assertObjectHasAttribute('processId', $data);
        $this->assertObjectHasAttribute('contractNo', $data);
        $this->assertObjectHasAttribute('flowId', $data);
        $this->assertObjectHasAttribute('appId', $data);
        $this->assertObjectHasAttribute('appName', $data);
        $this->assertObjectHasAttribute('autoArchive', $data);
        $this->assertObjectHasAttribute('flowStatus', $data);
        $this->assertObjectHasAttribute('flowDesc', $data);
        $this->assertObjectHasAttribute('flowStartTime', $data);
        $this->assertObjectHasAttribute('flowEndTime', $data);
        $this->assertObjectHasAttribute('businessScene', $data);
        $this->assertObjectHasAttribute('initiatorClient', $data);
        $this->assertObjectHasAttribute('initiatorAccountId', $data);
        $this->assertObjectHasAttribute('initiatorAuthorizedAccountId', $data);
        $this->assertObjectHasAttribute('payerAccountId', $data);
        $this->assertObjectHasAttribute('signValidity', $data);
        $this->assertObjectHasAttribute('contractValidity', $data);
        $this->assertObjectHasAttribute('contractEffective', $data);
        $this->assertObjectHasAttribute('contractRemind', $data);
        $this->assertObjectHasAttribute('configInfo', $data);
        $this->assertObjectHasAttribute('signPlatform', $data->configInfo);
        $this->assertObjectHasAttribute('noticeType', $data->configInfo);
        $this->assertObjectHasAttribute('noticeDeveloperUrl', $data->configInfo);
        $this->assertObjectHasAttribute('redirectUrl', $data->configInfo);
        $this->assertObjectHasAttribute('archiveLock', $data->configInfo);
    }
    
    public function testRevoke()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->signFlow()
            ->revoke(
                env('TEST_ESIGN_FLOW_ID')
            );

        $this->assertEquals(0, $response->code);
    }
}
