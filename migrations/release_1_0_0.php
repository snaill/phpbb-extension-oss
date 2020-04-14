<?php
/**
 *
 * @package       phpBB Extension - OSS
 * @copyright (c) 2020 snaill
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v3
 *
 */

namespace sharepai\oss\migrations;

class release_1_0_0 extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return [
			['config.add', ['oss_access_key_id', '']],
			['config.add', ['oss_secret_access_key', '']],
			['config.add', ['oss_endpoint', '']],
			['config.add', ['oss_bucket', '']],
			['config.add', ['oss_host', '']],
			['config.add', ['oss_is_enabled', 0]],
			[
				'module.add',
				[
					'acp',
					'ACP_CAT_DOT_MODS',
					'ACP_OSS_TITLE',
				],
			],
			[
				'module.add',
				[
					'acp',
					'ACP_OSS_TITLE',
					[
						'module_basename' => '\sharepai\oss\acp\main_module',
						'modes'           => ['settings'],
					],
				],
			],
		];
	}
}
