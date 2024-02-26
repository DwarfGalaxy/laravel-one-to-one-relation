<p>{{$blog->id}}</p>
<p>{{$blog->title}}</p>
<p>{{$blog->slug}}</p>
<p>{{$blog->blog_info->description}}</p>
<p>
    <img  src="{{ asset('storage/images/blogs/'.$blog->blog_info->image) }}" style="height: 30px" alt="{{$blog->blog_info->image}}">
</p>