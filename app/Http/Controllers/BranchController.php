<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Purchase;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branchs = Branch::all();
        return view('admin.branch', compact('branchs'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required',
            'place' => 'string|nullable',
            'address' => 'string|required',
            'number' => 'required|numeric',
            'email' => 'string|required',
            'gst_no' => 'string|required',
        ]);

        $data = $request->all();
        $status = Branch::create($data);

        if ($status) {
            return redirect()->route('branch.index')->with('success', 'Product successfully created');
        } else {
            return redirect()->back()->with('error', 'Error, Please try again');
        }
    }


     public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('includes.edit_delete_branch', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string|required',
            'place' => 'string|nullable',
            'address' => 'string|nullable',
            'number' => 'string|nullable',
            'email' => 'string|nullable',
            'gst_no' => 'string|required',
        ]);

        $branch = Branch::findOrFail($id);
        $branch->name = $request->name;
        // $branch->place = $request->place;
        $branch->address = $request->address;
        $branch->number = $request->number;
        $branch->email = $request->email;
        $branch->gst_no = $request->gst_no;
        $branch->save();

        return redirect()->route('branch.index')->with('success', 'Product has been updated successfully!');
    }

    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        return redirect()->route('branch.index')->with('success', 'Product has been deleted successfully!');
    }

}
