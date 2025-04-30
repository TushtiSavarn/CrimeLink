document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("upload-form");
    const fileInput = document.getElementById("file-input");
    const dropArea = document.getElementById("drop-area");

    // Drag & Drop functionality
    dropArea.addEventListener("dragover", (event) => {
        event.preventDefault();
        dropArea.classList.add("active");
    });

    dropArea.addEventListener("dragleave", () => {
        dropArea.classList.remove("active");
    });

    dropArea.addEventListener("drop", (event) => {
        event.preventDefault();
        dropArea.classList.remove("active");

        if (event.dataTransfer.files.length > 0) {
            fileInput.files = event.dataTransfer.files;
        }
    });

    // Form Submission
    form.addEventListener("submit", function (event) {
        const reportId = document.getElementById("report-id").value.trim();
        const file = fileInput.files[0];

        if (!reportId) {
            alert("Please enter a Report ID.");
            event.preventDefault();
            return;
        }

        if (!file) {
            alert("Please select a file to upload.");
            event.preventDefault();
        }
    });
});
