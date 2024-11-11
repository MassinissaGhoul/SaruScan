<?php
session_start();
include_once("header.php");
?>

<body>

    <!-- Users List -->
    <div class="container">
        <h2>Users List</h2>
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>user@example.com</td>
                    <td>✔</td>
                    <td><button>Remove rights</button></td>
                    <td><button>Delete</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Comics List -->
    <div class="container">
        <h2>Comics List</h2>
        <table>
            <thead>
                <tr>
                    <th>Comics</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Naruto</td>
                    <td>Kishimoto</td>
                    <td>Shonen</td>
                    <td>13/10/2024</td>
                    <td>
                        <button>Edit</button>
                        <button>Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Add Comics Form -->
    <div class="container">
        <h2>Add Comics</h2>
        <form action="#" method="post">
            <label for="comic">Comics</label>
            <input type="text" id="comic" name="comic"><br>

            <label for="author">Author</label>
            <input type="text" id="author" name="author"><br>

            <label for="description">Description</label>
            <input type="text" id="description" name="description"><br>

            <label for="category">Category</label>
            <input type="text" id="category" name="category"><br>

            <label for="image_path">Image Path</label>
            <input type="text" id="image_path" name="image_path"><br>

            <button type="submit">Add</button>
        </form>
    </div>

    <!-- Add Chapters Form -->
    <div class="container">
        <h2>Add Chapters</h2>
        <form action="#" method="post">
            <label for="comic_name">Comics</label>
            <input type="text" id="comic_name" name="comic_name"><br>

            <label for="chapter_number">Chapter Number</label>
            <input type="text" id="chapter_number" name="chapter_number"><br>

            <label for="title">Title</label>
            <input type="text" id="title" name="title"><br>

            <label for="chapter_image_path">Image Path</label>
            <input type="text" id="chapter_image_path" name="chapter_image_path"><br>

            <button type="submit">Add</button>
        </form>
    </div>

</body>
</html>
