if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

$.AdminBSB = {};
$.AdminBSB.options = {
    colors: {
        red: '#F44336',
        pink: '#E91E63',
        purple: '#9C27B0',
        deepPurple: '#673AB7',
        indigo: '#3F51B5',
        blue: '#2196F3',
        lightBlue: '#03A9F4',
        cyan: '#00BCD4',
        teal: '#009688',
        green: '#4CAF50',
        lightGreen: '#8BC34A',
        lime: '#CDDC39',
        yellow: '#ffe821',
        amber: '#FFC107',
        orange: '#FF9800',
        deepOrange: '#FF5722',
        brown: '#795548',
        grey: '#9E9E9E',
        blueGrey: '#607D8B',
        black: '#000000',
        white: '#ffffff'
    },
    leftSideBar: {
        scrollColor: 'rgba(0,0,0,0.5)',
        scrollWidth: '4px',
        scrollAlwaysVisible: false,
        scrollBorderRadius: '0',
        scrollRailBorderRadius: '0',
        scrollActiveItemWhenPageLoad: true,
        breakpointWidth: 1170
    },
    dropdownMenu: {
        effectIn: 'fadeIn',
        effectOut: 'fadeOut'
    }
}

/* Input - Function ========================================================================================================
*  You can manage the inputs(also textareas) with name of class 'form-control'
*  
*/
$.AdminBSB.input = {
    activate: function () {
        //On focus event
        $('.form-control').focus(function () {
            $(this).parent().addClass('focused');
        });

        //On focusout event
        $('.form-control').focusout(function () {
            var $this = $(this);
            if ($this.parents('.form-group').hasClass('form-float')) {
                if ($this.val() == '') { $this.parents('.form-line').removeClass('focused'); }
            }
            else {
                $this.parents('.form-line').removeClass('focused');
            }
        });

        $('input[name="type"].post-back').on('change', function () {
            let selected = $(this).val();
            window.location.href = '/' + selected;
        });
        //On label click
        $('body').on('click', '.form-float .form-line .form-label', function () {
            $(this).parent().find('input').focus();
        });
        $('body').on('click', '.form-float .form-line', function () {
            $('input[type="file"]').each(function () {
                var $el = $(this);
                var $fileLine = $el.parents('.form-line'); // ambil .form-line terdekat
                var $fileInfo = $fileLine.find('.file-upload #file-info #file-name'); // target file name
                var $removeBtn = $fileLine.find('.file-upload #remove-file'); // tombol remove (pastikan class sesuai)

                // Saat user pilih file
                $el.on('change', function () {
                    if (this.files && this.files.length > 0) {
                        $fileInfo.text(this.files[0].name);
                        $removeBtn.removeClass('hidden');
                        $fileLine.addClass('focused');
                    } else {
                        $fileInfo.text('');
                        $removeBtn.addClass('hidden');
                        $fileLine.removeClass('focused');
                    }
                });
            });
        });
        flatpickr('input[type="date"]', {
            dateFormat: "Y-m-d"
        });
        $('input[type="date"]').each(function () {
            var $el = $(this);

            if ($el.val()) {
                $el.addClass('has-value');
            }

            $el.on('change', function () {
                $(this).toggleClass('has-value', !!$(this).val());
            });
        });
        $('.form-control').each(function () {
            if ($(this).val() !== '') {
                $(this).parents('.form-line').addClass('focused');
            }
        });
    }
}
//==========================================================================================================================

/* Form - Select - Function ================================================================================================
*  You can manage the 'select' of form elements
*  
*/
$.AdminBSB.select = {
    activate: function () {
        $('select:not(.flatpickr-monthDropdown-months)').material_select();
        $('select:not(.flatpickr-monthDropdown-months)').on('change', function () {
            if ($(this).val() !== '') {
                $(this).parents('.form-line').addClass('focused');
            } else {
                $(this).parents('.form-line').removeClass('focused');
            }
        });
    }
}
//==========================================================================================================================

/* DropdownMenu - Function =================================================================================================
*  You can manage the dropdown menu
*  
*/

$.AdminBSB.dropdownMenu = {
    activate: function () {
        var _this = this;

        $('.dropdown, .dropup, .btn-group').on({
            "show.bs.dropdown": function () {
                var dropdown = _this.dropdownEffect(this);
                _this.dropdownEffectStart(dropdown, dropdown.effectIn);
            },
            "shown.bs.dropdown": function () {
                var dropdown = _this.dropdownEffect(this);
                if (dropdown.effectIn && dropdown.effectOut) {
                    _this.dropdownEffectEnd(dropdown, function () { });
                }
            },
            "hide.bs.dropdown": function (e) {
                var dropdown = _this.dropdownEffect(this);
                if (dropdown.effectOut) {
                    e.preventDefault();
                    _this.dropdownEffectStart(dropdown, dropdown.effectOut);
                    _this.dropdownEffectEnd(dropdown, function () {
                        dropdown.dropdown.removeClass('open');
                    });
                }
            }
        });

        // //Set Waves
        // Waves.attach('.dropdown-menu li a', ['waves-block']);
        // Waves.init();
    },
    dropdownEffect: function (target) {
        var effectIn = $.AdminBSB.options.dropdownMenu.effectIn, effectOut = $.AdminBSB.options.dropdownMenu.effectOut;
        var dropdown = $(target), dropdownMenu = $('.dropdown-menu', target);

        if (dropdown.length > 0) {
            var udEffectIn = dropdown.data('effect-in');
            var udEffectOut = dropdown.data('effect-out');
            if (udEffectIn !== undefined) { effectIn = udEffectIn; }
            if (udEffectOut !== undefined) { effectOut = udEffectOut; }
        }

        return {
            target: target,
            dropdown: dropdown,
            dropdownMenu: dropdownMenu,
            effectIn: effectIn,
            effectOut: effectOut
        };
    },
    dropdownEffectStart: function (data, effectToStart) {
        if (effectToStart) {
            data.dropdown.addClass('dropdown-animating');
            data.dropdownMenu.addClass('animated dropdown-animated');
            data.dropdownMenu.addClass(effectToStart);
        }
    },
    dropdownEffectEnd: function (data, callback) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        data.dropdown.one(animationEnd, function () {
            data.dropdown.removeClass('dropdown-animating');
            data.dropdownMenu.removeClass('animated dropdown-animated');
            data.dropdownMenu.removeClass(data.effectIn);
            data.dropdownMenu.removeClass(data.effectOut);

            if (typeof callback == 'function') {
                callback();
            }
        });
    }
}
//==========================================================================================================================

