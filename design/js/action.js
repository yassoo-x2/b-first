$(function () {
    'use strict';


  //-------------------------- refrech part of page to get country
    
    $('.select_country').on('click', function() {
      var $button = $(this);
      var productName = $button.find('span[name]').attr('name');
      var maxcost = $button.find('span[class]').text();
      $('.country').empty();
      // Perform AJAX request
      $.ajax({
        url: 'refresh.php?page=country',  // The URL to the server-side script that generates the updated content
        method: 'POST',       // The HTTP method (e.g., GET, POST)
        data: { product: productName , cost : maxcost },
        success: function(response) {
          // Update the content element with the retrieved response
          $('.country').html(response);
        },
        error: function() {
          // Handle any errors that occur during the AJAX request
          console.log('Error occurred during AJAX request.');
        },

      });

    });
                //---------------------------------refresh sms
                var url = url = window.location.href;
                if (url.indexOf('active') !== -1) {

                setInterval(function(){

                  var id = $('.idorder').text();
                  var sms = $('.sms');
                  var cancel = $('.cancel');
                  $.ajax({
                    url :'refresh.php?page=refresh',
                    method : 'POST',
                    data : { orderid: id },
                    success : function(getsms){
                      sms.html(getsms);
                      if (getsms && getsms['sms'] && Array.isArray(getsms['sms']) && getsms['sms'].length > 0 && getsms['sms'][0]['code'] === null) {
                        cancel.remove();
                      }

                    },
                    error:function(){
                      console.log('erorr')
                    },
                    complete: function() {
                      // Re-enable the button
                      
                    }
                  })
                 
      
                },2000)
               
              }

              //--------------------cancel order
              $('.cancel').click(function(){
                var id = $('.idorder').text();
                var price = $('.price').text();
                var sms = $('.sms');
                $.ajax({
                  url:'refresh.php?page=cancel',
                  method:'POST',
                  data : {orderid : id,price:price},
                  success : function(cancel){
                    var smsarray = cancel.sms;
    
                    if (smsarray && smsarray.length > 0) {
                      sms.html(smsarray[0]['code']);
                    
                    }else{
                      window.location.href ='home.php?number=canceled';
                    }
                    
                  },
                  error : function(){
                    window.location.href ='home.php?tryagain=true';
    
                  },
                })
              })


       //-----------------progress bar
       var expirationTime = new Date($('.expires').text()).getTime();
       var now = new Date().getTime();
       var expire = expirationTime - now;
       // Update the progress bar
       
       // Update the progress bar every second
       setInterval(function progressBarInterval() {
        var now = new Date().getTime();
        var timeRemaining = expirationTime - now;
        var progress = (timeRemaining / expire) * 100; // Adding 1000 milliseconds for smooth transition
        $('.progress-bar').css('width', progress + '%');
    
        // Check if the expiration time has passed
        if (now >= expirationTime) {
          clearInterval(progressBarInterval);
          $('#progress-bar').css('display', 'none');
          $('#progress-bar').next().append('<p>Expire Time</p>');

          var id = $('.idorder').text();
          var price = $('.price').text();
          var sms = $('.sms');
          $.ajax({
            url:'refresh.php?page=cancel',
            method:'POST',
            data : {orderid : id,price:price},
            success : function(cancel){
                var smsarray = cancel.sms;
          
                if (smsarray && smsarray.length > 0) {
                  sms.html(smsarray[0]['code']);
                
                }else{
                  window.location.href ='home.php?number=canceled';
                }
                
              },
            error : function(){
              window.location.href ='home.php?tryagain=true';

            },
        })
      }
      }, 1000); 














})