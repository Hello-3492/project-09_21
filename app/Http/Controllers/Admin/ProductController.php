<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\str;
use File;
use Image;

class ProductController extends Controller
{
    public function index(){
        return view('backend.product.index');
    }
    public function create(){
        return view('backend.product.create');
    }
    public function insert(Request $request){
        $pro = new product();
        $pro->name = $request->name;
        $pro->print = $request->print;
        $pro->description = $request->description;
        $pro->category_id = $request->category_id;
        if($request->hasFile('image')){
            $fliename = Str::random(10).'.'.$requeest->file('image')->getCloentOriginalExtension();
            $requeest->file('image')->move(public_path().'/backend/product/upload/image/',$fliename);
            image::make(public_path().'/backend/product/'.$fliename)->resize(200,200)->save(public_path().'/backend/product/resize/'.$fliename);

            $pro->file = $fliename;
        }else{
            $pro->image = "ไม่มีรูปภาพ";
        }
        $pro->save();
        return redirect('admin/product/index');
    }
}
