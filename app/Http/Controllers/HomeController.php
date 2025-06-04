<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\projects;
use Illuminate\Support\Facades\Auth;

// To be used for registration
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $projects = projects::with('milestones:project_id,status')->get(['id','title', 'status']);
        return view('home')->with(['projects'=>$projects]);
    }

    public function clients()
    {
        $allclients = User::where('business_id', Auth::user()->business_id)->where('category','client')->get();
        return view('clients')->with(['allclients'=>$allclients]);
    }



    public function newClient()
    {
        return view('new-client');
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    public function saveClient(Request $request)
    {
        if($request->password!=""){
            $password = Hash::make($request->password);

        }else{
            $password =$request->oldpassword;
        }

        if($request->cid>0){
            $outcome = "modified";
        }else{
            $outcome = "created";
        }

        $user = User::updateOrCreate(['id'=>$request->cid],[
            'name'         =>$request->name,
            'email'        =>$request->email,
            'password'     =>Hash::make($request->password),
            'phone_number' =>$request->phone_number,
            'category'     => $request->category ?? 'client',
            'address'      =>$request->address,
            'status'       =>$request->status,
            'business_id'  =>Auth()->user()->business_id
        ]);

        // Assign Client role to client
        $user->assignRole('Client');

        $client = Client::updateOrCreate([
            'name'         =>$request->name,
            'email'        =>$request->email,
            'about'        =>$request->about,
            'phone_number' =>$request->phone_number,
            'company_name' =>$request->company_name,
            'address'      =>$request->address,
            'business_id'  => Auth()->user()->business_id,
            'user_id'      => $user->id,
        ]);

        $message = 'The '.$request->object.' has been '.$outcome.' successfully';

        return redirect()->route('clients')->with(['message'=>$message]);
    }

    public function editClient($cid)
    {
        $client = User::where('id',$cid)->first();
        return view('edit-client')->with(['client'=>$client]);
    }

    public function updateClient(Request $request, $cid)
    {
        $user = User::findOrFail($cid);

        if($request->password!=""){
            $password = Hash::make($request->password);

        }else{
            $password =$request->oldpassword;
        }

        $user->update([
            'name'         =>$request->name,
            'email'        =>$request->email,
            'password'     =>Hash::make($request->password),
            'phone_number' =>$request->phone_number,
            'category'     => $request->category ?? 'client',
            'address'      =>$request->address,
            'status'       =>$request->status,
        ]);

        $user->client->update([
            'name'         =>$request->name,
            'email'        =>$request->email,
            'about'        =>$request->about,
            'phone_number' =>$request->phone_number,
            'company_name' =>$request->company_name,
            'address'      =>$request->address,
        ]);

        $message = 'Client updated successfully';

        return redirect()->route('clients')->with(['message'=>$message]);
    }


    public function settings(request $request){
        $validateData = $request->validate([
            'logo'=>'image|mimes:jpg,png,jpeg,gif,svg',
            'background'=>'image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        if(!empty($request->file('logo'))){

            $logo = time().'.'.$request->logo->extension();

            $request->logo->move(\public_path('images'),$logo);
        }else{
            $logo = $request->oldlogo;
        }

        if(!empty($request->file('background'))){

            $background = time().'.'.$request->background->extension();

            $request->background->move(\public_path('images'),$background);
        }else{
            $background = $request->oldbackground;
        }


        settings::updateOrCreate(['id'=>$request->id],[
            'ministry_name' => $request->ministry_name,
            'motto' => $request->motto,
            'logo' => $logo,
            'address' => $request->address,
            'background' => $background,
            'mode'=>$request->mode,
            'color'=>$request->color,
            'ministrygroup_id'=>$request->ministrygroup_id,
            'user_id'=>$request->user_id
        ]);


        $message = "The settings has been updated!";
        return redirect()->back()->with(['message'=>$message]);
      }

}
