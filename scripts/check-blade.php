<?php

// Compile and PHP-lint all application views, including partials behind auth/role guards.
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$compiler = $app->make('blade.compiler');
$temporary = tempnam(sys_get_temp_dir(), 'ecoshop-blade-');
$count = 0;
$failures = 0;

try {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(resource_path('views')));
    foreach ($files as $file) {
        if (!$file->isFile() || !str_ends_with($file->getFilename(), '.blade.php')) {
            continue;
        }
        $count++;
        try {
            file_put_contents($temporary, $compiler->compileString(file_get_contents($file->getPathname())));
            $process = proc_open([PHP_BINARY, '-l', $temporary], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
            $output = stream_get_contents($pipes[1]).stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            if (proc_close($process) !== 0) {
                throw new RuntimeException($output);
            }
        } catch (Throwable $exception) {
            $failures++;
            fwrite(STDERR, $file->getPathname().":\n".$exception->getMessage()."\n");
        }
    }
} finally {
    unlink($temporary);
}

echo "Blade syntax: {$count} templates checked, {$failures} failures.\n";
exit($failures === 0 ? 0 : 1);
