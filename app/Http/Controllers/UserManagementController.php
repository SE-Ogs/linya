<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        return view('admin-panel.user-manage'); 
    }

    // Action Button Stuff. For testing purposes only, update methods when database is operational.
    public function edit($id) {
    return back()->with('message', "User: $id. Edited Successfully");
    }

    public function report($id) {
        return back()->with('message', "User: $id. Reported Successfully");
    }

    public function suspend($id) {
        return back()->with('message', "User: $id. Suspended Successfully");
    }

    public function destroy($id) {
        return back()->with('message', "User: $id. Deleted Successfully");
    }
}