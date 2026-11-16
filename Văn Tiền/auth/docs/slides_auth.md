# Gợi ý nội dung slide phần xác thực

## Slide 6: Xác thực và phân quyền
- Luồng đăng nhập: form -> CSRF -> `password_verify` -> `session_regenerate_id` -> vào /admin
- Đăng xuất: xóa session + cookie, Back không xem lại được (no-store)
- Middleware: `requireLogin` (mọi /admin/*), `requireRole('admin')` (/admin/users*)
- Hai vai trò: Admin (quản lý tất cả + tài khoản), Editor (bài viết, chuyên mục)
- Đọc lại user từ DB mỗi request -> khóa tài khoản có hiệu lực ngay

## Slide 7: Bảo mật và quản lý tài khoản
- Mật khẩu: bcrypt (`password_hash`), DB chỉ lưu hash
- CSRF token cho mọi form POST; hành động xóa/khóa chỉ bằng POST
- Chống XSS: hàm `e()` cho mọi dữ liệu xuất ra
- SQL injection: PDO prepared statements
- CRUD tài khoản: thêm / sửa / khóa-mở / reset mật khẩu / xóa; chặn tự khóa, tự hạ quyền, tự xóa
- Kết quả test: dẫn từ docs/test/auth.md (kèm ảnh chụp)
