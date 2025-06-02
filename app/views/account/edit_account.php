
<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-warning text-dark">
            <h3 class="mb-0">✏️ Chỉnh sửa tài khoản</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/webbanhang/Account/updateAccount" onsubmit="return validateForm();">
                <input type="hidden" name="id" value="<?php echo $account->id; ?>">
                
                <div class="mb-3">
                    <label for="username" class="form-label">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" class="form-control rounded-3" 
                           value="<?php echo htmlspecialchars($account->username, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="fullname" class="form-label">Họ tên:</label>
                    <input type="text" id="fullname" name="fullname" class="form-control rounded-3" 
                           value="<?php echo htmlspecialchars($account->fullname, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control rounded-3" 
                           value="<?php echo htmlspecialchars($account->email ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu mới (để trống nếu không đổi):</label>
                    <input type="password" id="password" name="password" class="form-control rounded-3">
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control rounded-3">
                </div>

                <div class="mb-4">
                    <label class="form-label">Quyền hạn:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="roleUser" value="user" 
                               <?php echo ($account->role != 'admin') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="roleUser">
                            Người dùng
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="roleAdmin" value="admin"
                               <?php echo ($account->role == 'admin') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="roleAdmin">
                            Quản trị viên
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/webbanhang/Account/manage" class="btn btn-outline-secondary">← Quay lại</a>
                    <button type="submit" class="btn btn-success">💾 Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateForm() {
    const username = document.getElementById('username').value.trim();
    const fullname = document.getElementById('fullname').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (username === '' || fullname === '' || email === '') {
        alert('Tên đăng nhập, họ tên và email không được để trống');
        return false;
    }
    
    // Kiểm tra mật khẩu nếu có nhập
    if (password !== '') {
        if (password.length < 6) {
            alert('Mật khẩu phải có ít nhất 6 ký tự');
            return false;
        }
        
        if (password !== confirmPassword) {
            alert('Mật khẩu xác nhận không khớp');
            return false;
        }
    }
    
    // Kiểm tra định dạng email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Email không hợp lệ');
        return false;
    }
    
    return true;
}
</script>

<?php include 'app/views/shares/footer.php'; ?>