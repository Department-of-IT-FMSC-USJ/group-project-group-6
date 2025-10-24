// Index page specific scripts
document.addEventListener('DOMContentLoaded', function () {
  // Example: animate boxes or similar; placeholder
  var boxes = document.querySelectorAll('.box');
  boxes.forEach(function (b, i) {
    b.style.transition = 'transform 0.3s ease';
    b.addEventListener('mouseover', function () { b.style.transform = 'translateY(-6px)'; });
    b.addEventListener('mouseout', function () { b.style.transform = 'translateY(0)'; });
  });
});
