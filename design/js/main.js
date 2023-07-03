/* globale $ */
$(function () {
  'use strict';




//-----------------------filter
  $('#filterInput').on('keyup', function() {
    var searchText = $(this).val().toLowerCase();
    $('#prodlist #product').filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1)
    });
  });
//---------------------show login form
  $('#login_btn').click(function() {
    if ($('#registration').is(':visible')) {
      $('#registration').hide();
    }
    Swal.fire({
      html:
      "<div class='container text-center' id='login_div' >"+
    "<form class='form-horizontal ' action='' method='POST'>"+
        "<h1 class='login-text'>login</h1>"+
        "<div class='input-group form-group'>"+
            "<div class=' input-box '>"+
                "<input type='text' id='login_input text' placeholder=' ' name='user'"+
                   " class='text-input form-control login_input' autocomplete required>"+
                "<span>User Name</span>"+
            "</div>"+
       "</div>"+
        "<div class='input-group form-group'>"+
            "<div class=' input-box '>"+
                "<input type='password' placeholder=' ' id='login_pass' name='pass' class=' form-control text-input'"+
                    "autocomplete='off' required>"+
                "<span>Password</span>"+
            "</div>"+
        "</div>"+
        "<div class='form-group'>"+
           "<button type='submit' name='loginForm' id='login_butt' class='btn btn-primary float-right login_butt '>login</button>"+
        "</div>"+
    "</form>"+
"</div>",
showConfirmButton: false,
    })
  });


  //-----------------show regster form

    $('#reg_btn').click(function() {
      if ($('#login_div').is(':visible')) {
        $('#login_div').hide();
      }
      Swal.fire({
        html:
"<div class='container' id='registration' >"+
    "<form class='needs-validation' action='' method='POST' name='regForm'"+
        "id='reg_form' novalidate>"+
        "<div class='mb-3'>"+
            "<label for='username' class='form-label'>Username:</label>"+
            "<input type='text' class='form-control' id='username' name='username' required>"+
            "<div class='invalid-feedback'>"+
                "Please enter a username."+
            "</div>"+
        "</div>"+
        "<div class='mb-3'>"+
            "<label for='email' class='form-label'>Email:</label>"+
            "<input type='email' class='form-control' id='email' name='email' required>"+
            "<div class='invalid-feedback'>"+
                "Please enter a valid email address."+
            "</div>"+
        "</div>"+
        "<div class='mb-3'>"+
            "<label for='phone' class='form-label'>Phone:</label>"+
            "<div class='input-group'>"+
                "<input type='tel' class='form-control' id='phone' name='phone' required>"+
            "</div>"+
            "<div class='invalid-feedback'>"+
                "Please enter a valid phone number."+
            "</div>"+
        "</div>"+
        "<div class='mb-3'>"+
            "<label for='password' class='form-label'>Password:</label>"+
            "<input type='password' class='form-control' id='password' name='password' required>"+
            "<div class='invalid-feedback'>"+
                "Please enter a password."+
            "</div>"+
        "</div>"+
        "<div class='mb-3'>"+
            "<label for='confirmPassword' class='form-label'>Confirm Password:</label>"+
            "<input type='password' class='form-control' id='confirmPassword' name='confirmPassword' required>"+
            "<div class='invalid-feedback'>"+
                "Passwords do not match."+
            "</div>"+
        "</div>"+
        "<button type='submit' class='btn btn-primary' name='reg_form'>Register</button>"+
    "</form>"+
"</div>",
  showConfirmButton: false,
      })
    });
  
    $('#reg_form').submit(function(event) {
      if (this.checkValidity() && validatePassword()) {
        // Perform form validation and registration logic here
      } else {
        event.stopPropagation();
        event.preventDefault();
      }
      $(this).addClass('was-validated');
    });

    $('#confirmPassword').on('input', function() {
      validatePassword();
    });

  
  function validatePassword() {
    var password = $('#password').val();
    var confirmPassword = $('#confirmPassword').val();
    var confirmPasswordInput = $('#confirmPassword').get(0);
  
    if (password !== confirmPassword) {
      confirmPasswordInput.setCustomValidity("Passwords do not match.");
      return false;
    } else {
      confirmPasswordInput.setCustomValidity("");
      return true;
    }
  }
  
  //--------------show or hide country


    var numToShow = 10; // Number of spans to initially show
    var $spans = $('#countrylist .country-feild');
    var numTotal = $spans.length; // Total number of spans
  
    // Hide spans after the initial number
    $spans.slice(numToShow).addClass('hidden');
  
    $('#showMoreBtn').on('click', function() {
      // Toggle visibility of the remaining spans
      $spans.slice(numToShow).toggleClass('hidden');
  
      // Update the button text
      var btnText = $(this).text();
      $(this).text(btnText === 'Show More' ? 'Show Less' : 'Show More');
    });


          //------------------------------------buy Button
          $(".country-feild").click(function() {
            
            $(".buy").remove();
            $(".country-feild").css({"width":"100%",
            "border-radius": "",
            "box-shadow": "none"});
            $(this).parent().addClass('d-flex justify-content-start');
            $(this).parent().append('<div style="width:30%" class="buy"><button type="submit"  class="buy-btn btn btn-primary" name="buy">Buy</button></div>');
            $(this).css({"width":"70%",
            "border-radius": "0.25rem 0 0 0.25rem",
            "box-shadow": "0px 1px 5px 0px var(--fir_shad)"});
  
          });


  
          //------------------------send value buy form
          $('.buy-form').submit(function() {
            var country = $(this).find('.country_sel').text();
            var product = $(this).find('.prod_country').text();
            console.log(country + product);
            $(this).append('<input type=hidden name="country" value="'+country+'">');
            $(this).append('<input type=hidden name="product" value="'+product+'">');

          });
           
          
          $(".prodlist button:odd").addClass('btn btn-primary');
          $(".prodlist button:even").addClass('btn btn-primary');

  //-------------------loading-----------------------
  $(window).on("load", function() {
    // When the page finishes loading, hide the loading overlay
    $("#loading-overlay").fadeOut("slow");
  });


});
//--------------------copy
$('.fa-clipboard').click(function() {
  var text = $('.address').text();
  
  // Create a temporary input element
  var tempInput = $('<input>');
  $('body').append(tempInput);
  
  // Set the value of the input element to the text
  tempInput.val(text).select();
  tempInput[0].setSelectionRange(0, 99999); // For mobile devices

  
  // Copy the text to the clipboard
  document.execCommand('copy');
  
  // Remove the temporary input element
  tempInput.remove();
  
  // Display a notification or perform any other action
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-right',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: false,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})
    Toast.fire({
        icon: 'success',
        title: 'Address is copeid'
    })
});