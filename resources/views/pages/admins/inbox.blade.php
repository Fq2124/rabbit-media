@extends('layouts.auth.mst_admin')
@section('title', 'The Rabbits: Inbox | Rabbit Media – Digital Creative Service')

@push('styles')
    <link rel="stylesheet" href="{{asset('admins/modules/summernote/summernote-bs4.css')}}">
    <style>
        .compose {
            padding: 0;
            position: fixed;
            bottom: 0;
            right: 0;
            background: #fff;
            border: 1px solid #D9DEE4;
            border-right: 0;
            border-bottom: 0;
            border-top-left-radius: 5px;
            z-index: 20;
            display: none
        }

        .compose .compose-header {
            padding: 5px;
            background: #592f83;
            color: #fff;
            border-top-left-radius: 5px
        }

        .compose .compose-header .close {
            text-shadow: 0 1px 0 #fff;
            line-height: .8
        }

        .compose .compose-footer {
            padding: 10px
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Inbox</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{route('home-admin')}}">Dashboard</a></div>
                <div class="breadcrumb-item">Inbox</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="inbox">
                        <div class="card-header">
                            <h4>Messages</h4>
                            <div class="card-header-action">
                                <button id="compose" class="btn btn-sm btn-primary" type="button">
                                    <strong><i class="fas fa-edit"></i>&ensp;COMPOSE</strong>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($contacts) > 0)
                                <a href="javascript:void(0)" data-toggle-slide="#message-items"
                                   class="btn btn-primary btn-icon icon-left btn-lg btn-block mb-4 d-md-none">
                                    <i class="fas fa-list"></i> All Message</a>
                                <div class="tickets">
                                    <div class="ticket-items" id="message-items" style="height: 800px">
                                        @foreach($contacts as $row)
                                            @php
                                                $user = \App\User::where('email',$row->email);
                                                if($user->count()){
                                                    if($user->first()->ava == ""){
                                                        $ava = asset('admins/img/avatar/avatar-'.rand(1,5).'.png');
                                                    } else{
                                                        $ava = asset('storage/users/ava/'.$user->first()->ava);
                                                    }
                                                } else{
                                                    $ava = asset('admins/img/avatar/avatar-'.rand(1,5).'.png');
                                                }
                                            @endphp
                                            <div class="ticket-item" style="cursor: pointer" id="{{$row->id}}"
                                                 onclick="viewMail('{{$row->id}}','{{$row->name}}','{{$row->email}}',
                                                     '{{$ava}}','{{$row->subject}}','{{str_replace('\'', "’",$row->message)}}',
                                                     '{{\Carbon\Carbon::parse($row->created_at)->format('l, j F Y').
                                                     ' at '.\Carbon\Carbon::parse($row->created_at)->format('H:i')}}',
                                                     '{{encrypt($row->id)}}')">
                                                <div class="ticket-title">
                                                    <h4>{{ucfirst($row->subject)}}</h4>
                                                </div>
                                                <div class="ticket-desc">
                                                    <div>{{$row->name}}</div>
                                                    <div class="bullet"></div>
                                                    <div>{{\Carbon\Carbon::parse($row->created_at)->diffForHumans()}}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="ticket-content" id="message-contents"
                                         style="height: 800px;display: none"></div>
                                </div>
                            @else
                                <img class="img-fluid float-left" src="{{asset('images/searchPlace.png')}}" width="128">
                                <h5><em>There seems to be none of the feedback was found&hellip;</em></h5>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- compose -->
            <div class="compose col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="compose-header">
                    <strong id="compose_title">New Message</strong>
                    <button type="button" class="close compose-close text-white">
                        <span>×</span>
                    </button>
                </div>

                <div class="compose-body" style="margin: 1em">
                    <form action="{{route('admin.compose.inbox')}}" method="post" id="form-compose">
                        {{csrf_field()}}
                        <div class="row form-group">
                            <div class="col">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <input class="form-control" id="inbox_to" type="email" name="inbox_to"
                                           placeholder="To:"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-text-width"></i></span>
                                    </div>
                                    <input class="form-control" id="inbox_subject" type="text" name="inbox_subject"
                                           placeholder="Subject:" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <textarea class="summernote form-control" name="inbox_message"
                                          id="inbox_message"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button id="send" class="btn btn-block btn-primary" type="submit">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /compose -->
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{asset('admins/modules/summernote/summernote-bs4.js')}}"></script>
    <script>
        $(function () {
            @if($findMessage != null)
            $("#{{$findMessage}}").click();
            @else
            $("#message-items .ticket-item").first().click();
            @endif

            $("#message-items, .ticket-content").niceScroll({
                cursoropacitymin: 0,
                cursoropacitymax: .8,
                cursorwidth: 7,
                cursorcolor: "#592f83",
                cursorborder: "1px solid #592f83",
            });
        });

        $("#compose, .compose-close").on("click", function () {
            $(".compose").slideToggle()
        });

        $("#compose").on("click", function () {
            $("#compose_title").text('New Message');
            $('.summernote').summernote('code', '');
            $("#form-compose")[0].reset();
        });

        $("#form-compose").on('submit', function (e) {
            e.preventDefault();
            if ($('.summernote').summernote('isEmpty')) {
                swal('ATTENTION!', 'Please, write some messages!', 'warning');

            } else {
                $(this)[0].submit();
            }
        });

        function viewMail(id, name, email, ava, subject, message, date, deleteId) {
            $(".ticket-item").removeClass('active');
            $("#" + id).addClass('active');
            $(".compose").hide("slide");

            $("#message-contents").html(
                '<div class="ticket-header">' +
                '<div class="ticket-sender-picture img-shadow"><img src="' + ava + '" alt="Avatar"></div>' +
                '<div class="ticket-detail">' +
                '<div class="ticket-title"><h4>' + subject + '</h4></div>' +
                '<div class="ticket-info">' +
                '<div class="font-weight-600">' + name + '</div> <div class="bullet"></div> ' +
                '<div class="text-primary font-weight-600">' + date + '</div></div></div></div>' +
                '<div class="ticket-description"><p>' + message + '</p>' +
                '<div class="ticket-divider"></div></div>' +
                '<div class="btn-group" role="group">' +
                '<button class="btn btn-primary btn_reply' + id + '" type="button">' +
                '<i class="fas fa-reply mr-2"></i>Reply</button>' +
                '<button class="btn btn-info btn_forward' + id + '" type="button">' +
                '<i class="fas fa-share mr-2"></i>Forward</button>' +
                '<button class="btn btn-danger btn_delete_inbox' + id + '" type="button">' +
                '<i class="fas fa-trash mr-2"></i>Delete</button></div>'
            ).fadeIn("slow");

            $(".btn_reply" + id).on("click", function () {
                $("#compose_title").text('Reply Message');
                $("#inbox_to").val(email);
                $("#inbox_subject").val('Re: ' + subject);
                $('.summernote').summernote('code', '');
                $(".compose").slideToggle();
            });

            $(".btn_forward" + id).on("click", function () {
                $("#compose_title").text('Forward Message');
                $("#inbox_to").val('');
                $("#inbox_subject").val('Fwd: ' + subject);
                $('.summernote').summernote('code', message);
                $(".compose").slideToggle();
            });

            $(".btn_delete_inbox" + id).on("click", function () {
                var linkURL = '{{url('admin/inbox')}}/' + deleteId + '/delete';
                swal({
                    title: 'Delete Message',
                    text: "Are you sure to delete " + name + "'s message? You can't revert it!",
                    icon: 'warning',
                    dangerMode: true,
                    buttons: ["No", "Yes"],
                    closeOnEsc: false,
                    closeOnClickOutside: false,
                }).then((confirm) => {
                    if (confirm) {
                        swal({icon: "success", buttons: false});
                        window.location.href = linkURL;
                    }
                });
                return false;
            });

            $('html, body').animate({
                scrollTop: $('#inbox').offset().top
            }, 500);

            setTimeout(function () {
                $('.use-nicescroll').getNiceScroll().resize()
            }, 600);
        }
    </script>
@endpush
