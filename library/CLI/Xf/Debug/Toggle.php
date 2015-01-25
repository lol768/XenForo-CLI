<?php

/**
 * Toggle debug mode
 */
class CLI_Xf_Debug_Toggle extends CLI
{

	/**
	 * Run the command
	 *
	 * @return	void
	 */
	public function run()
	{
		$debugMode = XenForo_Application::debugMode();
		$basePath = XfCli_Application::xfBaseDir() . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR;
		$configPath = $basePath . DIRECTORY_SEPARATOR . "config.php";

		if ($debugMode)
		{
			$config = file($configPath);
			$this->printMessage("Debug mode is enabled. Removing relevant line from config file..");
			$config = array_filter($config, function($line)
			{
				return (preg_match('/\$config\[\'debug\'\]/', $line) !== 1);
			});
			try
			{
				file_put_contents($configPath, implode("", $config));
				$this->printMessage($this->colorText("Debug mode is now disabled!", parent::YELLOW));
			}
			catch (Exception $e)
			{
				$this->printWriteError();
			}
		}
		else
		{
			$this->printMessage("Debug mode is disabled. Adding line to config file...");
			try
			{
				file_put_contents($configPath, PHP_EOL . '$config[\'debug\'] = 1;', FILE_APPEND);
				$this->printMessage($this->colorText("Debug mode is now enabled!", parent::GREEN));
			}
			catch (Exception $e)
			{
				$this->printWriteError();
			}
		}
	}

	private function printWriteError()
	{
		$this->printMessage($this->colorText("Failed to write to config file! Check permissions and try again.", parent::RED));
	}

}
