<?php

class User extends Eloquent
{
	public function setHash()
	{
		echo Hash::make('lol', array());
	}
}