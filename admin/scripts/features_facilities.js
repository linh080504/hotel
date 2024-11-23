
        let feature_s_form = document.getElementById('feature_s_form');
        let facility_s_form = document.getElementById('facility_s_form');


    feature_s_form.addEventListener('submit', function(e){
        e.preventDefault();
        add_feature();
    });

    function add_feature() {

        let data = new FormData();
        data.append('name', feature_s_form.elements['feature_name'].value);
        data.append('action', 'add_feature');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);

        xhr.onload = function () {
            let myModal = document.getElementById('feature-s');
            let modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if(this.responseText==1)
        {
            arlert('success','New feature added');
            feature_s_form.elements['feature_name'].value='';
            get_feature();
        }
        else {
            arlert('Error','Sever Down!');
        }
            
        };
        xhr.send(data);
    }

    function get_feature() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status == 200) {
                document.getElementById('feature-data').innerHTML = this.responseText;
            } else {
                alert('Error: Failed to load team data!');
            }
        };
        xhr.send("action=get_feature");
    }

    function rem_feature(val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == "1") { // So sánh chuỗi "1"
            arlert('success', 'Feature removed!');
            get_feature();
        } 
        else if(this.responseText == 'room_added'){
            arlert('error', 'Feature is added in room!');
        }
        
        else {
            arlert('error', 'Server issue.');
        }
    };
    xhr.send('action=rem_feature&rem_feature=' + val);
    }

    facility_s_form.addEventListener('submit', function (e) {
    e.preventDefault(); // Chặn hành động mặc định
    add_facility();
    });

    
    function add_facility() {
        let data = new FormData();
        data.append('name', facility_s_form.elements['facility_name'].value);
        data.append('icon', facility_s_form.elements['facility_icon'].files[0]);
        data.append('desc', facility_s_form.elements['facility_desc'].value);
        data.append('action', 'add_facility');

        console.log([...data]); // Hiển thị dữ liệu gửi đi

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);

        xhr.onload = function () {

            let myModal = document.getElementById('facility-s');
            let modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if (this.responseText === 'inv_img') {
                alert('Only SVG images are allowed!');
            } else if (this.responseText === 'inv_size') {
                alert('Image should be less than 1MB!');
            } else if (this.responseText === 'upd_failed') {
                alert('Image upload failed. Server Down!');
            } else if (this.responseText === '1') {
                alert('New facility added!');
                facility_s_form.reset();
                get_facilities();
            } else {
                alert('Unknown error: ' + this.responseText);
            }
        };

        xhr.send(data);
    }


    function get_facilities() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status == 200) {
            document.getElementById('facilities-data').innerHTML = this.responseText;
        } else {
            alert('Error: Failed to load team data!');
        }
    };
    xhr.send("action=get_facilities");
    }

    function rem_facility(val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == "1") { // So sánh chuỗi "1"
            arlert('success', 'Facility removed!');
            get_facilities();
        } 
        else if(this.responseText == 'room_added'){
            arlert('error', 'Facility is added in room!');
        }
        
        else {
            arlert('error', 'Server issue.');
        }
    };
    xhr.send('action=rem_facility&rem_facility=' + val);
    }
    
    window.onload = function(){
        get_feature();
        get_facilities();
    }
