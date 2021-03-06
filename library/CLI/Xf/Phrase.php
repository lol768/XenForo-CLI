<?php

/**
 * XenForo CLI - Phrase command (ie. xf phrase)
 */
class CLI_Xf_Phrase extends CLI {
	
	/**
	 * @var string	Help text
	 */
	protected $_help = '
		Possible commands:
		
		(you can execute these commands with --help to view their instructions)
		
		xf phrase ..
			- add
			- find
			- get
	';
	
	public function run()
	{
		$this->showHelp();
	}
	
}