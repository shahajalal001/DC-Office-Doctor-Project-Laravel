<?php

namespace App\Http\Controllers;

use App\Recipientlist;
use App\Supportslist;
use Illuminate\Http\Request;
use App\Imports\ImportUsers;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Str;

class RecipientListControllerWork extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = new Recipientlist;
        $user->name = $request->name;
        $user->father = $request->father;
        $user->age = $request->age;
        $user->mobile = $request->mobile;
        $user->weight = $request->weight;
        $user->address = $request->address;
        $user->roger_bornona = $request->roger_bornona;
        $user->doctor_type = $request->doctor_type;
        $user->gender = $request->gender;
        $user->status = 0;
        $user->user_id = 'DCK'.Str::random(6);
        $save = $user->save();

        if($save){
            return response()->json(['error'=>false,'msg'=>'Successfully Placed Your Request']);
        }else{
            return response()->json(['error'=>true,'msg'=>'Problem In Creating Data']);
        }
    }

    public function search(Request $request){
        $document = Recipientlist::where('user_id', $request->id_or_no )->paginate(20);
        return view('applicants', compact('document'));
    }

    public function importData(Request $request)
    {

           $data =Excel::import(new ImportUsers,request()->file('select_file'));

           return back()->with('success', 'Excel Data Imported successfully.');
    }

    public function getRecipientByUniqueId(Request $request){
        $document = Recipientlist::where('user_id', $request->unique_id )->first();
        if(empty($document)){
            return response()->json(['error'=>true,'msg'=>'Unique Id Not Matched']);
        }
        return response()->json(['error'=>false,'msg'=>'Successfully Get Data','data'=>$document]);
    }

    public function updateRecipientByUniqueId(Request $request){

        $support = Supportslist::where('id', $request->id )->first();
        $document = Recipientlist::where('user_id', $request->unique_id )->update([
            'status'=> 1,
            'doctor'=>$support->name,
            'serve_date'=>Carbon::now()
        ]);

        if(empty($document)){
            return response()->json(['error'=>true,'msg'=>'Unique Id Not Matched']);
        }
        return response()->json(['error'=>false,'msg'=>'Successfully Confirmed']);
    }

    public function assignDoctor($name, $id, $page){
        $document = Recipientlist::where('user_id', $id )->update([
            'doctor'=>$name
        ]);
        return redirect('applicants?page='.$page);
    }

    public function getDoctorUnderPatient(Request $request){
        $support = Supportslist::where('id', $request->user_id )->first();

        $data = Recipientlist::where('doctor', $support->name)->paginate(50);
        if(empty($data)){
            return response()->json(['error'=>true,'msg'=>'No Data Found']);
        }
        return response()->json(['error'=>false,'msg'=>'Successfully Confirmed', 'data' => $data]);
    }

    public function checkAllApprovedOrDelete(Request $request){

        $checked = $request->chkbox;
        if($request->selected == "approved"){
                for($i=0;$i<count($checked);$i++){
                    $document = Recipientlist::where('user_id', $checked[$i] )->update([
                    'status'=> 2
            ]);

            }
        return redirect('applicants?page='.$request->page);
        }elseif($request->selected == "delete"){
            for($i=0;$i<count($checked);$i++){
                $document = Recipientlist::where('user_id', $checked[$i] )->delete();
            }
            return redirect('applicants?page='.$request->page);
        }elseif($request->selected == "delivered"){
            for($i=0;$i<count($checked);$i++){
                $document = Recipientlist::where('user_id', $checked[$i] )->update([
            'status'=> 1,
            'doctor'=>'admin',
            'serve_date'=>Carbon::now()
        ]);
            }
            return redirect('applicants?page='.$request->page);
        }
}

    public function getPorishonkanValue(Request $request){
        $documentPending = Recipientlist::count();
        $documentSuccess = Recipientlist::where('status', 1)->count();
        $doctors = Supportslist::count();

        return response()->json(['error'=>false,'total'=>$documentPending,'success'=>$documentSuccess, 'doctors'=>$doctors]);
    }

    public function approved($id,$page){
        $document = Recipientlist::where('user_id', $id )->update([
            'status'=> 1
    ]);
    return redirect('applicants?page='.$page);
    }

    public function delete($id,$page){
        $document = Recipientlist::where('user_id', $id )->delete();;
        return redirect('applicants?page='.$page);
    }

    public function deleteSupport($id,$page){
        $document = Supportslist::where('user_id', $id )->delete();;
        return redirect('volunteer?page='.$page);
    }

    public function deleteAdmin($id){
        $document = User::where('user_id', $id )->delete();;
        return redirect('admin');
    }

    public function sortUsingHelpDate(Request $request){
        $date= Carbon::parse($request->help_date);
        $document = Recipientlist::whereDate('serve_date', '=', $date)->paginate(2);
        return view('applicants', compact('document','date'));
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
     * @param  \App\Recipientlist  $recipientlist
     * @return \Illuminate\Http\Response
     */
    public function show(Recipientlist $recipientlist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recipientlist  $recipientlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Recipientlist $recipientlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recipientlist  $recipientlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipientlist $recipientlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recipientlist  $recipientlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipientlist $recipientlist)
    {
        //
    }
}
