<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Exception;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => []]);
    }

    public function list()
    {
        try {
            return $this->jsonData(Permission::get());
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            // store user information
            $permission = Permission::create([
                'guard_name' => 'api',
                'name' => $request->name,
            ]);
            if ($permission) {
                return $this->jsonData($permission);
            }
            return $this->jsonError('Sorry! Failed to create permission!');
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function show($id, Request $request)
    {
        $permission = Permission::with('roles')->find($id);

        if ($permission) {
            return $this->jsonData($permission);
        } else {
            return $this->jsonError('Sorry! Not found!');
        }
    }

    public function delete($id, Request $request)
    {
        $permission = Permission::find($id);
        if ($permission) {
            $permission->delete();
            return $this->jsonMessage('Permission has been deleted');
        } else {
            return $this->jsonError('Sorry! Not found!');
        }
    }

}
