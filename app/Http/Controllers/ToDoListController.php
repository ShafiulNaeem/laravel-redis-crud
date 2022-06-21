<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class ToDoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        // set key prefix
        $key_prefix = "todo:id:";

        // get all todo keys
        $key_value = Redis::keys('todo:id:*');

        // get key wise data and stored in $data variable
        foreach($key_value as $key=> $value){

            // get id from key
            $id = preg_replace('/[^0-9]/', '', $value);

            // get key wise data
            $data[$key] = json_decode(Redis::get($key_prefix.$id),true);

        }

        // sort data id wise DESC order
        $sort_data = array_column($data, 'id');
        array_multisort($sort_data, SORT_DESC, $data);

        // total element
        $total = count($data);

        if (!empty($data)){
            $response['status'] = 'success';
            $response['message'] = 'Data found.';
            $response['response_data'] = $data;
            $response['total'] = $total;
            return response()->json(['response' => $response], 200);
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Data not found.';
            return response()->json(['response' => $response], 404);
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

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'info' => 'required',
        ]);

        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['message'] =$validator->errors();
            return response()->json(['response' => $response]);
        }

        $request_data = $request->all();
        //get id
        $id = $this->getAuToIncrementId();
        $request_data['id'] = $id;
        $request_data['created_at'] = Carbon::now()->toDateTimeString();
        $request_data['updated_at'] = Carbon::now()->toDateTimeString();
        $request_data = json_encode($request_data);

        // Insert data
        $data = Redis::set('todo:id:'.$id, $request_data);

        if ($data){
            $response['status'] = 'success';
            $response['message'] = 'Data Inserted Successfully!';
            return response()->json(['response' => $response], 201);
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Something Went to Wrong!';
            return response()->json(['response' => $response], 404);
        }

    }

    public function getAuToIncrementId(){
        // get all todo keys
        $key_value = Redis::keys('todo:id:*');
        if ($key_value){
            $data = [];
            // get key wise id and stored in $data variable
            foreach($key_value as $value){
                // get id from key
                $data[] = preg_replace('/[^0-9]/', '', $value);
            }
            return max($data) + 1;
        }else{
            return 1;
        }

    }

    /**
     * Display the specified todo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Redis::get('todo:id:'.$id);
        if ($data){
            $response['status'] = 'success';
            $response['message'] = 'Data found.';
            $response['response_data'] = json_decode($data,true);
            return response()->json(['response' => $response], 200);
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Something Went to Wrong!';
            return response()->json(['response' => $response], 404);
        }
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
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'info' => 'required',
        ]);

        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['message'] =$validator->errors();
            return response()->json(['response' => $response]);
        }

        //get data
        $data = Redis::get('todo:id:'.$id);

        if ($data){
            $data = json_decode($data,true);

            //delete data
            Redis::del('todo:id:' . $id);

            $request_data = $request->all();
            $request_data['id'] = $id; // id from old data
            $request_data['created_at'] = $data['created_at']; // date from old data
            $request_data['updated_at'] = Carbon::now()->toDateTimeString();
            $request_data = json_encode($request_data);

            // insert new data in existed id
            Redis::set('todo:id:'.$id, $request_data);
            $response['status'] = 'success';
            $response['message'] = 'Data Updated Successfully!';
            return response()->json(['response' => $response], 200);
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Something Went to Wrong!';
            return response()->json(['response' => $response], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Redis::get('todo:id:'.$id);
        if ($data){
            Redis::del('todo:id:' . $id);
            $response['status'] = 'success';
            $response['message'] = 'Data Deleted Successfully!';
            return response()->json(['response' => $response], 200);
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Something Went to Wrong!';
            return response()->json(['response' => $response], 404);
        }
    }
}
