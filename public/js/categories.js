 
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
    