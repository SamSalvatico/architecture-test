<?php

main();

function main()
{
    $envFilepath = ".env";
    $envExampleFilepath = ".env.example";

    $envExampleContent = file($envExampleFilepath, FILE_SKIP_EMPTY_LINES);
    $explodedEnvContent = getEnvAsAssociativeArray($envFilepath);
    $missingKeys = [];

    foreach ($envExampleContent as $exampleLine) {
        $exploded = explode("=", $exampleLine);
        if (count($exploded) > 1) {
            if (!array_key_exists($exploded[0], $explodedEnvContent)) {
                $missingKeys[] = $exploded[0];
            }
        }
    }

    if (count($missingKeys)) {
        echoARedLine("These keys are missing from your .env file:");
        foreach ($missingKeys as $currentKey) {
            echoARedLine($currentKey);
        }
        exit(1);
    }
}


function echoARedLine($toEcho): void
{
    echo ("\033[01;31m" . $toEcho . "\033[0m" . PHP_EOL);
}

function getEnvAsAssociativeArray(string $filepath)
{
    $envContent = file($filepath, FILE_SKIP_EMPTY_LINES);
    $explodedEnvContent = [];

    foreach ($envContent as $currentEnvLine) {
        $exploded = explode("=", $currentEnvLine);
        if (count($exploded) > 1) {
            $explodedEnvContent[$exploded[0]] = $exploded[1];
        }
    }

    return $explodedEnvContent;
}