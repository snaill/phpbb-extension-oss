<?php
/**
 *
 * @package       phpBB Extension - OSS
 * @copyright (c) 2020 snaill
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v3
 *
 */

namespace sharepai\oss\event;

use OSS\OssClient;
use OSS\Core\OssException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var $phpbb_root_path */
	protected $phpbb_root_path;

	/** @var OSSClient */
	protected $oss_client;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config     $config   Config object
	 * @param \phpbb\template\template $template Template object
	 * @param \phpbb\user              $user     User object
	 * @param                          $phpbb_root_path
	 *
	 * @access public
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, $phpbb_root_path)
	{
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;

		if ($this->config['oss_is_enabled'])
		{
			// Instantiate an OSS client.

			$key = $this->config['oss_access_key_id'];
			$secret = $this->config['oss_secret_access_key'];
			$endpoint = $this->config['oss_endpoint'];
			$this->oss_client =  new OssClient($key, $secret, $endpoint);
		}
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.user_setup'                               => 'user_setup',
			'core.modify_uploaded_file'                     => 'modify_uploaded_file',
			'core.delete_attachments_from_filesystem_after' => 'delete_attachments_from_filesystem_after',
			'core.parse_attachments_modify_template_data'   => 'parse_attachments_modify_template_data',
		];
	}

	public function user_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'sharepai/oss',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Event to modify uploaded file before submit to the post
	 *
	 * @param $event
	 */
	public function modify_uploaded_file($event)
	{
		if ($this->config['oss_is_enabled'])
		{
			$filedata = $event['filedata'];

			// Fullsize
			$key = $filedata['physical_filename'];
			$path = $this->phpbb_root_path . $this->config['upload_path'] . '/' . $key;
			$this->uploadFileToOSS($key, $path, $filedata['mimetype']);
		}
	}

	/**
	 * Perform additional actions after attachment(s) deletion from the filesystem
	 *
	 * @param $event
	 */
	public function delete_attachments_from_filesystem_after($event)
	{
		if ($this->config['oss_is_enabled'])
		{
			foreach ($event['physical'] as $physical_file)
			{
				$this->oss_client->deleteObject($this->config['oss_bucket'], $physical_file['filename']);
			}
		}
	}

	/**
	 * Use this event to modify the attachment template data.
	 *
	 * This event is triggered once per attachment.
	 *
	 * @param $event
	 */
	public function parse_attachments_modify_template_data($event)
	{
		if ($this->config['oss_is_enabled'])
		{
			$block_array = $event['block_array'];
			$attachment = $event['attachment'];

			$key = 'thumb_' . $attachment['physical_filename'];

			if (empty($this->config['oss_host'])) {
				$oss_link_thumb = '//' . $this->config['oss_bucket'] . '.' . $this->config['oss_endpoint'] . '/' . $key;
				$oss_link_fullsize = '//' . $this->config['oss_bucket'] . '.' . $this->config['oss_endpoint'] . '/' . $attachment['physical_filename'];
			} else {
				$oss_link_thumb = '//' . $this->config['oss_host'] . '/' . $key;
				$oss_link_fullsize = '//' . $this->config['oss_host'] . '/' . $attachment['physical_filename'];
			}
			$local_thumbnail = $this->phpbb_root_path . $this->config['upload_path'] . '/' . $key;

			if ($this->config['img_create_thumbnail'])
			{

				// Existence on local filesystem check. Just in case "Create thumbnail" was turned off at some point in the past and thumbnails weren't generated.
				if (file_exists($local_thumbnail))
				{

					// Existence on OSS check. Since this method runs on every page load, we don't want to upload the thumbnail multiple times.
					if (!$this->oss_client->doesObjectExist($this->config['oss_bucket'], $key))
					{
						// Upload *only* the thumbnail to OSS.
						$this->uploadFileToOSS($key, $local_thumbnail, $attachment['mimetype']);
					}
				}
				$block_array['THUMB_IMAGE'] = $oss_link_thumb;
				$block_array['U_DOWNLOAD_LINK'] = $oss_link_fullsize;
			}

			$block_array['U_INLINE_LINK'] = $oss_link_fullsize;
			$event['block_array'] = $block_array;
		}
	}

	/**
	 * Upload the attachment to the OSS bucket.
	 *
	 * @param $key
	 * @param $path
	 */
	private function uploadFileToOSS($key, $path, $content_type)
	{
		$bucket = $this->config['oss_bucket'];
		$options = [
			OssClient::OSS_HEADERS => [
				'Content-Type' => $content_type,
				'x-oss-object-acl' => 'public-read',
			],
		];
		$this->oss_client->uploadFile($bucket, $key, $path, $options);
	}
}
