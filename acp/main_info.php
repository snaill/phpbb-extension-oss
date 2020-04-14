<?php
/**
 *
 * @package       phpBB Extension - OSS
 * @copyright (c) 2020 snaill
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v3
 *
 */

namespace sharepai\oss\acp;

class main_info
{
	function module()
	{
		return [
			'filename' => '\sharepai\oss\acp\main_module',
			'title'    => 'ACP_OSS_TITLE',
			'version'  => '1.0.0',
			'modes'    => [
				'settings' => [
					'title' => 'ACP_OSS',
					'auth'  => 'ext_sharepai/oss && acl_a_board',
					'cat'   => ['ACP_OSS_TITLE'],
				],
			],
		];
	}
}
