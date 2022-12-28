<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Exception;

class RolesController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => []]);
    }

    public function list()
    {
        try {
            $roles = Role::all();
            return $this->jsonData($roles);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            // store user information
            $role = Role::create([
                'guard_name' => 'api',
                'name' => $request->name,
            ]);

            // assign permission to role
            if (isset($request->permissions)) {
                $role->syncPermissions($request->permissions);
            }

            if ($role) {
                return $this->jsonData($role);
            }

            return $this->jsonError('Sorry! Failed to create role!');
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function show($id, Request $request)
    {
        try {
            $role = Role::with('permissions')->find($id);
            if ($role) {
                return $this->jsonData($role);
            } else {
                return $this->jsonError('Sorry! Not found!');
            }
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function delete($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return $this->jsonMessage('Role has been deleted');
        } else {
            return $this->jsonError('Sorry! Not found!');
        }
    }

    public function changePermissions($id, Request $request)
    {
        try {
            $request->validate([
                'permissions' => 'required',
            ]);
            // update role permissions
            $role = Role::find($id);
            if ($role) {
                // assign permission to role
                $role->syncPermissions($request->permissions);
                return $this->jsonMessage('Permission changed successfully!');
            }
            return $this->jsonError('Sorry! Role not found');
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }
}
