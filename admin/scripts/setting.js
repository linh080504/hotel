
let general_data, contacts_data;
let general_s_form = document.getElementById('general_s_form');
let contacts_s_form = document.getElementById('contacts_s_form');

let team_s_form = document.getElementById('team_s_form');
let member_name_inp = document.getElementById("member_name_inp");
let member_picture_inp = document.getElementById("member_picture_inp");

function loadGeneralData() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (this.status == 200) {
            general_data = JSON.parse(this.responseText);
            document.getElementById('site_title').innerText = general_data.site_title;
            document.getElementById('site_about').innerText = general_data.site_about;
            document.getElementById('site_title_inp').value = general_data.site_title;
            document.getElementById('site_about_inp').value = general_data.site_about;
            // Sửa lỗi ở đây
            let shutdownToggle = document.getElementById('shutdown-toggle');
            shutdownToggle.checked = general_data.shutdown == 1; // True if shutdown is on
            shutdownToggle.value = general_data.shutdown == 1 ? 1 : 0;
        }
    }
    xhr.send('action=get_general');
}
function get_general() {
    let site_title = document.getElementById('site_title');
    let site_about =document.getElementById('site_about');
    let shutdown_toggle =document.getElementById('shutdown_toggle');
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        general_data =JSON.parse(this.responseText);
        site_title.innerText = general_data.site_title;
        site_about.innerText = general_data.site_about;
        site_title_inp.value = general_data.site_title;
        site_about_inp.value =general_data.site_about;
        if(general_data.shutdown == 0) {
            shutdown_toggle.checked =false;
            shutdown_toggle.value = 0;
        } else { 
            shutdown_toggle.checked = true;
            shutdown_toggle.value = 1
        }
    }
    xhr.send('get_general');
}
general_s_form.addEventListener('submit', function(e){
    e.preventDefault();
    upd_general(site_title_inp.value, site_about_inp.value);
})

function upd_general(site_title_val, site_about_val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        let modal = bootstrap.Modal.getInstance(document.getElementById('general-s'));
        modal.hide();

        if (this.responseText == "1") {
            arlert('success', 'Changes saved!');
            loadGeneralData(); // Load updated data
        } else {
            arlert('error', 'No changes saved!');
        }
    }
    xhr.send('action=upd_general&site_title=' + encodeURIComponent(site_title_val) + '&site_about=' + encodeURIComponent(site_about_val));
}

function upd_shutdown(val) {
    console.log("Shutdown value being sent: ", val); 
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        console.log(this.responseText);
        if (this.responseText == 1) {
            arlert('success', 'Site has been shutdown!');
        } else {
            arlert('success', 'Shutdown mode off');
        }
        loadGeneralData(); // Refresh data
    }
    xhr.send('action=upd_shutdown&shutdown=' + val);
}

function get_contacts() {

    let contacts_p_id = ['address', 'gmap', 'pn1', 'pn2', 'email', 'fb', 'insta', 'tw'];
    let iframe = document.getElementById('iframe');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (this.status == 200) {
            contacts_data = JSON.parse(this.responseText);
            contacts_data = Object.values(contacts_data);
            
            for(i = 0; i < contacts_p_id.length; i++){
                document.getElementById(contacts_p_id[i]).innerText = contacts_data[i+1];
            }

            iframe.src = contacts_data[9];
            contacts_inp(contacts_data);
        }
    }
    xhr.send('action=get_contacts');
}

function contacts_inp(data){
    let contacts_inp_id = ['address_inp', 'gmap_inp', 'pn1_inp', 'pn2_inp', 'email_inp', 'fb_inp', 'insta_inp', 'tw_inp', 'iframe_inp'];

    for(i = 0; i < contacts_inp_id.length; i++){
        document.getElementById(contacts_inp_id[i]).value = data[i+1];
    }
}

contacts_s_form.addEventListener('submit', function(e){
    e.preventDefault();
    upd_contacts();
})

function upd_contacts() {
    let index = ['address', 'gmap', 'pn1', 'pn2', 'email', 'fb', 'insta', 'tw', 'iframe'];
    let contacts_inp_id = ['address_inp', 'gmap_inp', 'pn1_inp', 'pn2_inp', 'email_inp', 'fb_inp', 'insta_inp', 'tw_inp', 'iframe_inp'];
    let data_str = "";

    for (let i = 0; i < index.length; i++) {
        data_str += index[i] + "=" + encodeURIComponent(document.getElementById(contacts_inp_id[i]).value) + "&";
    }

    data_str += "action=upd_contacts"; // Thêm "action=" để chỉ định hành động

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        console.log(this.responseText);
        var myModal = document.getElementById('contacts-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == "1") { // Sử dụng chuỗi "1" để so sánh
            arlert('success', 'Changes saved!'); // Sửa lại tên hàm từ "arlert" thành "alert"
            get_contacts();
        } else {
            arlert('error', 'No changes made!');
        }
    }
    xhr.send(data_str);
}

team_s_form.addEventListener('submit', function(e){
    e.preventDefault();
    add_member();
})
function get_member() {
    let site_title = document.getElementById('site_title');
    let site_about =document.getElementById('site_about');
    let shutdown_toggle =document.getElementById('shutdown_toggle');
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        document.getElementById('team-data').innerHTML = this.responseText;
    }
    xhr.send('get_general');
}
function add_member(){
    let data = new FormData();
    data.append('name', member_name_inp.value);
    data.append('picture', member_picture_inp.files[0]);
    data.append('add_member', ' ' );

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);

    xhr.onload = function() {
        var myModal =  document.getElementById('team-s')
        let modal = bootstrap.Modal.getInstance(document.getElementById(myModal));
        modal.hide();
        if(this.responseText == 'inv_img') {
            alert('error', 'Only JPG and PNG are allowed')
        }else if (this.responseText == 'inv_size') {
            alert('error', 'Img less than 2mb')
        }else if (this.responseText == 'upd_failed') {
            alert('error', 'Img down failed. Server down')
        } else {
            alert('success', 'New member added! ');
            member_name_inp.value= '';
            member_picture_inp.value= '';
            get_member();
        }
    }
    xhr.send(data);
}
function rem_member(val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if(this.responseText == 1) {
            alert('success', 'Member removed!');
            get_member();
        } else { 
            alert('error', 'Server down')
        }
    }
    xhr.send('rem_member'+val); 
}
window.onload = function() {
    loadGeneralData(); // Initial data load
    get_contacts();
    get_member(); 
}
