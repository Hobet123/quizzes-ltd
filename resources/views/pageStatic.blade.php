@extends('layouts.app')

    @section('title',  $home->title)
    @section('description', $home->meta_keywords)
    @section('keywords', $home->meta_description)

    @section('content')

        <div class="container">


            <h4 >
                <b>{{ $home->title }}</b>
            </h4>
            <div>
                <?php echo $home->main_text; ?>
            </div>
        </div>
        
    @endsection
