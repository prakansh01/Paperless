<?PHP include "connection.php"; ?>

<?php
$exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dynamic Application Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .progress {
            height: 30px;
        }

        .progress-bar {
            line-height: 30px;
        }

        .preview {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border: 1px solid #ccc;
        }

        .container,
        .mt-5 {
            padding-top: 3%;
            width: 60%;
        }
    </style>

    <!-- Inline Script for Profile Photo Preview -->
    <script>
        function loadPhotoPreview(event) {
            const preview = document.getElementById("user-photo");
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.onload = () => URL.revokeObjectURL(preview.src); // Free memory
        }
    </script>

    <!-- Inline Script for Signature Preview -->
    <script>
        function loadSignaturePreview(event) {
            const preview = document.getElementById("user-signature");
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.onload = () => URL.revokeObjectURL(preview.src); // Free memory
        }
    </script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Exam Application Form</h1>
        <div class="progress my-4">
            <div class="progress-bar" role="progressbar" style="width: 20%" id="progressBar">
                Section 1 of 5
            </div>
        </div>
        <form id="applicationForm" method="post" action="processNewForm.php" enctype="multipart/form-data">
            <!-- Section 1 -->
            <div class="form-section" id="section1">
                <table class="table table-bordered">
                    <tr>
                        <td><label for="fullName">Full Name</label></td>
                        <td>
                            <input type="text" class="form-control" id="fullName" name="fullName"
                                placeholder="Enter your full name" required />
                        </td>
                        <td><label for="gender">Gender</label></td>
                        <td>
                            <select id="gender" class="form-select" name="gender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="fatherName">Father's Name</label></td>
                        <td>
                            <input type="text" class="form-control" id="fatherName" name="fatherName"
                                placeholder="Enter your father's name" required />
                        </td>
                        <td><label for="dob">Date of Birth</label></td>
                        <td>
                            <input type="date" class="form-control" id="dob" name="dob" placeholder="dd-mm-yyyy"
                                required />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="motherName">Mother's Name</label></td>
                        <td>
                            <input type="text" class="form-control" id="motherName" name="motherName"
                                placeholder="Enter your mother's name" required />
                        </td>
                        <td><label for="category">Category</label></td>
                        <td>
                            <select id="category" class="form-select" name="category" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="general">General</option>
                                <option value="obc">OBC</option>
                                <option value="sc">SC</option>
                                <option value="st">ST</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Section 2 -->
            <div class="form-section" id="section2" style="display: none">
                <table class="table table-bordered">
                    <tr>
                        <td><label for="email">Email</label></td>
                        <td>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter your email" required />
                        </td>
                        <td><label for="address">Full Address</label></td>
                        <td>
                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="Enter your full address" required />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="phone">Phone Number</label></td>
                        <td>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                placeholder="Enter your phone number" pattern="[0-9]{10}" required />
                        </td>
                        <td><label for="state">State</label></td>
                        <td>
                            <select id="state" class="form-select" name="state" required>
                                <option value="" disabled selected>Select State</option>
                                <option value="andhra-pradesh">Andhra Pradesh</option>
                                <option value="arunachal-pradesh">Arunachal Pradesh</option>
                                <option value="assam">Assam</option>
                                <option value="bihar">Bihar</option>
                                <option value="chhattisgarh">Chhattisgarh</option>
                                <option value="goa">Goa</option>
                                <option value="gujarat">Gujarat</option>
                                <option value="haryana">Haryana</option>
                                <option value="himachal-pradesh">Himachal Pradesh</option>
                                <option value="jharkhand">Jharkhand</option>
                                <option value="karnataka">Karnataka</option>
                                <option value="kerala">Kerala</option>
                                <option value="madhya-pradesh">Madhya Pradesh</option>
                                <option value="maharashtra">Maharashtra</option>
                                <option value="manipur">Manipur</option>
                                <option value="meghalaya">Meghalaya</option>
                                <option value="mizoram">Mizoram</option>
                                <option value="nagaland">Nagaland</option>
                                <option value="odisha">Odisha</option>
                                <option value="punjab">Punjab</option>
                                <option value="rajasthan">Rajasthan</option>
                                <option value="sikkim">Sikkim</option>
                                <option value="tamil-nadu">Tamil Nadu</option>
                                <option value="telangana">Telangana</option>
                                <option value="tripura">Tripura</option>
                                <option value="uttar-pradesh">Uttar Pradesh</option>
                                <option value="uttarakhand">Uttarakhand</option>
                                <option value="west-bengal">West Bengal</option>
                                <option value="andaman-nicobar">Andaman and Nicobar Islands</option>
                                <option value="chandigarh">Chandigarh</option>
                                <option value="dadra-nagar-haveli">Dadra and Nagar Haveli and Daman and Diu</option>
                                <option value="delhi">Delhi</option>
                                <option value="lakshadweep">Lakshadweep</option>
                                <option value="puducherry">Puducherry</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="whatsapp">Whatsapp Number</label></td>
                        <td>
                            <input type="tel" class="form-control" id="whatsapp" name="whatsapp"
                                placeholder="Enter parent's WhatsApp number" pattern="[0-9]{10}" />
                        </td>
                        <td><label for="city">City</label></td>
                        <td>
                            <input type="text" class="form-control" id="city" name="city" placeholder="Enter your city"
                                required />
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Section 3 -->
            <div class="form-section" id="section3" style="display: none">
                <table class="table table-bordered">
                    <tr>
                        <td><label for="10thPercentage">10th Percentage</label></td>
                        <td>
                            <input type="number" class="form-control" id="10thPercentage" name="10thPercentage"
                                placeholder="Enter 10th% obtained" required />
                        </td>
                        <td><label for="12thPercentage">12th Percentage</label></td>
                        <td>
                            <input type="number" class="form-control" id="12thPercentage" name="12thPercentage"
                                placeholder="Enter 12th% obtained" required />
                        </td>
                    </tr>
                    <tr>
                        <td>Select Stream</td>
                        <td>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="subject" id="radio-pcm" value="pcm"
                                    autocomplete="off" />
                                <label class="btn btn-outline-primary" for="radio-pcm">PCM</label>
                            </div>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="subject" id="radio-pcb" value="pcb"
                                    autocomplete="off" />
                                <label class="btn btn-outline-primary" for="radio-pcb">PCB</label>
                            </div>
                        </td>
                        <td><label for="hobby">Any Hobby ?</label></td>
                        <td>
                            <input type="text" class="form-control" id="hobby" name="hobby"
                                placeholder="like coding ?" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="competition">Given Any Competative exam ?</label>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="competition" name="competition" />
                        </td>
                        <td><label for="rank">Rank in Competative Exam</label></td>
                        <td>
                            <input type="number" class="form-control" id="rank" name="rank"
                                placeholder="Enter your rank" />
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Section 4 -->
            <div class="form-section" id="section4" style="display: none">
                <table class="table table-bordered">
                    <input type="hidden" class="form-control" id="examid" name="examId" value="<?php echo $exam_id; ?>">
                    <tr>
                        <td><label for="studentCode">Student Code For the selected Exam</label></td>
                        <td>
                            <input type="text" class="form-control" id="studentcode" name="studentCode"
                                placeholder="Enter the student code for the selected exam " required />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="center1">Preferred City 1 for Exam Center</label></td>
                        <td>
                            <select id="center1" class="form-select" name="center1" required>
                                <option value="" disabled selected>Select Center City 1</option>
                                <option value="agra">Agra</option>
                                <option value="allahabad">Allahabad (Prayagraj)</option>
                                <option value="aligarh">Aligarh</option>
                                <option value="ayodhya">Ayodhya</option>
                                <option value="bareilly">Bareilly</option>
                                <option value="ghaziabad">Ghaziabad</option>
                                <option value="gorakhpur">Gorakhpur</option>
                                <option value="kanpur">Kanpur</option>
                                <option value="lucknow">Lucknow</option>
                                <option value="meerut">Meerut</option>
                                <option value="noida">Noida</option>
                                <option value="varanasi">Varanasi</option>
                                <option value="mau">Mau</option>
                                <option value="faizabad">Faizabad</option>
                                <option value="firozabad">Firozabad</option>
                                <option value="jhansi">Jhansi</option>
                                <option value="moradabad">Moradabad</option>
                                <option value="mathura">Mathura</option>
                                <option value="raebareli">Rae Bareli</option>
                                <option value="shahjahanpur">Shahjahanpur</option>
                                <option value="saharanpur">Saharanpur</option>
                                <option value="azamgarh">Azamgarh</option>
                                <option value="banda">Banda</option>
                                <option value="ballia">Ballia</option>
                                <option value="etawah">Etawah</option>
                                <option value="sitapur">Sitapur</option>
                                <option value="basti">Basti</option>
                                <option value="deoria">Deoria</option>
                                <option value="unnao">Unnao</option>
                                <option value="hardoi">Hardoi</option>
                                <option value="mainpuri">Mainpuri</option>
                                <option value="hathras">Hathras</option>
                                <option value="sultanpur">Sultanpur</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="center2">Preferred City 1 for Exam Center</label></td>
                        <td>
                            <select id="center2" class="form-select" name="center2" required>
                                <option value="" disabled selected>Select Center City 1</option>
                                <option value="agra">Agra</option>
                                <option value="allahabad">Allahabad (Prayagraj)</option>
                                <option value="aligarh">Aligarh</option>
                                <option value="ayodhya">Ayodhya</option>
                                <option value="bareilly">Bareilly</option>
                                <option value="ghaziabad">Ghaziabad</option>
                                <option value="gorakhpur">Gorakhpur</option>
                                <option value="kanpur">Kanpur</option>
                                <option value="lucknow">Lucknow</option>
                                <option value="meerut">Meerut</option>
                                <option value="noida">Noida</option>
                                <option value="varanasi">Varanasi</option>
                                <option value="mau">Mau</option>
                                <option value="faizabad">Faizabad</option>
                                <option value="firozabad">Firozabad</option>
                                <option value="jhansi">Jhansi</option>
                                <option value="moradabad">Moradabad</option>
                                <option value="mathura">Mathura</option>
                                <option value="raebareli">Rae Bareli</option>
                                <option value="shahjahanpur">Shahjahanpur</option>
                                <option value="saharanpur">Saharanpur</option>
                                <option value="azamgarh">Azamgarh</option>
                                <option value="banda">Banda</option>
                                <option value="ballia">Ballia</option>
                                <option value="etawah">Etawah</option>
                                <option value="sitapur">Sitapur</option>
                                <option value="basti">Basti</option>
                                <option value="deoria">Deoria</option>
                                <option value="unnao">Unnao</option>
                                <option value="hardoi">Hardoi</option>
                                <option value="mainpuri">Mainpuri</option>
                                <option value="hathras">Hathras</option>
                                <option value="sultanpur">Sultanpur</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="center3">Preferred City 1 for Exam Center</label></td>
                        <td>
                            <select id="center3" class="form-select" name="center3" required>
                                <option value="" disabled selected>Select Center City 1</option>
                                <option value="agra">Agra</option>
                                <option value="allahabad">Allahabad (Prayagraj)</option>
                                <option value="aligarh">Aligarh</option>
                                <option value="ayodhya">Ayodhya</option>
                                <option value="bareilly">Bareilly</option>
                                <option value="ghaziabad">Ghaziabad</option>
                                <option value="gorakhpur">Gorakhpur</option>
                                <option value="kanpur">Kanpur</option>
                                <option value="lucknow">Lucknow</option>
                                <option value="meerut">Meerut</option>
                                <option value="noida">Noida</option>
                                <option value="varanasi">Varanasi</option>
                                <option value="mau">Mau</option>
                                <option value="faizabad">Faizabad</option>
                                <option value="firozabad">Firozabad</option>
                                <option value="jhansi">Jhansi</option>
                                <option value="moradabad">Moradabad</option>
                                <option value="mathura">Mathura</option>
                                <option value="raebareli">Rae Bareli</option>
                                <option value="shahjahanpur">Shahjahanpur</option>
                                <option value="saharanpur">Saharanpur</option>
                                <option value="azamgarh">Azamgarh</option>
                                <option value="banda">Banda</option>
                                <option value="ballia">Ballia</option>
                                <option value="etawah">Etawah</option>
                                <option value="sitapur">Sitapur</option>
                                <option value="basti">Basti</option>
                                <option value="deoria">Deoria</option>
                                <option value="unnao">Unnao</option>
                                <option value="hardoi">Hardoi</option>
                                <option value="mainpuri">Mainpuri</option>
                                <option value="hathras">Hathras</option>
                                <option value="sultanpur">Sultanpur</option>
                            </select>
                        </td>
                    </tr>

                </table>
            </div>

            <!-- Section 5 for photo and signature upload -->
            <div class="form-section" id="section5" style="display: none">
                <div class="container">
                    <div class="row mb-3">
                        <!-- Signature Upload Column -->
                        <div class="col-md-6 d-flex justify-content-center">
                            <div class="photo-upload d-flex flex-column align-items-center border border-secondary p-3">
                                <img src="./assets/signature.png" id="user-signature" alt="User Signature"
                                    class="border border-secondary"
                                    style="width: 250px; height: 100px; object-fit: cover" />
                                <div class="d-flex align-items-center mt-2">
                                    <label for="signature-upload-btn" class="btn btn-primary me-2"
                                        style="margin-top: 51px;">
                                        Upload Signature
                                    </label>
                                    <input type="file" id="signature-upload-btn" name="signature" accept="image/*"
                                        onchange="loadSignaturePreview(event)" hidden />
                                </div>
                            </div>
                        </div>

                        <!-- Photo Upload Column -->
                        <div class="col-md-6 d-flex justify-content-center">
                            <div class="photo-upload d-flex flex-column align-items-center border border-secondary p-3">
                                <img src="./assets/user-photo.png" id="user-photo" alt="User Photo"
                                    class="rounded-circle border border-secondary"
                                    style="width: 150px; height: 150px; object-fit: cover" />
                                <div class="d-flex align-items-center mt-2">
                                    <label for="photo-upload-btn" class="btn btn-primary me-2">
                                        Upload Photo
                                    </label>
                                    <input type="file" id="photo-upload-btn" name="photo" accept="image/*"
                                        onchange="loadPhotoPreview(event)" hidden />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="navigateForm(-1)" disabled>
                    Previous
                </button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="navigateForm(1)">
                    Next
                </button>
                <button type="submit" class="btn btn-success" id="submitBtn" style="display: none">
                    Submit
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript -->
    <script>
        let currentSection = 1;
        const totalSections = 5;

        function updateProgressBar() {
            const progress = (currentSection / totalSections) * 100;
            document.getElementById("progressBar").style.width = `${progress}%`;
            document.getElementById(
                "progressBar"
            ).innerText = `Section ${currentSection} of ${totalSections}`;
        }

        function navigateForm(direction) {
            document.getElementById(`section${currentSection}`).style.display =
                "none";
            currentSection += direction;
            document.getElementById(`section${currentSection}`).style.display =
                "block";

            document.getElementById("prevBtn").disabled = currentSection === 1;
            document.getElementById("nextBtn").style.display =
                currentSection === totalSections ? "none" : "block";
            document.getElementById("submitBtn").style.display =
                currentSection === totalSections ? "block" : "none";

            updateProgressBar();
        }

        // document
        //     .getElementById("applicationForm")
        //     .addEventListener("submit", (event) => {
        //         event.preventDefault();
        //         alert("Form submitted successfully!");
        //     });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>