//Dropdown Menu
function Dropdown() {
  /* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
  document.getElementById("dropdown-list").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
  if (!event.target.matches('.dropbtn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      for (var i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
              openDropdown.classList.remove('show');
          }
      }
  }
};

//Change background color
function Change_bg_color() {
  const body = document.querySelector('body');
  const toggle = document.getElementById('toggleDark');

  if (body.classList.contains('bg-secondary')) {
    body.classList.remove('bg-secondary');
    body.classList.add('bg-white');
    toggle.classList.remove('bi-brightness-high-fill');

    toggle.classList.add('bi-moon-fill');
  } 
  else {
    body.classList.remove('bg-white');
    body.classList.add('bg-secondary');
    toggle.classList.remove('bi-moon-fill');
    toggle.classList.add('bi-brightness-high-fill');
  }
}

//Typed
//Source https://github.com/mattboldt/typed.js
document.addEventListener('DOMContentLoaded', function () {
  new Typed('#Register_Section_Title', {
      strings: ['Hello', 'Welcome.'],
      typeSpeed: 50,
      backSpeed: 50,
      backDelay: 500,
      cursorChar: '_',
      fadeOut: true,
      loop: true
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

//Input validation
document.addEventListener('DOMContentLoaded',function(){
  document.querySelectorAll('input','text').forEach(function (element) {
    element.addEventListener('input',function(){
      if(this.validity.valid){
        this.classList.add('valid');
        this.classList.remove('invalid');
      }
      else{
        this.classList.add('invalid');
        this.classList.remove('valid');
      }
    })
  })
});

//Confirm password
window.onload = function() {
  document.querySelector('form').addEventListener('submit', function (event) {
      var password = document.querySelector('#password').value;
      var confirmpassword = document.querySelector('#confirmPassword').value;

      if (password !== confirmpassword) {
          event.preventDefault(); // Prevent form submission
          Swal.fire({
            icon: 'error',
            title: 'Password Error',
            text: 'Passwords do not match.',
        })
      }
  });
};

//Get new year
window.addEventListener('load',function(){
  document.getElementById('year').textContent = new Date().getFullYear();
})