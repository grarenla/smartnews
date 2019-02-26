const UPLOAD_IMG_URL = "https://api.cloudinary.com/v1_1/dqbat91l8/upload";
$(function () {

    CKEDITOR.replace('ckeditor');
    CKEDITOR.instances.ckeditor.on('contentDom', function () {
        CKEDITOR.instances.ckeditor.document.on('keyup', function () {
            console.log(CKEDITOR.instances.ckeditor.getData());
            validateContent(document.getElementById('ckeditor'));
        });
    });
    CKEDITOR.config.height = 300;


    Dropzone.options.frmFileUpload = {
        url: UPLOAD_IMG_URL,
        paramName: "file",
        params: {
            upload_preset: 'b3uy9rh5'
        },
        maxFiles: 1,
        addRemoveLinks: true,
        error: function (file, err) {
            console.log(err);
        },
        success: function (file, res) {
            console.log(res);
            var ip = document.createElement("input");
            ip.setAttribute("type", "hidden");
            ip.setAttribute("class", "urlImg");
            ip.setAttribute("name", file.name);
            ip.value = res.url;
            var urlImages = document.getElementById("url-images");
            urlImages.appendChild(ip);
        },

        init: function () {

            var _this = this;

            var inputs = document.getElementById("url-images").querySelectorAll(".edit-img");
            // console.log(inputs[0].value);
            for (var i = 0; i < inputs.length; i++) {
                var mockFile = {name: 'img', size: 1, type: 'image/jpeg'};
                _this.emit("addedfile", mockFile);
                _this.emit("thumbnail", mockFile, inputs[i].value);
                _this.emit("complete", mockFile);
                _this.files.push(mockFile);
            }
            this.on("success", function () {
                validateImages(document.getElementById("frm-file-upload"), getValueImages());
            });
            this.on("removedfile", function (file) {
                console.log(file.name);
                $("#url-images > input[name='" + file.name + "']").remove();
                validateImages(document.getElementById("frm-file-upload"), getValueImages());
            });
            this.on("maxfilesexceeded", function (file) {
                showNotification("alert-success", "Create News Successfully", "bottom", "right", "animated bounceIn", "animated bounceOut");
                alert("You can only choose 1 photo!");
                this.removeFile(file);
            });
        }
    };
});

$("#btn-submit").click(function () {

    var btn = this;

    var images = getValueImages();

    var forms = document.forms['form_news'];
    var title = validateTitle(forms['title']);
    var description = validateDescription(forms['description']);
    var content = validateContent(forms['content']);
    var source = validateSource(forms['source']);
    var author = validateAuthor(forms['author']);
    var category = validateCategory(forms['category']);
    var img = validateImages(document.getElementById("frm-file-upload"), images);

    console.log(img);
    if (title && description && content && source && author && category && img) {
        createButtonLoader(btn);
        var productData = {
            "title": forms['title'].value,
            "description": forms['description'].value,
            "content": CKEDITOR.instances.ckeditor.getData(),
            "source": forms['source'].value,
            "author": forms['author'].value,
            "category_id": forms['category'].value,
            "img": images[images.length - 1]
        };
        console.log(productData);
        var req = new XMLHttpRequest();
        req.open("POST", "/api/news");
        req.setRequestHeader("Content-Type", "application/json");
        req.onload = function () {
            console.log(this.responseText);
            console.log(req.status);
            if (req.status === 200 || req.status === 201) {
                showNotification("alert-success", "Create News Successfully", "bottom", "right", "animated bounceIn", "animated bounceOut");
                setTimeout(function () {
                    location.href = '/news';
                }, 800);

            } else {
                console.log(this.responseText);
                showNotification("alert-danger", "Create News Fail", "bottom", "right", "animated bounceIn", "animated bounceOut");
                creatAlertResponseErrorServer(this);
            }
        };
        req.onerror = function () {
            console.log(req.status);
        };
        req.onloadend = function () {
            btn.removeAttribute("disabled");
            btn.innerHTML = "SUBMIT";
        };
        req.send(JSON.stringify(productData));
    }
});

function showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit) {
    if (colorName === null || colorName === '') { colorName = 'bg-black'; }
    if (text === null || text === '') { text = 'Turning standard Bootstrap alerts'; }
    if (animateEnter === null || animateEnter === '') { animateEnter = 'animated fadeInDown'; }
    if (animateExit === null || animateExit === '') { animateExit = 'animated fadeOutUp'; }
    var allowDismiss = true;

    $.notify({
            message: text
        },
        {
            type: colorName,
            allow_dismiss: allowDismiss,
            newest_on_top: true,
            timer: 1000,
            placement: {
                from: placementFrom,
                align: placementAlign
            },
            animate: {
                enter: animateEnter,
                exit: animateExit
            },
            template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });
}

