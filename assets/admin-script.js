let timeout;
let invoiceTotal = 0;
let countQty = 0;
//POPUP
class Popup {
    constructor($) {
        this.selectorClass = "popup";
        this.appendSelector = "body";
        $("." + this.selectorClass).off("click");
        this.dom = null;
        this.domExistingHtml = null;
        this.init($);
    }
    init($) {
        let popUpTog = document.querySelectorAll("." + this.selectorClass);
        var _this = this;
        popUpTog.forEach(function (el) {
            $(el).on("click", function (e) {
                _this.dom = $(this);
                _this.domExistingHtml = _this.dom.html();
                _this.dom.html("<span class='working'></span>");//<span class='data-loading'></span>
                e.preventDefault();
                let url = el.getAttribute("href");
                let w = el.getAttribute("data-w");
                let cls = el.getAttribute("data-class");
                if (!cls) {
                    cls = "";
                }
                let ccs = "";
                if (w) {
                    ccs = "width:" + w + "px";
                }
                //console.log(el.dataset);
                let data = {
                    'action': url
                };
                Object.assign(data, el.dataset);
                jQuery.post(ajax_object.ajax_url, data, function (response) {
                    // handle success
                    _this.dom.html(_this.domExistingHtml);
                    var uID = Date.now();
                    $(_this.appendSelector).append(
                        "<div class='popup-wrap " +
                        uID +
                        "'><div class='popup-body " + cls + "' style='" +
                        ccs +
                        "'><span class='closePopup'></span><div class='popup-inner'>" +
                        response +
                        "</div></div></div>"
                    );
                    let popUpForm = $("." + uID).find("form.ajx");
                    $(popUpForm).on("submit", (e) => {
                        $(".dataError").remove();
                        let btn = e.target.querySelector('button[type="submit"]');
                        let exHtml = btn.innerHTML;
                        btn.innerHTML = "<span class='working'></span>";

                        //Form Submit by Ajax
                        e.preventDefault();
                        //let submitRoute = popUpForm.attr("action");
                        let fData = {
                            action: url + "_save",
                            data: jQuery(popUpForm).serialize()
                        }
                        $.post(ajax_object.ajax_url, fData, (response) => {
                            btn.innerHTML = exHtml;
                            response = JSON.parse(response);
                            if (response.error) {
                                ntf(response.message, 'error');
                            } else {
                                jQuery("." + uID).remove();
                                window.location.reload();
                            }
                        }); //Post Data to server
                    });
                    jQuery(".closePopup").on("click", function () {
                        jQuery(this).closest(".popup-wrap").remove();
                    });
                });
            });
        });
    }
}

class Notification {
    constructor({ ...options }) {
        this.type = options.type || "success";
        this.message = options.message || "";
        this.timeout = options.timeout || 6000;
        //if not success then error and timeout should increse
        if (this.type !== "success") {
            this.timeout = this.timeout * 2;
        }
        this.bind();
    }
    build() {
        //element
        let singleElement = document.createElement("div");
        singleElement.classList.add("notification");
        singleElement.classList.add(this.type);
        if (this.type == 'alert' || this.type == 'warning') {
            singleElement.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="notification-icon" viewBox="0 0 512 512"><title>Warning</title><path d="M85.57 446.25h340.86a32 32 0 0028.17-47.17L284.18 82.58c-12.09-22.44-44.27-22.44-56.36 0L57.4 399.08a32 32 0 0028.17 47.17z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M250.26 195.39l5.74 122 5.73-121.95a5.74 5.74 0 00-5.79-6h0a5.74 5.74 0 00-5.68 5.95z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 397.25a20 20 0 1120-20 20 20 0 01-20 20z"/></svg>';
        } else if (this.type == 'success') {
            singleElement.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="notification-icon" viewBox="0 0 512 512"><title>Checkmark</title><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96"/></svg>';
        } else {
            singleElement.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="notification-icon" viewBox="0 0 512 512"><title>Close Circle</title><path d="M448 256c0-106-86-192-192-192S64 150 64 256s86 192 192 192 192-86 192-192z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M320 320L192 192M192 320l128-128"/></svg>';
        }
        //message
        let messageElement = document.createElement("div");
        messageElement.classList.add("message");
        messageElement.innerHTML = this.message;
        singleElement.appendChild(messageElement);
        //close
        let closeElement = document.createElement("div");
        closeElement.classList.add("close");
        closeElement.innerHTML = "&times;";
        closeElement.addEventListener("click", () => {
            singleElement.remove();
        });
        singleElement.appendChild(closeElement);
        singleElement.classList.add('slide-righr');
        return singleElement;
    }

    bind() {
        //check existance of notifications wraper
        let notificationsWrapper = document.querySelector(".notifications");
        if (!notificationsWrapper) {
            notificationsWrapper = document.createElement("div");
            notificationsWrapper.classList.add("notifications");
            document.body.appendChild(notificationsWrapper);
        }
        //append notification
        notificationsWrapper.appendChild(this.build());
        //remove notification after timeout
        setTimeout(() => {
            if (notificationsWrapper.firstChild) {
                notificationsWrapper.removeChild(notificationsWrapper.firstChild);
            }
        }, this.timeout);
    }

}

