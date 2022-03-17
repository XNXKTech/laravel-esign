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
}
