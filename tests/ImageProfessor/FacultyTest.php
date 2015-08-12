<?php

namespace ImageProfessor\Tests;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class FacultyTest extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->fs = new Filesystem();

        include dirname(dirname(__DIR__)).'/example.php';

        $this->math = $math;
        $this->chemistry = $chemistry;
    }
    public function testProcessAll()
    {
        $ext = '/\.(jpg|JPG|jpeg|JPEG|gif|png)$/';
        $this->fs->remove($this->math->baseCacheDestination);
        $this->fs->remove($this->chemistry->baseCacheDestination);

        $finderMath1 = new Finder();
        $finderMath1->in($this->math->basePath)->name($ext);

        $finderChemistry1 = new Finder();
        $finderChemistry1->in($this->chemistry->basePath)->name($ext);

        $resMath = $this->math->process()->getLastProcessed();
        $resChemistry = $this->chemistry->process()->getLastProcessed();

        $finderMath2 = new Finder();
        $finderMath2->in($this->math->baseCacheDestination)->name($ext);
        $finderChemistry2 = new Finder();
        $finderChemistry2->in($this->chemistry->baseCacheDestination)->name($ext);

        $resMathFlatten = $this->_flattenArray($resMath);
        $resChemistryFlatten = $this->_flattenArray($resChemistry);

        $this->assertEquals(
            count($finderMath1) * count($this->math->professors)/* As there are two processors */,
            count($finderMath2)
        );
        $this->assertEquals(
            count($finderChemistry1) /* As there are two processors */,
            count($finderChemistry2)
        );

        $this->assertEquals(count($finderMath2), count($resMathFlatten));
        $this->assertEquals(count($finderChemistry2), count($resChemistryFlatten));
    }
    public function testProcessOneProfessor()
    {
        $ext = '/\.(jpg|JPG|jpeg|JPEG|gif|png)$/';
        $this->fs->remove($this->math->baseCacheDestination);
        $this->fs->remove($this->chemistry->baseCacheDestination);

        $finderMath1 = new Finder();
        $finderMath1->in($this->math->basePath)->name($ext);

        $finderChemistry1 = new Finder();
        $finderChemistry1->in($this->chemistry->basePath)->name($ext);

        $resMath = $this->math->process('small')->getLastProcessed();
        $resChemistry = $this->chemistry->process('small')->getLastProcessed();

        $finderMath2 = new Finder();
        $finderMath2->in($this->math->baseCacheDestination)->name($ext);
        $finderChemistry2 = new Finder();
        $finderChemistry2->in($this->chemistry->baseCacheDestination)->name($ext);

        $resMathFlatten = $this->_flattenArray($resMath);
        $resChemistryFlatten = $this->_flattenArray($resChemistry);

        $this->assertEquals(
            count($finderMath1)/* As there are two processors */,
            count($finderMath2)
        );
        $this->assertEquals(
            count($finderChemistry1) /* As there are two processors */,
            count($finderChemistry2)
        );

        $this->assertEquals(count($finderMath2), count($resMathFlatten));
        $this->assertEquals(count($finderChemistry2), count($resChemistryFlatten));
    }
    public function testProcessOneImageAndProcessor()
    {
        $ext = '/\.(jpg|JPG|jpeg|JPEG|gif|png)$/';
        $this->fs->remove($this->math->baseCacheDestination);
        $this->fs->remove($this->chemistry->baseCacheDestination);

        $finderMath1 = new Finder();
        $finderMath1->files()->in($this->math->basePath)->name('mona_lisa.png');

        $finderChemistry1 = new Finder();
        $finderChemistry1->files()->in($this->chemistry->basePath)->name('mona_lisa.png');

        $resMath = $this->math->process('small', 'mona_lisa.png');
        $resChemistry = $this->chemistry->process('small', 'mona_lisa.png');

        $finderMath2 = new Finder();
        $finderMath2->files()
            ->in($this->math->baseCacheDestination)
            ->path('small')
            ->name('mona_lisa.png');
        $finderChemistry2 = new Finder();
        $finderChemistry2->files()
            ->in($this->chemistry->baseCacheDestination)
            ->path('small')
            ->name('mona_lisa.png');

        $this->assertEquals(
            count($finderMath1)/* As there are two processors */,
            count($finderMath2)
        );
        $this->assertEquals(
            count($finderChemistry1) /* As there are two processors */,
            count($finderChemistry2)
        );
    }

    public function _flattenArray($array)
    {
        $return = array();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $return = array_merge($return, $this->_flattenArray($value));
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }
}
