<?php return array(
    'root' => array(
        'name' => '__root__',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => '70500f079bbdefd7f23034858fcf077e75fd6479',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        '__root__' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => '70500f079bbdefd7f23034858fcf077e75fd6479',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'monolog/monolog' => array(
            'pretty_version' => '3.5.0',
            'version' => '3.5.0.0',
            'reference' => 'c915e2634718dbc8a4a15c61b0e62e7a44e14448',
            'type' => 'library',
            'install_path' => __DIR__ . '/../monolog/monolog',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'psr/log' => array(
            'pretty_version' => '3.0.0',
            'version' => '3.0.0.0',
            'reference' => 'fe5ea303b0887d5caefd3d431c3e61ad47037001',
            'type' => 'library',
            'install_path' => __DIR__ . '/../psr/log',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'psr/log-implementation' => array(
            'dev_requirement' => false,
            'provided' => array(
                0 => '3.0.0',
            ),
        ),
        'workerman/workerman' => array(
            'pretty_version' => 'v4.1.15',
            'version' => '4.1.15.0',
            'reference' => 'afc8242fc769ab7cf22eb4ac22b97cb59d465e4e',
            'type' => 'library',
            'install_path' => __DIR__ . '/../workerman/workerman',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
