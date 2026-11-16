# Module Xác thực và Quản lý tài khoản

Website tin tức (PHP thuần + MySQL, MVC nhẹ). Module này lo đăng nhập, đăng xuất, phân quyền Admin/Editor, bảo mật form và CRUD tài khoản.

## Chức năng
- Đăng nhập bằng username hoặc email, đăng xuất.
- Session, đọc lại user từ database mỗi request nên khóa tài khoản có hiệu lực ngay.
- Phân quyền hai vai trò: Admin, Editor.
- Quản lý tài khoản (chỉ Admin): xem danh sách có phân trang, thêm, sửa, đổi mật khẩu, khóa/mở khóa, xóa.
- CSRF token cho mọi form POST, hàm `e()` chống XSS, PDO prepared statements chống SQL injection.

## Phân quyền
| Chức năng | Khách | Editor | Admin |
|---|---|---|---|
| Xem trang công khai, tìm kiếm | Có | Có | Có |
| Đăng nhập/đăng xuất | Không áp dụng | Có | Có |
| Tạo/sửa/xóa bài của mình | Không | Có | Có |
| Sửa/xóa bài người khác | Không | Không | Có |
| Chuyển bài sang published/archived | Không | Không (chỉ pending) | Có |
| Quản lý chuyên mục | Không | Không | Có |
| Quản lý tài khoản | Không | Không | Có |

## Cấu trúc
```
app/
  core/         Auth.php, Csrf.php
  middleware/   AuthMiddleware.php
  controllers/  AuthController.php
  controllers/admin/ UserController.php
  models/       User.php
  helpers/      escape.php, flash.php, input.php
  routes_auth.php
views/admin/    login.php, users/index.php, users/form.php
database/       users.sql, seed_users.sql
docs/           bootstrap_session_mau.php, test/auth.md, slides_auth.md
```

## Cài đặt
1. Copy các thư mục vào project, không ghi đè file của người khác.
2. Tạo bảng `users` bằng `database/users.sql` (bỏ qua nếu schema chung đã có), rồi chạy `database/seed_users.sql`.
3. Trong bootstrap, gọi `session_start()` đúng một lần theo `docs/bootstrap_session_mau.php`, và nạp `app/helpers/*.php`.
4. Autoload các class trong `app/core`, `app/middleware`, `app/models`, `app/controllers`.
5. Đăng ký route từ `app/routes_auth.php`. Mỗi dòng gồm: method, URL, `[Controller, action]`, quyền (`null`, `'login'` hoặc `'admin'`).
6. Cần có `Database::pdo()` trả về đối tượng PDO (chế độ exception). Nếu dự án có lớp `View` với `View::render($view, $data)` thì controller sẽ dùng, nếu không sẽ nạp view trực tiếp.
7. Sau khi đăng nhập sẽ chuyển về `/admin`, route này cần tồn tại.

## Tài khoản demo
| Username | Mật khẩu | Vai trò | Trạng thái |
|---|---|---|---|
| admin01 | Admin@12345 | admin | active |
| editor01 | Editor@12345 | editor | active |
| locked01 | Locked@12345 | editor | locked |

Đổi hoặc xóa các tài khoản này trước khi deploy.

## Cách dùng cho các phần khác
```php
AuthMiddleware::requireLogin();
AuthMiddleware::requireRole('admin');
AuthMiddleware::requireRole('admin', 'editor');

Auth::check(); Auth::id(); Auth::user(); Auth::role();
Auth::hasRole('admin');
Auth::canEditArticle($article);
Auth::canSetStatus($status);
```
- Form POST: đặt `<?= Csrf::field() ?>` trong form và gọi `Csrf::check()` đầu action. Với AJAX gửi header `X-CSRF-Token` (giá trị `Csrf::token()`).
- Xuất dữ liệu văn bản thường bằng `<?= e($giaTri) ?>`. Nội dung editor đã lọc HTML thì không dùng `e()`.
- Đăng xuất, xóa, khóa phải dùng form POST có CSRF.

## Kiểm thử
Bảng test phân quyền ở `docs/test/auth.md`: chạy từng dòng, điền cột "Thực tế" và chụp ảnh làm bằng chứng.
