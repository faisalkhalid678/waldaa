<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Admin\Follower;

class FollowersController extends Controller
{

    public function index()
    {
        //
    }



    public function follow(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'userId' => 'required',
            'follower_id' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }

        $follower = new Follower();
        $isFollowed = $follower->where(['following_id' => $request->userId, 'follower_id' => $request->follower_id])->first();
        if ($isFollowed) {
            $message = 'Follow Request Sent Successfully.';
            if ($isFollowed->follow_status == 'Accepted') {
                $message = 'Unfollowed Successfully';
                $markFollowRequest = array(

                    'follow_status' => 'Unfollowed',
                    'unfollow_at' => date('Y-m-d H:i:s')
                );
                $done = $follower->where(['following_id' => $request->userId, 'follower_id' => $request->follower_id])->update($markFollowRequest);
            } else {
                $message = 'Follow Request Sent Successfully.';
                $markFollowRequest = array(

                    'follow_status' => 'Pending',
                    'follow_at' => date('Y-m-d H:i:s')
                );
                $done = $follower->where(['following_id' => $request->userId, 'follower_id' => $request->follower_id])->update($markFollowRequest);
            }
        } else {

            $follower->following_id = $request->userId;
            $follower->follower_id = $request->follower_id;
            $follower->follow_at = date('Y-m-d H:i:s');
            $done = $follower->save();
            $message = 'Follow Request Sent Successfully.';
        }

        if ($done) {
            return buildResponse('success', $message, buildResponseError());
        } else {
            return buildResponse('error', $message, buildResponseError());
        }
    }



    public function request_status_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required',
            'follower_id' => 'required',
            'request_type' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }

        $follower = new Follower();
        $isFollowed = $follower->where(['follower_id' => $request->userId, 'following_id' => $request->follower_id])->first();
        if ($isFollowed) {
            $message = 'Follow Request Sent Successfully.';
            if ($request->request_type == 'accept') {
                $message = 'Follow Request Accepted Successfully';
                $markFollowRequest = array(
                    'follow_status' => 'Accepted',
                    'unfollow_at' => date('Y-m-d H:i:s')
                );
                $done = $follower->where(['follower_id' => $request->userId, 'following_id' => $request->follower_id])->update($markFollowRequest);
            } elseif ($request->request_type == 'reject') {
                $message = 'Follow Request Rejected Successfully.';
                $markFollowRequest = array(
                    'follow_status' => 'Rejected',
                    'follow_at' => date('Y-m-d H:i:s')
                );
                $done = $follower->where(['following_id' => $request->userId, 'follower_id' => $request->follower_id])->update($markFollowRequest);
            }
        } else {
            $done = true;
            $message = 'Not Followed Already.';
        }

        if ($done) {
            return buildResponse('success', $message, buildResponseError());
        } else {
            return buildResponse('error', $message, buildResponseError());
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
