@extends('layouts.app_admin')

@section('title', 'Upload Quiz')

@section('content')
    <header class="header">
        <h1>Upload Bundle:</h1>
    </header>
    @if(isset($bundle))
        <form method="POST" action="/admin/doEditBundle" enctype="multipart/form-data" class="">
        <input type="hidden" name="bl_id" value="{{ $bundle->id}}" />
    @else    
        <form method="POST" action="/admin/doUploadBundle" enctype="multipart/form-data" class="">
        
    @endif
    @csrf
    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />

    <input type="hidden" name="is_bundle" value="1" />

    <div class="form-group form-control">
        <label for="quiz_name">Bundle Name</label>
        <input type="text" name="quiz_name" value="{{ isset($bundle) ? $bundle->quiz_name : old('quiz_name') }}" />
    </div>
<!-- bundle -->
<div class="form-group form-control">
        <label for="cover_image">Link Quizes (start typing)</label>
        <input type="text" name="keyword" id="keyword" oninput="fetchCategories()">
    </div>
    <div class="" id="cat_list" style="">
    </div>

    <div id="my_cat" style="background-color: lightgray; border: 1px;" class="p-3 m-3">

@if(isset($quizes) && !empty($quizes))
    @foreach($quizes as $cur)

        <div data-id="{{ $cur->id }}">
            <span>{{ $cur->quiz_name }}</span>
            <a href="#" onclick="removeCategoryFromMyCat({{ $cur->id }})">Delete</a>
            <input type="hidden" name="Category_name-{{ $cur->id }}" value="Free 1">
        </div>
        
    @endforeach
@endif
<!-- <div data-id="1"><span style="margin-right: 10px;">GIT1</span><a href="#" onclick="removeCategoryFromMyCat(1)">Delete</a></div> -->

    </div>
<!-- end bundle --> 
    <div class="form-group form-control">
        <label for="category">Category</label>
        <input type="text" name="category" value="{{ isset($bundle) ? $bundle->category : old('category') }}" maxlength="255" />
    </div>
    <div class="form-group form-control">
        <label for="meta_keywords">Meta Keyword (Put comma(,) separated)</label>
        <input type="text" name="meta_keywords" value="{{ isset($bundle) ? $bundle->meta_keywords : old('meta_keywords') }}" maxlength="255" />
    </div>    
    <div class="form-group form-control">
        <label for="Featured">Featured</label>
        Yes &nbsp;&nbsp; <input type="checkbox" id="featured" name="featured" value="1" 
        @if (isset($bundle) && $bundle->featured === 1) checked=checked @endif>
    </div>
    <div class="form-group form-control">
        <label for="Active">Active</label>
        Yes &nbsp;&nbsp; <input type="checkbox" id="active" name="active" value="1" 
        @if (isset($bundle) && $bundle->active === 1) checked=checked @endif>
    </div>
    <div class="form-group form-control">
        <label for="quiz_price">Bundle Price</label>
        <input type="text" name="quiz_price" value="{{ isset($bundle) ? $bundle->quiz_price : old('quiz_price') }}" />
    </div>
    <div class="form-group form-control">
        <label for="short_description">Short Description</label>
        <input type="text" name="short_description" value="{{ isset($bundle) ? $bundle->short_description : old('short_description') }}" maxlength="255" />
    </div>
    <div class="form-group form-control">
        <label for="quiz_name">Quiz Description</label>
        <textarea name="quiz_description" value="">{{ isset($bundle) ? $bundle->quiz_description : old('quiz_description') }}</textarea>
    </div>
    <div class="form-group form-control">
        <label for="cover_image">Cover Image</label>
        <input type="file" name="cover_image" value="" />
        @if(isset($bundle->cover_image))
        <p><img src="/cover_images/{{ $bundle->cover_image }}" width="100" alt=""></p>
        @endif
    </div>


    <input type="submit" class="btn btn-block" value="Upload" />
    </form>

<script>
    
function fetchCategories() {
    const keyword = document.getElementById("keyword").value;
    fetch("{{ route('filter.quizzes') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ keyword: keyword })
    })
    .then(response => response.json())
    .then(data => displayCategories(data))
    .catch(error => console.error("Error fetching categories: ", error));
}

function displayCategories(categories) {
    const catListDiv = document.getElementById("cat_list");
    catListDiv.innerHTML = ""; // Clear previous list

    const dropdown = document.createElement("select");
    dropdown.setAttribute("id", "category-dropdown");
    dropdown.size=5;
    dropdown.setAttribute("onchange", "addCategoryToMyCat()");
    const defaultOption = document.createElement("option");
    defaultOption.text = "Select Quize/s";
    defaultOption.style.backgroundColor = "lightblue";
    dropdown.appendChild(defaultOption);

    categories.forEach(category => {
        const option = document.createElement("option");
        option.value = category.ID;
        option.text = category.Category_name;
        dropdown.appendChild(option);
    });

    catListDiv.appendChild(dropdown);
}

function addCategoryToMyCat() {
    const dropdown = document.getElementById("category-dropdown");
    const selectedOption = dropdown.options[dropdown.selectedIndex];

    if (selectedOption.value !== "") {
        const categoryId = selectedOption.value;
        const categoryName = selectedOption.text;

        const myCatDiv = document.getElementById("my_cat");

        // Check if the category is already selected
        if (document.querySelector(`[data-id="${categoryId}"]`) === null) {
            const newCategory = document.createElement("div");
            newCategory.setAttribute("data-id", categoryId);

            const categoryNameSpan = document.createElement("span");
            categoryNameSpan.style.marginRight = '10px';
            categoryNameSpan.textContent = categoryName;
            newCategory.appendChild(categoryNameSpan);

            const deleteLink = document.createElement("a");
            deleteLink.href = "#";
            deleteLink.textContent = "Delete";
            deleteLink.onclick = function() {
                removeCategoryFromMyCat(categoryId);
            };
            newCategory.appendChild(deleteLink);

            myCatDiv.appendChild(newCategory);

            const hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = `Category_name-${categoryId}`;
            hiddenInput.value = categoryName;
            myCatDiv.appendChild(hiddenInput);
        }

        dropdown.value = ""; // Reset dropdown to default option
    }
}

function removeCategoryFromMyCat(categoryId) {
    const categoryDiv = document.querySelector(`[data-id="${categoryId}"]`);
    if (categoryDiv !== null) {
        const categoryName = categoryDiv.firstChild.textContent;
        categoryDiv.remove();

        const hiddenInput = document.querySelector(`input[name="Category_name-${categoryId}"]`);
        if (hiddenInput !== null) {
            hiddenInput.remove();
        }
    }
}

document.getElementById("keyword").addEventListener("input", fetchCategories);

</script>
@endsection