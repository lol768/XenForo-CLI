<?php
/**
 * Import an addon from a folder path or repo
 */
class CLI_Xf_Addon_Import extends CLI
{
	protected $_help = '
Use this to import an add-on quickly from a repo or folder. It has to have the right structure, upload folder and addon-*.xml file. It will be either copied over or symlinked depending on the --dont-use-symlinks option.

usage: 	addon import folderPath|gitRepoUrl|hgRepoUrl 
	[--path-for-repo=path] 
	[--dont-use-symlinks]

	--path-for-repo
		The folder for the repo if importing for one. Defaults to /repos/reponame

	--dont-use-symlinks
		This will do a hard copy instead of symlinking the repo
';

	/**
	 * Run the command
	 * 
	 * @param	string 	$addonId
	 * 
	 * @return	void							
	 */
	public function run($path)
	{
		// TODO: make this test looks for protocols instead of just ://
		if (strpos($path, '://') !== false)
		{
			// The following might not be correct for all test cases, but we will give it a go
			if (strpos(trim(shell_exec('git ls-remote ' . $path)), 'fatal:') !== 0)
			{
				$path = $this->_cloneGit($path);
			}
			else if (strpos(trim(shell_exec('hg identify ' . $path)), 'abort:') !== 0)
			{
				$path = $this->_cloneHg($path);
			}
			else
			{
				$this->bail('No valid folder path, git repo URL or hg repo URL was provided: ' . $path);
			}
		}

		$this->_import($path);
	}

	protected function _import($path)
	{
		$this->_assertCanImport($path);

		$this->printInfo('Importing addon source from ' . $path . '...');

		list ($xml) = glob($path . DIRECTORY_SEPARATOR . '*.xml');
		if (is_dir($path . 'upload'))
		{
			$path = $path . 'upload';
		}

		$this->_importFolder($path);
		$this->manualRun('addon install ' . $xml, false, false, false);
	}

	protected function _importFolder($path, $rootPath = null)
	{
		if ($rootPath === null)
		{
			$rootPath = $path;
		}

		$dir = new DirectoryIterator($path);
		$base = XfCli_Application::xfBaseDir();
		foreach ($dir as $obj)
		{
			if ($obj->isDot() OR 
				$obj->getFilename() == '.git' OR 
				$obj->getFilename() == '.hg' OR
				strpos(strtolower($obj->getFilename()), 'readme') !== false OR
				strpos(strtolower($obj->getFilename()), 'license') !== false
			)
			{
				continue;
			}

			$xfEquivalent = str_replace($rootPath . DIRECTORY_SEPARATOR, $base, $obj->getPathname());

			if ($obj->isDir() AND is_dir($xfEquivalent))
			{
				$this->_importFolder($obj->getPathname(), $rootPath);
				continue;
			}
			else if ($obj->isFile() AND file_exists($xfEquivalent))
			{
				// TODO: rollback and update support
				$this->bail('File already exists in your XenForo install: ' . $xfEquivalent);
			}

			if ( ! $this->hasFlag('dont-use-symlinks'))
			{
				shell_exec('ln -s ' . $obj->getPathname() . ' ' . $xfEquivalent);
			}

			// TODO: hard copy

			// TODO: log what we just did so we can do a xf addon remove
		}
	}

	protected function _assertCanImport($path)
	{
		// Need one xml file only
		if (count(glob($path . DIRECTORY_SEPARATOR . '*.xml')) !== 1)
		{
			// TODO: add option to sepecify xml install file
			$this->bail('Didn\'t detect a single XML file.. addon not compatible with this command');
		}

		// If we want to be more strict and force an upload folder then do it here
	}

	protected function _cloneGit($url)
	{
		$path = $this->_getRepoPath($url);

		$this->printInfo('Cloning git repository ' . $url . ' into ' . $path . '...');

		// TODO: error checking
		shell_exec('git clone ' . $url . ' ' . $path);

		if ( ! is_dir($path . DIRECTORY_SEPARATOR . '.git'))
		{
			$this->bail('Failed to clone git repository: ' . $url);
		}

		return $path;
	}

	protected function _cloneHg($url)
	{

	}

	protected function _getRepoPath($url)
	{
		$path = $this->getOption('path-for-repo');
		if ( ! $path)
		{
			$folder = strrchr($url, '/');
			$folder = substr($folder, 1, strpos($folder, '.') - 1);
			$path = XfCli_Application::xfBaseDir() . 'repos' . DIRECTORY_SEPARATOR . $folder;
			// TODO: recurse back to make previous directories if they don't exist
			if ( ! is_dir($path))
			{
				if ( ! mkdir($path, 0755, true))
				{
					$this->bail('Could not create repo directory: ' . $path);
				}
			}
		}

		return $path;
	}
}