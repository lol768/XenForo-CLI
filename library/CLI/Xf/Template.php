<?php

/**
 * XenForo CLI - Template command (ie. xf template)
 */
class CLI_Xf_Template extends CLI {
	
	/**
	 * @var string	Help text
	 */
	protected $_help = '
		Possible commands:
		
		(you can execute these commands with --help to view their instructions)
		
		xf template ..
			- add
			- delete
	';
	
	public function run()
	{
		$this->showHelp();
	}
	
}