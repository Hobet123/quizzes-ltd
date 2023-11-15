<div class="messages p-1">
    @if(!empty($error) && isset($error))
        <div class='errors_div p-3'>
            {{ $error }}
        </div>
    @endif

    @if(!empty($success) && isset($success))
        <div class='correct_div p-3'>
            {!! $success !!}
        </div>
    @endif

    @if (session('success'))
        <div class="correct_div p-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="errors_div p-3">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="errors_div p-3 pb-1">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>