@push('css')
    @include('layouts.component.css')
@endpush
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chat') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card chat-app">
                            <div id="plist" class="people-list">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    </div>
                                    <input type="text" class="form-control searchInput" placeholder="Search...">
                                </div>
                                <ul class="list-unstyled chat-list mt-2 mb-0">
                                    @isset($leftPanel)
                                        @foreach ($leftPanel as $key => $item)
                                            <li class="clearfix conversationItem {{$key == 0 ? 'active' : ''}} {{$item->chat_id}}_chatItem" from="{{$userId}}" to="{{$item->user_id}}" chatId="{{$item->chat_id}}" image="https://bootdey.com/img/Content/avatar/avatar1.png" lastmessage="{{($item->lastMessage ?? '')}}" name="{{($item->user_name ?? '')}}">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
                                                <div class="about">
                                                    <div class="name">{{($item->user_name ?? '')}} <span class="badge badge-info ms-2 badgesCounter">{{(isset($item->unseen) ? ($item->unseen > 0 ? $item->unseen : '') : '')}}</span></div>
                                                    <div class="status lastMessage">{{($item->lastMessage ?? '')}}</div>                                            
                                                </div>
                                            </li>
                                        @endforeach
                                    @endisset
                                </ul>
                            </div>
                            <div class="chat">
                                <div class="chat-header clearfix selectedConversaction" from="" to="" chatId="" image="">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                                <img alt="">
                                            </a>
                                            <div class="chat-about">
                                                <h6 class="m-b-0 name"></h6>
                                                <small class="lastMessage"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-history">
                                    <ul class="m-b-0 historyList">
                                    </ul>
                                </div>
                                <div class="chat-message clearfix">
                                    <div class="input-group mb-0 disabledDiv">
                                        <div class="input-group-prepend submitMessage">
                                            <span class="input-group-text"><i class="fa fa-send"></i></span>
                                        </div>
                                        <input type="text" class="form-control messageInput" placeholder="Enter text here...">                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('script')
    @include('layouts.component.scripts')
@endpush
</x-app-layout>