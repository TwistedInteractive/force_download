<?php
Class extension_force_download extends Extension
{
	// Set the delegates:
	public function getSubscribedDelegates()
	{
		return array(
			array(
				'delegate' => 'AddCustomPreferenceFieldsets',
				'page' => '/system/preferences/',
				'callback' => 'appendPreferences'
			),
			array(
				'page' => '/system/preferences/',
				'delegate' => 'Save',
				'callback' => 'savePreferences'
			)
		);
	}

	/**
	 * Append preferences to the preferences page
	 *
	 * @param $context
	 *  The context
	 */
	public function appendPreferences($context)
	{
		$group = new XMLElement('fieldset');
		$group->setAttribute('class', 'settings');
		$group->appendChild(new XMLElement('legend', __('Force Download')));

		$label = Widget::Label(__('Trusted Locations'));

		$locations = implode("\n", $this->getLocations());

		$label->appendChild(Widget::Textarea('force_download[trusted_locations]', 5, 50, $locations));

		$group->appendChild($label);

		$group->appendChild(new XMLElement('p', __('Relative from the root. Single path per line. Add * at end for wild card matching.'), array('class' => 'help')));

		$context['wrapper']->appendChild($group);
	}

	/**
	 * Returns an array of locations where force download is allowed to download from
	 *
	 * @return array
	 */
	public function getLocations()
	{
		$locations = unserialize(Symphony::Configuration()->get('trusted_locations', 'force_download'));
		if(is_array($locations))
		{
			return array_filter($locations);
		} else {
			return array();
		}
		
	}

	/**
	 * Save the preferences
	 *
	 * @param $context
	 */
	public function savePreferences($context)
	{
		if(isset($_POST['force_download']['trusted_locations']))
		{
			Symphony::Configuration()->set('trusted_locations', serialize(explode("\n", str_replace("\r", '', $_POST['force_download']['trusted_locations']))), 'force_download');
			Symphony::Configuration()->write();
		}
	}
}
