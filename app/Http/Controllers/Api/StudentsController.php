<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Students;
use Illuminate\Http\Request;
use Validator;
class StudentsController extends BaseController
{
    public function __construct()
    {
        //
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Students::whereActive('0')->get();
        if($students->count() > 0){
            $messageArray = array('message' => 'Student data', );
            return $this->sendResponse('true',$students,$messageArray );
        }else {
            $messageArray = array('message' => 'No data found.', );
            return $this->sendResponse('false',$students,$messageArray );
        }
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
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'parent_name' => ['required', 'string', 'max:255'],
                'mobile_number' => ['required', 'string', 'size:10'],
                'standard' => ['required', 'string', 'max:255'],
                'course' => ['required', 'string', 'max:255'],
                'email' => ['email', 'string', 'max:255'],
            ],
            [
                'mobile_number.size'=>'The mobile number must be 10 digit.'
            ]
        );

        if ($validator->fails()) {
            return $this->sendResponse('false',$request->all(), $validator->errors());
        }
        $students = Students::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'parent_name' => $request['parent_name'],
            'mobile_number' => $request['mobile_number'],
            'standard' => $request['standard'],
            'course' => $request['course'],
            'email' => $request['email'],
        ]);

        if($students){
            $messageArray = array('message' => 'Student data saved successfully.');
            return $this->sendResponse('true',$students,$messageArray );
        }else {
            $messageArray = array('message' => 'Student not saved.');
            return $this->sendResponse('false',$request->all(),$messageArray);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\students  $students
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'student_id' => ['required','numeric'],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'parent_name' => ['required', 'string', 'max:255'],
                'mobile_number' => ['required', 'string', 'size:10'],
                'standard' => ['required', 'string', 'max:255'],
                'course' => ['required', 'string', 'max:255'],
                'email' => ['email', 'string', 'max:255'],
            ],
            [
                'mobile_number.size'=>'The mobile number must be 10 digit.'
            ]
        );

        if ($validator->fails()) {
            return $this->sendResponse('false',$request->all(), $validator->errors());
        }
        $student = Students::find($request['student_id']);
        $student->first_name = $request['first_name'];
        $student->last_name = $request['last_name'];
        $student->parent_name = $request['parent_name'];
        $student->mobile_number = $request['mobile_number'];
        $student->standard = $request['standard'];
        $student->course = $request['course'];
        $student->email = $request['email'];
        $student->save();

        if($student){
            $messageArray = array('message' => 'Student data update successfully.');
            return $this->sendResponse('true',$student,$messageArray );
        }else {
            $messageArray = array('message' => 'Student data not update.');
            return $this->sendResponse('false',$request->all(),$messageArray);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\students  $students
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'student_id' => ['required'],
            ]
        );

        if ($validator->fails()) {
            return $this->sendResponse('false',$request->all(), $validator->errors());
        }
        $student = Students::find($request['student_id']);
        $student->active = '1';
        $student->save();

        if($student){
            $messageArray = array('message' => 'Student deleted successfully.');
            return $this->sendResponse('true',$student,$messageArray );
        }else {
            $messageArray = array('message' => 'Student not delete.');
            return $this->sendResponse('false',$request->all(),$messageArray);
        }
    }

}
