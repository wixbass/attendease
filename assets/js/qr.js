document.addEventListener('DOMContentLoaded', function () {
       const qrImg = document.querySelector('.qr-img');

       if (qrImg) {
              setTimeout(() => {
                     qrImg.style.display = 'none';
              }, 30 * 60 * 1000);
             
       }
})