document.addEventListener('DOMContentLoaded', function(){
    // reveal login card
    setTimeout(function(){
        var card = document.querySelector('.animated-card');
        if(card) card.classList.add('visible');
    }, 120);

    // stagger demo buttons
    var demoBtns = document.querySelectorAll('.demo-btn');
    demoBtns.forEach(function(b,i){
        setTimeout(function(){ b.classList.add('visible'); }, 220 + (i*110));
    });

    // small entrance for header logo
    var logo = document.querySelector('.logo');
    if(logo){ logo.style.transform = 'translateY(-2px)'; logo.style.transition = 'transform 480ms ease'; }
});
