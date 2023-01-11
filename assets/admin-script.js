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
        console.log(popUpTog);
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
                        let btn = e.target.querySelector('button[type="submit"]');
                        let exHtml = btn.innerHTML;
                        btn.innerHTML = "<span class='working'></span>";

                        //Form Submit by Ajax
                        e.preventDefault();
                        let submitRoute = popUpForm.attr("action");
                        let fData = {
                            action: url + "_save",
                            data: $(popUpForm).serialize()
                        }
                        jQuery.post(ajax_object.ajax_url, fData, function (response) {
                            LoadData();
                            $("." + uID).remove();
                            btn.innerHTML = exHtml;
                        }, function (res) {
                            btn.innerHTML = exHtml;
                        }); //Post Data to server
                    });
                    $(".closePopup").on("click", function () {
                        $(this).closest(".popup-wrap").remove();
                    });
                });
            });
        });
    }
}