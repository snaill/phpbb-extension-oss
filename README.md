# phpBB 3.3 阿里云OSS扩展

## Description

本项目利用阿里云OSS保存[phpBB](https://www.phpbb.com)上传的附件，并以阿里云OSS来展示。

- 目前测试过的版本只有phpBBv3.3
- 支持附件同步上传到OSS
- 支持附件同步删除
- 支持附件使用OSS访问
- 不支持头像同步到OSS

## 安装

Clone into phpBB/ext/sharepai/oss:

    git clone https://github.com/snaill/phpbb-extension-oss.git phpBB/ext/sharepai/oss

Set up the dependencies:

    php composer.phar install --dev

Go to "ACP" > "Customise" > "Extensions" and enable the "Aliyun OSS" extension.

## 特别说明

本项目基于[Austin Maddox](https://github.com/AustinMaddox/)的[AustinMaddox/s3](https://github.com/AustinMaddox/phpbb-extension-s3)扩展v1.0.3修改而成。

## License

[GPLv3](LICENSE)