<?php
/**
 *
 * phpbbgallery-attachment. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Vladimir Surtsov, https://github.com/kisa47
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kisa47\phpbbgalleryattachment\event;

/**
 * @ignore
 */

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbbgallery\core\auth;
use phpbbgallery\core\config;


/**
 * phpbbgallery-attachment Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [
			'core.posting_modify_template_vars' => 'load_image_to_galary',
		];
		
	// 	return [
	// 		'core.user_setup'							=> 'load_language_on_setup',
	// 'core.display_forums_modify_template_vars'	=> 'display_forums_modify_template_vars',
	// 	];
	}

	/* @var \phpbb\language\language */
	protected $language;
	/* @var \phpbb\template\template */
	protected $template;
	/* @var \phpbbgallery\core\auth\auth  */
	protected $gallery_auth;

	/** @var ContainerInterface */
	protected $phpbb_container;
	/** @var \phpbbgallery\core\config */
	protected $gallery_config;

	// global $phpbb_container;
	/**
	 * Constructor
	 *
	 * @param \phpbb\language\language	$language	Language object
	 * @param \phpbb\template\template $template Template object
	 * @param auth\auth                         $gallery_auth
	 * @param ContainerInterface                     $phpbb_container
	 * @param \phpbbgallery\core\config         $gallery_config
	 */
	//public function __construct(\phpbb\language\language $language, \phpbb\template\template $template, \phpbbgallery\core\auth\auth $gallery_auth, ContainerInterface $phpbb_container)
	public function __construct(\phpbb\language\language $language, \phpbb\template\template $template, \phpbbgallery\core\auth\auth $gallery_auth, ContainerInterface $phpbb_container, \phpbbgallery\core\config $gallery_config,)
	{
		$this->language = $language;
		$this->template = $template;	
		$this->gallery_auth = $gallery_auth;
		$this->phpbb_container = $phpbb_container;
		$this->gallery_config = $gallery_config;
	}


	public function load_image_to_galary($event)
	{
		//$albums = $this->gallery_auth->acl_album_ids('a_list');
		//print($albums)
		// foreach ($albums as $VAR) 
		// {
		$gallery_album = $this->phpbb_container->get('phpbbgallery.core.album');
		$box = str_replace("select", 'select onchange="loadUpdateForm();"', $gallery_album->get_albumbox(false, 'album_id', false, 'i_upload', false, \phpbbgallery\core\block::PUBLIC_ALBUM, \phpbbgallery\core\block::TYPE_UPLOAD));
		$this->template->assign_vars(array(
			'S_SELECT_IMPORT' => $box,
			'S_MAX_FILESIZE'		=> get_formatted_filesize($this->gallery_config->get('max_filesize')),
			'S_MAX_WIDTH'			=> $this->gallery_config->get('max_width'),
			'S_MAX_HEIGHT'			=> $this->gallery_config->get('max_height'),
		));
		
	}
}
