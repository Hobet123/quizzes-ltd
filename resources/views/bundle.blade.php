<!DOCTYPE html>
<html>
<head>
    <title>Category Selection</title>
</head>
<body>
    <form method="POST" action="/uploadBundle" enctype="multipart/form-data" class="">
    @csrf    
        <div style="padding: 10px;">
            <input type="text" name="keyword" id="keyword" oninput="fetchCategories()">
        </div>
        <div id="cat_list" style="background-color: lightgreen; padding: 10px;">
        </div>
        <div id="my_cat" style="background-color: lightblue; padding: 10px;"></div>
        <div class="form-group form-control">
            <label for="cover_image">Cover Image</label>
            <input type="file" name="cover_image" value="{{ old('cover_image') }}" />
        </div>
        <div class="form-group form-control">
            <label for="questions_images">Questions per Part</label>
            <input type="text" style="width: 45px;" name="per_part" value="20" />
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
    dropdown.setAttribute("onchange", "addCategoryToMyCat()");
    const defaultOption = document.createElement("option");
    defaultOption.text = "Select a Category";
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
</body>
</html>