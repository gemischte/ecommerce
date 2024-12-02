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

  if (body.classList.contains('bg-primary')) {
    body.classList.remove('bg-primary');
    body.classList.add('bg-white');
    toggle.classList.remove('bi-brightness-high-fill');

    toggle.classList.add('bi-moon-fill');
  } 
  else {
    body.classList.remove('bg-white');
    body.classList.add('bg-primary');
    toggle.classList.remove('bi-moon-fill');
    toggle.classList.add('bi-brightness-high-fill');
  }
}

//Change Index background color
function Index_Change_bg_color() {
  const body = document.querySelector('.min-h-screen');
  const toggle = document.getElementById('toggleColor');

  if (body.classList.contains('bg-red-400')) {
    body.classList.remove('bg-red-400');
    body.classList.add('bg-stone-100');
    toggle.classList.remove('bi-brightness-high-fill');

    toggle.classList.add('bi-moon-fill');
  } 
  else {
    body.classList.remove('bg-stone-100');
    body.classList.add('bg-red-400');
    toggle.classList.remove('bi-moon-fill');
    toggle.classList.add('bi-brightness-high-fill');
  }
}

//Typed
//Source https://github.com/mattboldt/typed.js
window.onload = function (){
    new Typed('#element', {
    strings: ['<i>Hello </i>', 'Welcome.'],
    typeSpeed: 50,
    backSpeed:50,
    backDelay:500,
    cursorChar: '_',
    fadeOut: true,
    loop:true
  });
}

window.onload = function (){
  new Typed('#Indexhtml_Section_Title', {
  strings: ['<i>History of Programming Languages </i>'],
  typeSpeed: 50,
  backSpeed:50,
  backDelay:500,
  cursorChar: '~',
  // fadeOut: true,
  loop:true
});
}

//showpassword
function showpassword() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}