<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RestreamRTMPJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $source;
    protected $destination;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($source, $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $command = "ffmpeg -i {$this->source} -c copy -f flv \"{$this->destination}\"";

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(3600); // Optional: set a timeout in seconds

        try {
            $process->mustRun();
            // Log success or handle any post-process logic
        } catch (ProcessFailedException $exception) {
            // Log the failure
            \Log::error('Restreaming failed: ' . $exception->getMessage());
        }
    }
}
