<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use File;
use Image;

class ProductController extends Controller

{
    public function index(){
        $product = Product::orderBy('created_at','desc')->Paginate();
        return view('backend.product.index',compact('product'));
    }
    public function create(){
        $Category = Category::all();
        return view('backend.product.create',compact('Category'));
    }
    public function insert(Request $request){
        $validate = $request->validate([
            'name'=>'required|max:255',
            'price'=>'required|max:255',
            'description'=>'required',
            'image'=>'mimes:png,jpg,jpeg',
        ],[
            'name.required'=>'กรุณากรอกข้อมูลสินค้า',
            'name.max'=>'กรอกข้อมูลได้สูงสุด 255 ตัวอักษร',
            'price.required'=>'กรุณากรอกข้อมูลราคาสินค้า',
            'price.max'=>'กรอกข้อมูลได้สูงสุด 255 ตัวอักษร',
            'description.required'=>'กรอกข้อมูลรายละเอียดสินค้า',
            'image.mimes'=>'อัพโหลดภาพที่มีนามสกุล .jpg .jpeg .png ได้เท่านั้น',
        ]);

       $pro = new Product ();
       $pro->name = $request->name;
       $pro->price = $request->price;
       $pro->description = $request->description;
       $pro->category_id = $request->category_id;
       if($request->hasFile('image')){
        $filename = Str::random(10).'.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path().'/backend/product/',$filename);
        Image::make(public_path().'/backend/product/'.$filename)->resize(200,200)->save(public_path().'/backend/product/resize/'.$filename);

        $pro->image = $filename;
       }else{
        $pro->image = 'no_image.jpg';


    }
    $pro->save();
    alert()->success('แก้ไขข้อมูลสําเร็จ','ข้อมูลนี้ถูกอัพเดทแล้ว');
    return redirect('admin/product/index');
    }



    public function edit($products_id){
    $pro = Product::find($products_id);
    $cat = Category::all();
    return view('backend.product.edit',compact('pro','cat'));
    }

    public function update(Request $request, $products_id){
        $pro = Product::find($products_id);
        $pro->name = $request->name;
       $pro->price =$request->price;
       $pro->description = $request->description;
       $pro->category_id = $request->category_id;
       if($request->hasFile('image')){
        if($pro->image != 'no_image.jpg'){
            File::delete(public_path().'/backend/product/'.$pro->image);
            File::delete(public_path().'/backend/product/resize'.$pro->image);

        }
        $filename = Str::random(10).'.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path().'/backend/product/',$filename);
        Image::make(public_path().'/backend/product/'.$filename)->resize(200,200)->save(public_path().'/backend/product/resize/'.$filename);

        $pro->image = $filename;

    }
    $pro->update();
    alert()->success('แก้ไขข้อมูลสําเร็จ','ข้อมูลนี้ถูกอัพเดทแล้ว');
    return redirect('admin/product/index');



    }
    public function delete($products_id){
        $pro = Product::find($products_id);
        if($pro->image != 'no_image.jpg'){
            File::delete(public_path().'/backend/product/'.$pro->image);
            File::delete(public_path().'/backend/product/resize'.$pro->image);
    }
    $pro->delete();
    alert()->success('แก้ไขข้อมูลสําเร็จ','ข้อมูลนี้ถูกลบแล้ว');
    return redirect('admin/product/index');

}
}
