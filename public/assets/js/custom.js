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
// User delete Alert 
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

// Booking delete alert
function deleteBooking(id) {
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
      var booking = document.getElementById('deleteBooking-' + id);
      var bookingData = new FormData(booking);

      fetch(booking.action, {
        method: 'DELETE',
        body: bookingData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          swal("Poof! That booking has been deleted!", {
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
      swal("That booking is safe!", {
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
        onRender: function() 
        {
          var currentStep = 0;
          $(document).on('click', `#${fieldData.name} .next-btn`, function() {
            currentStep++;
            showSection(currentStep);
          });
          $(document).on('click', `#${fieldData.name} .prev-btn`, function() {
            currentStep--;
            showSection(currentStep);
          });
          function showSection(step) 
          {
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
    if (jQuery(".save-template").length > 0) {
      const saveform = document.querySelector(".save-template");
      saveform.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent the default form submission behavior
    
        // Get the input value and clear any existing error messages
        var inputElement = document.getElementById("formTemplatesname");
        var inputValue = inputElement.value.trim();
        var errorMessageElement = document.getElementById("formTemplatesname-error");
    
        if (errorMessageElement) {
          errorMessageElement.remove(); // Remove previous error message
        }
    
        // Check if the input value is empty
        if (!inputValue) {
          var errorMessage = document.createElement("span");
          errorMessage.id = "formTemplatesname-error";
          errorMessage.textContent = "The form name cannot be empty.";
          inputElement.parentNode.appendChild(errorMessage); // Append the message below the input
          inputElement.focus();
          return; // Stop execution if the field is empty
        }
    
        var data = formBuilder.actions.getData();
        var formid = document.getElementById("formid") ? document.getElementById("formid").value : "";
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    
        var formData = {
          data: data,
          formname: inputValue,
          formid: formid,
          _token: csrfToken,
        };
    
        // Perform the AJAX request
        $.ajax({
          url: "/form/save",
          method: "POST",
          data: formData,
          success: function (response) {
            if (jQuery("#formid").length > 0) {
              window.location.href = window.location.origin + "/form";
            } else {
              window.location.href = window.location.origin + "/form";
            }
          },
          error: function (xhr, status, error) {
            console.error(xhr.responseText);
          },
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
    // DataTable For Users Lists
    $('#user-list-table').DataTable();
    // DataTable For Forms Lists
    $('#form-list-table').DataTable();
    // DataTable For Booking Lists
    $('#booking-list-table').DataTable();
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
});

// Datatables for Bookings, Forms, and Users tables
$('#booking-list-table').DataTable();
$('#form-list-table').DataTable();
$('#user-list-table').DataTable();

// Add Booking 
document.addEventListener("DOMContentLoaded", function () {
  // Show the modal on page load
  $('#formTemplateModal').modal({backdrop: 'static', keyboard: false}).modal('show');
  // Handle template selection
  document.getElementById("select-template-btn").addEventListener("click", function () 
  {
    const formTemplateList = document.getElementById("form-template-list");
    const selectedOption = formTemplateList.options[formTemplateList.selectedIndex];

    if (formTemplateList.value === "") 
    {
      document.getElementById("form-template-error").style.display = "block";
    } else 
    {
      document.getElementById("form-template-error").style.display = "none";
      document.getElementById("booking_form_id").value = selectedOption.value;
      document.getElementById("booking_data").value = selectedOption.getAttribute("data-booking_data");
      $('#formTemplateModal').modal('hide');
      document.getElementById("booking-form").style.display = "block";
    }
  });
});

document.getElementById('loadTemplateBtn').addEventListener('click', function () {
  const selectedOption = document.querySelector('#formTemplateSelect option:checked');
  const bookingData = selectedOption.value;
  const bookingFormId = selectedOption.dataset.id;

  if (bookingData) {
      const formFields = JSON.parse(bookingData);
      const dynamicForm = document.getElementById('dynamicFormFields');
      dynamicForm.innerHTML = '';

      let formData = {};

      formFields.forEach(field => {
          let inputHtml = '';
          switch (field.type) {
              case 'text':
              case 'email':
              case 'number':
              case 'password':
              case 'tel':
              case 'date':
              case 'url':
                  inputHtml = `
                  <div class="form-group">
                      <label>${field.label || ''}</label>
                      <input 
                      type="${field.subtype || 'text'}" 
                      class="${field.className || 'form-control'}"
                      placeholder="${field.placeholder || ''}" 
                      name="${field.name || ''}" 
                      ${field.required === "true" ? 'required' : ''}>
                  </div>`;
                  break;
              case 'textarea':
                  inputHtml = `
                  <div class="form-group">
                      <label>${field.label || ''}</label>
                      <textarea 
                      class="${field.className || 'form-control'}"
                      placeholder="${field.placeholder || ''}" 
                      name="${field.name || ''}" 
                      ${field.required === "true" ? 'required' : ''}></textarea>
                  </div>`;
                  break;
              case 'select':
                  const options = field.values.map(option => `<option value="${option.value}">${option.label}</option>`).join('');
                  inputHtml = `
                  <div class="form-group">
                      <label>${field.label || ''}</label>
                      <select 
                      class="${field.className || 'form-control'}" 
                      name="${field.name || ''}" 
                      ${field.required === "true" ? 'required' : ''}>
                      ${options}
                      </select>
                  </div>`;
                  break;
              case 'radio-group':
                  inputHtml = `
                  <div class="form-group">
                      <label>${field.label || ''}</label>
                      <div>
                          ${field.values.map(option => `
                              <label class="${field.className || 'form-check-label'}">
                                  <input 
                                      type="radio" 
                                      name="${field.name || ''}" 
                                      value="${option.value}" 
                                      ${field.required === "true" ? 'required' : ''}>
                                  ${option.label}
                              </label>
                          `).join('')}
                      </div>
                  </div>`;
                  break;
              case 'checkbox-group':
                  inputHtml = `
                  <div class="form-group">
                      <label>${field.label || ''}</label>
                      <div>
                          ${field.values.map(option => `
                              <label class="${field.className || 'form-check-label'}">
                                  <input 
                                      type="checkbox" 
                                      name="${field.name || ''}[]" 
                                      value="${option.value}" 
                                      ${field.required === "true" ? 'required' : ''}>
                                  ${option.label}
                              </label>
                          `).join('')}
                      </div>
                  </div>`;
                  break;
              default:
                  inputHtml = `
                  <div class="form-group">
                      <label>${field.label || ''}</label>
                      <input 
                          type="${field.type || 'text'}" 
                          class="${field.className || 'form-control'}" 
                          name="${field.name || ''}" 
                          ${field.required === "true" ? 'required' : ''}>
                  </div>`;
          }
          // Append generated input HTML to the form
          dynamicForm.innerHTML += inputHtml;
      });

      // When form is submitted, capture values
      document.querySelector('form').addEventListener('submit', function(event) {
          // Empty formData before adding new data
          formData = {};
          // Capture all field values in formData
          formFields.forEach(field => {
              const fieldElement = document.querySelector(`[name="${field.name}"]`);
              if (fieldElement) {
                  if (field.type === 'checkbox-group' || field.type === 'radio-group') {
                      // Get selected checkboxes or radio buttons
                      formData[field.name] = [];
                      document.querySelectorAll(`[name="${field.name}"]:checked`).forEach(checkbox => {
                          formData[field.name].push(checkbox.value);
                      });
                  } else {
                      formData[field.name] = fieldElement.value;
                  }
              }
          });
          // Update hidden inputs with the form data in JSON format
          document.getElementById('bookingFormId').value = bookingFormId;
          document.getElementById('bookingData').value = JSON.stringify(formData);
      });
      // Close modal
      $('#formTemplateModal').modal('hide');
  } else {
      alert('Please select a form template.');
  }
});









