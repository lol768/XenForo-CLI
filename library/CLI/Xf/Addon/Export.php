<?php

class CLI_Xf_Addon_Export extends CLI
{
	protected $_help = '
		Simply exports an addon like you would from the ACP.

		usage: addon export path --addon-id=id

		path
			Where the xml will go, either path/to/location or path/to/location/and_name.xml

		--addon-id
			if specified will export the addon with this id, otherwise will use whatever addon is selected
	';

	public function run($path)
	{
		$addonId = $this->getOption('addon-id');
		if ( ! $addonId)
		{
			$addonId = XfCli_Application::getConfig()->addon->id;
			if ( ! $addonId)
			{
				$this->bail('There is no addon selected and the --addon-id is not set');
			}
		}

		$addonModel = XenForo_Model::create('XenForo_Model_AddOn');
		/** @var XenForo_Model_AddOn $addonModel */
		$addon = $addonModel->getAddonById($addonId);
		if ( ! $addon)
		{
			$this->bail('No addon exists with that ID (' . $addonId . ')');
		}

		$xml = $addonModel->getAddOnXml($addon)->saveXml();

		if (!file_exists($path))
		{
			$this->printInfo($this->colorText('Warning! Specified path does not exist, attempting to create directories...', parent::YELLOW));
			try
			{
				mkdir($path, 0777, true);
			}
			catch (Exception $e)
			{
				$this->printInfo($this->colorText('Directories could not be created for ' . $path . PHP_EOL . "Check permissions and try again.", parent::RED));
			}
		}

		if (XenForo_Helper_File::getFileExtension($path) != 'xml')
		{
			if (substr($path, strlen($path)-1) != DIRECTORY_SEPARATOR)
			{
				$path .= DIRECTORY_SEPARATOR . 'addon-' . $addon['addon_id'] . '.xml';
			}
			else
			{
				$path .= 'addon-' . $addon['addon_id'] . '.xml';
			}

		}


		try
		{
			file_put_contents($path, $xml);
			$this->printInfo($this->colorText('File written to ' . $path, parent::GREEN));
		}
		catch (Exception $e)
		{
			$this->printInfo($this->colorText('File could not be written to ' . $path . PHP_EOL . "Check permissions and try again.", parent::RED));
		}

	}
}