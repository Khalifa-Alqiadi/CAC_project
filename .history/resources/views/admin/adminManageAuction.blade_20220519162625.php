@extends('admin.layout.dashboard')
@section('content')


<h1 class="text-center fs-3  text-white mt-4">  المزايدات الحالية </h1>
<div class="col-lg-4 col-8 mx-auto mb-5 mt-0">
    <span class="fa fa-search text-light"></span>
    <input type="text" placeholder="ابحث عن السياره" class="search-input w-100 px-5 text-light">
</div>
    <div class="container">
        @if(session()->has('success'))
            <div class="alert alert-success message">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="table-responsive text-white ms-5">
            <table class="main-table manage-members text-center table  text-white">
                <tr class="active">
                    <th>#ID</th>
                    <th class="u-name">السيارة </th>
                    <th> اسم البايع</th>
                    <th> اسم المزايد</th>
                    <th>انتهاء وقت المزايدة</ف>
                    <th>السعر الابتدائي  </th>
                    <th>المبلغ الذي وصل اليه </th>
                    <th>تفاصيل المزايدة </th>
                    <th>الحالة </th>

                </tr>
                @php $i = 1 @endphp
                @foreach($auctions as $auction)
                    @if($auction->auction_post->is_active == 1 && $auction->auction_post->end_date >= date('Y-m-d') && $auction->auction_post->status_auction == 0)
                        <tr>
                            <td>{{$i++}}</td>
                            <td class="u-name">{{$auction->auction_post->name}}</td>
                            <td>{{$auction->userOwner->name}}</td>
                            <td>{{$auction->userAw->name}}</td>
                            <td>{{$auction->auction_post->end_date}}</td>
                            <td>{{$auction->bid_amount}}</td>
                            <td>{{$auction->bid_total}}</td>
                            <td>
                                <a href="{{route('auctiondetails',$auction->post_id)}}" class="card-link active text-center mt-5 mb-2"> تفاصيل المزاد <i class="fa fa-long-arrow-left p-2 pt-1"> </i></a>
                            </td>
                            <td class="d-flex justify-content-center align-items-center">
                     
                    
                                {{-- <a href="" class='btn btn-danger' data-bs-toggle="modal" data-bs-target="#deletePayment{{$Payment->id}}">
                                    <i class='fa fa-close'></i> Delete
                                </a> --}}
                                @if($auction->is_active == 1)
                                   
                                    <label class="switch" data-bs-toggle="modal" data-bs-target="#active{{$auction->auction_post->id}}">
                                        <input type="checkbox" checked>
                                        <span class="slider"></span>
                                      </label>
                                @else
                                
                                    <label class="switch" data-bs-toggle="modal" data-bs-target="#active{{$auction->auction_post->id}}">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                      </label>
                                @endif
                                
                            </td>
                        </tr>
                        @endif
                        <div class="modal fade user" id="active{{$auction->auction_post->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content bg-dark">
                                    <form action="unactive_auction" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel"> تعديل حالة المزاد</h5>
                                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close" style="color: white !important;"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h2>هل انت متاكد</h2>
                                            <input type="hidden" name="auction_id" value="{{$auction->id}}">
                                            <input type="hidden" name="userid" value="{{$auction->aw_user_id}}">
                                            <input type="hidden" name="is_active" value="{{$auction->is_active}}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class=" bg-lighter text-white fs-5" data-bs-dismiss="modal">تراجع</button>
                                            <input type="submit" class=" bg-yellow text-white fs-5" value=" تاكيد   " />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    
                @endforeach

              
            </table>
        </div>
       
       
    </div>

@endsection
                
