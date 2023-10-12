@extends('layouts.app_admin')

@section('title', 'Upload Quiz')

@section('content')
    <header class="header">
        <h1>Upload Plain Quizz:</h1>
    </header>
    <form action="/admin/doUploadDuQuiz" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />

        <div class="form-group form-control">
            <label for="quiz_name">Quiz Name</label>
            <input type="text" name="quiz_name" value="{{ old('quiz_name') }}" />
        </div>
        <!-- categories -->
        @include('.inc.categories')
        <!-- end categories -->
        <div class="form-group form-control">
            <label for="category">Custom Category</label>
            <input type="text" name="category" value="{{ old('category') }}" maxlength="255" />
        </div>
        <div class="form-group form-control">
            <label for="meta_keywords">Meta Keyword (Put comma(,) separated)</label>
            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" maxlength="255" />
        </div>    
        <div class="form-group form-control">
            <label for="Featured">Featured</label>
            Yes &nbsp;&nbsp; <input type="checkbox" id="featured" name="featured" value="1">
        </div>
        <div class="form-group form-control">
            <label for="Active">Active</label>
            Yes &nbsp;&nbsp; <input type="checkbox" id="active" name="active" value="1">
        </div>
        <div class="form-group form-control">
            <label for="quiz_price">Quiz Price</label>
            <input type="text" name="quiz_price" value="{{ old('quiz_price') }}" maxlength="10" />
        </div>
        <div class="form-group form-control">
            <label for="short_description">Short Description</label>
            <textarea name="short_description" value="">{{ old('short_description') }}</textarea>
        </div>
        <div class="form-group form-control">
            <label for="quiz_name">Quiz Description</label>
            <textarea name="quiz_description" value="">{{ old('quiz_description') }}</textarea>
        </div>
        <div class="form-group form-control">
            <label for="cover_image">Cover Image</label>
            <input type="file" name="cover_image" value="{{ old('cover_image') }}" />
        </div>
        <div class="form-group form-control">
            <label for="">Questions per Part</label>
            <input type="text" style="width: 45px;" name="per_part" value="20" maxlength="10" />
        </div>
        <div class="form-group form-control">
            <label for="">Quiz Order</label>
            <input type="text" style="width: 55px;" name="quiz_order" value="777" />
        </div>
    
        <input type="submit" class="btn btn-block" value="Upload" />

    </form>
    <script>
    
    function fetchCategories() {
        const keyword = document.getElementById("keyword").value;
        fetch("{{ route('filter.categories') }}", {
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
        // const defaultOption = document.createElement("option");
        // defaultOption.text = "Select Quize/s";
        // defaultOption.style.backgroundColor = "lightblue";
        // dropdown.appendChild(defaultOption);
    
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
