<?php

namespace App\Http\Controllers\api;

use App\Helpers\ResponseBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\ItemAddRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ItemlistResource;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsUnprocessable;

class ItemController extends Controller
{
    public function list(Request $request)
    {
        try {
            $user = $request->user();
            $this->response = ItemlistResource::collection($user->items);
            return ResponseBuilder::success($this->response);

        } catch (\Exception $e) {
            Log::error($e);
            dd($e);
            return ResponseBuilder::error('error', $this->errorStatus);
        }
    }
    public function create(ItemAddRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            $items = Item::create($data);

            $items = ItemlistResource::collection($request->user()->items);

            DB::commit();
            return ResponseBuilder::success($items);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();

            ResponseBuilder::error("error", $this->errorStatus);
        }
    }
}
