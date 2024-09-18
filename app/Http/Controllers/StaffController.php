<?php

namespace App\Http\Controllers;

use App\Mail\ManagerMail;
use App\Models\Menu;
use App\Models\Staff;
use App\Mail\StaffMail;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restaurantId = Session::get('id');   
        $staff = Staff::where('restaurantId',$restaurantId)->paginate(5);
        return view('staff.staffindex',compact('staff'))
        ->with('i', (request()->input('page', 1) - 1) * 5);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $restaurantId = Session::get('id');
        $restaurant = Restaurant::find($restaurantId);
        return view('staff.staffcreate',compact('restaurant','restaurantId'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{ $validatedData = $request->validate([
                'name' => 'required',
                'contactNumber' => 'required|digits:10|numeric|regex:/^[6-9]\d{9}$/',
                'email' => 'required|email|unique:staff,email',
                'password' => 'required|min:6',
                'staffType' => 'required',
            ]);
            $restaurantId = Session::get('id');
            $data = new Staff();
            $data->restaurantid = $restaurantId;
            $data->name = $request->name;
            $data->contactNumber = $request->contactNumber;
            $data->email = $request->email;
            $data->password = bcrypt($request->password);
            $data->staffType = $request->staffType;
            $data->save();
            
            $Data = Restaurant::find($restaurantId);
            $mailData = [
                'restaurantName' => $Data->name,
                'name' => $request->name,
                'email' => $request->email,
                'password'=> $request->password,
                'staffType' => $request->staffType,
            ];
            
            // staff create send mail
            $mailData['body'] = 'This is for Hiring for Staff Mail.';
            $mailData['title'] = 'Congratulations! You are hired';
            Mail::to($request->email)->send(new StaffMail($mailData));
            
            // manager send mail
            $staff = Staff::where('staffType','Manager')->first();
            $mailData['title'] = 'Mail From Staff is Hired';
            $mailData['body'] = 'Confirmation For Staff is Hired';
            Mail::to($staff->email)->send(new ManagerMail($mailData));  

            return response()->json([
                'status' => true,
                'message' => 'Staff Successfully Saved!...',
                'data' => $data,
            ]);
    
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ]);
        }   


    }

    public function trashstaff()
    {
        $restaurantId = Session::get('id');
        $staff = Staff::where('restaurantId', $restaurantId)
            ->onlyTrashed()
            ->where('status', 'Inactive')
            ->paginate(5);
    
        return view('staff.trashstaff', compact('staff'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function restore(string $id){
        $staff = Staff::onlyTrashed()->find($id);

        if ($staff) {
            $staff->restore();
            $staff->status = "Active";
            $staff->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Staff restored successfully!',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Staff not found or already restored.',
            ]);
        }
    }
    public function forcedelete(string $id){
        $staff = Staff::withTrashed()->findOrFail($id);
        $staff->forceDelete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Staff permanently deleted!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = Staff::all()->find($id);
        return view('staff.staffview',compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = Staff::with('restaurant')->find($id);
        return view('staff.staffedit',compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try{ $validatedData = $request->validate([
            'name' => 'required',
            'contactNumber' => 'required|digits:10|numeric|regex:/^[6-9]\d{9}$/',
            'email' => 'required|email|unique:staff,email,'.$request->id,
            'staffType' => 'required',
            ]);
        

            $data  = Staff::find($id);
            $data->name = $request->name;
            $data->contactNumber = $request->contactNumber;
            $data->email = $request->email;
            $data->staffType = $request->staffType;
            // $data->status = $request->status;
            $data->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Updated successfully!',
                'data' => $data,
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Staff::find($id);    
        $data->status = 'Inactive';
        $data->save();    
        $data->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Deleted Successfully!',
            'data' => $data,
        ]);
    }

    public function export(){

        $filename = 'staff-data.csv';
        $restaurantId = Session::get('id');

    
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        return response()->stream(function ()  use ($restaurantId){

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'name',
                'contactNumber',
                'email',
                'staffType',
                'status',
            ]);

            Staff::where('restaurantId', $restaurantId)->chunk(25, function ($staffs) use ($handle){

                foreach($staffs as $staff){
                    $data = [
                        isset($staff->name)? $staff->name : '',
                        isset($staff->contactNumber)? $staff->contactNumber : '',
                        isset($staff->email)? $staff->email : '',
                        isset($staff->staffType)? $staff->staffType : '',
                        isset($staff->status)? $staff->status : '',

                    ];

                    fputcsv($handle,$data);
                }
            });
            fclose($handle);
        },200,$headers);
    }

    public function import(Request $request){
        try{ $request->validate([
                'import_file' => 'required',
            ]);

            $file = $request->file('import_file');
            $handle = fopen($file->path(), 'r');
            fgetcsv($handle);

            $chunksize = 25;
            while(!feof($handle))
            {
                $chunkdata = [];

                for($i = 0; $i<$chunksize; $i++)
                {
                    $data = fgetcsv($handle);
                    if($data === false)
                    {
                        break;
                    }
                    $chunkdata[] = $data; 
                }

                $this->getchunkdata($chunkdata);
            }
            fclose($handle);
            return response()->json([
                'success' => true,
                'message' => 'Data Inserted successfully!',
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ]);
        }
    }

    public function getchunkdata($chunkdata)
    {
        foreach($chunkdata as $column){

            $restaurantId = $column[0];
            $name = $column[1];
            $contactNumber = $column[2];
            $email = $column[3];
            $password = $column[4];
            $staffType = $column[5];
            $status = $column[6];        
            
            $hashedPassword = Hash::make($password);
            $existingrecord = Staff::where('email', $email)->exists();
            if($existingrecord){

                Staff::where('email', $email)->update([
                    'restaurantId' => $restaurantId,
                    'name' => $name,
                    'contactNumber' => $contactNumber,
                    'password' => $hashedPassword,
                    'staffType' => $staffType,
                    'status' => $status,
                ]);
            }else{
                Staff::create([
                    'restaurantId' => $restaurantId,
                    'name' => $name,
                    'contactNumber' => $contactNumber,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'staffType' => $staffType,
                    'status' => $status,
                ]);
            }
        }
    }

    public function sendemail(){
           
        dd("Email is sent successfully.");
    }

}