/* Browser - Function ======================================================================================================
*  You can manage browser
*  
*/
var edge = 'Microsoft Edge';
var ie10 = 'Internet Explorer 10';
var ie11 = 'Internet Explorer 11';
var opera = 'Opera';
var firefox = 'Mozilla Firefox';
var chrome = 'Google Chrome';
var safari = 'Safari';

$.AdminBSB.browser = {
    activate: function () {
        var _this = this;
        var className = _this.getClassName();

        if (className !== '') $('html').addClass(_this.getClassName());
    },
    getBrowser: function () {
        var userAgent = navigator.userAgent.toLowerCase();

        if (/edge/i.test(userAgent)) {
            return edge;
        } else if (/rv:11/i.test(userAgent)) {
            return ie11;
        } else if (/msie 10/i.test(userAgent)) {
            return ie10;
        } else if (/opr/i.test(userAgent)) {
            return opera;
        } else if (/chrome/i.test(userAgent)) {
            return chrome;
        } else if (/firefox/i.test(userAgent)) {
            return firefox;
        } else if (!!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/)) {
            return safari;
        }

        return undefined;
    },
    getClassName: function () {
        var browser = this.getBrowser();

        if (browser === edge) {
            return 'edge';
        } else if (browser === ie11) {
            return 'ie11';
        } else if (browser === ie10) {
            return 'ie10';
        } else if (browser === opera) {
            return 'opera';
        } else if (browser === chrome) {
            return 'chrome';
        } else if (browser === firefox) {
            return 'firefox';
        } else if (browser === safari) {
            return 'safari';
        } else {
            return '';
        }
    }
}
//==========================================================================================================================

$(function () {
    $.AdminBSB.browser.activate();
    $.AdminBSB.dropdownMenu.activate();
    $.AdminBSB.input.activate();
    $.AdminBSB.select.activate();

    window.Parsley.addValidator('fileextension', {
        requirementType: 'string',
        validateString: function (value, requirement, parsleyInstance) {
            var fileInput = parsleyInstance.$element[0];
            if (fileInput.files.length === 0) return true; // Tidak ada file, biar required yang tangani

            var allowedExtensions = requirement.split(',');
            var fileName = fileInput.files[0].name.toLowerCase();
            var extension = fileName.split('.').pop();

            return allowedExtensions.includes(extension);
        },
        messages: {
            en: 'Invalid file type. Allowed types: %s.',
            id: 'Tipe file tidak diizinkan. Hanya boleh: %s.'
        }
    });

    window.Parsley.addValidator('maxfilesize', {
        validateString: function (_value, maxSize, parsleyInstance) {
            if (!window.FormData) {
                alert("Browser tidak mendukung FormData API.");
                return true;
            }

            var files = parsleyInstance.$element[0].files;
            if (files.length === 0) {
                return true;
            }

            for (var i = 0; i < files.length; i++) {
                if (files[i].size > maxSize) {
                    return false;
                }
            }
            return true;
        },
        requirementType: 'integer',
        messages: {
            id: 'Ukuran file terlalu besar (maksimal %{requirement} byte).'
        }
    });
    window.Parsley.addValidator('phoneid', {
        requirementType: 'string',
        validateString: function (value) {
            // Hanya angka, mulai dengan 08, panjang 9–14 digit
            return /^08[0-9]{7,12}$/.test(value);
        },
        messages: {
            id: 'Nomor HP tidak valid. Gunakan format 08xxxxxxxxxx.'
        }
    });
    $(window).on("scroll", function () {
        let scrollTop = $(this).scrollTop();
        let maxScroll = 400; // batas scroll yang akan mempengaruhi scale
        let scale = 1 - Math.min(scrollTop / maxScroll, window.matchMedia("(max-width: 767px)").matches ? 0.3 : 0.5);
        // akan mengecil sampai 50% ukuran asli

        $(".image-top").css("transform", `scale(${scale})`);

        let blurAmount = Math.min(scrollTop / maxScroll, 10); // max blur 10px
        let opacity = Math.min(scrollTop / maxScroll, 0.8); // overlay max opacity 0.8

        $("#bgBlur")
            .css({
                "backdrop-filter": `blur(${blurAmount}px)`,
                "-webkit-backdrop-filter": `blur(${blurAmount}px)`,
                opacity: opacity,
            });
    });

    const fadeDiv = document.getElementById('fadeDiv');
    if (fadeDiv) {
        window.addEventListener('scroll', () => {
            const maxScroll = 200; // scroll sejauh ini akan 100% hilang
            const opacity = Math.max(0, 1 - window.scrollY / maxScroll);
            fadeDiv.style.opacity = opacity;
        });
    }

    // $(window).on('load', function () {
    //     $('.preloaderpage').addClass('hidden');
    //     $('body').removeClass('overflow-hidden');
    // });
});