<?php
class ImageService {
    private const MAX_FILE_SIZE = 2 * 1024 * 1024;
    private const MAX_FILES_COUNT = 10;
    private const ALLOWED_MIME_TYPES = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];

    public function uploadMultiple(array $files, int $articleId): array {
        $uploadedImages = [];
        if (empty($files["name"][0])) {
            return ["success" => false, "message" => "Không có file nào được chọn.", "uploaded" => []];
        }

        $fileCount = count($files["name"]);
        if ($fileCount > self::MAX_FILES_COUNT) {
            return ["success" => false, "message" => "Tối đa 10 ảnh mỗi bài.", "uploaded" => []];
        }

        $uploadDir = __DIR__ . "/../../public/uploads/articles/" . $articleId . "/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        for ($i = 0; $i < $fileCount; $i++) {
            if ($files["error"][$i] !== UPLOAD_ERR_OK) continue;
            if ($files["size"][$i] > self::MAX_FILE_SIZE) {
                return ["success" => false, "message" => "Ảnh vượt quá 2MB.", "uploaded" => []];
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($files["tmp_name"][$i]);

            if (!array_key_exists($mimeType, self::ALLOWED_MIME_TYPES)) {
                return ["success" => false, "message" => "Chỉ chấp nhận JPG, PNG, WebP.", "uploaded" => []];
            }

            $ext = self::ALLOWED_MIME_TYPES[$mimeType];
            $randomName = bin2hex(random_bytes(16)) . "." . $ext;
            $destination = $uploadDir . $randomName;

            if (move_uploaded_file($files["tmp_name"][$i], $destination)) {
                $uploadedImages[] = [
                    "original_name" => $files["name"][$i],
                    "file_path" => "uploads/articles/" . $articleId . "/" . $randomName,
                    "file_size" => $files["size"][$i]
                ];
            }
        }

        return ["success" => true, "message" => "Upload thành công.", "uploaded" => $uploadedImages];
    }
}

