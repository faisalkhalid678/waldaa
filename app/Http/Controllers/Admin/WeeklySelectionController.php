<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin\Weekly_selection;

class WeeklySelectionController extends Controller
{
    
    public function __construct() {
        $this->weekly_selection = new Weekly_selection();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Weekly Selection';
        $active = 'weekly_selection';
        $weekly_selection = $this->weekly_selection->first();
        return view('admin.weekly_selection.weekly_selection', compact('pageTitle', 'weekly_selection', 'active'));
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'link' => 'required',
            'id' => 'required',
        ]);
        $id = $request->id;
        $update = array(
            'title' => $request->title,
            'link' => $request->link
        );
        //print_r($update); die();
        $updated = $this->weekly_selection->where('id',$id)->update($update);
        if ($updated) {
            return redirect()->back()->with('message', 'Weekly Selection Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Weekly Selection Not Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
