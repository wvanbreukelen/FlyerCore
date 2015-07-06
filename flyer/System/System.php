<?php

namespace Flyer\Components;

use App;
use System\SystemPHP;

class System
{
	/**
	 * Construct a new system instance
	 * @param boolean $autoregister Autoregister all the systems
	 */
	public function __construct($autoregister = true)
	{
			if ($autoregister)
			{
					$this->registerSystems();
			}
	}

	/**
	 * Call the PHP system statically
	 * @return mixed Results
	 */
	public static function php()
	{
			// Users can create calls like System::php()->version();
			return $this->getSystem($id);
	}

	/**
	 * Get a specific system out of the app container
	 * @param  string $id System ID
	 * @return object     The resolved container object
	 */
	protected static function getSystem($id)
	{
			return App::access($id);
	}

	/**
	 * Register all systems to the application container
	 * @return mixed
	 */
	protected function registerSystems()
	{
			App::attach('system.php', new SystemPHP);
	}
}
