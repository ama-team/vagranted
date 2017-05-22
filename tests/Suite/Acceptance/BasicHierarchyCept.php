<?php

use AmaTeam\Vagranted\Filesystem\Helper;

$root = Helper::getInstallationRoot();
$fixture = "$root/tests/Fixtures/1";
$project = "$fixture/_root";
$target = "$fixture/target";
$executable = "$root/bin/vagranted";

$command = "'$executable' compile --project '$project' --target '$target'";

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

$I = new AcceptanceTester($scenario);
$I->runShellCommand($command);
foreach ($expectation as $path => $content) {
    $content = is_array($content) ? $content : [$content];
    $I->seeFileFound("$target/$path");
    $I->openFile("$target/$path");
    foreach ($content as $entry) {
        $I->seeInThisFile($entry);
    }
}
