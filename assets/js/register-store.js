const form = document.querySelector("form"),
        secondpage = form.querySelector(".second-page"),
        thirdpage = form.querySelector(".third-page"),
        backto1st = form.querySelector(".backto1st-page"),
        backto2nd = form.querySelector(".backto2nd-page"),
        allInput1st = form.querySelectorAll("#first-form input"),
        allInput2nd = form.querySelectorAll("#second-form input");

secondpage.addEventListener("click", ()=> {
    allInput1st.forEach(input => {
        if(input.value != ""){
            form.classList.add('secActive');
        }else{
            form.classList.remove('secActive');
        }
    })
})
    backto1st.addEventListener("click", () => form.classList.remove('secActive'));


thirdpage.addEventListener("click", ()=> {
    allInput2nd.forEach(input => {
        if(input.value != ""){
            form.classList.add('thrdActive');
        }else{
            form.classList.remove('thrdActive');
        }
    })
})

backto2nd.addEventListener("click", () => form.classList.remove('thrdActive'));