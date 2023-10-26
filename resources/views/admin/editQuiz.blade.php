@extends('layouts.app_admin')

@section('title', 'Upload Quiz')

@section('content')
    <div>
        <h1>Edit Quiz:</h1>
    </div>
    <div class="container">
    <form action="/admin/doEditQuiz" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}" />
        <div>
            <p><a href="/quizDetails/{{ $quiz->id }}" target="_blank">Preview Page</a></p>
        </div>
        <div class="form-group form-control">
            <label for="quiz_name">Quiz Name</label>
            <input type="text" name="quiz_name" value="{{ $quiz->quiz_name }}" />
        </div>
        <!-- categories -->
        @include('.inc.categories')
        <!-- end categories -->
        <div class="form-group form-control">
            <label for="category">Category</label>
            <input type="text" name="category" value="{{ $quiz->category }}" maxlength="255" />
        </div>
        <div class="form-group form-control">
            <label for="meta_keywords">Meta Keyword (Put comma(,) separated)</label>
            <input type="text" name="meta_keywords" value="{{ $quiz->meta_keywords }}" maxlength="255" />
        </div>    
        <div class="form-group form-control">
           Yes &nbsp;&nbsp; <label for="Featured">Featured</label>
            <input type="checkbox" id="featured" name="featured" value="1" @if ($quiz->featured === 1) checked=checked @endif>
        </div>
        <div class="form-group form-control">
            <label for="Active">Active</label>
            Yes &nbsp;&nbsp; <input type="checkbox" id="active" name="active"  value="1" @if ($quiz->active === 1) checked=checked @endif>
        </div>
        <div>
            <p>Quiz Status:</p>
            
               <input type="radio" id="quiz_sts" name="quiz_sts" value="1" @if($quiz->quiz_sts === 1) checked @endif>
<label for="html">Draft</label>
            <br>

            
               <input type="radio" id="quiz_sts" name="quiz_sts" value="2" @if($quiz->quiz_sts === 2) checked @endif>
<label for="html">Waiting for approval</label> 
            <br>

            
               <input type="radio" id="quiz_sts" name="quiz_sts" value="0" @if($quiz->quiz_sts === 0) checked @endif>
<label for="html">Approved</label>
            <br>

        </div>
        <div class="form-group form-control">
            <label for="quiz_price">Quiz Price</label>
            <input type="text" name="quiz_price" value="{{ $quiz->quiz_price }}" />
        </div>
        <div class="form-group form-control">
            <label for="short_description">Short Description</label>
            <textarea name="short_description" value="">{{ $quiz->short_description }}</textarea>
        </div>
        <div class="form-group form-control">
            <label for="quiz_description">Quiz Description</label>
            <textarea name="quiz_description" value="">{{ $quiz->quiz_description }}</textarea>
        </div>
        <div class="form-group">
            <label for="cover_image">Cover Image</label>
            <input type="file" name="cover_image" class="form-control" value="" /> 
            <p><img src="/cover_images/{{ $quiz->cover_image }}" width="100" alt=""></p>
        </div>

        <!--  Switch -->
        <div>
            <hr>
        </div>
        <div class="form-group form-control">
            <div>
                <input type="radio" id="jsonRadio" name="fileType" value="json" onchange="handleRadioChange()" checked> JSON
            </div>
            <div>
                <input type="radio" id="xlsxRadio" name="fileType" value="xlsx" onchange="handleRadioChange()"> XLSX
            </div>
        </div>
        <div class="form-group">
            <input type="file" id="fileInput" class="form-control" name="json">
        </div>
        <div>
            <hr>
        </div>

        <!-- end switch -->

        <div class="form-group" id="queImg" style="display:none;">
            <label for="questions_images">Questions Images</label>
            <input type="file" name="questions_images" class="form-control" value="" />
        </div>

        <div class="form-group form-control">
            <label for="questions_images">Questions per Part</label>
            <input type="text" style="width: 45px;" name="per_part" value="{{ $quiz->per_part }}" />
        </div>


        <div class="form-group form-control">
            <label for="questions_images">Quiz Order</label>
            <input type="text" style="width: 55px;" name="quiz_order" value="{{ $quiz->quiz_order }}" />
        </div>


        <div>
            <h2>Questions:</h2>
            <div style="width: 100%; text-align: center; margin-bottom: 20px;"><a href="/admin/editQuizQAs/{{ $quiz->id }}">Edit Questions</a></div>
        </div>
        <input type="submit" class="btn btn-block" value="Edit Quiz" />

    </form>
    <script>
    function handleRadioChange() {
      var fileInput = document.getElementById('fileInput');
      var jsonRadio = document.getElementById('jsonRadio');
      var queImg = document.getElementById('queImg');

      
      if (jsonRadio.checked) {
        fileInput.name = 'json';
        queImg.style.display = "none";
        alert("You're switching to XLSX file!");
      } else {
        fileInput.name = 'xlsx';
        queImg.style.display = "block";
        alert("You're switching to XLSX file!");
      }
    }
  </script>
    </div>
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
