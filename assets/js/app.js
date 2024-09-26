// validate the form
$(document).ready(function() {
    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    function validatePhone(phone) {
        const regex = /^[0-9]{10}$/;
        return regex.test(phone);
    }
    function validateURL(url) {
        const regex = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([/\w .-]*)*\/?$/;
        return regex.test(url);
    }
    $("input[name='Supplier_Name']").on('input', function() {
        if ($(this).val().length === 0) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
    $("input[name='Mobile_one']").on('input', function() {
        if (!validatePhone($(this).val())) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
    $("input[name='Mobile_two']").on('input', function() {
        if ($(this).val().length > 0 && !validatePhone($(this).val())) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
    // $("input[name='Landline']").on('input', function() {
    //     if ($(this).val().length > 0 && !validatePhone($(this).val())) {
    //         $(this).addClass('is-invalid');
    //     } else {
    //         $(this).removeClass('is-invalid').addClass('is-valid');
    //     }
    // });
    $("input[name='Mail']").on('input', function() {
        if ($(this).val().length > 0 && !validateEmail($(this).val())) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
    $("input[name='Website']").on('input', function() {
        if ($(this).val().length > 0 && !validateURL($(this).val())) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
    $("input[name='Address1']").on('input', function() {
        if ($(this).val().length === 0) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
    $("select[name='State']").on('change', function() {
        if ($(this).val() === '') {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
    $("select[name='District']").on('change', function() {
        if ($(this).val() === '') {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
    $("input[name='City']").on('input', function() {
        if ($(this).val().length === 0) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
    $("input[name='Pincode']").on('input', function() {
        if ($(this).val().length !== 6 || isNaN($(this).val())) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
});
$(document).ready(function() {
    function validatePhone(phone) {
        const regex = /^[0-9]{10}$/;
        return regex.test(phone);
    }

    $("input[name='Mobile_one']").on('input', function() {
        let mobileOne = $(this).val();

        if (mobileOne.length > 10) {
            $(this).val(mobileOne.slice(0, 10));
        }

        if (!validatePhone(mobileOne)) {
            $(this).addClass('is-invalid').removeClass('is-valid');
        } else {
            $(this).addClass('is-valid').removeClass('is-invalid');
        }
    });

    $("input[name='Mobile_two']").on('input', function() {
        let mobileTwo = $(this).val();
        if (mobileTwo.length > 10) {
            $(this).val(mobileTwo.slice(0, 10));
        }
        if (mobileTwo.length > 0 && !validatePhone(mobileTwo)) {
            $(this).addClass('is-invalid').removeClass('is-valid');
        } else {
            $(this).addClass('is-valid').removeClass('is-invalid');
        }
    });
});

$(document).ready(function () {
    $('#name').on('input', function () {
      const name = $(this).val();
      if (/[^a-zA-Z\s]/.test(name)) {
        $('#nameError').text('Name can only contain letters and spaces.');
        $('#Submit').prop('disabled', true);
      } else {
        $('#nameError').text('');
        $('#Submit').prop('disabled', false);
      }
    });

    $('#mrp').on('input', function () {
      const mrp = $(this).val();
      if (mrp <= 0) {
        $('#mrpError').text('MRP must be greater than 0.');
        $('#Submit').prop('disabled', true);
      } else {
        $('#mrpError').text('');
        $('#Submit').prop('disabled', false);
      }
    });

    $('#qty').on('input', function () {
      const qty = $(this).val();
      if (qty < 0) {
        $('#qtyError').text('Quantity cannot be negative.');
        $('#Submit').prop('disabled', true);
      } else {
        $('#qtyError').text('');
        $('#Submit').prop('disabled', false);
      }
    });

  });