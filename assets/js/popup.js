// SCRIPT FOR RATE POPUP
let popup = document.getElementById("popup");
let dim = document.getElementById("popup-container");

function openPopup(){
    popup.classList.add("open-popup");
    dim.classList.add("active");
}

function closePopup(){
    popup.classList.remove("open-popup");
    dim.classList.remove("active");
}

// SCRIPT FOR CART POPUP
let cartpopup = document.getElementById("cartpopup");

function cartPopup(){
    cartpopup.classList.add("cart-popup");
}

window.addEventListener("click", function Popup(){
    setTimeout(
        function open(event){
            cartpopup.classList.remove("cart-popup");
        },
        3000
    )
});

// SCRIPT FOR CONFIRMATION POPUP
let confirm = document.getElementById("popup-confirmation");

function register(){
    confirm.classList.add("open-popup");
    dim.classList.add("active");
}

function NO(){
    confirm.classList.remove("open-popup");
    dim.classList.remove("active");
}

let success = document.getElementById("popup-registration-success");

function YES(){
    success.classList.add("open-popup");
    confirm.classList.remove("open-popup");
    dim.classList.add("active");
}

function OK(){
    success.classList.remove("open-popup");
    dim.classList.remove("active");
}

function myFunction(){
    const list = document.querySelectorAll(".hide");
    for (let i = 0; i < list.length; i++) {
 list[i].style.visibility = "hidden";
    }
}
