<?php

Namespace ImageProfessor;

Class Faculty{

	public $professors = [];

	public function __construct($basePath,$baseAddress){

		$this->basePath = $basePath;
		$this->baseAddress = $baseAddress;

	}

	public function addProfessor(Professor $professor){

		$this->professors[$professor->name] = $professor;

	}

}