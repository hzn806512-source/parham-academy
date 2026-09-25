/* ==========================================================================
   Parham Academy — app.js (vanilla, no build step)
   منوها • اعتبارسنجی فرم • راهنمای انیمیشنی • پخش‌کننده امن ویدیو
   ========================================================================== */

(function () {
    "use strict";

    var $  = function (sel, ctx) { return (ctx || document).querySelector(sel); };
    var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };

    /* ---------------------------------------------- 0) theme toggle ------- */
    function initTheme() {
        var STORAGE_KEY = "pa_theme";
        var root = document.documentElement;

        function apply(theme) {
            root.setAttribute("data-theme", theme);
            $$("[data-theme-toggle]").forEach(function (btn) {
                btn.setAttribute("aria-pressed", theme === "light" ? "true" : "false");
            });
        }

        var saved = "dark";
        try { saved = window.localStorage.getItem(STORAGE_KEY) || "dark"; } catch (e) {}
        apply(saved);

        $$("[data-theme-toggle]").forEach(function (btn) {
            btn.addEventListener("click", function () {
                var next = root.getAttribute("data-theme") === "light" ? "dark" : "light";
                apply(next);
                try { window.localStorage.setItem(STORAGE_KEY, next); } catch (e) {}
            });
        });
    }

    /* ---------------------------------------------- 1) public nav toggle -- */
    function initNav() {
        var toggle = $("[data-nav-toggle]");
        var nav    = $("[data-nav]");
        if (!toggle || !nav) { return; }

        toggle.addEventListener("click", function () {
            var open = nav.classList.toggle("is-open");
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
        });

        document.addEventListener("click", function (event) {
            if (!nav.classList.contains("is-open")) { return; }
            if (nav.contains(event.target) || toggle.contains(event.target)) { return; }
            nav.classList.remove("is-open");
            toggle.setAttribute("aria-expanded", "false");
        });
    }

    /* --------------------------------------- 2) panel/admin sidebar ------- */
    function initSidebar() {
        var toggle   = $("[data-sidebar-toggle]");
        var sidebar  = $("[data-sidebar]");
        var backdrop = $("[data-sidebar-backdrop]");
        if (!toggle || !sidebar) { return; }

        function setOpen(open) {
            sidebar.classList.toggle("is-open", open);
            if (backdrop) { backdrop.classList.toggle("is-open", open); }
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
            document.body.style.overflow = open && window.innerWidth < 900 ? "hidden" : "";
        }

        toggle.addEventListener("click", function () {
            setOpen(!sidebar.classList.contains("is-open"));
        });

        if (backdrop) { backdrop.addEventListener("click", function () { setOpen(false); }); }

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") { setOpen(false); }
        });

        $$(".sidebar-link", sidebar).forEach(function (link) {
            link.addEventListener("click", function () {
                if (window.innerWidth < 900) { setOpen(false); }
            });
        });
    }

    /* --------------------------------------------- 3) flash messages ------ */
    function initFlashes() {
        $$("[data-flash]").forEach(function (flash) {
            var close = $("[data-flash-close]", flash);
            var hide  = function () {
                flash.classList.add("is-hidden");
                window.setTimeout(function () { flash.remove(); }, 300);
            };
            if (close) { close.addEventListener("click", hide); }
            window.setTimeout(hide, 7000);
        });
    }

    /* ------------------------------------------ 4) reveal on scroll ------- */
    function initReveal() {
        var items = $$(".reveal");
        if (items.length === 0) { return; }

        if (!("IntersectionObserver" in window)) {
            items.forEach(function (item) { item.classList.add("is-visible"); });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) { return; }
                entry.target.classList.add("is-visible");
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.12, rootMargin: "0px 0px -40px 0px" });

        items.forEach(function (item) { observer.observe(item); });
    }

    /* --------------------------------------- 5) password helpers ---------- */
    function initPasswordTools() {
        $$("[data-password-toggle]").forEach(function (button) {
            button.addEventListener("click", function () {
                var input = $("input", button.parentNode);
                if (!input) { return; }
                input.type = input.type === "password" ? "text" : "password";
                button.classList.toggle("is-on");
            });
        });

        $$("[data-strength]").forEach(function (input) {
            var field = input.closest(".field");
            var bar   = field ? $("[data-strength-bar]", field) : null;
            if (!bar) { return; }
            var fill = $("span", bar);

            input.addEventListener("input", function () {
                var value = input.value;
                var score = 0;
                if (value.length >= 8)      { score += 1; }
                if (value.length >= 12)     { score += 1; }
                if (/[a-z]/.test(value) && /[A-Z]/.test(value)) { score += 1; }
                if (/[0-9]/.test(value))    { score += 1; }
                if (/[^A-Za-z0-9]/.test(value)) { score += 1; }

                var percent = Math.min(100, score * 20);
                fill.style.width = percent + "%";
                bar.classList.toggle("is-mid", score >= 2 && score < 4);
                bar.classList.toggle("is-good", score >= 4);
            });
        });
    }

    /* ------------------------------------------ 6) form validation -------- */
    function messageFor(input) {
        var value = input.value.trim();

        if (input.required && value === "") {
            return "این فیلد الزامی است.";
        }
        if (value === "") {
            return "";
        }

        var min = parseInt(input.getAttribute("minlength") || "0", 10);
        if (min > 0 && value.length < min) {
            return "حداقل " + min + " کاراکتر لازم است.";
        }

        var pattern = input.getAttribute("pattern");
        if (pattern && !new RegExp("^(?:" + pattern + ")$").test(value)) {
            return "قالب واردشده معتبر نیست.";
        }

        var matchName = input.getAttribute("data-match");
        if (matchName) {
            var other = input.form ? input.form.elements[matchName] : null;
            if (other && other.value !== input.value) {
                return "تکرار رمز عبور یکسان نیست.";
            }
        }

        return "";
    }

    function showError(input, message) {
        var field = input.closest(".field");
        if (!field) { return; }
        var box = $("[data-error]", field);
        field.classList.toggle("has-error", message !== "");
        if (box) { box.textContent = message; }
    }

    function initValidation() {
        $$("form[data-validate]").forEach(function (form) {
            var inputs = $$("input, select, textarea", form).filter(function (input) {
                return input.type !== "hidden" && input.type !== "file";
            });

            inputs.forEach(function (input) {
                input.addEventListener("blur", function () { showError(input, messageFor(input)); });
                input.addEventListener("input", function () {
                    var field = input.closest(".field");
                    if (field && field.classList.contains("has-error")) {
                        showError(input, messageFor(input));
                    }
                });
            });

            form.addEventListener("submit", function (event) {
                var firstInvalid = null;

                inputs.forEach(function (input) {
                    var message = messageFor(input);
                    showError(input, message);
                    if (message !== "" && firstInvalid === null) { firstInvalid = input; }
                });

                if (firstInvalid !== null) {
                    event.preventDefault();
                    firstInvalid.focus();
                    return;
                }

                var submit = $("button[type='submit']", form);
                if (submit) {
                    submit.disabled = true;
                    submit.dataset.label = submit.textContent;
                    submit.textContent = "در حال ارسال...";
                    window.setTimeout(function () {
                        submit.disabled = false;
                        submit.textContent = submit.dataset.label;
                    }, 8000);
                }
            });
        });
    }

    /* ------------------------------------- 7) destructive confirmation ---- */
    function initConfirm() {
        $$("form[data-confirm]").forEach(function (form) {
            form.addEventListener("submit", function (event) {
                if (!window.confirm(form.getAttribute("data-confirm"))) {
                    event.preventDefault();
                }
            });
        });
    }

    /* ------------------------------------------ 8) animated guide --------- */
    function initGuide() {
        var wrapper = $("[data-guide]");
        if (!wrapper) { return; }

        var steps    = $$("[data-guide-step]", wrapper);
        var progress = $("[data-guide-progress] .guide-progress-bar");
        var seen     = 1;

        function updateProgress() {
            if (!progress) { return; }
            progress.style.width = Math.round((seen / steps.length) * 100) + "%";
        }

        steps.forEach(function (step, index) {
            var head = $("[data-guide-toggle]", step);
            if (!head) { return; }

            head.addEventListener("click", function () {
                var isOpen = step.classList.contains("is-open");
                steps.forEach(function (other) { other.classList.remove("is-open"); });
                if (!isOpen) {
                    step.classList.add("is-open");
                    seen = Math.max(seen, index + 1);
                    updateProgress();
                    step.scrollIntoView({ behavior: "smooth", block: "nearest" });
                }
            });
        });

        updateProgress();
    }

    /* -------------------------------------------- 9) video player -------- */
    function initPlayer() {
        var player = $("[data-player]");
        if (!player) { return; }

        var overlay    = $("[data-player-error]");
        var reload     = $("[data-player-reload]");
        var ticketUrl  = player.getAttribute("data-ticket-url");
        var storageKey = "pa_pos_" + window.location.pathname;

        /* بارگذاری ناهمگام آدرس بدون نمایش در سورس HTML (Ctrl + U) */
        function loadStreamSource(autoplay) {
            if (!ticketUrl) { return; }
            fetch(ticketUrl, {
                method: "GET",
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(function (res) {
                if (!res.ok) { throw new Error("Stream denied"); }
                return res.json();
            })
            .then(function (data) {
                if (data && data.src) {
                    player.src = data.src;
                    if (autoplay) {
                        player.play().catch(function () {});
                    }
                } else {
                    if (overlay) { overlay.hidden = false; }
                }
            })
            .catch(function () {
                if (overlay) { overlay.hidden = false; }
            });
        }

        loadStreamSource(false);

        /* جلوگیری از میانبرهای مشاهده سورس و ذخیره در صفحه ویدیو */
        document.addEventListener("keydown", function (e) {
            if ((e.ctrlKey || e.metaKey) && (e.key === "u" || e.key === "U" || e.key === "s" || e.key === "S")) {
                e.preventDefault();
                return false;
            }
        });

        /* منع منوی راست‌کلیک و کشیدن فایل */
        player.addEventListener("contextmenu", function (event) { event.preventDefault(); });
        player.addEventListener("dragstart", function (event) { event.preventDefault(); });

        /* ادامهٔ پخش از محل قبلی (فقط سمت مرورگر) */
                player.addEventListener("loadedmetadata", function () {
            var saved = parseFloat(window.localStorage.getItem(storageKey) || "0");
            if (saved > 10 && saved < player.duration - 10) {
                var mins = Math.floor(saved / 60);
                var secs = Math.floor(saved % 60);
                var timeStr = (mins < 10 ? "0" : "") + mins + ":" + (secs < 10 ? "0" : "") + secs;

                setTimeout(function () {
                    showPromptToast({
                        title: "ادامه تماشای ویدیو",
                        message: "این ویدیو تا زمان «" + timeStr + "» دیده شده است. مایلید از همان ثانیه ادامه دهید؟",
                        confirmText: "ادامه از " + timeStr,
                        cancelText: "پخش از ابتدا",
                        onConfirm: function () {
                            player.currentTime = saved;
                            player.play().catch(function () {});
                        },
                        onCancel: function () {
                            player.currentTime = 0;
                        }
                    });
                }, 700);
            }
        });

        var lastSave = 0;
        player.addEventListener("timeupdate", function () {
            var now = Date.now();
            if (now - lastSave < 5000) { return; }
            lastSave = now;
            try { window.localStorage.setItem(storageKey, String(player.currentTime)); } catch (e) {}
        });

        player.addEventListener("ended", function () {
            try { window.localStorage.removeItem(storageKey); } catch (e) {}
        });

        /* خطای پخش = احتمالاً انقضای توکن یا تغییر دسترسی */
        player.addEventListener("error", function () {
            if (overlay) { overlay.hidden = false; }
        });

        if (reload) {
            reload.addEventListener("click", function () {
                if (overlay) { overlay.hidden = true; }
                loadStreamSource(true);
            });
        }

        /* کلیدهای میانبر پخش */
        document.addEventListener("keydown", function (event) {
            var tag = (event.target.tagName || "").toLowerCase();
            if (tag === "input" || tag === "textarea" || tag === "select") { return; }

            if (event.code === "Space" || event.key === "k") {
                event.preventDefault();
                if (player.paused) { player.play(); } else { player.pause(); }
            } else if (event.key === "ArrowRight") {
                player.currentTime = Math.max(0, player.currentTime - 5);
            } else if (event.key === "ArrowLeft") {
                player.currentTime = Math.min(player.duration || 0, player.currentTime + 5);
            } else if (event.key === "f") {
                if (player.requestFullscreen) { player.requestFullscreen(); }
            }
        });
    }

    /* ------------------------------------- 10) image fallback harness ----- */
    function initImageFallback() {
        $$("img[data-fallback]").forEach(function (img) {
            var wrap = img.parentNode;
            var placeholder = wrap ? $("[data-fallback-placeholder]", wrap) : null;
            img.addEventListener("error", function () {
                img.hidden = true;
                if (placeholder) { placeholder.hidden = false; }
            });
        });
    }

    /* ------------------------------------------------ 11) toast helper ---- */
    var toastEl = null, toastTimer = null;
    function showToast(message) {
        toastEl = toastEl || $("#toast");
        if (!toastEl) { return; }
        toastEl.textContent = message;
        toastEl.classList.add("is-on");
        window.clearTimeout(toastTimer);
        toastTimer = window.setTimeout(function () { toastEl.classList.remove("is-on"); }, 5500);
    }

    /* ------------------------------------------- 12) home contact form ---- */
    function initContactForm() {
        var form = $("#contactForm");
        if (!form) { return; }

        function fieldMessage(input) {
            var value = input.value.trim();
            if (input.required && value === "") { return "این فیلد الزامی است."; }
            var min = parseInt(input.getAttribute("minlength") || "0", 10);
            if (min > 0 && value !== "" && value.length < min) { return "حداقل " + min + " کاراکتر لازم است."; }
            return "";
        }

        function setFieldError(input, message) {
            var field = input.closest(".field");
            if (!field) { return; }
            var box = $("[data-error]", field);
            field.classList.toggle("has-error", message !== "");
            if (box) { box.textContent = message; }
        }

        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var inputs = $$("input, textarea", form);
            var firstInvalid = null;
            inputs.forEach(function (input) {
                var message = fieldMessage(input);
                setFieldError(input, message);
                if (message !== "" && firstInvalid === null) { firstInvalid = input; }
            });
            if (firstInvalid) { firstInvalid.focus(); return; }

            form.reset();
            showToast("متشکریم! برای هماهنگی سریع‌تر می‌توانید مستقیماً با شمارهٔ ۰۹۱۵۵۸۴۲۶۰۲ تماس بگیرید.");
        });
    }

    /* ------------------------------------------- 13) reveal-more lists ---- */
    function initRevealLists() {
        $$("[data-reveal-list]").forEach(function (list) {
            var step = parseInt(list.getAttribute("data-reveal-step") || "4", 10);
            var btn  = list.parentNode ? list.parentNode.querySelector("[data-reveal-more]") : null;
            if (!btn) { return; }

            function hiddenItems() {
                return Array.prototype.filter.call(list.children, function (li) {
                    return li.hasAttribute("hidden");
                });
            }

            function refresh() {
                btn.hidden = hiddenItems().length === 0;
            }

            btn.addEventListener("click", function () {
                hiddenItems().slice(0, step).forEach(function (li) { li.removeAttribute("hidden"); });
                refresh();
            });

            refresh();
        });
    }

    /* ------------------------------------------------------- 15) modals --- */
    function initModals() {
        function closeModal(modal) {
            modal.classList.remove("is-open");
            document.body.classList.remove("modal-open");
        }

        $$("[data-modal-open]").forEach(function (btn) {
            var modal = document.querySelector(btn.getAttribute("data-modal-open"));
            if (!modal) { return; }
            btn.addEventListener("click", function () {
                modal.classList.add("is-open");
                document.body.classList.add("modal-open");
            });
        });

        $$(".modal-overlay").forEach(function (modal) {
            modal.addEventListener("click", function (event) {
                if (event.target === modal) { closeModal(modal); }
            });
            $$("[data-modal-close]", modal).forEach(function (btn) {
                btn.addEventListener("click", function () { closeModal(modal); });
            });
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                $$(".modal-overlay.is-open").forEach(closeModal);
            }
        });
    }

    /* ------------------------------------- 14) searchable user select ----- */
    function initSearchableSelect() {
        $$("select[data-searchable]").forEach(function (select) {
            var box = document.createElement("input");
            box.type = "search";
            box.placeholder = "جستجو در فهرست...";
            box.className = "searchable-box";
            box.style.marginBottom = "8px";
            select.parentNode.insertBefore(box, select);

            var options = Array.prototype.slice.call(select.options);

            box.addEventListener("input", function () {
                var term = box.value.trim().toLowerCase();
                options.forEach(function (option) {
                    if (option.value === "") { return; }
                    var visible = term === "" || option.textContent.toLowerCase().indexOf(term) !== -1;
                    option.hidden = !visible;
                });
            });
        });
    }    /* ------------------------------ نوتیفیکیشن با صدا و تایمر ۱۵ ثانیه ---- */
    function playChimeSound() {
        try {
            var AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) { return; }
            var ctx = new AudioCtx();

            // نت اول (C5)
            var osc1 = ctx.createOscillator();
            var gain1 = ctx.createGain();
            osc1.type = "sine";
            osc1.frequency.setValueAtTime(523.25, ctx.currentTime);
            osc1.frequency.exponentialRampToValueAtTime(659.25, ctx.currentTime + 0.1);
            gain1.gain.setValueAtTime(0.09, ctx.currentTime);
            gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start();
            osc1.stop(ctx.currentTime + 0.5);

            // نت دوم هارمونیک و براق (G5)
            var osc2 = ctx.createOscillator();
            var gain2 = ctx.createGain();
            osc2.type = "sine";
            osc2.frequency.setValueAtTime(783.99, ctx.currentTime + 0.12);
            gain2.gain.setValueAtTime(0.07, ctx.currentTime + 0.12);
            gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.7);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start(ctx.currentTime + 0.12);
            osc2.stop(ctx.currentTime + 0.7);
        } catch (e) {}
    }

    function showPromptToast(options) {
        // بستن نوتیفیکیشن قبلی اگر باز باشد
        var old = $(".pa-prompt-toast");
        if (old) { old.remove(); }

        playChimeSound();

        var toast = document.createElement("div");
        toast.className = "pa-prompt-toast";
        toast.setAttribute("role", "alertdialog");

        toast.innerHTML =
            '<div class="pa-prompt-header">' +
                '<div class="pa-prompt-icon">✦</div>' +
                '<div class="pa-prompt-title">' + (options.title || "پیام آکادمی") + '</div>' +
            '</div>' +
            '<div class="pa-prompt-body">' + (options.message || "") + '</div>' +
            '<div class="pa-prompt-actions">' +
                '<button type="button" class="pa-prompt-btn-confirm">' + (options.confirmText || "بله، انتقال") + '</button>' +
                '<button type="button" class="pa-prompt-btn-cancel">' + (options.cancelText || "رد") + '</button>' +
            '</div>' +
            '<div class="pa-prompt-timer-bar"><div class="pa-prompt-timer-fill"></div></div>';

        document.body.appendChild(toast);

        window.requestAnimationFrame(function () {
            toast.classList.add("is-visible");
        });

        var timer = null;
        function closeToast() {
            if (timer) { clearTimeout(timer); }
            toast.classList.remove("is-visible");
            setTimeout(function () { toast.remove(); }, 350);
        }

        // انقضا و بسته شدن بعد از ۱۵ ثانیه
        timer = setTimeout(function () {
            closeToast();
            if (options.onCancel) { options.onCancel(); }
        }, 15000);

        var btnConfirm = toast.querySelector(".pa-prompt-btn-confirm");
        var btnCancel  = toast.querySelector(".pa-prompt-btn-cancel");

        btnConfirm.addEventListener("click", function () {
            closeToast();
            if (options.onConfirm) { options.onConfirm(); }
        });

        btnCancel.addEventListener("click", function () {
            closeToast();
            if (options.onCancel) { options.onCancel(); }
        });
    }
        /* ----------------------------------- 16) auto resume & remember ---- */
    function initAutoResume() {
        var body = document.body;
        var authUser = body.getAttribute("data-auth-user") || "";
        var authToken = body.getAttribute("data-auth-token") || "";

        if (authUser && authToken) {
            try {
                localStorage.setItem("pa_username", authUser);
                localStorage.setItem("pa_token", authToken);
            } catch (e) {}
        }

        var path = window.location.pathname + window.location.search;
        var ignored = ["/login", "/register", "/logout", "/api/auto-login"];
        var isIgnored = ignored.some(function (p) { return path.indexOf(p) === 0; });

        if (!isIgnored && path !== "/") {
            try {
                localStorage.setItem("pa_last_page", path);
            } catch (e) {}
        }

        if (!authUser) {
            var savedUser = "";
            var savedToken = "";
            try {
                savedUser = localStorage.getItem("pa_username") || "";
                savedToken = localStorage.getItem("pa_token") || "";
            } catch (e) {}

            if (savedUser && savedToken) {
                fetch("/api/auto-login", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ username: savedUser, token: savedToken })
                })
                .then(function (res) { return res.json(); })
                .then(function (res) {
                    if (res && res.ok) {
                        window.location.reload();
                    } else {
                        localStorage.removeItem("pa_username");
                        localStorage.removeItem("pa_token");
                    }
                })
                .catch(function () {});
            }
        }

        // نمایش نوتیفیکیشن انتخابی هنگام ورود مجدد به سایت به جای ریدایرکت اجباری
        var sessionFlag = false;
        try { sessionFlag = sessionStorage.getItem("pa_session_active") === "1"; } catch (e) {}

        if (!sessionFlag) {
            try { sessionStorage.setItem("pa_session_active", "1"); } catch (e) {}
            var lastTarget = "";
            try { lastTarget = localStorage.getItem("pa_last_page") || ""; } catch (e) {}

            if (lastTarget && lastTarget !== "/" && window.location.pathname === "/") {
                setTimeout(function () {
                    showPromptToast({
                        title: "ادامه یادگیری دوره",
                        message: "شما قبلاً در حال مشاهده صفحات آموزشی بودید. مایلید به آخرین صفحه بازگردید؟",
                        confirmText: "بله، انتقال به صفحه",
                        cancelText: "رد و ماندن در صفحه اصلی",
                        onConfirm: function () {
                            window.location.replace(lastTarget);
                        }
                    });
                }, 1000);
            }
        }

        $("form[action*='/logout']").forEach(function (form) {
            form.addEventListener("submit", function () {
                try {
                    localStorage.removeItem("pa_username");
                    localStorage.removeItem("pa_token");
                    localStorage.removeItem("pa_last_page");
                    sessionStorage.removeItem("pa_session_active");
                } catch (e) {}
            });
        });
    }


    /* ------------------------------------------------------- bootstrap --- */
    document.addEventListener("DOMContentLoaded", function () {
        initTheme();
        initNav();
        initSidebar();
        initFlashes();
        initReveal();
        initPasswordTools();
        initValidation();
        initConfirm();
        initGuide();
        initPlayer();
        initImageFallback();
        initContactForm();
        initRevealLists();
        initSearchableSelect();
    });
})();