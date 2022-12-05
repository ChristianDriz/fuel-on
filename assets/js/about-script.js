/* HEADER JS */
const header = document.querySelector('#index-navbar');
const brand = document.querySelector('#index-navbar-brand');
const nodeList = document.querySelectorAll("#index-nav-link");
const bar = document.querySelector('.navbar-toggler');


document.addEventListener('scroll', () => {
    var scroll_position = window.scrollY;
        for (let i = 0; i < nodeList.length; i++) {

        if (scroll_position > 300) {
            header.style.backgroundColor = '#ffffff';
            header.style.boxShadow = '0 2px 5px rgba(228,217,217,0.4)';
            brand.style.color = '#3e3d3d';
            nodeList[i].style.color = "#3e3d3d";
            bar.style.color = "rgba(0,0,0,0.74)";            
        
        } else {
            header.style.backgroundColor = 'rgba(255,255,255,0)';
            header.style.boxShadow = 'none';
            brand.style.color = 'white';
            nodeList[i].style.color = "white";
            bar.style.color = "white";

        }
    }
});
/* END HEADER JS */


