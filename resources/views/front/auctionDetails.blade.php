@extends('front.layout.home')
@section('content')
    <style>
        p {
            border-bottom: 0.5px solid rgb(49, 49, 49);
            width: 70%;
            padding: 2px;
        }

    </style>
    </div>
    @if (session()->has('message'))
            {
            <p class="message">{{ session()->get('message') }}</p>
            }
            @endif
    <section class="d-flex container mt-5 flex-wrap col-lg-9">
        <section class="col-12  col-lg-6 mt-5">
            <a href="" class="d-flex card-details fs-6 mb-1"><span class="fa fa-long-arrow-right pt-2 px-2"></span>رجوع</a>
            <div class="mt-1 mb-2">
                <h3 class="active mb-4"><span class="card-details fs-6"> السعر الاولي/</span>
                    {{ $post->starting_price }}$
                </h3>

                <div class="d-flex flex-wrap">
                    <div class="col-6 col-lg-4">

                        <p class="card-details mb-1 fw-bold">العنوان</p>
                        <p class="card-details mb-1 fw-bold">المدينة</p>
                        <p class="card-details mb-1 fw-bold">الموديل</p>
                        <p class="card-details mb-1 fw-bold">الماركة</p>
                        <p class="card-details mb-5 fw-bold">اللون</p>
                        <p class="card-details mb-1 fw-bold">المحرك</p>
                        <p class="card-details mb-1 fw-bold">الضرر</p>
                        <p class="card-details mb-1 fw-bold">حالة السيارة</p>
                        <p class="card-details mb-1 fw-bold">تاريخ انتهاء المزاد</p>
                        <p class="card-details mb-1 fw-bold"> الوصف</p>

                    </div>
                    <div class="col-6 col-lg-8">
                        <p class="mb-1 text-light ">{{ $post->address }}</p>
                        <p class="mb-1 text-light ">{{ $post->city }}</p>
                        <p class="mb-1 text-light ">{{ $post->model }}</p>
                        <p class="mb-1 text-light ">{{ $post->category->name }}</p>
                        <p class="mb-5 text-light ">{{ $post->color }}</p>
                        <p class="mb-1 text-light ">{{ $post->engin_car }}</p>
                        @if ($post->damage == 1)
                            <p class="mb-1 text-light ">لايوجد ضرر</p>
                        @elseif ($post->damage == 2)
                            <p class="mb-1 text-light ">ضرر سطحي</p>
                        @else
                            <p class="mb-1 text-light ">ضرر ثانوي</p>
                        @endif
                        @if ($post->status_car == 1)
                            <p class="mb-1 text-light ">جديد</p>
                        @else
                            <p class="mb-1 text-light ">مستخدم</p>
                        @endif

                        <p class="mb-1 text-light ">{{ $post->end_date }}</p>
                        <p class="mb-1 text-light "> {{ $post->description }}</p>

                    </div>
                    @php $total = $post->starting_price @endphp

                    @php $userId = [] @endphp
                    <div class=" border-n mt-4 col-10">
                        <form >
                            {{-- @csrf --}}

                            <div class="modal-body bg-darkgrey col-12 w-100">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul style="list-style: none">
                                            
                                        </ul>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-around  flex-column align-items-center mt-3 ">
                                    @foreach($auctions as $auction)
                                        @php $userId [] = $auction->userAw->id @endphp
                                    @endforeach
                                    <div class="d-flex  align-items-center gap-3 w-100 ">
                                        <h3 class="text-white fs-6"> </h3>
                                        <input type="number" class="input-model text-white p-2 auction-input" required
                                            min="{{ $post->auction_ceiling }}" step="{{ $post->auction_ceiling }}" value=""
                                            name="amount" placeholder="مقدار الزيادة" />
                                        {{-- @if (session()->has('error')) --}}
                                        
                                        {{-- @endif --}}

                                    </div>
                                    
                                </div>

                                @php
                                    $discount = 0.2 * $post->starting_price;
                                @endphp
                                <h3 class="text-white fs-7 mt-2 ">*يجب ان تكون مقدار الزيادة من مضاعفات
                                    <span class="yellow"> {{ $post->auction_ceiling }}$
                                </h3></span>
                                <h3 class="text-white  fs-7 mt-2">*سيتم خصم من حسابك مبلغ وقدرة
                                    <span class="yellow"> {{ $discount }}$ </span>حتى انتهاء العملية
                                </h3>
                                @if (isset($post->auctions[0]->bid_total))
                                    @if($post->auctions[0]->is_active == 1)
                                        <div>
                                            <h2 class="text-white fs-6 ">
                                                مبلغ المزايدة سيكون اكثر من <em
                                                    class="yellow">{{ $post->auction_ceiling}}$</em>
                                            </h2>
                                        </div>
                                    @endif
                                @endif
                                <input type="hidden" name="discount" value="{{ $discount }}">
                            </div>

                            @if ($post->end_date >= date('Y-m-d') && Auth::id() != $userAdmin->id && $post->users->id != Auth::id())
                                <a href="#"
                                    class='bg-yellow text-light fs-6 py-3 px-5 d-flex  align-items-center justify-content-center make-auction'
                                    data-bs-toggle="modal" data-bs-target="#auction{{ $post->id }}">مزايدة<i
                                        class="fa fa-long-arrow-left"> </i></a>
                            @endif
                        </form>

                    </div>
                    {{-- @php
                        $totals = ''
                    @endphp
                    @foreach($post->$auctions as $auc)
                        @php $totals = $auc->max('bid_total') @endphp
                    @endforeach --}}
                    
                    @if (Auth::user() == null)
                        <div class="modal fade user" id="auction{{ $post->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content m-auto ">
                                    <div class="modal-header bg-darkgrey">

                                        <button type="button" class="btn-close yellow" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <h6 class="text-center yellow mt-5 mb-5 ">عذرا يرجى تسجيل الدخول اولا حتى تتمكن من
                                        المزايدة!!</h6>
                                    <img class="m-auto" src="/assets/images/login_error.png" width="300" alt="">
                                    <a href="{{ route('login') }}" class="card-link active text-center mt-5 mb-5">
                                        تسجيل الدخول <i class="fa fa-long-arrow-left p-2 pt-1"> </i></a>
                                </div>

                            </div>
                        </div>
                    @elseif(!isset(auth()->user()->profile->user_id))
                        <div class="modal fade user" id="auction{{ $post->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content m-auto ">
                                    <div class="modal-header bg-darkgrey">

                                        <button type="button" class="btn-close yellow" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <h6 class="text-center yellow mt-5 mb-5 ">يمكنك اكمال اعدادات حسابك اولا</h6>
                                    <img class="m-auto" src="/assets/images/login_error.png" width="300" alt="">
                                    <a href="{{ route('complate_regester') }}" class="card-link active text-center mt-5 mb-5">
                                        اعدادات الحساب <i class="fa fa-long-arrow-left p-2 pt-1"> </i></a>
                                </div>

                            </div>
                        </div>
                    @else
                        <div class="modal fade user" id="auction{{ $post->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content  ">
                                    <form action="{{ route('test') }}" method="get">
                                        @csrf
                                        <div class="modal-header bg-darkgrey d-flex flex-column">
                                            <div class="w-100">
                                                {{-- <p class="messagError text-white"></p> --}}
                                                <input type="hidden" name="post_id" value="{{$post->id}}">
                                                <input type="hidden" name="post_name" value="{{$post->name}}">
                                                <input type="hidden" name="user_id" value="{{$post->users->id}}">
                                                <input type="hidden" name="bid_amount" value="">
                                                <input type="hidden" name="discount" value="{{$discount}}">
                                                <input type="hidden" name="total" value="{{$total}}">
                                                <input type="hidden" name="auction_ceiling" value="{{ $post->auction_ceiling}}">
                                            </div>
                                            <div class="w-100">
                                                <h2 class="text-white fs-4 p-3"> هل انت متاكد تريد المزايدة على السيارة بمبلغ
                                                    <em class="yellow"><span class="text-price"></span>$</em>
                                                </h2>
                                                @php $mess = 0 @endphp
                                                @if(isset($userAuction))
                                                    @foreach($userAuction as $user)
                                                        @if(isset($user->aw_user_id))
                                                            <h1></h1>
                                                        @else
                                                            @php $mess++ @endphp
                                                            @if ($mess = 1)
                                                                <h2 class="text-white fs-4 p-3"> ملاحظة :
                                                                    <span class="fs-6">سيتم سحب من حساب {{$discount}}$</span>
                                                                    <em class="yellow fs-6">
                                                                        <a href="{{route('faq')}}" class="card-link active text-center mt-5 mb-2"> معرفة المزيد <i class="fa fa-long-arrow-left p-2 pt-1"> </i></a></li>
                                                                    </em>
                                                                </h2>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="w-100">
                                                <button type="button" class=" bg-lighter text-white fs-5"
                                                        data-bs-dismiss="modal">تراجع</button>
                                                <input type="submit" class="bg-yellow text-white fs-5" value=" تاكيد  " />
                                            </div>
                                            
                                        </div>
                                        <div class="modal-body bg-darkgrey ">

                                        </div>
                                        <div class="modal-footer bg-darkergrey">
                                            
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    @endif

                </div>
            </div>




        </section>
        <section class="col-lg-6 col-sm-12 mt-5">
            <div class="col-12 d-flex justify-content-between align-items-center mt-4">
                <h3 class="text-light fw-bold card-details">{{ $post->name }}/ <span
                        class="active">{{ $post->model }}</span></h3>

                <div>

                    @if (isset($totalMax))
                        <h3 class="active fs-4"> <span class="card-details text-light fw-bold card-details fs-5"> السعر
                                الحالي/ </span>
                            {{$totalMax}}
                        </h3>
                    @else
                        <h3 class="active fs-4"> <span class="card-details text-light fw-bold card-details fs-5"> السعر
                                الحالي/ </span>
                            {{ $post->starting_price }}$
                        </h3>
                    @endif
                </div>


            </div>


            <div id="carouselExampleControls" class="carousel slide col-12 mt-3" data-bs-ride="carousel">
                <div class="carousel-inner main-img-div w-100">
                    <div class="carousel-item active w-100">
                        <img src="{{ URL::to('images/'.$post->image) }}" width="550" height="400" class="d-block  main-img"
                            alt="{{ $post->image }}">

                    </div>

                </div>

                <div class="col-lg-12 col-sm-12 d-flex flex-wrap justify-content-center align-items-center">


                    @php
                        $images = json_decode($post->multiple_image, true);
                        
                    @endphp


                    @foreach ($images as $image)
                        <div class="img-responsive">
                            <img src="{{ URL::to('/images/' . $image) }}" class="img-fluid"
                                alt="{{ $image }}">
                        </div>
                    @endforeach
                    <div class="img-responsive">
                        <img src="{{ URL::to('/images/' . $post->image) }}" class="img-fluid" alt="...">
                    </div>


                </div>
                <hr class="active">
                @php
                    $i=0;
                @endphp
              
               
                @foreach($auctions as $auction)
                    @php $i++ @endphp
                    @if ($i==1)
                        <h4  class="grey my-3"> عمليات المزايدة الحالية</h4>
                    @endif
                    @if($auction->is_active == 1)
                        <div class="">
                            <h2 class="text-white fs-6">
                                {{$auction->userAw->name}} زايد بمبلغ
                                <em class="yellow">{{ $auction->bid_amount }}$</em>
                                اصبح اجمالي المزايدة 
                                <em class="yellow">{{ $auction->bid_total }}$</em>
                            </h2>
                        </div>
                    @endif
                @endforeach
        </section>

    </section>

@endsection
