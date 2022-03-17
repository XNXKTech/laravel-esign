# ![Laravel-Esign](https://socialify.git.ci/XNXKTech/laravel-esign/image?font=Bitter&language=1&logo=https://avatars.githubusercontent.com/u/94216091?s=200&v=4&owner=1&pattern=Circuit%20Board&theme=Light)

[![Tests](https://github.com/XNXKTech/laravel-esign/actions/workflows/tests.yml/badge.svg)](https://github.com/XNXKTech/laravel-esign/actions/workflows/tests.yml)
![PHP from Packagist](https://img.shields.io/packagist/php-v/xnxktech/laravel-esign?style=flat-square)
![Packagist Version](https://img.shields.io/packagist/v/xnxktech/laravel-esign?style=flat-square)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/xnxktech/laravel-esign?style=flat-square)
![GitHub last commit (branch)](https://img.shields.io/github/last-commit/xnxktech/laravel-esign/main?style=flat-square)
![GitHub Release Date](https://img.shields.io/github/release-date/xnxktech/laravel-esign?style=flat-square)
[![LICENSE](https://img.shields.io/badge/License-Anti%20996-blue.svg?style=flat-square)](https://github.com/996icu/996.ICU/blob/master/LICENSE)
[![LICENSE](https://img.shields.io/badge/License-Apache--2.0-green.svg?style=flat-square)](LICENSE-APACHE)
[![996.icu](https://img.shields.io/badge/Link-996.icu-red.svg?style=flat-square)](https://996.icu)

## Installation

```bash
$ composer require xnxktech/laravel-esign
```

## Configuration

generate config file

```bash
$ php artisan vendor:publish --provider="XNXK\LaravelEsign\ServiceProvider"
```

## Usage

```php
$service = esign();

// 签署方账户类
$accountService = $service->account;

// 创建个人签署账号
$thirdPartyUserId = 'your_party_user_id'; // 用户唯一标识
$name = 'your_name'; // 姓名
$idType = 'CRED_PSN_CH_IDCARD'; // 证件类型
$idNumber = 'your_id_number'; // 证件号
$mobile = 'your_mobile'; // 手机号, 签署流程开始时对应的签署人会收到短信通知

$accountResponse = $accountService->createPersonalAccount($thirdPartyUserId, $mobile, $name, $idNumber, $email, $idType);
$accountId = $accountResponse->data->accountId; // 签署方个人账号 ID

// 创建机构签署账号
$thirdPartyUserId = 'your_party_org_id'; // 机构唯一标识
$creatorAccountId = $accountId; // 需先创建法人个人签署账号
$name = 'your_org_name'; // 机构名称
$idType = 'CRED_ORG_USCC'; // 机构证件类型
$idNumber = 'your_id_number'; // 机构证件号，一般选择使用统一信用代码   
$orgLegalIdNumber = 'your_org_legal_id_number'; // 机构法人证件号
$orgLegalName = 'your_org_legal_name'; // 机构法人姓名

$accountResponse = $accountService->createOrganizeAccount($thirdPartyUserId, $creatorAccountId, $name, $idNumber, $orgLegalIdNumber, $orgLegalName, $idType);
$orgId = $accountResponse->data->orgId; // 签署方机构 ID

```
## License

The code in this repository, unless otherwise noted, is under the terms of both the [Anti 996](https://github.com/996icu/996.ICU/blob/master/LICENSE) License and the [Apache License (Version 2.0)]().