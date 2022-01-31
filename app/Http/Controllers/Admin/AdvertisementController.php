<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin\Advertisement;

class AdvertisementController extends Controller
{
    
    public function __construct() {
        $this->Advertisement = new Advertisement();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Advertisement';
        $active = 'advertisement';
        $advertisement = $this->Advertisement->first();
        return view('admin.advertisement.advertisement', compact('pageTitle', 'advertisement', 'active'));
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
            
        ]);
        $id = $request->id;
        $update = array(
            'title' => $request->title,
            'advertisement_text' => $request->advertisement_text
        );
        if ($request->hasFile('advertisement_image')) {
            $this->validate($request, [
                'advertisement_image' => 'mimes:jpg,jpeg,png,tiff,bmp',
            ]);
            
            $file = $request->advertisement_image;
            $file_name = str_replace(' ', '', strtolower($request->input('title'))) . '.' . $file->getClientOriginalExtension();
            $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR. 'advertisement'.DIRECTORY_SEPARATOR;
            $file->move($path, $file_name);
            $filenametostore = $file_name;
        } else {
            $filenametostore = '';
        }
        $update['advertisement_image'] = $filenametostore;
        $update['date_created'] = date('Y-m-d H:i:s');
        $advertisementData = $this->Advertisement->first();
        if($advertisementData){
        $updated = $this->Advertisement->where('id',$id)->update($update);
        }else{
            $updated = $this->Advertisement->insert($update);
        }
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
