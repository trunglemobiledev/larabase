<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\QueryService;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Exception;

class PostController extends Controller
{
    public function __construct()
    {
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

            $queryService = new QueryService(new Post);
            $queryService->select = [];
            $queryService->columnSearch = [];
            $queryService->withRelationship = ['category'];
            $queryService->search = $search;
            $queryService->betweenDate = $betweenDate;
            $queryService->limit = $limit;
            $queryService->ascending = $ascending;
            $queryService->orderBy = $orderBy;

            $query = $queryService->queryTable();
            $query = $query->paginate($limit);
            $post = $query->toArray();

            return $this->jsonTable($post);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'body' => 'required|string',
                'category_id' => 'required|integer'
            ]);
            if ($validator->fails()) {
                return $this->jsonError($validator->errors()->toArray());
            }
            $post = Post::create($request->all());
            return $this->jsonData($post);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function show($id)
    {
        try {
            $post = Post::find($id);
           if($post) {
               $post->category;
               return $this->jsonData($post);
           } else {
               return $this->jsonError("Không tìm thấy dữ liệu");
           }
        } catch (Exception $e) {
            return $this->jsonError($e);
        }

    }

    public function update(Request $request, $id)
    {
        try {
            $post = Post::find($id);
            if($post) {
                $post->update($request->all());
                return $this->jsonData($post);
            } else {
                return $this->jsonError("Không tìm thấy dữ liệu");
            }
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function destroy($id)
    {
        try {
            $post = Post::find($id);
            if($post) {
                $post->delete();
                return $this->jsonMessage("Xóa thành công");
            } else {
                return $this->jsonError("Không tìm thấy dữ liệu");
            }
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }
}
