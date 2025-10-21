<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRec;

class CategoryController extends Controller
{

    public function index()
    {

        return view('admin.category')->with(['Category'=> Category::all()]);
    }

    public function store(CategoryRec $request)
    {
        $request->validated();

        $category = new Category;
        $category->name = $request->name;
        $category->save();
        flash()->success('Success','Employee Record has been created successfully !');
        return redirect()->route('category.index')->with('success');
    }

    public function edit($id)
    {
        $category=Category::find($id);
        if(!$category){
            request()->session()->flash('error','management not found');
        }
        return view('includes.edit_delete_category')->with('category',$category);
    }

    public function update(CategoryRec $request, Category $category)
    {
        $request->validated();

        $category->name = $request->name;
        $category->save();

        flash()->success('Success','Category Record has been Updated successfully !');

        return redirect()->route('category.index')->with('success');
    }


    public function destroy(Category $category)
    {
        $category->delete();
        flash()->success('Success','Category Record has been Deleted successfully !');
        return redirect()->route('category.index')->with('success');
    }
}