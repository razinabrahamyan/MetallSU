@if(!empty($users))
    @foreach($users as $user)
        @include('includes.user.userSetting')
    @endforeach
@endif
