<?php

Namespace ImageProfessor;

Class Professor{

	public $name;

	public $transformFunction;

	public function __construct($name, $transformFunction, $transformClass = '\Imanee\Imanee'){

		$this->name = $name;
		$this->transformFunction = $transformFunction;
		$this->transformClass = $transformClass;
	}
	
}