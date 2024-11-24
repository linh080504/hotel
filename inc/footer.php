<div class="container-fluid bg-white mt-5">
    <div class="row">
        <div class="col-lg-4 p-4">
            <h3 class="h-font fw-bold fs-3 ">TJ HOTEL</h3>
            <p>
                We always value the contact from our customers and partners. 
                If you have any questions, support requests, or comments, please do not hesitate to contact us. 
                Our team will respond to you as soon as possible to resolve any queries quickly and effectively.
            </p>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Links</h5>
            <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none">Home</a> <br>
            <a href="room.php" class="d-inline-block mb-2 text-dark text-decoration-none">Rooms</a> <br>
            <a href="facilities.php" class="d-inline-block mb-2 text-dark text-decoration-none">Facilities</a> <br>
            <a href="contact.php" class="d-inline-block mb-2 text-dark text-decoration-none">Contact us</a> <br>
            <a href="about.php" class="d-inline-block mb-2 text-dark text-decoration-none">About</a>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Follow us</h5>
            <a href="<?php echo $contact_r['tw']; ?>" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-twitter me-1"></i>Twitter
            </a><br>
            <a href="<?php echo $contact_r['fb']; ?>" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-facebook me-1"></i>Facebook
            </a><br>
            <a href="<?php echo $contact_r['insta']; ?>" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-instagram me-1"></i>Instagram
            </a>
        </div>
    </div>
</div>

<h6 class="text-center bg-dark text-white p-3 m-0">Design and developer by TJ Webdev</h6>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script> 

    function arlert(type, msg, position='body') {
        let bs_class = (type.toLowerCase() == 'success') ? 'alert-success' : 'alert-danger';
        let element = document.createElement('div');
        element.innerHTML = `
            <div class="alert ${bs_class} alert-dismissible fade show custom-alert" role="alert">
                <strong class="me-3">${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        if (position == 'body') {
            document.body.append(element);
        } else {
            let target = document.getElementById(position);
            if (target) {
                target.appendChild(element);
            } else {
                console.error(`Position "${position}" not found.`);
            }
        }
        setTimeout(() => element.remove(), 2000); // Xóa alert sau 2 giây
    }

    function setActive(){
        let navbar = document.getElementById('nav-bar');
        let a_tag = navbar.getElementsByTagName('a');

        for(i = 0; i < a_tag.length; i++){
            let file = a_tag[i].href.split('/').pop();
            let file_name = file.split('.')[0];
            if(document.location.href.indexOf(file_name) >= 0){
                a_tag[i].classList.add('active');
            }
        }
    }

    let register_form = document.getElementById('register-form');
    register_form.addEventListener('submit', (e) => {
    e.preventDefault();

        let data = new FormData();

        data.append('name', register_form.elements['name'].value);
        data.append('email', register_form.elements['email'].value);
        data.append('phonenum', register_form.elements['phonenum'].value);
        data.append('address', register_form.elements['address'].value);
        data.append('pincode', register_form.elements['pincode'].value);
        data.append('dob', register_form.elements['dob'].value);
        data.append('pass', register_form.elements['pass'].value);
        data.append('cpass', register_form.elements['cpass'].value);
        data.append('profile', register_form.elements['profile'].files[0]);
        data.append('register', '');

        var myModal = document.getElementById('registerModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        let xhr = new XMLHttpRequest(); 
        xhr.open("POST", "ajax/login_register.php", true);

        xhr.onload = function(){
            console.log(this.responseText);
            if(this.responseText == 'pass_mismatch') {
                arlert('error', "Password Mismatch!");
            }
            else if(this.responseText == 'email_already') {
                arlert('error', "Email is already registered!");
            }
            else if(this.responseText == 'phone_already') {
                arlert('error', "Phone number is already registered!");
            }
            else if(this.responseText == 'inv_img'){
                arlert('error', "Only JPG, WEBP & PNG images are allowed!");
            }
            else if(this.responseText == 'upd_failed'){
                arlert('error', "Image upload failed!");
            }
            else if(this.responseText == 'mail_failed'){
                arlert('error', "Cannot send confirmation email! Server down!");
            }
            else if(this.responseText == 'ins_failed'){
                arlert('error', "Registration failed! Server down!");
            }
            else{
                arlert('success', "Registration successful. Confirmation link sent to email!"); 
                register_form.reset();
            }
        }

    xhr.send(data);
}); 

    setActive();
</script>