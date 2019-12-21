<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\StudentDocuments;
use Illuminate\Http\Request;
use Validator;
class StudentDocumentsController extends BaseController
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
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'student_id' => 'required|numeric',
        ],
        );

        if ($validator->fails()) {
            return $this->sendResponse('false',$request->all(), $validator->errors());
        }
        $student_docs = StudentDocuments::where('student_id',$request['student_id'])->get();
        if($student_docs){
            $messageArray = array('message' => 'Student documents data', );
            return $this->sendResponse('true',$student_docs,$messageArray );
        }else {
            $messageArray = array('message' => 'No data found.', );
            return $this->sendResponse('false',$student_docs,$messageArray );
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
        $validator = Validator::make($request->all(),
        [
            'student_id' => 'required|numeric',
            'birth_certificate' => 'required|mimes:jpeg,pdf,png|max:2048',
            'document_file[]' => 'mimes:jpeg,pdf,png|max:2048',
            'document_type'=>'required_if:document_file[0],!=,birth_certificate|string'
        ],
        );

        if ($validator->fails()) {
            return $this->sendResponse('false',$request->all(), $validator->errors());
        }

        $extension = $request->file('birth_certificate')->getClientOriginalExtension();
        $dir = 'uploads/';
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $request->file('birth_certificate')->move($dir, $filename);
        $student_id = $request['student_id'];

        if ($filename != "") {
            $students = StudentDocuments::create([
                'student_id' => $student_id,
                'document_type' => 'birth_certificate',
                'document_file' => $filename,
            ]);
        }

        if($request->hasfile('document_file'))
         {
            foreach($request->file('document_file') as $image)
            {
               $extension = $image->extension();
               $dir = 'uploads/';
               $document = uniqid() . '_' . time() . '.' . $extension;
               $image->move($dir, $document);
               
               $students = StudentDocuments::create([
                    'student_id' => $student_id,
                    'document_type' => $request['document_type'],
                    'document_file' => $document,
                ]);
            }
        }
        $student_docs = StudentDocuments::where('student_id',$request['student_id'])->get();
        if($student_docs){
            $messageArray = array('message' => 'Student documents saved successfully.');
            return $this->sendResponse('true',$student_docs,$messageArray );
        }else {
            $messageArray = array('message' => 'Student documents not saved.');
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
        $validator = Validator::make($request->all(),
        [
            'student_id' => 'required|numeric',
            'birth_certificate' => 'required|mimes:jpeg,pdf,png|max:2048',
            'document_file[]' => 'mimes:jpeg,pdf,png|max:2048',
            'document_type'=>'required_if:document_file[0],!=,birth_certificate|string'
        ],
        );

        if ($validator->fails()) {
            return $this->sendResponse('false',$request->all(), $validator->errors());
        }

        $extension = $request->file('birth_certificate')->getClientOriginalExtension();
        $dir = 'uploads/';
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $request->file('birth_certificate')->move($dir, $filename);
        $student_id = $request['student_id'];

        if ($filename != "") {
            $student_doc = StudentDocuments::find([
                ["student_id",$request['student_id']],
                ["document_type",'birth_certificate']
            ]
            );
            $students = StudentDocuments::updateOrCreate([
                    'student_id' => $student_id,
                    'document_type' => 'birth_certificate'
                ],
                [
                    'student_id' => $student_id,
                    'document_type' => 'birth_certificate',
                    'document_file' => $filename,
                ],
            );
        }

        if($request->hasfile('document_file'))
         {
            $student_docs_delete = StudentDocuments::where([
                ["student_id",$request['student_id']],
                ["document_type",'<>','birth_certificate']
            ])->get()->each->delete();

            foreach($request->file('document_file') as $image)
            {
               $extension = $image->extension();
               $dir = 'uploads/';
               $document = uniqid() . '_' . time() . '.' . $extension;
               $image->move($dir, $document);
               
               $students = StudentDocuments::create([
                    'student_id' => $student_id,
                    'document_type' => $request['document_type'],
                    'document_file' => $document,
                ]);
            }
        }
        $student_docs = StudentDocuments::where('student_id',$request['student_id'])->get();
        if($student_docs){
            $messageArray = array('message' => 'Student documents update successfully.');
            return $this->sendResponse('true',$student_docs,$messageArray );
        }else {
            $messageArray = array('message' => 'Student documents not update.');
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
                'document_id' => ['required'],
                'student_id' => ['required'],
            ]
        );

        if ($validator->fails()) {
            return $this->sendResponse('false',$request->all(), $validator->errors());
        }
        $student_doc = StudentDocuments::where([
                ["student_id",$request['student_id']],
                ["id",$request['document_id']]
            ]);
        $student_doc_delete = $student_doc->delete();
        if($student_doc_delete){
            $messageArray = array('message' => 'Student document deleted successfully.');
            return $this->sendResponse('true',$student_doc,$messageArray );
        }else {
            $messageArray = array('message' => 'Student document not delete.');
            return $this->sendResponse('false',$request->all(),$messageArray);
        }
    }

}
