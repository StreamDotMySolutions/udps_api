<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::defaultOrder()->paginate(10)->withQueryString(); 
        return response()->json(['modules' => $modules]);

    }

    public function show(Module $module)
    {
        return response()->json(['module' => $module]);
    }

   

    public function update(Request $request,Module $module)
    {

        //\Log::info($request);
        // validation
        $data = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $module = Module::where('id', $module->id)->update($request->except(['_method','id']));
        return response()->json(['message' => 'module successfully created']);
    }

   

    public function ordering(Module $module, Request $request)
    {
        //\Log::info($module);
        // reference https://github.com/lazychaser/laravel-nestedset
        switch($request->input('direction')){
            case 'up':
                $module->up(); // module ordering up
                break;
            case 'down':
                $module->down(); //  // deejay ordering down
            break;
        }
        
    }

    public function activation(Module $module, $is_active)
    {
        // \Log::info($module->id);
        // \Log::info($is_active);
        $module = Module::where('id', $module->id)->update(['is_active' => $is_active]);
    }

}