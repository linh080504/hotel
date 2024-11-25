<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Manager</title>
    <?php require('inc/links.php') ?>
</head>
<body class="bg-white">
    <?php require('inc/header.php') ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">USER MANAGER</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <!-- Search bar -->
                        <div class="input-group mb-3">
                            <input type="text" id="search-input" class="form-control shadow-none" placeholder="Search by name or email">
                            <button class="btn btn-dark shadow-none" onclick="searchUser()">Search</button>
                        </div>

                        <!-- Add User Button -->
                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-user">
                                <i class="bi bi-plus-square"></i> Add User
                            </button>
                        </div>
                        
                        <!-- User Table -->
                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border text-center">
                                <thead>
                                    <tr class="bg-dark text-light">
                                    <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Pincode</th>
                                        <th scope="col">DOB</th>
                                        <th scope="col">Profile</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Datetime</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="user-data">
                                    <!-- User Data will be loaded dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="add-user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="add-user-form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="text" name="phonenum" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" name="password" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Pincode</label>
                                <input type="text" name="pincode" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Address</label>
                                <textarea name="address" rows="3" class="form-control shadow-none" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark shadow-none">Submit</button>
                    </div>
                </div>
            </form>  
        </div>
    </div>

    <?php require('inc/scripts.php') ?>
    <script>
        function fetchUsers() {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/user_manager.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                document.getElementById('user-data').innerHTML = this.responseText;
            }
            xhr.send("action=fetch_users");
        }

        function searchUser() {
            let search = document.getElementById('search-input').value;
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/user_manager.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                document.getElementById('user-data').innerHTML = this.responseText;
            }
            xhr.send("action=search_user&query=" + search);
        }

        document.getElementById('add-user-form').addEventListener('submit', function(e) {
            e.preventDefault();
            let form = new FormData(this);
            form.append("action", "add_user");

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/user_manager.php", true);
            xhr.onload = function() {
                if (this.responseText == 1) {
                    alert('User added successfully!');
                    fetchUsers();
                } else {
                    alert('Error adding user!');
                }
            }
            xhr.send(form);
        });

        function deleteUser(id) {
            if (!confirm("Are you sure you want to delete this user?")) return;

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/user_manager.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (this.responseText == 1) {
                    alert('User deleted successfully!');
                    fetchUsers();
                } else {
                    alert('Error deleting user!');
                }
            }
            xhr.send("action=delete_user&id=" + id);
        }

        window.onload = fetchUsers;
    </script>
</body>
</html>
