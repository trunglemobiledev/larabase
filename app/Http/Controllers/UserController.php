<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use App\Services\QueryService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => []]);
//        $this->middleware('permission.index', ['only' => ['index','show']]);
    }

    public function index(Request $request)
    {
        try {
            $limit = $request->get('limit', 25);
            $ascending = (int)$request->get('ascending', 0);
            $orderBy = $request->get('orderBy', '');
            $search = $request->get('search', '');
            $betweenDate = $request->get('updated_at', []);

            $queryService = new QueryService(new User);
            $queryService->select = [];
            $queryService->columnSearch = ['name'];
            $queryService->withRelationship = [];
            $queryService->search = $search;
            $queryService->betweenDate = $betweenDate;
            $queryService->limit = $limit;
            $queryService->ascending = $ascending;
            $queryService->orderBy = $orderBy;

            $query = $queryService->queryTable();
            $query = $query->paginate($limit);
            $users = $query->toArray();

            return $this->jsonTable($users);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                return $this->jsonValidate($validator->errors());
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $role = $user->assignRole($request->role);
            if ($user) {
                return $this->jsonData($user);
            }
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function show($id)
    {
        try {
            $users = User::find($id);
            $users->userDetail;
            $roles = $users->getRoleNames();
            $permission = $users->getAllPermissions();
            return $this->jsonData($users);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $user->update($request->all());
            return $this->jsonData($user);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return $this->jsonMessage("Xóa thành công");
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function changeRole($id,Request $request)
    {
        $user = User::find($id);
        if ($user) {
            $user->syncRoles($request->roles);
            return $this->jsonMessage('Roles changed successfully!');
        }
        return $this->jsonError('Sorry! User not found');
    }
}
