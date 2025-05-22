<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Dotenv\Util\Str;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Accounts/Index', [
            'accounts' => Account::get()
        ]);
    }

    public function getAccountsToSend()
    {
        $accounts = Contact::select('id', 'firstname', 'lastname', 'account_id', 'phone')->with('account')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Accounts retrieved successfully',
            'accounts' => $accounts
        ])->setStatusCode(200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Accounts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $account = Account::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'city'          => $request->city,
            'state'         => $request->state,
            'zip'           => $request->zip,
            'country'       => $request->country,
            'created_by'    => Auth::id(),
            'updated_by'    => Auth::id()
        ]);

        $contact = Contact::create([
            'firstname'    => $request->firstname,
            'lastname'     => $request->lastname,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'account_id'    => $account->id,
            'created_by'    => Auth::id(),
            'updated_by'    => Auth::id()
        ]);



        return response()->json([
            'status' => 'success',
            'message' => 'Account created successfully',
            'account' => $account,
            'contact' => $contact
        ])->setStatusCode(200);


    }

    public function storeApp(Request $request){

        $accValition = Account::where('phone', $request->phone)->first();
        if($accValition){
            return response()->json([
                'status' => 'error',
                'message' => 'Account already exists'
            ])->setStatusCode(400);
        }

        $account = Account::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'city'          => $request->city,
            'state'         => $request->state,
            'zip'           => $request->zip,
            'country'       => $request->country,
            'created_by'    => 1,
            'updated_by'    => 1
        ]);

        $contact = Contact::create([
            'firstname'    => $request->firstname,
            'lastname'     => $request->lastname,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'account_id'    => $account->id,
            'created_by'    => 1,
            'updated_by'    => 1
        ]);



        return response()->json([
            'status' => 'success',
            'message' => 'Account created successfully',
            'account' => $account,
            'contact' => $contact
        ])->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Account = Account::with(['createdBy', 'editedBy', 'contacts', 'salesOrders'])->find($id);

        $Account->created_at = $Account->created_at->format('Y-m-d H:i:s');
        $Account->updated_at = $Account->updated_at->format('Y-m-d H:i:s');

        return Inertia::render('Accounts/Show', [
            'account'       => $Account,
            'contacts'      => $Account->contacts,
            'salesOrders'   => $Account->salesOrders,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $account)
    {
        $AccountRecord = Account::with('createdBy')->with('editedBy')->find($account);
        return Inertia::render('Accounts/Edit', [
            'customRecord' => $AccountRecord
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $account)
    {
        $Account = Account::find($account);
        $request->merge([
            'updated_by' => Auth::id()
        ]);
        $Account->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Account updated successfully',
            'account' => $Account
        ])->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $Account = Account::find($id);
        $Account->contacts()->delete();
        $Account->salesOrders()->delete();
        $Account->delete();
        return response()->json([
            'message' => 'Productb2b deleted successfully'
        ])->setStatusCode(200);
    }
}
