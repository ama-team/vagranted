<?php

use AmaTeam\Vagranted\Builder;
use AmaTeam\Vagranted\Filesystem\Helper;
use AmaTeam\Vagranted\Model\Configuration;

$root = Helper::getInstallationRoot();
$fixture = "$root/tests/Fixtures/Acceptance/1";
$project = "$fixture/_root";
$target = "$fixture/target";

$expectation = [
    '_root.rendered' => '_root: 0',
    '_root.asset' => '_root',
    'alpha.asset' => 'alpha',
    'alpha.rendered' => 'alpha: 1',
    'beta.asset' => 'beta',
    'beta.rendered' => 'beta: 2',
    'enumeration.rendered' => [
        '_root: 0',
        'alpha: 1',
        'beta: 2',
    ]
];

$configuration = (new Configuration())
    ->setProjectDirectory($project)
    ->setTargetDirectory($target)
    ->setWorkingDirectory($project);

$api = (new Builder())->withConfiguration($configuration)->build();
$api->getCompilationAPI()->compile();

$I = new AcceptanceTester($scenario);
foreach ($expectation as $path => $content) {
    $content = is_array($content) ? $content : [$content];
    $I->seeFileFound("$target/$path");
    $I->openFile("$target/$path");
    foreach ($content as $entry) {
        $I->seeInThisFile($entry);
    }
}
