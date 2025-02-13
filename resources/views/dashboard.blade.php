@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="mb-4">Project</h2>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Project List</h5>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <button id="addBtn" class="btn text-white" style="background-color: #6f42c1;" data-bs-toggle="modal"
                    data-bs-target="#modalProject">
                    Add
                </button>

            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Project Name</th>
                            <th>Description</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="projectTable">
                        <tr>
                            <td colspan="4">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalProject" tabindex="-1" role="dialog" aria-labelledby="modalProjectLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProjectLabel">Add New Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="projectForm">
                    @csrf
                    <div class="form-group">
                        <label for="project-name" class="col-form-label">Project Name:</label>
                        <input type="text" class="form-control" id="project-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="project-description" class="col-form-label">Description:</label>
                        <textarea class="form-control" id="project-description" name="description" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveProject">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProjectForm">
                    @csrf
                    <input type="hidden" id="editProjectId">
                    <div class="mb-3">
                        <label for="editProjectName" class="form-label">Project Name</label>
                        <input type="text" id="editProjectName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProjectDescription" class="form-label">Description</label>
                        <textarea id="editProjectDescription" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Project</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-labelledby="deleteProjectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProjectModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this project?</p>
                <strong id="deleteProjectName"></strong>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="deleteProjectId">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        let token = localStorage.getItem("token");
        
        if (!token) {
            window.location.href = "/login"; // Redirect ke login jika tidak ada token
        } else {
            $.ajax({
                url: "/api/projects",
                type: "GET",
                headers: { "Authorization": "Bearer " + token },
                success: function(response) {
                    if (response.success) {
                        let projects = response.data;
                        let projectTable = $("#projectTable");
                        projectTable.empty();

                        if (projects.length === 0) {
                            projectTable.append("<tr><td colspan='4'>Tidak ada project.</td></tr>");
                        } else {
                            projects.forEach((project, index) => {
                                projectTable.append(`
                                    <tr class="text-center">
                                        <td>${index + 1}</td>
                                        <td>${project.name}</td>
                                        <td>${project.description}</td>
                                        <td>${new Date(project.created_at).toLocaleDateString()}</td>
                                        <td class="text-center">
                                        <button class="btn btn-warning btn-sm me-2 edit-btn" data-id="${project.id}" data-name="${project.name}" data-description="${project.description}">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>
                                       <button class="btn btn-danger btn-sm delete-btn" data-id="${project.id}" data-name="${project.name}">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </td>
                                `);
                            });

                            $(".edit-btn").click(function () {
                                let projectId = $(this).data("id");
                                let projectName = $(this).data("name");
                                let projectDescription = $(this).data("description");

                                $("#editProjectId").val(projectId);
                                $("#editProjectName").val(projectName);
                                $("#editProjectDescription").val(projectDescription);

                                $("#editProjectModal").modal("show");
                             }) ;
                                $(".delete-btn").click(function () {
                                let projectId = $(this).data("id");
                                let projectName = $(this).data("name");

                                $("#deleteProjectId").val(projectId);
                                $("#deleteProjectName").text(projectName);
                                $("#deleteProjectModal").modal("show");
                              });
                        }
                    }
                },
                error: function() {
                    localStorage.removeItem("token"); // Hapus token jika error
                    window.location.href = "/login";
                }
            });
        }
        $("#saveProject").click(function () {
            let name = $("#project-name").val().trim();
            let description = $("#project-description").val().trim();

            if (name === "" || description === "") {
                alert("Project Name dan Description tidak boleh kosong.");
                return;
            }

            $.ajax({
                url: "/api/projects",
                type: "POST",
                headers: { "Authorization": "Bearer " + token },
                contentType: "application/json",
                data: JSON.stringify({ name: name, description: description }),
                success: function (response) {
                    if (response.success) {
                        alert("Project berhasil ditambahkan!");
                        $("#modalProject").modal("hide"); // Tutup modal
                        $("#projectForm")[0].reset(); // Reset form
                        location.reload(); // Refresh halaman untuk menampilkan data baru
                    } else {
                        alert("Gagal menambahkan project.");
                    }
                },
                error: function () {
                    alert("Terjadi kesalahan, coba lagi.");
                }
            });
        });

        $("#editProjectForm").submit(function (e) {
        e.preventDefault();

        let projectId = $("#editProjectId").val();
        let updatedData = {
            name: $("#editProjectName").val(),
            description: $("#editProjectDescription").val(),
        };

        $.ajax({
            url: `/api/projects/${projectId}`,
            type: "PUT",
            headers: { "Authorization": "Bearer " + token, "Content-Type": "application/json" },
            data: JSON.stringify(updatedData),
            success: function (response) {
                if (response.success) {
                    alert("Project updated successfully!");
                    $("#editProjectModal").modal("hide");
                    location.reload();
                } else {
                    alert("Failed to update project.");
                }
            },
            error: function () {
                alert("Error updating project.");
            }
        });
    });

    // Handle delete project
    $("#confirmDelete").click(function () {
        let projectId = $("#deleteProjectId").val();

        $.ajax({
            url: `/api/projects/${projectId}`,
            type: "DELETE",
            headers: { "Authorization": "Bearer " + token },
            success: function (response) {
                if (response.success) {
                    alert("Project deleted successfully!");
                    $("#deleteProjectModal").modal("hide");
                    location.reload();
                } else {
                    alert("Failed to delete project.");
                }
            },
            error: function () {
                alert("Error deleting project.");
            }
        });
    });

    });
</script>
@endsection