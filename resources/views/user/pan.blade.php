<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-5-28
 * Time: 下午11:51
 */

?>
@extends('layout')
@section('contents')

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="/user/pan"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="row">
                        <div id="actions" class="col-md-12">
                            <div id="new_file" class="btn btn-default">
                                <form id="upload" enctype="multipart/form-data">
                                    <label class="" for="fileupload">
                                        <span class="glyphicon glyphicon-cloud-upload upload-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Upload</span>
                                    </label>

                                    <input id="fileupload" type="file" name="files[]" data-toggle="tooltip" data-placement="bottom" title="Max File Size:{{-- {{{ $maxFileSize }}}--}}" multiple>

                                    <input type="hidden" name="_method" value="PUT">

                                    <input type="hidden" name="dirPath" value="{{--{{{ $path }}}--}}">

                                </form>
                            </div>
                            <button id="new_folder" type="button" class="btn btn-default" aria-label="New Folder">
                                <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> New Folder
                            </button>
                            <button id="delete_object" type="button" class="btn btn-default" aria-label="Delete Objects">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete
                            </button>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div id="file_system" class="row" data-dirpath="{{--{{{ $path }}}--}}" data-maxfilesize="{{--{{{ $maxFileSizeBytes }}}--}}">
{{--                        @foreach ($objects as $object)--}}
                            <div class="col-xs-4 col-sm-3 col-md-2 object-container">
                                <div class="object" data-filetype="{{--{{{ $object->type }}}--}}" data-fullpath="{{--{{{ $object->path }}}--}}" data-ext="" data-basename="{{--{{{ $object->pathinfo['basename'] }}}--}}">
                                    <div class="icon-container">

                                        <div class="icon-base{{-- {{{ $object->type }}}--}}"></div>

                                        <div class="icon-main"></div>
                                    </div>
                                    <div class="name-container">
                                        <div role="button" class="name text-primary" title="{{--{{{ $object->pathinfo['basename'] }}}--}}">
                                            <a class="link" href="{{--{{{ $object->url }}}--}}">{{--{{{ $object->basename }}}--}}</a>
                                            <a href="#" class="hide rename"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
                                        </div>
                                        <div class="meta-info text-muted">{{--{{{ $object->lastModified }}}--}}</div>
                                    </div>
                                </div>
                            </div>
                      {{--  @endforeach--}}
                    </div>
                </div>

    {{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>--}}
    {{--<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>--}}
    {{--<script src="{{{ asset('/js/app.js') }}}"></script>--}}
    @endsection

