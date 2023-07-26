<?php
// Simulate fetching categories based on the keyword
$keyword = $_GET['keyword'] ?? '';

echo $keyword;

// exit;

// Dummy categories data for testing
// $categories = array(
//     array("ID" => 1, "Category_name" => "Cat1"),
//     array("ID" => 2, "Category_name" => "Cat"),
//     array("ID" => 3, "Category_name" => "Cat3"),
//     array("ID" => 4, "Category_name" => "Cat4"),
//     array("ID" => 4, "Category_name" => "asd"),
//     array("ID" => 5, "Category_name" => "Cat5")
// );

$categories = [
    ["ID" => 1, "Category_name" => "Cat1"],
    ["ID" => 2, "Category_name" => "asd"],

];

// Filter categories based on the keyword (for demonstration purposes)
// if (!empty($keyword)) {
//     $filteredCategories = array_filter($categories, function ($category) use ($keyword) {
//         return stripos($category['Category_name'], $keyword) !== false;
//     });
//     $categories = array_values($filteredCategories);
// }

// Set the response content type to JSON
header('Content-Type: application/json');

// Send the JSON response
echo json_encode($categories);
// echo json_encode($keyword);
?>
