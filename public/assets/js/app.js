document.addEventListener('DOMContentLoaded', function() {
    // Form doğrulama işlemleri
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Dinamik filtreleme işlemi
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('change', function() {
            this.submit();
        });
    }

    // Sepet işlemleri
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const productId = this.dataset.productId;
            addToCart(productId);
        });
    });

    // Ürün sepete eklendiğinde bir mesaj göster
    function addToCart(productId) {
        // Burada sepet işlemi yapılır (AJAX kullanılabilir)
        alert('Ürün sepete eklendi: ' + productId);
    }

    // Ürün detayı sayfasında not ekleme işlemi
    const addNoteButton = document.getElementById('addNote');
    if (addNoteButton) {
        addNoteButton.addEventListener('click', function() {
            const note = document.getElementById('productNote').value;
            if (note.trim() !== '') {
                alert('Not eklendi: ' + note);
                // Burada not ekleme işlemi yapılabilir
            }
        });
    }

    // Sipariş özetini PDF olarak indir
    const downloadPdfButton = document.getElementById('downloadPdf');
    if (downloadPdfButton) {
        downloadPdfButton.addEventListener('click', function() {
            // Burada PDF oluşturma işlemi yapılır
            alert('Sipariş özeti PDF olarak indiriliyor.');
        });
    }

    // Mail ile sipariş gönderme işlemi
    const sendMailButton = document.getElementById('sendMail');
    if (sendMailButton) {
        sendMailButton.addEventListener('click', function() {
            const recipientEmail = prompt('Göndermek istediğiniz e-posta adresini girin:');
            if (recipientEmail) {
                alert('Sipariş özeti ' + recipientEmail + ' adresine gönderiliyor.');
                // Burada mail gönderme işlemi yapılır
            }
        });
    }
});
