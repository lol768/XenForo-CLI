<?php

/**
 * XenForo CLI - System  command (ie. xf debug)
 */
class CLI_Xf_Debug extends CLI {
	
	/**
	 * @var string	Help text
	 */
	protected $_help = '
		Possible commands:
		
		(you can execute these commands with --help to view their instructions)
		
		xf debug ..
			- toggle
	';
	
	public function run()
	{
		$debugMode = XenForo_Application::debugMode();
		if ($debugMode)
		{
			$this->printMessage("Debug mode is enabled.");
		}
		else
		{
			$this->printMessage("Debug mode is disabled.");
		}
	}
	
}