function createButtonLoader(e) {
    e.setAttribute("disabled", "");
    e.innerHTML = "<div class=\"preloader pl-size-xs\">" + "<div class=\"spinner-layer pl-grey\">"
        + "<div class=\"circle-clipper left\">" + "<div class=\"circle\"></div>" + "</div>"
        + "<div class=\"circle-clipper right\">" + "<div class=\"circle\"></div>"
        + "</div>" + "</div>" + " </div>";
}

var validateTitle = function (elm) {
    console.log(elm);
    var alert = elm.parentElement.parentElement.querySelector("label");
    if (checkNull(elm.value)) {
        createAlert(alert, elm, "Please enter Title.");
        return false;
    } else {
        removeAlert(alert, elm);
        return true;
    }
};

var validateSource = function (elm) {
    var alert = elm.parentElement.parentElement.querySelector("label");
    if (checkNull(elm.value)) {
        createAlert(alert, elm, "Please enter Source.");
        return false;
    } else {
        removeAlert(alert, elm);
        return true;
    }
};

var validateAuthor = function (elm) {
    var alert = elm.parentElement.parentElement.querySelector("label");
    if (checkNull(elm.value)) {
        createAlert(alert, elm, "Please enter Author.");
        return false;
    } else {
        removeAlert(alert, elm);
        return true;
    }
};

var validateDescription = function (elm) {
    var alert = elm.parentElement.parentElement.querySelector("label");
    if (checkNull(elm.value)) {
        createAlert(alert, elm, "Please enter Description.");
        return false;
    } else {
        removeAlert(alert, elm);
        return true;
    }
};

var validateCategory = function (elm) {

    console.log(elm);
    var alert = elm.parentElement.parentElement.parentElement.querySelector("label");
    if (checkNull(elm.value)) {
        createAlert(alert, elm.parentElement, "Please choice Category.");
        return false;
    } else {
        removeAlert(alert, elm.parentElement);
        return true;
    }
};

var validateContent = function (elm) {
    var alert = elm.parentElement.parentElement.querySelector("label");
    if (checkNull(CKEDITOR.instances.ckeditor.getData())) {
        createAlert(alert, elm, "Please enter Content.");
        return false;
    } else {
        removeAlert(alert, elm);
        return true;
    }
};

var validateImages = function (elm, images) {
    var alert = elm.parentElement.querySelector("label");
    if (images.length === 0) {
        createAlert(alert, elm, "Please choice Images.");
        return false;
    } else {
        removeAlert(alert, elm);
        return true;
    }
};

var getValueImages = function () {
    var inputUrlImg, images = [];
    if ($('#url-images').html() !== "" || $('#url-images').html() !== null) {
        inputUrlImg = document.getElementsByClassName("urlImg");
        for (var i = 0; i < inputUrlImg.length; i++) {
            images.push(inputUrlImg[i].value);
        }
    }
    return images;
};

function checkNull(value) {
    var regex = XRegExp('^\\s+$');
    if (value == null || regex.test(value) || value === "") {
        return true;
    }
    return false;
}

function createAlert(alert, elm, str) {
    alert.innerHTML = str;
    alert.className = "error";
    if (elm.parentElement.className.includes('form-line')) {
        if (elm.parentElement.className.includes("success")) {
            elm.parentElement.className = elm.parentElement.className.replace(" success", "");
        }
        if (!elm.parentElement.className.includes("error")) {
            elm.parentElement.className += " error";
        }
    }
}

function removeAlert(alert, elm) {
    alert.removeAttribute('class');
    alert.innerHTML = "";
    if (elm.parentElement.className.includes('form-line')) {
        if (elm.parentElement.className.includes("error")) {
            elm.parentElement.className = elm.parentElement.className.replace(" error", "");
        }
        if (!elm.parentElement.className.includes("success")) {
            elm.parentElement.className += " success";
        }
    }
}

function creatAlertResponseErrorServer(e) {
    var alertError = document.getElementById("alert-error");
    if(!alertError.className.includes("alert bg-red")){
        alertError.className = "alert bg-red";
    }
    alertError.innerHTML = JSON.parse(e.responseText).message;

    if(document.getElementById('code').className.includes("success")){
        document.getElementById('code').className = document.getElementById('code').className.replace("success", "error");
    }
}