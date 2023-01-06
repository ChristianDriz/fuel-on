// (function(){
//     function readURL(input) {

//         if (input.files && input.files[0]) {
//             var reader = new FileReader();

//             reader.onload = function (e) {
//                 $('.avatar-bg').css({
//                     'background':'url('+e.target.result+')',
//                     'background-size':'cover',
//                     'background-position': '50% 50%'
//                 });
//             };

//             reader.readAsDataURL(input.files[0]);
//         }
//     }
//     $('.form-control').change(function(){
//         readURL(this);
//     });
// })();


var selDiv = "";
      var storedFiles = [];
      $(document).ready(function () {
        $(".image-input").on("change", handleFileSelect);
        selDiv = $(".avatar-bg");
      });

      function handleFileSelect(e) {
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        filesArr.forEach(function (f) {
          if (!f.type.match("image.*")) {
            return;
          }
          storedFiles.push(f);

          var reader = new FileReader();
          reader.onload = function (e) {
            var html =
              '<img src="' +
              e.target.result +
              "\" data-file='" +
              f.name +
              "alt='Category Image' height='150px' width='150px'>";
            selDiv.html(html);
          };
          reader.readAsDataURL(f);
        });
      }

// function clearimg(){
//     const clear = document.querySelectorAll(".imeyds");
//     for (let i = 0; i < clear.length; i++) {
//         clear[i].style.visibility = 'hidden';
//     }
// }
$(document).ready(function () {
    $('.cancel').click(function () {

        Swal.fire({
            title: 'Are you sure?',
            text: "You will discard the data you input.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
            }).then((result) => {
            if (result.isConfirmed) {
                location.href = "store-myproducts.php";
            }
        })
    });
});

/* show password */
var pass = false;
$('.show-pass').click(function () {
    if(pass){
        $('.input-pass').attr('type', 'password');
        pass = false;
    }
    else{
        $('.input-pass').attr('type', 'text');
        pass = true;
    }
});
/* end show password*/  