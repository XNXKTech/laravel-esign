<?php

declare(strict_types=1);

namespace Tests\Endpoints;

use Tests\TestCase;
use Tests\TestHelpers;

class FileTest extends TestCase
{
    public function testUploadFile()
    {
        $filePath = dirname(__DIR__).'/Public/test.pdf';
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

        putenv('TEST_ESIGN_FILE_ID='.$data->fileId);
        putenv('TEST_ESIGN_FILE_UPLOAD_URL='.$data->uploadUrl);
    }

    public function testDownloadFile()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->file()
            ->downloadFile(env('TEST_ESIGN_FILE_ID'));

        $data = $response->data;

        $this->assertObjectHasAttribute('fileId', $data);
        $this->assertObjectHasAttribute('name', $data);
        $this->assertObjectHasAttribute('downloadUrl', $data);
        $this->assertObjectHasAttribute('size', $data);
        $this->assertObjectHasAttribute('status', $data);
        $this->assertObjectHasAttribute('pdfTotalPages', $data);
    }

    public function testUploadFileByDocTemplates()
    {
        $filePath = dirname(__DIR__).'/Public/test.pdf';
        $response = app(TestHelpers::class)
            ->esign()
            ->file()
            ->uploadFileByDocTemplates(
                $filePath,
                basename($filePath),
                'application/pdf',
                false
            );

        $data = $response->data;

        $this->assertObjectHasAttribute('templateId', $data);
        $this->assertObjectHasAttribute('uploadUrl', $data);
        
        putenv('TEST_ESIGN_FILE_TEMPLATE_ID='.$data->templateId);
        putenv('TEST_ESIGN_FILE_TEMPLATE_UPLOAD_URL='.$data->uploadUrl);
    }
    
    public function testDownloadFileByDocTemplate()
    {
        $response = app(TestHelpers::class)
            ->esign()
            ->file()
            ->downloadFileByDocTemplate(env('TEST_ESIGN_FILE_TEMPLATE_ID'));

        $data = $response->data;

        $this->assertObjectHasAttribute('templateId', $data);
        $this->assertObjectHasAttribute('fileKey', $data);
        $this->assertObjectHasAttribute('downloadUrl', $data);
        $this->assertObjectHasAttribute('fileSize', $data);
        $this->assertObjectHasAttribute('templateName', $data);
        $this->assertObjectHasAttribute('templateType', $data);
        $this->assertObjectHasAttribute('createTime', $data);
        $this->assertObjectHasAttribute('updateTime', $data);
        $this->assertObjectHasAttribute('structComponents', $data);
    }
}
