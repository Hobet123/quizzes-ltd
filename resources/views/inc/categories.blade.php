<!-- categories -->
<div class="form-group form-control">
        <label for="cover_image">Link Categories (start typing and select under)</label>
        <input type="text" name="keyword" id="keyword" oninput="fetchCategories()">
    </div>
    <div class="" id="cat_list" style="">
    </div>

    <div id="my_cat" style="background-color: lightgray; border: 1px;" class="p-3 m-3">

        @if(isset($cats) && !empty($cats))

        <?php
            // print_r($cats);
        ?>
            @foreach($cats as $cur)

                <div data-id="{{ $cur->id }}">
                    <span>{{ $cur->cat_name }}</span>
                    <a href="#" onclick="removeCategoryFromMyCat({{ $cur->id }})">Delete</a>
                    <input type="hidden" name="Category_name-{{ $cur->id }}" value="Free 1">
                </div>
                
            @endforeach
        @endif
    <!-- <div data-id="1"><span style="margin-right: 10px;">GIT1</span><a href="#" onclick="removeCategoryFromMyCat(1)">Delete</a></div> -->

    </div>
<!-- end categories --> 