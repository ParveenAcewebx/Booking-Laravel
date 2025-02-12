// Form delete alert
function deleteForm(id) {
  event.preventDefault();

  swal({
    title: "Are you sure?",
    text: "Once deleted, you will not be able to recover this imaginary file!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      var form = document.getElementById('deleteForm-' + id);
      var formData = new FormData(form);

      fetch(form.action, {
        method: 'DELETE',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          swal("Poof! That form has been deleted!", {
            icon: "success",
          }).then(() => {
            window.location.reload();
          });
        } else {
          swal("There was an errors!", {
            icon: "error",
          });
        }
      })
      .catch(error => {
        console.error('Error:', error);
        swal("There was an errors!", {
          icon: "error",
        });
      });

    } else {
      swal("That form is safe!", {
        icon: "info",
      });
    }
  });
}
//   User delete Alert 

function deleteUser(id) {
  event.preventDefault();

  swal({
    title: "Are you sure?",
    text: "Once deleted, you will not be able to recover this imaginary file!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      var form = document.getElementById('deleteUser-' + id);
      var formData = new FormData(form);

      fetch(form.action, {
        method: 'DELETE',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success === true) {
          swal("Poof! That User has been deleted!", {
            icon: "success",
          }).then(() => {
            window.location.reload();
          });
        } else if (data.success === 'login') {
          swal("That user is currently logged in.", {
            icon: "error",
          });
        } else {
          swal("There was an error!", {
            icon: "error",
          });
        }
      })
      .catch(error => {
        console.error('Error:', error);
        swal("There was an error processing your request.", {
          icon: "error",
        });
      });

    } else {
      swal("That user is safe!", {
        icon: "info",
      });
    }
  });
}
//  Form builder 

jQuery(function($) {
  const templateSelect = document.getElementById("formTemplates");
  const fbEditor = document.getElementById('build-wrap');

  var newfield = [{
    label: 'New Section',
    attrs: {
      type: 'newsection'
    },
    required: false,
    icon: '<i class="fa-solid fa-section"></i>'
  }];

  var temp = {
    newsection: function(fieldData) {
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
        onRender: function() {
          var currentStep = 0;

          $(document).on('click', `#${fieldData.name} .next-btn`, function() {
            currentStep++;
            showSection(currentStep);
          });

          $(document).on('click', `#${fieldData.name} .prev-btn`, function() {
            currentStep--;
            showSection(currentStep);
          });

          function showSection(step) {
            var allSections = $('.section');
            var totalSections = allSections.length;
            allSections.hide();
            if (step >= 0 && step < totalSections) {
              $(allSections[step]).show();
            }

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

          showSection(currentStep);
        }
      };
    }
  };

  const formBuilder = $(fbEditor).formBuilder({
    fields: newfield,
    templates: temp,
    controlPosition: 'left'
  });

  const templates = [{
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
  ];

  jQuery(window).on('load', function() {
    if (jQuery('#formsaddpage').length === 0 && jQuery('#formTemplates').length>0) {
      // console.log("fsdfsdfsdfsd testing");
      const selectedValue = document.getElementById('formTemplates').value;
       console.log("Selected Value:", selectedValue);

      const parsedValue = JSON.parse(selectedValue);
      parsedValue.forEach(item => {
        if (item.hasOwnProperty("required") && item.required === "false") {
          item.required = false;
        }
        if (item.hasOwnProperty("toggle") && item.toggle === "false") {
          item.toggle = false;
        }
        if (item.hasOwnProperty("access") && item.access === "false") {
          item.access = false;
        }
        if (item.hasOwnProperty("inline") && item.inline === "false") {
          item.inline = false;
        }
      });
      console.log(parsedValue);

      formBuilder.actions.setData(parsedValue);
    }
  });

  jQuery(window).on('load', function() {
    if(jQuery(".save-template").length >0){
    const saveform = document.querySelector(".save-template");

    saveform.addEventListener("click", function(e) {
      // console.log("checking form me");
      var data = formBuilder.actions.getData();
      var inputValue = document.getElementById('formTemplatesname').value;
      var formid = document.getElementById('formid') ? document.getElementById('formid').value : '';
      var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      var formData = {
        data: data,
        formname: inputValue,
        formid: formid,
        _token: csrfToken
      };

      $.ajax({
        url: "/form/save",
        method: 'POST',
        data: formData,
        success: function(response) {
          if (jQuery('#formid').length > 0) {
            window.location.href = window.location.origin +'/form';
          } else {
            window.location.href = window.location.origin +'/form';
          }
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    });
  }
  });

  jQuery(window).on('load', function() {
    jQuery('#formTemplates').click();
  });

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
  });

  jQuery(window).on('load', function() {
    jQuery('#mymodelsformessage').click();
  });
  $('#booking-list-table').DataTable();
  $('#form-list-table').DataTable();
  $('#user-list-table').DataTable();
});

