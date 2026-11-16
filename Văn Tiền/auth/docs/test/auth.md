# Bảng test phân quyền

Tài khoản test: `admin01` (admin), `editor01` (editor), `locked01` (editor, status = locked).
Điền cột **Thực tế** (Pass/Fail), chụp ảnh làm bằng chứng. Lỗi mức cao phải sửa và test lại.

## A. Ma trận vai trò x URL
| Vai trò | URL | Kỳ vọng | Thực tế |
|---|---|---|---|
| Khách | /admin | Chuyển về /admin/login | |
| Khách | /admin/articles | Chuyển về /admin/login | |
| Khách | /admin/users | Chuyển về /admin/login | |
| Editor | /admin/articles | Vào được | |
| Editor | /admin/users (gõ thẳng URL) | 403 | |
| Editor | Menu "Tài khoản" | Không hiển thị (ẩn ở Backend, không chỉ ẩn menu) | |
| Admin | /admin/users | Vào được | |
| Tài khoản bị khóa | Đăng nhập | Bị từ chối | |

## B. Đăng nhập / đăng xuất
| Tình huống | Kỳ vọng | Thực tế |
|---|---|---|
| Đúng tài khoản | Vào /admin | |
| Sai mật khẩu | Thông báo lỗi chung | |
| Sai username | Cùng thông báo lỗi chung | |
| Đăng xuất rồi bấm Back | Không xem lại được trang Admin | |
| Session id sau đăng nhập | Đã đổi (session_regenerate_id) | |

## C. Quản lý tài khoản
| Tình huống | Kỳ vọng | Thực tế |
|---|---|---|
| Admin tạo Editor, Editor đăng nhập | Thành công | |
| Trùng username / email | Bị từ chối, báo lỗi đúng trường | |
| Admin tự khóa / hạ quyền / xóa mình | Bị chặn | |
| Khóa tài khoản đang đăng nhập | Request kế tiếp bị đá ra | |
| Cột password_hash trong DB | Chuỗi hash bcrypt ($2y$...) | |
| Reset mật khẩu, đăng nhập mật khẩu mới | Thành công | |

## D. Bảo mật form
| Tình huống | Kỳ vọng | Thực tế |
|---|---|---|
| POST thiếu token | Từ chối (419) | |
| POST sai token | Từ chối (419) | |
| Xóa / khóa / đổi trạng thái bằng link GET | Không chạy | |
| Nhập `<b>x</b>` vào họ tên | Hiện nguyên văn, không in đậm | |

## E. Bổ sung sau rà soát
| Tình huống | Kỳ vọng | Thực tế |
|---|---|---|
| Gửi `username[]=x` (dữ liệu dạng mảng) | Không lỗi PHP, báo lỗi validation | |
| So sánh CSRF token trước và sau đăng nhập | Token khác nhau | |
| Cookie session (DevTools) | Có HttpOnly và SameSite=Lax | |
| Mọi route /admin/* trong router | Đều có requireLogin hoặc requireRole | |
