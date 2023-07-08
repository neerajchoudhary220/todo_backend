<?php

namespace App\Http\Controllers\api;

use App\Helpers\ResponseBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            $this->response = new UserResource($user);
            return ResponseBuilder::success($this->response, 'success');
        } catch (\Exception $e) {
            Log::error($e);
            return ResponseBuilder::error("Error", $this->errorStatus);
        }
    }

    public function update(ProfileRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $request->user('api');
            $data = $request->only('name');

            if($request->image){
                $image = Media::save_media(file: $request->file('image'), dir: 'profile', tags: ['profile image'], user_id: $user->id, store_as: 'image');
                $data['media_id']= $image->id;
            }
            $user->update($data);
            DB::commit();
            $this->response = new UserResource($user);
            return ResponseBuilder::success($this->response, "Updated Successfully");
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();

            return ResponseBuilder::error("Error", $this->errorStatus);
        }
    }
}
