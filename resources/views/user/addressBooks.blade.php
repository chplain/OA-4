<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-5-29
 * Time: 下午10:16
 */?>
@extends('layout')
@section('contents')
            <div class="am-g">
                <!-- Row start -->
<!--                --><?php //var_dump($users);?>
                @foreach ($users as $user)
                <div class="am-u-md-3">
                    <div class="card-box widget-user">
                        <div>
                            <img src={{$user->avatar}} class="img-responsive img-circle" alt="user">
                            <div class="wid-u-info">
                                <h4 class="m-t-0 m-b-5 font-600">{{$user->nickname}}</h4>
                                <p class="text-muted m-b-5 font-13">{{$user->email}}</p>
                                <small class="text-warning"><b>{{$user->sex}}</b></small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
@endsection