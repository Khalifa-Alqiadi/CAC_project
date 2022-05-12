@extends('client.layout.clientdashboard')
@section('content')
<section class="col-11 m-auto">
<div class="dash-header col-lg-12  col-11 m-auto mt-5 d-flex justify-content-center align-items-center">

  <div class="dash-header1 col-8">
      <h4 class=" p-3"><em class="yellow">اسم المستخدم!!</em> {{ Auth::user()->name }} </h4>
      <h5 class=" p-3"><em class=""> اجمالي المبلغ في المحفضة</em> {{ Auth::user()->هي }} </h5>
  </div>

  <div class="dash-header2 col-4">

      <p class="hour ">
          @php
              print_r(date('H:i:s'));
              
          @endphp

      </p>
      <p>
          @php
              print_r(date('Y-m-d'));
              
          @endphp
      </p>


  </div>
</div>

<div class="dash-content d-flex flex-wrap justify-content-around m-auto" >

  <div class=" d-flex flex-wrap justify-content-around  col-12 mt-4 gap-3 m-auto">
      <div>
          <div class="card-admin text-light p-3" style="width:12rem">
              <div class="d-flex justify-content-between align-items-center">
                  <i class="yellow fs-1 bi bi-people-fill"></i>
                  <em class="fs-3"></em>
              </div>
              <div>
                  <p class="fs-7 p-dash grey">اجمالي المبلغ في المحفضة</p>
              </div>
          </div>
      </div>

       

  </div>


</div> 


</div>
</section>

@endsection