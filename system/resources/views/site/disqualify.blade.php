@extends('site.layouts.app')


@section('content')
    
<div class="jumbotron jumbotron-fluid bg-teal  border-top">
    <div class="container py-3">
        <h6 class="m-0 text-white">{!!$title!!}</h6>
    </div>
</div>

<div class="bg-white">
    <div class="container p-5">
        <div class=" p-5">
           
            <div class="w-75 mx-auto answer border bg-white p-5">
               <h6 class="font-weight-bold mb-3  bg-light p-3 ">Thank you for your interest in becoming a NI-Q Donor. But Due to your answers you have been disqualified from becoming a donor.</h6>
               <p>We look forward to reviewing your answers and determining if you are qualified to be a breast milk donor.  This process takes about 24 to 48 hours to complete, Once approved you will recieve an email with a temparay password to sign in to the donor portal.  Please keep a lookout for our approval email! once again thank you for your interest in becoming a donor.</p>
                <a class="btn btn-teal mt-3" href="http://ni-q.com">Return to NI-Q</a>
            </div>

        </div>
    </div>
</div>
@endsection
