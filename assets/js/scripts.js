/* HEADER JS */
const header = document.querySelector('#index-navbar');
const brand = document.querySelector('#index-navbar-brand');
const nodeList = document.querySelectorAll("#index-nav-link");
const bar = document.querySelector('.navbar-toggler');


document.addEventListener('scroll', () => {
    var scroll_position = window.scrollY;
        for (let i = 0; i < nodeList.length; i++) {

        if (scroll_position > 800) {
            header.style.backgroundImage = 'none';
            header.style.backgroundColor = '#ffff';
            brand.style.color = '#000000';
            nodeList[i].style.color = "#000000";
            bar.style.color = "rgba(0,0,0,0.74)";            
        
        } else {
            header.style.backgroundImage = "url('assets/img/bg.png')";
            brand.style.color = "#ffffff";
            nodeList[i].style.color = "#ffffff";
            bar.style.color = "rgba(255,255,255,0.74)";
        }
    }
});
/* END HEADER JS */


/* show password */
var state = false;
$('#show-pass').click(function () {
    if(state){
        $('.input-pass').attr('type', 'password');
        $(this).attr('class', 'fas fa-eye-slash');
        
        state = false;
    }
    else{
        $('.input-pass').attr('type', 'text');
        $(this).attr('class', 'fas fa-eye');
        state = true;
    }
});
/* end show password*/  

/* register script*/
var Form1 = document.getElementById("form1");
var Form2 = document.getElementById("form2");
var Next = document.getElementById("next");
var Back = document.getElementById("back");
var Progress = document.getElementById("progress");

Next.onclick = function(){
    Form1.style.display = "none";
    Form2.style.display = "block";
    Progress.style.width = "240px";
}

Back.onclick = function(){
    Form1.style.display = "block";
    Form2.style.display = "none";
    Progress.style.width = "120px";
}
/* end register script*/


