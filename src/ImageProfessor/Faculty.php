<?php

Namespace ImageProfessor;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


Class Faculty{

	public $professors = [];

	public $extenstions = '/\.(jpg|JPG|jpeg|JPEG|gif|png)$/';

	public $basePath = '';

	public $baseAddress = '';

	public $baseCacheDestination = '';

	public $domain = '';

	public $linkCreator = null;

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
	public function process($professorName = null, $path = null){
		if($professorName === null){
			$this->processAll();
		}else{
			$professor = $this->getProfessor($professorName);
			if($path === null){
				
				$photos = $this->findAll();
				$arr = [];
				foreach($photos as $photo){
					$dest = $this->baseCacheDestination . '/' . $professor->name .'/'.$photo->getRelativePathname();
					$arr[] = $dest;
					$this->processSingleFile($photo->getRealPath(), $dest,$professor->transformFunction, $professor->transformClass);				
				}

			$this->lastProcessedFiles = $arr;
			}else{
				$photos = new Finder();
				
				$photos->files()->in($this->basePath);

				$pathBase = dirname($path);
				if($pathBase !=='.'){

					$photos->path($pathBase);
				}
			
			 	$photos->name(basename($path));
			
				
				foreach($photos as $photo){
					if(preg_match($this->extenstions,$photo->getRealPath())){
						$dest = $this->baseCacheDestination . '/' . $professor->name .'/'.$photo->getRelativePathname();
						$this->lastProcessedFiles = $dest;
						$this->processSingleFile($photo->getRealPath(), $dest,$professor->transformFunction, $professor->transformClass);		
					}else{
						return false;
					}
				}
			}
		}
		return $this;
	}

	public function processAll(){
	
		$photos = $this->findAll();

		$arr = [];

		foreach($this->professors as $professor){
			$arr[$professor->name] = [];
			foreach($photos as $photo){
				$dest = $this->baseCacheDestination . '/' . $professor->name .'/'.$photo->getRelativePathname();
				$arr[] = $dest;
				$this->processSingleFile($photo->getRealPath(), $dest,$professor->transformFunction, $professor->transformClass);
			}
		}
		$this->lastProcessedFiles = $arr;
		return $image;
		
	}
	public function findAll(){
		$photos = new Finder();
		return $photos->files()->in($this->basePath)->name($this->extenstions);
	}
	public function processSingleFile($path, $dest, $transformFunction, $transformClass){
		$image = new $transformClass($path);
		$image = $transformFunction($image);
		$dir = dirname($dest);
		if(!file_exists($dir)){
			mkdir($dir,0755,true);
		}

		$image->write($dest);
		$this->lastProcessedFiles = array($path);
		return $this;
	}
	public function getLastProcessed(){
		return $this->lastProcessedFiles;
	}
	public function getUrl($professorName,$filePath){

		$professor = $this->getProfessor($professorName);
		$func = $this->linkCreator;

		return $func($filePath,$this,$professor);
	}
	public function getProfessor($name){
		if(isset($this->professors[$name])){
			return $this->professors[$name];
		}else{
			return false;
		}
	}
	public function mount(){
		return '/' . $this->baseAddress .'/';
	}
}