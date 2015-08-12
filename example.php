<?php
    use ImageProfessor\Faculty;
    use ImageProfessor\Professor;
    $baseDir = __DIR__; 
            /* defining groups/filepaths */
    $math = new Faculty(
         $baseDir.'/tests/images/math', /* Base Path */
        'tests/cache/math', /*Return Address Base*/
        $baseDir.'/tests/cache/math', /* Cache Base */
        'http://n1.foo.bar', /* Domain (optional) */
        function($filePath, $faculty, $professor){
            return $faculty->domain. '/' . $faculty->baseAddress . '/' . $professor->name . '/' . $filePath;
        }/*Link creation function*/
    );

    $chemistry = new Faculty(
             $baseDir.'/tests/images/chemistry', /*Base Path*/
            'tests/cache/chemistry', /*Base Address */
            $baseDir.'/tests/cache/chemistry' /*Base Path*/
    );

        /* defining image processors */
    $small = new Professor(/*Name*/'small',function($image){
            
        return $image->resize(200,200);
    });

    $big = new Professor('big',function($image){

            return $image->resize(800,800);
    });

        /* assigning Processors to $group/Filepaths */
    $math->addProfessor($small);

    $math->addProfessor($big);

    $chemistry->addProfessor($small);