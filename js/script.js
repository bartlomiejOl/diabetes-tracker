function goToRegister() {
  window.location.href = '../html/registration.html';
}
$(document).ready(function () {
  $('.btn').click(function () {
    $('.items').toggleClass('show');
  });
});
