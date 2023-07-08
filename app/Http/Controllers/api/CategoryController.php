<?php

namespace App\Http\Controllers\api;

use App\Helpers\ResponseBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function list(Request $request)
    {
        try {
            $category = $request->user()->categories;
            $this->response = CategoryResource::collection($category);
            return ResponseBuilder::success($this->response, "success");
        } catch (\Exception $e) {
            dd($e);
            Log::error($e);
        }
    }

    public function create(CategoryCreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;
            Category::create($data);
            DB::commit();
            $this->response = CategoryResource::collection($request->user()->categories);
            return ResponseBuilder::success($this->response);
        } catch (\Exception $e) {
            Log::error($e);
            dd($e);
            DB::rollback();

            return ResponseBuilder::error("error", $this->errorStatus);
        }
    }
}