//Custom Dialog Popup
class DialogBox {
    constructor({ ...options }) {
        this.title = options.title || "Title Here";
        this.body = options.body || "Dialog Body Here";
        this.position = options.position || "center";
        this.actions = options.actions || [
            {
                label: "Ok",
                class: "btn-primary",
                callback: function (_this) {
                    _this.close();
                }
            }
        ];
        this.bind();
        return this;
    }
    build() {
        //build element
        let dialogBox = document.createElement("div");
        dialogBox.classList.add("dialog-box");
        //build header
        let header = document.createElement("div");
        header.classList.add("header");
        //Title wrap
        let titleWrap = document.createElement("div");
        titleWrap.classList.add("title-wrap");
        titleWrap.innerHTML = this.title;
        header.appendChild(titleWrap);
        //close button
        let closeButton = document.createElement("div");
        closeButton.classList.add("close-button");
        closeButton.innerHTML = "&times;";
        closeButton.addEventListener("click", () => {
            dialogBox.remove();
        });
        header.appendChild(closeButton);
        dialogBox.appendChild(header);
        //build body
        let body = document.createElement("div");
        body.classList.add("body");
        body.innerHTML = this.body;
        dialogBox.appendChild(body);
        //build actions
        if (this.actions.length > 0) {
            let actions = document.createElement("div");
            actions.classList.add("actions");
            this.actions.forEach((el) => {
                let action = document.createElement("div");
                action.classList.add("action");
                action.classList.add(el.className);
                action.innerHTML = el.label;
                action.addEventListener("click", () => {
                    el.callback(this);
                });
                actions.appendChild(action);
            });
            dialogBox.appendChild(actions);
        }
        this.dialogBox = dialogBox;
        return dialogBox;
    }
    bind() {
        //append dialog
        document.body.appendChild(this.build());
        //position dialog
        if (this.position == "center") {
            this.dialogBox.style.top = "50%";
            this.dialogBox.style.left = "50%";
            this.dialogBox.style.transform = "translate(-50%, -50%)";
        } else {
            //check position object or not
            //console.log(this.dialogBox.clientHeight);
            if (typeof this.position == "object") {
                this.dialogBox.style.top = (this.position.top - this.dialogBox.clientHeight) + "px";
                this.dialogBox.style.left = this.position.left + "px";
            }
        }

    }

    close() {
        this.dialogBox.remove();
    }
}

//Custom Confirm Class
/**
 * @param Object {title,Message,yes,no,yesCallback,noCallback}
 */
class ConfirmBox {
    constructor({ ...option }) {
        this.param = option.param || {};
        this.title = option.title || "Confirm";
        this.message = option.message || "Are you sure?";
        this.yes = option.yes || "Yes";
        this.no = option.no || "No";
        this.yesCallback = option.yesCallback || function () { };
        this.noCallback = option.noCallback || function () { };
        this.confirm();
    }

    confirm() {
        this.Ui();
        this.eventHandler();
    }

    Ui() {
        //Create Element
        let modal = document.createElement("div");
        modal.classList.add("confirm-modal");

        let modalBody = document.createElement("div");
        modalBody.classList.add("confirm-modal-body");

        let modalHeader = document.createElement("div");
        modalHeader.classList.add("confirm-modal-header");

        let modalTitle = document.createElement("div");
        modalTitle.classList.add("confirm-modal-title");
        modalTitle.innerHTML = this.title;

        let modalMessage = document.createElement("div");
        modalMessage.classList.add("confirm-modal-message");
        modalMessage.innerHTML = this.message;

        let modalFooter = document.createElement("div");
        modalFooter.classList.add("confirm-modal-footer");

        let modalYes = document.createElement("div");
        modalYes.classList.add("confirm-modal-yes");
        modalYes.innerHTML = this.yes;

        let modalNo = document.createElement("div");
        modalNo.classList.add("confirm-modal-no");
        modalNo.innerHTML = this.no;

        let modalClose = document.createElement("div");
        modalClose.classList.add("confirm-modal-close");
        modalClose.innerHTML = "&times;";
        //Append Element to Modal
        modal.appendChild(modalBody);
        modalBody.appendChild(modalHeader);
        modalHeader.appendChild(modalTitle);
        modalHeader.appendChild(modalClose);

        modalBody.appendChild(modalMessage);
        modalBody.appendChild(modalFooter);
        modalFooter.appendChild(modalYes);
        modalFooter.appendChild(modalNo);
        //Append Modal to Body
        document.body.appendChild(modal);
        //Append Event Listener to Close Button
        this.modalClose = modalClose;
        this.modalYes = modalYes;
        this.modalNo = modalNo;
        this.modal = modal;
    }

    //Event And Callback Handler
    eventHandler() {
        this.modalClose.addEventListener("click", () => {
            this.modal.remove();
        });
        //Append Event Listener to Yes Button
        this.modalYes.addEventListener("click", () => {
            this.yesCallback(this.param);
            this.modal.remove();
        });
        //Append Event Listener to No Button
        this.modalNo.addEventListener("click", () => {
            this.noCallback(this.param);
            this.modal.remove();
        });
    }
}

window.ntf = function (txt, cls) {
    new Notification({
        message: txt,
        type: cls
    });
};


function deleteData(_this) {
    new ConfirmBox({
        title: "Delete Confirmations",
        message: "Are you sure to Delete ?",
        yesCallback: function () {
            let data = {
                action: 'deleteData',
                id: _this.dataset.id,
                tablename: _this.dataset.type,
            }
            jQuery.post(ajax_object.ajax_url, data, function (response) {
                response = JSON.parse(response);
                if (response.error) {
                    ntf(response.message, 'error');
                } else {
                    _this.closest('tr').remove();
                    ntf(response.message);
                }
            });
        }
    });
}