#! /usr/bin/env php

<?php

/**
 *
 * Multiple processes run prototype.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 *
 */

$start = time();

// Function for building php commands
$buildCommand = function ($cmd) {
    return sprintf(
        'exec %s -r "%s" &',
        PHP_BINARY,
        stripslashes($cmd)
    );
};

// Get process key by stream
$getProcessKey = function ($stream, array $streamCollection)
{
    if (
        is_resource($stream)
        && get_resource_type($stream) === 'stream'
        && $streamCollection
    ) {
        foreach ($streamCollection as $key => $streamSuite) {
            if (in_array($stream, $streamSuite)) {
                return $key;
            }
        }
    }

    return null;
};

// Error or success (1 for stdout, 2 for stderr)
//
$getFailedProcessKey = function ($stream, array $streamCollection) use ($getProcessKey) {
    $processKey = $getProcessKey($stream, $streamCollection);
    return 2 == array_search($stream, $streamCollection[$processKey]) ? $processKey : null;
};

// Commands
$cmds = [
    "throw new Exception('fooo');",
    "echo 'foo\n';sleep(1); echo 'bar\n;",
    "echo 'foo\n';sleep(1); echo 'bar\n';",
    "sleep(1); echo '33\n'; sleep(1); echo 33.5, PHP_EOL;\n",
    "sleep(2); echo 34, PHP_EOL;\n",
    "sleep(3); echo 35, PHP_EOL;\n",
    "sleep(10); echo 135, PHP_EOL;\n",
];

// Descriptors
$descriptors = [
    ['pipe', 'r'], // Process stdin
    ['pipe', 'w'], // Process stdout
    ['pipe', 'w'], // Process stderr
];

// #1 Execute processes one by one
//
$processCollection = $streamCollection = [];
foreach ($cmds as $key => $cmd) {
    $process = proc_open(
        $buildCommand($cmd),
        $descriptors,
        $pipes
    );

    $processCollection[$key] = $process;
    $streamCollection[$key] = $pipes;
}

// #2 Run system select to get process output
//
$processOutputs = [];
do {

    // #2.1 Build read streams
    //
    $readStreams = $closedProcesses = $failedProcesses = [];
    $writeStreams = $errStreams = null;
    foreach ($streamCollection as $streamSuiteKey => $streamSuite) {
        $streamIn = $streamSuite[0];
        $streamOut = $streamSuite[1];
        $streamErr = $streamSuite[2];
        $readStreams[] = $streamOut;
        $readStreams[] = $streamErr;
    }

    // #2.2 Run system select()
    //
    if (isset($readStreams[0])) {
        $num = stream_select($readStreams, $writeStreams, $errStreams, 0, 500);
        if ($num !== false && $num > 0) {


            foreach ($readStreams as $readStream) {

                $line = fgets($readStream);
                if (false !== $line) {
                    $processKey = $getProcessKey($readStream, $streamCollection);
                    $line = trim ($line);
                    $processOutputs[$processKey][] = $line;
                    if (null !== $processKey = $getFailedProcessKey($readStream, $streamCollection)) {
                        $failedProcesses[$processKey] = 1;
                        $closedProcesses[] = $processKey;
                    }
                } else {
                    $processKey = $getProcessKey($readStream, $streamCollection);
                    $closedProcesses[] = $processKey;
                }

            }

        } else {
            usleep(500);
//            echo 'Empty output';
//            die();
            // ... Exception
        }
    }

    // #2.3 Close processes and process streams
    //
    foreach ($closedProcesses as $processKey) {
        // Close all streams
        if (isset($streamCollection[$processKey])) {
            foreach ($streamCollection[$processKey] as $stream) {
                if (is_resource($stream) && get_resource_type($stream) === 'stream') {
                    fclose($stream);
                }
            }
            unset($streamCollection[$processKey]);
        }

        // Close process
        if (isset($processCollection[$processKey])) {
            if (is_resource($processCollection[$processKey])) {
                printf(
                    "Process %s exited with status [%s]: %s\n",
                    $processKey,
                    isset($failedProcesses[$processKey]) ? 1 : 0,
                    implode('', $processOutputs[$processKey])
                );
//                var_dump(proc_get_status($processCollection[$processKey])['exitcode']);
                proc_close($processCollection[$processKey]);
            }
            unset($processCollection[$processKey]);
        }
    }
} while (count($processCollection));

printf("In %d seconds.", (time() - $start));