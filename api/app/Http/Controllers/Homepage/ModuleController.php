<?php
namespace App\Http\Controllers\Homepage; 

use App\Models\Module;
use App\Http\Controllers\Controller; 

class ModuleController extends Controller
{
    public function show()
    {
        $modules = Module::query()
            ->where( 'is_active' , 1 )
            ->defaultOrder()
            ->get();

        return response()->json([
            'message' => 'Modules for Homepage',
            'modules' => $modules
        ]);
    }
}