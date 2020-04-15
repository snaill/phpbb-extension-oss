<?php
/**
 *
 * @package       phpBB Extension - OSS
 * @copyright (c) 2020 snaill
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v3
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	'ACP_OSS'               => '设置',
	'ACP_OSS_SETTING_SAVED' => '设置保存成功！',

	'ACP_OSS_ACCESS_KEYS_EXPLAIN' => '您需要提供有效的<strong>access keys</strong>以调用OSS API，查询阿里云OSS文档了解详情。',

	'ACP_OSS_ACCESS_KEY_ID'         => 'OSS Access Key Id',
	'ACP_OSS_ACCESS_KEY_ID_EXPLAIN' => '输入您的OSS Access Key Id。',
	'ACP_OSS_ACCESS_KEY_ID_INVALID' => '“%s”不是一个有效的OSS Access Key Id。',

	'ACP_OSS_SECRET_ACCESS_KEY'         => 'OSS Secret Access Key',
	'ACP_OSS_SECRET_ACCESS_KEY_EXPLAIN' => '输入您的OSS Secret Access Key。',
	'ACP_OSS_SECRET_ACCESS_KEY_INVALID' => '“%s”不是一个有效的OSS Secret Access Key。',

	'ACP_OSS_ENDPOINT'         => 'OSS Endpoint',
	'ACP_OSS_ENDPOINT_EXPLAIN' => '请输入OSS Endpoint。',
	'ACP_OSS_ENDPOINT_INVALID' => '您必须输入一个有效的OSS Endpoint',

	'ACP_OSS_BUCKET'         => 'OSS Bucket',
	'ACP_OSS_BUCKET_EXPLAIN' => '请输入您的OSS Bucket。',
	'ACP_OSS_BUCKET_INVALID' => '您必须输入一个有效的OSS Bucket。',

	'ACP_OSS_HOST'         => 'OSS关联域名（选填）',
	'ACP_OSS_HOST_EXPLAIN' => '设置OSS关联的域名，如http://oss.xxxhost.com，文件路径会使用该域名显示。',
	'ACP_OSS_HOST_INVALID' => '您必须输入一个有效的域名，与网页同协议可以//开头。',

	'ACP_OSS_IS_ENABLED'         => '是否有效?',
	'ACP_OSS_IS_ENABLED_EXPLAIN' => '显示Enabled，表示文件会上传到OSS。',
]);
