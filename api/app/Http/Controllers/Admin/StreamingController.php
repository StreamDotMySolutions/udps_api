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
            
            'ffmpeg', 
            '-v', 'debug', // Verbose output
            '-i', $source, 
            '-c', 'copy', 
            '-f', 'flv', 
            $destination,
   
        ];
       
        try {
            // Create the process
            $process = new Process($command);
            $process->setTimeout(null); // Disable timeout for long-running processes
            $process->setOptions(['create_new_console' => true]); // Detach the process in the background
            $process->start(); // Start the process asynchronously
        
            // Get the PID of the detached process
            $pid = $process->getPid();
            echo "Process running in the background with PID: " . $pid . PHP_EOL;
        
        } catch (ProcessFailedException $exception) {
            // Handle errors
            echo "The command failed with error: " . $exception->getMessage();
        }
     
    }
    
    /**
     * Stop streaming.
     *
     * @return \Illuminate\Http\Response
     */
   
     public function stopStreaming($pid)
     {
         // Kill the process using the PID
         exec("kill -9 $pid");

         return response()->json(['message' => 'Process stopped', 'pid' => $pid]);
     }
}
