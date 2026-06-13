<?php

/*
|--------------------------------------------------------------------------
| Simple Git Deploy Webhook
|--------------------------------------------------------------------------
| Triggers `git pull origin master` in the project directory.
|
| The secret(s) live in the Laravel .env file as DEPLOY_TOKEN (comma-
| separated for multiple tokens). Trigger with:
|   https://withbilgi.com/deploy.php?token=YOUR_SECRET
*/

// ---- Configuration ----------------------------------------------------
$repoPath = '/home/shababhs/withbilgi.com';
$branch   = 'master';

// Read a value straight from the Laravel .env (this file lives in public/,
// so .env sits one directory up). Avoids booting Laravel for a standalone script.
function env_value(string $key, string $envPath): ?string
{
    if (!is_readable($envPath)) {
        return null;
    }

    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
            continue;
        }

        [$name, $val] = explode('=', $line, 2);
        if (trim($name) !== $key) {
            continue;
        }

        // Strip surrounding quotes if present.
        return trim(trim($val), "\"'");
    }

    return null;
}

$rawTokens = env_value('DEPLOY_TOKEN', __DIR__ . '/../.env') ?? '';
$tokens    = array_filter(array_map('trim', explode(',', $rawTokens)));

// ---- Auth -------------------------------------------------------------
$provided = (string) ($_GET['token'] ?? $_SERVER['HTTP_X_DEPLOY_TOKEN'] ?? '');

$authorized = false;
foreach ($tokens as $token) {
    if ($token !== '' && hash_equals($token, $provided)) {
        $authorized = true;
        break;
    }
}

if (!$authorized) {
    http_response_code(403);
    header('Content-Type: text/plain');
    exit("403 Forbidden\n");
}

// ---- Run deploy -------------------------------------------------------
header('Content-Type: text/plain');

$command = sprintf(
    'cd %s && git pull origin %s 2>&1',
    escapeshellarg($repoPath),
    escapeshellarg($branch)
);

exec($command, $output, $exitCode);

http_response_code($exitCode === 0 ? 200 : 500);

echo "$ git pull origin {$branch}\n";
echo "----------------------------------------\n";
echo implode("\n", $output) . "\n";
echo "----------------------------------------\n";
echo $exitCode === 0 ? "Deploy successful.\n" : "Deploy failed (exit {$exitCode}).\n";
