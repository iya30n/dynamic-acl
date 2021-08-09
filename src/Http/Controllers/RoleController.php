<?php

namespace Iya30n\DynamicAcl\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Iya30n\DynamicAcl\ACL;
use Iya30n\DynamicAcl\Models\Role;
use Iya30n\DynamicAcl\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    public function test($id)
    {

    }

    public function oldTest(Role $role)
    {
        // if controller key exists on request()->route()->getAction(), that's controller
        // else it's closure
        // TODO: check if we can get closure parameters
        // TODO: try to get controller@method called in Resource Controllers. NOTE: maybe you can't get method from Resource Controller

        $action = request()->route()->getAction()['controller']; // "Iya30n\DynamicAcl\Http\Controllers\RoleController@test"

        $method = \Str::after($action, '@'); // "test"

        $controller = \Str::replaceLast("@$method", '', $action); // "Iya30n\DynamicAcl\Http\Controllers\RoleController"

        $result = new \ReflectionMethod($controller, $method);

        /*
        {
         name: "Iya30n\DynamicAcl\Models\Role"
         allowsNull: false
         isBuiltin: false
        }
        */
        $modelName = $result->getParameters()[0]->getType()->getName(); // "Iya30n\DynamicAcl\Models\Role"
        dd($modelName);
    }

    public function index()
    {
		$roles = Role::latest()->paginate();

        return view('dynamicACL::role.index',  compact('roles'));
    }

    public function create()
    {
        $permissions = app(ACL::class)->getRoutes();

        return view('dynamicACL::role.create', compact('permissions'));
    }

    public function store(RoleRequest $request)
    {
        $permissions = $request->access;

        if (isset($permissions['fullAccess']) && $permissions['fullAccess'] == 0)
            Arr::forget($permissions, 'fullAccess');

		Role::create([
		    'name' => $request->name,
            'permissions' => $permissions
        ]);

//        flash()->success('پیغام', 'نقش با موفقیت ایجاد شد.');
        return redirect()->route('admin.roles.index');
    }

    public function edit(Role $role)
    {
        $permissions = app(ACL::class)->getRoutes();

        return view('dynamicACL::role.edit',  compact('role', 'permissions'));
    }

    public function update(Role $role, RoleRequest $request)
    {
        $permissions = $request->access;

        if (isset($permissions['fullAccess']) && $permissions['fullAccess'] == 0)
            Arr::forget($permissions, 'fullAccess');

        $role->update([
            'name' => $request->name,
            'permissions' => $permissions
        ]);
        
//        flash()->success('پیغام', 'نقش با موفقیت بروزرسانی شد.');
        return redirect()->route('admin.roles.index');
    }

    public function destroy(Role $role)
    {
        $role->users()->sync([]);

        $role->delete();

//        flash()->success('', 'نقش با موفقیت حذف شد.');
        return back();
    }
}
