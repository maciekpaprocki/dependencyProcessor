#Easy image processor microservice. 

Unfortunately most of the image processors built-in in standard cms's are not good enough for more advanced usecases. In cases like that you want to use microservice to show process your images in more gentle way. =

Instalation
-------------------

```bash
	composer install maciekpaprocki/imageProfessor

```

Be sure that you have imagemagick(preferred)||GD installed on your server. 


Usage
-------------------

Define image processor name, paths and transformation.

```php
	use ImageProfessor\ImageProfessor;

	var $thumbnail = New ImageProfessor('thumbnail','assets/*',function($image){
		
		return $image->thumbnail(200,200)
			->placeImage('thisisyourwatermarkimage.png');


	},'cache');

```

Because we are using Imanee package for image tranformation, you have whole array of possibilities. 

To get link to image just use:

```php
	echo $thumbnail->getUrl('path/to.jpg');

```

to process all images use (require app autoloader):

```php
	$thumbnail->process();

```

to process one image use (require app autoloader):

```php
	$thumbnail->process('path/to.jpg');

```

Future api 
---------------------
Bescause it's extremally annoying to each time get all variables for one file it's better to just use 