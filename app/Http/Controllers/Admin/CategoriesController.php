<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin\Category;

class CategoriesController extends Controller {

    public function __construct() {
        $this->category = new Category();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $pageTitle = 'Categories';
        $active = 'categories';
        $where['status'] = getConstant('STATUS_ACTIVE');
        $categories = $this->category->getAllCategories($where);
        return view('admin.categories.listing', compact('pageTitle', 'categories','active'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $pageTitle = 'Categories';
        $active = 'categories';
        return view('admin.categories.add', compact('pageTitle','active'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'category_name' => 'required',
        ]);
        $saved = $this->category->saveCategory($request->input());
        if ($saved) {
            return redirect()->back()->with('message', 'Category Inserted Successfully');
        } else {
            return redirect()->back()->with('error', 'Category Not Inserted Successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $pageTitle = 'Categories';
        $active = 'categories';
        $where['id'] = $id;
        $where['status'] = getConstant('STATUS_ACTIVE');
        $categoryDetail = $this->category->getCategoryDetail($where);
        return view('admin.categories.edit',compact('pageTitle','active','categoryDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'category_name' => 'required',
        ]);
        $where['id'] = $id;
        $updated = $this->category->updateCategory($request->input(),$where);
        if ($updated) {
            return redirect()->back()->with('message', 'Category Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Category Not Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $where['id'] = $id;
        $deleted = $this->category->deleteCategory($where);
        if ($deleted) {
            return redirect()->back()->with('message', 'Category Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Category Not Deleted Successfully');
        }
    }

}
