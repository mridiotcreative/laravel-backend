@extends('frontend.layouts.master')

@section('title', __('page_title.register'))

@section('main-content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<section class="register-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 register-left-main">
                <div class="register-left">
                    <div class="register-img">
                        @php
                        $getSettingData = (new \App\Helpers\AppHelper)->getSettingData();
                        @endphp
                        <img src="{{ $getSettingData['logo'] }}" alt="">
                    </div>
                    {{-- <div class="register-img-txt">
                        <p>Go through the registration form with us easily</p>
                    </div> --}}
                </div>
            </div>
            <div class="col-lg-6 register-right-main">
                <div class="container">
                    <div class="register-para d-flex justify-content-center mt-3">
                        <h3>Create a new account</h3>
                    </div>
                    <div class="register-para  d-flex justify-content-center">
                        <p>Please put your information below to create a new, account for using app.</p>
                    </div>
                    {{-- <div class="stepwizard col-md-offset-3">
                        <div class="stepwizard-row setup-panel">
                            <div class="stepwizard-step">
                                <span class="btn btn-primary btn-circle" id="span-step-1">1</span>
                            </div>
                            <div class="stepwizard-step">
                                <span class="btn btn-default btn-circle" id="span-step-2">2</span>
                            </div>
                            <div class="stepwizard-step">
                                <span class="btn btn-default btn-circle" id="span-step-3">3</span>
                            </div>
                        </div>
                    </div> --}}

                    <form class="form" id="registerFrom" method="post" action="{{ route('register.submit') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="setup-content" id="step-1">
                            <div class="row">
                                <div class="col-lg-12 input-group mb-3">
                                    <input type="text" name="full_name" class="form-control"
                                        placeholder="Enter Full Name" value="{{ old('full_name') }}" class="">
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-12 input-group mb-3">
                                    <input type="text" name="email" class="form-control"
                                        placeholder="Enter Email address" value="{{ old('email') }}">
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Your Password<span class="text-danger">*</span></label>
                                        <input type="password" name="password" placeholder="Enter password"
                                            value="{{ old('password') }}">
                                        @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="col-lg-12 input-group mb-3">
                                    <input type="password" name="password" id="pass_log_id" class="form-control"
                                        placeholder="Enter password" value="{{ old('password') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i
                                                class="fa fa-eye-slash toggle-password"></i></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group login-btn">
                                        {{-- <button class="btn nextBtn" data-next="step-2" type="button">Next</button>
                                        --}}
                                        <button class="btn nextBtn" data-next="step-3" type="submit">Done</button>
                                        <div class="errorTxt"></div>
                                        <div class="alredy-acc">
                                            <span>Already have an account? <a href="{{ route('login.form') }}"
                                                    class="login-btn">Log in</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row setup-content" id="step-2">
                            <div class="col-lg-12">
                                <div class="user-type">
                                    <label>State<span class="text-danger">*</span></label>
                                    <select name="state_id" id="state_id">
                                        <option value="">Choose State </option>
                                        @if (isset($city))
                                        @foreach ($state as $st)
                                        <option value="{{ $st->id }}" {{ old('state_id', '' )==$st->id ? 'selected' : ''
                                            }}>
                                            {{ $st->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @error('state_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="user-type">
                                    <label>City<span class="text-danger">*</span></label>
                                    <select name="city_id" id="city_id">
                                        <option value="">Choose City </option>
                                        comhere @if (isset($city))
                                        @foreach ($city as $ct)
                                        <option value="{{ $ct->id }}" {{ old('city_id', '' )==$ct->id ? 'selected' : ''
                                            }}>
                                            {{ $ct->name }}</option>
                                        @endforeach
                                        @endif comover
                                    </select>
                                    @error('city_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="pincode-name ">
                                    <label for="html">Pincode<span class="text-danger">*</span></label>
                                    <input type="number" value="{{ old('pincode') }}" name="pincode"
                                        placeholder="Enter Pincode">
                                    @error('pincode')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="contact-main">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="contact-name">
                                                    <label for="html">1. Contact Number<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" value="{{ old('contact_no_1') }}"
                                                        name="contact_no_1" placeholder="Enter number">
                                                    @error('contact_no_1')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="contact-name">
                                                    <label for="html">2. Contact Number<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" value="{{ old('contact_no_2') }}"
                                                        name="contact_no_2" placeholder="Enter number">
                                                    @error('contact_no_2')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="next-btn">
                                    <button class="btn nextBtn" data-next="step-3" type="button">Next</button>
                                </div>
                            </div>


                        </div>
                        <div class="row setup-content" id="step-3">
                            <!-- Upload Area -->
                            <div id="uploadArea" class="upload-area">
                                <!-- Header -->

                                <!-- End Header -->

                                <!-- Drop Zoon -->
                                <div class="col-lg-12 in_mr">
                                    <div class="gst">
                                        <label for="html">Designation<span class="text-danger">*</span></label>
                                        <input type="text" value="{{ old('designation') }}" name="designation"
                                            placeholder="Enter Designation">
                                        @error('designation')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 not_in_mr">
                                    <div class="gst">
                                        <label for="html">GST No.<span class="text-danger">*</span></label>
                                        <input type="text" value="{{ old('gst_no') }}" name="gst_no"
                                            placeholder="Enter GST No">
                                        @error('gst_no')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 file-uploader-main not_in_mr">
                                    <div class="gst-label">
                                        <label>Upload GST Certificate<span class="text-danger">*</span></label>
                                    </div>
                                    <div id="dropZoon" class="upload-area__drop-zoon drop-zoon">
                                        <span class="drop-zoon__icon">
                                            <img src="/frontend/img/fileuploader.svg" alt="">
                                        </span>
                                        <p class="drop-zoon__paragraph">Drop your files to upload or
                                            <span>Browse</span>
                                            <input type="file" name="gst_document" id="gst_document"
                                                class="drop-zoon__file-input">

                                        </p>
                                        <span id="loadingText" class="drop-zoon__loading-text">Please Wait</span>
                                        <img src="" alt="Preview Image" id="previewImage"
                                            class="drop-zoon__preview-image" draggable="false">
                                    </div>
                                    @error('gst_document')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="col-lg-12 not_in_mr">
                                    <div class="drug">
                                        <label for="html">Drug Licence No.<span class="text-danger">*</span></label>
                                        <input type="text" name="drug_licence_no" value="{{ old('drug_licence_no') }}"
                                            placeholder="Enter Drug Licence No">
                                        @error('drug_licence_no')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 file-uploader-main not_in_mr">
                                    <div class="gst-label">
                                        <label>Upload Drug Certificate<span class="text-danger">*</span></label>
                                    </div>
                                    <div id="dropZoon" class="upload-area__drop-zoon drop-zoon">
                                        <span class="drop-zoon__icon">
                                            <img src="/frontend/img/fileuploader.svg" alt="">
                                        </span>
                                        <p class="drop-zoon__paragraph">Drop your files to upload or
                                            <span>Browse</span>
                                        </p>
                                        <span id="loadingText" class="drop-zoon__loading-text">Please
                                            Wait</span>
                                        <img src="" alt="Preview Image" id="previewImage"
                                            class="drop-zoon__preview-image" draggable="false">
                                        <input type="file" id="drug_document" name="drug_document"
                                            class="drop-zoon__file-input">
                                    </div>
                                    @error('drug_document')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-lg-12 file-uploader-main">
                                    <div class="gst-label">
                                        <label>Upload Document ( ID Proof )<span class="text-danger">*</span></label>
                                    </div>
                                    <div id="dropZoon" class="upload-area__drop-zoon drop-zoon">
                                        <span class="drop-zoon__icon">
                                            <img src="/frontend/img/fileuploader.svg" alt="">
                                        </span>
                                        <p class="drop-zoon__paragraph">Drop your files to upload or
                                            <span>Browse</span>
                                        </p>
                                        <span id="loadingText" class="drop-zoon__loading-text">Please Wait</span>
                                        <img src="" alt="Preview Image" id="previewImage"
                                            class="drop-zoon__preview-image" draggable="false">
                                        <input type="file" name="id_proof_document" id="id_proof_document"
                                            class="drop-zoon__file-input">
                                    </div>
                                    @error('id_proof_document')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="done-btn">
                                    <button class="btn nextBtn" data-next="step-3" type="button">Done</button>
                                    comehere <button type="button" class="" name="submit">Done</button> comover
                                </div>
                                <!-- End Drop Zoon -->

                                <!-- File Details -->
                                <div id="fileDetails" class="upload-area__file-details file-details">
                                    <h3 class="file-details__title">Uploaded File</h3>

                                    <div id="uploadedFile" class="uploaded-file">
                                        <div class="uploaded-file__icon-container">
                                            <i class='bx bxs-file-blank uploaded-file__icon'></i>
                                            <span class="uploaded-file__icon-text"></span>
                                            <!-- Data Will be Comes From Js -->
                                        </div>

                                        <div id="uploadedFileInfo" class="uploaded-file__info">
                                            <span class="uploaded-file__name">Proejct 1</span>
                                            <span class="uploaded-file__counter">0%</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- End File Details -->
                            </div>
                            <!-- End Upload Area -->
                        </div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .shop.login .form .btn {
        margin-right: 0;
    }

    .btn-facebook {
        background: #39579A;
    }

    .btn-facebook:hover {
        background: #073088 !important;
    }

    .btn-github {
        background: #444444;
        color: white;
    }

    .btn-github:hover {
        background: black !important;
    }

    .btn-google {
        background: #ea4335;
        color: white;
    }

    .btn-google:hover {
        background: rgb(243, 26, 26) !important;
    }

    /* Used for validation highlights */
    .register-right-main input.is-invalid {
        border: 1px solid #dc3545;
    }

    .nice-select.is-invalid {
        border: 1px solid #dc3545;
    }

    .upload-area__drop-zoon.drop-zoon.is-invalid {
        border: 1px solid #dc3545 !important;
    }

    .step-done {
        background: #005778;
        color: #ffffff;
    }

    /* end validation highlights*/
</style>
@endpush
@push('scripts')
<script src="{{ asset('frontend/js/jquery.validate.min.js') }}"></script>
<script>
    $(document).ready(function() {

        $('#registerFrom').validate({
            rules: {
                full_name:{
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                email: {
                    required: "The email field is required."
                },
                password: {
                    required: "The password field is required.",
                    minlength: "The password must be at least 6 characters."
                }
            },
            errorElement : 'lable',
            errorLabelContainer: '.errorTxt',
            submitHandler: function(form) {
                form.submit();
            }
        });


        $("body").on('click', '.toggle-password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $("#pass_log_id");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

            // hideShowMr();
            // var allWells = $('.setup-content'),
            //     allNextBtn = $('.nextBtn'),
            //     allPrevBtn = $('.prevBtn'),
            //     form = $('#registerFrom'),
            //     userType = $('#role_slug');

            // allWells.hide();
            // $('#step-1').show();
            // $('#span-step-1').addClass('step-done');

            // // User Type
            // userType.change(function() {
            //     hideShowMr();
            // });

            // // Hide/Show MR
            // function hideShowMr() {
            //     slug = $('#role_slug').val();
            //     if (slug == 'mr') {
            //         $('.not_in_mr').hide();
            //         $('.in_mr').show();
            //     } else {
            //         $('.not_in_mr').show();
            //         $('.in_mr').hide();
            //     }
            // }

            // navListItems.click(function(e) {
            //     e.preventDefault();
            //     var $target = $($(this).attr('href')),
            //         $item = $(this);

            //     if (!$item.hasClass('disabled')) {
            //         navListItems.removeClass('btn-primary').addClass('btn-default');
            //         $item.addClass('btn-primary');
            //         allWells.hide();
            //         $target.show();
            //         $target.find('input:eq(0)').focus();
            //     }
            // });

            // allPrevBtn.click(function() {
            //     var curStep = $(this).closest(".setup-content"),
            //         curStepBtn = curStep.attr("id"),
            //         prevStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev()
            //         .children("a");
            //     prevStepWizard.removeAttr('disabled').trigger('click');
            // });

            // allNextBtn.click(function() {
            //     var curStep = $(this).closest(".setup-content"),
            //         curStepBtn = curStep.attr("id"),
            //         nextStepWizard = $(this).data('next'),
            //         curInputs = curStep.find("input[type='text'],input[type='number']"),
            //         curSelect = curStep.find("select"),
            //         curFiles = curStep.find("input[type='file']"),
            //         isValid = true;

            //     $.validator.addMethod("regx", function(value, element, regexpr) {
            //         return regexpr.test(value);
            //     }, $.validator.messages.regx);
            //     form.validate({
            //         rules: {
            //             full_name: {
            //                 required: true
            //             },
            //             firm_name: {
            //                 required: true
            //             },
            //             email: {
            //                 required: true,
            //                 email: true
            //             },
            //             password: {
            //                 required: true,
            //                 minlength: 6
            //             },
            //             pincode: {
            //                 required: true,
            //                 regx: /^[1-9][0-9]{5}$/
            //             },
            //             contact_no_1: {
            //                 required: true,
            //                 regx: /^(\+\d{1,3}[- ]?)?\d{10}$/
            //             },
            //             contact_no_2: {
            //                 required: true,
            //                 regx: /^(\+\d{1,3}[- ]?)?\d{10}$/
            //             },
            //             designation: {
            //                 required: true
            //             },
            //             gst_no: {
            //                 required: true,
            //                 //regx: /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/
            //             },
            //             gst_document: {
            //                 required: true
            //             },
            //             drug_licence_no: {
            //                 required: true
            //             },
            //             drug_document: {
            //                 required: true
            //             },
            //             id_proof_document: {
            //                 required: true
            //             }
            //         },
            //         messages: {
            //             full_name: {
            //                 required: "The full name field is required."
            //             },
            //             firm_name: {
            //                 required: "The firm name field is required."
            //             },
            //             email: {
            //                 required: "The email field is required."
            //             },
            //             password: {
            //                 required: "The password field is required.",
            //                 minlength: "The password must be at least 6 characters."
            //             },
            //             pincode: {
            //                 required: "The pincode field is required.",
            //                 regx: "The pincode field is not valid."
            //             },
            //             contact_no_1: {
            //                 required: "The contact no 1 field is required.",
            //                 regx: "The contact no 1 field is not valid."
            //             },
            //             contact_no_2: {
            //                 required: "The contact no 2 field is required.",
            //                 regx: "The contact no 2 field is not valid."
            //             },
            //             designation: {
            //                 required: "The designation field is required."
            //             },
            //             gst_no: {
            //                 required: "The GST no field is required.",
            //                 regx: "The GST no field is not valid."
            //             },
            //             gst_document: {
            //                 required: "The GST document field is required."
            //             },
            //             drug_licence_no: {
            //                 required: "The drug licence no field is required."
            //             },
            //             drug_document: {
            //                 required: "The drug document field is required."
            //             },
            //             id_proof_document: {
            //                 required: "The id proof document field is required."
            //             }
            //         }
            //     });
            //     if (form.valid()) {
            //         if (curStepBtn == 'step-3') {
            //             form.submit();
            //         } else {
            //             allWells.hide();
            //             $('#span-' + nextStepWizard).addClass('step-done');
            //             $('#' + nextStepWizard).show();
            //         }
            //     }
            // });

            // Get City based on state
            // $('#state_id').change(function() {
            //     getCity($(this).val());
            // });

            // function getCity(state_id) {
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': "{{ csrf_token() }}"
            //         }
            //     });
            //     $.ajax({
            //         url: "{{ route('register.city') }}",
            //         method: 'post',
            //         data: {
            //             state_id: state_id,
            //         },
            //         success: function(result) {
            //             if (result.result.length > 0) {
            //                 $('#city_id').empty();
            //                 $.each(result.result, function(i, item) {
            //                     $('#city_id').append($("<option></option>")
            //                         .attr("value", item.id)
            //                         .text(item.name));
            //                 });
            //                 $('#city_id').niceSelect('update');
            //             }
            //         }
            //     });
            // }
        });
</script>
{{-- <script>
    // Design By
        // - https://dribbble.com/shots/13992184-File-Uploader-Drag-Drop

        // Select Upload-Area
        const uploadArea = document.querySelector('#uploadArea')

        // Select Drop-Zoon Area
        const dropZoon = document.querySelector('#dropZoon');

        // Loading Text
        const loadingText = document.querySelector('#loadingText');

        // Slect File Input
        const fileInput = document.querySelector('.drop-zoon__file-input');

        // Select Preview Image
        const previewImage = document.querySelector('#previewImage');

        // File-Details Area
        const fileDetails = document.querySelector('#fileDetails');

        // Uploaded File
        const uploadedFile = document.querySelector('#uploadedFile');

        // Uploaded File Info
        const uploadedFileInfo = document.querySelector('#uploadedFileInfo');

        // Uploaded File  Name
        const uploadedFileName = document.querySelector('.uploaded-file__name');

        // Uploaded File Icon
        const uploadedFileIconText = document.querySelector('.uploaded-file__icon-text');

        // Uploaded File Counter
        const uploadedFileCounter = document.querySelector('.uploaded-file__counter');

        // ToolTip Data
        // const toolTipData = document.querySelector('.upload-area__tooltip-data');

        // Images Types
        const imagesTypes = [
            "jpeg",
            "png",
            "svg",
            "gif"
        ];

        // Append Images Types Array Inisde Tooltip Data
        // toolTipData.innerHTML = [...imagesTypes].join(', .');

        // When (drop-zoon) has (dragover) Event
        dropZoon.addEventListener('dragover', function(event) {
            // Prevent Default Behavior
            event.preventDefault();

            // Add Class (drop-zoon--over) On (drop-zoon)
            dropZoon.classList.add('drop-zoon--over');
        });

        // When (drop-zoon) has (dragleave) Event
        dropZoon.addEventListener('dragleave', function(event) {
            // Remove Class (drop-zoon--over) from (drop-zoon)
            dropZoon.classList.remove('drop-zoon--over');
        });

        // When (drop-zoon) has (drop) Event
        dropZoon.addEventListener('drop', function(event) {
            // Prevent Default Behavior
            event.preventDefault();

            // Remove Class (drop-zoon--over) from (drop-zoon)
            dropZoon.classList.remove('drop-zoon--over');

            // Select The Dropped File
            const file = event.dataTransfer.files[0];

            // Call Function uploadFile(), And Send To Her The Dropped File :)
            uploadFile(file);
        });

        // When (drop-zoon) has (click) Event
        // dropZoon.addEventListener('click', function(event) {
        //     // Click The (fileInput)
        //     fileInput.click();
        // });

        // When (fileInput) has (change) Event
        fileInput.addEventListener('change', function(event) {
            // Select The Chosen File
            const file = event.target.files[0];

            // Call Function uploadFile(), And Send To Her The Chosen File :)
            uploadFile(file);
        });

        // Upload File Function
        // function uploadFile(file) {
        //     // FileReader()
        //     const fileReader = new FileReader();
        //     // File Type
        //     const fileType = file.type;
        //     // File Size
        //     const fileSize = file.size;

        //     // If File Is Passed from the (File Validation) Function
        //     if (fileValidate(fileType, fileSize)) {
        //         // Add Class (drop-zoon--Uploaded) on (drop-zoon)
        //         dropZoon.classList.add('drop-zoon--Uploaded');

        //         // Show Loading-text
        //         loadingText.style.display = "block";
        //         // Hide Preview Image
        //         previewImage.style.display = 'none';

        //         // Remove Class (uploaded-file--open) From (uploadedFile)
        //         uploadedFile.classList.remove('uploaded-file--open');
        //         // Remove Class (uploaded-file__info--active) from (uploadedFileInfo)
        //         uploadedFileInfo.classList.remove('uploaded-file__info--active');

        //         // After File Reader Loaded
        //         fileReader.addEventListener('load', function() {
        //             // After Half Second
        //             setTimeout(function() {
        //                 // Add Class (upload-area--open) On (uploadArea)
        //                 uploadArea.classList.add('upload-area--open');

        //                 // Hide Loading-text (please-wait) Element
        //                 loadingText.style.display = "none";
        //                 // Show Preview Image
        //                 previewImage.style.display = 'block';

        //                 // Add Class (file-details--open) On (fileDetails)
        //                 fileDetails.classList.add('file-details--open');
        //                 // Add Class (uploaded-file--open) On (uploadedFile)
        //                 uploadedFile.classList.add('uploaded-file--open');
        //                 // Add Class (uploaded-file__info--active) On (uploadedFileInfo)
        //                 uploadedFileInfo.classList.add('uploaded-file__info--active');
        //             }, 500); // 0.5s

        //             // Add The (fileReader) Result Inside (previewImage) Source
        //             previewImage.setAttribute('src', fileReader.result);

        //             // Add File Name Inside Uploaded File Name
        //             uploadedFileName.innerHTML = file.name;

        //             // Call Function progressMove();
        //             progressMove();
        //         });

        //         // Read (file) As Data Url
        //         fileReader.readAsDataURL(file);
        //     } else { // Else

        //         this; // (this) Represent The fileValidate(fileType, fileSize) Function

        //     };
        // };

        // Progress Counter Increase Function
        function progressMove() {
            // Counter Start
            let counter = 0;

            // After 600ms
            setTimeout(() => {
                // Every 100ms
                let counterIncrease = setInterval(() => {
                    // If (counter) is equle 100
                    if (counter === 100) {
                        // Stop (Counter Increase)
                        clearInterval(counterIncrease);
                    } else { // Else
                        // plus 10 on counter
                        counter = counter + 10;
                        // add (counter) vlaue inisde (uploadedFileCounter)
                        uploadedFileCounter.innerHTML = `${counter}%`
                    }
                }, 100);
            }, 600);
        };


        // Simple File Validate Function
        function fileValidate(fileType, fileSize) {
            // File Type Validation
            let isImage = imagesTypes.filter((type) => fileType.indexOf(`image/${type}`) !== -1);

            // If The Uploaded File Type Is 'jpeg'
            if (isImage[0] === 'jpeg') {
                // Add Inisde (uploadedFileIconText) The (jpg) Value
                uploadedFileIconText.innerHTML = 'jpg';
            } else { // else
                // Add Inisde (uploadedFileIconText) The Uploaded File Type
                uploadedFileIconText.innerHTML = isImage[0];
            };

            // If The Uploaded File Is An Image
            if (isImage.length !== 0) {
                // Check, If File Size Is 2MB or Less
                if (fileSize <= 2000000) { // 2MB :)
                    return true;
                } else { // Else File Size
                    return alert('Please Your File Should be 2 Megabytes or Less');
                };
            } else { // Else File Type
                return alert('Please make sure to upload An Image File Type');
            };
        };

        // :)
</script> --}}
@endpush
