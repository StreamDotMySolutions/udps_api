<?php
namespace App\Http\Controllers\Admin; 

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller; 

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\DeleteRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
                        ->with('roles')
                        ->orderBy('id','DESC')
                        ->paginate(10)
                        ->withQueryString();

        return response()->json([
            'message' => 'Registered users',
            'users' => $users
        ]);
    }

    public function show(User $user)
    {

        if ($user->roles->isNotEmpty()) {
            // Assign the first role's name to the 'role' key
            $user['role'] = $user->roles->first()->name;
            
            // Assign the first role's id to the 'role_id' key
            $user['role_id'] = $user->roles->first()->id;
        } else {
            // If user has no roles
            $user['role'] = null;
            $user['role_id'] = null;
        }

        
        return response()->json([
                'message' => "User data for id {$user->id}",
                'user' => $user
        ]);
    }

    public function roles()
    {
        $roles = Role::all();
        return response()->json([
            'message' => 'Available roles',
            'roles' => $roles
        ]);
    }

    public function store( StoreRequest $request)
    {
        // Create User
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'password' => Hash::make($request->input('password'))
        ]);
        
        // Get RoleName
        $role = Role::find($request->input('role_id'));
        
        // Assign Role
        $user->guard_name = 'web';
        $user->assignRole($role->name);

        // Response
        return response()->json(['message' => 'User successfully created']);

    }

    public function update ( UpdateRequest $request, User $user)
    {
        // User
        if ($request->has('password')) {
            // If a password is provided, create a user with the provided password.
            $password = Hash::make($request->input('password'));
            User::where('id', $user->id)->update(['password' => $password]);
        } 

        // Role
        if ($request->has('role_id')) {
            $role = Role::find($request->input('role_id'));
            $user->syncRoles([]);
            $user->assignRole($role->name);
        }

        // Email
        if ($request->has('email')) {
             User::where('id', $user->id)->update($request->only(['email']));
        }

        // Status
        if ($request->has('status')) {
             User::where('id', $user->id)->update($request->only(['status']));
        }

        // Name
        if ($request->has('name')) {
            User::where('id', $user->id)->update($request->only(['name']));
        }

        // Response
        return response()->json([
            'message' => "User data for id {$user->id} successfully updated",
            'user' => $user
        ]);
    }

    public function delete ( DeleteRequest $request, User $user)
    {
        //\Log::info($user);
        $user->delete();
       
        return response()->json([
            'message' => "User data for id {$user->id} successfully deleted",
        ]);
    }
}
