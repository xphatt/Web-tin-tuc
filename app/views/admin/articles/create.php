<?php
/**
 * create.php - Form Thêm mới bài viết (Dành cho TV04: Editor & Upload ảnh)
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm bài viết mới - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: "#editor",
            plugins: "anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount",
            toolbar: "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | removeformat",
            height: 400
        });
    </script>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Thêm Bài Viết Mới (TV04 Module)</h4>
                    </div>
                    <div class="card-body">
                        <form action="store-article.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề bài viết</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Chuyên mục</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="1">Tin tức chung</option>
                                    <option value="2">Công nghệ</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editor" class="form-label">Nội dung bài viết</label>
                                <textarea id="editor" name="content"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="images" class="form-label">Upload ảnh bài viết (Tối đa 10 ảnh, <= 2MB, JPG/PNG/WebP)</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/webp">
                            </div>
                            <button type="submit" class="btn btn-success">Lưu bài viết</button>
                            <a href="index.php" class="btn btn-secondary">Quay lại</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

