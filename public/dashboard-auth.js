// Dashboard-specific auth wiring
document.addEventListener('DOMContentLoaded', function(){
    var btn = document.getElementById('dashboardLogoutBtn');
    if(!btn) return;
    function update(){
        if(window.auth && window.auth.isLoggedIn && window.auth.isLoggedIn()){
            btn.style.display = 'inline-block';
        } else {
            btn.style.display = 'none';
        }
    }
    update();
    btn.addEventListener('click', function(){
        if(window.auth && typeof window.auth.logout === 'function'){
            window.auth.logout();
        } else {
            // fallback: clear demo key and redirect
            try{ localStorage.removeItem('ecocollect_logged_in'); }catch(e){}
            window.location.href = 'login.html';
        }
    });
});
