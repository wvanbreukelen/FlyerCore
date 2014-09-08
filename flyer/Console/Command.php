<?php

namespace Flyer\Components\Console;

class Command extends \Symfony\Component\Console\Command\Command
{

	protected $name;

	protected $desc;

	protected $action;

	public function run()
	{
		parent::__construct($this->getName());

		$this->setDescription($this->getDescription());
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setDescription($desc)
	{
		$this->desc = $desc;
	}

	public function setAction($action)
	{
		if (is_callable($action))
		{
			$action = call_user_func($action);
		}
		
		$this->action = $action;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getDescription()
	{
		return $this->desc;
	}

	public function getAction()
	{
		return $this->action;
	}

}