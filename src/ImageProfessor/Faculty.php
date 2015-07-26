<?php

Namespace ImageProfessor;

use Symfony\Component\Finder\Finder;

Class Faculty{

	public $professors = [];

	public function __construct($basePath, $baseAddress, $baseCacheDestination, $domain = '/', $linkCreator = null){

		$this->basePath = $basePath;
		$this->baseAddress = $baseAddress;
		$this->baseCacheDestination = $baseCacheDestination;
		$this->domain = $domain;
		if($linkCreator != null){
			$this->linkCreator = $linkCreator;
		}else{
			$this->linkCreator = function($filePath, $faculty, $professor){
				return $faculty->domain . $faculty->baseAddress . '/' . $professor->name . '/' . $filePath;
			};
		}

	}

	public function addProfessor(Professor $professor){

		$this->professors[$professor->name] = $professor;

	}
	public function process($path = null){
		if($path==null){
			$photos = new Finder();
			$photos->in($this->basePath)->name('/\.(jpg|JPG|jpeg|JPEG|gif|png)$/');

			$arr = [];

			foreach($this->professors as $professor){
				$arr[$professor->name] = [];
				foreach($photos as $photo){
					$dest = $this->baseCacheDestination . '/' . $professor->name .'/'.$photo->getRelativePathname();
					$arr[$professor->name][] = $this->processSingleFile($photo->getRealPath(), $dest,$professor->transformFunction, $professor->transformClass);
					
				}
			}
			return $arr;
		}
	}
	private function processSingleFile($path, $dest, $transformFunction, $transformClass){
		$image = new $transformClass($path);
		$image = $transformFunction($image);
		$dir = dirname($dest);
		if(!file_exists($dir)){
			mkdir($dir,0755,true);
		}
		$image->write($dest);
		return $dest;
	}
	public function getUrl($professorName,$filePath){

		$professor = $this->getProfessor($professorName);
		$func = $this->linkCreator;

		return $func($filePath,$this,$professor);
	}
	public function getProfessor($name){

		return $this->professors[$name];
	}
}