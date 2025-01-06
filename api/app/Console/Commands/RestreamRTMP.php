<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RestreamRTMP extends Command
{
    protected $signature = 'restream:rtmp {source} {destination}';
    protected $description = 'Restream an RTMP stream to another RTMP/RTMPS server';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $source = $this->argument('source');
        $destination = $this->argument('destination');

        $command = "ffmpeg -i {$source} -c copy -f flv \"{$destination}\"";

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(3600); // Set a timeout if needed

        try {
            $process->mustRun();
            $this->info('Restreaming completed successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error('Restreaming failed: ' . $exception->getMessage());
        }
    }
}
