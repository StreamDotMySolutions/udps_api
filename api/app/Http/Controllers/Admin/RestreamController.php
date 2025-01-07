<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restream;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;

class RestreamController extends Controller
{
    public function index()
    {
        $restreams = Restream::defaultOrder()->paginate(10)->withQueryString(); 
        return response()->json(['restreams' => $restreams]);

    }

    public function show(Restream $restream)
    {
        return response()->json(['restream' => $restream]);
    }

    public function store(Request $request)
    {
        // validation
        $request->validate([
            'name' => 'required|string',
            'rtmp_address' => 'required|string',
        ]);

        

        $restream = Restream::create([
           
            'name' => $request->input('name'),
            'rtmp_address' => $request->input('rtmp_address'),
 
        ]);
        

        return response()->json(['message' => 'Restream creation success']);
    }

   

    public function update(Request $request,Restream $restream)
    {

        //\Log::info($request);
        // validation
        // $data = $request->validate([
        //     'is_active' => 'required|boolean',
        // ]);
        $request->validate([
            'name' => 'sometimes|string',
            'rtmp_address' => 'sometimes|string',
        ]);


        $restream = Restream::where('id', $restream->id)->update($request->except(['_method','id']));
        return response()->json(['message' => 'restream successfully updated']);
    }

    public function delete(Request $request,Restream $restream)
    {

        $data = $request->validate([
            'acknowledge' => 'required|accepted',
        ]);

        $restream->delete();
        return response()->json(['message' => 'FooRestreamter successfully deleted']);
      
    }

   
    public function ordering(Restream $restream, Request $request)
    {
        //\Log::info($restream);
        // reference https://github.com/lazychaser/laravel-nestedset
        switch($request->input('direction')){
            case 'up':
                $restream->up(); // restream ordering up
                break;
            case 'down':
                $restream->down(); //  // deejay ordering down
            break;
        }
        
    }

    public function startStreaming(Restream $restream)
    {

        // Start the streaming
        $source = 'rtmp://localhost/publish/nasionalfm';
        // Accessing the rtmp_address as a property of the Restream model
        $destination = $restream->rtmp_address;
        
        
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
            //echo "Process running in the background with PID: " . $pid . PHP_EOL;
        
        } catch (ProcessFailedException $exception) {
            // Handle errors
            //echo "The command failed with error: " . $exception->getMessage();
            \Log::info( $exception->getMessage() );
        }
         
        // Update the 'is_active' field to 1
        $restream->update([
            'pid' => $pid,
            'is_active' => 1
        ]);
        return response()->json(['message' => 'restream successfully updated']);
    }

    public function stopStreaming(Restream $restream)
    {

        // Kill the process using the PID
        exec("kill -9 $restream->pid");
        
        // Update the 'is_active' field to 1
        $restream->update([
            'pid' => null,
            'is_active' => 0
        ]);
        return response()->json(['message' => 'restream successfully updated']);
    }

    // public function activation(Restream $restream, $is_active)
    // {
    //     // \Log::info($restream->id);
    //     // \Log::info($is_active);
    //     $restream = Restream::where('id', $restream->id)->update(['is_active' => $is_active]);
    // }

}