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
        let navbar = document.getElementById('dashboard-menu');
        let a_tag = navbar.getElementsByTagName('a');

        for(i = 0; i < a_tag.length; i++){
            let file = a_tag[i].href.split('/').pop();
            let file_name = file.split('.')[0];
            if(document.location.href.indexOf(file_name) >= 0){
                a_tag[i].classList.add('active');
            }
        }
    }
    setActive();

</script>