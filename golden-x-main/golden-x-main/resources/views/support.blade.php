@extends('layouts.support')

@section('support')
<div class="support-empty ">
    <div>
        <img src="/images/support_hello.svg" class="support_hello_img">
        <div class="text-support_hello">
        	Выберите обращение слева<br /> <span>или</span> 
</div>
    	<a onclick="load('support/create')" class="support_create_blue is-ripples flare d-flex align-center has-ripple">Создайте новое
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="sp_crte">
<path d="M12 3.99999C12.5523 3.99999 13 4.44771 13 5V11L19 11C19.5523 11 20 11.4477 20 12C20 12.5523 19.5523 13 19 13L13 13L13 19C13 19.5523 12.5523 20 12 20C11.4477 20 11 19.5523 11 19L10.999 13L5 13C4.44772 13 4 12.5523 4 12C4 11.4477 4.44772 11 5 11L10.999 11L11 5C11 4.44771 11.4477 3.99999 12 3.99999Z" fill="white"/>
</svg>

</a>
    </div>
</div>
@endsection