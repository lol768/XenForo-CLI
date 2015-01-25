<?php

/**
 * XenForo Command Line Interface class
 */
class CLI_Xf extends CLI
{

	protected $_versionString = "1.1f";
	protected $_versionId = 11;

	protected $_help = '
		Possible commands:
		
		(You can execute these commands with --help to view their instructions)

			<cg><b>Addons<cn>
				- addon
				- addon add
				- addon import
				- addon install
				- addon list
				- addon select
				- addon show
				- addon uninstall
				- addon export
			
			<cg><b>Code Events<cn>
				- extend
				- extend add
				- extend delete
				- listener add
				- listener delete
			
			<cg><b>Phrases<cn>
				- phrase add
				- phrase find
				- phrase get
			
			<cg><b>Templates<cn>
				- template add
			
			<cg><b>Routes<cn>
				- route add

			<cg><b>XenForo System<cn>
				- debug
				- debug toggle
	';
	
	/**
	 * Default run method
	 * 
	 * @return	void							
	 */
	public function run()
	{
		$class = 'CLI_' . ucfirst(strtolower($this->getArgumentAt(0)));

		// If a sub command is called, attempt to forward the call
		// this is to allow addon developers to create CLI command handlers
		// inside their XF install
		if ($class != __CLASS__ AND class_exists($class))
		{
			$arguments = $this->getArguments();
			array_shift($arguments);
			
			$callStructure 		= $this->_callStructure;
			$callStructure[] 	= $this;

			new $class($class, $arguments, $this->getFlags(), $this->getOptions(), $callStructure);
		}
		else
		{
			$this->printVersionInfo();
			$this->printHeading("Help");
			$this->showHelp();
		}
		
	}

	public function initialize()
	{
		parent::initialize();

		$this->loadConfig();
	}

	/**
	 * Loads the global and add-on (if applicable) flags and options from the configs
	 * 
	 * @return void 
	 */
	protected function loadConfig()
	{
		$config = XenForo_Application::getConfig();

		// We set any flags and options from the config, if already set it has priority so skip
		foreach ($config AS $option => $value)
		{
			if ($option == 'flags')
			{
				foreach ($value as $flag)
				{
					if ( ! $this->hasFlag($flag))
						$this->setFlag($flag);
				}

				continue;
			}

			if ( ! $this->hasOption($option))
			{
				$this->setOption($option, $value);
			}
		}
	}

	/**
	 * Loads the JSON config from a file into an array which it returns
	 * 
	 * @param  string $filepath 
	 * @return array           
	 */
	protected function loadConfigJson($filepath)
	{
		$config = file_get_contents($filepath);
		$config = json_decode($config, true);
		
		if ($config === null)
		{
			return array();
		}

		return $config;
	}

	/**
	 * Prints out the title of the tool and its version.
	 */
	protected function printVersionInfo() {
		// Why don't I just submit a PR instead of forking the tool? a) I don't think the project is active anymore,
		// b) Some of my changes might not be written well enough/deemed useful enough to include upstream
		$xfVer = XenForo_Application::$version;
		$this->printHeading("Version info");
		$this->printMessage("XenForo-CLI by Naatan and robclancy. Forked by lol768." . PHP_EOL . "Running CLI version " . $this->_versionString . " on XenForo $xfVer." . PHP_EOL);

	}

	/**
	 * Prints a pretty heading
	 * @param string $string Heading title
	 * @return void
	 */
	protected  function printHeading($string)
	{
		$this->printMessage($this->colorText($string, self::BOLD));

		$print = '';
		for ($c = 0; $c < strlen($string); $c++)
		{
			$print .= '=';
		}
		$this->printMessage($print);
	}
}