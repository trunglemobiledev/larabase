<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PostCategoty;
use Illuminate\Http\Request;
use App\Services\QueryService;
use Illuminate\Support\Facades\Validator;
use Exception;

class PostCategoryController extends Controller
{
    public function __construct()
    {
        //except: không thuộc về auth token
        $this->middleware('jwt.verify', ['except' => []]);
    }

    public function index(Request $request)
    {
        try {
            $limit = $request->get('limit', 25);
            $ascending = (int)$request->get('ascending', 0);
            $orderBy = $request->get('orderBy', '');
            $search = $request->get('search', '');
            $betweenDate = $request->get('updated_at', []);

            $queryService = new QueryService(new PostCategoty);
            $queryService->select = [];
            $queryService->columnSearch = [];
            $queryService->withRelationship = [];
            $queryService->search = $search;
            $queryService->betweenDate = $betweenDate;
            $queryService->limit = $limit;
            $queryService->ascending = $ascending;
            $queryService->orderBy = $orderBy;

            $query = $queryService->queryTable();
            $query = $query->paginate($limit);
            $categorys = $query->toArray();

            return $this->jsonTable($categorys);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255|unique:post_categoties',
            ]);
            if ($validator->fails()) {
                return $this->jsonError($validator->errors()->toArray());
            }
            $category = PostCategoty::create($request->all());
            return $this->jsonData($category);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }


    public function show($id)
    {
        try {
            $postCategoty = PostCategoty::find($id);
            return $this->jsonData($postCategoty);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $postCategoty = PostCategoty::find($id);
            $postCategoty->update($request->all());
            return $this->jsonData($postCategoty);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function destroy($id)
    {
        try {
            $postCategoty = PostCategoty::find($id);
            if($postCategoty) {
                $postCategoty->delete();
                return $this->jsonMessage("Xóa thành công");
            } else {
                return $this->jsonError("Không tìm thấy dữ liệu");
            }
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }
}
