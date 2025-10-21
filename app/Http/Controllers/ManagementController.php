<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManagemenRec;
use App\Models\Management;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Purchase;

class ManagementController extends Controller
{
    public function index()
    {
        $managements = Management::with('categories')->get();
        $categories = Category::all();
        return view('admin.management', compact('managements', 'categories'));
        flash()->success('Success','Schedule has been created successfully !');

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'string|required',
            'product_code' => 'string|required',
            'Quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'branch' => 'string|required',
            'date' => 'string|required',
        ]);

        $category = Category::find($request->category_id);
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found.');
        }
        $data=$request->all();
        $status=management::create($data);

        if ($status) {
            return redirect()->route('management.index')->with('success', 'Product successfully created');
        } else {
            return redirect()->back()->with('error', 'Error, Please try again');
        }
    }


    public function edit($id)
    {
        $management=Management::find($id);
        if(!$management){
            request()->session()->flash('error','management not found');
        }
        return view('includes.edit_delete_management')->with('management',$management);
    }

    public function update(ManagemenRec $request, Management $management)
    {
        $request->validated();
        $management->product_name = $request->product_name;
        $management->product_code = $request->product_code;
        $management->Quantity = $request->Quantity;
        $management->price = $request->price;
        $management->branch = $request->branch;
        $management->category_id = $request->category_id;
        $management->date = $request->date;
        $management->save();

        flash()->success('Success','management Record has been Updated successfully !');

        return redirect()->route('management.index')->with('success');
    }

    public function destroy(Management $management)
    {
        $management->delete();
        flash()->success('Success','Employee Record has been Deleted successfully !');
        return redirect()->route('management.index')->with('success');
    }
}