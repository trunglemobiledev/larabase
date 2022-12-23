<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use App\Services\QueryService;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //except: không thuộc về auth token
        $this->middleware('jwt.verify', ['except' => []]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user = User::create($request->all());
            return $this->jsonData($user);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $users = User::find($id);
            $users->userDetail;
            return $this->jsonData($users);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        try {
            $user->update($request->all());
            return $this->jsonData($user);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return $this->jsonMessage("Xóa thành công");
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }
}
