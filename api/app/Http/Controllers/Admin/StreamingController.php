<?php 

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;

class StreamingController extends Controller
{
    protected $ffmpegProcess;

    /**
     * Start streaming.
     *
     * @return \Illuminate\Http\Response
     */
    public function startStreaming(Request $request)
    {
        $source = 'rtmp://localhost/publish/nasionalfm';
        $destination = 'rtmps://live-api-s.facebook.com:443/rtmp/FB-182209403726432-0-Ab0FzIYXFrpAWsrUW7YviJhK';
        
        // Properly structure the ffmpeg command
        // Correct the path using forward slashes
        $command = [
            'nohup',
            'ffmpeg', 
            '-v', 'debug', // Verbose output
            '-i', $source, 
            '-c', 'copy', 
            '-f', 'flv', 
            $destination
            ,
            '> /dev/null 2>&1', 
            '&'
        ];
       
        //exec($command);
        try {
            // Run the process
            $process = new Process($command);
            $process->mustRun(); // Will throw an exception if the command fails
            //$process->start(); // Start the process asynchronously
            // If needed, you can capture the output
            // $output = $process->getOutput();
            // echo $output;

            $pid = $process->getPid();
            echo "Process PID: " . $pid . PHP_EOL;
        
        } catch (ProcessFailedException $exception) {
            // Handle errors (if the process fails)
            echo "The command failed with error: " . $exception->getMessage();
        }        
        // $this->ffmpegProcess = Process::fromShellCommandline($command);
        // $this->ffmpegProcess->setTimeout(0); // No timeout for long-running process
    
        // try {
        //     $this->ffmpegProcess->start();
        //     Storage::put('ffmpeg_process.pid', $this->ffmpegProcess->getPid());
        //     return response()->json(['message' => 'Streaming started.'], 200);
        // } catch (ProcessFailedException $exception) {
        //     return response()->json(['error' => 'Failed to start streaming: ' . $exception->getMessage()], 500);
        // } catch (\Exception $exception) {
        //     // Catch any other unexpected exceptions
        //     \Log::error('Unexpected error: ' . $exception->getMessage());
        //     return response()->json(['error' => 'An unexpected error occurred.'], 500);
        // }
    }
    
    /**
     * Stop streaming.
     *
     * @return \Illuminate\Http\Response
     */
    public function stopStreaming(Request $request)
    {
       return 'stop streaming';
    }
}
