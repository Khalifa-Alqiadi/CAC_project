@extends('front.layout.home')
@section('content')
    <section>
        <div class="auctions-bg w-100 mt-5">

            <div class="d-flex  flex-wrap  auctions-bg-child ">
                <div></div>
                <h1 class="fw-bold w-100 text-center active mb-5">
                    مستكشف المركبات
                </h1>
                <p class="w-100 text-center text-lighter m-2 mb-5">
                    هل تبحث عن سيارات بحالة معينة؟
                    <br>
                    تبسيط البحث عن طريق تحديد فئة لتضييق تطاق نتائجك
                </p>
                <form action="" class="w-100 d-flex flex-wrap auction-form">
                    <div class="w-75 d-flex justify-content-between flex-wrap mx-auto">

                        <div class="my-2 mx-auto">
                            <select id="mod" class="text-center py-1">
                                @foreach ($model as $mod)
                                    <option value="{{ $mod->model }}">{{ $mod->model }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="my-2 mx-auto">
                            <select id="cate" class="text-center  py-1">
                                @foreach ($category as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="my-2 mx-auto">
                            <select id="coun" class="text-center  py-1">
                                @foreach ($cities as $cit)
                                    <option value="{{$cit->city}}">{{$cit->city}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="my-2 mx-auto">
                            <select id="type" class="text-center  py-1">
                                @foreach ($status as $status)
                                    @if ($status->status_car == 1)
                                        <option value="مستخدم">مستخدم</option>
                                    @elseif ($status->status_car != 1)
                                        <option value="جديد">جديد</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="my-2 mx-auto">
                            <select id="price" class="text-center py-1 d-flex">

                                <option value="اقل من 2000$">اقل من 2000$</option>
                                <option value="$2000 - $4000">$2000 - $4000</option>
                                <option class="$4000 - $7000">$4000 - $7000</option>
                                <option value="اكثر من 7000$">اكثر من 7000$</option>
                            </select>
                        </div>
                    </div>

                    <div class="w-100 d-flex justify-content-center">
                        <input type="submit" value="ابحث" class="py-1 px-5 my-3 contact text-light border-0">

                    </div>
                </form>

            </div>
        </div>
    </section>
    <section class="offers offers-page d-flex flex-column align-items-center pt-5 my-5 ">
        <h1 class="d-flex flex-wrap   yellow fs-3">المزادات الحالية </h1>

        <div class="d-flex flex-wrap  col-12 col-lg-9 gap-1">

            @foreach ($posts as $post)
                @if (isset($post->auctions[0]->is_active))
                    @if ($post->is_active == 1 && $post->end_date >= date('Y-m-d'))
                        <div class="card animate text-light m-auto  py-0 mb-3" style="width: 20rem;">
                            <a href="{{ route('auctiondetails', $post->id) }}"> <img src="/images/{{ $post->image }}"
                                    class="card-img-top p-3" height="220" alt="..."></a>
                            <div class="card-body py-0">

                                <h5 class="card-title text-center"><span class="cate"></span>{{ $post->name }} /
                                    <span class="mod">{{ $post->model }}</span>
                                    <span class="coun">{{ $post->city }}</span>
                                </h5>
                                <p class="text-center fs-7 card-details type">
                                    @if ($post->status_car == 1)
                                        جديد / {{ $post->category->name }}
                                    @else
                                        مستخدم / {{ $post->category->name }}
                                    @endif
                                </p>

                            </div>

                            <p class="text-center fs-7 card-details coun">
                               
                                     {{$post->city}}
                                
                              
                            </p>

                            <div class="card-body d-flex justify-content-between py-0">
                                <p href="#" class="card-link card-details ">سعر المزايدة/<span class="active price">


                                        {{ $post->auctions->max('bid_total') }}

                                    </span><i class="active">$</i>
                                </p>
                                <a href="{{ route('auctiondetails', $post->id) }}" class="card-link active  fs-7">تفاصيل<i
                                        class="fa fa-long-arrow-left p-2 pt-1"> </i></a>

                            </div>
                        </div>


                        <!--  the model   -->

                        <div class="modal fade user" id="auction{{ $post->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            @if (Auth::id())
                                <div class="modal-dialog">
                                    <div class="modal-content ">
                                        {{-- <form action="{{ route('bid_auction', $post->id) }}" method="post">
                                            @csrf --}}
                                            <div class="modal-header bg-darkgrey">

                                                <button type="button" class="btn-close yellow" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body bg-darkgrey  p-3  ">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul style="list-style: none">
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <h2 class="text-white fs-6 pb-3 "> هل انت متاكد تريد المزايدة على هذة
                                                    السيارة ودفع مبلغ مضاف الى قيمتها الحالية التي تقدر ب <em
                                                        class="yellow">{{ $post->auctions->max('bid_total') }}$</em>
                                                </h2>
                                                <div class="d-flex  align-items-center gap-3 ">
                                                    <h3 class="text-white fs-6"> مقدار الزيادة: </h3>
                                                    <input type="number" class="input-model text-white"
                                                        min="{{ $post->auction_ceiling }}"
                                                        step="{{ $post->auction_ceiling }}"
                                                        value="{{ $post->auction_ceiling }}" name="amount">
                                                </div>
                                                @php
                                                    $discount = 0.2 * $post->starting_price;
                                                @endphp
                                                <h3 class="yellow fs-7 mt-2">*يجب ان تكون مقدار الزيادة من مضاعفات
                                                    {{ $post->auction_ceiling }}$</h3>
                                                <h3 class="yellow fs-7 mt-2">*سيتم خصم من حسابك مبلغ وقدرة
                                                    {{ $discount }}$ حتى انتهاء العملية </h3>
                                                <input type="hidden" name="discount" value="{{ $discount }}">
                                            </div>
                                            <div class="modal-footer bg-darkergrey">
                                                <button type="button" class=" bg-lighter text-white fs-5"
                                                    data-bs-dismiss="modal">تراجع</button>
                                                <input type="submit" class="btn bg-yellow text-white fs-5"
                                                    value=" تاكيد  " />
                                            </div>
                                        {{-- </form> --}}
                                    </div>
                                </div>
                            @else
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
                            @endif

                        </div>
                    @endif
                @endif
            @endforeach
        </div>

    </section>
    {{ $posts->links('front.layout.paginate') }}

@endsection
