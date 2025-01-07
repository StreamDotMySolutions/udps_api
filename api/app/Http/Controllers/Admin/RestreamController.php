<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restream;

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

        

        $restream = Footer::create([
           
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

    // public function activation(Restream $restream, $is_active)
    // {
    //     // \Log::info($restream->id);
    //     // \Log::info($is_active);
    //     $restream = Restream::where('id', $restream->id)->update(['is_active' => $is_active]);
    // }

}