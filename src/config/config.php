<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary' => '/usr/local/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => array(),
		'tempPath' => base_path().'/app/storage/pdftemp'
    ),
    'image' => array(
        'enabled' => true,
        'binary' => '/usr/local/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => array(),
    ),


);
