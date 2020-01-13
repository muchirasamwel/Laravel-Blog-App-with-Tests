@extends('layouts.app')
@section('title')
    <title>Advanced Databases Blogs</title>
@endsection
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <div>
        <div>
            <h1 class="text-center">Manage Your Blog</h1>
            <div class="d-flex justify-content-center">
                <ul>
                    @if($errors)
                        @foreach($errors as $err)
                            <li class="text-danger">{{$err}}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="d-flex justify-content-center">

                <form action="{{route('blog.store')}}" method="post" class="w-50 formB">
                    @csrf
                    <div class="form-group">
                        <label>Blog Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Blog content</label>
                        <textarea name="content" class="form-control" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Publisher ID</label>
                        <input type="text" name="user_id" class="form-control" readonly value="{{$user->id}}">
                    </div>
                    <div>
                        <button type="reset" class="btn btn-outline-secondary">clear</button>
                        <button type="submit" class="btn btn-primary mx-1">Add Blog</button>
                    </div>
                </form>
            </div>
            <div class="d-flex justify-content-center py-2">
                <table class="table w-75">
                    <thead class="table-dark">
                    <td>Blog Title</td>
                    <td>Blog Content</td>
                    <td>Blog Publisher</td>
                    <td>Action</td>
                    </thead>
                    <tbody>
                    @foreach($blogs as $blog)
                        <tr>
                            <td>{{$blog->title}}</td>
                            <td>{{$blog->content}}</td>
                            <td>{{$blog->user->name}}</td>
                            <td>
                                @if($user->name==$blog->user->name)
                                    <a href="/delete/{{$blog->id}}"
                                       data-method="delete"  class="btn btn-outline-danger">Delete</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection
