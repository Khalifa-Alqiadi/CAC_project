@extends('admin.layout.dashboard')
@section('content')


<section>
  <div class="dash-header col-11 m-auto mt-5">
<div class="header-car col-2">
  <img src="assets/images/pmw.png" width="100%" alt="">
</div>
 
<div class="dash-header1">
<p class="fs-4 p-3">مرحبا!! {{Auth::user()->name}} </p>

</div>
 </div>

</section>


@endsection
                