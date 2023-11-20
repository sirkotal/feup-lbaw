function togglePaymentFields() {
    var paymentMethod = document.getElementById("payment_method").value;
    var creditCardFields = document.getElementById("credit_card_fields");
    var mbwayFields = document.getElementById("mbway_fields");
  
    if (paymentMethod === "Credit Card") {
      creditCardFields.style.display = "block";
      mbwayFields.style.display = "none";
      document.getElementById("mbway_phone").removeAttribute("required");
      document.getElementById("cname").setAttribute("required", "required");
      document.getElementById("ccnum").setAttribute("required", "required");
      document.getElementById("expmonth").setAttribute("required", "required");
      document.getElementById("expyear").setAttribute("required", "required");
      document.getElementById("cvv").setAttribute("required", "required");
    } else if (paymentMethod === "MBWAY") {
      creditCardFields.style.display = "none";
      mbwayFields.style.display = "block";
      document.getElementById("mbway_phone").setAttribute("required", "required");
      document.getElementById("cname").removeAttribute("required");
      document.getElementById("ccnum").removeAttribute("required");
      document.getElementById("expmonth").removeAttribute("required");
      document.getElementById("expyear").removeAttribute("required");
      document.getElementById("cvv").removeAttribute("required");
    }
  }
  