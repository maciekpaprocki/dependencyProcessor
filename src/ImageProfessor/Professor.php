<?php

Namespace ImageProfessor;



Class Professor{

	public static $declared = array();

	public $name;

	private $guardPath;

	private $transformFunction;

	private $cachePath;

	private $linkCreationFunction;

	public function __construct($name, $tranformFunction, $linkCreationFunction = null){
		$this->name = $name;
		$this->guardPath = $guardPath;
		$this->transformFunction = $transformFunction;
		$this->$cachePath = $cachePath;
		if($linkCreationFunction === null){
			$this->linkCreationFunction = function($path,$ob){
				return $ob->cachePath . '/' . $ob->name .'/' . $path;
			}
		}else{
			$this->linkCreationFunction = $linkCreationFunction;
		}
	}

	public function process($path = null){
		if($path!=null){

		}
	}
	
	
}