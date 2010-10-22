<?php
Class extension_force_download extends Extension
{
	// About this extension:
	public function about()
	{
		return array(
			'name' => 'Force Download',
			'version' => '1.1',
			'release-date' => '2010-10-22',
			'author' => array(
				'name' => 'Giel Berkers',
				'website' => 'http://www.gielberkers.com',
				'email' => 'info@gielberkers.com'),
			'description' => 'Provides a force download-event'
		);
	}
	
	// Set the delegates:
	public function getSubscribedDelegates()
	{
		return array();
	}
}
?>