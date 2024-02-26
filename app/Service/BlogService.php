<?php
namespace App\Service;

use App\Models\Blog;
use App\Models\BlogInfo;
use Illuminate\Support\Facades\Storage;

// one to one relation
class BlogService{

    // =============POST============
    public function addService($request){
        // store single image in local storage folder
        if(isset($request['image'])){
            $timestamp=now()->timestamp;
            $originalName=$request['image']->getClientOriginalName();
            $imageName=$timestamp. '-' . $originalName;
            $request['image']->storeAs('public/images/blogs',$imageName);

            // update the image name in the $request array
            $request['image']=$imageName;
        };

        // store in database table
        // Blog::create($request);

        // =====one to one relation======
        // store in parent table
        $blog=Blog::create([
            'title'=>$request['title'],
            'slug'=>$request['slug'],
        ]);
        // store in child table
        BlogInfo::create([
            'image'=>$request['image'],
            'description'=>$request['description'],
            'blog_id'=>$blog->id,
        ]);
    }

    // ========GET(Read all)================
    public function fetchBlogs(){
    //    $blogs=Blog::paginate(10);  //Paginate with 10 records per page
    //    $blogs=Blog::where('published',true)->get(); //where published=true
    //    $blogs=Blog::select('SELECT * FROM blogs'); //raw sql
    //    $blogs=Blog::all(); //fetch all the records
       $blogs=Blog::with('blog_info')->get();  //fetch all data with one to one relation
    //    $blogs=Blog::with('blog_info')->paginate(10);  // fetch data relationship with one to one
       return $blogs;
    }


    // =========DELETE==========
    public function delete($blog){
        // delete image from local storage
        if(isset($blog->blog_info->image)){
            Storage::delete('public/images/blogs/'.$blog->blog_info->image);
        }
        // if cascade on delete not used:
        // $blog->blog_info->delete(); //delete child record
        // $blog->delete(); //delete parent record
        // if cascade on delete used:
        $blog->delete();
    }

    // ========FETCH(Single Blog)======
    public function singleBlog($blog){
        // $blogs=Blog::where('slug',$blog->slug)->first();
        return $blog;
    }

    // =======UPDATE(PUT)==============
    public function updateService($request, $blog){
        // $blog->update($request); //update data in db if no image & relation
        
        // Check if a new image is uploaded
        if(isset($request['image'])){
            // Delete the old image from storage folder
            Storage::delete('public/images/blogs/'.$blog->blog_info->image);
            // Store the new image
            $timestamp = now()->timestamp;
            $originalName = $request['image']->getClientOriginalName();
            $imageName = $timestamp . '-' . $originalName;
            $request['image']->storeAs('public/images/blogs', $imageName);
            // Update the image name in the $request array
            $request['image'] = $imageName;
        } else {
            $request['image'] = $blog->blog_info->image;
        }
    
        // Update in parent table
        $blog->update([
            'title' => $request['title'],
            'slug' => $request['slug'],
        ]);
    
        // Update in child table
        $blog->blog_info->update([
            'image' => $request['image'],
            'description' => $request['description'],
        ]);
    }
    
}