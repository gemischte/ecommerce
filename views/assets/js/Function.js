//https://github.com/mattboldt/typed.js
document.addEventListener('DOMContentLoaded', function () {
  new Typed('#Register_Section_Title', {
    strings: ['Already have an account? ^100.', 'Now.'],
    typeSpeed: 50,
    cursorChar: '_',
    loop: true,
  });
});

//Showpassword
let PasswordVisible = false;

function showpassword() {
    const passwordLabel = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    if (PasswordVisible) {
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
        passwordLabel.type = 'password';
        PasswordVisible = false;
    } else {
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
        passwordLabel.type = 'text';
        PasswordVisible = true;
    }
}

//Show confirm password
let ConfirmVisible = false;
function confirm_show_password() {
  const passwordLabel = document.getElementById("confirmPassword");
  const confirm_password_eye_icon = document.getElementById("confirm_password_eye_icon");

  if (ConfirmVisible) {
    confirm_password_eye_icon.classList.remove('fa-eye-slash');
    confirm_password_eye_icon.classList.add('fa-eye');
    passwordLabel.type = 'password';
    ConfirmVisible = false;
  }
  else{
    confirm_password_eye_icon.classList.remove('fa-eye');
    confirm_password_eye_icon.classList.add('fa-eye-slash');
    passwordLabel.type = 'text';
    ConfirmVisible = true;
  }
}

//Filters
function fetchFilteredProducts() {
  const checkedBrands = Array.from(document.querySelectorAll('.brand-checkbox:checked')).map(cb => cb.value);
  const checkedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked')).map(cb => cb.value);

  const params = new URLSearchParams();
  if (checkedBrands.length > 0) {
    params.append('brands', JSON.stringify(checkedBrands));
  }
  if (checkedCategories.length > 0) {
    params.append('categories', JSON.stringify(checkedCategories));
  }

  fetch('functions/includes/filters.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: params.toString()
  })
  .then(response => response.text())
  .then(html => {
    document.getElementById('products-container').innerHTML = html;
  })
  .catch(err => {
    console.error('Error fetching filtered products:', err);
  });
}

document.querySelectorAll('.brand-checkbox, .category-checkbox').forEach(checkbox => {
  checkbox.addEventListener('change', fetchFilteredProducts);
});
