<?php

namespace duncan3dc\SonosTests;

require __DIR__ . "/../vendor/autoload.php";

if (!empty($_ENV["SONOS_LIVE_TESTS"])) {
    echo "\nWARNING: These tests will make changes to the Sonos devices on the network:\n";

    $warnings = [
        "Queue contents will be changed",
        "Music will play",
        "Volume will be changed",
        "Playlists will be created",
        "Alarms will be created",
    ];
    foreach ($warnings as $warning) {
        echo "    * {$warning}\n";
    }

    $sleep = 5;
    echo "\nTests will run in " . $sleep . " seconds";
    for ($i = 0; $i < $sleep; $i++) {
        echo ".";
        sleep(1);
    }
    echo "\n";
}


# HHVM no longer has a built in webserver, so don't run these tests
$_ENV["SONOS_LOCAL_TESTS"] = true;
if (isset($_ENV["TRAVIS_PHP_VERSION"]) && $_ENV["TRAVIS_PHP_VERSION"] === "hhvm") {
    $_ENV["SONOS_LOCAL_TESTS"] = false;
}

# Start the internal server to act as a sonos system
if ($_ENV["SONOS_LOCAL_TESTS"]) {
    exec("php -S localhost:1400 " . __DIR__ . "/local/router.php >/dev/null 2>&1 & echo $!", $output, $status);
    $pid = (int) $output[0];

    # Ensure the internal web server is killed when the tests end
    register_shutdown_function(function () use ($pid) {
        exec("kill {$pid}");
    });
}
