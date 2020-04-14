<?php
/**
 *
 * @package       phpBB Extension - OSS
 * @copyright (c) 2020 snaill
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v3
 *
 */

namespace sharepai\oss\acp;
use OSS\OssClient;
use OSS\Core\OssException;

class main_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $config, $request, $template, $user;

		$user->add_lang('acp/common');
		$this->tpl_name = 'oss_body';
		$this->page_title = $user->lang('ACP_OSS_TITLE');
		add_form_key('sharepai\oss');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('sharepai\oss'))
			{
				trigger_error('FORM_INVALID');
			}

			$errors = [];
			if (empty($request->variable('oss_access_key_id', '')))
			{
				$errors[] = $user->lang('ACP_OSS_ACCESS_KEY_ID_INVALID', $request->variable('oss_access_key_id', ''));
			}

			if (empty($request->variable('oss_secret_access_key', '')))
			{
				$errors[] = $user->lang('ACP_OSS_SECRET_ACCESS_KEY_INVALID', $request->variable('oss_secret_access_key', ''));
			}

			if (empty($request->variable('oss_endpoint', '')))
			{
				$errors[] = $user->lang('ACP_OSS_ENDPOINT_INVALID');
			}

			if (empty($request->variable('oss_bucket', '')))
			{
				$errors[] = $user->lang('ACP_OSS_BUCKET_INVALID');
			}

			// If we have no errors so far, let's ensure our AWS credentials are actually working.
			if (!count($errors))
			{
				try
				{
					// Instantiate an OSS client.
					$key    = $request->variable('oss_access_key_id', '');
					$secret = $request->variable('oss_secret_access_key', '');
					$endpoint = $request->variable('oss_endpoint', '');
					$oss_client = new OssClient($key, $secret, $endpoint);

					// Upload a test file to ensure credentials are valid and everything is working properly.
					$oss_client->putObject($request->variable('oss_bucket', ''), 'test.txt', 'test body');

					// Delete the test file.
					$oss_client->deleteObject($request->variable('oss_bucket', ''), 'test.txt');
				}
				catch (OssException $e)
				{
					$errors[] = $e->getMessage();
				}
			}

			// If we still don't have any errors, it is time to set the database config values.
			if (!count($errors))
			{
				$config->set('oss_access_key_id', $request->variable('oss_access_key_id', ''));
				$config->set('oss_secret_access_key', $request->variable('oss_secret_access_key', ''));
				$config->set('oss_endpoint', $request->variable('oss_endpoint', ''));
				$config->set('oss_bucket', $request->variable('oss_bucket', ''));
				$config->set('oss_host', $request->variable('oss_host', ''));
				$config->set('oss_is_enabled', 1);

				trigger_error($user->lang('ACP_OSS_SETTING_SAVED') . adm_back_link($this->u_action));
			}
		}

		$template->assign_vars([
			'U_ACTION'              => $this->u_action,
			'OSS_ERROR'             => isset($errors) ? ((count($errors)) ? implode('<br /><br />', $errors) : '') : '',
			'OSS_ACCESS_KEY_ID'     => $config['oss_access_key_id'],
			'OSS_SECRET_ACCESS_KEY' => $config['oss_secret_access_key'],
			'OSS_ENDPOINT'          => $config['oss_endpoint'],
			'OSS_BUCKET'            => $config['oss_bucket'],
			'OSS_HOST'            	=> $config['oss_host'],
			'OSS_IS_ENABLED'        => ($config['oss_is_enabled']) ? 'Enabled' : 'Disabled',
		]);
	}
}
