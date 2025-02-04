<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name', 'Universal Booking Solution') }}</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
    	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    	<![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-rateyo@2.3.0/jquery.rateyo.min.css"/>
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        li.input-control.input-control-14.ui-sortable-handle::before {
    /* content: 'sd'; */
    content: "";
   
    margin-right: -16px !important;
}
    </style>
   
</head>
<body class="">
   
<div id="app">
        <!-- Header -->
        @include('layouts.header')
        
        <!-- Side nav bar -->
        @include('layouts.navbar')
      
        <!-- all contents -->
        @yield('content')
    </div>
<script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/pcoded.min.js') }}"></script>
<script src="{{ asset('assets/js/menu-setting.min.js') }}"></script>

<!-- Apex Chart -->
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

<!-- custom-chart js -->
<script src="{{ asset('assets/js/pages/dashboard-main.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-rateyo@2.3.0/jquery.rateyo.min.js"></script>
<script>
  jQuery(function($) {
//   const fbEditor = document.getElementById("build-wrap");
  const templateSelect = document.getElementById("formTemplates");
  //const saveform = document.getElementsByClassName("save-template")[0];

// //   var fields = [{
// //     label: 'Star Rating',
// //     attrs: {
// //       type: 'starRating'
// //     },
// //     icon: '🌟'
// //   }];
// //   var templates2 = {
// //     starRating: function(fieldData) {
// //       return {
// //         field: '<span id="' + fieldData.name + '">',
// //         onRender: function() {
// //           jQuery(document.getElementById(fieldData.name)).rateYo({
// //             rating: 3.6
// //           });
// //         }
// //       };
// //     }
// //   };
//   const formBuilder = jQuery(fbEditor).formBuilder( );
var fbEditor = document.getElementById('build-wrap');
// var fbEditor = document.getElementById('build-wrap');

// Define new custom field for New Section
var newfield = [{
  label: 'New Section',
  attrs: {
    type: 'newsection'
  },
  required: false,
  icon: '<i class="fa-solid fa-section"></i>'
}];

// Define custom template for New Section with Previous and Next buttons
var temp = {
  newsection: function (fieldData) {
    return {
      field: `
        <div id="${fieldData.name}" class="section">
        
          <div class="section-content">
            <!-- Add your content here, e.g., form fields, text, etc. -->
           
          </div>
          <div class="section-navigation">
            <button class="prev-btn" style="display:none;">Previous</button>
            <button class="next-btn">Next</button>
          </div>
        </div>`,
      onRender: function () {
        // On render, initialize button functionality
        var currentStep = 0; // Track the current section step

        // Handle Next Button Click
        $(document).on('click', `#${fieldData.name} .next-btn`, function () {
          currentStep++;
          showSection(currentStep);
        });

        // Handle Previous Button Click
        $(document).on('click', `#${fieldData.name} .prev-btn`, function () {
          currentStep--;
          showSection(currentStep);
        });

        // Function to show sections based on current step
        function showSection(step) {
          var allSections = $('.section');
          var totalSections = allSections.length;

          // Hide all sections and show the current one
          allSections.hide();
          if (step >= 0 && step < totalSections) {
            $(allSections[step]).show();
          }

          // Show/Hide Previous and Next buttons based on step
          if (step === 0) {
            $(`#${fieldData.name} .prev-btn`).hide();
          } else {
            $(`#${fieldData.name} .prev-btn`).show();
          }

          if (step === totalSections - 1) {
            $(`#${fieldData.name} .next-btn`).hide();
          } else {
            $(`#${fieldData.name} .next-btn`).show();
          }
        }

        // Initially show the first section
        showSection(currentStep);
      }
    };
  }
};

// Initialize formBuilder with the new section field and template
const formBuilder = $(fbEditor).formBuilder({
  fields: newfield,
  templates: temp
});



  const templates =  [
      {
        type: "text",
        label: "Name:",
        subtype: "text",
        className: "form-control",
        name: "text-1475765723950"
      },
      {
        type: "text",
        subtype: "email",
        label: "Email:",
        className: "form-control",
        name: "text-1475765724095"
      },
      {
        type: "text",
        subtype: "tel",
        label: "Phone:",
        className: "form-control",
        name: "text-1475765724231"
      },
      {
        type: "textarea",
        label: "Short Bio:",
        className: "form-control",
        name: "textarea-1475765724583"
      },
      {
    "type": "newsection",
    "required": false,
    "label": "New Section",
    "name": "newsection-1736235906446-0",
    "access": false
  }
    ]
  ;
  jQuery(window).on('load', function() {
 
    if (jQuery('#formsaddpage').length === 0) {

      console.log("fsdfsdfsdfsd testing");
    const selectedValue = document.getElementById('formTemplates').value;

    console.log("Selected Value:", selectedValue);

    const parsedValue = JSON.parse(selectedValue);
    parsedValue.forEach(item => {
            if (item.hasOwnProperty("required") && item.required === "false") {
                item.required = false;  // Change the 'required' to boolean false
            }
            if (item.hasOwnProperty("toggle") && item.toggle === "false") {
                item.toggle = false;  // Change the 'required' to boolean false
            }
            if (item.hasOwnProperty("access") && item.access === "false") {
                item.access = false;  // Change the 'required' to boolean false
            }
            if (item.hasOwnProperty("inline") && item.inline === "false") {
                item.inline = false;  // Change the 'required' to boolean false
            }
        });
    console.log(parsedValue);
   
    formBuilder.actions.setData(parsedValue);

      }

}); 
jQuery(window).on('load', function() {

  const saveform = document.querySelector(".save-template");


    saveform.addEventListener("click", function(e) {
         console.log("checking form me");
        var data = formBuilder.actions.getData();
        var inputValue = document.getElementById('formTemplatesname').value;
        if (jQuery('#formid').length>0) {
        var formid =  document.getElementById('formid').value;
        }else{
          var formid =  '';
        }
        var formData = {
            data: data, 
            formname: inputValue,
            formid: formid,
            _token: "{{ csrf_token() }}"  
        };

        // Send the data via AJAX to the specified route
        $.ajax({
            url: "{{ route('form.save') }}",   
            method: 'POST',
            data: formData,  
            success: function (response) {
              if (jQuery('#formid').length>0) {  
              window.location.href = 'http://127.0.0.1:8000/form';
              }else{
                window.location.href = 'http://127.0.0.1:8000/form';
              }
            },
            error: function (xhr, status, error) {
               
                console.error(xhr.responseText);
               
            }
        });
    });
} );



// const saveform = document.querySelector(".save-template");


// saveform.addEventListener("click", function(e) {
//      console.log("checking form me");
//     var data = formBuilder.actions.getData();
//     var inputValue = document.getElementById('formTemplatesname').value;
//     var formid =  document.getElementById('formid').value;
   
//     var formData = {
//         data: data, 
//         formname: inputValue,
//         formid: formid,
//         _token: "{{ csrf_token() }}"  
//     };

//     // Send the data via AJAX to the specified route
//     $.ajax({
//         url: "{{ route('form.save') }}", 
//         method: 'POST',
//         data: formData,  
//         success: function (response) {
            
//             console.log("Data saved successfully!");
           
//         },
//         error: function (xhr, status, error) {
           
//             console.error(xhr.responseText);
           
//         }
//     });
// });
} );





jQuery(window).on('load', function() {

  jQuery('#formTemplates').click();  


});
</script>


<script>
   

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toGMTString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function checkCookie() {
        var ticks = getCookie("modelopen");
        if (ticks != "") {
            ticks++;
            setCookie("modelopen", ticks, 1);
            if (ticks == "2" || ticks == "1" || ticks == "0") {
                jQuery('#exampleModalCenter').modal();
            }
        } else {
            // user = prompt("Please enter your name:", "");
            jQuery('#exampleModalCenter').modal();
            ticks = 1;
            setCookie("modelopen", ticks, 1);
        }
    }


  
	$('#exampleModal').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget)
		var recipient = button.data('whatever')
		var modal = $(this)
		modal.find('.modal-title').text('New message to ' + recipient)
		modal.find('.modal-body input').val(recipient)
	})


  jQuery(window).on('load', function() {
    jQuery('#mymodelsformessage').click(); 
    
});

</script>

</body>

</html>
