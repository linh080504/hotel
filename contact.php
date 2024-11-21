<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TJ Hotel - CONTACT</title>
    <?php require('inc/links.php')?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body class="bg-light">
<?php require('inc/header.php');?>

<div class="md-5 px-4">
    <h2 class="fw-bold h-font text-center ">CONTACT US</h2>
    <div class="h-line bg-dark"></div>
    <p class="text-center  mt-3">
    This introduction conveys warmth and readiness to assist, making visitors feel comfortable reaching out. 
    <br>Let me know if you need adjustments!
    </p>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-6 col-md-6 mb-5 px-4">
            <div class="bg-white rounded shadow p-4">
                <iframe class="w-100 rounded mb-4" height="320px" src="<?php echo $contact_r['iframe']?>" height="450" loading="lazy"></iframe>
                <h5>Address</h5>
                <a href="<?php echo $contact_r['address']; ?>" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
                    <i class="bi bi-geo-alt-fill"></i> <?php echo $contact_r['address']; ?>
                </a>
                <h5 class="mt-4">Call us</h5>
                <a href="tell: +<?php echo $contact_r['pn1']; ?>" class="d-inline-block mb-2 text-decoration-none text-dark ">
                    <i class="bi bi-telephone-fill"></i><?php echo $contact_r['pn1']; ?>
                </a>
                <br>
                <?php if (!empty($contact_r['pn2'])): ?>
                    <a href="tel:+<?php echo $contact_r['pn2']; ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i><?php echo $contact_r['pn2']; ?>
                    </a>
                <?php endif; ?>
                <h5 class="mt-4">Email</h5>
                <a href="<?php echo $contact_r['email']; ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                    <i class="bi bi-envelope"></i> <?php echo $contact_r['email']; ?>
                </a>
                <h5 class="mt-4">Follow us</h5>
                <?php if (!empty($contact_r['tw'])): ?>
                    <a href="<?php echo $contact_r['tw']; ?>" class="d-inline-block mb-3 text-dark fs-5 me-2">
                      <i class="bi bi-twitter me-1"></i>
                </a>
                <?php endif; ?>

                <?php if (!empty($contact_r['fb'])): ?>
                    <a href="<?php echo $contact_r['fb']; ?>" class="d-inline-block mb-3 text-dark fs-5 me-2">
                        <i class="bi bi-facebook me-1"></i>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($contact_r['insta'])): ?>
                    <a href="<?php echo $contact_r['insta']; ?>" class="d-inline-block mb-3 text-dark fs-5 me-2">
                   <i class="bi bi-instagram me-1"></i>
                </a>
                <?php endif; ?>
                    
            </div>
        </div>
        <div class="col-lg-6 col-md-6 mb-5 px-4">
            <div class="bg-white rounded shadow p-4 ">
                <form method="POST">
                    <h5>Send a message</h5>
                    <div class="mt-3">
                        <label class="form-label">Name</label>
                        <input name="name" required type="text" class="form-control shadow-none">
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Email</label>
                        <input name="email" required type="email" class="form-control shadow-none">
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Subject</label>
                        <input name="subject" required type="text" class="form-control shadow-none">
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" required class="form-control shadow-none" rows = "5" style="resize: none;"></textarea>
                    </div>
                    <button type="submit" name="send" class="btn text-white custom-bg mt-3 shadow-none">SEND </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
    if(isset($_POST['send']))
    {
        $frm_data = filteration($_POST);

        $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`, `date`) VALUES (?,?,?,?,?)";
        $values = [$frm_data['name'], $frm_data['email'], $frm_data['subject'], $frm_data['message'], date('Y-m-d')];
        $res = insert($q, $values, 'sssss');

        if($res==1){
            alert('success','Mail sent!');
        }
        else{
            alert('error','Server Down! Try again later.');
        }
    }
?>

<?php require('inc/footer.php')?>
</body>
</html>